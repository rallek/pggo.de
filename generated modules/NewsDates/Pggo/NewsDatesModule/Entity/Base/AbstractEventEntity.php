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

namespace Pggo\NewsDatesModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Pggo\NewsDatesModule\Traits\EntityWorkflowTrait;
use Pggo\NewsDatesModule\Traits\StandardFieldsTrait;

use RuntimeException;
use ServiceUtil;
use Zikula\Core\Doctrine\EntityAccess;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for event entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 *
 * @abstract
 */
abstract class AbstractEventEntity extends EntityAccess
{
    /**
     * Hook entity workflow field and behaviour.
     */
    use EntityWorkflowTrait;

    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'event';
    
    /**
     * @Assert\Type(type="bool")
     * @var boolean Option to bypass validation if needed
     */
    protected $_bypassValidation = false;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @Assert\Choice(callback="getWorkflowStateAllowedValues", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $title
     */
    protected $title = '';
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     * @Assert\DateTime()
     * @var DateTime $startDate
     */
    protected $startDate;
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @var string $duration
     */
    protected $duration = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @var string $location
     */
    protected $location = '';
    
    /**
     * @ORM\Column(type="text", length=2000)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="2000")
     * @var text $description
     */
    protected $description = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @Assert\Url(checkDNS=false)
     * @var string $eventUrl
     */
    protected $eventUrl = '';
    
    
    /**
     * @Gedmo\Slug(fields={"title"}, updatable=true, unique=false, separator="-", style="lower")
     * @ORM\Column(type="string", length=255, unique=false)
     * @Assert\Length(min="1", max="255")
     * @var string $slug
     */
    protected $slug;
    
    /**
     * @ORM\OneToMany(targetEntity="\Pggo\NewsDatesModule\Entity\EventCategoryEntity", 
     *                mappedBy="entity", cascade={"all"}, 
     *                orphanRemoval=true)
     * @var \Pggo\NewsDatesModule\Entity\EventCategoryEntity
     */
    protected $categories = null;
    
    
    /**
     * EventEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     *
     * @param TODO
     */
    public function __construct()
    {
        $this->startDate = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $this->initWorkflow();
        $this->categories = new ArrayCollection();
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        $this->_objectType = $_objectType;
    }
    
    /**
     * Returns the _bypass validation.
     *
     * @return boolean
     */
    public function get_bypassValidation()
    {
        return $this->_bypassValidation;
    }
    
    /**
     * Sets the _bypass validation.
     *
     * @param boolean $_bypassValidation
     *
     * @return void
     */
    public function set_bypassValidation($_bypassValidation)
    {
        $this->_bypassValidation = $_bypassValidation;
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = intval($id);
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        $this->workflowState = isset($workflowState) ? $workflowState : '';
    }
    
    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the title.
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = isset($title) ? $title : '';
    }
    
    /**
     * Returns the start date.
     *
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }
    
    /**
     * Sets the start date.
     *
     * @param DateTime $startDate
     *
     * @return void
     */
    public function setStartDate($startDate)
    {
        if (is_object($startDate) && $startDate instanceOf \DateTime) {
            $this->startDate = $startDate;
        } else {
            $this->startDate = new \DateTime($startDate);
        }
    }
    
    /**
     * Returns the duration.
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }
    
    /**
     * Sets the duration.
     *
     * @param string $duration
     *
     * @return void
     */
    public function setDuration($duration)
    {
        $this->duration = isset($duration) ? $duration : '';
    }
    
    /**
     * Returns the location.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }
    
    /**
     * Sets the location.
     *
     * @param string $location
     *
     * @return void
     */
    public function setLocation($location)
    {
        $this->location = isset($location) ? $location : '';
    }
    
    /**
     * Returns the description.
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets the description.
     *
     * @param text $description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = isset($description) ? $description : '';
    }
    
    /**
     * Returns the event url.
     *
     * @return string
     */
    public function getEventUrl()
    {
        return $this->eventUrl;
    }
    
    /**
     * Sets the event url.
     *
     * @param string $eventUrl
     *
     * @return void
     */
    public function setEventUrl($eventUrl)
    {
        $this->eventUrl = isset($eventUrl) ? $eventUrl : '';
    }
    
