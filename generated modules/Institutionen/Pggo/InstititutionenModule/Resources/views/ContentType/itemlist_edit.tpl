{* Purpose of this template: edit view of generic item list content type *}
<div class="form-group">
    {gt text='Object type' domain='pggoinstititutionenmodule' assign='objectTypeSelectorLabel'}
    {formlabel for='pggoInstititutionenModuleObjectType' text=$objectTypeSelectorLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {pggoinstititutionenmoduleObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='pggoInstititutionenModuleObjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes cssClass='form-control'}
        <span class="help-block">{gt text='If you change this please save the element once to reload the parameters below.' domain='pggoinstititutionenmodule'}</span>
    </div>
</div>

<div class="form-group">
    {gt text='Sorting' domain='pggoinstititutionenmodule' assign='sortingLabel'}
    {formlabel text=$sortingLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formradiobutton id='pggoInstititutionenModuleSortRandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='pggoinstititutionenmodule' assign='sortingRandomLabel'}
        {formlabel for='pggoInstititutionenModuleSortRandom' text=$sortingRandomLabel}
        {formradiobutton id='pggoInstititutionenModuleSortNewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='pggoinstititutionenmodule' assign='sortingNewestLabel'}
        {formlabel for='pggoInstititutionenModuleSortNewest' text=$sortingNewestLabel}
        {formradiobutton id='pggoInstititutionenModuleSortDefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='pggoinstititutionenmodule' assign='sortingDefaultLabel'}
        {formlabel for='pggoInstititutionenModuleSortDefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="form-group">
    {gt text='Amount' domain='pggoinstititutionenmodule' assign='amountLabel'}
    {formlabel for='pggoInstititutionenModuleAmount' text=$amountLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formintinput id='pggoInstititutionenModuleAmount' dataField='amount' group='data' mandatory=true maxLength=2 cssClass='form-control'}
    </div>
</div>

<div class="form-group">
    {gt text='Template' domain='pggoinstititutionenmodule' assign='templateLabel'}
    {formlabel for='pggoInstititutionenModuleTemplate' text=$templateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {pggoinstititutionenmoduleTemplateSelector assign='allTemplates'}
        {formdropdownlist id='pggoInstititutionenModuleTemplate' dataField='template' group='data' mandatory=true items=$allTemplates cssClass='form-control'}
    </div>
</div>

<div id="customTemplateArea" class="form-group"{* data-switch="pggoInstititutionenModuleTemplate" data-switch-value="custom"*}>
    {gt text='Custom template' domain='pggoinstititutionenmodule' assign='customTemplateLabel'}
    {formlabel for='pggoInstititutionenModuleCustomTemplate' text=$customTemplateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='pggoInstititutionenModuleCustomTemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80 cssClass='form-control'}
        <span class="help-block">{gt text='Example' domain='pggoinstititutionenmodule'}: <em>itemlist_[objectType]_display.html.twig</em></span>
    </div>
</div>

<div class="form-group">
    {gt text='Filter (expert option)' domain='pggoinstititutionenmodule' assign='filterLabel'}
    {formlabel for='pggoInstititutionenModuleFilter' text=$filterLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='pggoInstititutionenModuleFilter' dataField='filter' group='data' mandatory=false maxLength=255 cssClass='form-control'}
        {*<span class="help-block">
            <a class="fa fa-filter" data-toggle="modal" data-target="#filterSyntaxModal">{gt text='Show syntax examples' domain='pggoinstititutionenmodule'}</a>
        </span>*}
    </div>
</div>

{*include file='include_filterSyntaxDialog.tpl'*}

<script type="text/javascript">
    (function($) {
    	$('#pggoInstititutionenModuleTemplate').change(function() {
    	    $('#customTemplateArea').toggleClass('hidden', $(this).val() != 'custom');
	    }).trigger('change');
    })(jQuery)
</script>
