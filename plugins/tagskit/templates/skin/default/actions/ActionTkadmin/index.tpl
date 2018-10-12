{assign var="noSidebar" value=true}
{include file='header.tpl'}

<link rel="stylesheet" type="text/css" href="{$aTemplateWebPathPlugin.tagskit|cat:'css/main.css'}" media="all" />


<div>
    <h2 class="page-header">{$aLang.plugin.tagskit.admin.group.title}</h2>

	{$aLang.plugin.tagskit.admin.group.add_main_tag}:<br/>
	<form action="" method="POST">
		<input type="text" class="autocomplete-tags input-width-200" name="add_main_tag"><br/><br/>
    </form>


	<table>
		<tr valign="top">
			<td>
				{$aLang.plugin.tagskit.admin.group.list_main_tags}:<br/>
                <select multiple="multiple" id="tk-tags-main-select">
					{foreach $aTagItems as $oTag}
						<option value="{$oTag->getMainText()|escape:'html'}">{$oTag->getMainText()|escape:'html'}</option>
					{/foreach}
                </select>
			</td>
			<td width="250">
				{$aLang.plugin.tagskit.admin.group.list_depend_tags}:<br/>
				{include file=$aTemplatePathPlugin.tagskit|cat:"depend.tags.items.tpl" bHide=true}
			</td>
		</tr>
	</table>




</div>

<script type="text/javascript">
    jQuery(function($){
        ls.plugin.tagskit.initSettingsGroupTags();
    });
</script>

{include file='footer.tpl'}