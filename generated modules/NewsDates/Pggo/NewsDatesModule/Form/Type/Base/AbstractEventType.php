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

namespace Pggo\NewsDatesModule\Form\Type\Base;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Pggo\NewsDatesModule\Entity\Factory\NewsDatesFactory;
use Pggo\NewsDatesModule\Helper\FeatureActivationHelper;
use Pggo\NewsDatesModule\Helper\ListEntriesHelper;

/**
 * Event editing form type base class.
 */
abstract class AbstractEventType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var NewsDatesFactory
     */
    protected $entityFactory;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * EventType constructor.
     *
     * @param TranslatorInterface $translator    Translator service instance
     * @param NewsDatesFactory        $entityFactory Entity factory service instance
     * @param ListEntriesHelper   $listHelper    ListEntriesHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(TranslatorInterface $translator, NewsDatesFactory $entityFactory, ListEntriesHelper $listHelper, FeatureActivationHelper $featureActivationHelper)
    {
        $this->setTranslator($translator);
        $this->entityFactory = $entityFactory;
        $this->listHelper = $listHelper;
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
        $this->addEntityFields($builder, $options);
        if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, 'event')) {
            $this->addCategoriesField($builder, $options);
        }
        $this->addOutgoingRelationshipFields($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addReturnControlField($builder, $options);
        $this->addSubmitButtons($builder, $options);
    }

    /**
     * Adds basic entity fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Title') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the title of the event')
            ],'required' => true,
        ]);
        
        $builder->add('startDate', 'Pggo\NewsDatesModule\Form\Type\Field\DateTimeType', [
            'label' => $this->__('Start date') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => '',
                'title' => $this->__('Enter the start date of the event')
            ],'empty_data' => date('Y-m-d H:i'),
            'required' => false,
            'widget' => 'single_text'
        ]);
        
        $builder->add('duration', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Duration') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the duration of the event')
            ],'required' => false,
        ]);
        
        $builder->add('location', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Location') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the location of the event')
            ],'required' => false,
        ]);
        
        $builder->add('description', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Description') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => '',
                'title' => $this->__('Enter the description of the event')
            ],'required' => false
        ]);
        
        $builder->add('eventUrl', 'Symfony\Component\Form\Extension\Core\Type\UrlType', [
            'label' => $this->__('Event url') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => ' validate-url',
                'title' => $this->__('Enter the event url of the event')
            ],'required' => false
        ]);
        
    }

    /**
     * Adds a categories field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addCategoriesField(FormBuilderInterface $builder, array $options)
    {
        $builder->add('categories', 'Zikula\CategoriesModule\Form\Type\CategoriesType', [
            'label' => $this->__('Categories') . ':',
            'empty_data' => [],
            'attr' => [
                'class' => 'category-selector'
            ],
            'required' => false,
            'multiple' => true,
            'module' => 'PggoNewsDatesModule',
            'entity' => 'EventEntity',
            'entityCategoryClass' => 'Pggo\NewsDatesModule\Entity\EventCategoryEntity'
        ]);
    }

    /**
     * Adds fields for outgoing relationships.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addOutgoingRelationshipFields(FormBuilderInterface $builder, array $options)
    {
        $queryBuilder = function(EntityRepository $er) {
            // select without joins
            return $er->getListQueryBuilder('', '', false);
        };
        $builder->add('articles', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
            'class' => 'PggoNewsDatesModule:ArticleEntity',
            'choice_label' => 'getTitleFromDisplayPattern',
            'multiple' => true,
            'expanded' => false,
            'query_builder' => $queryBuilder,
            'label' => $this->__('Articles'),
            'attr' => [
                'title' => $this->__('Choose the articles')
            ]
        ]);
    }

    /**
     * Adds special fields for moderators.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addModerationFields(FormBuilderInterface $builder, array $options)
    {
        if (!$options['hasModeratePermission']) {
            return;
        }
    
        $builder->add('moderationSpecificCreator', 'Pggo\NewsDatesModule\Form\Type\Field\UserType', [
            'mapped' => false,
            'label' => $this->__('Creator') . ':',
            'attr' => [
                'maxlength' => 11,
                'class' => ' validate-digits',
                'title' => $this->__('Here you can choose a user which will be set as creator')
            ],
            'empty_data' => 0,
            'required' => false,
            'help' => $this->__('Here you can choose a user which will be set as creator')
        ]);
        $builder->add('moderationSpecificCreationDate', 'Pggo\NewsDatesModule\Form\Type\Field\DateTimeType', [
            'mapped' => false,
            'label' => $this->__('Creation date') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('Here you can choose a custom creation date')
            ],
            'empty_data' => '',
            'required' => false,
            'widget' => 'single_text',
            'help' => $this->__('Here you can choose a custom creation date')
        ]);
    }

    /**
     * Adds the return control field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addReturnControlField(FormBuilderInterface $builder, array $options)
    {
        if ($options['mode'] != 'create') {
            return;
        }
        $builder->add('repeatCreation', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
            'mapped' => false,
            'label' => $this->__('Create another item after save'),
            'required' => false
        ]);
    }

    /**
     * Adds submit buttons.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSubmitButtons(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['actions'] as $action) {
            $builder->add($action['id'], 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
                'label' => $this->__(/** @Ignore */$action['title']),
                'icon' => ($action['id'] == 'delete' ? 'fa-trash-o' : ''),
                'attr' => [
                    'class' => $action['buttonClass'],
                    'title' => $this->__(/** @Ignore */$action['description'])
                ]
            ]);
        }
        $builder->add('reset', 'Symfony\Component\Form\Extension\Core\Type\ResetType', [
            'label' => $this->__('Reset'),
            'icon' => 'fa-refresh',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
        $builder->add('cancel', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
            'label' => $this->__('Cancel'),
            'icon' => 'fa-times',
            'attr' => [
                'class' => 'btn btn-default',
                'formnovalidate' => 'formnovalidate'
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pggonewsdatesmodule_event';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data (required for embedding forms)
                'data_class' => 'Pggo\NewsDatesModule\Entity\EventEntity',
                'empty_data' => function (FormInterface $form) {
                    return $this->entityFactory->createEvent();
                },
                'error_mapping' => [
                ],
                'mode' => 'create',
                'actions' => [],
                'hasModeratePermission' => false,
                'filterByOwnership' => true,
                'inlineUsage' => false
            ])
            ->setRequired(['mode', 'actions'])
            ->setAllowedTypes([
                'mode' => 'string',
                'actions' => 'array',
                'hasModeratePermission' => 'bool',
                'filterByOwnership' => 'bool',
                'inlineUsage' => 'bool'
            ])
            ->setAllowedValues([
                'mode' => ['create', 'edit']
            ])
        ;
    }
}
