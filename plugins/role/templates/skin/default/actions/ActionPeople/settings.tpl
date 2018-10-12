{include file='header.tpl'}

<h2 class="page-header">
    <a href="{router page="admin"}">{$aLang.admin_header}</a>
    <span>&raquo;</span>
{$aLang.plugin.role.title_people}
</h2>

{if $oUserCurrent && $oUserCurrent->getRole()}
    {assign var="aUserRole" value=$oUserCurrent->getRole()}
{/if}
<div class="page people levo">
{literal}
<script language="JavaScript" type="text/javascript">

    function DellUser(userid) {

        ls.ajax(aRouter['role'] + 'people/ajaxdeleteapeople/', {userid:userid, security_ls_key:LIVESTREET_SECURITY_KEY}, function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                ls.msg.notice(null, result.sMsg);
                $('#usern' + userid).parent('tr').remove();
            }
        });

        return false;
    }
    function saveUserId(oUser, id) {
        formObj = $('#peole_form_' + id);
        ls.ajaxSubmit(aRouter['role'] + 'people/ajaxsaveuser/', $('#peole_form_' + id), function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                ls.msg.notice(null, result.sMsg);
                $('#user' + id).html(result.HtmlEditForm);
                $('#user' + id).toggle('fast');
            }
        });
    }

    function ActUser(obj, id) {
        ls.ajax(aRouter['role'] + 'people/ajaxactuser/', { user_id:id, security_ls_key:LIVESTREET_SECURITY_KEY  }, function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                ls.msg.notice(null, result.sMsg);
                $(obj).remove();
            }
        });

    }
    jQuery(document).ready(function ($) {
        ls.autocomplete.add($(".autocomplete-users2"), aRouter['ajax'] + 'autocompleter/user/', false);
    });
</script>
{/literal}
{if $oUserCurrent->isAdministrator() or ($aUserRole and $aUserRole.user.add)}
<ul class="nav nav-pills">
    <li class="active">
        <a href="#" onclick="$('#news_user').toggle('fast'); return false;">{$aLang.plugin.role.user_add}</a>
    </li>
</ul>

<div class="giftsa profile-user" id="news_user" style="display: none;">

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

        <p>
            <label for="user_login">{$aLang.plugin.role.user_login}</label>
            <input type="text" id="login" name="login" value="{$_aRequest.login}" class="input-text input-width-250"/>
        </p>

        <p>
            <label for="user_mail">{$aLang.plugin.role.user_mail}</label>
            <input type="text" id="mail" name="mail" value="{$_aRequest.mail}" class="input-text input-width-250"/>
        </p>

        <p>
            <label for="user_pass">{$aLang.plugin.role.user_pass}</label>
            <input type="text" id="password" name="password" value="{$_aRequest.password}" class="input-text input-width-250"/>
        </p>

        <p>
            <label for="user_act">
                <input type="checkbox" name="user_act" id="user_act" value="1" class="radio" {if $_aRequest.user_act==1}checked{/if} /> &mdash; {$aLang.plugin.role.user_act}</label>
        </p>

        <input type="submit" name="submit_user_add" value="{$aLang.plugin.role.user_submit_add}" class="button button-primary fl-r"/>

        <a href="#" onclick="$('#news_user').toggle('fast'); return false;" class="button">{$aLang.plugin.role.user_form_hide}</a>

    </form>

</div>
{/if}
<form action="" method="POST">
    <p>
    <h2 class="page-header">{$aLang.plugin.role.user_search_form_title}:</h2>
        <input type="hidden" name="search" value="1"/>
        <input id="login_user" type="text" name="user" value="" class="input-text input-width-250" />
        <input type="submit" name="search_user_add" value="{$aLang.plugin.role.user_view}" class="button button-primary" />
    </p>
</form>
<div style="clear: both;"></div>

<h2 class="page-header">{$aLang.plugin.role.user_list} <span>({$aStat.count_all})</span></h2>

