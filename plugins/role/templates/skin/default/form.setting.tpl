<h1>{$aLang.settings_profile_edit} {$oUser->getLogin()}</h1>
<form action="" method="POST" enctype="multipart/form-data" name="peole_form_{$oUser->getId()}" id="peole_form_{$oUser->getId()}" onsubmit="return false">
{hook run='form_setti~ngs_profile_begin'}
    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
    <input type="hidden" name="user_id" value="{$oUser->getId()}" />

    <p>
        <label for="profile_login">{$aLang.registration_login}:</label><br/>
        <input type="text" name="login" id="login"
               value="{$oUser->getLogin()|escape:'html'}" class="input-300"/><br/>
        <span class="form_note">{$aLang.registration_login_notice}</span>
    </p>

    <p>
	<label for="profile_name">{$aLang.settings_profile_name}:</label>
	<input type="text" name="profile_name" id="profile_name" value="{$oUser->getProfileName()|escape:'html'}" class="w100p" /><br />
	<span class="form_note">{$aLang.settings_profile_name_notice}</span>
    </p>
    <p>
	<label for="mail">{$aLang.settings_profile_mail}:</label>
	<input type="text" class="w100p" name="mail" id="mail" value="{$oUser->getMail()|escape:'html'}"/><br />
	<span class="form_note">{$aLang.settings_profile_mail_notice}</span>
    </p>
    <p>
	<label for="">{$aLang.settings_profile_sex}:</label><br />
	<label for="profile_sex_m"><input type="radio" name="profile_sex" id="profile_sex_m" value="man" {if $oUser->getProfileSex()=='man'}checked{/if} class="radio" />  &mdash;  {$aLang.settings_profile_sex_man}</label><br />
	<label for="profile_sex_w"><input type="radio" name="profile_sex" id="profile_sex_w" value="woman" {if $oUser->getProfileSex()=='woman'}checked{/if} class="radio" />  &mdash;  {$aLang.settings_profile_sex_woman}</label><br />
	<label for="profile_sex_o"><input type="radio" name="profile_sex" id="profile_sex_o"  value="other" {if $oUser->getProfileSex()=='other'}checked{/if} class="radio" />  &mdash;  {$aLang.settings_profile_sex_other}</label>
    </p>
    <p>
	<label for="">{$aLang.settings_profile_birthday}:</label><br />
	<select name="profile_birthday_day" class="w70">
	    <option value="">{$aLang.date_day}</option>
	    {section name=date_day start=1 loop=32 step=1}
		<option value="{$smarty.section.date_day.index}" {if $smarty.section.date_day.index==$oUser->getProfileBirthday()|date_format:"%d"}selected{/if}>{$smarty.section.date_day.index}</option>
	    {/section}
	</select>
	<select name="profile_birthday_month" class="w100">
	    <option value="">{$aLang.date_month}</option>
	    {section name=date_month start=1 loop=13 step=1}
		<option value="{$smarty.section.date_month.index}" {if $smarty.section.date_month.index==$oUser->getProfileBirthday()|date_format:"%m"}selected{/if}>{$aLang.month_array[$smarty.section.date_month.index][0]}</option>
	    {/section}
	</select>
	<select name="profile_birthday_year" class="w70">
	    <option value="">{$aLang.date_year}</option>
	    {section name=date_year start=1940 loop=2000 step=1}
		<option value="{$smarty.section.date_year.index}" {if $smarty.section.date_year.index==$oUser->getProfileBirthday()|date_format:"%Y"}selected{/if}>{$smarty.section.date_year.index}</option>
	    {/section}
	</select>
    </p>

    <p>
	<label for="profile_country">{$aLang.settings_profile_country}:</label><br /><input type="text" class="w300" 	id="profile_country" name="profile_country" value="{$oUser->getProfileCountry()|escape:'html'}"/><br />
	<label for="profile_city">{$aLang.settings_profile_city}:</label><br /><input type="text" class="w300" id="profile_city" name="profile_city" value="{$oUser->getProfileCity()|escape:'html'}"/><br />
    </p>

    <p><label for="profile_icq">{$aLang.settings_profile_icq}:</label><br /><input type="text" class="w300" name="profile_icq" id="profile_icq" value="{$oUser->getProfileIcq()|escape:'html'}"/></p>

    <p>
	<label for="profile_site">{$aLang.settings_profile_site}:</label><br />
	<label for="profile_site"><input type="text" class="w300" style="margin-bottom: 5px;" id="profile_site" name="profile_site" value="{$oUser->getProfileSite()|escape:'html'}"/> &mdash; {$aLang.settings_profile_site_url}</label><br />
	<label for="profile_site_name"><input type="text" class="w300" id="profile_site_name"	name="profile_site_name" value="{$oUser->getProfileSiteName()|escape:'html'}"/> &mdash; {$aLang.settings_profile_site_name}</label>
    </p>

    <p>
	<label for="profile_about">{$aLang.settings_profile_about}:</label><br />
	<textarea class="small" name="profile_about" id="profile_about">{$oUser->getProfileAbout()|escape:'html'}</textarea>
    </p>

    <p>
	<label for="password">{$aLang.settings_profile_password_new}:</label><br /><input type="password" class="w300" id="password"	name="password" value=""/><br />
    </p>

    {if $oUser->getProfileAvatar()}
	<img src="{$oUser->getProfileAvatarPath(100)}" border="0">
	<img src="{$oUser->getProfileAvatarPath(64)}" border="0">
	<img src="{$oUser->getProfileAvatarPath(24)}" border="0">
	<input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> &mdash; <label for="avatar_delete"><span class="form">{$aLang.settings_profile_avatar_delete}</span></label><br /><br>
    {/if}
    <p><label for="avatar">{$aLang.settings_profile_avatar}:</label><br /><input type="file" id="avatar" name="avatar"/></p>

    {if $oUser->getProfileFoto()}
	<img src="{$oUser->getProfileFoto()}" border="0">
	<input type="checkbox" id="foto_delete" name="foto_delete" value="on"> &mdash; <label for="foto_delete"><span class="form">{$aLang.settings_profile_foto_delete}</span></label><br /><br>
    {/if}
    <p><label for="foto">{$aLang.settings_profile_foto}:</label><br /><input type="file" id="foto" name="foto"/></p>

    {hook run='form_settings_profile_end'}
    <p><input type="submit" value="{$aLang.settings_profile_submit}" name="submit_profile_edit" onclick="saveUserId(document.getElementById('peole_form_{$oUser->getId()}'),'{$oUser->getId()}'); return false;"/></p>
    <a href="#" onclick="ShowHideForm('user{$oUser->getId()}'); return false;" style="color: red;">{$aLang.role_user_form_hide}</a>
</form>
