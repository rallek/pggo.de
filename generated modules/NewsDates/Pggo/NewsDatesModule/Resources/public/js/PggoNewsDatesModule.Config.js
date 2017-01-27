'use strict';

function newsdateToggleShrinkSettings(fieldName) {
    var idSuffix = fieldName.replace('pggonewsdatesmodule_appsettings_', '');
    jQuery('#shrinkDetails' + idSuffix).toggleClass('hidden', !jQuery('#pggonewsdatesmodule_appsettings_enableShrinkingFor' + idSuffix).prop('checked'));
}

jQuery(document).ready(function() {
    jQuery('.shrink-enabler').each(function (index) {
        jQuery(this).bind('click keyup', function (event) {
            newsdateToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
        });
        newsdateToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
    });
});
