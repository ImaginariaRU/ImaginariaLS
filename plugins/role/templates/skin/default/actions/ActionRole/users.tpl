{include file='header.tpl'}

<h2 class="page-header">
    <a href="{router page="admin"}">{$aLang.admin_header}</a>
    <span>&raquo;</span>
{$aLang.plugin.role.user_role_title}
</h2>

<script type="text/javascript">
    {literal}
	jQuery(document).ready(function($){
	    ls.autocomplete.add($(".autocomplete-users2"), aRouter['ajax']+'autocompleter/user/', false);
	});
	function sh(obj){
	    var obj = $(obj);
	    var id = $(obj).attr('id');
	    var checked = $('input#'+id+':checked').val();
	    if (checked){
		$('#'+id+'_box').show();
	    } else {
		$('#'+id+'_box').hide();
		$('#'+id+'_box input:checkbox').removeAttr('checked');
	    }
	}

	function SaveRoleUser(sUserId){
	    var formObj = $('#user_role'+sUserId);
	    ls.ajax(aRouter['role_ajax']+'saveuserrole/',formObj.serializeJSON(),function(result) {
		if (result.bStateError) {
		    ls.msg.error(null, result.sMsg);
		} else {
		    ls.msg.notice(null, result.sMsg);
		    $('#form_box'+sUserId).hide();
		}
	    });
	}
	function DelUser(sUserId){
	    ls.ajax(aRouter['role_ajax']+'deluser/',{sUserId:sUserId, security_ls_key: LIVESTREET_SECURITY_KEY  },function(result) {
		if (result.bStateError) {
		    ls.msg.error(null, result.sMsg);
		} else {
		    ls.msg.notice(null, result.sMsg);
		    $('#role_id'+sUserId).remove();
		}
	    });
	}
    {/literal}
</script>
<ul class="nav nav-pills">
    <li class="active">
        <a href="#" onclick="$('#form_box').toggle(); return false;">{$aLang.plugin.role.add_user_acl}</a>
    </li>
</ul>
<div id="form_box" style="display: none;">
    <form action="" method="POST" enctype="multipart/form-data">
	<p>
	    <label for="role_user_login">{$aLang.plugin.role.create_login}:</label>
	    <input type="text" id="role_user_login" name="role_user_login" class="input-text input-width-250 autocomplete-users2" />
	</p>
	<div>
        <ul class="nav nav-pills">
            <li class="active">{$aLang.plugin.role.create_acl}:</li>
        </ul>
	    <label><input type="checkbox" name="role[user]" id="role_user" value="1" onclick="sh(this)" /> {$aLang.plugin.role.create_user}</label>
	    <div id="role_user_box" class="opt-role">
            <label><input type="checkbox" name="role[user][add]" value="1" id="role_user_add" /> {$aLang.plugin.role.create_user_add}</label>
            <label><input type="checkbox" name="role[user][edit]" value="1" id="role_user_edit" /> {$aLang.plugin.role.create_user_edit}</label>
            <label><input type="checkbox" name="role[user][delete]" value="1" id="role_user_delete" /> {$aLang.plugin.role.create_user_delete}</label>
            <label><input type="checkbox" name="role[user][banned]" value="1" id="role_user_banned" /> {$aLang.plugin.role.create_user_banned}</label>
	    </div>
	    <br />
	    <label><input type="checkbox" name="role[blog]" value="1" id="role_blog" onclick="sh(this)" /> {$aLang.plugin.role.create_blog}</label>
	    <div id="role_blog_box" class="opt-role">
            <label><input type="checkbox" name="role[blog][add]" value="1" id="role_blog_add" /> {$aLang.plugin.role.create_blog_add}</label>
            <label><input type="checkbox" name="role[blog][edit]" value="1" id="role_blog_edit" /> {$aLang.plugin.role.create_blog_edit}</label>
            <label><input type="checkbox" name="role[blog][delete]" value="1" id="role_blog_delete" /> {$aLang.plugin.role.create_blog_delete}</label>

		    <label><input type="checkbox" name="role[blog][topic]" value="1" id="role_blog_topic" onclick="sh(this)" /> {$aLang.plugin.role.create_blog_topic}</label>
            <div id="role_blog_topic_box" class="opt-role">
                <label><input type="checkbox" name="role[blog][topic][add]" value="1" id="role_blog_topic_add" /> {$aLang.plugin.role.create_blog_topic_add}</label>
                <label><input type="checkbox" name="role[blog][topic][edit]" value="1" id="role_blog_topic_edit" /> {$aLang.plugin.role.create_blog_topic_edit}</label>
                <label><input type="checkbox" name="role[blog][topic][delete]" value="1" id="role_blog_topic_delete" /> {$aLang.plugin.role.create_blog_topic_delete}</label>

                <label><label><input type="checkbox" name="role[blog][topic][comment]" value="1" id="role_blog_topic_comment" onclick="sh(this)" /> {$aLang.plugin.role.create_blog_topic_comment}</label>
                <div id="role_blog_topic_comment_box" class="opt-role">
                    <label><input type="checkbox" name="role[blog][topic][comment][add]" value="1" id="role_blog_topic_comment_add" /> {$aLang.plugin.role.create_blog_topic_comment_add}</label>
                    <label><input type="checkbox" name="role[blog][topic][comment][edit]" value="1" id="role_blog_topic_comment_edit" /> {$aLang.plugin.role.create_blog_topic_comment_edit}</label>
                    <label><input type="checkbox" name="role[blog][topic][comment][delete]" value="1" id="role_blog_topic_comment_delete" /> {$aLang.plugin.role.create_blog_topic_comment_delete}</label>
                </div>

            </div>

	    </div>

	    {hook run='roles_user'}
	    <br />

	</div>


	<input type="submit" name="role_create_submit" class="button button-primary fl-r" value="{$aLang.plugin.role.create_submit}" />
    </form>
