<section class="block" id="block_role_{$oRole->getId()}">
    <header class="block-header">
        <h3>
            {if $oRole->getAvatar()}<img src="{$oRole->getAvatarPath(24)}" align="left"/> {/if}&nbsp;
            {$oRole->getName()}
        </h3>
    </header>

    <div class="block-content">
    {assign var="aUsers" value=$oRole->getUsers()}
    {if $aUsers}
        <ul class="list_user_role">
            {foreach from=$aUsers item=oUser name="cmt"}
                <li {if $smarty.foreach.cmt.iteration % 2 == 1}class="even"{/if}>
                    <a href="{$oUser->getUserWebPath()}"><img
                            src="{$oUser->getProfileAvatarPath(48)}"/>{$oUser->getLogin()}</a>
                </li>
            {/foreach}

        </ul>
        {else}
        {$aLang.plugin.role.role_not_user}
    {/if}
    </div>
</section>