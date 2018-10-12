<section class="block">
    <header class="block-header sep">
        <h3>{$aLang.plugin.role.block_menu_title}</h3>
    </header>
    <ul class="block-menu">
        <li {if $sMenuItemSelect=='roles'}class="active"{/if}>
            <a href="{router page="role"}roles/">{$aLang.plugin.role.menu_roles_title}</a>
        </li>
        <li {if $sMenuItemSelect=='users'}class="active"{/if}>
            <a href="{router page="role"}users/">{$aLang.plugin.role.menu_users_title}</a>
        </li>
        <li {if $sMenuItemSelect=='people'}class="active"{/if}>
            <a href="{router page="role"}people/">{$aLang.plugin.role.menu_people_title}</a>
        </li>
        <li {if $sMenuItemSelect=='admins'}class="active"{/if}>
            <a href="{router page="role"}admins/">{$aLang.plugin.role.menu_admins_title}</a>
        </li>
        <li {if $sMenuItemSelect=='avatar'}class="active"{/if}>
            <a href="{router page="role"}avatar/">{$aLang.plugin.role.menu_avatar_title}</a>
        </li>
    </ul>
</section>