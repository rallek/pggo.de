'use strict';

function institToggleShrinkSettings(fieldName) {
    var idSuffix = fieldName.replace('pggoinstititutionenmodule_appsettings_', '');
    jQuery('#shrinkDetails' + idSuffix).toggleClass('hidden', !jQuery('#pggoinstititutionenmodule_appsettings_enableShrinkingFor' + idSuffix).prop('checked'));
}

jQuery(document).ready(function() {
    jQuery('.shrink-enabler').each(function (index) {
        jQuery(this).bind('click keyup', function (event) {
            institToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
        });
        institToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
    });
});
