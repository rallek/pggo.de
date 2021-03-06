<?php
/**
 * Team.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\TeamModule\Form\Base;

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
        $this->modVars = $this->variableApi->getAll('RKTeamModule');
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
        $this->addListViewsFields($builder, $options);
        $this->addImagesFields($builder, $options);

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
     * Adds fields for list views fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addListViewsFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personEntriesPerPage', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('Person entries per page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('The amount of persons shown per page')
                ],
                'help' => $this->__('The amount of persons shown per page'),
                'required' => false,
                'data' => isset($this->modVars['personEntriesPerPage']) ? $this->modVars['personEntriesPerPage'] : '',
                'empty_data' => intval('10'),
                'attr' => [
                    'maxlength' => 255,
                    'title' => $this->__('Enter the person entries per page.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0
            ])
            ->add('linkOwnPersonsOnAccountPage', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
                'label' => $this->__('Link own persons on account page') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Whether to add a link to persons of the current user on his account page')
                ],
                'help' => $this->__('Whether to add a link to persons of the current user on his account page'),
                'required' => false,
                'data' => (bool)(isset($this->modVars['linkOwnPersonsOnAccountPage']) ? $this->modVars['linkOwnPersonsOnAccountPage'] : true),
                'attr' => [
                    'title' => $this->__('The link own persons on account page option.')
                ],
            ])
        ;
    }

    /**
     * Adds fields for images fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addImagesFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enableShrinkingForPersonTeamMemberImage', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
                'label' => $this->__('Enable shrinking for person team member image') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Whether to enable shrinking huge images to maximum dimensions. Stores downscaled version of the original image.')
                ],
                'help' => $this->__('Whether to enable shrinking huge images to maximum dimensions. Stores downscaled version of the original image.'),
                'required' => false,
                'data' => (bool)(isset($this->modVars['enableShrinkingForPersonTeamMemberImage']) ? $this->modVars['enableShrinkingForPersonTeamMemberImage'] : false),
                'attr' => [
                    'title' => $this->__('The enable shrinking for person team member image option.'),
                    'class' => 'shrink-enabler'
                ],
            ])
            ->add('shrinkWidthPersonTeamMemberImage', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('Shrink width person team member image') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('The maximum image width in pixels.')
                ],
                'help' => $this->__('The maximum image width in pixels.'),
                'required' => false,
                'data' => isset($this->modVars['shrinkWidthPersonTeamMemberImage']) ? $this->modVars['shrinkWidthPersonTeamMemberImage'] : '',
                'empty_data' => intval('800'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the shrink width person team member image.') . ' ' . $this->__('Only digits are allowed.'),
                    'class' => 'shrinkdimension-shrinkwidthpersonteammemberimage'
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('shrinkHeightPersonTeamMemberImage', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('Shrink height person team member image') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('The maximum image height in pixels.')
                ],
                'help' => $this->__('The maximum image height in pixels.'),
                'required' => false,
                'data' => isset($this->modVars['shrinkHeightPersonTeamMemberImage']) ? $this->modVars['shrinkHeightPersonTeamMemberImage'] : '',
                'empty_data' => intval('600'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the shrink height person team member image.') . ' ' . $this->__('Only digits are allowed.'),
                    'class' => 'shrinkdimension-shrinkheightpersonteammemberimage'
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailModePersonTeamMemberImage', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => $this->__('Thumbnail mode person team member image') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail mode (inset or outbound).')
                ],
                'help' => $this->__('Thumbnail mode (inset or outbound).'),
                'required' => false,
                'data' => isset($this->modVars['thumbnailModePersonTeamMemberImage']) ? $this->modVars['thumbnailModePersonTeamMemberImage'] : '',
                'empty_data' => 'inset',
                'attr' => [
                    'title' => $this->__('Choose the thumbnail mode person team member image.')
                ],'choices' => [
                    $this->__('Inset') => 'inset'
                    ,$this->__('Outbound') => 'outbound'
                ],
                'choices_as_values' => true,
                'multiple' => false
            ])
            ->add('thumbnailWidthPersonTeamMemberImageView', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('Thumbnail width person team member image view') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail width on view pages in pixels.')
                ],
                'help' => $this->__('Thumbnail width on view pages in pixels.'),
                'required' => false,
                'data' => isset($this->modVars['thumbnailWidthPersonTeamMemberImageView']) ? $this->modVars['thumbnailWidthPersonTeamMemberImageView'] : '',
                'empty_data' => intval('32'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail width person team member image view.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailHeightPersonTeamMemberImageView', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('Thumbnail height person team member image view') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail height on view pages in pixels.')
                ],
                'help' => $this->__('Thumbnail height on view pages in pixels.'),
                'required' => false,
                'data' => isset($this->modVars['thumbnailHeightPersonTeamMemberImageView']) ? $this->modVars['thumbnailHeightPersonTeamMemberImageView'] : '',
                'empty_data' => intval('24'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail height person team member image view.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailWidthPersonTeamMemberImageDisplay', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('Thumbnail width person team member image display') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail width on display pages in pixels.')
                ],
                'help' => $this->__('Thumbnail width on display pages in pixels.'),
                'required' => false,
                'data' => isset($this->modVars['thumbnailWidthPersonTeamMemberImageDisplay']) ? $this->modVars['thumbnailWidthPersonTeamMemberImageDisplay'] : '',
                'empty_data' => intval('240'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail width person team member image display.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailHeightPersonTeamMemberImageDisplay', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('Thumbnail height person team member image display') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail height on display pages in pixels.')
                ],
                'help' => $this->__('Thumbnail height on display pages in pixels.'),
                'required' => false,
                'data' => isset($this->modVars['thumbnailHeightPersonTeamMemberImageDisplay']) ? $this->modVars['thumbnailHeightPersonTeamMemberImageDisplay'] : '',
                'empty_data' => intval('180'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail height person team member image display.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailWidthPersonTeamMemberImageEdit', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('Thumbnail width person team member image edit') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail width on edit pages in pixels.')
                ],
                'help' => $this->__('Thumbnail width on edit pages in pixels.'),
                'required' => false,
                'data' => isset($this->modVars['thumbnailWidthPersonTeamMemberImageEdit']) ? $this->modVars['thumbnailWidthPersonTeamMemberImageEdit'] : '',
                'empty_data' => intval('240'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail width person team member image edit.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
            ->add('thumbnailHeightPersonTeamMemberImageEdit', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
                'label' => $this->__('Thumbnail height person team member image edit') . ':',
                'label_attr' => [
                    'class' => 'tooltips',
                    'title' => $this->__('Thumbnail height on edit pages in pixels.')
                ],
                'help' => $this->__('Thumbnail height on edit pages in pixels.'),
                'required' => false,
                'data' => isset($this->modVars['thumbnailHeightPersonTeamMemberImageEdit']) ? $this->modVars['thumbnailHeightPersonTeamMemberImageEdit'] : '',
                'empty_data' => intval('180'),
                'attr' => [
                    'maxlength' => 4,
                    'title' => $this->__('Enter the thumbnail height person team member image edit.') . ' ' . $this->__('Only digits are allowed.')
                ],'scale' => 0,
                'input_group' => ['right' => $this->__('pixels')]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'rkteammodule_appsettings';
    }
}
