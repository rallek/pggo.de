<?php
/**
 * NewsDates.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <webmaster@pggo.de>.
 * @link http://pggo.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace Pggo\NewsDatesModule\Controller\Base;

use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Zikula\Component\SortableColumns\Column;
use Zikula\Component\SortableColumns\SortableColumns;
use Zikula\Core\Controller\AbstractController;
use Zikula\Core\RouteUrl;
use Pggo\NewsDatesModule\Entity\ArticleEntity;
use Pggo\NewsDatesModule\Helper\FeatureActivationHelper;

/**
 * Article controller base class.
 */
abstract class AbstractArticleController extends AbstractController
{
    /**
     * This is the default action handling the index admin area called without defining arguments.
     * @Cache(expires="+7 days", public=true)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminIndexAction(Request $request)
    {
        return $this->indexInternal($request, true);
    }
    
    /**
     * This is the default action handling the index area called without defining arguments.
     * @Cache(expires="+7 days", public=true)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function indexAction(Request $request)
    {
        return $this->indexInternal($request, false);
    }
    
    /**
     * This method includes the common implementation code for adminIndex() and index().
     */
    protected function indexInternal(Request $request, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'article';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_OVERVIEW;
        if (!$this->hasPermission($this->name . ':' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        return $this->redirectToRoute('pggonewsdatesmodule_article_' . $templateParameters['routeArea'] . 'view');
        
        // return index template
        return $this->render('@PggoNewsDatesModule/Article/index.html.twig', $templateParameters);
    }
    /**
     * This action provides an item list overview in the admin area.
     * @Cache(expires="+2 hours", public=false)
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminViewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return $this->viewInternal($request, $sort, $sortdir, $pos, $num, true);
    }
    
    /**
     * This action provides an item list overview.
     * @Cache(expires="+2 hours", public=false)
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function viewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return $this->viewInternal($request, $sort, $sortdir, $pos, $num, false);
    }
    
    /**
     * This method includes the common implementation code for adminView() and view().
     */
    protected function viewInternal(Request $request, $sort, $sortdir, $pos, $num, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'article';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_READ;
        if (!$this->hasPermission($this->name . ':' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        $controllerHelper = $this->get('pggo_newsdates_module.controller_helper');
        $viewHelper = $this->get('pggo_newsdates_module.view_helper');
        
        // parameter for used sort order
        $sortdir = strtolower($sortdir);
        $request->query->set('sort', $sort);
        $request->query->set('sortdir', $sortdir);
        
        $sortableColumns = new SortableColumns($this->get('router'), 'pggonewsdatesmodule_article_' . ($isAdmin ? 'admin' : '') . 'view', 'sort', 'sortdir');
        
        $sortableColumns->addColumns([
            new Column('workflowState'),
            new Column('title'),
            new Column('displayOnIndex'),
            new Column('startDate'),
            new Column('endDatetime'),
            new Column('views'),
            new Column('event'),
            new Column('createdBy'),
            new Column('createdDate'),
            new Column('updatedBy'),
            new Column('updatedDate'),
        ]);
        
        $templateParameters = $controllerHelper->processViewActionParameters($objectType, $sortableColumns, $templateParameters, true);
        
        $featureActivationHelper = $this->get('pggo_newsdates_module.feature_activation_helper');
        if ($featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, $objectType)) {
            $templateParameters['items'] = $this->get('pggo_newsdates_module.category_helper')->filterEntitiesByPermission($templateParameters['items']);
        }
        
        foreach ($templateParameters['items'] as $k => $entity) {
            $entity->initWorkflow();
        }
        
        // fetch and return the appropriate template
        return $viewHelper->processTemplate($objectType, 'view', $templateParameters);
    }
    /**
     * This action provides a item detail view in the admin area.
     * @ParamConverter("article", class="PggoNewsDatesModule:ArticleEntity", options={"mapping": {"slug": "slug""id": "id", "repository_method" = "selectByIdList"}})
     * @Cache(lastModified="article.getUpdatedDate()", ETag="'Article' ~ article.getid() ~ article.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param ArticleEntity $article Treated article instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if item to be displayed isn't found
     */
    public function adminDisplayAction(Request $request, ArticleEntity $article)
    {
        return $this->displayInternal($request, $article, true);
    }
    
    /**
     * This action provides a item detail view.
     * @ParamConverter("article", class="PggoNewsDatesModule:ArticleEntity", options={"mapping": {"slug": "slug""id": "id", "repository_method" = "selectByIdList"}})
     * @Cache(lastModified="article.getUpdatedDate()", ETag="'Article' ~ article.getid() ~ article.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param ArticleEntity $article Treated article instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if item to be displayed isn't found
     */
    public function displayAction(Request $request, ArticleEntity $article)
    {
        return $this->displayInternal($request, $article, false);
    }
    
    /**
     * This method includes the common implementation code for adminDisplay() and display().
     */
    protected function displayInternal(Request $request, ArticleEntity $article, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'article';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_READ;
        if (!$this->hasPermission($this->name . ':' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        // create identifier for permission check
        $instanceId = $article->createCompositeIdentifier();
        if (!$this->hasPermission($this->name . ':' . ucfirst($objectType) . ':', $instanceId . '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        
        $article->initWorkflow();
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : '',
            $objectType => $article
        ];
        
        $featureActivationHelper = $this->get('pggo_newsdates_module.feature_activation_helper');
        if ($featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, $objectType)) {
            if (!$this->get('pggo_newsdates_module.category_helper')->hasPermission($article)) {
                throw new AccessDeniedException();
            }
        }
        
        $controllerHelper = $this->get('pggo_newsdates_module.controller_helper');
        $templateParameters = $controllerHelper->processDisplayActionParameters($objectType, $templateParameters, true);
        
        // fetch and return the appropriate template
        $response = $this->get('pggo_newsdates_module.view_helper')->processTemplate($objectType, 'display', $templateParameters);
        
        return $response;
    }
    /**
     * This action provides a handling of edit requests in the admin area.
     * @Cache(lastModified="article.getUpdatedDate()", ETag="'Article' ~ article.getid() ~ article.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if item to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminEditAction(Request $request)
    {
        return $this->editInternal($request, true);
    }
    
    /**
     * This action provides a handling of edit requests.
     * @Cache(lastModified="article.getUpdatedDate()", ETag="'Article' ~ article.getid() ~ article.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if item to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function editAction(Request $request)
    {
        return $this->editInternal($request, false);
    }
    
    /**
     * This method includes the common implementation code for adminEdit() and edit().
     */
    protected function editInternal(Request $request, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'article';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_EDIT;
        if (!$this->hasPermission($this->name . ':' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        $controllerHelper = $this->get('pggo_newsdates_module.controller_helper');
        $templateParameters = $controllerHelper->processEditActionParameters($objectType, $templateParameters);
        
        // delegate form processing to the form handler
        $formHandler = $this->get('pggo_newsdates_module.form.handler.article');
        $result = $formHandler->processForm($templateParameters);
        if ($result instanceof RedirectResponse) {
            return $result;
        }
        
        $templateParameters = $formHandler->getTemplateParameters();
        
        // fetch and return the appropriate template
        return $this->get('pggo_newsdates_module.view_helper')->processTemplate($objectType, 'edit', $templateParameters);
    }
    /**
     * This action provides a handling of simple delete requests in the admin area.
     * @ParamConverter("article", class="PggoNewsDatesModule:ArticleEntity", options={"mapping": {"slug": "slug""id": "id", "repository_method" = "selectByIdList"}})
     * @Cache(lastModified="article.getUpdatedDate()", ETag="'Article' ~ article.getid() ~ article.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param ArticleEntity $article Treated article instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if item to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminDeleteAction(Request $request, ArticleEntity $article)
    {
        return $this->deleteInternal($request, $article, true);
    }
    
    /**
     * This action provides a handling of simple delete requests.
     * @ParamConverter("article", class="PggoNewsDatesModule:ArticleEntity", options={"mapping": {"slug": "slug""id": "id", "repository_method" = "selectByIdList"}})
     * @Cache(lastModified="article.getUpdatedDate()", ETag="'Article' ~ article.getid() ~ article.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param ArticleEntity $article Treated article instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if item to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function deleteAction(Request $request, ArticleEntity $article)
    {
        return $this->deleteInternal($request, $article, false);
    }
    
    /**
     * This method includes the common implementation code for adminDelete() and delete().
     */
    protected function deleteInternal(Request $request, ArticleEntity $article, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'article';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_DELETE;
        if (!$this->hasPermission($this->name . ':' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $logger = $this->get('logger');
        $logArgs = ['app' => 'PggoNewsDatesModule', 'user' => $this->get('zikula_users_module.current_user')->get('uname'), 'entity' => 'article', 'id' => $article->createCompositeIdentifier()];
        
        $article->initWorkflow();
        
        // determine available workflow actions
        $workflowHelper = $this->get('pggo_newsdates_module.workflow_helper');
        $actions = $workflowHelper->getActionsForObject($article);
        if (false === $actions || !is_array($actions)) {
            $this->addFlash('error', $this->__('Error! Could not determine workflow actions.'));
            $logger->error('{app}: User {user} tried to delete the {entity} with id {id}, but failed to determine available workflow actions.', $logArgs);
            throw new \RuntimeException($this->__('Error! Could not determine workflow actions.'));
        }
        
        // redirect to the list of articles
        $redirectRoute = 'pggonewsdatesmodule_article_' . ($isAdmin ? 'admin' : '') . 'view';
        
        // check whether deletion is allowed
        $deleteActionId = 'delete';
        $deleteAllowed = false;
        foreach ($actions as $actionId => $action) {
            if ($actionId != $deleteActionId) {
                continue;
            }
            $deleteAllowed = true;
            break;
        }
        if (!$deleteAllowed) {
            $this->addFlash('error', $this->__('Error! It is not allowed to delete this article.'));
            $logger->error('{app}: User {user} tried to delete the {entity} with id {id}, but this action was not allowed.', $logArgs);
        
            return $this->redirectToRoute($redirectRoute);
        }
        
        $form = $this->createForm('Pggo\NewsDatesModule\Form\DeleteEntityType', $article);
        
        if ($form->handleRequest($request)->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $hookHelper = $this->get('pggo_newsdates_module.hook_helper');
                // Let any hooks perform additional validation actions
                $validationHooksPassed = $hookHelper->callValidationHooks($article, 'validate_delete');
                if ($validationHooksPassed) {
                    // execute the workflow action
                    $success = $workflowHelper->executeAction($article, $deleteActionId);
                    if ($success) {
                        $this->addFlash('status', $this->__('Done! Item deleted.'));
                        $logger->notice('{app}: User {user} deleted the {entity} with id {id}.', $logArgs);
                    }
                    
                    // Let any hooks know that we have deleted the article
                    $hookHelper->callProcessHooks($article, 'process_delete', null);
                    
                    return $this->redirectToRoute($redirectRoute);
                }
            } elseif ($form->get('cancel')->isClicked()) {
                $this->addFlash('status', $this->__('Operation cancelled.'));
        
                return $this->redirectToRoute($redirectRoute);
            }
        }
        
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : '',
            'deleteForm' => $form->createView(),
            $objectType => $article
        ];
        
        $controllerHelper = $this->get('pggo_newsdates_module.controller_helper');
        $templateParameters = $controllerHelper->processDeleteActionParameters($objectType, $templateParameters, true);
        
        // fetch and return the appropriate template
        return $this->get('pggo_newsdates_module.view_helper')->processTemplate($objectType, 'delete', $templateParameters);
    }

    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @param Request $request Current request instance
     *
     * @return bool true on sucess, false on failure
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function adminHandleSelectedEntriesAction(Request $request)
    {
        return $this->handleSelectedEntriesActionInternal($request, true);
    }
    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @param Request $request Current request instance
     *
     * @return bool true on sucess, false on failure
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function handleSelectedEntriesAction(Request $request)
    {
        return $this->handleSelectedEntriesActionInternal($request, false);
    }
    
    /**
     * This method includes the common implementation code for adminHandleSelectedEntriesAction() and handleSelectedEntriesAction().
     */
    protected function handleSelectedEntriesActionInternal(Request $request, $isAdmin = false)
    {
        $objectType = 'article';
        
        // Get parameters
        $action = $request->request->get('action', null);
        $items = $request->request->get('items', null);
        
        $action = strtolower($action);
        
        $selectionHelper = $this->get('pggo_newsdates_module.selection_helper');
        $workflowHelper = $this->get('pggo_newsdates_module.workflow_helper');
        $hookHelper = $this->get('pggo_newsdates_module.hook_helper');
        $logger = $this->get('logger');
        $userName = $this->get('zikula_users_module.current_user')->get('uname');
        
        // process each item
        foreach ($items as $itemid) {
            // check if item exists, and get record instance
            $entity = $selectionHelper->getEntity($objectType, $itemid, '', false);
            if (null === $entity) {
                continue;
            }
            $entity->initWorkflow();
        
            // check if $action can be applied to this entity (may depend on it's current workflow state)
            $allowedActions = $workflowHelper->getActionsForObject($entity);
            $actionIds = array_keys($allowedActions);
            if (!in_array($action, $actionIds)) {
                // action not allowed, skip this object
                continue;
            }
        
            // Let any hooks perform additional validation actions
            $hookType = $action == 'delete' ? 'validate_delete' : 'validate_edit';
            $validationHooksPassed = $hookHelper->callValidationHooks($entity, $hookType);
            if (!$validationHooksPassed) {
                continue;
            }
        
            $success = false;
            try {
                if ($action != 'delete' && !$entity->validate()) {
                    continue;
                }
                // execute the workflow action
                $success = $workflowHelper->executeAction($entity, $action);
            } catch(\Exception $e) {
                $this->addFlash('error', $this->__f('Sorry, but an error occured during the %s action.', ['%s' => $action]) . '  ' . $e->getMessage());
                $logger->error('{app}: User {user} tried to execute the {action} workflow action for the {entity} with id {id}, but failed. Error details: {errorMessage}.', ['app' => 'PggoNewsDatesModule', 'user' => $userName, 'action' => $action, 'entity' => 'article', 'id' => $itemid, 'errorMessage' => $e->getMessage()]);
            }
        
            if (!$success) {
                continue;
            }
        
            if ($action == 'delete') {
                $this->addFlash('status', $this->__('Done! Item deleted.'));
                $logger->notice('{app}: User {user} deleted the {entity} with id {id}.', ['app' => 'PggoNewsDatesModule', 'user' => $userName, 'entity' => 'article', 'id' => $itemid]);
            } else {
                $this->addFlash('status', $this->__('Done! Item updated.'));
                $logger->notice('{app}: User {user} executed the {action} workflow action for the {entity} with id {id}.', ['app' => 'PggoNewsDatesModule', 'user' => $userName, 'action' => $action, 'entity' => 'article', 'id' => $itemid]);
            }
        
            // Let any hooks know that we have updated or deleted an item
            $hookType = $action == 'delete' ? 'process_delete' : 'process_edit';
            $url = null;
            if ($action != 'delete') {
                $urlArgs = $entity->createUrlArgs();
                $urlArgs['_locale'] = $request->getLocale();
                $url = new RouteUrl('pggonewsdatesmodule_article_' . /*($isAdmin ? 'admin' : '') . */'display', $urlArgs);
            }
            $hookHelper->callProcessHooks($entity, $hookType, $url);
        }
        
        return $this->redirectToRoute('pggonewsdatesmodule_article_' . ($isAdmin ? 'admin' : '') . 'index');
    }
}