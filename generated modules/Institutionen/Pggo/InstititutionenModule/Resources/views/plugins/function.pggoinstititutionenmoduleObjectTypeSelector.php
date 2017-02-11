<?php
/**
 * Instititutionen.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

/**
 * The pggoinstititutionenmoduleObjectTypeSelector plugin provides items for a dropdown selector.
 *
 * Available parameters:
 *   - assign: If set, the results are assigned to the corresponding variable instead of printed out.
 *
 * @param  array            $params All attributes passed to this function from the template
 * @param  Zikula_Form_View $view   Reference to the view object
 *
 * @return string The output of the plugin
 */
function smarty_function_pggoinstititutionenmoduleObjectTypeSelector($params, $view)
{
    $dom = ZLanguage::getModuleDomain('PggoInstititutionenModule');
    $result = [];

    $result[] = ['text' => __('Pictures', $dom), 'value' => 'picture'];
    $result[] = ['text' => __('Institutions', $dom), 'value' => 'institution'];

    if (array_key_exists('assign', $params)) {
        $view->assign($params['assign'], $result);

        return;
    }

    return $result;
}
