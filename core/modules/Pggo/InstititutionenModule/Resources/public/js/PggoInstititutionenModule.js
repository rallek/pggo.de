'use strict';

function pggoInstititutionenCapitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.substring(1);
}

/**
 * Initialise the quick navigation panel in list views.
 */
function pggoInstititutionenInitQuickNavigation()
{
    var quickNavForm;
    var objectType;

    if (jQuery('.pggoinstititutionenmodule-quicknav').length < 1) {
        return;
    }

    quickNavForm = jQuery('.pggoinstititutionenmodule-quicknav').first();
    objectType = quickNavForm.attr('id').replace('pggoInstititutionenModule', '').replace('QuickNavForm', '');

    quickNavForm.find('select').change(function (event) {
        quickNavForm.submit();
    });

    var fieldPrefix = 'pggoinstititutionenmodule_' + objectType.toLowerCase() + 'quicknav_';
    // we can hide the submit button if we have no visible quick search field
    if (jQuery('#' + fieldPrefix + 'q').length < 1 || jQuery('#' + fieldPrefix + 'q').parent().parent().hasClass('hidden')) {
        jQuery('#' + fieldPrefix + 'updateview').addClass('hidden');
    }
}

/**
 * Simulates a simple alert using bootstrap.
 */
function pggoInstititutionenSimpleAlert(beforeElem, title, content, alertId, cssClass)
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
function pggoInstititutionenInitMassToggle()
{
    if (jQuery('.pggoinstititutionen-mass-toggle').length > 0) {
        jQuery('.pggoinstititutionen-mass-toggle').click(function (event) {
            jQuery('.pggoinstititutionen-toggle-checkbox').prop('checked', jQuery(this).prop('checked'));
        });
    }
}

/**
 * Initialises fixed table columns.
 */
function pggoInstititutionenInitFixedColumns()
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
function pggoInstititutionenInitItemActions(context)
{
    var containerSelector;
    var containers;
    var listClasses;

    containerSelector = '';
    if (context == 'view') {
        containerSelector = '.pggoinstititutionenmodule-view';
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

/**
 * Helper function to create new Bootstrap modal window instances.
 */
function pggoInstititutionenInitInlineWindow(containerElem)
{
    var newWindowId;
    var modalTitle;

    // show the container (hidden for users without JavaScript)
    containerElem.removeClass('hidden');

    // define name of window
    newWindowId = containerElem.attr('id') + 'Dialog';

    containerElem.unbind('click').click(function(e) {
        e.preventDefault();

        // check if window exists already
        if (jQuery('#' + newWindowId).length < 1) {
            // create new window instance
            jQuery('<div id="' + newWindowId + '"></div>')
                .append(
                    jQuery('<iframe width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto" />')
                        .attr('src', containerElem.attr('href'))
                )
                .dialog({
                    autoOpen: false,
                    show: {
                        effect: 'blind',
                        duration: 1000
                    },
                    hide: {
                        effect: 'explode',
                        duration: 1000
                    },
                    title: containerElem.data('modal-title'),
                    width: 600,
                    height: 400,
                    modal: false
                });
        }

        // open the window
        jQuery('#' + newWindowId).dialog('open');
    });

    // return the dialog selector id;
    return newWindowId;
}

/**
 * Initialises modals for inline display of related items.
 */
function pggoInstititutionenInitQuickViewModals()
{
    jQuery('.pggoinstititutionen-inline-window').each(function (index) {
        pggoInstititutionenInitInlineWindow(jQuery(this));
    });
}

/**
 * Initialises image viewing behaviour.
 */
function pggoInstititutionenInitImageViewer()
{
    jQuery('a.image-link').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        image: {
            titleSrc: 'title',
            verticalFit: true
        },
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            tPrev: Translator.__('Previous (Left arrow key)'),
            tNext: Translator.__('Next (Right arrow key)'),
            tCounter: '<span class="mfp-counter">%curr% ' + Translator.__('of') + ' %total%</span>'
        },
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out'
        }
    });
}

jQuery(document).ready(function() {
    var isViewPage;
    var isDisplayPage;

    isViewPage = jQuery('.pggoinstititutionenmodule-view').length > 0;
    isDisplayPage = jQuery('.pggoinstititutionenmodule-display').length > 0;

    pggoInstititutionenInitImageViewer();

    if (isViewPage) {
        pggoInstititutionenInitQuickNavigation();
        pggoInstititutionenInitMassToggle();
        jQuery(window).resize(pggoInstititutionenInitFixedColumns);
        pggoInstititutionenInitFixedColumns();
        window.setTimeout(pggoInstititutionenInitFixedColumns, 1000);
        pggoInstititutionenInitItemActions('view');
    } else if (isDisplayPage) {
        pggoInstititutionenInitItemActions('display');
    }

    pggoInstititutionenInitQuickViewModals();
});
