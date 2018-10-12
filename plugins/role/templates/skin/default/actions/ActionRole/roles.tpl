{include file='header.tpl'}

<h2 class="page-header">
    <a href="{router page="admin"}">{$aLang.admin_header}</a>
    <span>&raquo;</span>
{$aLang.plugin.role.title}
</h2>

<script type="text/javascript">
    {literal}
    jQuery(document).ready(function ($) {
        ls.autocomplete.add($(".autocomplete-users2"), aRouter['ajax'] + 'autocompleter/user/', false);
    });
    function sh(obj) {
        var checked = obj.checked;
        var obj = $(obj);
        var id = $(obj).attr('id');

        if (checked) {
            $('#' + id + '_box').show();
        } else {
            $('#' + id + '_box').hide();
            $('#' + id + '_box input:checkbox').removeAttr('checked');
        }
    }
    function AddUser(sRoleId, sLogin) {
        ls.ajax(aRouter['role_ajax'] + 'adduser/', { sRoleId:sRoleId, sLogin:sLogin, security_ls_key:LIVESTREET_SECURITY_KEY  }, function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                ls.msg.notice(null, result.sMsg);
                var user = '<div id="user_role_' + result.sId + '_' + sRoleId + '" class="ruser">' + result.sLogin + '<a href="#" onclick="DelUserRole(\'' + sRoleId + '\',\'' + result.sId + '\'); return false;" class="del-role">&#215;</a></div>';
                $('#user_r' + sRoleId).append(user);
            }
        });
    }
    function DelUserRole(sRoleId, sUserId) {
        ls.ajax(aRouter['role_ajax'] + 'deluserrole/', { sRoleId:sRoleId, sUserId:sUserId, security_ls_key:LIVESTREET_SECURITY_KEY  }, function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                ls.msg.notice(null, result.sMsg);
                $('#user_role_' + sUserId + '_' + sRoleId).remove();
            }
        });
    }
    function DelRole(sRoleId) {
        ls.ajax(aRouter['role_ajax'] + 'delrole/', { sRoleId:sRoleId, security_ls_key:LIVESTREET_SECURITY_KEY  }, function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                ls.msg.notice(null, result.sMsg);
                $('#role_id' + sRoleId).remove();
            }
        });
    }
    function SaveRoleAcl(sRoleId) {
        var formObj = $('#role_form_acl' + sRoleId);
        ls.ajax(aRouter['role_ajax'] + 'saveroleacl/', formObj.serializeJSON(), function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                ls.msg.notice(null, result.sMsg);
                $('#role_r' + sRoleId).hide();
            }
        });
    }
    function SaveRole(sRoleId) {
        var formObj = $('#role_form' + sRoleId);
        ls.ajaxSubmit(aRouter['role_ajax'] + 'saverole/', $('#role_form' + sRoleId), function (data) {
            if (data.bStateError) {
                ls.msg.error(null, data.sMsg);
            } else {
                ls.msg.notice(null, data.sMsg);
                $('#role_s' + sRoleId).hide();
                $('#name' + sRoleId).html(data.sName);
                if (data.delete_avatar) {
                    $('#av-' + sRoleId).html('');
                }
                if (data.edit_avatar) {
                    $('#av-' + sRoleId).html(data.sAvatarHtml);
                }
            }
        });
    }
    {/literal}
</script>
{if $oConfig->GetValue('view.tinymce')}
<script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce-jq/tiny_mce.js"></script>

<script type="text/javascript">
        {literal}
        tinyMCE.init({
            mode:"textareas",
            theme:"advanced",
            theme_advanced_toolbar_location:"top",
            theme_advanced_toolbar_align:"left",
            theme_advanced_buttons1:"lshselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,lslink,unlink,pagebreak,code",
            theme_advanced_buttons2:"",
            theme_advanced_buttons3:"",
            theme_advanced_statusbar_location:"bottom",
            theme_advanced_resizing:true,
            theme_advanced_resize_horizontal:0,
            theme_advanced_resizing_use_cookie:0,
            theme_advanced_path:false,
            object_resizing:true,
            force_br_newlines:true,
            forced_root_block:'', // Needed for 3.x
            force_p_newlines:false,
            plugins:"lseditor,safari,inlinepopups,media,pagebreak",
            convert_urls:false,
            pagebreak_separator:"<cut>",
            media_strict:false,
            language:TINYMCE_LANG,
            inline_styles:false,
            formats:{
                underline:{inline:'u', exact:true},
                strikethrough:{inline:'s', exact:true}
            }
        });
        {/literal}
