var ls = ls || {};

ls.ignore = (function ($) {
    
	this.updateIgnore = function(obj, idUser){
       var valueArray = new Array();
	   $.each($('#ignore_setting_form input:checkbox:checked'), function() {
	       valueArray.push($(this).val());
	   });
        $('#ignore_setting_form').children().each(function(i, item){$(item).attr('disabled','disabled')});
        var reason = $('#ignore_setting_reason').val()
        var url = aRouter.ajax+'ignore/setting/';
		var params = {idUser: idUser, data: valueArray.join('|'), reason: reason};

		ls.ajax(url, params, function(result){
			$('#ignore_setting_form').children().each(function(i, item){$(item).removeAttr('disabled')});
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				ls.msg.notice(null,result.sMsg);
				$('#ignore_setting_form').jqmHide();
			}
		});
		return false;
	};
    
	this.showIgnore = function(obj, idUser){
        var url = aRouter.ajax+'ignore/window/';
		var params = {idUser: idUser};

		ls.ajax(url, params, function(result){
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
                $('#ignore_setting_form').remove();
                $('body').prepend(result.sText);
				$('#ignore_setting_form').jqm();
				$('#ignore_setting_form').jqmShow();
			}
		}.bind(this));
		return false;
	};
    
    this.IgnorePostMyTopic = function() {
        $('#comments').find('a.reply-link').parent().addClass('js-title-ignore').attr('title', ls.lang.get('plugin.ignore.js_ignore_post_comment_my_topic')).html('<a href="#comments" class="ignore-link-reply">Ответить</a>');
        $('#comment_id_0').hide();
        $('#reply').hide();
        $('#reply').after('<div class="system-message-error">'+ls.lang.get('plugin.ignore.js_ignore_post_comment_my_topic')+'</div>');
    }

    this.HideMeComments = function() {
        $.each($('li.ignore-hide-comment'), function() {
            $('#comment_id_'+$(this).attr('id').substr(20)).removeClass('comment-self comment-new').html('<div class="deleted">'+ls.lang.get('plugin.ignore.js_ignore_hide_me_comments')+'</div>');
        });
    }

    this.IgnoreRyplyMyComment = function() {
	   $.each($('li.ignore-reply-comment'), function() {
	       $('#comment_id_'+$(this).attr('id').substr(21)).find('a.reply-link').parent().addClass('js-title-ignore').attr('title', ls.lang.get('plugin.ignore.js_ignore_reply_my_comment')).html('<a href="#comment'+$(this).attr('id').substr(21)+'" class="ignore-link-reply">Ответить</a>');
	   });
    }

    this.IgnorePostMyWall = function() {
        $('.wall-submit-reply').remove();
        $('.wall-submit').hide();
        $('.wall-submit').after('<div class="system-message-error">'+ls.lang.get('plugin.ignore.js_ignore_post_my_wall')+'</div>');
        $('#wall-note-list-empty').hide();
        $('#wall-container').find('.wall-item-actions').hide();
    }

	return this;
}).call(ls.ignore || {},jQuery);

jQuery(document).ready(function($){
    ls.ignore.HideMeComments();
    ls.ignore.IgnoreRyplyMyComment();
	$('.js-title-ignore').poshytip({
		className: 'infobox-standart',
		alignTo: 'target',
		alignX: 'right',
		alignY: 'center',
		offsetX: 10,
		liveEvents: true,
		showTimeout: 500
	});

});
