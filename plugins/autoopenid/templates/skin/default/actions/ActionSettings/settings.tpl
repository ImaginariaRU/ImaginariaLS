{assign var="noSidebar" value=false}
{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}

{include file='menu.settings.tpl'}

<h1>{$aLang.plugin.autoopenid.openid_menu_settings_title}</h1>

{if count($aOpenId)}

	{literal}
	<script language="JavaScript" type="text/javascript">
	function deleteOpenID(openid,obj) {
		if (typeof Request == 'undefined') {
			ls.ajax(DIR_WEB_ROOT+'/settings/openid/ajaxdeleteopenid/', {openid:openid}, function(data) { 
				if ( data.bStateError ) {
					ls.msg.error( data.sMsgTitle,  data.sMsg );				
				}else{
					ls.msg.notice( data.sMsgTitle,  data.sMsg );
				}
			});
		}else {
			new Request.JSON({
				url: aRouter['settings']+'openid/ajaxdeleteopenid/',
				noCache: false,
				data: {openid:openid,security_ls_key: LIVESTREET_SECURITY_KEY},
				onSuccess: function(resp){
					if (resp) {
						if (resp.bStateError) {
							msgErrorBox.alert(resp.sMsgTitle,resp.sMsg);
						} else {
							msgNoticeBox.alert(resp.sMsgTitle,resp.sMsg);
							$(obj).getParent().fade(0);
						}
					} else {
						msgErrorBox.alert('Error','Please try again later');
					}
				}.bind(this),
				onFailure: function(){
					msgErrorBox.alert('Error','Please try again later');
				}
			}).send();
			return false;
		}
	}
	</script>
	{/literal}

	<ul>
	{foreach from=$aOpenId item=oOpenId}
		<li>{$oOpenId->getOpenid()|escape:'html'} <a href="#" onclick="return deleteOpenID('{$oOpenId->getOpenid()|escape:'html'}',this);"><img src="{$sTemplateWebPathPlugin}img/delete.png" alt="{$aLang.plugin.autoopenid.openid_menu_settings_delete}" title="{$aLang.plugin.autoopenid.openid_menu_settings_delete}"/></a></li>
	{/foreach}
	</ul>
{else}
	{$aLang.plugin.autoopenid.openid_menu_settings_empty}
{/if}

{include file='footer.tpl'}