{* Purpose of this template: edit view of generic item list content type *}
<div class="form-group">
    {gt text='Object type' domain='pggomediaattachmodule' assign='objectTypeSelectorLabel'}
    {formlabel for='pggoMediaAttachModuleObjectType' text=$objectTypeSelectorLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {pggomediaattachmoduleObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='pggoMediaAttachModuleObjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes cssClass='form-control'}
        <span class="help-block">{gt text='If you change this please save the element once to reload the parameters below.' domain='pggomediaattachmodule'}</span>
    </div>
</div>

<div class="form-group">
    {gt text='Sorting' domain='pggomediaattachmodule' assign='sortingLabel'}
    {formlabel text=$sortingLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formradiobutton id='pggoMediaAttachModuleSortRandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='pggomediaattachmodule' assign='sortingRandomLabel'}
        {formlabel for='pggoMediaAttachModuleSortRandom' text=$sortingRandomLabel}
        {formradiobutton id='pggoMediaAttachModuleSortNewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='pggomediaattachmodule' assign='sortingNewestLabel'}
        {formlabel for='pggoMediaAttachModuleSortNewest' text=$sortingNewestLabel}
        {formradiobutton id='pggoMediaAttachModuleSortDefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='pggomediaattachmodule' assign='sortingDefaultLabel'}
        {formlabel for='pggoMediaAttachModuleSortDefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="form-group">
    {gt text='Amount' domain='pggomediaattachmodule' assign='amountLabel'}
    {formlabel for='pggoMediaAttachModuleAmount' text=$amountLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formintinput id='pggoMediaAttachModuleAmount' dataField='amount' group='data' mandatory=true maxLength=2 cssClass='form-control'}
    </div>
</div>

<div class="form-group">
    {gt text='Template' domain='pggomediaattachmodule' assign='templateLabel'}
    {formlabel for='pggoMediaAttachModuleTemplate' text=$templateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {pggomediaattachmoduleTemplateSelector assign='allTemplates'}
        {formdropdownlist id='pggoMediaAttachModuleTemplate' dataField='template' group='data' mandatory=true items=$allTemplates cssClass='form-control'}
    </div>
</div>

<div id="customTemplateArea" class="form-group"{* data-switch="pggoMediaAttachModuleTemplate" data-switch-value="custom"*}>
    {gt text='Custom template' domain='pggomediaattachmodule' assign='customTemplateLabel'}
    {formlabel for='pggoMediaAttachModuleCustomTemplate' text=$customTemplateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='pggoMediaAttachModuleCustomTemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80 cssClass='form-control'}
        <span class="help-block">{gt text='Example' domain='pggomediaattachmodule'}: <em>itemlist_[objectType]_display.html.twig</em></span>
    </div>
</div>

<div class="form-group">
    {gt text='Filter (expert option)' domain='pggomediaattachmodule' assign='filterLabel'}
    {formlabel for='pggoMediaAttachModuleFilter' text=$filterLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='pggoMediaAttachModuleFilter' dataField='filter' group='data' mandatory=false maxLength=255 cssClass='form-control'}
        {*<span class="help-block">
            <a class="fa fa-filter" data-toggle="modal" data-target="#filterSyntaxModal">{gt text='Show syntax examples' domain='pggomediaattachmodule'}</a>
        </span>*}
    </div>
</div>

{*include file='include_filterSyntaxDialog.tpl'*}

<script type="text/javascript">
    (function($) {
    	$('#pggoMediaAttachModuleTemplate').change(function() {
    	    $('#customTemplateArea').toggleClass('hidden', $(this).val() != 'custom');
	    }).trigger('change');
    })(jQuery)
</script>
