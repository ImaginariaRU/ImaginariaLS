<?php

/**
 * Обрабатывает авторизацию через OpenId/oAuth
 *
 */
class PluginAutoopenid_ActionLogin extends ActionPlugin
{
    /**
     * Текущий пользователь
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;

    /**
     * Инициализация
     *
     * @return null
     */
    public function Init()
    {
        /**
         * Получаем текущего пользователя
         */
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^login$/i', '/^oauth$/i', '/^[\w\-\_]{1,200}$/i', '/^$/i', 'EventLoginOauth');
        $this->AddEventPreg('/^login$/i', '/^data$/i', '/^$/i', 'EventData');
        $this->AddEventPreg('/^login$/i', '/^confirm$/i', '/^$/i', 'EventConfirmMail');
        $this->AddEventPreg('/^login$/i', '/^migration$/i', '/^$/i', 'EventMigration');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */


    /**
     * Обработка редиректа авторизации OAuth
     */
    protected function EventLoginOauth()
    {
        $sService = $this->GetParam(1);
        /**
         * Получаем сервис
         */
        if (!$oService = $this->PluginAutoopenid_Main_GetService($sService)) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
            return Router::Action('error');
        }
        /**
         * Получаем токен
         */
        if (!$oToken = $this->PluginAutoopenid_Main_GetRequestToken($oService)) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
            return Router::Action('error');
        }
        /**
         * Получаем данные
         */
        if (!$oService->loadDataDetail()) {
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
            return Router::Action('error');
        }
        $sId = $oService->getId();
        $sType = $oService->getServiceType();
        /**
         * Смотрим пользователя
         */
        $oOpenidUser = $this->PluginAutoopenid_Main_GetUserByFilter(array(
            'service_type' => $sType,
            'service_id'   => $sId
        ));
        if ($oOpenidUser and $oOpenidUser->getUser()) {
            if ($this->oUserCurrent) {
                $this->Message_AddErrorSingle($this->Lang_Get('plugin.autoopenid.errors.account_use'), null, true);
                $this->RedirectBack(false);
            }
            /**
             * Обновляем токен
             */
            $oOpenidUser->setToken($oToken->getAccessToken());
            $aDataUser = $oOpenidUser->getData();
            $aDataUser['token'] = $oToken->getAccessToken();
            $aDataUser['token_refresh'] = $oToken->getRefreshToken();
            $aDataUser['token_extra'] = $oToken->getExtraParams();
            $oOpenidUser->setData($aDataUser);
            $oOpenidUser->Update();

            $oUser = $oOpenidUser->getUser();
            if (!$oUser->getActivate()) {
                $this->Message_AddErrorSingle($this->Lang_Get('user_not_activated',
                    array('reactivation_path' => Router::GetPath('login') . 'reactivation')), null, true);
            } else {
                /**
                 * Авторизуем
                 */
                $this->User_Authorization($oUser);
            }
            /**
             * Редиректим обратно
             */
            $this->RedirectBack();
        } else {
            if ($oOpenidUser) {
                /**
                 * Старая запись о несуществующем пользователе - удаляем
                 */
                $oOpenidUser->Delete();
                $oOpenidUser = null;
            }
            /**
             * Создаем набор временных данных
             */
            $aData = array(
                'id'            => $oService->getId(),
                'mail'          => $oService->getMail(),
                'login'         => $oService->getLogin() ? $oService->getLogin() : $oService->generateLogin(),
                'link'          => $oService->getLink(),
                'name_short'    => $oService->getNameShort(),
                'name_full'     => $oService->getNameFull(),
                'name_display'  => $oService->getNameDisplay(),
                'avatar'        => $oService->getAvatar(),
                'photo'         => $oService->getPhoto(),
                'gender'        => $oService->getGender(),
                'token'         => $oToken->getAccessToken(),
                'token_refresh' => $oToken->getRefreshToken(),
                'token_extra'   => $oToken->getExtraParams(),
            );

            if ($this->oUserCurrent) {
                /**
                 * Пользователь дополнительно привязывает к себе новый аккаунт для входа
                 */
                $oOpenidUser = Engine::GetEntity('PluginAutoopenid_ModuleMain_EntityUser');
                $oOpenidUser->setUserId($this->oUserCurrent->getId());
                $oOpenidUser->setServiceId($sId);
                $oOpenidUser->setServiceType($sType);
                $oOpenidUser->setDate(date("Y-m-d H:i:s"));
                $oOpenidUser->setToken($aData['token']);
                $oOpenidUser->setData($aData);
                $oOpenidUser->Add();
                $this->RedirectBack();
                exit();
            }

            $oTmp = Engine::GetEntity('PluginAutoopenid_ModuleMain_EntityTmp');
            $oTmp->setKey(func_generator(32));
            $oTmp->setServiceId($sId);
            $oTmp->setServiceType($sType);
            $oTmp->setData($aData);
            $oTmp->setDate(date("Y-m-d H:i:s"));
            $oTmp->Add();

            setcookie('openidkey', $oTmp->getKey(), time() + Config::Get('plugin.autoopenid.time_key_limit'),
                Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
            Router::Location(Router::GetPath('login/autoopenid/data'));
        }
        $this->SetTemplate(false);
    }


