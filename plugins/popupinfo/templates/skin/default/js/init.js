/**
 * Popupinfo plugin
 *
 * @copyright Serge Pustovit (PSNet), 2008 - 2014
 * @author    Serge Pustovit (PSNet) <light.feel@gmail.com>
 *
 * @link      http://psnet.lookformp3.net
 * @link      http://livestreet.ru/profile/PSNet/
 * @link      https://catalog.livestreetcms.com/profile/PSNet/
 * @link      http://livestreetguide.com/developer/PSNet/
 */

var ls = ls || {};

ls.popupinfo = (function ($) {

	this.storedEvent = null;
	this.hideProcId = null;
	this.showProcId = null;
	

	this.GetMoreInfo = function (Param, URL) {
		$.ajax ({
			url: URL,
			data: {
				'param': Param,
				'security_ls_key': LIVESTREET_SECURITY_KEY
			},
			type: 'POST',
			dataType: 'json',
			success: function (data) {
				if (!data.bStateError) {
					ls.popupinfo.ShowMoreInfo (data.sText);
				} else {
					ls.msg.error (data.sMsgTitle, data.sMsg);
				}
			}
		});
	};
	

	this.ShowMoreInfo = function (TextToShow) {
		// stop hiding process if that is planed
		this.StopHidingPanel ();
		
		// for html elements get correct places and borders of screen doesnt crashed them
		var oPopupinfoMoreInfoContainer = $ ('#Popupinfo_MoreInfoContainer');
		oPopupinfoMoreInfoContainer.css ({
			'top': '0px',
			'left': '0px'
		});
		
		oPopupinfoMoreInfoContainer.html (TextToShow);

		// for popup want be below cursor
		var OffsetForMouseCursor = 16;
		// // manually set the scrollbar size as default
		var WindowScrollbarSize = 24;

		var OffsetYForOverScreen = (ls.userpanel ? ls.userpanel.OriginalPanelHeight + 14 : WindowScrollbarSize);
		// if popup is over the screen - move window to this pixels from borders
		// also we check if user enabled user panel and get panel height + (2 * padding 5px + 2 * border 1px + 2px shadow offset) = 14px
		
		// get coordinates of mouse over the link
		var XCoord = this.storedEvent.clientX + OffsetForMouseCursor;
		var YCoord = this.storedEvent.clientY + OffsetForMouseCursor;
		
		// check if window is over of range of screen resolution
		var WinW = oPopupinfoMoreInfoContainer.width ();
		var WinH = oPopupinfoMoreInfoContainer.height ();
		var PadW = parseInt (oPopupinfoMoreInfoContainer.css ('paddingLeft').slice (0, -2)) * 2;	 // padding: left + right
		var PadH = parseInt (oPopupinfoMoreInfoContainer.css ('paddingTop').slice (0, -2)) * 2;
		var ScrW = $ (window).width ();
		var ScrH = $ (window).height ();
		
		if (XCoord + WinW + PadW > ScrW - WindowScrollbarSize) {
			XCoord = parseInt (ScrW - WinW - PadW - WindowScrollbarSize);
		}
		if (YCoord + WinH + PadH > ScrH - OffsetYForOverScreen) {
			YCoord = parseInt (ScrH - WinH - PadH - OffsetYForOverScreen);
		}
		
		oPopupinfoMoreInfoContainer.css ({
			'top': YCoord + 'px',
			'left': XCoord + 'px'
		});
		oPopupinfoMoreInfoContainer.fadeIn (400);
	};
	

	this._HideInfoPanel = function () {
		$ ('#Popupinfo_MoreInfoContainer').fadeOut (200);
	};
	

	this.StartHidingPanel = function () {
		this.StopHidingPanel ();
		ls.popupinfo.hideProcId = setTimeout ('ls.popupinfo._HideInfoPanel ()', 700);
	};
	

	this.StopHidingPanel = function () {
		if (ls.popupinfo.hideProcId != null) {
			clearTimeout (ls.popupinfo.hideProcId);
			ls.popupinfo.hideProcId = null;
		}
	};
	

	this.StartShowingPanel = function (Param, URL) {
		this.StopShowingPanel ();
		ls.popupinfo.showProcId = setTimeout ('ls.popupinfo.GetMoreInfo ("' + Param + '", "' + URL + '");', Popupinfo_Panel_Showing_Delay);
	};
	

	this.StopShowingPanel = function () {
		if (ls.popupinfo.showProcId != null) {
			clearTimeout (ls.popupinfo.showProcId);
			ls.popupinfo.showProcId = null;
		}
	};
	
	// ---

	return this;
	
}).call (ls.popupinfo || {}, jQuery);

// ---

jQuery (document).ready (function ($) {

	/**
	 * Для логинов пользователей
	 */
	$ (document).on('mouseover.popupinfo', 'a[href^="' + aRouter ['profile'] + '"]', function (e) {
		var CurLink = $ (this).attr ('href').replace (aRouter ['profile'], '');
		var LinkChains = CurLink.split ('/');
		
		// leave long links for profile alone
		if ((Popupinfo_Leave_Long_Links_Alone) && (LinkChains [1] != '')) return;
		
		ls.popupinfo.storedEvent = e;
		ls.popupinfo.StartShowingPanel (LinkChains [0], Popupinfo_GetLoginMoreInfo);
	}).bind ('mouseout.popupinfo', function (e) {
		ls.popupinfo.StopShowingPanel ();
		ls.popupinfo.StartHidingPanel ();
	});
	
	/**
	 * Для блогов
	 */
	$ (document).on('mouseover.popupinfo', 'a[href^="' + aRouter ['blog'] + '"]', function (e) {
		var CurLink = $ (this).attr ('href').replace (aRouter ['blog'], '');
		var LinkChains = CurLink.split ('/');
		
		if (LinkChains [1] != '') return;
		if (
			(LinkChains [0] == 'add') ||
			(LinkChains [0] == 'bad') ||
			(LinkChains [0] == 'newall') ||
			(LinkChains [0] == 'discussed') ||
			(LinkChains [0] == 'top') ||
			(LinkChains [0] == 'new')
		) {
			// disable "add", "bad" and "new" events only,
			// "edit" not needed to disable coz it has third parameter and will be disabled on previous check
			return;
		}
		
		ls.popupinfo.storedEvent = e;
		ls.popupinfo.StartShowingPanel (LinkChains [0], Popupinfo_GetBlogMoreInfo);
	}).bind ('mouseout.popupinfo', function (e) {
		ls.popupinfo.StopShowingPanel ();
		ls.popupinfo.StartHidingPanel ();
	});
	
	/**
	 * Для движений мыши по обертке
	 */
	$ ('#Popupinfo_MoreInfoContainer').bind ('mouseover.popupinfo', function () {
		ls.popupinfo.StopHidingPanel ();
	}).bind ('mouseout.popupinfo', function () {
		ls.popupinfo.StartHidingPanel ();
	});

});