</div>
<br />

<h2 class="page-header">{$aLang.plugin.role.users_list_title}</h2>

{if $aUsers}
    {foreach from=$aUsers item=oUser}
	<div class="role" id="role_id{$oUser->getId()}">
	    <div class="name">{$oUser->getLogin()} <a href="#" onclick="DelUser('{$oUser->getId()}'); return false;" class="del-role">{$aLang.plugin.role.delete}</a></div>
	    <div style="width: 100%; overflow: hidden; height: 1px; background: #aaa; margin-top: 10px; clear: both;"></div>
	    <div id="user_r{$oUser->getId()}" style="width: 100%; overflow: hidden; background: #fafafa;">
		{assign var="aRole" value=$oUser->getRole()}
		{if $aRole}
		    <label for="role_acl" style="margin-top: 5px; display: block;"><a href="#" class="button" onclick="$('#form_box{$oUser->getId()}').toggle();return false;">{$aLang.plugin.role.create_acl}</a></label>
		    <div id="form_box{$oUser->getId()}" style="display: none;">
			<form action="" method="POST" id="user_role{$oUser->getId()}" enctype="multipart/form-data">
			    <input type="hidden" name="user_id" id="user_id" value="{$oUser->getId()}" />
			    <div>

				<label><input type="checkbox"{if $aRole.user} checked{/if} name="role[user]" id="role_user{$oUser->getId()}" value="1" onclick="sh(this)" /> {$aLang.plugin.role.create_user}</label>
				<div id="role_user{$oUser->getId()}_box" class="opt-role"{if $aRole.user} style="display: block;"{/if}>
				    <label><input type="checkbox"{if $aRole.user.add} checked{/if} name="role[user][add]" value="1" id="role_user_add" /> {$aLang.plugin.role.create_user_add}</label>
				    <label><input type="checkbox"{if $aRole.user.edit} checked{/if} name="role[user][edit]" value="1" id="role_user_edit" /> {$aLang.plugin.role.create_user_edit}</label>
				    <label><input type="checkbox"{if $aRole.user.delete} checked{/if} name="role[user][delete]" value="1" id="role_user_delete" /> {$aLang.plugin.role.create_user_delete}</label>
				    <label><input type="checkbox"{if $aRole.user.banned} checked{/if} name="role[user][banned]" value="1" id="role_user_banned" /> {$aLang.plugin.role.create_user_banned}</label>
				</div>
				<br />
				<label><input type="checkbox"{if $aRole.blog} checked{/if} name="role[blog]" value="1" id="role_blog{$oUser->getId()}" onclick="sh(this)" /> {$aLang.plugin.role.create_blog}</label>
				<div id="role_blog{$oUser->getId()}_box" class="opt-role"{if $aRole.blog} style="display: block;"{/if} >
				    <label><input type="checkbox"{if $aRole.blog.add} checked{/if} name="role[blog][add]" value="1" id="role_blog_add" /> {$aLang.plugin.role.create_blog_add}</label>
				    <label><input type="checkbox"{if $aRole.blog.edit} checked{/if} name="role[blog][edit]" value="1" id="role_blog_edit" /> {$aLang.plugin.role.create_blog_edit}</label>
				    <label><input type="checkbox"{if $aRole.blog.delete} checked{/if} name="role[blog][delete]" value="1" id="role_blog_delete" /> {$aLang.plugin.role.create_blog_delete}</label>

				    <label><input type="checkbox"{if $aRole.blog.topic} checked{/if} name="role[blog][topic]" value="1" id="role_blog_topic{$oUser->getId()}" onclick="sh(this)" /> {$aLang.plugin.role.create_blog_topic}</label>
				    <div id="role_blog_topic{$oUser->getId()}_box" class="opt-role"opt-role"{if $aRole.blog.topic} style="display: block;"{/if}>
					<label><input type="checkbox"{if $aRole.blog.topic.add} checked{/if} name="role[blog][topic][add]" value="1" id="role_blog_topic_add" /> {$aLang.plugin.role.create_blog_topic_add}</label>
					<label><input type="checkbox"{if $aRole.blog.topic.edit} checked{/if} name="role[blog][topic][edit]" value="1" id="role_blog_topic_edit" /> {$aLang.plugin.role.create_blog_topic_edit}</label>
					<label><input type="checkbox"{if $aRole.blog.topic.delete} checked{/if} name="role[blog][topic][delete]" value="1" id="role_blog_topic_delete" /> {$aLang.plugin.role.create_blog_topic_delete}</label>

					<label><input type="checkbox"{if $aRole.blog.topic.comment} checked{/if} name="role[blog][topic][comment]" value="1" id="role_blog_topic_comment{$oUser->getId()}" onclick="sh(this)" /> {$aLang.plugin.role.create_blog_topic_comment}</label>
					<div id="role_blog_topic_comment{$oUser->getId()}_box" class="opt-role"opt-role"{if $aRole.blog.topic.comment} style="display: block;"{/if}>
					    <label><input type="checkbox"{if $aRole.blog.topic.comment.add} checked{/if} name="role[blog][topic][comment][add]" value="1" id="role_blog_topic_comment_add" /> {$aLang.plugin.role.create_blog_topic_comment_add}</label>
					    <label><input type="checkbox"{if $aRole.blog.topic.comment.edit} checked{/if} name="role[blog][topic][comment][edit]" value="1" id="role_blog_topic_comment_edit" /> {$aLang.plugin.role.create_blog_topic_comment_edit}</label>
					    <label><input type="checkbox"{if $aRole.blog.topic.comment.delete} checked{/if} name="role[blog][topic][comment][delete]" value="1" id="role_blog_topic_comment_delete" /> {$aLang.plugin.role.create_blog_topic_comment_delete}</label>
					</div>

				    </div>

				</div>
				{hook run='roles_role_user_show_end' role=$aRole id=$oUser->getId()}
				
			    </div>

			    <input type="button" class="button button-primary fl-r" onclick="SaveRoleUser('{$oUser->getId()}'); return false;" name="role_create_submit" value="{$aLang.plugin.role.create_submit_save}" />
			</form>
		    </div>
		{/if}
	    </div>
	</div>
    {/foreach}
{else}
    {$aLang.plugin.role.list_empty}
{/if}

{include file='footer.tpl'}