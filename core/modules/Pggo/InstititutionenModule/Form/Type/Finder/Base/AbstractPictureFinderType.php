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

namespace Pggo\InstititutionenModule\Form\Type\Finder\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Pggo\InstititutionenModule\Helper\FeatureActivationHelper;

/**
 * Picture finder form type base class.
 */
abstract class AbstractPictureFinderType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * PictureFinderType constructor.
     *
     * @param TranslatorInterface $translator Translator service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(TranslatorInterface $translator, FeatureActivationHelper $featureActivationHelper)
    {
        $this->setTranslator($translator);
        $this->featureActivationHelper = $featureActivationHelper;
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
        $builder
            ->setMethod('GET')
            ->add('objectType', 'Symfony\Component\Form\Extension\Core\Type\HiddenType', [
                'data' => $options['objectType']
            ])
            ->add('editor', 'Symfony\Component\Form\Extension\Core\Type\HiddenType', [
                'data' => $options['editorName']
            ])
        ;

        $this->addImageFields($builder, $options);
        $this->addPasteAsField($builder, $options);
        $this->addSortingFields($builder, $options);
        $this->addAmountField($builder, $options);
        $this->addSearchField($builder, $options);

        $builder
            ->add('update', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => $this->__('Change selection'),
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
     * Adds fields for image insertion options.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addImageFields(FormBuilderInterface $builder, array $options)
    {
        $builder->add('onlyImages', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
            'label' => $this->__('Only images'),
            'empty_data' => false,
            'help' => $this->__('Enable this option to insert images'),
            'required' => false
        ]);
        $builder->add('imageField', 'Symfony\Component\Form\Extension\Core\Type\HiddenType', [
            'data' => 'image'
        ]);
    }

    /**
     * Adds a "paste as" field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addPasteAsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pasteAs', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
            'label' => $this->__('Paste as') . ':',
            'empty_data' => 1,
            'choices' => [
                $this->__('Relative link to the picture') => 1,
                $this->__('Absolute url to the picture') => 2,
                $this->__('ID of picture') => 3,
                $this->__('Relative link to the image') => 6,
                $this->__('Image') => 7,
                $this->__('Image with relative link to the picture') => 8,
                $this->__('Image with absolute url to the picture') => 9
            ],
            'choices_as_values' => true,
            'multiple' => false,
            'expanded' => false
        ]);
    }

    /**
     * Adds sorting fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSortingFields(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sort', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => $this->__('Sort by') . ':',
                'empty_data' => '',
                'choices' => [
                    $this->__('Title') => 'title',
                    $this->__('Copyright') => 'copyright',
                    $this->__('Creation date') => 'createdDate',
                    $this->__('Creator') => 'createdBy',
                    $this->__('Update date') => 'updatedDate',
                    $this->__('Updater') => 'updatedBy'
                ],
                'choices_as_values' => true,
                'multiple' => false,
                'expanded' => false
            ])
            ->add('sortdir', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => $this->__('Sort direction') . ':',
                'empty_data' => 'asc',
                'choices' => [
                    $this->__('Ascending') => 'asc',
                    $this->__('Descending') => 'desc'
                ],
                'choices_as_values' => true,
                'multiple' => false,
                'expanded' => false
            ])
        ;
    }

    /**
     * Adds a page size field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addAmountField(FormBuilderInterface $builder, array $options)
    {
        $builder->add('num', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
            'label' => $this->__('Page size') . ':',
            'empty_data' => 20,
            'attr' => [
                'class' => 'text-right'
            ],
            'choices' => [
                $this->__('5') => 5,
                $this->__('10') => 10,
                $this->__('15') => 15,
                $this->__('20') => 20,
                $this->__('30') => 30,
                $this->__('50') => 50,
                $this->__('100') => 100
            ],
            'choices_as_values' => true,
            'multiple' => false,
            'expanded' => false
        ]);
    }

    /**
     * Adds a search field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSearchField(FormBuilderInterface $builder, array $options)
    {
        $builder->add('q', 'Symfony\Component\Form\Extension\Core\Type\SearchType', [
            'label' => $this->__('Search for') . ':',
            'required' => false,
            'attr' => [
                'maxlength' => 255
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pggoinstititutionenmodule_picturefinder';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'objectType' => 'institution',
                'editorName' => 'ckeditor'
            ])
            ->setRequired(['objectType', 'editorName'])
            ->setAllowedTypes([
                'objectType' => 'string',
                'editorName' => 'string'
            ])
            ->setAllowedValues([
                'editorName' => ['tinymce', 'ckeditor']
            ])
        ;
    }
}
