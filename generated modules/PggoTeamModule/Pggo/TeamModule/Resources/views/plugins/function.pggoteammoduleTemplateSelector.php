<?php
/**
 * Team.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://pggo.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

/**
 * The pggoteammoduleTemplateSelector plugin provides items for a dropdown selector.
 *
 * Available parameters:
 *   - assign: If set, the results are assigned to the corresponding variable instead of printed out.
 *
 * @param  array            $params All attributes passed to this function from the template
 * @param  Zikula_Form_View $view   Reference to the view object
 *
 * @return string The output of the plugin
 */
function smarty_function_pggoteammoduleTemplateSelector($params, $view)
{
    $dom = ZLanguage::getModuleDomain('PggoTeamModule');
    $result = [];

    $result[] = ['text' => __('Only item titles', $dom), 'value' => 'itemlist_display.html.twig'];
    $result[] = ['text' => __('With description', $dom), 'value' => 'itemlist_display_description.html.twig'];
    $result[] = ['text' => __('Custom template', $dom), 'value' => 'custom'];

    if (array_key_exists('assign', $params)) {
        $view->assign($params['assign'], $result);

        return;
    }

    return $result;
}
