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

namespace Pggo\NewsDatesModule\Helper\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Zikula\Core\Response\PlainResponse;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\PermissionsModule\Api\PermissionApi;
use Pggo\NewsDatesModule\Helper\ControllerHelper;

/**
 * Helper base class for view layer methods.
 */
abstract class AbstractViewHelper
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var PermissionApi
     */
    protected $permissionApi;

    /**
     * @var VariableApi
     */
    protected $variableApi;

    /**
     * @var ControllerHelper
     */
    protected $controllerHelper;

    /**
     * ViewHelper constructor.
     *
     * @param EngineInterface  $templating       EngineInterface service instance
     * @param RequestStack     $requestStack     RequestStack service instance
     * @param PermissionApi    $permissionApi    PermissionApi service instance
     * @param VariableApi      $variableApi      VariableApi service instance
     * @param ControllerHelper $controllerHelper ControllerHelper service instance
     *
     * @return void
     */
    public function __construct(EngineInterface $templating, RequestStack $requestStack, PermissionApi $permissionApi, VariableApi $variableApi, ControllerHelper $controllerHelper)
    {
        $this->templating = $templating;
        $this->request = $requestStack->getCurrentRequest();
        $this->permissionApi = $permissionApi;
        $this->variableApi = $variableApi;
        $this->controllerHelper = $controllerHelper;
    }

    /**
     * Determines the view template for a certain method with given parameters.
     *
     * @param string $type Current controller (name of currently treated entity)
     * @param string $func Current function (index, view, ...)
     *
     * @return string name of template file
     */
    public function getViewTemplate($type, $func)
    {
        // create the base template name
        $template = '@PggoNewsDatesModule/' . ucfirst($type) . '/' . $func;
    
        // check for template extension
        $templateExtension = '.' . $this->determineExtension($type, $func);
    
        // check whether a special template is used
        $tpl = $this->request->query->getAlnum('tpl', '');
        if (!empty($tpl)) {
            // check if custom template exists
            $customTemplate = $template . ucfirst($tpl);
            if ($this->templating->exists($customTemplate . $templateExtension)) {
                $template = $customTemplate;
            }
        }
    
        $template .= $templateExtension;
    
        return $template;
    }

    /**
     * Helper method for managing view templates.
     *
     * @param string  $type               Current controller (name of currently treated entity)
     * @param string  $func               Current function (index, view, ...)
     * @param array   $templateParameters Template data
     * @param string  $template           Optional assignment of precalculated template file
     *
     * @return mixed Output
     */
    public function processTemplate($type, $func, array $templateParameters = [], $template = '')
    {
        $templateExtension = $this->determineExtension($type, $func);
        if (empty($template)) {
            $template = $this->getViewTemplate($type, $func);
        }
    
        if ($templateExtension == 'pdf.twig') {
            $template = str_replace('.pdf', '.html', $template);
    
            return $this->processPdf($templateParameters, $template);
        }
    
        // look whether we need output with or without the theme
        $raw = $this->request->query->getBoolean('raw', false);
        if (!$raw && $templateExtension != 'html.twig') {
            $raw = true;
        }
    
        $output = $this->templating->render($template, $templateParameters);
        $response = null;
        if (true === $raw) {
            // standalone output
            $response = new PlainResponse($output);
        } else {
            // normal output
            $response = new Response($output);
        }
    
        // check if we need to set any custom headers
        switch ($templateExtension) {
        }
    
        return $response;
    }

    /**
     * Get extension of the currently treated template.
     *
     * @param string $type Current controller (name of currently treated entity)
     * @param string $func Current function (index, view, ...)
     *
     * @return array List of allowed template extensions
     */
    protected function determineExtension($type, $func)
    {
        $templateExtension = 'html.twig';
        if (!in_array($func, ['view', 'display'])) {
            return $templateExtension;
        }
    
        $extensions = $this->availableExtensions($type, $func);
        $format = $this->request->getRequestFormat();
        if ($format != 'html' && in_array($format, $extensions)) {
            $templateExtension = $format . '.twig';
        }
    
        return $templateExtension;
    }

    /**
     * Get list of available template extensions.
     *
     * @param string $type Current controller (name of currently treated entity)
     * @param string $func Current function (index, view, ...)
     *
     * @return array List of allowed template extensions
     */
    public function availableExtensions($type, $func)
    {
        $extensions = [];
        $hasAdminAccess = $this->permissionApi->hasPermission('PggoNewsDatesModule:' . ucfirst($type) . ':', '::', ACCESS_ADMIN);
        if ($func == 'view') {
            if ($hasAdminAccess) {
                $extensions = [];
            } else {
                $extensions = [];
            }
        } elseif ($func == 'display') {
            if ($hasAdminAccess) {
                $extensions = [];
            } else {
                $extensions = [];
            }
        }
    
        return $extensions;
    }

    /**
     * Processes a template file using dompdf (LGPL).
     *
     * @param array  $templateParameters Template data
     * @param string $template           Name of template to use
     *
     * @return mixed Output
     */
    protected function processPdf(array $templateParameters = [], $template)
    {
        // first the content, to set page vars
        $output = $this->templating->render($template, $templateParameters);
    
        // make local images absolute
        $output = str_replace('img src="/', 'img src="' . $this->request->server->get('DOCUMENT_ROOT') . '/', $output);
    
        // see http://codeigniter.com/forums/viewthread/69388/P15/#561214
        //$output = utf8_decode($output);
    
        // then the surrounding
        $output = $this->templating->render('includePdfHeader.html.twig') . $output . '</body></html>';
    
        $siteName = $this->variableApi->getSystemVar('sitename');
    
        // create name of the pdf output file
        $fileTitle = $this->controllerHelper->formatPermalink($siteName)
                   . '-'
                   . $this->controllerHelper->formatPermalink(\PageUtil::getVar('title'))
                   . '-' . date('Ymd') . '.pdf';
    
        /*
        if (true === $this->request->query->getBoolean('dbg', false)) {
            die($output);
        }
        */
    
        // instantiate pdf object
        $pdf = new \DOMPDF();
        // define page properties
        $pdf->set_paper('A4');
        // load html input data
        $pdf->load_html($output);
        // create the actual pdf file
        $pdf->render();
        // stream output to browser
        $pdf->stream($fileTitle);
    
        return new Response(); 
    }
}
