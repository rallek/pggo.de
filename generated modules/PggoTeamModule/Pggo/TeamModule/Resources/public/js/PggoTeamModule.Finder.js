'use strict';

var currentPggoTeamModuleEditor = null;
var currentPggoTeamModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getPggoTeamModulePopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function PggoTeamModuleFinderCKEditor(editor, teamUrl)
{
    // Save editor for access in selector window
    currentPggoTeamModuleEditor = editor;

    editor.popup(
        Routing.generate('pggoteammodule_external_finder', { objectType: 'person', editor: 'ckeditor' }),
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}


var pggoTeamModule = {};

pggoTeamModule.finder = {};

pggoTeamModule.finder.onLoad = function (baseId, selectedId)
{
    var imageModeEnabled;

    imageModeEnabled = jQuery("[id$='onlyImages']").prop('checked');
    if (!imageModeEnabled) {
        jQuery('#imageFieldRow').addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=6]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=7]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=8]").addClass('hidden');
    } else {
        jQuery('#searchTermRow').addClass('hidden');
    }

    jQuery('input[type="checkbox"]').click(pggoTeamModule.finder.onParamChanged);
    jQuery('select').not("[id$='pasteAs']").change(pggoTeamModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(pggoTeamModule.finder.handleCancel);

    var selectedItems = jQuery('#pggoteammoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        pggoTeamModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

pggoTeamModule.finder.onParamChanged = function ()
{
    jQuery('#pggoTeamModuleSelectorForm').submit();
};

pggoTeamModule.finder.handleCancel = function ()
{
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        pggoTeamClosePopup();
    } else if ('ckeditor' === editor) {
        pggoTeamClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function pggoTeamGetPasteSnippet(mode, itemId)
{
    var quoteFinder;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var imageUrl;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    imageUrl = jQuery('#imageUrl' + itemId).length > 0 ? jQuery('#imageUrl' + itemId).val().replace(quoteFinder, '') : '';
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if (pasteMode === '2') {
        return '' + itemId;
    }

    // link to detail page
    if (pasteMode === '1') {
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    if (pasteMode === '6') {
        // link to image file
        return mode === 'url' ? imageUrl : '<a href="' + imageUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    if (pasteMode === '7') {
        // image tag
        return '<img src="' + imageUrl + '" alt="' + itemTitle + '" width="300" />';
    }
    if (pasteMode === '8') {
        // image tag with link to detail page
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemTitle + '"><img src="' + imageUrl + '" alt="' + itemTitle + '" width="300" /></a>';
    }


    return '';
}


// User clicks on "select item" button
pggoTeamModule.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        html = pggoTeamGetPasteSnippet('html', itemId);
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if ('ckeditor' === editor) {
        if (null !== window.opener.currentPggoTeamModuleEditor) {
            html = pggoTeamGetPasteSnippet('html', itemId);

            window.opener.currentPggoTeamModuleEditor.insertHtml(html);
        }
    } else {
        alert('Insert into Editor: ' + editor);
    }
    pggoTeamClosePopup();
};

function pggoTeamClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// PggoTeamModule item selector for Forms
//=============================================================================

pggoTeamModule.itemSelector = {};
pggoTeamModule.itemSelector.items = {};
pggoTeamModule.itemSelector.baseId = 0;
pggoTeamModule.itemSelector.selectedId = 0;

pggoTeamModule.itemSelector.onLoad = function (baseId, selectedId)
{
    pggoTeamModule.itemSelector.baseId = baseId;
    pggoTeamModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#pggoTeamModuleObjectType').change(pggoTeamModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(pggoTeamModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(pggoTeamModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(pggoTeamModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(pggoTeamModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(pggoTeamModule.itemSelector.onParamChanged);
    jQuery('#pggoTeamModuleSearchGo').click(pggoTeamModule.itemSelector.onParamChanged);
    jQuery('#pggoTeamModuleSearchGo').keypress(pggoTeamModule.itemSelector.onParamChanged);

    pggoTeamModule.itemSelector.getItemList();
};

pggoTeamModule.itemSelector.onParamChanged = function ()
{
    jQuery('#ajax_indicator').removeClass('hidden');

    pggoTeamModule.itemSelector.getItemList();
};

pggoTeamModule.itemSelector.getItemList = function ()
{
    var baseId;
    var params;

    baseId = pggoTeamModule.itemSelector.baseId;
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
        url: Routing.generate('pggoteammodule_ajax_getitemlistfinder'),
        data: params
    }).done(function(res) {
        // get data returned by the ajax response
        var baseId;
        baseId = pggoTeamModule.itemSelector.baseId;
        pggoTeamModule.itemSelector.items[baseId] = res.data;
        jQuery('#ajax_indicator').addClass('hidden');
        pggoTeamModule.itemSelector.updateItemDropdownEntries();
        pggoTeamModule.itemSelector.updatePreview();
    });
};

pggoTeamModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = pggoTeamModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = pggoTeamModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.get(0).options[i] = new Option(item.title, item.id, false);
    }

    if (pggoTeamModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(pggoTeamModule.itemSelector.selectedId);
    }
};

pggoTeamModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = pggoTeamModule.itemSelector.baseId;
    items = pggoTeamModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (pggoTeamModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id == pggoTeamModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
        pggoTeamInitImageViewer();
    }
};

pggoTeamModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = pggoTeamModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(pggoTeamModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    pggoTeamModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
    pggoTeamInitImageViewer();
};
