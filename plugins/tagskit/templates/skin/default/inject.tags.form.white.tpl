<script type="text/javascript">
    jQuery(function($){
        ls.plugin.tagskit.initFormWhite();
    });
</script>


<div class="modal modal-tk-white-list" id="modal_tk_white_list">
    <header class="modal-header">
        <h3>{$aLang.plugin.tagskit.choice_from_white.title}</h3>
        <a href="#" class="close jqmClose"></a>
    </header>

    <div class="modal-content">

		{include file=$aTemplatePathPlugin.tagskit|cat:"white.tags.items.tpl"}

		<br/>
        <button type="submit"  class="button" onclick="ls.plugin.tagskit.clearTags();">{$aLang.plugin.tagskit.choice_from_white.action.clear}</button>
        <button type="submit"  class="button" onclick="ls.plugin.tagskit.autoSearchTags(this);">{$aLang.plugin.tagskit.choice_from_white.action.auto_search}</button>
        <button type="submit"  class="button fl-r jqmClose">{$aLang.plugin.tagskit.close}</button>
        <br/>
    </div>
</div>
