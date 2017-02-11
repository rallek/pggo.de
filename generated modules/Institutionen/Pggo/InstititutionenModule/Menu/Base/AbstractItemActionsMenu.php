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

namespace Pggo\InstititutionenModule\Menu\Base;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Zikula\Common\Translator\TranslatorTrait;
use Pggo\InstititutionenModule\Entity\PictureEntity;
use Pggo\InstititutionenModule\Entity\InstitutionEntity;

/**
 * This is the item actions menu implementation class.
 */
class AbstractItemActionsMenu implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use TranslatorTrait;

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * Builds the menu.
     *
     * @param FactoryInterface $factory Menu factory
     * @param array            $options Additional options
     *
     * @return MenuItem The assembled menu
     */
    public function menu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('itemActions');
        if (!isset($options['entity']) || !isset($options['area']) || !isset($options['context'])) {
            return $menu;
        }

        $this->setTranslator($this->container->get('translator.default'));

        $entity = $options['entity'];
        $routeArea = $options['area'];
        $context = $options['context'];

        $permissionApi = $this->container->get('zikula_permissions_module.api.permission');
        $currentUserApi = $this->container->get('zikula_users_module.current_user');
        $menu->setChildrenAttribute('class', 'list-inline');

        $currentUserId = $currentUserApi->isLoggedIn() ? $currentUserApi->get('uid') : 1;
        if ($entity instanceof PictureEntity) {
            $component = 'PggoInstititutionenModule:Picture:';
            $instance = $entity['id'] . '::';
            $routePrefix = 'pggoinstititutionenmodule_picture_';
            $isOwner = $currentUserId > 0 && null !== $entity->getCreatedBy() && $currentUserId == $entity->getCreatedBy()->getUid();
        
            if ($routeArea == 'admin') {
                $menu->addChild($this->__('Preview'), [
                    'route' => $routePrefix . 'display',
                    'routeParameters' => ['id' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-search-plus');
                $menu[$this->__('Preview')]->setLinkAttribute('target', '_blank');
                $menu[$this->__('Preview')]->setLinkAttribute('title', $this->__('Open preview page'));
            }
            if ($context != 'display') {
                $menu->addChild($this->__('Details'), [
                    'route' => $routePrefix . $routeArea . 'display',
                    'routeParameters' => ['id' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-eye');
                $menu[$this->__('Details')]->setLinkAttribute('title', str_replace('"', '', $entity->getTitleFromDisplayPattern()));
            }
            if ($permissionApi->hasPermission($component, $instance, ACCESS_EDIT)) {
                // only allow editing for the owner or people with higher permissions
                if ($isOwner || $permissionApi->hasPermission($component, $instance, ACCESS_ADD)) {
                    $menu->addChild($this->__('Edit'), [
                        'route' => $routePrefix . $routeArea . 'edit',
                        'routeParameters' => ['id' => $entity['id']]
                    ])->setAttribute('icon', 'fa fa-pencil-square-o');
                    $menu[$this->__('Edit')]->setLinkAttribute('title', $this->__('Edit this picture'));
                    $menu->addChild($this->__('Reuse'), [
                        'route' => $routePrefix . $routeArea . 'edit',
                        'routeParameters' => ['astemplate' => $entity['id']]
                    ])->setAttribute('icon', 'fa fa-files-o');
                    $menu[$this->__('Reuse')]->setLinkAttribute('title', $this->__('Reuse for new picture'));
                }
            }
            if ($context == 'display') {
                $title = $this->__('Back to overview');
                $menu->addChild($title, [
                    'route' => $routePrefix . $routeArea . 'view'
                ])->setAttribute('icon', 'fa fa-reply');
                $menu[$title]->setLinkAttribute('title', $title);
            }
        }
        if ($entity instanceof InstitutionEntity) {
            $component = 'PggoInstititutionenModule:Institution:';
            $instance = $entity['id'] . '::';
            $routePrefix = 'pggoinstititutionenmodule_institution_';
            $isOwner = $currentUserId > 0 && null !== $entity->getCreatedBy() && $currentUserId == $entity->getCreatedBy()->getUid();
        
            if ($routeArea == 'admin') {
                $menu->addChild($this->__('Preview'), [
                    'route' => $routePrefix . 'display',
                    'routeParameters' => ['id' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-search-plus');
                $menu[$this->__('Preview')]->setLinkAttribute('target', '_blank');
                $menu[$this->__('Preview')]->setLinkAttribute('title', $this->__('Open preview page'));
            }
            if ($context != 'display') {
                $menu->addChild($this->__('Details'), [
                    'route' => $routePrefix . $routeArea . 'display',
                    'routeParameters' => ['id' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-eye');
                $menu[$this->__('Details')]->setLinkAttribute('title', str_replace('"', '', $entity->getTitleFromDisplayPattern()));
            }
            if ($permissionApi->hasPermission($component, $instance, ACCESS_EDIT)) {
                $menu->addChild($this->__('Edit'), [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['id' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-pencil-square-o');
                $menu[$this->__('Edit')]->setLinkAttribute('title', $this->__('Edit this institution'));
                $menu->addChild($this->__('Reuse'), [
                    'route' => $routePrefix . $routeArea . 'edit',
                    'routeParameters' => ['astemplate' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-files-o');
                $menu[$this->__('Reuse')]->setLinkAttribute('title', $this->__('Reuse for new institution'));
            }
            if ($context == 'display') {
                $title = $this->__('Back to overview');
                $menu->addChild($title, [
                    'route' => $routePrefix . $routeArea . 'view'
                ])->setAttribute('icon', 'fa fa-reply');
                $menu[$title]->setLinkAttribute('title', $title);
            }
            
            // more actions for adding new related items
            
            $relatedComponent = 'PggoInstititutionenModule:Picture:';
            $relatedInstance = $entity['id'] . '::';
            if ($isOwner || $permissionApi->hasPermission($relatedComponent, $relatedInstance, ACCESS_ADD)) {
                $title = $this->__('Create picture');
                $menu->addChild($title, [
                    'route' => 'pggoinstititutionenmodule_picture_' . $routeArea . 'edit',
                    'routeParameters' => ['institution' => $entity['id']]
                ])->setAttribute('icon', 'fa fa-plus');
                $menu[$title]->setLinkAttribute('title', $title);
            }
        }

        return $menu;
    }
}
