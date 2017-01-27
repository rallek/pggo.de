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

namespace Pggo\NewsDatesModule\Container\Base;

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
        $bundle = new SubscriberBundle('PggoNewsDatesModule', 'subscriber.pggonewsdatesmodule.ui_hooks.articles', 'ui_hooks', $this->__('pggonewsdatesmodule. Articles Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'pggonewsdatesmodule.ui_hooks.articles.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'pggonewsdatesmodule.ui_hooks.articles.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'pggonewsdatesmodule.ui_hooks.articles.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'pggonewsdatesmodule.ui_hooks.articles.validate_edit');
        // Validate input from an ui delete form.
        $bundle->addEvent('validate_delete', 'pggonewsdatesmodule.ui_hooks.articles.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'pggonewsdatesmodule.ui_hooks.articles.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'pggonewsdatesmodule.ui_hooks.articles.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('PggoNewsDatesModule', 'subscriber.pggonewsdatesmodule.filter_hooks.articles', 'filter_hooks', $this->__('pggonewsdatesmodule. Articles Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'pggonewsdatesmodule.filter_hooks.articles.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('PggoNewsDatesModule', 'subscriber.pggonewsdatesmodule.ui_hooks.events', 'ui_hooks', $this->__('pggonewsdatesmodule. Events Display Hooks'));
        
        // Display hook for view/display templates.
        $bundle->addEvent('display_view', 'pggonewsdatesmodule.ui_hooks.events.display_view');
        // Display hook for create/edit forms.
        $bundle->addEvent('form_edit', 'pggonewsdatesmodule.ui_hooks.events.form_edit');
        // Display hook for delete dialogues.
        $bundle->addEvent('form_delete', 'pggonewsdatesmodule.ui_hooks.events.form_delete');
        // Validate input from an ui create/edit form.
        $bundle->addEvent('validate_edit', 'pggonewsdatesmodule.ui_hooks.events.validate_edit');
        // Validate input from an ui delete form.
        $bundle->addEvent('validate_delete', 'pggonewsdatesmodule.ui_hooks.events.validate_delete');
        // Perform the final update actions for a ui create/edit form.
        $bundle->addEvent('process_edit', 'pggonewsdatesmodule.ui_hooks.events.process_edit');
        // Perform the final delete actions for a ui form.
        $bundle->addEvent('process_delete', 'pggonewsdatesmodule.ui_hooks.events.process_delete');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new SubscriberBundle('PggoNewsDatesModule', 'subscriber.pggonewsdatesmodule.filter_hooks.events', 'filter_hooks', $this->__('pggonewsdatesmodule. Events Filter Hooks'));
        // A filter applied to the given area.
        $bundle->addEvent('filter', 'pggonewsdatesmodule.filter_hooks.events.filter');
        $this->registerHookSubscriberBundle($bundle);
        
        
        
    }
}
