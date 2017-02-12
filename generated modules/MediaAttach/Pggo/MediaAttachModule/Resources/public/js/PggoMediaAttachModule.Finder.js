'use strict';

var currentPggoMediaAttachModuleEditor = null;
var currentPggoMediaAttachModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getPggoMediaAttachModulePopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function PggoMediaAttachModuleFinderCKEditor(editor, mediaattachUrl)
{
    // Save editor for access in selector window
    currentPggoMediaAttachModuleEditor = editor;

    editor.popup(
        Routing.generate('pggomediaattachmodule_external_finder', { objectType: 'file', editor: 'ckeditor' }),
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}


var pggoMediaAttachModule = {};

pggoMediaAttachModule.finder = {};

pggoMediaAttachModule.finder.onLoad = function (baseId, selectedId)
{
    jQuery('select').not("[id$='pasteAs']").change(pggoMediaAttachModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(pggoMediaAttachModule.finder.handleCancel);

    var selectedItems = jQuery('#pggomediaattachmoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        pggoMediaAttachModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

pggoMediaAttachModule.finder.onParamChanged = function ()
{
    jQuery('#pggoMediaAttachModuleSelectorForm').submit();
};

pggoMediaAttachModule.finder.handleCancel = function ()
{
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        pggoMediaAttachClosePopup();
    } else if ('ckeditor' === editor) {
        pggoMediaAttachClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function pggoMediaAttachGetPasteSnippet(mode, itemId)
{
    var quoteFinder;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if (pasteMode === '2') {
        return '' + itemId;
    }

    // link to detail page
    if (pasteMode === '1') {
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    return '';
}


// User clicks on "select item" button
pggoMediaAttachModule.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        html = pggoMediaAttachGetPasteSnippet('html', itemId);
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if ('ckeditor' === editor) {
        if (null !== window.opener.currentPggoMediaAttachModuleEditor) {
            html = pggoMediaAttachGetPasteSnippet('html', itemId);

            window.opener.currentPggoMediaAttachModuleEditor.insertHtml(html);
        }
    } else {
        alert('Insert into Editor: ' + editor);
    }
    pggoMediaAttachClosePopup();
};

function pggoMediaAttachClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// PggoMediaAttachModule item selector for Forms
//=============================================================================

pggoMediaAttachModule.itemSelector = {};
pggoMediaAttachModule.itemSelector.items = {};
pggoMediaAttachModule.itemSelector.baseId = 0;
pggoMediaAttachModule.itemSelector.selectedId = 0;

pggoMediaAttachModule.itemSelector.onLoad = function (baseId, selectedId)
{
    pggoMediaAttachModule.itemSelector.baseId = baseId;
    pggoMediaAttachModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#pggoMediaAttachModuleObjectType').change(pggoMediaAttachModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(pggoMediaAttachModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(pggoMediaAttachModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(pggoMediaAttachModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(pggoMediaAttachModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(pggoMediaAttachModule.itemSelector.onParamChanged);
    jQuery('#pggoMediaAttachModuleSearchGo').click(pggoMediaAttachModule.itemSelector.onParamChanged);
    jQuery('#pggoMediaAttachModuleSearchGo').keypress(pggoMediaAttachModule.itemSelector.onParamChanged);

    pggoMediaAttachModule.itemSelector.getItemList();
};

pggoMediaAttachModule.itemSelector.onParamChanged = function ()
{
    jQuery('#ajax_indicator').removeClass('hidden');

    pggoMediaAttachModule.itemSelector.getItemList();
};

pggoMediaAttachModule.itemSelector.getItemList = function ()
{
    var baseId;
    var params;

    baseId = pggoMediaAttachModule.itemSelector.baseId;
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
        url: Routing.generate('pggomediaattachmodule_ajax_getitemlistfinder'),
        data: params
    }).done(function(res) {
        // get data returned by the ajax response
        var baseId;
        baseId = pggoMediaAttachModule.itemSelector.baseId;
        pggoMediaAttachModule.itemSelector.items[baseId] = res.data;
        jQuery('#ajax_indicator').addClass('hidden');
        pggoMediaAttachModule.itemSelector.updateItemDropdownEntries();
        pggoMediaAttachModule.itemSelector.updatePreview();
    });
};

pggoMediaAttachModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = pggoMediaAttachModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = pggoMediaAttachModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.get(0).options[i] = new Option(item.title, item.id, false);
    }

    if (pggoMediaAttachModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(pggoMediaAttachModule.itemSelector.selectedId);
    }
};

pggoMediaAttachModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = pggoMediaAttachModule.itemSelector.baseId;
    items = pggoMediaAttachModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (pggoMediaAttachModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id == pggoMediaAttachModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
    }
};

pggoMediaAttachModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = pggoMediaAttachModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(pggoMediaAttachModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    pggoMediaAttachModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
};
