{* Purpose of this template: edit view of generic item list content type *}
<div class="form-group">
    {gt text='Object type' domain='pggoteammodule' assign='objectTypeSelectorLabel'}
    {formlabel for='pggoTeamModuleObjectType' text=$objectTypeSelectorLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {pggoteammoduleObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='pggoTeamModuleOjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes cssClass='form-control'}
        <span class="help-block">{gt text='If you change this please save the element once to reload the parameters below.' domain='pggoteammodule'}</span>
    </div>
</div>

{if $featureActivationHelper->isEnabled(const('Pggo\\TeamModule\\Helper\\FeatureActivationHelper::CATEGORIES', $objectType))}
{formvolatile}
{if $properties ne null && is_array($properties)}
    {nocache}
    {foreach key='registryId' item='registryCid' from=$registries}
        {assign var='propName' value=''}
        {foreach key='propertyName' item='propertyId' from=$properties}
            {if $propertyId eq $registryId}
                {assign var='propName' value=$propertyName}
            {/if}
        {/foreach}
        <div class="form-group">
            {assign var='hasMultiSelection' value=$categoryHelper->hasMultipleSelection($objectType, $propertyName)}
            {gt text='Category' domain='pggoteammodule' assign='categorySelectorLabel'}
            {assign var='selectionMode' value='single'}
            {if $hasMultiSelection eq true}
                {gt text='Categories' domain='pggoteammodule' assign='categorySelectorLabel'}
                {assign var='selectionMode' value='multiple'}
            {/if}
            {formlabel for="pggoTeamModuleCatIds`$propertyName`" text=$categorySelectorLabel cssClass='col-sm-3 control-label'}
            <div class="col-sm-9">
                {formdropdownlist id="pggoTeamModuleCatIds`$propName`" items=$categories.$propName dataField="catids`$propName`" group='data' selectionMode=$selectionMode cssClass='form-control'}
                <span class="help-block">{gt text='This is an optional filter.' domain='pggoteammodule'}</span>
            </div>
        </div>
    {/foreach}
    {/nocache}
{/if}
{/formvolatile}
{/if}

<div class="form-group">
    {gt text='Sorting' domain='pggoteammodule' assign='sortingLabel'}
    {formlabel text=$sortingLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formradiobutton id='pggoTeamModuleSortRandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='pggoteammodule' assign='sortingRandomLabel'}
        {formlabel for='pggoTeamModuleSortRandom' text=$sortingRandomLabel}
        {formradiobutton id='pggoTeamModuleSortNewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='pggoteammodule' assign='sortingNewestLabel'}
        {formlabel for='pggoTeamModuleSortNewest' text=$sortingNewestLabel}
        {formradiobutton id='pggoTeamModuleSortDefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='pggoteammodule' assign='sortingDefaultLabel'}
        {formlabel for='pggoTeamModuleSortDefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="form-group">
    {gt text='Amount' domain='pggoteammodule' assign='amountLabel'}
    {formlabel for='pggoTeamModuleAmount' text=$amountLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formintinput id='pggoTeamModuleAmount' dataField='amount' group='data' mandatory=true maxLength=2}
    </div>
</div>

<div class="form-group">
    {gt text='Template' domain='pggoteammodule' assign='templateLabel'}
    {formlabel for='pggoTeamModuleTemplate' text=$templateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {pggoteammoduleTemplateSelector assign='allTemplates'}
        {formdropdownlist id='pggoTeamModuleTemplate' dataField='template' group='data' mandatory=true items=$allTemplates cssClass='form-control'}
    </div>
</div>

<div id="customTemplateArea" class="form-group" data-switch="pggoTeamModuleTemplate" data-switch-value="custom">
    {gt text='Custom template' domain='pggoteammodule' assign='customTemplateLabel'}
    {formlabel for='pggoTeamModuleCustomTemplate' text=$customTemplateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='pggoTeamModuleCustomTemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80 cssClass='form-control'}
        <span class="help-block">{gt text='Example' domain='pggoteammodule'}: <em>itemlist_[objectType]_display.tpl</em></span>
    </div>
</div>

<div class="form-group">
    {gt text='Filter (expert option)' domain='pggoteammodule' assign='filterLabel'}
    {formlabel for='pggoTeamModuleFilter' text=$filterLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='pggoTeamModuleFilter' dataField='filter' group='data' mandatory=false maxLength=255 cssClass='form-control'}
        {*<span class="help-block">
            <a class="fa fa-filter" data-toggle="modal" data-target="#filterSyntaxModal">{gt text='Show syntax examples' domain='pggoteammodule'}</a>
        </span>*}
    </div>
</div>

{*include file='include_filterSyntaxDialog.tpl'*}

{pageaddvar name='stylesheet' value='web/bootstrap/css/bootstrap.min.css'}
{pageaddvar name='stylesheet' value='web/bootstrap/css/bootstrap-theme.min.css'}
{pageaddvar name='javascript' value='web/bootstrap/js/bootstrap.min.js'}
