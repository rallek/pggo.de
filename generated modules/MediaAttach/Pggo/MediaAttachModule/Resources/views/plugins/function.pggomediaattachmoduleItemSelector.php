<?php
/**
 * MediaAttach.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://pggo.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

/**
 * The pggomediaattachmoduleItemSelector plugin provides items for a dropdown selector.
 *
 * @param  array            $params All attributes passed to this function from the template
 * @param  Zikula_Form_View $view   Reference to the view object
 *
 * @return string The output of the plugin
 */
function smarty_function_pggomediaattachmoduleItemSelector($params, $view)
{
    return $view->registerPlugin('\\Pggo\\MediaAttachModule\\Form\\Plugin\\ItemSelector', $params);
}
