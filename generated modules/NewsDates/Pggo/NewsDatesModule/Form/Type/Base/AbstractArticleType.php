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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Pggo\NewsDatesModule\Entity\Factory\NewsDatesFactory;
use Pggo\NewsDatesModule\Helper\FeatureActivationHelper;
use Pggo\NewsDatesModule\Helper\ListEntriesHelper;

/**
 * Article editing form type base class.
 */
abstract class AbstractArticleType extends AbstractType
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
     * ArticleType constructor.
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
        if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, 'article')) {
            $this->addCategoriesField($builder, $options);
        }
        $this->addIncomingRelationshipFields($builder, $options);
        $this->addAdditionalNotificationRemarksField($builder, $options);
        $this->addModerationFields($builder, $options);
        $this->addReturnControlField($builder, $options);
        $this->addSubmitButtons($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $entity = $event->getData();
            foreach (['image'] as $uploadFieldName) {
                $entity[$uploadFieldName] = [
                    $uploadFieldName => $entity[$uploadFieldName] instanceof File ? $entity[$uploadFieldName]->getPathname() : null
                ];
            }
        });
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $entity = $event->getData();
            foreach (['image'] as $uploadFieldName) {
                if (is_array($entity[$uploadFieldName])) {
                    $entity[$uploadFieldName] = $entity[$uploadFieldName][$uploadFieldName];
                }
            }
        });
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
                'title' => $this->__('Enter the title of the article')
            ],'required' => true,
        ]);
        
        $builder->add('teaser', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Teaser') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 10000,
                'class' => '',
                'title' => $this->__('Enter the teaser of the article')
            ],'required' => true
        ]);
        
        $builder->add('bodyText', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Body text') . ':',
            'empty_data' => '',
            'attr' => [
                'maxlength' => 20000,
                'class' => '',
                'title' => $this->__('Enter the body text of the article')
            ],'required' => false
        ]);
        
        $builder->add('image', 'Pggo\NewsDatesModule\Form\Type\Field\UploadType', [
            'label' => $this->__('Image') . ':',
            'attr' => [
                'class' => ' validate-upload',
                'title' => $this->__('Enter the image of the article')
            ],'required' => false,
            'entity' => $options['entity'],
            'allowed_extensions' => 'gif, jpeg, jpg, png',
            'allowed_size' => 0
        ]);
        
        $builder->add('copyright', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
            'label' => $this->__('Copyright') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('copyright of the image')
            ],
            'help' => $this->__('copyright of the image'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 255,
                'class' => '',
                'title' => $this->__('Enter the copyright of the article')
            ],'required' => false,
        ]);
        
        $builder->add('notes', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'label' => $this->__('Notes') . ':',
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $this->__('notes are not visible for the Users. It is only for the admins.')
            ],
            'help' => $this->__('notes are not visible for the Users. It is only for the admins.'),
            'empty_data' => '',
            'attr' => [
                'maxlength' => 2000,
                'class' => '',
                'title' => $this->__('Enter the notes of the article')
            ],'required' => false
        ]);
        
        $builder->add('displayOnIndex', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
            'label' => $this->__('Display on index') . ':',
            'attr' => [
                'class' => '',
                'title' => $this->__('display on index ?')
            ],'required' => false,
        ]);
        
        $builder->add('startDate', 'Pggo\NewsDatesModule\Form\Type\Field\DateTimeType', [
            'label' => $this->__('Start date') . ':',
            'empty_data' => '',
            'attr' => [
                'class' => ' validate-daterange-article',
                'title' => $this->__('Enter the start date of the article')
            ],'empty_data' => date('Y-m-d H:i'),
            'required' => false,
            'widget' => 'single_text'
        ]);
        
        $builder->add('endDatetime', 'Pggo\NewsDatesModule\Form\Type\Field\DateTimeType', [
            'label' => $this->__('End datetime') . ':',
            'empty_data' => '2099-12-31 00:00:00',
            'attr' => [
                'class' => ' validate-daterange-article',
                'title' => $this->__('Enter the end datetime of the article')
            ],'empty_data' => '2099-12-31 00:00:00',
            'required' => false,
            'widget' => 'single_text'
        ]);
        
        $builder->add('views', 'Symfony\Component\Form\Extension\Core\Type\IntegerType', [
            'label' => $this->__('Views') . ':',
            'empty_data' => '0',
            'attr' => [
                'maxlength' => 11,
                'class' => ' validate-digits',
                'title' => $this->__('Enter the views of the article.') . ' ' . $this->__('Only digits are allowed.')
            ],'required' => true,
            'scale' => 0
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
            'entity' => 'ArticleEntity',
            'entityCategoryClass' => 'Pggo\NewsDatesModule\Entity\ArticleCategoryEntity'
        ]);
    }

    /**
     * Adds fields for incoming relationships.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addIncomingRelationshipFields(FormBuilderInterface $builder, array $options)
    {
        $queryBuilder = function(EntityRepository $er) {
            // select without joins
            return $er->getListQueryBuilder('', '', false);
        };
        $builder->add('event', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
            'class' => 'PggoNewsDatesModule:EventEntity',
            'choice_label' => 'getTitleFromDisplayPattern',
            'multiple' => false,
            'expanded' => false,
            'query_builder' => $queryBuilder,
            'label' => $this->__('Event'),
            'attr' => [
                'title' => $this->__('Choose the event')
            ]
        ]);
    }

    /**
     * Adds a field for additional notification remarks.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addAdditionalNotificationRemarksField(FormBuilderInterface $builder, array $options)
    {
        $helpText = '';
        if ($options['isModerator']) {
            $helpText = $this->__('These remarks (like a reason for deny) are not stored, but added to any notification emails send to the creator.');
        } elseif ($options['isCreator']) {
            $helpText = $this->__('These remarks (like questions about conformance) are not stored, but added to any notification emails send to our moderators.');
        }
    
        $builder->add('additionalNotificationRemarks', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', [
            'mapped' => false,
            'label' => $this->__('Additional remarks'),
            'label_attr' => [
                'class' => 'tooltips',
                'title' => $helpText
            ],
            'attr' => [
                'title' => $options['mode'] == 'create' ? $this->__('Enter any additions about your content') : $this->__('Enter any additions about your changes')
            ],
            'required' => false,
            'help' => $helpText
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
        return 'pggonewsdatesmodule_article';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                // define class for underlying data (required for embedding forms)
                'data_class' => 'Pggo\NewsDatesModule\Entity\ArticleEntity',
                'empty_data' => function (FormInterface $form) {
                    return $this->entityFactory->createArticle();
                },
                'error_mapping' => [
                    'image' => 'image.image',
                    'isStartDateBeforeEndDatetime' => 'startDate',
                ],
                'mode' => 'create',
                'isModerator' => false,
                'isCreator' => false,
                'actions' => [],
                'hasModeratePermission' => false,
                'filterByOwnership' => true,
                'inlineUsage' => false
            ])
            ->setRequired(['entity', 'mode', 'actions'])
            ->setAllowedTypes([
                'mode' => 'string',
                'isModerator' => 'bool',
                'isCreator' => 'bool',
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
