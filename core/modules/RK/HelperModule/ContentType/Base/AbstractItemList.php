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
use RK\HelperModule\Helper\FeatureActivationHelper;

/**
 * Generic item list content plugin base class.
 */
abstract class AbstractItemList extends \Content_AbstractContentType implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * The treated object type.
     *
     * @var string
     */
    protected $objectType;
    
    /**
     * The sorting criteria.
     *
     * @var string
     */
    protected $sorting;
    
    /**
     * The amount of desired items.
     *
     * @var integer
     */
    protected $amount;
    
    /**
     * Name of template file.
     *
     * @var string
     */
    protected $template;
    
    /**
     * Name of custom template file.
     *
     * @var string
     */
    protected $customTemplate;
    
    /**
     * Optional filters.
     *
     * @var string
     */
    protected $filter;
    
    /**
     * ItemList constructor.
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
        return 'ItemList';
    }
    
    /**
     * Returns the title of this content type.
     *
     * @return string The content type title
     */
    public function getTitle()
    {
        return $this->container->get('translator.default')->__('RKHelperModule list view');
    }
    
    /**
     * Returns the description of this content type.
     *
     * @return string The content type description
     */
    public function getDescription()
    {
        return $this->container->get('translator.default')->__('Display list of RKHelperModule objects.');
    }
    
    /**
     * Loads the data.
     *
     * @param array $data Data array with parameters
     */
    public function loadData(&$data)
    {
        $controllerHelper = $this->container->get('rk_helper_module.controller_helper');
    
        $contextArgs = ['name' => 'list'];
        if (!isset($data['objectType']) || !in_array($data['objectType'], $controllerHelper->getObjectTypes('contentType', $contextArgs))) {
            $data['objectType'] = $controllerHelper->getDefaultObjectType('contentType', $contextArgs);
        }
    
        $this->objectType = $data['objectType'];
    
        if (!isset($data['sorting'])) {
            $data['sorting'] = 'default';
        }
        if (!isset($data['amount'])) {
            $data['amount'] = 1;
        }
        if (!isset($data['template'])) {
            $data['template'] = 'itemlist_' . $this->objectType . '_display.html.twig';
        }
        if (!isset($data['customTemplate'])) {
            $data['customTemplate'] = '';
        }
        if (!isset($data['filter'])) {
            $data['filter'] = '';
        }
    
        $this->sorting = $data['sorting'];
        $this->amount = $data['amount'];
        $this->template = $data['template'];
        $this->customTemplate = $data['customTemplate'];
        $this->filter = $data['filter'];
    }
    
    /**
     * Displays the data.
     *
     * @return string The returned output
     */
    public function display()
    {
        $repository = $this->container->get('rk_helper_module.entity_factory')->getRepository($this->objectType);
        $permissionApi = $this->container->get('zikula_permissions_module.api.permission');
    
        // create query
        $where = $this->filter;
        $orderBy = $this->getSortParam($repository);
        $qb = $repository->genericBaseQuery($where, $orderBy);
    
        // get objects from database
        $currentPage = 1;
        $resultsPerPage = isset($this->amount) ? $this->amount : 1;
        $query = $repository->getSelectWherePaginatedQuery($qb, $currentPage, $resultsPerPage);
        list($entities, $objectCount) = $repository->retrieveCollectionResult($query, $orderBy, true);
    
        $data = [
            'objectType' => $this->objectType,
            'sorting' => $this->sorting,
            'amount' => $this->amount,
            'template' => $this->template,
            'customTemplate' => $this->customTemplate,
            'filter' => $this->filter
        ];
    
        $templateParameters = [
            'vars' => $data,
            'objectType' => $this->objectType,
            'items' => $entities
        ];
    
        $imageHelper = $this->container->get('rk_helper_module.image_helper');
        $templateParameters = array_merge($templateParameters, $repository->getAdditionalTemplateParameters($imageHelper, 'contentType'));
    
        $template = $this->getDisplayTemplate();
    
        return $this->container->get('twig')->render($template, $templateParameters);
    }
    
    /**
     * Returns the template used for output.
     *
     * @return string the template path
     */
    protected function getDisplayTemplate()
    {
        $templateFile = $this->template;
        if ($templateFile == 'custom') {
            $templateFile = $this->customTemplate;
        }
    
        $templateForObjectType = str_replace('itemlist_', 'itemlist_' . $this->objectType . '_', $templateFile);
        $templating = $this->container->get('templating');
    
        $templateOptions = [
            'ContentType/' . $templateForObjectType,
            'ContentType/' . $templateFile,
            'ContentType/itemlist_display.html.twig'
        ];
    
        $template = '';
        foreach ($templateOptions as $templatePath) {
            if ($templating->exists('@RKHelperModule/' . $templatePath)) {
                $template = '@RKHelperModule/' . $templatePath;
                break;
            }
        }
    
        return $template;
    }
    
    /**
     * Determines the order by parameter for item selection.
     *
     * @param Doctrine_Repository $repository The repository used for data fetching
     *
     * @return string the sorting clause
     */
    protected function getSortParam($repository)
    {
        if ($this->sorting == 'random') {
            return 'RAND()';
        }
    
        $sortParam = '';
        if ($this->sorting == 'newest') {
            $selectionHelper = $this->container->get('rk_helper_module.selection_helper');
            $idFields = $selectionHelper->getIdFields($this->objectType);
            if (count($idFields) == 1) {
                $sortParam = $idFields[0] . ' DESC';
            } else {
                foreach ($idFields as $idField) {
                    if (!empty($sortParam)) {
                        $sortParam .= ', ';
                    }
                    $sortParam .= $idField . ' DESC';
                }
            }
        } elseif ($this->sorting == 'default') {
            $sortParam = $repository->getDefaultSortingField() . ' ASC';
        }
    
        return $sortParam;
    }
    
    /**
     * Displays the data for editing.
     */
    public function displayEditing()
    {
        return $this->display();
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
            'sorting' => 'default',
            'amount' => 1,
            'template' => 'itemlist_display.html.twig',
            'customTemplate' => '',
            'filter' => ''
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
    }
    
    /**
     * Returns the edit template path.
     *
     * @return string
     */
    public function getEditTemplate()
    {
        $absoluteTemplatePath = str_replace('ContentType/Base/AbstractItemList.php', 'Resources/views/ContentType/itemlist_edit.tpl', __FILE__);
    
        return 'file:' . $absoluteTemplatePath;
    }
}
