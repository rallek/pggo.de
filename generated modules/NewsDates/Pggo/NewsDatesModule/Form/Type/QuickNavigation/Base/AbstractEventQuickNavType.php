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

namespace Pggo\NewsDatesModule\Form\Type\QuickNavigation\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Pggo\NewsDatesModule\Helper\FeatureActivationHelper;
use Pggo\NewsDatesModule\Helper\ListEntriesHelper;

/**
 * Event quick navigation form type base class.
 */
abstract class AbstractEventQuickNavType extends AbstractType
{
    use TranslatorTrait;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * @var FeatureActivationHelper
     */
    protected $featureActivationHelper;

    /**
     * EventQuickNavType constructor.
     *
     * @param TranslatorInterface $translator   Translator service instance
    * @param RequestStack        $requestStack RequestStack service instance
     * @param ListEntriesHelper   $listHelper   ListEntriesHelper service instance
     * @param FeatureActivationHelper $featureActivationHelper FeatureActivationHelper service instance
     */
    public function __construct(TranslatorInterface $translator, RequestStack $requestStack, ListEntriesHelper $listHelper, FeatureActivationHelper $featureActivationHelper)
    {
        $this->setTranslator($translator);
        $this->request = $requestStack->getCurrentRequest();
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
        $builder
            ->setMethod('GET')
            ->add('all', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
            ->add('own', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
            ->add('tpl', 'Symfony\Component\Form\Extension\Core\Type\HiddenType')
        ;

        if ($this->featureActivationHelper->isEnabled(FeatureActivationHelper::CATEGORIES, 'event')) {
            $this->addCategoriesField($builder, $options);
        }
        $this->addIncomingRelationshipFields($builder, $options);
        $this->addListFields($builder, $options);
        $this->addSearchField($builder, $options);
        $this->addSortingFields($builder, $options);
        $this->addAmountField($builder, $options);
        $builder->add('updateview', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
            'label' => $this->__('OK'),
            'attr' => [
                'class' => 'btn btn-default btn-sm'
            ]
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
        $objectType = 'event';
    
        $builder->add('categories', 'Zikula\CategoriesModule\Form\Type\CategoriesType', [
            'label' => $this->__('Categories'),
            'empty_data' => [],
            'attr' => [
                'class' => 'input-sm category-selector',
                'title' => $this->__('This is an optional filter.')
            ],
            'help' => $this->__('This is an optional filter.'),
            'required' => false,
            'multiple' => true,
            'module' => 'PggoNewsDatesModule',
            'entity' => ucfirst($objectType) . 'Entity',
            'entityCategoryClass' => 'Pggo\NewsDatesModule\Entity\\' . ucfirst($objectType) . 'CategoryEntity'
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
        $mainSearchTerm = '';
        if ($this->request->query->has('q')) {
            // remove current search argument from request to avoid filtering related items
            $mainSearchTerm = $this->request->query->get('q');
            $this->request->query->remove('q');
        }
    
        $builder->add('article', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
            'class' => 'PggoNewsDatesModule:ArticleEntity',
            'choice_label' => 'getTitleFromDisplayPattern',
            'placeholder' => $this->__('All'),
            'required' => false,
            'label' => $this->__('Article'),
            'attr' => [
                'class' => 'input-sm'
            ]
        ]);
    
        if ($mainSearchTerm != '') {
            // readd current search argument
            $this->request->query->set('q', $mainSearchTerm);
        }
    }

    /**
     * Adds list fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addListFields(FormBuilderInterface $builder, array $options)
    {
        $listEntries = $this->listHelper->getEntries('event', 'workflowState');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('workflowState', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
            'label' => $this->__('State'),
            'attr' => [
                'class' => 'input-sm'
            ],
            'required' => false,
            'placeholder' => $this->__('All'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
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
            'label' => $this->__('Search'),
            'attr' => [
                'maxlength' => 255,
                'class' => 'input-sm'
            ],
            'required' => false
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
                'label' => $this->__('Sort by'),
                'attr' => [
                    'class' => 'input-sm'
                ],
                'choices' =>             [
                    $this->__('Title') => 'title',
                    $this->__('Start date') => 'startDate',
                    $this->__('Location') => 'location',
                    $this->__('Event url') => 'eventUrl',
                    $this->__('Creation date') => 'createdDate',
                    $this->__('Creator') => 'createdBy',
                    $this->__('Update date') => 'updatedDate',
                    $this->__('Updater') => 'updatedBy'
                ],
                'choices_as_values' => true,
                'required' => true,
                'expanded' => false
            ])
            ->add('sortdir', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', [
                'label' => $this->__('Sort direction'),
                'empty_data' => 'asc',
                'attr' => [
                    'class' => 'input-sm'
                ],
                'choices' => [
                    $this->__('Ascending') => 'asc',
                    $this->__('Descending') => 'desc'
                ],
                'choices_as_values' => true,
                'required' => true,
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
            'label' => $this->__('Page size'),
            'empty_data' => 20,
            'attr' => [
                'class' => 'input-sm text-right'
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
            'required' => false,
            'expanded' => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pggonewsdatesmodule_eventquicknav';
    }
}
