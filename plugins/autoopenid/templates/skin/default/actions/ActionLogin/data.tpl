{assign var="noSidebar" value=true}
{include file='header.tpl'}

<div class="openid-block step-two">
	<h1 class="openid-title">{$aLang.plugin.autoopenid.continue_auth}</h1>

	<ul>
		<li {if !$_aRequest.submit_mail}class="active"{/if} id="aoid-toggle-data"><a href="javascript:ls.plugin.autoopenid.showFormData()" ><span>{$aLang.plugin.autoopenid.auth_type_new}</span></a></li>
		<li id="aoid-toggle-mail" {if $_aRequest.submit_mail}class="active"{/if}><a href="javascript:ls.plugin.autoopenid.showFormMail()" ><span>{$aLang.plugin.autoopenid.auth_type_exists}</span></a></li>
	</ul>
	
	
	<form method="post" action="" id="aoid-form-data" {if $_aRequest.submit_mail}style="display: none;"{/if}>
		<p>
			<label>{$aLang.plugin.autoopenid.login}</label>
			<input type="text" class="input-text input-width-300" name="login" value="{$_aRequest.login}" />
		</p>
		<p style="margin-bottom: 18px;">
			{if Config::Get('plugin.autoopenid.mail_required')}
				<label>{$aLang.plugin.autoopenid.mail}</label>
			{else}
				<a href="javascript:ls.plugin.autoopenid.toggleFieldMail()" class="openid-mail">{$aLang.plugin.autoopenid.mail_toggle}</a><br/>
			{/if}
			<input type="text" class="input-text input-width-300" style="margin-top: 5px;{if !Config::Get('plugin.autoopenid.mail_required') and !$_aRequest.mail}display: none;{/if}" maxlength="50" name="mail" value="{$_aRequest.mail}" id="aoid-field-mail"/>
		</p>
		<button type="submit" name="submit_data" class="button">{$aLang.plugin.autoopenid.auth_type_new_send}</button>
	</form>

	
	<form method="post" action=""  id="aoid-form-mail" {if !$_aRequest.submit_mail}style="display: none;"{/if}>
		<p>
			<label>{$aLang.plugin.autoopenid.mail}</label>
			<input type="text" class="input-text input-width-300" name="mail" value="{$_aRequest.mail}" />
		</p>
		<button type="submit" name="submit_mail" class="button">{$aLang.plugin.autoopenid.auth_type_exists_send}</button>
	</form>
</div>

{include file='footer.tpl'}