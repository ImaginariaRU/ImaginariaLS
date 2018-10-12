
	{if $oBlog}
		<!-- Popupinfo plugin -->
		<div class="Popupinfo_UserMoreInfo">
			<div class="AvatarHolder">
				<img src="{$oBlog->getAvatarPath(48)}" alt="avatar" class="avatar" />
				<br />
				<a href="{router page='rss'}blog/{$oBlog->getUrl()}/" class="RSS" title="RSS"></a>
				{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
					<a href="#" onclick="ls.blog.toggleJoin (this, {$oBlog->getId()}); return false;" class="JoinBlog {if $oBlog->getUserIsJoin()}active{/if}" title="{$aLang.Popupinfo_JoinBlog}"></a>
				{/if}
				
				{hook run='pi_blog_info_avatar' oBlog=$oBlog}
			</div>
			<div class="TextHolder">
				<h3>{$oBlog->getTitle()}</h3>
				<div class="SecondLine">
					<span class="Rating">
						{$oBlog->getRating()}
					</span>
				</div>
				<div class="SecondLine Description">
					{$oBlog->getDescription()}
				</div>
				<div class="SecondLine Publications NoBg">
					<dl>
						<dt>{$aLang.blog_user_administrators}:</dt>
						<dd>{$iCountBlogAdministrators}</dd>
					</dl>
					
					{assign var="oUserOwner" value=$oBlog->getOwner()}
					<ul class="PeopleList">
						<li>
							<a href="{$oUserOwner->getUserWebPath()}"><img src="{$oUserOwner->getProfileAvatarPath(24)}" alt="" title="{$oUserOwner->getLogin()}" /></a>
						</li>
						{if $aBlogAdministrators}
							{foreach from=$aBlogAdministrators item=oBlogUser}
								{assign var="oUser" value=$oBlogUser->getUser()}
								<li>
									<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" title="{$oUser->getLogin()}" /></a>
								</li>
							{/foreach}
						{/if}
					</ul>

					<dl>
						<dt>{$aLang.blog_user_moderators}:</dt>
						<dd>{$iCountBlogModerators}</dd>
					</dl>
					
					{if $aBlogModerators}
						<ul class="PeopleList">
							{foreach from=$aBlogModerators item=oBlogUser}
								{assign var="oUser" value=$oBlogUser->getUser()}
								<li>
									<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" title="{$oUser->getLogin()}" /></a>
								</li>
							{/foreach}
						</ul>
					{/if}
					
					<dl>
						<dt>{$aLang.blog_user_readers}:</dt>
						<dd>{$iCountBlogUsers}</dd>
					</dl>
					
					{if $aBlogUsers}
						<ul class="PeopleList">
							{foreach from=$aBlogUsers item=oBlogUser}
								{assign var="oUser" value=$oBlogUser->getUser()}
								<li>
									<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" title="{$oUser->getLogin()}" /></a>
								</li>
							{/foreach}
						</ul>
						{if count($aBlogUsers)<$iCountBlogUsers}
						<br />
						<a href="{$oBlog->getUrlFull()}users/">{$aLang.blog_user_readers_all}</a>
						{/if}
					{/if}
					
				</div>
				{hook run='pi_blog_info_text' oBlog=$oBlog}
				
			</div>
			{hook run='pi_blog_info_wrapper' oBlog=$oBlog}
			<div class="Rights"><a href="http://psnet.lookformp3.net/">Powered by PSNet</a></div>
		</div>
		<!-- /Popupinfo plugin -->
	{/if}
