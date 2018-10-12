<div id="tk-white-tags-area" style="min-height: 30px;">
{foreach $aTagsTkWhite as $oTagWhite}
    <span class="tk-tags-white" onclick="ls.plugin.tagskit.clickWhiteTag('{$oTagWhite->getText()}',this);">{$oTagWhite->getText()|escape:'html'}</span>
{/foreach}

    <br/><br/>
{include file=$aTemplatePathPlugin.tagskit|cat:"ajax.paging.tpl" aPaging=$aPagingTagsTkWhite}
</div>