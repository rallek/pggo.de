'use strict';

function rKDownLoadCapitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.substring(1);
}

/**
 * Initialise the quick navigation panel in list views.
 */
function rKDownLoadInitQuickNavigation()
{
    var quickNavForm;
    var objectType;

    if (jQuery('.rkdownloadmodule-quicknav').length < 1) {
        return;
    }

    quickNavForm = jQuery('.rkdownloadmodule-quicknav').first();
    objectType = quickNavForm.attr('id').replace('rKDownLoadModule', '').replace('QuickNavForm', '');

    quickNavForm.find('select').change(function (event) {
        quickNavForm.submit();
    });

    var fieldPrefix = 'rkdownloadmodule_' + objectType.toLowerCase() + 'quicknav_';
    // we can hide the submit button if we have no visible quick search field
    if (jQuery('#' + fieldPrefix + 'q').length < 1 || jQuery('#' + fieldPrefix + 'q').parent().parent().hasClass('hidden')) {
        jQuery('#' + fieldPrefix + 'updateview').addClass('hidden');
    }
}

/**
 * Simulates a simple alert using bootstrap.
 */
function rKDownLoadSimpleAlert(beforeElem, title, content, alertId, cssClass)
{
    var alertBox;

    alertBox = ' \
        <div id="' + alertId + '" class="alert alert-' + cssClass + ' fade"> \
          <button type="button" class="close" data-dismiss="alert">&times;</button> \
          <h4>' + title + '</h4> \
          <p>' + content + '</p> \
        </div>';

    // insert alert before the given element
    beforeElem.before(alertBox);

    jQuery('#' + alertId).delay(200).addClass('in').fadeOut(4000, function () {
        jQuery(this).remove();
    });
}

/**
 * Initialises the mass toggle functionality for admin view pages.
 */
function rKDownLoadInitMassToggle()
{
    if (jQuery('.rkdownload-mass-toggle').length > 0) {
        jQuery('.rkdownload-mass-toggle').click(function (event) {
            jQuery('.rkdownload-toggle-checkbox').prop('checked', jQuery(this).prop('checked'));
        });
    }
}

/**
 * Initialises fixed table columns.
 */
function rKDownLoadInitFixedColumns()
{
    var originalTable, fixedColumnsTable;

    jQuery('.table.fixed-columns').remove();
    jQuery('.table').each(function() {
        originalTable = jQuery(this);
        if (originalTable.find('.fixed-column').length > 0) {
            fixedColumnsTable = originalTable.clone().insertBefore(originalTable).addClass('fixed-columns');
            originalTable.find('.dropdown').addClass('hidden');
            fixedColumnsTable.find('.dropdown').removeClass('hidden');
            fixedColumnsTable.css('left', originalTable.parent().offset().left);

            fixedColumnsTable.find('th, td').not('.fixed-column').remove();

            fixedColumnsTable.find('tr').each(function (i, elem) {
                jQuery(this).height(originalTable.find('tr:eq(' + i + ')').height());
            });
        }
    });
}

/**
 * Creates a dropdown menu for the item actions.
 */
function rKDownLoadInitItemActions(context)
{
    var containerSelector;
    var containers;
    var listClasses;

    containerSelector = '';
    if (context == 'view') {
        containerSelector = '.rkdownloadmodule-view';
        listClasses = 'list-unstyled dropdown-menu';
    } else if (context == 'display') {
        containerSelector = 'h2, h3';
        listClasses = 'list-unstyled dropdown-menu';
    }

    if (containerSelector == '') {
        return;
    }

    containers = jQuery(containerSelector);
    if (containers.length < 1) {
        return;
    }

    containers.find('.dropdown > ul').removeClass('list-inline').addClass(listClasses);
    containers.find('.dropdown > ul a').each(function (index) {
        jQuery(this).html(jQuery(this).html() + jQuery(this).find('i').first().data('original-title'));
    });
    containers.find('.dropdown > ul a i').addClass('fa-fw');
    containers.find('.dropdown-toggle').removeClass('hidden').dropdown();
}

jQuery(document).ready(function() {
    var isViewPage;
    var isDisplayPage;

    isViewPage = jQuery('.rkdownloadmodule-view').length > 0;
    isDisplayPage = jQuery('.rkdownloadmodule-display').length > 0;

    if (isViewPage) {
        rKDownLoadInitQuickNavigation();
        rKDownLoadInitMassToggle();
        jQuery(window).resize(rKDownLoadInitFixedColumns);
        rKDownLoadInitFixedColumns();
        window.setTimeout(rKDownLoadInitFixedColumns, 1000);
        rKDownLoadInitItemActions('view');
    } else if (isDisplayPage) {
        rKDownLoadInitItemActions('display');
    }
});
