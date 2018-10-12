{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}


{include file='menu.settings.tpl'}

	{if $aServicesItems}
		<fieldset>
			<legend>{$aLang.plugin.autoopenid.settings.services_list}</legend>

			<table>
				{foreach $aServicesItems as $oOpenidUser}
					<tr id="autoopenid-service-connect-item-{$oOpenidUser->getId()}">
						<td><div title="{$aLang.plugin.autoopenid.services[$oOpenidUser->getServiceType()]}" class="openid-service-{$oOpenidUser->getServiceType()}-sm"></div></td>
						<td title="id: {$oOpenidUser->getServiceId()|escape:'html'}">{$oOpenidUser->getNameDisplay()|escape:'html'}</td>
						<td><a href="#" class="icon-remove js-autoopenid-remove" data-service-type="{$oOpenidUser->getServiceType()}" data-service-id="{$oOpenidUser->getServiceId()|escape:'html'}"></a></td>
					</tr>
				{/foreach}
			</table>
		</fieldset>

	{else}
		<h1 class="openid-title">{$aLang.plugin.autoopenid.settings.services_empty}</h1>
	{/if}

	<fieldset>
		<legend>{$aLang.plugin.autoopenid.settings.connect_more}</legend>
		<ul class="openid-service-list">
			{foreach $aAutoopenidServicesAvailable as $sServiceAvailable}
				<li title="{$aLang.plugin.autoopenid.services[$sServiceAvailable]}" class="js-autoopenid-auth openid-service-{$sServiceAvailable}" data-service="{$sServiceAvailable}"></li>
			{/foreach}
		</ul>
	</fieldset>

{include file='footer.tpl'}