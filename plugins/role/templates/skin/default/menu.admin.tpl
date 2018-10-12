<li {if $sMenuItemSelect=='role'}class="active"{/if}>
    <a href="{router page='role'}">{$aLang.plugin.role.title}</a>
</li>
<li {if $sMenuItemSelect=='people'}class="active"{/if}>
    <a href="{router page='role'}people/">{$aLang.plugin.role.users_title}</a>
</li>
