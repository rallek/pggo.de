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

namespace Pggo\InstititutionenModule\Controller;

use Pggo\InstititutionenModule\Controller\Base\AbstractAjaxController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use RuntimeException;
use Zikula\Core\Response\Ajax\AjaxResponse;
use Zikula\Core\Response\Ajax\BadDataResponse;
use Zikula\Core\Response\Ajax\FatalResponse;
use Zikula\Core\Response\Ajax\NotFoundResponse;

/**
 * Ajax controller implementation class.
 *
 * @Route("/ajax")
 */
class AjaxController extends AbstractAjaxController
{
    
    /**
     * Retrieves a general purpose list of users.
     *
     * @Route("/getCommonUsersList", options={"expose"=true})
     * @Method("GET")
     *
     * @param Request $request Current request instance
     *
     * @return JsonResponse
     */ 
    public function getCommonUsersListAction(Request $request)
    {
        return parent::getCommonUsersListAction($request);
    }
    
    /**
     * Retrieve item list for finder selections in Forms, Content type plugin and Scribite.
     *
     * @Route("/getItemListFinder", options={"expose"=true})
     * @Method("POST")
     *
     * @param string $ot      Name of currently used object type
     * @param string $sort    Sorting field
     * @param string $sortdir Sorting direction
     *
     * @return AjaxResponse
     */
    public function getItemListFinderAction(Request $request)
    {
        return parent::getItemListFinderAction($request);
    }

    // feel free to add your own ajax controller methods here
}
