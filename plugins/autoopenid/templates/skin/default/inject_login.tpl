{if $aAutoopenidServicesAvailable}

	<div style="margin-bottom: 25px; border-bottom: #999 2px solid; padding-bottom: 15px;">
		<ul class="openid-service-list">
			{foreach $aAutoopenidServicesAvailable as $sServiceAvailable}
				<li title="{$aLang.plugin.autoopenid.services[$sServiceAvailable]}" class="js-autoopenid-auth openid-service-{$sServiceAvailable}-sm" data-service="{$sServiceAvailable}"></li>
			{/foreach}
		</ul>
	</div>

{/if}