<div id="tk-tags-depend-area">
	{if !$bHide}
		{strip}
		<textarea id="tk-tags-depend-text">
			{$sText|escape:'html'}
		</textarea>
		{/strip}
		<input type="hidden" id="tk-tags-main-tag" value="{$sTag|escape:'html'}">
		<br/><br/>
		<button onclick="ls.plugin.tagskit.saveDependTags();" class="button fl-r">{$aLang.plugin.tagskit.save}</button>
	{/if}
</div>