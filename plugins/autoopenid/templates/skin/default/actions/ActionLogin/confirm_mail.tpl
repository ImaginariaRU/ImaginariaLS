{assign var="noSidebar" value=true}
{include file='header.tpl'}


<div class="openid-block step-three wide">
	<h1>{$aLang.plugin.autoopenid.confirm_mail.form_title}:
	<span>
		{$sNameDisplay|escape:'html'}
	</span></h1>
			
	<form  method="post" action="">
		<p>
			{$aLang.plugin.autoopenid.confirm_mail.form_desc}
			<strong>
				{$sNameDisplay|escape:'html'} ({$oKey->getServiceType()})
			</strong>
			<br />
			{$aLang.plugin.autoopenid.confirm_mail.form_question}
		</p>	
		
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
		<input type="hidden" value="{$_aRequest.confirm_key}" name="confirm_key"/>

		<button type="submit" name="submit_confirm" class="button">{$aLang.plugin.autoopenid.confirm_mail.form_yes}</button>
		<button type="submit" name="submit_cancel" class="button">{$aLang.plugin.autoopenid.confirm_mail.form_no}</button>
	</form>
</div>


{include file='footer.tpl'}