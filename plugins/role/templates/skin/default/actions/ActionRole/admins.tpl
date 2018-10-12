{include file='header.tpl' menu="role"}

<h2 class="page-header">
    <a href="{router page="admin"}">{$aLang.admin_header}</a>
    <span>&raquo;</span>
{$aLang.plugin.role.title_admins}
</h2>

<script type="text/javascript">
    {literal}
	jQuery(document).ready(function($){
	    ls.autocomplete.add($(".autocomplete-users2"), aRouter['ajax']+'autocompleter/user/', false);
	});    
	function DelAdmin(sId){
	    ls.ajax(aRouter['role_ajax']+'deladmin/',{ sId: sId, security_ls_key: LIVESTREET_SECURITY_KEY  },function(result) {
		if (result.bStateError) {
		    ls.msg.error(null, result.sMsg);
		} else {
		    ls.msg.notice(null, result.sMsg);
		    $('#admin_id'+sId).remove();
		}
	    });	
	}
    {/literal}
</script>

<div id="form_box" style="display: block;">
    <form action="" method="POST" enctype="multipart/form-data">

	<p>
	    <label for="login">{$aLang.plugin.role.create_login}:</label>
	    <input type="text" id="login" name="login" value="{$_aRequest.login}" class="autocomplete-users2 input-text input-width-250" />


	<input type="submit" name="role_create_submit" class="button button-primary" value="{$aLang.plugin.role.create_submit}" />
    </p>
    </form>    
</div>
<br />

<h2 class="page-header">{$aLang.plugin.role.admin_list_title}</h2>

{if $aAdmins}
    {foreach from=$aAdmins item=oAdmin}  
	<div class="role" id="admin_id{$oAdmin->getId()}">
	    <div class="name" id="name{$oAdmin->getId()}">{$oAdmin->getLogin()} 
		<a href="#" onclick="DelAdmin('{$oAdmin->getId()}'); return false;" class="del-role">{$aLang.plugin.role.delete}</a>
	    </div>
	</div>
    {/foreach}
{else}
    {$aLang.plugin.role.admin_list_empty}
{/if}    

{include file='footer.tpl'}