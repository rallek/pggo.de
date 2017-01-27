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

use Zikula\Component\HookDispatcher\Hook;
use Zikula\Component\HookDispatcher\HookDispatcher;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\Core\Hook\ProcessHook;
use Zikula\Core\Hook\ValidationHook;
use Zikula\Core\Hook\ValidationProviders;
use Zikula\Core\RouteUrl;

/**
 * Helper base class for hook related methods.
 */
abstract class AbstractHookHelper
{
    /**
     * @var HookDispatcher
     */
    protected $hookDispatcher;

    /**
     * HookHelper constructor.
     *
     * @param HookDispatcher $hookDispatcher Hook dispatcher service instance
     */
    public function __construct($hookDispatcher)
    {
        $this->hookDispatcher = $hookDispatcher;
    }

    /**
     * Calls validation hooks.
     *
     * @param EntityAccess $entity   The currently processed entity
     * @param string       $hookType Name of hook type to be called
     *
     * @return boolean Whether validation is passed or not
     */
    public function callValidationHooks($entity, $hookType)
    {
        $hookAreaPrefix = $entity->getHookAreaPrefix();
    
        $hook = new ValidationHook(new ValidationProviders());
        $validators = $this->dispatchHooks($hookAreaPrefix . '.' . $hookType, $hook)->getValidators();
    
        return !$validators->hasErrors();
    }

    /**
     * Calls process hooks.
     *
     * @param EntityAccess $entity The currently processed entity
     * @param string       $hookType Name of hook type to be called
     * @param RouteUrl     $url      The url object
     */
    public function callProcessHooks($entity, $hookType, $url)
    {
        $hookAreaPrefix = $entity->getHookAreaPrefix();
    
        $hook = new ProcessHook($entity->createCompositeIdentifier(), $url);
        $this->dispatchHooks($hookAreaPrefix . '.' . $hookType, $hook);
    }

    /**
     * Dispatch hooks.
     *
     * @param string $name Hook event name
     * @param Hook   $hook Hook interface
     *
     * @return Hook
     */
    public function dispatchHooks($name, Hook $hook)
    {
        return $this->hookDispatcher->dispatch($name, $hook);
    }
}
