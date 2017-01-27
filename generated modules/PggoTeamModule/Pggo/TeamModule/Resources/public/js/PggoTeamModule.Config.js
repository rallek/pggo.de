'use strict';

function teamToggleShrinkSettings(fieldName) {
    var idSuffix = fieldName.replace('pggoteammodule_appsettings_', '');
    jQuery('#shrinkDetails' + idSuffix).toggleClass('hidden', !jQuery('#pggoteammodule_appsettings_enableShrinkingFor' + idSuffix).prop('checked'));
}

jQuery(document).ready(function() {
    jQuery('.shrink-enabler').each(function (index) {
        jQuery(this).bind('click keyup', function (event) {
            teamToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
        });
        teamToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
    });
});
