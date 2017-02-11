<?php
/**
 * Helper.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\HelperModule\ContentType\Base;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpKernel\Controller\ControllerReference;

/**
 * Generic single item display content plugin base class.
 */
abstract class AbstractItem extends \Content_AbstractContentType implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $objectType;
    
    /**
     * @var integer
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $displayMode;
    
    /**
     * Item constructor.
     */
    public function __construct()
    {
        $this->setContainer(\ServiceUtil::getManager());
    }
    
    /**
     * Returns the module providing this content type.
     *
     * @return string The module name
     */
    public function getModule()
    {
        return 'RKHelperModule';
    }
    
    /**
     * Returns the name of this content type.
     *
     * @return string The content type name
     */
    public function getName()
    {
        return 'Item';
    }
    
    /**
     * Returns the title of this content type.
     *
     * @return string The content type title
     */
    public function getTitle()
    {
        return $this->container->get('translator.default')->__('RKHelperModule detail view');
    }
    
    /**
     * Returns the description of this content type.
     *
     * @return string The content type description
     */
    public function getDescription()
    {
        return $this->container->get('translator.default')->__('Display or link a single RKHelperModule object.');
    }
    
    /**
     * Loads the data.
     *
     * @param array $data Data array with parameters
     */
    public function loadData(&$data)
    {
        $controllerHelper = $this->container->get('rk_helper_module.controller_helper');
    
        $contextArgs = ['name' => 'detail'];
        if (!isset($data['objectType']) || !in_array($data['objectType'], $controllerHelper->getObjectTypes('contentType', $contextArgs))) {
            $data['objectType'] = $controllerHelper->getDefaultObjectType('contentType', $contextArgs);
        }
    
        $this->objectType = $data['objectType'];
    
        if (!isset($data['id'])) {
            $data['id'] = null;
        }
        if (!isset($data['displayMode'])) {
            $data['displayMode'] = 'embed';
        }
    
        $this->id = $data['id'];
        $this->displayMode = $data['displayMode'];
    }
    
    /**
     * Displays the data.
     *
     * @return string The returned output
     */
    public function display()
    {
        if (null === $this->id || empty($this->id) || empty($this->displayMode)) {
            return '';
        }
    
        $controllerReference = new ControllerReference('RKHelperModule:External:display', $this->getDisplayArguments());
    
        return $this->container->get('fragment.handler')->render($controllerReference, 'inline', []);
    }
    
    /**
     * Displays the data for editing.
     */
    public function displayEditing()
    {
        if (null === $this->id || empty($this->id) || empty($this->displayMode)) {
            return $this->container->get('translator.default')->__('No item selected.');
        }
    
        return $this->display();
    }
    
    /**
     * Returns common arguments for display data selection with the external api.
     *
     * @return array Display arguments
     */
    protected function getDisplayArguments()
    {
        return [
            'objectType' => $this->objectType,
            'id' => $this->id,
            'source' => 'contentType',
            'displayMode' => $this->displayMode
        ];
    }
    
    /**
     * Returns the default data.
     *
     * @return array Default data and parameters
     */
    public function getDefaultData()
    {
        return [
            'objectType' => 'linker',
            'id' => null,
            'displayMode' => 'embed'
        ];
    }
    
    /**
     * Executes additional actions for the editing mode.
     */
    public function startEditing()
    {
        // ensure that the view does not look for templates in the Content module (#218)
        $this->view->toplevelmodule = 'RKHelperModule';
    
        // ensure our custom plugins are loaded
        array_push($this->view->plugins_dir, 'modules/RK/HelperModule/Resources/views/plugins');
    
        // required as parameter for the item selector plugin
        $this->view->assign('objectType', $this->objectType);
    }
    
    /**
     * Returns the edit template path.
     *
     * @return string
     */
    public function getEditTemplate()
    {
        $absoluteTemplatePath = str_replace('ContentType/Base/AbstractItem.php', 'Resources/views/ContentType/item_edit.tpl', __FILE__);
    
        return 'file:' . $absoluteTemplatePath;
    }
}