</script>
    {else}
<script type="text/javascript">{literal}
function getMarkitupRoleSettings() {
    return {
        onShiftEnter:{keepDefault:false, replaceWith:'<br />\n'},
        onTab:{keepDefault:false, replaceWith:'    '},
        markupSet:[
            {name:'H4', className:'editor-h4', openWith:'<h4>', closeWith:'</h4>' },
            {name:'H5', className:'editor-h5', openWith:'<h5>', closeWith:'</h5>' },
            {name:'H6', className:'editor-h6', openWith:'<h6>', closeWith:'</h6>' },
            {separator:'---------------' },
            {name:ls.lang.get('panel_b'), className:'editor-bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
            {name:ls.lang.get('panel_i'), className:'editor-italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
            {name:ls.lang.get('panel_s'), className:'editor-stroke', key:'S', openWith:'<s>', closeWith:'</s>' },
            {name:ls.lang.get('panel_u'), className:'editor-underline', key:'U', openWith:'<u>', closeWith:'</u>' },
            {name:ls.lang.get('panel_quote'), className:'editor-quote', key:'Q', replaceWith:function (m) {
                if (m.selectionOuter) return '<blockquote>' + m.selectionOuter + '</blockquote>'; else if (m.selection) return '<blockquote>' + m.selection + '</blockquote>'; else return '<blockquote></blockquote>'
            } },
            {name:ls.lang.get('panel_code'), className:'editor-code', openWith:'<code>', closeWith:'</code>' },
            {separator:'---------------' },
            {name:ls.lang.get('panel_list'), className:'editor-ul', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ul>\n', closeBlockWith:'\n</ul>' },
            {name:ls.lang.get('panel_list'), className:'editor-ol', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ol>\n', closeBlockWith:'\n</ol>' },
            {separator:'---------------' },
            {name:ls.lang.get('panel_url'), className:'editor-link', key:'L', openWith:'<a href="[![' + ls.lang.get('panel_url_promt') + ':!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
            {name:ls.lang.get('panel_user'), className:'editor-user', replaceWith:'<ls user="[![' + ls.lang.get('panel_user_promt') + ']!]" />' },
            {separator:'---------------' },
            {name:ls.lang.get('panel_clear_tags'), className:'editor-clean', replaceWith:function (markitup) {
                return markitup.selection.replace(/<(.*?)>/g, "")
            } },
        ]
    }
}{/literal}
jQuery(document).ready(function ($) {
    ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li,panel_image_promt,panel_user,panel_user_promt"});
    // Подключаем редактор
    $('.markitup-editor').markItUp(getMarkitupRoleSettings());
});
</script>
{/if}
<ul class="nav nav-pills">
    <li class="active"><a href="#"
                          onclick="$('#form_box').toggle(); return false;">{$aLang.plugin.role.create_title}</a></li>
</ul>

<div id="form_box" style="display: none;">
    <form action="" method="POST" enctype="multipart/form-data">

        <p>
            <label for="role_name">{$aLang.plugin.role.create_name}:</label>
            <input type="text" id="role_name" name="role_name" value="{$_aRequest.role_name}"
                   class="input-text input-width-full"/>
        </p>

        <p>
            <label for="role_text">{$aLang.plugin.role.create_text}:</label>
            <textarea name="role_text" id="role_text" rows="10" class="mce-editor markitup-editor input-width-full"
                      style="height: 100px">{$_aRequest.role_text}</textarea>
        </p>

        <p>
            <label for="avatar">{$aLang.plugin.role.create_avatar}:</label>
            <input type="file" id="avatar" class="input-text"
                   name="avatar"
                   accept="image/png,image/gif,image/pjpeg,image/jpeg,image/jpg"/>
        </p>

        <p>
            <label for="role_rating_use">
                <input type="checkbox" id="role_rating_use" name="role_rating_use"
                       class="input-checkbox" value="1" onclick="sh(this)"
                       {if $_aRequest.role_rating_use==1}checked{/if} /> {$aLang.plugin.role.creat_rating_use}
                :</label>

        <div id="role_rating_use_box" style="display: block;">
            <label for="role_rating">{$aLang.plugin.role.create_rate}:</label>
            <input type="text" id="role_rating" name="role_rating" value="{$_aRequest.role_rating}"
                   class="input-text input-width-100"/><br/>
            <span class="note">{$aLang.role_create_rate_note}</span>
        </div>
        </p>

        <p>
            <label for="role_reg">
                <input type="checkbox" id="role_reg" name="role_reg" class="checkbox" value="1"
                       {if $_aRequest.role_reg==1}checked{/if} />
            {$aLang.plugin.role.create_reg}</label>
            <span class="note">{$aLang.plugin.role.create_reg_note}</span>
        </p>

        <p>
            <label for="role_place_block">
                <input type="checkbox" id="role_place_block" name="role_place_block" class="checkbox" value="1"
                       onclick="sh(this)" {if $_aRequest.plugin.plugin.role.place_block==1}checked{/if} />
            {$aLang.plugin.role.create_block}
            </label>

        <div id="role_place_block_box" style="display: none;">
            <textarea name="role_place_list" id="role_place_list" class="input-width-full" style="height: 50px;"
                      rows="5">{$_aRequest.plugin.role.place_list}</textarea>
            <span class="note">{$aLang.plugin.role.create_block_note}</span>
        </div>
        </p>

        <div>
            <ul class="nav nav-pills">
                <li class="active">{$aLang.plugin.role.create_acl}:</li>
            </ul>
            <label><input type="checkbox" name="role[user]" id="role_user" value="1"
                          onclick="sh(this)"/> {$aLang.plugin.role.create_user}</label>

            <div id="role_user_box" class="opt-role">
                <label><input type="checkbox" name="role[user][add]" value="1"
                              id="role_user_add"/> {$aLang.plugin.role.create_user_add}</label>
                <label><input type="checkbox" name="role[user][edit]" value="1"
                              id="role_user_edit"/> {$aLang.plugin.role.create_user_edit}</label>
                <label><input type="checkbox" name="role[user][delete]" value="1"
                              id="role_user_delete"/> {$aLang.plugin.role.create_user_delete}</label>
                <label><input type="checkbox" name="role[user][banned]" value="1"
                              id="role_user_banned"/> {$aLang.plugin.role.create_user_banned}</label>
            </div>
            <br/>

            <label><input type="checkbox" name="role[blog]" value="1" id="role_blog"
                          onclick="sh(this)"/> {$aLang.plugin.role.create_blog}</label>

            <div id="role_blog_box" class="opt-role">
                <label><input type="checkbox" name="role[blog][add]" value="1"
                              id="role_blog_add"/> {$aLang.plugin.role.create_blog_add}</label>
                <label><input type="checkbox" name="role[blog][edit]" value="1"
                              id="role_blog_edit"/> {$aLang.plugin.role.create_blog_edit}</label>
                <label><input type="checkbox" name="role[blog][delete]" value="1"
                              id="role_blog_delete"/> {$aLang.plugin.role.create_blog_delete}</label>

                <label><input type="checkbox" name="role[blog][topic]" value="1" id="role_blog_topic"
                              onclick="sh(this)"/> {$aLang.plugin.role.create_blog_topic}</label>

                <div id="role_blog_topic_box" class="opt-role">
                    <label><input type="checkbox" name="role[blog][topic][add]" value="1"
                                  id="role_blog_topic_add"/> {$aLang.plugin.role.create_blog_topic_add}</label>
                    <label><input type="checkbox" name="role[blog][topic][edit]" value="1"
                                  id="role_blog_topic_edit"/> {$aLang.plugin.role.create_blog_topic_edit}</label>
                    <label><input type="checkbox" name="role[blog][topic][delete]" value="1"
                                  id="role_blog_topic_delete"/> {$aLang.plugin.role.create_blog_topic_delete}
                    </label>

                    <label><input type="checkbox" name="role[blog][topic][comment]" value="1"
                                  id="role_blog_topic_comment"
                                  onclick="sh(this)"/> {$aLang.plugin.role.create_blog_topic_comment}</label>

                    <div id="role_blog_topic_comment_box" class="opt-role">
                        <label><input type="checkbox" name="role[blog][topic][comment][add]" value="1"
                                      id="role_blog_topic_comment_add"/> {$aLang.plugin.role.create_blog_topic_comment_add}
                        </label>
                        <label><input type="checkbox" name="role[blog][topic][comment][edit]" value="1"
                                      id="role_blog_topic_comment_edit"/> {$aLang.plugin.role.create_blog_topic_comment_edit}
                        </label>
                        <label><input type="checkbox" name="role[blog][topic][comment][delete]" value="1"
                                      id="role_blog_topic_comment_delete"/> {$aLang.plugin.role.create_blog_topic_comment_delete}
                        </label>
                    </div>

                </div>

            </div>

        {hook run='roles_role'}
            <br/>

        </div>


        <input type="submit" name="role_create_submit" class="button button-primary fl-r"
               value="{$aLang.plugin.role.create_submit}"/>
    </form>
</div>
<br/>


<h2 class="page-header">{$aLang.plugin.role.list_title}</h2>

{if $aRole}
    {foreach from=$aRole item=oRole}
    <div class="role" id="role_id{$oRole->getId()}">
        <div class="name" id="name{$oRole->getId()}">{$oRole->getName()}
            <a href="#" onclick="DelRole('{$oRole->getId()}'); return false;"
               class="del-role">{$aLang.plugin.role.delete}</a>
        </div>
        <div style="width: 100%; overflow: hidden; height: 1px; background: #aaa; margin-top: 10px; clear: both;"></div>
        <div class="uf">
            {$aLang.plugin.role.user_login}:
            <input type="text" name="rule_user_{$oRole->getId()}" id="rule_user_{$oRole->getId()}"
                   class="input-text input-width-100 autocomplete-users2" style="margin-top: 5px;"/>
            <a href="#" class="button button-primary fl-r"
               onclick="AddUser({$oRole->getId()},$('#rule_user_{$oRole->getId()}').val()); return false;"
               style="margin-top: 5px;">{$aLang.plugin.role.add_user}</a>
        </div>
        <label for="role_users" style="margin-top: 5px; display: block;">
            <a href="#" class="button"
               onclick="$('#role_r{$oRole->getId()}, #user_r{$oRole->getId()}').hide(); $('#role_s{$oRole->getId()}').toggle();return false;">{$aLang.plugin.role.edit_role}</a>
            <a href="#" class="button"
               onclick="$('#user_r{$oRole->getId()}, #role_s{$oRole->getId()}').hide();$('#role_r{$oRole->getId()}').toggle();return false;">{$aLang.plugin.role.acl_list_role}</a>
            <a href="#" class="button"
               onclick="$('#role_r{$oRole->getId()}, #role_s{$oRole->getId()}').hide(); $('#user_r{$oRole->getId()}').toggle();return false;">{$aLang.plugin.role.users_list_role}</a>
        </label>

        <div id="role_s{$oRole->getId()}" style="width: 100%; overflow: hidden; background: #fafafa; display: none;">
            <form action="" method="POST" id="role_form{$oRole->getId()}" enctype="multipart/form-data">
                <input type="hidden" id="role_id" name="role_id" value="{$oRole->getId()}"/>
                <input type="hidden" name="is_iframe" value="true"/>
                <br/>

                <p>
                    <label for="role_name">{$aLang.plugin.role.create_name}:</label>
                    <input type="text" id="role_name" name="role_name" value="{$oRole->getName()}"
                           class="input-text input-width-full"/>
                </p>

                <p>
                    <label for="role_text">{$aLang.plugin.role.create_text}:</label>
                    <textarea name="role_text" id="role_text" rows="20"
                              class="mce-editor markitup-editor input-width-full"
                              style="height: 100px;">{$oRole->getText()}</textarea>
                </p>

                <div id="av-{$oRole->getId()}">
                    {if $oRole->getAvatar()}
                        <img src="{$oRole->getAvatarPath(96)}"/>
                        <img src="{$oRole->getAvatarPath(64)}"/>
                        <img src="{$oRole->getAvatarPath(48)}"/>
                        <img src="{$oRole->getAvatarPath(24)}"/><br/>
                        <label for="avatar_delete">
                            <input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"
                                   class="input-checkbox"/>{$aLang.plugin.role.avatar_delete}</label><br/>
                    {/if}
                </div>
                <p><label for="avatar">{$aLang.plugin.role.create_avatar}:</label>
                    <input type="file" id="avatar" name="avatar"
                           accept="image/png,image/gif,image/pjpeg,image/jpeg,image/jpg"/>
                </p>

                <p>
                    <label for="role_rate_use">
                        <input type="checkbox" id="role_rating_use{$oRole->getId()}" name="role_rating_use"
                               class="checkbox" value="1" onclick="sh(this)" {if $oRole->getRatingUse()}checked{/if} />
                        {$aLang.plugin.role.creat_rating_use}:</label>

                <div id="role_rating_use{$oRole->getId()}_box"
                     style="display: {if $oRole->getRatingUse()}block{else}none{/if};">
                    <label for="role_rate">{$aLang.plugin.role.create_rate}:</label>
                    <input type="text" id="role_rating" name="role_rating" value="{$oRole->getRating()}"
                           class="input-text input-width-100"/><br/>
                    <span class="note">{$aLang.plugin.role.create_rate_note}</span>
                </div>
                </p>

                <p>
                    <label for="role_reg"><input type="checkbox" id="role_reg" name="role_reg" class="checkbox"
                                                 value="1" {if $oRole->getReg()==1}checked{/if} />
                        {$aLang.plugin.role.create_reg}</label>
                    <span class="note">{$aLang.plugin.role.create_reg_note}</span>
                </p>

                <p>
                    <label for="role_reg">
                        <input type="checkbox" id="role_place_block{$oRole->getId()}" name="role_place_block"
                               class="checkbox" value="1" onclick="sh(this)" {if $oRole->getPlace()}checked{/if} />
                        {$aLang.plugin.role.create_block}</label>

                <div id="role_place_block{$oRole->getId()}_box"
                     style="display: {if $oRole->getPlace()}block{else}none{/if};">
                    <textarea name="role_place_list" id="role_place_list" class="input-width-full"
                              rows="5">{$oRole->getPlace()}</textarea>
                    <span class="note">{$aLang.plugin.role.create_block_note}</span>
                </div>
                </p>

                <input type="button" class="button button-primary fl-r"
                       onclick="SaveRole('{$oRole->getId()}'); return false;" name="role_create_submit"
                       value="{$aLang.plugin.role.create_submit}"/><br/><br/>
            </form>

        </div>

        <div id="user_r{$oRole->getId()}" style="width: 100%; overflow: hidden; background: #fafafa; display: none;">
            <br />
            {assign var="aUsers" value=$oRole->getUsers()}
            {if $aUsers}
                {foreach from=$aUsers item=oUser}
                    <div id="user_role_{$oUser->getId()}_{$oRole->getId()}" class="ruser">
                        {$oUser->getLogin()}
                        <a href="#" onclick="DelUserRole('{$oRole->getId()}','{$oUser->getId()}'); return false;"
                           class="del-role">&#215;</a>
                    </div>
                {/foreach}
            {/if}
        </div>

        {assign var="aRole" value=$oRole->getRole()}
        <div id="role_r{$oRole->getId()}" style="display: none;">
            <form action="" method="POST" id="role_form_acl{$oRole->getId()}" enctype="multipart/form-data">
                <input type="hidden" id="role_id" name="role_id" value="{$oRole->getId()}"/>
                <br/>
                <div>
                    <label for="role_acl">{$aLang.plugin.role.create_acl}:</label>
                    <label><input type="checkbox"{if $aRole.user} checked{/if} name="role[user]"
                                  id="role_user{$oRole->getId()}" value="1"
                                  onclick="sh(this)"/> {$aLang.plugin.role.create_user}</label>

                    <div id="role_user{$oRole->getId()}_box" class="opt-role"{if $aRole.user}
                         style="display: block;"{/if}>
                        <label><input type="checkbox"{if $aRole.user.add} checked{/if} name="role[user][add]" value="1"
                                      id="role_user_add"/> {$aLang.plugin.role.create_user_add}</label>
                        <label><input type="checkbox"{if $aRole.user.edit} checked{/if} name="role[user][edit]"
                                      value="1" id="role_user_edit"/> {$aLang.plugin.role.create_user_edit}</label>
                        <label><input type="checkbox"{if $aRole.user.delete} checked{/if} name="role[user][delete]"
                                      value="1" id="role_user_delete"/> {$aLang.plugin.role.create_user_delete}
                        </label>
                        <label><input type="checkbox"{if $aRole.user.banned} checked{/if} name="role[user][banned]"
                                      value="1" id="role_user_banned"/> {$aLang.plugin.role.create_user_banned}
                        </label>
                    </div>
                    <br/>
                    <label><input type="checkbox"{if $aRole.blog} checked{/if} name="role[blog]" value="1"
                                  id="role_blog{$oRole->getId()}" onclick="sh(this)"/> {$aLang.plugin.role.create_blog}
                    </label>

                    <div id="role_blog{$oRole->getId()}_box" class="opt-role"{if $aRole.blog}
                         style="display: block;"{/if}>
                        <label><input type="checkbox"{if $aRole.blog.add} checked{/if} name="role[blog][add]" value="1"
                                      id="role_blog_add"/> {$aLang.plugin.role.create_blog_add}</label>
                        <label><input type="checkbox"{if $aRole.blog.edit} checked{/if} name="role[blog][edit]"
                                      value="1" id="role_blog_edit"/> {$aLang.plugin.role.create_blog_edit}</label>
                        <label><input type="checkbox"{if $aRole.blog.delete} checked{/if} name="role[blog][delete]"
                                      value="1" id="role_blog_delete"/> {$aLang.plugin.role.create_blog_delete}
                        </label>

                        <label><input type="checkbox"{if is_array($aRole.blog.topic)} checked{/if}
                                      name="role[blog][topic]" value="1" id="role_blog_topic{$oRole->getId()}"
                                      onclick="sh(this)"/> {$aLang.plugin.role.create_blog_topic}</label>

                        <div id="role_blog_topic{$oRole->getId()}_box" class="opt-role"{if $aRole.blog.topic}
                             style="display: block;"{/if}>
                            <label><input type="checkbox"{if is_array($aRole.blog.topic)}{if $aRole.blog.topic.add}
                                          checked{/if}{/if} name="role[blog][topic][add]" value="1"
                                          id="role_blog_topic_add"/> {$aLang.plugin.role.create_blog_topic_add}
                            </label>
                            <label><input type="checkbox"{if is_array($aRole.blog.topic)}{if $aRole.blog.topic.edit}
                                          checked{/if}{/if} name="role[blog][topic][edit]" value="1"
                                          id="role_blog_topic_edit"/> {$aLang.plugin.role.create_blog_topic_edit}
                            </label>
                            <label><input type="checkbox"{if is_array($aRole.blog.topic)}{if $aRole.blog.topic.delete}
                                          checked{/if}{/if} name="role[blog][topic][delete]" value="1"
                                          id="role_blog_topic_delete"/> {$aLang.plugin.role.create_blog_topic_delete}
                            </label>

                            <label><input type="checkbox"{if is_array($aRole.blog.topic)}{if $aRole.blog.topic.comment}
                                          checked{/if}{/if}  name="role[blog][topic][comment]" value="1"
                                          id="role_blog_topic_comment{$oRole->getId()}"
                                          onclick="sh(this)"/> {$aLang.plugin.role.create_blog_topic_comment}</label>

                            <div id="role_blog_topic_comment{$oRole->getId()}_box"
                                 class="opt-role"{if is_array($aRole.blog.topic)}{if $aRole.blog.topic.comment}
                                 style="display: block;"{/if}{/if}>
                                <label><input
                                        type="checkbox"{if is_array($aRole.blog.topic)}{if is_array($aRole.blog.topic.comment)}{if $aRole.blog.topic.comment.add}
                                        checked{/if}{/if}{/if} name="role[blog][topic][comment][add]" value="1"
                                        id="role_blog_topic_comment_add"/> {$aLang.plugin.role.create_blog_topic_comment_add}
                                </label>
                                <label><input
                                        type="checkbox"{if is_array($aRole.blog.topic)}{if is_array($aRole.blog.topic.comment)}{if $aRole.blog.topic.comment.edit}
                                        checked{/if}{/if}{/if} name="role[blog][topic][comment][edit]" value="1"
                                        id="role_blog_topic_comment_edit"/> {$aLang.plugin.role.create_blog_topic_comment_edit}
                                </label>
                                <label><input
                                        type="checkbox"{if is_array($aRole.blog.topic)}{if is_array($aRole.blog.topic.comment)}{if $aRole.blog.topic.comment.delete}
                                        checked{/if}{/if}{/if} name="role[blog][topic][comment][delete]" value="1"
                                        id="role_blog_topic_comment_delete"/> {$aLang.plugin.role.create_blog_topic_comment_delete}
                                </label>
                            </div>

                        </div>

                    </div>

                    {hook run='roles_role_show_end' role=$aRole id=$oRole->getId()}
                    <br/>

                </div>


                <input type="button" onclick="SaveRoleAcl('{$oRole->getId()}'); return false;" name="role_create_submit"
                       value="{$aLang.plugin.role.create_submit_save}" class="button button-primary fl-r" />
            </form>
        </div>


    </div>
    {/foreach}
    {else}
    {$aLang.plugin.role.list_empty}
{/if}

{include file='footer.tpl'}