    /**
     * Returns the slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * Sets the slug.
     *
     * @param string $slug
     *
     * @return void
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    
    /**
     * Returns the categories.
     *
     * @return ArrayCollection[]
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    
    /**
     * Sets the categories.
     *
     * @param ArrayCollection $categories
     *
     * @return void
     */
    public function setCategories(ArrayCollection $categories)
    {
        foreach ($this->categories as $category) {
            if (false === $key = $this->collectionContains($categories, $category)) {
                $this->categories->removeElement($category);
            } else {
                $categories->remove($key);
            }
        }
        foreach ($categories as $category) {
            $this->categories->add($category);
        }
    }
    
    /**
     * Checks if a collection contains an element based only on two criteria (categoryRegistryId, category).
     *
     * @param ArrayCollection $collection
     * @param \Pggo\NewsDatesModule\Entity\EventCategoryEntity $element
     *
     * @return bool|int
     */
    private function collectionContains(ArrayCollection $collection, \Pggo\NewsDatesModule\Entity\EventCategoryEntity $element)
    {
        foreach ($collection as $key => $category) {
            /** @var \Pggo\NewsDatesModule\Entity\EventCategoryEntity $category */
            if ($category->getCategoryRegistryId() == $element->getCategoryRegistryId()
                && $category->getCategory() == $element->getCategory()
            ) {
                return $key;
            }
        }
    
        return false;
    }
    
    
    
    /**
     * Returns the formatted title conforming to the display pattern
     * specified for this entity.
     *
     * @return string The display title
     */
    public function getTitleFromDisplayPattern()
    {
        $listHelper = ServiceUtil::get('pggo_newsdates_module.listentries_helper');
    
        $formattedTitle = ''
                . $this->getTitle()
                . ' '
                . \DateUtil::formatDatetime($this->getStartDate(), 'datetimebrief')
                . ' '
                . $this->getLocation();
    
        return $formattedTitle;
    }
    
    
    /**
     * Returns a list of possible choices for the workflowState list field.
     * This method is used for validation.
     *
     * @return array List of allowed choices
     */
    public static function getWorkflowStateAllowedValues()
    {
        $container = ServiceUtil::get('service_container');
        $helper = $container->get('pggo_newsdates_module.listentries_helper');
        $listEntries = $helper->getWorkflowStateEntriesForEvent();
    
        $allowedValues = ['initial'];
        foreach ($listEntries as $entry) {
            $allowedValues[] = $entry['value'];
        }
    
        return $allowedValues;
    }
    
    
    /**
     * Start validation and raise exception if invalid data is found.
     *
     * @return boolean Whether everything is valid or not
     */
    public function validate()
    {
        if (true === $this->_bypassValidation) {
            return true;
        }
    
        $validator = ServiceUtil::get('validator');
        $errors = $validator->validate($this);
    
        if (count($errors) > 0) {
            $flashBag = ServiceUtil::get('session')->getFlashBag();
            foreach ($errors as $error) {
                $flashBag->add('error', $error->getMessage());
            }
    
            return false;
        }
    
        return true;
    }
    
    /**
     * Return entity data in JSON format.
     *
     * @return string JSON-encoded data
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array The resulting arguments list
     */
    public function createUrlArgs()
    {
        $args = [];
    
        $args['id'] = $this['id'];
    
        if (property_exists($this, 'slug')) {
            $args['slug'] = $this['slug'];
        }
    
        return $args;
    }
    
    /**
     * Create concatenated identifier string (for composite keys).
     *
     * @return String concatenated identifiers
     */
    public function createCompositeIdentifier()
    {
        $itemId = $this['id'];
    
        return $itemId;
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'pggonewsdatesmodule.ui_hooks.events';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects The objects are added to this array. Default: []
     * 
     * @return array of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = []) 
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Event ' . $this->createCompositeIdentifier() . ': ' . $this->getTitleFromDisplayPattern();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!($this->id)) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifiers
        $this->setId(0);
    
        // reset workflow
        $this->resetWorkflow();
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    
        // clone categories
        $categories = $this->categories;
        $this->categories = new ArrayCollection();
        foreach ($categories as $c) {
            $newCat = clone $c;
            $this->categories->add($newCat);
            $newCat->setEntity($this);
        }
    }
}
