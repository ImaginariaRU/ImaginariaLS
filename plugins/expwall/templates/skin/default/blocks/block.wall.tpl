<section class="block block-type-wall">
    <header class="block-header sep">
        <h3><a href="{router page='wall'}">{$aLang.plugin.expwall.title_block}</a></h3>
    </header>

    <div class="block-content">
        <div class="js-block-wall-content">
{if $aWall}
        {foreach from=$aWall item=oWall}
        {assign var="oWallUser" value=$oWall->getUser()}
        {assign var="aReplyWall" value=$oWall->getLastReplyWall()}

            <div id="wall-item-{$oWall->getId()}" class="js-wall-item wall-item-wrapper">
                <div class="wall-item">
                    <a href="{$oWallUser->getUserWebPath()}"><img src="{$oWallUser->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>

                    <p class="info">
                        <a href="{$oWallUser->getUserWebPath()}wall/">{$oWallUser->getLogin()}</a> ·
                        <time class="date" datetime="{date_format date=$oWall->getDateAdd() format='c'}">{date_format date=$oWall->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time>
                    </p>

                    <div class="wall-item-content text">
                        {$oWall->getText()}
                    </div>

                </div>


            </div>
        {/foreach}
{/if}

        </div>
    </div>
</section>