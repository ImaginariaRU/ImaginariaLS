<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.0)
 *   Plugin Role (v.0.6)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */

class PluginRole_ActionPeople extends ActionPlugin
{

    protected $sMenuItemSelect = 'people';
    protected $oUserCurrent = null;
    protected $aUserRole = null;

    public function Init()
    {
        $this->oUserCurrent = $this->User_GetUserCurrent();
        $this->aUserRole = $this->oUserCurrent->getRole();

        if ($this->oUserCurrent and ($this->oUserCurrent->isAdministrator() or !empty($this->aUserRole['user']))) {

        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return Router::Action('error');
        }
    }

    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^settings$/i', '/^(page(\d+))?$/i', 'EventSettings');
        $this->AddEventPreg('/^settings$/i', '/^users$/i', 'EventUsers');
        $this->AddEventPreg('/^settings$/i', '/^users$/i', '/^[\w\-\_]+$/i', 'EventUsers');
        $this->AddEventPreg('/^settings$/i', '/^ajaxdeleteapeople$/i', 'EventAjaxDelete');
        $this->AddEventPreg('/^settings$/i', '/^ajaxsaveuser$/i', 'AjaxSaveUser');
        $this->AddEventPreg('/^settings$/i', '/^ajaxactuser$/i', 'AjaxActUser');
    }

    protected function EventUsers()
    {
        if ($this->GetParam(1)) {
            if ($oUser = $this->User_GetUserByLogin($this->getParam(1))) {
                $this->Viewer_Assign("oUser", $oUser);
            }
        }
    }

    protected function EventSettings()
    {

        if (isPost('submit_user_add')) {
            $this->EventNewUser();
        }

        if (isPost('search')) {
            $sUserLogin = getRequest('user', null, 'post');
            if (!($oUser = $this->User_GetUserByLogin($sUserLogin))) {
                $this->Message_AddError($this->Lang_Get('user_not_found'), $this->Lang_Get('error'));
                return;
            }
            $aUsersRating['0'] = $oUser;
        } else {

            $iPage = preg_match("/^page(\d+)$/i", $this->getParam(0), $aMatch) ? $aMatch[1] : 1;
            /**
             * Получаем список юзеров
             */
            $iCount = 0;
            $aFilter = array();
            $aSort = array();
            $aResult = $this->PluginRole_People_GetUserList($iCount, $iPage, Config::Get('module.user.per_page'), $aFilter, $aSort);
            //print_R($aResult);
            $aUsersRating = $aResult['collection'];
            /**
             * Формируем постраничность
             */
            $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.user.per_page'), 4, Router::GetPath('role') . 'people');
            if ($aUsersRating) {
                $this->Viewer_Assign('aPaging', $aPaging);
            }
        }
        $this->Viewer_Assign('aUsersRating', $aUsersRating);
        $aStat = $this->User_GetStatUsers();
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('aStat', $aStat);

        $this->SetTemplateAction('settings');
    }

    protected function EventNewUser()
    {

        if (!$this->oUserCurrent->isAdministrator() and (empty($this->aUserRole['user']) or empty($this->aUserRole['user']['add']))) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }

        if (!$this->CheckUserFields()) {

            $oUser = Engine::GetEntity('User');
            $oUser->setLogin(getRequest('login'));
            $oUser->setMail(getRequest('mail'));
            $oUser->setPassword(func_encrypt(getRequest('password')));
            $oUser->setDateRegister(date("Y-m-d H:i:s"));
            $oUser->setIpRegister(func_getIp());

            if (!getRequest('user_act')) {
                $oUser->setActivate(0);
            } else {
                $oUser->setActivate(1);
            }
            $oUser->setActivateKey(null);

            if ($this->User_Add($oUser)) {
                $this->Blog_CreatePersonalBlog($oUser);
            } else {
                $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
                return Router::Action('error');
            }
        } else {
            return;
        }
    }

    protected function CheckUserFields($oUser = null)
    {
        $this->Security_ValidateSendForm();

        $bError = false;

        if (!func_check(getRequest('login'), 'login', 3, 30)) {
            $this->Message_AddError($this->Lang_Get('registration_login_error'), $this->Lang_Get('error'));
            $bError = true;
        }
        if (!$oUser or ($oUser and $oUser->getLogin() != getRequest('login'))) {
            if ($oIssUser = $this->User_GetUserByLogin(getRequest('login'))) {
                $this->Message_AddError($this->Lang_Get('registration_login_error_used'), $this->Lang_Get('error'));
                $bError = true;
            }
        }

        if (!func_check(getRequest('mail'), 'mail')) {
            $this->Message_AddError($this->Lang_Get('registration_mail_error'), $this->Lang_Get('error'));
            $bError = true;
        }
        if (!$oUser or ($oUser and $oUser->getMail() != getRequest('mail'))) {
            if ($oIssUser = $this->User_GetUserByMail(getRequest('mail'))) {
                $this->Message_AddError($this->Lang_Get('registration_mail_error_used'), $this->Lang_Get('error'));
                $bError = true;
            }
        }
        if (!$oUser) {
            if (!func_check(getRequest('password'), 'password', 5)) {
                $this->Message_AddError($this->Lang_Get('registration_password_error'), $this->Lang_Get('error'));
                $bError = true;
            }
        }
        return $bError;
    }

    protected function AjaxActUser()
    {

        if (!$this->oUserCurrent->isAdministrator() or (empty($this->aUserRole['user']) and empty($this->aUserRole['user']['edit']))) {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
        $this->Viewer_SetResponseAjax('json');
        $sUserId = getRequest('user_id', null, 'post');
        if (!($oUser = $this->User_GetUserById($sUserId))) {
            $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
            return;
        }
        $oUser->setActivate(1);
        if ($this->User_Update($oUser)) {
            $this->Message_AddNoticeSingle($this->Lang_Get('user_ac_user'), $this->Lang_Get('attention'));
            return;
        } else {
            $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
            return;
        }
    }

    protected function EventAjaxDelete()
    {

        $this->Viewer_SetResponseAjax('json');
        if ($this->oUserCurrent->isAdministrator() or (!empty($this->aUserRole['user']) and !empty($this->aUserRole['user']['delete']))) {
            $sUserId = getRequest('userid', null, 'post');

            if (!($oUser = $this->User_GetUserById($sUserId))) {
                $this->Message_AddErrorSingle($this->Lang_Get('system_error'), $this->Lang_Get('error'));
                return;
            }
            if ($oUser->isAdministrator()) {
                $this->Message_AddErrorSingle($this->Lang_Get('plugin.role.delete_admin_not_acceses'), $this->Lang_Get('error'));
                return;
            }
            $this->PluginRole_People_DeleteUserById($oUser);

            $this->Message_AddNoticeSingle($this->Lang_Get('plugin.role.people_delete_ok'), $this->Lang_Get('attention'));
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
    }

    protected function AjaxSaveUser()
    {
        // В зависимости от типа загрузчика устанавливается тип ответа
        if (getRequest('is_iframe')) {
            $this->Viewer_SetResponseAjax('jsonIframe', false);
        } else {
            $this->Viewer_SetResponseAjax('json');
        }

        $sUserId = getRequest('user_id', null, 'post');


        if ($this->oUserCurrent->isAdministrator() or (!empty($this->aUserRole['user']) and !empty($this->aUserRole['user']['edit']))) {

            if (!($oUser = $this->User_GetUserById($sUserId))) {
                return false;
            }

            if ($this->CheckUserFields($oUser)) {

                return false;
            }
            if ($oUser->isAdministrator()) {
                $this->Message_AddErrorSingle($this->Lang_Get('plugin.role.edit_admin_not_acceses'), $this->Lang_Get('error'));
                return;
            }

            $bError = false;
            $aErr = array();
            /**
             * Заполняем профиль из полей формы
             */
            /**
             * Полверка логина
             */
            $oUser->setLogin(getRequest('login'));

            /**
             * Проверяем имя
             */
            if (func_check(getRequest('profile_name'), 'text', 2, 20)) {
                $oUser->setProfileName(getRequest('profile_name'));
            } else {
                $oUser->setProfileName(null);
            }
            /**
             * Проверка мыла
             */
            if (func_check(getRequest('mail'), 'mail')) {
                if ($oUserMail = $this->User_GetUserByMail(getRequest('mail')) and $oUserMail->getId() != $oUser->getId()) {
                    $aErr[] = $this->Lang_Get('settings_profile_mail_error_used');
                    $bError = true;
                } else {
                    $oUser->setMail(getRequest('mail'));
                }
            } else {
                $aErr[] = $this->Lang_Get('settings_profile_mail_error');
                $bError = true;
            }
            /**
             * Проверяем пол
             */
            if (in_array(getRequest('profile_sex'), array('man', 'woman', 'other'))) {
                $oUser->setProfileSex(getRequest('profile_sex'));
            } else {
                $oUser->setProfileSex('other');
            }
            /**
             * Проверяем дату рождения
             */
            if (func_check(getRequest('profile_birthday_day'), 'id', 1, 2) and func_check(getRequest('profile_birthday_month'), 'id', 1, 2) and func_check(getRequest('profile_birthday_year'), 'id', 4, 4)) {
                $oUser->setProfileBirthday(date("Y-m-d H:i:s", mktime(0, 0, 0, getRequest('profile_birthday_month'), getRequest('profile_birthday_day'), getRequest('profile_birthday_year'))));
            } else {
                $oUser->setProfileBirthday(null);
            }

            /**
             * Проверяем информацию о себе
             */
            if (func_check(getRequest('profile_about'), 'text', 1, 3000)) {
                $oUser->setProfileAbout(getRequest('profile_about'));
            } else {
                $oUser->setProfileAbout(null);
            }
            /**
             * Проверка на смену пароля
             */
            if (getRequest('password', '') != '') {
                if (func_check(getRequest('password'), 'password', 5)) {
                    $oUser->setPassword(func_encrypt(getRequest('password')));
                } else {
                    $bError = true;
                    $aErr[] = $this->Lang_Get('settings_profile_password_new_error');
                }
            }
            /**
             * Ставим дату последнего изменения профиля
             */
            $oUser->setProfileDate(date("Y-m-d H:i:s"));
            /**
             * Сохраняем изменения профиля
             */
            $oUser->setRating(getRequest('rating'));
            $oUser->setSkill(getRequest('skill'));

            if (!$bError) {

                if ($this->PluginRole_Role_UpdateUser($oUser)) {
                    /**
                     * Добавляем страну
                     */
                    if ($oUser->getProfileCountry()) {
                        if (!($oCountry = $this->User_GetCountryByName($oUser->getProfileCountry()))) {
                            $oCountry = Engine::GetEntity('User_Country');
                            $oCountry->setName($oUser->getProfileCountry());
                            $this->User_AddCountry($oCountry);
                        }
                        $this->User_SetCountryUser($oCountry->getId(), $oUser->getId());
                    }
                    /**
                     * Добавляем город
                     */
                    if ($oUser->getProfileCity()) {
                        if (!($oCity = $this->User_GetCityByName($oUser->getProfileCity()))) {
                            $oCity = Engine::GetEntity('User_City');
                            $oCity->setName($oUser->getProfileCity());
                            $this->User_AddCity($oCity);
                        }
                        $this->User_SetCityUser($oCity->getId(), $oUser->getId());
                    }
                    //$bStateError = false;
                    $this->Message_AddNoticeSingle($this->Lang_Get('settings_profile_submit_ok'), $this->Lang_Get('attention'));

                    $oViewerLocal = $this->Viewer_GetLocalViewer();
                    $oViewerLocal->Assign('oUser', $oUser);
                    $HtmlEditForm = $oViewerLocal->Fetch(Plugin::GetTemplatePath('role') . 'form.setting.tpl');
                    $this->Viewer_AssignAjax('HtmlEditForm ', $HtmlEditForm);
                } else {
                    $this->Message_AddErrorSingle($this->Lang_Get('system_error'), $this->Lang_Get('error'));
                }
            }
        } else {
            $this->Message_AddErrorSingle($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return;
        }
    }

    public function EventShutdown()
    {
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
    }

}

?>
