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

namespace Pggo\NewsDatesModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Event handler base class for Symfony kernel events.
 */
abstract class AbstractKernelListener implements EventSubscriberInterface
{
    /**
     * Makes our handlers known to the event system.
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST        => ['onRequest', 5],
            KernelEvents::CONTROLLER     => ['onController', 5],
            KernelEvents::VIEW           => ['onView', 5],
            KernelEvents::RESPONSE       => ['onResponse', 5],
            KernelEvents::FINISH_REQUEST => ['onFinishRequest', 5],
            KernelEvents::TERMINATE      => ['onTerminate', 5],
            KernelEvents::EXCEPTION      => ['onException', 5]
        ];
    }
    
    /**
     * Listener for the `kernel.request` event.
     *
     * Occurs after the request handling has started.
     *
     * If possible you can return a Response object directly (for example showing a "maintenance mode" page).
     * The first listener returning a response stops event propagation.
     * Also you can initialise variables and inject information into the request attributes.
     *
     * Example from Symfony: the RouterListener determines controller and information about arguments.
     *
     * @param GetResponseEvent $event The event instance
     */
    public function onRequest(GetResponseEvent $event)
    {
    }
    
    /**
     * Listener for the `kernel.controller` event.
     *
     * Occurs after routing has been done and the controller has been selected.
     *
     * You can initialise things requiring the controller and/or routing information.
     * Also you can change the controller before it is executed.
     *
     * Example from Symfony: the ParamConverterListener performs reflection and type conversion.
     *
     * @param FilterControllerEvent $event The event instance
     */
    public function onController(FilterControllerEvent $event)
    {
    }
    
    /**
     * Listener for the `kernel.view` event.
     *
     * Occurs only if the controller did not return a Response object.
     *
     * You can convert the controller's return value into a Response object.
     * This is useful for own view layers.
     * The first listener returning a response stops event propagation.
     *
     * Example from Symfony: TemplateListener renders Twig templates with returned arrays.
     *
     * @param GetResponseForControllerResultEvent $event The event instance
     */
    public function onView(GetResponseForControllerResultEvent $event)
    {
    }
    
    /**
     * Listener for the `kernel.response` event.
     *
     * Occurs after a response has been created and returned to the kernel.
     *
     * You can modify or replace the response object, including http headers,
     * cookies, and so on. Of course you can also amend the actual content by
     * for example injecting some custom JavaScript code.
     * Of course you can use request attributes you set in onKernelRequest
     * or onKernelController or other events happened before.
     *
     * Examples from Symfony:
     *    - ContextListener: serialises user data into session for next request
     *    - WebDebugToolbarListener: injects the web debug toolbar
     *    - ResponseListener: updates the content type according to the request format
     *
     * @param FilterResponseEvent $event The event instance
     */
    public function onResponse(FilterResponseEvent $event)
    {
    }
    
    /**
     * Listener for the `kernel.finish_request` event.
     *
     * Occurs after processing a request has been completed.
     * Called after a normal response as well as after an exception was thrown.
     *
     * You can cleanup things here which are not directly related to the response.
     *
     * @param FinishRequestEvent $event The event instance
     */
    public function onFinishRequest(FinishRequestEvent $event)
    {
    }
    
    /**
     * Listener for the `kernel.terminate` event.
     *
     * Occurs before the system is shutted down.
     *
     * You can perform any bigger tasks which can be delayed until the Response
     * has been served to the client. One example is sending some spooled emails.
     *
     * Example from Symfony: SwiftmailerBundle with memory spooling activates an
     * EmailSenderListener which delivers emails created during the request.
     *
     * @param PostResponseEvent $event The event instance
     */
    public function onTerminate(PostResponseEvent $event)
    {
    }
    
    /**
     * Listener for the `kernel.exception` event.
     *
     * Occurs whenever an exception is thrown. Handles (different types
     * of) exceptions and creates a fitting Response object for them.
     *
     * You can inject custom error handling for specific error types.
     *
     * @param GetResponseForExceptionEvent $event The event instance
     */
    public function onException(GetResponseForExceptionEvent $event)
    {
    }
}