<div id="ulist">
{if $aUsersRating}
    <table class="table table-users">
        <thead>
        <tr>
            <th class="cell-name cell-tab">{$aLang.user}</th>
            <th class="cell-skill cell-tab"><div class="cell-tab-inner">{$aLang.user_skill}</div></th>
            <th class="cell-rating cell-tab"><div class="cell-tab-inner active">{$aLang.user_rating}</div></th>
        </tr>
        </thead>

        <tbody>
            {foreach from=$aUsersRating item=oUser}
            <tr>
                <td id="usern{$oUser->getId()}" class="cell-name" style="border: none;">
                    <a href="{router page='profile'}{$oUser->getLogin()}/"><img src="{$oUser->getProfileAvatarPath(24)}"
                                                                                alt=""/></a>
                    <a href="{router page='profile'}{$oUser->getLogin()}/" class="link">{$oUser->getLogin()}</a>
                    {if $oUserCurrent->isAdministrator() or ($aUserRole and $aUserRole.user.edit)}
                        <a href="#"
                           onclick="$('#user{$oUser->getId()}').toggle('fast'); return false;">{$aLang.plugin.role.user_edit_title}</a>
                        {if !$oUser->getActivate()}<b id="act{$oUser->getId()}"><a href="#"
                                                                                   onclick="ActUser(this,'{$oUser->getId()}'); return false;"
                                                                                   style="color: green;">{$aLang.plugin.role.user_act_title}</a></b>{/if}
                    {/if}
                    {if $oUserCurrent->isAdministrator() or ($aUserRole and $aUserRole.user.delete)}<a href="#"
                                                                                                       onclick="DellUser('{$oUser->getId()}'); return false;"
                                                                                                       style="color: red;">{$aLang.plugin.role.user_dellete_title}</a>{/if}
                </td>
                <td id="userr{$oUser->getId()}" class="cell-skill" style="border: none;">{$oUser->getSkill()}</td>
                <td id="users{$oUser->getId()}" class="cell-rating {if $oUser->getRating() < 0}negative{/if}" style="border: none;">
                    <strong>{$oUser->getRating()}</strong></td>
            </tr>
                {if $oUserCurrent->isAdministrator() or ($aUserRole and $aUserRole.user.edit)}
                <tr>
                    <td colspan="3" style="padding: 0">
                        <div id="user{$oUser->getId()}" style="display: none; padding: 15px 25px;">
                            <strong>{$aLang.settings_profile_edit} {$oUser->getLogin()}</strong>

                            <form action="" method="POST" enctype="multipart/form-data"
                                  name="peole_form_{$oUser->getId()}" id="peole_form_{$oUser->getId()}"
                                  onsubmit="return false">
                                {hook run='form_settings_profile_begin'}
                                <input type="hidden" name="is_iframe" value="true"/>
                                <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>
                                <input type="hidden" name="user_id" value="{$oUser->getId()}"/>

                                <p>
                                    <label for="profile_login">{$aLang.registration_login}:</label>
                                    <input type="text" name="login" id="login"
                                           value="{$oUser->getLogin()|escape:'html'}" class="input-text input-width-250"/>
                                    <span class="note">{$aLang.registration_login_notice}</span>
                                </p>

                                <p>
                                    <label for="profile_rating">{$aLang.user_rating}:</label>
                                    <input type="text" name="rating" id="rating" value="{$oUser->getRating()}"
                                           class="input-text input-width-250"/>
                                </p>

                                <p>
                                    <label for="profile_skill">{$aLang.user_skill}:</label>
                                    <input type="text" name="skill" id="skill" value="{$oUser->getSkill()}"
                                           class="input-text input-width-250"/>

                                </p>

                                <p>
                                    <label for="profile_name">{$aLang.settings_profile_name}:</label>
                                    <input type="text" name="profile_name" id="profile_name"
                                           value="{$oUser->getProfileName()|escape:'html'}" class="input-text input-width-250"/><br/>
                                    <span class="note">{$aLang.settings_profile_name_notice}</span>
                                </p>

                                <p>
                                    <label for="mail">{$aLang.settings_profile_mail}:</label>
                                    <input type="text" class="input-text input-width-250" name="mail" id="mail"
                                           value="{$oUser->getMail()|escape:'html'}"/>
                                    <span class="note">{$aLang.settings_profile_mail_notice}</span>
                                </p>

                                <p>
                                    <label for="">{$aLang.settings_profile_sex}:</label>
                                    <label for="profile_sex_m">
                                        <input type="radio" name="profile_sex" id="profile_sex_m" value="man" {if $oUser->getProfileSex()=='man'}checked{/if} class="radio"/> &mdash;  {$aLang.settings_profile_sex_man}
                                    </label>
                                    <label for="profile_sex_w">
                                        <input type="radio" name="profile_sex" id="profile_sex_w" value="woman" {if $oUser->getProfileSex()=='woman'}checked{/if} class="radio"/> &mdash;  {$aLang.settings_profile_sex_woman}
                                    </label>
                                    <label for="profile_sex_o">
                                        <input type="radio" name="profile_sex" id="profile_sex_o" value="other" {if $oUser->getProfileSex()=='other'}checked{/if} class="radio"/> &mdash;  {$aLang.settings_profile_sex_other}
                                    </label>
                                </p>

                                <p>
                                    <label for="">{$aLang.settings_profile_birthday}:</label>
                                    <select name="profile_birthday_day" class="input-100">
                                        <option value="">{$aLang.date_day}</option>
                                        {section name=date_day start=1 loop=32 step=1}
                                            <option value="{$smarty.section.date_day.index}"
                                                    {if $smarty.section.date_day.index==$oUser->getProfileBirthday()|date_format:"%d"}selected{/if}>{$smarty.section.date_day.index}</option>
                                        {/section}
                                    </select>
                                    <select name="profile_birthday_month" class="input-100">
                                        <option value="">{$aLang.date_month}</option>
                                        {section name=date_month start=1 loop=13 step=1}
                                            <option value="{$smarty.section.date_month.index}"
                                                    {if $smarty.section.date_month.index==$oUser->getProfileBirthday()|date_format:"%m"}selected{/if}>{$aLang.month_array[$smarty.section.date_month.index][0]}</option>
                                        {/section}
                                    </select>
                                    <select name="profile_birthday_year" class="input-100">
                                        <option value="">{$aLang.date_year}</option>
                                        {section name=date_year start=1940 loop=2000 step=1}
                                            <option value="{$smarty.section.date_year.index}"
                                                    {if $smarty.section.date_year.index==$oUser->getProfileBirthday()|date_format:"%Y"}selected{/if}>{$smarty.section.date_year.index}</option>
                                        {/section}
                                    </select>
                                </p>

                                <p>
                                    <label for="profile_about">{$aLang.settings_profile_about}:</label>
                                    <textarea name="profile_about" id="profile_about" rows="10" class="input-text input-width-full" rows="7" style="height: 50px;">{$oUser->getProfileAbout()|escape:'html'}</textarea>
                                </p>

                                <p>
                                    <label for="password">{$aLang.settings_profile_password_new}:</label>
                                    <input type="password" class="input-text input-width-200" id="password" name="password" value=""/>
                                </p>

                                <p><input type="submit" class="button button-primary fl-r" value="{$aLang.settings_profile_submit}"
                                          name="submit_profile_edit"
                                          onclick="saveUserId(document.getElementById('#peole_form_{$oUser->getId()}'),'{$oUser->getId()}'); return false;"/>
                                </p>
                                <a href="#" onclick="$('#user{$oUser->getId()}').toggle('fast'); return false;"
                                   class="button" style="color: red;">{$aLang.plugin.role.user_form_hide}</a>
                            </form>
                        </div>
                    </td>
                </tr>
                {/if}
            {/foreach}
        </tbody>
    </table>
    {else}
    {$aLang.user_empty}
{/if}


{include file="paging.tpl" aPaging=$aPaging}

</div>
</div>

{include file='footer.tpl'}
