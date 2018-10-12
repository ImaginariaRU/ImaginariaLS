{assign var="noSidebar" value=true}
{include file='header.tpl'}

<div class="text">

<h4>{$aLang.plugin.autoopenid.migration.title}</h4>

{if $bTableOldExists}
	{$aLang.plugin.autoopenid.migration.notice}<br/><br/>
	<form action="" method="post">
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

		<button type="submit" name="submit_migration" class="button">{$aLang.plugin.autoopenid.migration.submit}</button>
	</form>
{else}
	{$aLang.plugin.autoopenid.migration.data_empty}
{/if}

</div>

{include file='footer.tpl'}