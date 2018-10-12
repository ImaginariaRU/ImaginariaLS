<div id="openid_block">

{literal}
<style>
.hidden 			 { display: none; }
.footer-about h4 	 { position: relative; }
#openid_block  		 { margin-bottom: 10px; }
.modal #openid_block { position:relative; top:-10px; }
.openid-icon	 	 { display:inline-block; }
#footer .openid-link { position: absolute!important; left: 140px!important; top: 0px!important; width: 174px!important; margin-bottom: 0; }
.openid-link.vintage-footer  		 { display:none; }
#footer .openid-link.vintage-footer  { display:block; }
#login-form .openid-icon,
#registration-form .openid-icon, 
#popup-registration-form .openid-icon{ display:none; }
#footer .openid-link.default	 	 { display:none; }
</style>
{/literal}

<!-- Default -->
<div class="openid-link default" style="margin-left:4px">
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons.png) -96px 0" href="#" onclick="fb_open()" rel="open_facebook" title="{$aLang.plugin.autoopenid.openid_enter_title} Facebook"></a>
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons.png) -24px 0"  href="#" onclick="javascript: openid_vk()" title="{$aLang.plugin.autoopenid.openid_enter_title} ВКонтакте"></a>
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons.png) -72px 0" href="#" onclick="javascript: openid_twitter()" title="{$aLang.plugin.autoopenid.openid_enter_title} Twitter"></a>
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons.png) -0px 0" href="#" onclick="javascript: openid_google()" title="{$aLang.plugin.autoopenid.openid_enter_title} Google"></a>
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons.png) -168px 0" href="#" onclick="javascript: openid_yandex()" title="{$aLang.plugin.autoopenid.openid_enter_title} Yandex"></a>
<a class="openid-icon" title="{$aLang.plugin.autoopenid.openid_enter_title_openid}" style="width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons/openid_2.png)" href="#" onclick="document.getElementById('openid_manual').className = (document.getElementById('openid_manual').className=='hidden')?'':'hidden';"></a>
</div>


<!-- Vintage Footer -->
<div class="openid-link vintage-footer">
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons/facebook.png)" href="#" onclick="fb_open()" rel="open_facebook"title="{$aLang.plugin.autoopenid.openid_enter_title} Facebook"></a>
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons/rus-vk-01.png)"  href="#" onclick="javascript: openid_vk()" title="{$aLang.plugin.autoopenid.openid_enter_title} ВКонтакте"></a>
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons/twitter-3.png)" href="#" onclick="javascript: openid_twitter()" title="{$aLang.plugin.autoopenid.openid_enter_title} Twitter"></a>
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons/google.png)" href="#" onclick="javascript: openid_google()" title="{$aLang.plugin.autoopenid.openid_enter_title} Google"></a>
<a style="display:inline-block;width:24px;height:24px;margin:0 7px 0 0;background:url({$sTemplateWebPathPlugin}img/auth_icons/rus-yandex-01.png)" href="#" onclick="javascript: openid_yandex()" title="{$aLang.plugin.autoopenid.openid_enter_title} Yandex"></a>
</div>



<div id="openid_manual" class="hidden">
<div class="lite-note" style="margin-top: 5px;" ><label for="login-input">{$aLang.plugin.autoopenid.openid}</label>
<br>
<input type="text" class="input-text" maxlength="255" name="open_login" id="open_login" style="width: 300px;"/>
<input type="hidden" name="submit_open_login" id="submit_open_login_hidden" value="go"/>
<input type="hidden" value="{$_aRequest.return}" name="return" id="rreturn" />
					<button onclick="authRedir();return false;"  type="button" name="openid_button" class="button"><span><em>{$aLang.user_login_submit}</em></span></button>
</div>
</div>
<div id="vk_api_transport"></div>


<script type="text/javascript">
var sVkTransportPath='{cfg name='plugin.autoopenid.vk.transport_path'}';
var iVkAppId='{cfg name='plugin.autoopenid.vk.id'}';
var sVkLoginPath='{$aRouter.login}'+'openid/vk/';
var vkScope='{cfg name='plugin.autoopenid.vk.scope'}';
var fbAppId='{cfg name='plugin.autoopenid.fb.id'}';
var fbLoginPath='{$aRouter.login}'+'openid/fb/';
var fbScope='{cfg name='plugin.autoopenid.fb.scope'}';
var sTwitterLoginPath='{$aRouter.login}'+'openid/twitter/?authorize=1';
var base_redir_url = "{router page='login'}openid/enter/";

{literal}
	function getEl(id) {
		return document.getElementById(id);
	}

	function openid_yandex() {
		getEl('open_login').value='openid.yandex.ru';		
		authRedir();

	}
	
	function openid_rambler() {
		getEl('open_login').value='rambler.ru';		
		authRedir();
	}
	
	function openid_google() {
		getEl('open_login').value='https://www.google.com/accounts/o8/id';		
		authRedir();
	}
	function authRedir(){
		
		if (getEl('open_login').value) {
			str = base_redir_url+"?submit_open_login=go&open_login="+getEl('open_login').value+"&return="+getEl('rreturn').value;
			openid_setCookie("openid_referrer", window.location.href, 1);
			window.location = str;
		} else	{
			
			alert('Не указан OpenId');

		}

	}


	function openid_vk() {		
		openid_setCookie("openid_referrer", window.location.href, 1);
		window.location ='http://api.vkontakte.ru/oauth/authorize?client_id='+iVkAppId+'&redirect_uri='+sVkLoginPath+'&scope='+encodeURI(vkScope);  
	}	
	
   var w;
   function fb_open() {
		openid_setCookie("openid_referrer", window.location.href, 1);		
		window.location = 'https://www.facebook.com/dialog/oauth?client_id='+fbAppId+'&redirect_uri='+fbLoginPath+'&scope='+encodeURI(fbScope);
	  }
	function openid_twitter() {
		openid_setCookie("openid_referrer", window.location.href, 1);			
		window.location = sTwitterLoginPath;
	}	


	function openid_setCookie(c_name,value,exdays)
	{
		var exdate=new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value=escape(value) + ((exdays==null) ? "" : ";expires="+exdate.toUTCString())+";path=/";
		document.cookie=c_name + "=" + c_value;
	}	
	
</script>
{/literal}

</div>