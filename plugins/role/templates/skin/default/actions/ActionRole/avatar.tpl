{include file='header.tpl'}

<h2 class="page-header">
    <a href="{router page="admin"}">{$aLang.admin_header}</a>
    <span>&raquo;</span>
{$aLang.plugin.role.avatar_title}
</h2>

<script type="text/javascript">
    {literal}
    jQuery(document).ready(function ($) {
        ls.autocomplete.add($(".autocomplete-users2"), aRouter['ajax'] + 'autocompleter/user/', false);
    });
    {/literal}
</script>

<div id="form_box" style="display: block;">
    <form action="" method="POST" enctype="multipart/form-data">

        <p>
            <label for="login">{$aLang.plugin.role.create_login}:</label>
            <input type="text" id="login" name="login" value="{$_aRequest.login}"
                   class="autocomplete-users2 input-text input-width-250"/>


        <input type="submit" name="role_create_avatar_submit" value="{$aLang.plugin.role.create_avatar_submit}" class="button button-primary" />
        </p>
    </form>
</div>

{include file='footer.tpl'}