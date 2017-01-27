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

namespace Pggo\TeamModule\Helper\Base;

use Zikula\Core\RouteUrl;
use Zikula\SearchModule\AbstractSearchable;
use Pggo\TeamModule\Helper\FeatureActivationHelper;

/**
 * Search helper base class.
 */
abstract class AbstractSearchHelper extends AbstractSearchable
{
    /**
     * Display the search form.
     *
     * @param boolean    $active  if the module should be checked as active
     * @param array|null $modVars module form vars as previously set
     *
     * @return string Template output
     */
    public function getOptions($active, $modVars = null)
    {
        $permissionApi = $this->container->get('zikula_permissions_module.api.permission');
    
        if (!$permissionApi->hasPermission($this->name . '::', '::', ACCESS_READ)) {
            return '';
        }
    
        $templateParameters = [];
    
        $searchTypes = array('person');
        foreach ($searchTypes as $searchType) {
            $templateParameters['active_' . $searchType] = (!isset($args['pggoTeamModuleSearchTypes']) || in_array($searchType, $args['pggoTeamModuleSearchTypes']));
        }
    
        return $this->getContainer()->get('twig')->render('@PggoTeamModule/Search/options.html.twig', $templateParameters);
    }
    
    /**
     * Returns the search results.
     *
     * @param array      $words      Array of words to search for
     * @param string     $searchType AND|OR|EXACT (defaults to AND)
     * @param array|null $modVars    Module form vars passed though
     *
     * @return array List of fetched results
     */
    public function getResults(array $words, $searchType = 'AND', $modVars = null)
    {
        $permissionApi = $this->container->get('zikula_permissions_module.api.permission');
        $featureActivationHelper = $this->container->get('pggo_team_module.feature_activation_helper');
        $request = $this->container->get('request_stack')->getCurrentRequest();
    
        if (!$permissionApi->hasPermission($this->name . '::', '::', ACCESS_READ)) {
            return [];
        }
    
        // save session id as it is used when inserting search results below
        $sessionId = $this->container->get('session')->getId();
    
        // initialise array for results
        $records = [];
    
        // retrieve list of activated object types
        $searchTypes = isset($modVars['objectTypes']) ? (array)$modVars['objectTypes'] : [];
        if (!is_array($searchTypes) || !count($searchTypes)) {
            if ($request->isMethod('GET')) {
                $searchTypes = $request->query->get('pggoTeamModuleSearchTypes', []);
            } elseif ($request->isMethod('POST')) {
                $searchTypes = $request->request->get('pggoTeamModuleSearchTypes', []);
            }
        }
    
        $controllerHelper = $this->container->get('pggo_team_module.controller_helper');
        $allowedTypes = $controllerHelper->getObjectTypes('helper', ['helper' => 'search', 'action' => 'getResults']);
    
        foreach ($searchTypes as $objectType) {
            if (!in_array($objectType, $allowedTypes)) {
                continue;
            }
    
            $whereArray = [];
            $languageField = null;
            switch ($objectType) {
                case 'person':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.lastName';
                    $whereArray[] = 'tbl.firstName';
                    $whereArray[] = 'tbl.image';
                    $whereArray[] = 'tbl.copyright';
                    $whereArray[] = 'tbl.shortDescription';
                    $whereArray[] = 'tbl.description';
                    $whereArray[] = 'tbl.functionRole';
                    $whereArray[] = 'tbl.emailAddress';
                    $whereArray[] = 'tbl.contact';
                    break;
            }
    
            $repository = $this->container->get('pggo_team_module.entity_factory')->getRepository($objectType);
    
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
    
            $entitiesWithDisplayAction = ['person'];
    
            foreach ($entities as $entity) {
                $urlArgs = $entity->createUrlArgs();
                $hasDisplayAction = in_array($objectType, $entitiesWithDisplayAction);
    
                $instanceId = $entity->createCompositeIdentifier();
                // perform permission check
                if (!$permissionApi->hasPermission($this->name . ':' . ucfirst($objectType) . ':', $instanceId . '::', ACCESS_OVERVIEW)) {
                    continue;
                }
                if (in_array($objectType, ['person'])) {
                    if ($featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, $objectType)) {
                        if (!$this->container->get('pggo_team_module.category_helper')->hasPermission($entity)) {
                            continue;
                        }
                    }
                }
    
                $description = !empty($descriptionField) ? $entity[$descriptionField] : '';
                $created = isset($entity['createdDate']) ? $entity['createdDate'] : null;
    
                $urlArgs['_locale'] = (null !== $languageField && !empty($entity[$languageField])) ? $entity[$languageField] : $request->getLocale();
    
                $displayUrl = $hasDisplayAction ? new RouteUrl('pggoteammodule_' . $objectType . '_display', $urlArgs) : '';
    
                $records[] = [
                    'title' => $entity->getTitleFromDisplayPattern(),
                    'text' => $description,
                    'module' => $this->name,
                    'sesid' => $sessionId,
                    'created' => $created,
                    'url' => $displayUrl
                ];
            }
        }
    
        return $records;
    }
}
