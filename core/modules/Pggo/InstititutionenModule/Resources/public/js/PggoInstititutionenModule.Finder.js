'use strict';

var currentPggoInstititutionenModuleEditor = null;
var currentPggoInstititutionenModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getPggoInstititutionenModulePopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function PggoInstititutionenModuleFinderCKEditor(editor, institUrl)
{
    // Save editor for access in selector window
    currentPggoInstititutionenModuleEditor = editor;

    editor.popup(
        Routing.generate('pggoinstititutionenmodule_external_finder', { objectType: 'institution', editor: 'ckeditor' }),
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}


var pggoInstititutionenModule = {};

pggoInstititutionenModule.finder = {};

pggoInstititutionenModule.finder.onLoad = function (baseId, selectedId)
{
    var imageModeEnabled;

    imageModeEnabled = jQuery("[id$='onlyImages']").prop('checked');
    if (!imageModeEnabled) {
        jQuery('#imageFieldRow').addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=6]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=7]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=8]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=9]").addClass('hidden');
    } else {
        jQuery('#searchTermRow').addClass('hidden');
    }

    jQuery('input[type="checkbox"]').click(pggoInstititutionenModule.finder.onParamChanged);
    jQuery('select').not("[id$='pasteAs']").change(pggoInstititutionenModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(pggoInstititutionenModule.finder.handleCancel);

    var selectedItems = jQuery('#pggoinstititutionenmoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        pggoInstititutionenModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

pggoInstititutionenModule.finder.onParamChanged = function ()
{
    jQuery('#pggoInstititutionenModuleSelectorForm').submit();
};

pggoInstititutionenModule.finder.handleCancel = function (event)
{
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        pggoInstititutionenClosePopup();
    } else if ('ckeditor' === editor) {
        pggoInstititutionenClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function pggoInstititutionenGetPasteSnippet(mode, itemId)
{
    var quoteFinder;
    var itemPath;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var imagePath;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemPath = jQuery('#path' + itemId).val().replace(quoteFinder, '');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    imagePath = jQuery('#imagePath' + itemId).length > 0 ? jQuery('#imagePath' + itemId).val().replace(quoteFinder, '') : '';
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if (pasteMode === '3') {
        return '' + itemId;
    }

    // relative link to detail page
    if (pasteMode === '1') {
        return mode === 'url' ? itemPath : '<a href="' + itemPath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    // absolute url to detail page
    if (pasteMode === '2') {
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    if (pasteMode === '6') {
        // relative link to image file
        return mode === 'url' ? imagePath : '<a href="' + imagePath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    if (pasteMode === '7') {
        // image tag
        return '<img src="' + imagePath + '" alt="' + itemTitle + '" width="300" />';
    }
    if (pasteMode === '8') {
        // image tag with relative link to detail page
        return mode === 'url' ? itemPath : '<a href="' + itemPath + '" title="' + itemTitle + '"><img src="' + imagePath + '" alt="' + itemTitle + '" width="300" /></a>';
    }
    if (pasteMode === '9') {
        // image tag with absolute url to detail page
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemTitle + '"><img src="' + imagePath + '" alt="' + itemTitle + '" width="300" /></a>';
    }


    return '';
}


// User clicks on "select item" button
pggoInstititutionenModule.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        html = pggoInstititutionenGetPasteSnippet('html', itemId);
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if ('ckeditor' === editor) {
        if (null !== window.opener.currentPggoInstititutionenModuleEditor) {
            html = pggoInstititutionenGetPasteSnippet('html', itemId);

            window.opener.currentPggoInstititutionenModuleEditor.insertHtml(html);
        }
    } else {
        alert('Insert into Editor: ' + editor);
    }
    pggoInstititutionenClosePopup();
};

function pggoInstititutionenClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// PggoInstititutionenModule item selector for Forms
//=============================================================================

pggoInstititutionenModule.itemSelector = {};
pggoInstititutionenModule.itemSelector.items = {};
pggoInstititutionenModule.itemSelector.baseId = 0;
pggoInstititutionenModule.itemSelector.selectedId = 0;

pggoInstititutionenModule.itemSelector.onLoad = function (baseId, selectedId)
{
    pggoInstititutionenModule.itemSelector.baseId = baseId;
    pggoInstititutionenModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#pggoInstititutionenModuleObjectType').change(pggoInstititutionenModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(pggoInstititutionenModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(pggoInstititutionenModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(pggoInstititutionenModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(pggoInstititutionenModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(pggoInstititutionenModule.itemSelector.onParamChanged);
    jQuery('#pggoInstititutionenModuleSearchGo').click(pggoInstititutionenModule.itemSelector.onParamChanged);
    jQuery('#pggoInstititutionenModuleSearchGo').keypress(pggoInstititutionenModule.itemSelector.onParamChanged);

    pggoInstititutionenModule.itemSelector.getItemList();
};

pggoInstititutionenModule.itemSelector.onParamChanged = function ()
{
    jQuery('#ajax_indicator').removeClass('hidden');

    pggoInstititutionenModule.itemSelector.getItemList();
};

pggoInstititutionenModule.itemSelector.getItemList = function ()
{
    var baseId;
    var params;

    baseId = pggoInstititutionenModule.itemSelector.baseId;
    params = {
        ot: baseId,
        sort: jQuery('#' + baseId + 'Sort').val(),
        sortdir: jQuery('#' + baseId + 'SortDir').val(),
        q: jQuery('#' + baseId + 'SearchTerm').val()
    }
    if (jQuery('#' + baseId + '_catidMain').length > 0) {
        params[catidMain] = jQuery('#' + baseId + '_catidMain').val();
    } else if (jQuery('#' + baseId + '_catidsMain').length > 0) {
        params[catidsMain] = jQuery('#' + baseId + '_catidsMain').val();
    }

    jQuery.ajax({
        type: 'POST',
        url: Routing.generate('pggoinstititutionenmodule_ajax_getitemlistfinder'),
        data: params
    }).done(function(res) {
        // get data returned by the ajax response
        var baseId;
        baseId = pggoInstititutionenModule.itemSelector.baseId;
        pggoInstititutionenModule.itemSelector.items[baseId] = res.data;
        jQuery('#ajax_indicator').addClass('hidden');
        pggoInstititutionenModule.itemSelector.updateItemDropdownEntries();
        pggoInstititutionenModule.itemSelector.updatePreview();
    });
};

pggoInstititutionenModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = pggoInstititutionenModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = pggoInstititutionenModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.get(0).options[i] = new Option(item.title, item.id, false);
    }

    if (pggoInstititutionenModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(pggoInstititutionenModule.itemSelector.selectedId);
    }
};

pggoInstititutionenModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = pggoInstititutionenModule.itemSelector.baseId;
    items = pggoInstititutionenModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (pggoInstititutionenModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id == pggoInstititutionenModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
        pggoInstititutionenInitImageViewer();
    }
};

pggoInstititutionenModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = pggoInstititutionenModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(pggoInstititutionenModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    pggoInstititutionenModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
    pggoInstititutionenInitImageViewer();
};
