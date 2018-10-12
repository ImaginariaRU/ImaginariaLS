
	{if $oUser}
		<!-- Popupinfo plugin -->
		<div class="Popupinfo_UserMoreInfo">
			<div class="AvatarHolder">
				<div class="Avatar">
					{assign var="oSession" value=$oUser->getSession()}
					{if $oSession}
						{assign var="dDateLastVisit" value=$oSession->getDateLast()}
						{assign var="bUserOnline" value=(time() - strtotime($dDateLastVisit))<$oConfig->GetValue("plugin.popupinfo.Time_To_Stay_Online")}
					{else}
						{assign var="bUserOnline" value=false}
					{/if}
					
					<img src="{$oUser->getProfileAvatarPath(48)}" alt="avatar" class="avatar" {if $dDateLastVisit}title="{date_format date=$dDateLastVisit hours_back="12" minutes_back="60" now="60" day="day H:i" format="d F Y, H:i"} {if $oUser->getProfileSex()=='man'}{$aLang.plugin.popupinfo.Was_Online_M}{elseif $oUser->getProfileSex()=='woman'}{$aLang.plugin.popupinfo.Was_Online_W}{else}{$aLang.plugin.popupinfo.Was_Online}{/if}"{/if} />
					
					{if $bUserOnline}
						<div class="OnlineStatus"></div>
					{/if}
				</div>
				
				{if $oUserCurrent and $oUserCurrent->getId()!=$oUser->getId()}
					<a class="Talk" href="{router page='talk'}add/?talk_users={$oUser->getLogin()}" title="{$aLang.user_write_prvmsg}"></a>
				{/if}
				
				{hook run='pi_user_info_avatar' oUser=$oUser}
			</div>
			<div class="TextHolder">
				{if $oUser->getProfileName()}
					<h3>{$oUser->getProfileName()|escape:'html'}</h3>
				{else}
					<h3>{$oUser->getLogin()}</h3>
				{/if}
				<div class="SecondLine">
					<span class="Sex {if $oUser->getProfileSex()=='man'}Man{elseif $oUser->getProfileSex()=='woman'}Woman{/if}">
						{if $oUser->getProfileSex()=='man'}
							{$aLang.plugin.popupinfo.Sex_M}
						{elseif $oUser->getProfileSex()=='woman'}
							{$aLang.plugin.popupinfo.Sex_W}
						{else}
							{$aLang.plugin.popupinfo.Sex_O}
						{/if}
					</span>
					<span class="Skill">
						{$oUser->getSkill()}
					</span>
					<span class="Rating">
						{$oUser->getRating()}
					</span>
				</div>
				<div class="SecondLine Location">
					{if $oGeoTarget}
						{if $oGeoTarget->getCountryId()}
							<a href="{router page='people'}country/{$oGeoTarget->getCountryId()}/">{$oUser->getProfileCountry()|escape:'html'}</a>{if $oGeoTarget->getCityId()},{/if}
						{/if}

						{if $oGeoTarget->getCityId()}
							<a href="{router page='people'}city/{$oGeoTarget->getCityId()}/">{$oUser->getProfileCity()|escape:'html'}</a>
						{/if}
					{/if}
				</div>
				<div class="SecondLine Publications">
					<dl>
						<dt>{$aLang.plugin.popupinfo.Wrote_Topics}:</dt>
						<dd>{$iCountTopicUser}</dd>
					</dl>
					<dl>
						<dt>{$aLang.plugin.popupinfo.Wrote_Comments}:</dt>
						<dd>{$iCountCommentUser}</dd>
					</dl>
				</div>
				{hook run='pi_user_info_text' oUser=$oUser}
				
			</div>
			{hook run='pi_user_info_wrapper' oUser=$oUser}
			<div class="Rights"><a href="http://psnet.lookformp3.net/">Powered by PSNet</a></div>
		</div>
		<!-- /Popupinfo plugin -->
	{/if}
