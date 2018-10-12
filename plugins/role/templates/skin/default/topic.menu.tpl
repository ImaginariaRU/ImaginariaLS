{assign var="oBlog" value=$oTopic->getBlog()}
{assign var="oUser" value=$oTopic->getUser()}
{if $oUserCurrent and ($oUserCurrent->getId()!=$oTopic->getUserId() and !$oUserCurrent->isAdministrator() and !$oBlog->getUserIsAdministrator() and !$oBlog->getUserIsModerator() and $oBlog->getOwnerId()!=$oUserCurrent->getId())}
    {if $oUserCurrent && $oUserCurrent->getRole()}
        {assign var="aRole" value=$oUserCurrent->getRole()}
    {/if}
<header class="topic-header">
    <ul class="topic-actions">
        {if $aRole and $aRole.blog.topic.edit}
            <li class="edit"><i class="icon-synio-actions-edit"></i><a href="{cfg name='path.root.web'}/{$oTopic->getType()}/edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}" class="actions-edit">{$aLang.topic_edit}</a></li>
        {/if}

        {if $aRole and $aRole.blog.topic.delete}
            <li class="delete"><i class="icon-synio-actions-delete"></i><a href="{router page='topic'}delete/{$oTopic->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');" class="actions-delete">{$aLang.topic_delete}</a></li>
        {/if}
    </ul>
</header>
{/if}
