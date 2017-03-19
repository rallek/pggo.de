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

namespace Pggo\InstititutionenModule\Container\Base;

use Zikula\Bundle\HookBundle\AbstractHookContainer as ZikulaHookContainer;

use Zikula\Bundle\HookBundle\Bundle\SubscriberBundle;

/**
 * Base class for hook container methods.
 */
abstract class AbstractHookContainer extends ZikulaHookContainer
{
    /**
     * Define the hook bundles supported by this module.
     *
     * @return void
     */
    protected function setupHookBundles()
    {
        $bundle = new SubscriberBundle('PggoInstititutionenModule', 'subscriber.pggoinstititutionenmodule.ui_hooks.pictures', 'ui_hooks', $this->__('pggoinstititutionenmodule. Pictures Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'pggoinstititutionenmodule.ui_hooks.pictures.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'pggoinstititutionenmodule.ui_hooks.pictures.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'pggoinstititutionenmodule.ui_hooks.pictures.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'pggoinstititutionenmodule.ui_hooks.pictures.validate_edit');
        // Validate input from an ui delete form.
        $bundle->addEvent('validate_delete', 'pggoinstititutionenmodule.ui_hooks.pictures.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'pggoinstititutionenmodule.ui_hooks.pictures.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'pggoinstititutionenmodule.ui_hooks.pictures.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('PggoInstititutionenModule', 'subscriber.pggoinstititutionenmodule.filter_hooks.pictures', 'filter_hooks', $this->__('pggoinstititutionenmodule. Pictures Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'pggoinstititutionenmodule.filter_hooks.pictures.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('PggoInstititutionenModule', 'subscriber.pggoinstititutionenmodule.ui_hooks.institutions', 'ui_hooks', $this->__('pggoinstititutionenmodule. Institutions Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'pggoinstititutionenmodule.ui_hooks.institutions.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'pggoinstititutionenmodule.ui_hooks.institutions.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'pggoinstititutionenmodule.ui_hooks.institutions.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'pggoinstititutionenmodule.ui_hooks.institutions.validate_edit');
        // Validate input from an ui delete form.
        $bundle->addEvent('validate_delete', 'pggoinstititutionenmodule.ui_hooks.institutions.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'pggoinstititutionenmodule.ui_hooks.institutions.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'pggoinstititutionenmodule.ui_hooks.institutions.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('PggoInstititutionenModule', 'subscriber.pggoinstititutionenmodule.filter_hooks.institutions', 'filter_hooks', $this->__('pggoinstititutionenmodule. Institutions Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'pggoinstititutionenmodule.filter_hooks.institutions.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        
        
    }
}