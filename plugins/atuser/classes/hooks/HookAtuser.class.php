<?php

/**
 *
 * Class PluginAtuser_HookAtuser
 */
class PluginAtuser_HookAtuser extends Hook
{

    /**
     * Регистрация событий на хуки
     */
    public function RegisterHook()
    {
        $this->AddHook('comment_add_before', 'correctComment', __CLASS__);
        $this->AddHook('topic_add_before', 'correctTopic', __CLASS__);
        $this->AddHook('topic_edit_before', 'correctTopic', __CLASS__);
    }

    /**
     * @param $params
     */
    public function correctComment($params)
    {
        $oComment = $params['oCommentNew'];
        $oSender = $this->User_GetUserById($oComment->getUserId());
        $oTopic = $this->Topic_GetTopicById($oComment->getTargetId());

        $sRes = $this->makeCorrection($oComment->getText(),
            'notify.comment_mention.tpl',
            array('oComment' => $oComment, 'oSender' => $oSender, 'oTopic' => $oTopic)
        );
        $oComment->setText($sRes);
        $oComment->setTextHash(md5($sRes));
    }

    /**
     * @param $sText
     * @param $template
     * @param array $aAssign
     * @return mixed
     */
    protected function makeCorrection($sText, $template, $aAssign = array())
    {
        $repls = array();
        $match = array();
        preg_match_all('/@\w+/u', $sText, $match);

        foreach ($match as $vals) {
            if (count($vals) > 0) {
                foreach ($vals as $val) {
                    $login = substr(trim($val), 1);
                    $oUser = $this->User_GetUserByLogin($login);

                    if ($oUser) {

                        $repls[] = array(
                            'repl' => $val,
                            'ref' => $oUser->getUserWebPath(),
                            'login' => $oUser->getLogin()
                        );

                        if ($template != '') {
                            $params = array('oUser' => $oUser);
                            $params = array_merge($params, $aAssign);
                            $sNotifyTitle = $this->Lang_Get('plugin.atuser.notify_title');
                            $this->Notify_Send($oUser, $template, $sNotifyTitle, $params, 'atuser');
                        }
                    }
                }
            }
        }
        $sRes = $sText;
        foreach ($repls as $repl) {
            // $sRes = str_replace($repl['repl'], '<a href="' . $repl['ref'] . '" class="ls-user">' . $repl['login'] . '</a>', $sRes);
            $sRes = str_replace($repl['repl'], "<a href=\"{$repl['ref']}\" class=\"ls-user\">{$repl['login']}</a>", $sRes);
        }
        return $sRes;
    }

    public function correctTopic($params)
    {
        $oTopic = $params['oTopic'];
        $sRes = $this->makeCorrection($oTopic->getText(), '');
        $oTopic->setText($sRes);
        $oTopic->setTextHash(md5($sRes));
    }
}
