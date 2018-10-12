    	<div id="ignore_setting_form" class="modal">
    		<header class="modal-header">
    			<h3>{$aLang.plugin.ignore.settings}</h3>
    			<a href="#" class="close jqmClose"></a>
    		</header>
    
    		<form onsubmit="return ls.ignore.updateIgnore(this,{$oUserProfile->getId()});" class="modal-content">
                {if $oConfig->GetValue('plugin.ignore.ignore_post_me_pm')}
                    <p><label><input type="checkbox" name="ignore1" value="1" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnorePostMePM())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_post_me_pm}</label></p>
                {/if}
                {if $oConfig->GetValue('plugin.ignore.ignore_hide_me_comments')}
                    <p><label><input type="checkbox" name="ignore2" value="2" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsHideMeComments())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_hide_me_comments}</label></p>
                {/if}
                {if $oConfig->GetValue('plugin.ignore.ignore_reply_my_comment')}
                    <p><label><input type="checkbox" name="ignore3" value="3" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnoreRyplyMyComment())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_reply_my_comment}</label></p>
                {/if}
                {if $oConfig->GetValue('plugin.ignore.ignore_post_comment_my_topic')}
                    <p><label><input type="checkbox" name="ignore4" value="4" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnorePostMyTopic())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_post_comment_my_topic}</label></p>
                {/if}
                {if $oConfig->GetValue('plugin.ignore.ignore_post_my_wall')}
                    <p><label><input type="checkbox" name="ignore5" value="5" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnorePostMyWall())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_post_my_wall}</label></p>
                {/if}
                <p><label>{$aLang.plugin.ignore.reason_null}<br /><input type="text" name="reason" id="ignore_setting_reason" class="input-text input-width-full" maxlength="240" value="{if $oIgnore}{$oIgnore->getReason()}{/if}" /></label></p>
    			<button type="submit" class="button button-primary">{$aLang.plugin.ignore.submit_apply}</button>
    		</form>
    	</div>