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

namespace Pggo\InstititutionenModule\Form\Plugin\Base;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Zikula_Form_Plugin_TextInput;
use Zikula_Form_View;
use Zikula_View;

/**
 * Item selector plugin base class.
 */
class AbstractItemSelector extends Zikula_Form_Plugin_TextInput implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * The treated object type.
     *
     * @var string
     */
    public $objectType = '';

    /**
     * Identifier of selected object.
     *
     * @var integer
     */
    public $selectedItemId = 0;

    /**
     * ItemSelector constructor.
     */
    public function __construct()
    {
        $this->setContainer(\ServiceUtil::getManager());
    }

    /**
     * Get filename of this file.
     * The information is used to re-establish the plugins on postback.
     *
     * @return string
     */
    public function getFilename()
    {
        return __FILE__;
    }

    /**
     * Create event handler.
     *
     * @param Zikula_Form_View $view    Reference to Zikula_Form_View object
     * @param array            &$params Parameters passed from the Smarty plugin function
     *
     * @see    Zikula_Form_AbstractPlugin
     *
     * @return void
     */
    public function create(Zikula_Form_View $view, &$params)
    {
        $params['maxLength'] = 11;
        /*$params['width'] = '8em';*/

        // let parent plugin do the work in detail
        parent::create($view, $params);
    }

    /**
     * Helper method to determine css class.
     *
     * @see    Zikula_Form_Plugin_TextInput
     *
     * @return string the list of css classes to apply
     */
    protected function getStyleClass()
    {
        $class = parent::getStyleClass();

        return str_replace('z-form-text', 'z-form-itemlist ' . strtolower($this->objectType), $class);
    }

    /**
     * Render event handler.
     *
     * @param Zikula_Form_View $view Reference to Zikula_Form_View object
     *
     * @return string The rendered output
     */
    public function render(Zikula_Form_View $view)
    {
        static $firstTime = true;
        if ($firstTime) {
            $assetHelper = $this->container->get('zikula_core.common.theme.asset_helper');
            $cssAssetBag = $this->container->get('zikula_core.common.theme.assets_css');
            $jsAssetBag = $this->container->get('zikula_core.common.theme.assets_js');
            $homePath = $this->container->get('request_stack')->getCurrentRequest()->getBasePath();

            $jsAssetBag->add($homePath . '/web/magnific-popup/jquery.magnific-popup.min.js');
            $cssAssetBag->add($homePath . '/web/magnific-popup/magnific-popup.css');
            $jsAssetBag->add($assetHelper->resolve('@PggoInstititutionenModule:js/PggoInstititutionenModule.js'));
            $jsAssetBag->add($assetHelper->resolve('@PggoInstititutionenModule:js/PggoInstititutionenModule.Finder.js'));
            $cssAssetBag->add($assetHelper->resolve('@PggoInstititutionenModule:css/style.css'));
        }
        $firstTime = false;

        $permissionApi = $this->container->get('zikula_permissions_module.api.permission');

        if (!$permissionApi->hasPermission('PggoInstititutionenModule:' . ucfirst($this->objectType) . ':', '::', ACCESS_COMMENT)) {
            return false;
        }

        $categorisableObjectTypes = ['institution'];
        $catIds = [];
        if (in_array($this->objectType, $categorisableObjectTypes)) {
            // fetch selected categories to reselect them in the output
            // the actual filtering is done inside the repository class
            $categoryHelper = $this->container->get('pggo_instititutionen_module.category_helper');
            $catIds = $categoryHelper->retrieveCategoriesFromRequest($this->objectType);
        }

        $this->selectedItemId = $this->text;

        $repository = $this->container->get('pggo_instititutionen_module.entity_factory')->getRepository($this->objectType);

        $sort = $repository->getDefaultSortingField();
        $sdir = 'asc';

        // convenience vars to make code clearer
        $where = '';
        $sortParam = $sort . ' ' . $sdir;

        $entities = $repository->selectWhere($where, $sortParam);

        $view = Zikula_View::getInstance('PggoInstititutionenModule', false);
        $view->assign('objectType', $this->objectType)
             ->assign('items', $entities)
             ->assign('sort', $sort)
             ->assign('sortdir', $sdir)
             ->assign('selectedId', $this->selectedItemId);

        // assign category properties
        $properties = null;
        if (in_array($this->objectType, $categorisableObjectTypes)) {
            $properties = $categoryHelper->getAllProperties($this->objectType);
        }
        $view->assign('properties', $properties)
             ->assign('catIds', $catIds)
             ->assign('categoryHelper', $categoryHelper);

        return $view->fetch('External/' . ucfirst($this->objectType) . '/select.tpl');
    }

    /**
     * Decode event handler.
     *
     * @param Zikula_Form_View $view Zikula_Form_View object
     *
     * @return void
     */
    public function decode(Zikula_Form_View $view)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $this->objectType = $request->request->get('PggoInstititutionenModule_objecttype', 'institution');
        $this->selectedItemId = $this->text = $request->request->get($this->inputName, 0);
    }
}