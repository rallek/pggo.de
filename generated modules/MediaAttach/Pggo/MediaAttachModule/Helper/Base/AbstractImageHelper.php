<?php
/**
 * MediaAttach.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://pggo.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace Pggo\MediaAttachModule\Helper\Base;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\ExtensionsModule\Api\VariableApi;

/**
 * Helper base class for image methods.
 */
abstract class AbstractImageHelper
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var VariableApi
     */
    protected $variableApi;

    /**
     * Name of the application.
     *
     * @var string
     */
    protected $name;

    /**
     * ImageHelper constructor.
     *
     * @param TranslatorInterface $translator  Translator service instance
     * @param SessionInterface    $session     Session service instance
     * @param VariableApi         $variableApi VariableApi service instance
     */
    public function __construct(TranslatorInterface $translator, SessionInterface $session, VariableApi $variableApi)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->variableApi = $variableApi;
        $this->name = 'PggoMediaAttachModule';
    }

    /**
     * This method returns an Imagine runtime options array for the given arguments.
     *
     * @param string $objectType Currently treated entity type
     * @param string $fieldName  Name of upload field
     * @param string $context    Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args       Additional arguments
     *
     * @return array The selected runtime options
     */
    public function getRuntimeOptions($objectType = '', $fieldName = '', $context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'actionHandler', 'block', 'contentType'])) {
            $context = 'controllerAction';
        }
    
        $contextName = '';
        if ($context == 'controllerAction') {
            if (!isset($args['controller'])) {
                $args['controller'] = 'user';
            }
            if (!isset($args['action'])) {
                $args['action'] = 'index';
            }
    
            $contextName = $this->name . '_' . $args['controller'] . '_' . $args['action'];
        }
        if (empty($contextName)) {
            $contextName = $this->name . '_default';
        }
    
        return $this->getCustomRuntimeOptions($objectType, $fieldName, $contextName, $context, $args);
    }

    /**
     * This method returns an Imagine runtime options array for the given arguments.
     *
     * @param string $objectType Currently treated entity type
     * @param string $fieldName  Name of upload field
     * @param string $contextName Name of desired context
     * @param string $context    Usage context (allowed values: controllerAction, api, actionHandler, block, contentType)
     * @param array  $args       Additional arguments
     *
     * @return array The selected runtime options
     */
    public function getCustomRuntimeOptions($objectType = '', $fieldName = '', $contextName = '', $context = '', $args = [])
    {
        $options = [
            'thumbnail' => [
                'size'      => [100, 100], // thumbnail width and height in pixels
                'mode'      => $this->variableApi->get('PggoMediaAttachModule', 'thumbnailMode' . ucfirst($objectType) . ucfirst($fieldName), 'inset'),
                'extension' => null        // file extension for thumbnails (jpg, png, gif; null for original file type)
            ]
        ];
    
        if ($contextName == $this->name . '_relateditem') {
            $options['thumbnail']['size'] = [100, 75];
        } elseif ($context == 'controllerAction') {
            if (in_array($args['action'], ['view', 'display', 'edit'])) {
                $fieldSuffix = ucfirst($objectType) . ucfirst($fieldName) . ucfirst($args['action']);
                $defaultWidth = $args['action'] == 'view' ? 32 : 240;
                $defaultHeight = $args['action'] == 'view' ? 24 : 180;
                $options['thumbnail']['size'] = [
                    $this->variableApi->get('PggoMediaAttachModule', 'thumbnailWidth' . $fieldSuffix, $defaultWidth),
                    $this->variableApi->get('PggoMediaAttachModule', 'thumbnailHeight' . $fieldSuffix, $defaultHeight)
                ];
            }
        }
    
        return $options;
    }
}
