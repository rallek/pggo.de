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

namespace Pggo\InstititutionenModule\Helper\Base;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Composite;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zikula\Core\RouteUrl;
use Zikula\PermissionsModule\Api\PermissionApi;
use Zikula\SearchModule\Entity\SearchResultEntity;
use Zikula\SearchModule\SearchableInterface;
use Pggo\InstititutionenModule\Entity\Factory\InstititutionenFactory;
use Pggo\InstititutionenModule\Helper\CategoryHelper;
use Pggo\InstititutionenModule\Helper\ControllerHelper;
use Pggo\InstititutionenModule\Helper\FeatureActivationHelper;

/**
 * Search helper base class.
 */
abstract class AbstractSearchHelper implements SearchableInterface
{
    /**
     * @var PermissionApi
     */
    protected $permissionApi;
    
    /**
     * @var EngineInterface
     */
    private $templateEngine;
    
    /**
     * @var SessionInterface
     */
    private $session;
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var InstititutionenFactory
     */
    private $entityFactory;
    
    /**
     * @var ControllerHelper
     */
    private $controllerHelper;
    
    /**
     * @var FeatureActivationHelper
     */
    private $featureActivationHelper;
    
    /**
     * @var CategoryHelper
     */
    private $categoryHelper;
    
    /**
     * SearchHelper constructor.
     *
     * @param PermissionApi    $permissionApi   PermissionApi service instance
     * @param EngineInterface  $templateEngine  Template engine service instance
     * @param SessionInterface $session         Session service instance
     * @param RequestStack     $requestStack    RequestStack service instance
     * @param InstititutionenFactory $entityFactory EntityFactory service instance
     * @param ControllerHelper $controllerHelper ControllerHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     * @param CategoryHelper   $categoryHelper CategoryHelper service instance
     */
    public function __construct(
        PermissionApi $permissionApi,
        EngineInterface $templateEngine,
        SessionInterface $session,
        RequestStack $requestStack,
        InstititutionenFactory $entityFactory,
        ControllerHelper $controllerHelper,
        FeatureActivationHelper $featureActivationHelper,
        CategoryHelper $categoryHelper
    ) {
        $this->permissionApi = $permissionApi;
        $this->templateEngine = $templateEngine;
        $this->session = $session;
        $this->request = $requestStack->getCurrentRequest();
        $this->entityFactory = $entityFactory;
        $this->controllerHelper = $controllerHelper;
        $this->featureActivationHelper = $featureActivationHelper;
        $this->categoryHelper = $categoryHelper;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOptions($active, $modVars = null)
    {
        if (!$this->permissionApi->hasPermission('PggoInstititutionenModule::', '::', ACCESS_READ)) {
            return '';
        }
    
        $templateParameters = [];
    
        $searchTypes = ['picture', 'institution'];
        foreach ($searchTypes as $searchType) {
            $templateParameters['active_' . $searchType] = !isset($args['pggoInstititutionenModuleSearchTypes']) || in_array($searchType, $args['pggoInstititutionenModuleSearchTypes']);
        }
    
        return $this->templateEngine->renderResponse('@PggoInstititutionenModule/Search/options.html.twig', $templateParameters)->getContent();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getResults(array $words, $searchType = 'AND', $modVars = null)
    {
        if (!$this->permissionApi->hasPermission('PggoInstititutionenModule::', '::', ACCESS_READ)) {
            return [];
        }
    
        // initialise array for results
        $results = [];
    
        // retrieve list of activated object types
        $searchTypes = isset($modVars['objectTypes']) ? (array)$modVars['objectTypes'] : [];
        if (!is_array($searchTypes) || !count($searchTypes)) {
            if ($this->request->isMethod('GET')) {
                $searchTypes = $this->request->query->get('pggoInstititutionenModuleSearchTypes', []);
            } elseif ($this->request->isMethod('POST')) {
                $searchTypes = $this->request->request->get('pggoInstititutionenModuleSearchTypes', []);
            }
        }
    
        $allowedTypes = $this->controllerHelper->getObjectTypes('helper', ['helper' => 'search', 'action' => 'getResults']);
    
        foreach ($searchTypes as $objectType) {
            if (!in_array($objectType, $allowedTypes)) {
                continue;
            }
    
            $whereArray = [];
            $languageField = null;
            switch ($objectType) {
                case 'picture':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.title';
                    $whereArray[] = 'tbl.image';
                    $whereArray[] = 'tbl.copyright';
                    $whereArray[] = 'tbl.description';
                    break;
                case 'institution':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.name';
                    $whereArray[] = 'tbl.image';
                    $whereArray[] = 'tbl.copyright';
                    $whereArray[] = 'tbl.description';
                    break;
            }
    
            $repository = $this->entityFactory->getRepository($objectType);
    
            // build the search query without any joins
            $qb = $repository->genericBaseQuery('', '', false);
    
            // build where expression for given search type
            $whereExpr = $this->formatWhere($qb, $words, $whereArray, $searchType);
            $qb->andWhere($whereExpr);
    
            $query = $qb->getQuery();
    
            // set a sensitive limit
            $query->setFirstResult(0)
                  ->setMaxResults(250);
    
            // fetch the results
            $entities = $query->getResult();
    
            if (count($entities) == 0) {
                continue;
            }
    
            $descriptionField = $repository->getDescriptionFieldName();
    
            $entitiesWithDisplayAction = ['picture', 'institution'];
    
            foreach ($entities as $entity) {
                $urlArgs = $entity->createUrlArgs();
                $hasDisplayAction = in_array($objectType, $entitiesWithDisplayAction);
    
                $instanceId = $entity->createCompositeIdentifier();
                // perform permission check
                if (!$this->permissionApi->hasPermission('PggoInstititutionenModule:' . ucfirst($objectType) . ':', $instanceId . '::', ACCESS_OVERVIEW)) {
                    continue;
                }
    
                if (in_array($objectType, ['institution'])) {
                    if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, $objectType)) {
                        if (!$this->categoryHelper->hasPermission($entity)) {
                            continue;
                        }
                    }
                }
    
                $description = !empty($descriptionField) ? $entity[$descriptionField] : '';
                $created = isset($entity['createdBy']) ? $entity['createdBy'] : null;
    
                $urlArgs['_locale'] = (null !== $languageField && !empty($entity[$languageField])) ? $entity[$languageField] : $this->request->getLocale();
    
                $displayUrl = $hasDisplayAction ? new RouteUrl('pggoinstititutionenmodule_' . $objectType . '_display', $urlArgs) : '';
    
                $result = new SearchResultEntity();
                $result->setTitle($entity->getTitleFromDisplayPattern())
                    ->setText($description)
                    ->setModule('PggoInstititutionenModule')
                    ->setCreated($created)
                    ->setSesid($this->session->getId())
                    ->setUrl($displayUrl);
                $results[] = $result;
            }
        }
    
        return $results;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return [];
    }
    
    /**
     * Construct a QueryBuilder Where orX|andX Expr instance.
     *
     * @param QueryBuilder $qb
     * @param array $words the words to query for
     * @param array $fields
     * @param string $searchtype AND|OR|EXACT
     *
     * @return null|Composite
     */
    protected function formatWhere(QueryBuilder $qb, array $words, array $fields, $searchtype = 'AND')
    {
        if (empty($words) || empty($fields)) {
            return null;
        }
    
        $method = ($searchtype == 'OR') ? 'orX' : 'andX';
        /** @var $where Composite */
        $where = $qb->expr()->$method();
        $i = 1;
        foreach ($words as $word) {
            $subWhere = $qb->expr()->orX();
            foreach ($fields as $field) {
                $expr = $qb->expr()->like($field, "?$i");
                $subWhere->add($expr);
                $qb->setParameter($i, '%' . $word . '%');
                $i++;
            }
            $where->add($subWhere);
        }
    
        return $where;
    }
}
