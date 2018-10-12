var ls = ls || {};
ls.plugin = ls.plugin || {};


ls.plugin.tagskit = (function ($) {

	this.search = function(form) {
		$form=$(form);
		/**
		 * Параметры поиска
		 */
		var tags=$form.find('.js-tag-search').val();
		var how=$('.js-tagskit-search').find(':radio[name=tk_how]').filter(":checked").val();
		var where=$('.js-tagskit-search').find(':radio[name=tk_where]').filter(":checked").val();
		/**
		 * Формируем URL для поиска тегов и открываем его
		 */
		var url=aRouter.tag+'?tags='+encodeURIComponent(tags)+'&tk_how='+how+'&tk_where='+where;
		window.location.href=url;
		return false;
	};

	this.searchButton = function() {
		$("#content .js-tag-search-form").submit();
	};

	this.init = function() {
		$("#content .autocomplete-tags").autocomplete("destroy");
		ls.autocomplete.add($("#content .autocomplete-tags"), aRouter['ajax']+'autocompleter/tag/', true);

		$("#content .js-tag-search-form").unbind("submit");
		$("#content .js-tag-search-form").submit(function(){
			return ls.plugin.tagskit.search(this);
		});
	};

	this.initFormWhite = function() {
		$('#modal_tk_white_list').jqm();
		$('input[name=topic_tags]').autocomplete("destroy").bind('focus',function(){
			this.showWindowWhite();
		}.bind(this));
	};

	this.initSettingsGroupTags = function() {
		$('#tk-tags-main-select').bind('change',function(e){
			var val=e.target.value;
			$('#tk-tags-main-select  option:selected').each(function(){
				this.selected=false;
			});
			$("#tk-tags-main-select :contains('"+val+"')").attr("selected", "selected");
			this.clickGroupTag(val);
		}.bind(this));
	};

	this.clickGroupTag = function(text) {
		$('#tk-tags-depend-area').html('.').addClass('tk-loader');
		ls.ajax(aRouter.tk_admin+'ajax/load-depend-tags/',{ 'text': text },function(result){
			$('#tk-tags-depend-area').removeClass('tk-loader').html(result.sText);
		}.bind(this));
	};

	this.saveDependTags = function() {
		$('#tk-tags-depend-text').addClass('tk-loader');
		ls.ajax(aRouter.tk_admin+'ajax/save-depend-tags/',{ 'tag': $('#tk-tags-main-tag').val(), 'text': $('#tk-tags-depend-text').val() },function(result){
			$('#tk-tags-depend-text').removeClass('tk-loader');
			this.clickGroupTag($('#tk-tags-main-tag').val());
		}.bind(this));
	};

	this.showWindowWhite = function() {
		this.highlightTags();
		$('#modal_tk_white_list').jqmShow();
	};

	this.clickWhiteTag = function(sTag,obj) {
		obj=$(obj);
		var terms = $('#topic_tags').val().split( /,\s*/ );
		/**
		 * Если такой тег уже есть, то удаляем его, иначе добавляем
		 */
		if (this.in_array(sTag,terms)) {
			terms=this.array_diff(terms,[sTag]);
			obj.removeClass('active');
		} else {
			if (terms.length==1 && !terms[0]) {
				terms=[sTag];
			} else {
				terms.push(sTag);
			}
			obj.addClass('active');
		}
		this.array_unique(terms);
		$('#topic_tags').val(terms.join(", "));
	};

	this.clearTags = function() {
		$('#topic_tags').val('');
		this.highlightTags();
	};

	this.highlightTags = function() {
		$this=this;
		var terms = $('#topic_tags').val().split( /,\s*/ );
		$('#tk-white-tags-area .tk-tags-white').removeClass('active').each(function(k,v){
			if ($this.in_array($(v).text(),terms)) {
				$(this).addClass('active');
			}
		});
	};

	this.loadPageWhiteTag = function(iPage) {
		$('#tk-white-tags-area').html('').addClass('tk-loader');

		ls.ajax(aRouter.ajax+'tk/load-white-tags/',{ 'page': iPage },function(result){
			$('#tk-white-tags-area').html('').removeClass('tk-loader').replaceWith(result.sText);
			this.highlightTags();
		}.bind(this));

		return false;
	};

	this.autoSearchTags = function(button) {
		if ($(button).hasClass('tk-loader')) {
			return false;
		}
		$(button).addClass('tk-loader');
		var text =(BLOG_USE_TINYMCE) ? tinyMCE.activeEditor.getContent()  : $('#topic_text').val();
		ls.ajax(aRouter.ajax+'tk/auto-search-tags/',{ 'text': text },function(result){
			$(button).removeClass('tk-loader');

			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				var terms=[];
				$.each(result.aTags,function(k,v){
					terms.push(v);
				});
				$('#topic_tags').val(terms.join(", "));
				this.highlightTags();
			}

		}.bind(this));
	};

	this.array_unique = function(array) {
		var p, i, j;
		for(i = array.length; i;){
			for(p = --i; p > 0;){
				if(array[i] === array[--p]){
					for(j = p; --p && array[i] === array[p];);
					i -= array.splice(p + 1, j - p).length;
				}
			}
		}
		return true;
	};


	this.array_diff = function(a1,a2) {
		var a=[], diff=[];
		for(var i=0;i<a1.length;i++)
			a[a1[i]]=true;
		for(var i=0;i<a2.length;i++)
			if(a[a2[i]]) delete a[a2[i]];
			else a[a2[i]]=true;
		for(var k in a)
			diff.push(k);
		return diff;
	};


	this.in_array = function(needle, haystack) {
		var found = false, key;
		for (key in haystack) {
			if (haystack[key] === needle){
				found = true;
				break;
			}
		}
		return found;
	};


	return this;
}).call(ls.plugin.tagskit || {},jQuery);