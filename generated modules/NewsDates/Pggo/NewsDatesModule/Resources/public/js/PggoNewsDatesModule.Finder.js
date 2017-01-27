'use strict';

var currentPggoNewsDatesModuleEditor = null;
var currentPggoNewsDatesModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getPggoNewsDatesModulePopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function PggoNewsDatesModuleFinderCKEditor(editor, newsdateUrl)
{
    // Save editor for access in selector window
    currentPggoNewsDatesModuleEditor = editor;

    editor.popup(
        Routing.generate('pggonewsdatesmodule_external_finder', { objectType: 'article', editor: 'ckeditor' }),
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}


var pggoNewsDatesModule = {};

pggoNewsDatesModule.finder = {};

pggoNewsDatesModule.finder.onLoad = function (baseId, selectedId)
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

    jQuery('input[type="checkbox"]').click(pggoNewsDatesModule.finder.onParamChanged);
    jQuery('select').not("[id$='pasteAs']").change(pggoNewsDatesModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(pggoNewsDatesModule.finder.handleCancel);

    var selectedItems = jQuery('#pggonewsdatesmoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        pggoNewsDatesModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

pggoNewsDatesModule.finder.onParamChanged = function ()
{
    jQuery('#pggoNewsDatesModuleSelectorForm').submit();
};

pggoNewsDatesModule.finder.handleCancel = function ()
{
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        pggoNewsDatesClosePopup();
    } else if ('ckeditor' === editor) {
        pggoNewsDatesClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function pggoNewsDatesGetPasteSnippet(mode, itemId)
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
pggoNewsDatesModule.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        html = pggoNewsDatesGetPasteSnippet('html', itemId);
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if ('ckeditor' === editor) {
        if (null !== window.opener.currentPggoNewsDatesModuleEditor) {
            html = pggoNewsDatesGetPasteSnippet('html', itemId);

            window.opener.currentPggoNewsDatesModuleEditor.insertHtml(html);
        }
    } else {
        alert('Insert into Editor: ' + editor);
    }
    pggoNewsDatesClosePopup();
};

function pggoNewsDatesClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// PggoNewsDatesModule item selector for Forms
//=============================================================================

pggoNewsDatesModule.itemSelector = {};
pggoNewsDatesModule.itemSelector.items = {};
pggoNewsDatesModule.itemSelector.baseId = 0;
pggoNewsDatesModule.itemSelector.selectedId = 0;

pggoNewsDatesModule.itemSelector.onLoad = function (baseId, selectedId)
{
    pggoNewsDatesModule.itemSelector.baseId = baseId;
    pggoNewsDatesModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#pggoNewsDatesModuleObjectType').change(pggoNewsDatesModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(pggoNewsDatesModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(pggoNewsDatesModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(pggoNewsDatesModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(pggoNewsDatesModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(pggoNewsDatesModule.itemSelector.onParamChanged);
    jQuery('#pggoNewsDatesModuleSearchGo').click(pggoNewsDatesModule.itemSelector.onParamChanged);
    jQuery('#pggoNewsDatesModuleSearchGo').keypress(pggoNewsDatesModule.itemSelector.onParamChanged);

    pggoNewsDatesModule.itemSelector.getItemList();
};

pggoNewsDatesModule.itemSelector.onParamChanged = function ()
{
    jQuery('#ajax_indicator').removeClass('hidden');

    pggoNewsDatesModule.itemSelector.getItemList();
};

pggoNewsDatesModule.itemSelector.getItemList = function ()
{
    var baseId;
    var params;

    baseId = newsdates.itemSelector.baseId;
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
        url: Routing.generate('pggonewsdatesmodule_ajax_getitemlistfinder'),
        data: params
    }).done(function(res) {
        // get data returned by the ajax response
        var baseId;
        baseId = pggoNewsDatesModule.itemSelector.baseId;
        pggoNewsDatesModule.itemSelector.items[baseId] = res.data;
        jQuery('#ajax_indicator').addClass('hidden');
        pggoNewsDatesModule.itemSelector.updateItemDropdownEntries();
        pggoNewsDatesModule.itemSelector.updatePreview();
    });
};

pggoNewsDatesModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = pggoNewsDatesModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = pggoNewsDatesModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.options[i] = new Option(item.title, item.id, false);
    }

    if (pggoNewsDatesModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(pggoNewsDatesModule.itemSelector.selectedId);
    }
};

pggoNewsDatesModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = pggoNewsDatesModule.itemSelector.baseId;
    items = pggoNewsDatesModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (pggoNewsDatesModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id === pggoNewsDatesModule.itemSelector.selectedId) {
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

pggoNewsDatesModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = pggoNewsDatesModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    preview = window.atob(pggoNewsDatesModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    pggoNewsDatesModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
};
