{if $oUserCurrent and !$oUserCurrent->isAdministrator() and ((!$oBlog or $oUserCurrent->getId()!=$oBlog->getOwnerId()) and !$oUserCurrent->isAdministrator() and (!$oBlog or !$oBlog->getUserIsAdministrator()) )}
    {if $oUserCurrent && $oUserCurrent->getRole()}
        {assign var="aRole" value=$oUserCurrent->getRole()}
    {/if}

    {if $aRole and ($aRole.blog.edit or $aRole.blog.delete)}
    <ul class="actions">
        {if $aRole.blog.edit}
            <li>
                <a href="{router page='blog'}edit/{$oBlog->getId()}/" title="{$aLang.blog_edit}"
                   class="edit">{$aLang.blog_edit}</a></li>
        {/if}
        {if $aRole.blog.delete}
            <li>
                <a href="{router page='blog'}delete/{$oBlog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"
                   title="{$aLang.blog_delete}"
                   onclick="return confirm('{$aLang.blog_admin_delete_confirm}');">{$aLang.blog_delete}</a></li>
        {/if}
    </ul>
    {/if}

{/if}