    protected function RedirectBack($bSkipRegLogin = true)
    {
        $sUrl = $this->PluginAutoopenid_Main_RetrieveReferalUrl();
        if ($bSkipRegLogin) {
            $aPart = @parse_url($sUrl);
            if (isset($aPart['path']) and (strpos($aPart['path'], 'registration') or strpos($aPart['path'], 'login'))) {
                $sUrl = Config::Get('path.root.web');
            }
        }
        Router::Location($sUrl);
    }

    /**
     * Подтверждение email для связи с OpenId
     */
    protected function EventConfirmMail()
    {
        /**
         * Проверяем валидность ключа подтверждения почты
         */
        if (!($oKey = $this->PluginAutoopenid_Main_GetTmpByConfirmMailKey(getRequestStr('confirm_key')))) {
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.autoopenid.errors.confirm_mail_key_no_valid'));
            return Router::Action('error');
        }
        $aData = $oKey->getData();
        /**
         * Если пользователь подтвердил связь с Openid
         */
        if (isset($_REQUEST['submit_confirm'])) {
            $this->Security_ValidateSendForm();
            /**
             * А не занят ли уже Openid?
             */
            if ($this->PluginAutoopenid_Main_GetUserByFilter(array(
                'service_id'   => $oKey->getServiceId(),
                'service_type' => $oKey->getServiceType()
            ))
            ) {
                $this->Message_AddErrorSingle($this->Lang_Get('plugin.autoopenid.errors.confirm_mail_busy'));
                return Router::Action('error');
            }
            /**
             * Есть ли пользователь с таким емайлом
             */
            if (!($oUser = $this->User_GetUserByMail($oKey->getConfirmMail()))) {
                $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
                return Router::Action('error');
            }
            /**
             * Привязываем OpenId к аккаунту
             */
            $oOpenidUser = Engine::GetEntity('PluginAutoopenid_ModuleMain_EntityUser');
            $oOpenidUser->setUserId($oUser->getId());
            $oOpenidUser->setServiceId($oKey->getServiceId());
            $oOpenidUser->setServiceType($oKey->getServiceType());
            $oOpenidUser->setDate(date("Y-m-d H:i:s"));
            $oOpenidUser->setToken(isset($aData['token']) ? $aData['token'] : null);
            $oOpenidUser->setData($aData);
            $oOpenidUser->Add();
            /**
             * Удаляем временные данные
             */
            $oKey->Delete();
            setcookie('openidkey', '', 1, Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));
            /**
             * Авторизуем
             */
            $this->User_Authorization($oUser, true);
            $this->RedirectBack();
            /**
             * Если пользователь отказался подтверждать связь с Openid
             */
        } elseif (isset($_REQUEST['submit_cancel'])) {
            $this->Security_ValidateSendForm();
            /**
             * Удаляем временные данные
             */
            $oKey->Delete();
            $this->RedirectBack(false);
        }
        /**
         * Отображаем форму подтверждения
         */
        $this->Viewer_Assign('oKey', $oKey);
        /**
         * Загружаем в шаблон e-mail полученный от OpenID провайдера
         */
        if (isset($aData['name_display'])) {
            $this->Viewer_Assign('sNameDisplay', $aData['name_display']);
        }
        $this->SetTemplateAction('confirm_mail');
    }


    protected function EventData()
    {
        $this->SetTemplateAction('data');
        /**
         * Проверяем наличие временного ключа в куках
         */
        $bKeyValid = false;
        if (isset($_COOKIE['openidkey']) and $sKey = $_COOKIE['openidkey'] and is_string($sKey)) {
            if ($oKey = $this->PluginAutoopenid_Main_GetTmpByKey($sKey)) {
                if (strtotime($oKey->getDate()) >= time() - Config::Get('plugin.autoopenid.time_key_limit')) {
                    // ключ валиден
                    $bKeyValid = true;
                }
            }
        }
        /**
         * Если ключ не валиден
         */
        if (!$bKeyValid) {
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.autoopenid.errors.data_key_no_valid'));
            return Router::Action('error');
        }
        /**
         * Если есть связь с OpenId, то авторизуем
         */
        if ($oOpenidUser = $this->PluginAutoopenid_Main_GetUserByFilter(array(
            'service_id'   => $oKey->getServiceId(),
            'service_type' => $oKey->getServiceType()
        ))
        ) {
            $this->User_Authorization($oOpenidUser->getUser());
            $this->RedirectBack();
        }
        /**
         * Устанавливаем дефолтное значение полей
         */
        $aData = $oKey->getData();
        if (!$aData) {
            return $this->EventErrorDebug();
        }
        if (!isset($_REQUEST['submit_data']) and !isset($_REQUEST['submit_mail'])) {
            if (isset($aData['login'])) {
                $_REQUEST['login'] = $aData['login'];
            }
            if (isset($aData['mail'])) {
                $_REQUEST['mail'] = $aData['mail'];
            }
        }
        $bAutoRegistration = Config::Get('plugin.autoopenid.auto_registration');
        /**
         * Отправили форму с даными для нового пользователя
         */
        if (isset($_REQUEST['submit_data']) or ($bAutoRegistration and !isset($_REQUEST['submit_mail']))) {
            $sLogin = getRequestStr('login');
            $sMail = getRequestStr('mail');

            $oUser = Engine::GetEntity('ModuleUser_EntityUser');
            $oUser->_setValidateScenario('registration');

            $aFields = array('login');
            $oUser->setLogin($sLogin);
            $oUser->setMail(null);

            if (Config::Get('plugin.autoopenid.mail_required') or $sMail) {
                $oUser->setMail($sMail);
                $aFields[] = 'mail';
            }
            /**
             * Валидируем поля
             */
            $oUser->_Validate($aFields);
            /**
             * Возникли ошибки?
             */
            if ($oUser->_hasValidateErrors()) {
                /**
                 * Получаем ошибки
                 */
                $this->Viewer_AssignAjax('aErrors', $oUser->_getValidateErrors());
            }
            /**
             * Если всё ок
             */
            if (!$oUser->_hasValidateErrors()) {
                $sPassword = func_generator(7);
                $oUser->setPassword(func_encrypt($sPassword));
                $oUser->setDateRegister(date("Y-m-d H:i:s"));
                $oUser->setIpRegister(func_getIp());
                /**
                 * Если используется активация, то генерим код активации
                 */
                if (Config::Get('general.reg.activation') and $oUser->getMail()) {
                    $oUser->setActivate(0);
                    $oUser->setActivateKey(md5(func_generator() . time()));
                } else {
                    $oUser->setActivate(1);
                    $oUser->setActivateKey(null);
                }
                /**
                 * Регистрируем
                 */
                if ($this->User_Add($oUser)) {
                    $this->Hook_Run('registration_after', array('oUser' => $oUser));
                    /**
                     * Подписываем пользователя на дефолтные события в ленте активности
                     */
                    $this->Stream_switchUserEventDefaultTypes($oUser->getId());
                    $oUser = $this->User_GetUserById($oUser->getId());
                    /**
                     * Заполняем дополнительные данные профиля
                     */
                    $this->UpdateUserProfile($oUser, $oKey);
                    /**
                     * Создаём связь пользователя с OpenId
                     */
                    $oOpenidUser = Engine::GetEntity('PluginAutoopenid_ModuleMain_EntityUser');
                    $oOpenidUser->setUserId($oUser->getId());
                    $oOpenidUser->setServiceId($oKey->getServiceId());
                    $oOpenidUser->setServiceType($oKey->getServiceType());
                    $oOpenidUser->setDate(date("Y-m-d H:i:s"));
                    $oOpenidUser->setToken($oKey->getDataOne('token'));
                    $oOpenidUser->setData($aData);
                    $oOpenidUser->Add();
                    /**
                     * Удаляем временные данные
                     */
                    $oKey->Delete();
                    setcookie('openidkey', '', 1, Config::Get('sys.cookie.path'), Config::Get('sys.cookie.host'));

                    /**
                     * Если стоит регистрация с активацией то проводим её
                     */
                    if (Config::Get('general.reg.activation') and $oUser->getMail()) {
                        /**
                         * Отправляем на мыло письмо о подтверждении регистрации
                         */
                        $this->Notify_SendRegistrationActivate($oUser, $sPassword);
                        Router::Location(Router::GetPath('registration') . 'confirm/');
                    } else {
                        /**
                         * Уведомление об успешной регистрации
                         */
                        if ($oUser->getMail()) {
                            $this->Notify_SendRegistration($oUser, $sPassword);
                        }
                        /**
                         * Авторизуем
                         */
                        $this->User_Authorization($oUser, false);
                        $this->RedirectBack();
                    }
                }
            } else {
                foreach ($oUser->_getValidateErrors() as $sField => $aErrors) {
                    foreach ($aErrors as $sError) {
                        $this->Message_AddError($sError);
                    }
                }
            }
            /**
             * Отправили форму для существующего пользователя
             */
        } elseif (isset($_REQUEST['submit_mail'])) {
            $_REQUEST['submit_mail'] = 1;
            /**
             * Проверяем есть ли пользователь с таким email, если есть то отправляем ему код активации текущего OpenId
             */
            if (getRequestStr('mail') and $oUser = $this->User_GetUserByMail(getRequestStr('mail'))) {
                /**
                 * Генерируем ключ подтверждения
                 */
                $oKey->setConfirmMail($oUser->getMail());
                $oKey->setConfirmMailKey(func_generator(32));
                $oKey->Update();
                /**
                 * Отправляем уведомление с активацией
                 */
                $this->Notify_Send(
                    $oUser,
                    'email.confirm_mail.tpl',
                    $this->Lang_Get('plugin.autoopenid.confirm_mail_subject'),
                    array(
                        'oKey'         => $oKey,
                        'sNameDisplay' => isset($aData['name_display']) ? $aData['name_display'] : $oKey->getServiceId()
                    ),
                    __CLASS__
                );
                /**
                 * Показываем сообщение о том, что письмо отправлено
                 */
                $this->SetTemplateAction('confirm_wait');
            } else {
                $this->Message_AddError($this->Lang_Get('plugin.autoopenid.errors.mail_not_found'),
                    $this->Lang_Get('error'));
            }
        }
    }

    /**
     * Заполняем дополнительные данные профиля
     */
    protected function UpdateUserProfile($oUser, $oTmp)
    {
        /**
         * Пол
         */
        $oUser->setProfileSex(in_array($oTmp->getDataOne('gender'), array(
            PluginAutoopenid_ModuleMain::GENDER_TYPE_INDEFINITE,
            PluginAutoopenid_ModuleMain::GENDER_TYPE_FEMALE,
            PluginAutoopenid_ModuleMain::GENDER_TYPE_MALE
        )) ? $oTmp->getDataOne('gender') : PluginAutoopenid_ModuleMain::GENDER_TYPE_INDEFINITE);
        /**
         * Имя в профиле
         */
        $oUser->setProfileName($oTmp->getDataOne('name_full'));
        /**
         * Аватар
         */
        if ($sFileTmp = $this->PluginAutoopenid_Main_DownloadTmpFile($oTmp->getDataOne('avatar'))) {
            if ($sPath = $this->User_UploadAvatar($sFileTmp, $oUser)) {
                $oUser->setProfileAvatar($sPath);
            }
        }
        /**
         * Фото
         */
        if ($sFileTmp = $this->PluginAutoopenid_Main_DownloadTmpFile($oTmp->getDataOne('photo'))) {
            if ($sPath = $this->User_UploadFoto($sFileTmp, $oUser)) {
                $oUser->setProfileFoto($sPath);
            }
        }
        $this->User_Update($oUser);
    }

    /**
     * Перенос данных на новую версию плагина
     */
    protected function EventMigration()
    {
        $bTableOldExists = $this->Database_isTableExists('prefix_openid');
        $this->Viewer_Assign('bTableOldExists', $bTableOldExists);
        $this->SetTemplateAction('migration');

        if (isPost('submit_migration')) {
            set_time_limit(0);
            $aMap = array(
                'vk' => 'vkontakte',
                'fb' => 'facebook',
                'ok' => 'odnoklassniki',
            );
            $iCount = 0;
            $iPage = 1;
            /**
             * Получаем старые аккаунты
             */
            while ($aOpenidItems = $this->PluginAutoopenid_Main_GetOpenidOldItemsByFilter(array(
                    '#where' => array(
                        'openid LIKE ? or openid LIKE ? or openid LIKE ? ' => array(
                            'fb_%',
                            'vk_%',
                            'ok_%'
                        )
                    ),
                    '#page'  => array(
                        $iPage,
                        100
                    ),
                    '#order' => array('user_id' => 'asc')
                )) and $aOpenidItems['collection']) {
                $iPage++;

                foreach ($aOpenidItems['collection'] as $oOpenid) {
                    $sServieType = null;
                    $sServiceId = null;
                    if (preg_match('#^(fb|vk|ok)_(\w+)$#i', $oOpenid->getOpenid(), $aMatch)) {
                        $sServieType = $aMap[$aMatch[1]];
                        $sServiceId = $aMatch[2];
                        /**
                         * Проверяем на существование
                         */
                        if (!$this->PluginAutoopenid_Main_GetUserByFilter(array(
                            'service_type' => $sServieType,
                            'service_id'   => $sServiceId
                        ))
                        ) {
                            /**
                             * Создаём связь
                             */
                            $oOpenidUser = Engine::GetEntity('PluginAutoopenid_ModuleMain_EntityUser');
                            $oOpenidUser->setUserId($oOpenid->getUserId());
                            $oOpenidUser->setServiceId($sServiceId);
                            $oOpenidUser->setServiceType($sServieType);
                            $oOpenidUser->setDate($oOpenid->getDate());
                            $oOpenidUser->setToken($oOpenid->getToken());
                            $oOpenidUser->setData(array());
                            $oOpenidUser->Add();
                            $iCount++;
                        }
                    }
                }
            }
            $this->Message_AddNotice($this->Lang_Get('plugin.autoopenid.migration.migrate_complete',
                array('count' => $iCount)));
        }
    }
}