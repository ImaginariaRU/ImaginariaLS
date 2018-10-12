{if $oRole and $oRole->getAvatar()}
<div style="position: absolute; top: 130px; left: 12px; text-align: center; ">
    <div style="text-align: center;">{$oRole->getTitle()}</div>
    <img src="{$oRole->getAvatarPath(24)}"/>
</div>
{/if}

