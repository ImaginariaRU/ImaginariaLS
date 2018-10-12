
	<!-- Popupinfo plugin -->
	<script>
		// vars
		Popupinfo_GetLoginMoreInfo = "{router page='popupinfo'}getuserinfo/";
		Popupinfo_GetBlogMoreInfo = "{router page='popupinfo'}getbloginfo/";
		Popupinfo_Leave_Long_Links_Alone = "{if $oConfig->GetValue("plugin.popupinfo.Leave_Long_Links_Alone")}1{else}0{/if}";
		Popupinfo_Panel_Showing_Delay = {$oConfig->GetValue("plugin.popupinfo.Panel_Showing_Delay")};
	</script>
	<div id="Popupinfo_MoreInfoContainer">
		<a href="http://livestreetguide.com/">LiveStreet CMS Guide by PSNet - мануал по разработке</a>
	</div>
	<!-- /Popupinfo plugin -->
