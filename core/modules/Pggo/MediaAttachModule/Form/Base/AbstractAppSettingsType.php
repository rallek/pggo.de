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

namespace Pggo\MediaAttachModule\Form\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\ExtensionsModule\Api\VariableApi;

/**
 * Configuration form type base class.
 */
abstract class AbstractAppSettingsType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var VariableApi
     */
    protected $variableApi;

    /**
     * @var array
     */
    protected $modVars;

    /**
     * AppSettingsType constructor.
     *
     * @param TranslatorInterface $translator  Translator service instance
     * @param VariableApi         $variableApi VariableApi service instance
     */
    public function __construct(TranslatorInterface $translator, VariableApi $variableApi)
    {
        $this->setTranslator($translator);
        $this->variableApi = $variableApi;
        $this->modVars = $this->variableApi->getAll('PggoMediaAttachModule');
    }

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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addFileSettingsFields($builder, $options);
        $this->addListViewsFields($builder, $options);

        $builder
            ->add('save', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => $this->__('Update configuration'),
                'icon' => 'fa-check',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->add('cancel', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => $this->__('Cancel'),
                'icon' => 'fa-times',
                'attr' => [
                    'class' => 'btn btn-default',
                    'formnovalidate' => 'formnovalidate'
                ]
            ])
        ;
    }

    /**
     * Adds fields for file settings fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addFileSettingsFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fileTypes', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => $this->__('File types') . ':',
                'required' => false,
                'data' => isset($this->modVars['fileTypes']) ? $this->modVars['fileTypes'] : '',
                'empty_data' => 'pdf',
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the file types.')
                ],
            ])
            ->add('filePath', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => $this->__('File path') . ':',
                'required' => false,
                'data' => isset($this->modVars['filePath']) ? $this->modVars['filePath'] : '',
                'empty_data' => '',
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the file path.')
                ],
            ])
            ->add('uploadFileSize', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => $this->__('Upload file size') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('e.g. 200k, 2M')
                ],
                'help' => $this->__('e.g. 200k, 2M'),
                'required' => false,
                'data' => isset($this->modVars['uploadFileSize']) ? $this->modVars['uploadFileSize'] : '',
                'empty_data' => '500k',
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the upload file size.')
                ],
            ])
        ;
    }

    /**
     * Adds fields for list views fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addListViewsFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fileEntriesPerPage', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('File entries per page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('The amount of files shown per page')
                ],
                'help' => $this->__('The amount of files shown per page'),
                'required' => false,
                'data' => isset($this->modVars['fileEntriesPerPage']) ? $this->modVars['fileEntriesPerPage'] : '',
                'empty_data' => intval('10'),
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the file entries per page.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0
            ])
            ->add('linkOwnFilesOnAccountPage', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
                'label' => $this->__('Link own files on account page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Whether to add a link to files of the current user on his account page')
                ],
                'help' => $this->__('Whether to add a link to files of the current user on his account page'),
                'required' => false,
                'data' => (bool)(isset($this->modVars['linkOwnFilesOnAccountPage']) ? $this->modVars['linkOwnFilesOnAccountPage'] : true),
                'attr' => [
                    'title' => $this->__('The link own files on account page option.')
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pggomediaattachmodule_appsettings';
    }
}
