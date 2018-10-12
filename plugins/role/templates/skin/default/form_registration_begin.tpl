{if $aRoleReg}
<script type='text/javascript' src='{$oConfig->get('path.root.web')}/engine/lib/external/jquery/jquery.js'></script>
    {literal}
    <script type="text/javascript">
        $(document).ready(function () {
            $('.role-reg').click(function () {
                $('#role_id').val($(this).attr('tabindex'));
                $('.role-reg').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
    {/literal}
<div class="roler">
    <label>{$aLang.plugin.role.role_select_registration_role}</label><br/>

    <input type="hidden" name="role_add" id="role_add" value="true"/>
    <input type="hidden" name="role_id" id="role_id" value="{$_aRequest.role_id}"/>

    {foreach from=$aRoleReg item=oRole}
        <div class="role-reg" tabindex="{$oRole->getId()}">
            <div class="name">
                <img src="{$oRole->getAvatarPath(96)}" class="av_role"/>
                {$oRole->getName()}
            </div>
            <div class="text">
                {$oRole->getText()}
            </div>
        </div>
    {/foreach}
</div>
{/if}