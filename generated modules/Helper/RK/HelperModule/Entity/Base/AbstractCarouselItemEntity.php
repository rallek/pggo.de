<?php
/**
 * Helper.
 *
 * @copyright Ralf Koester (RK)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://k62.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace RK\HelperModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use RK\HelperModule\Traits\EntityWorkflowTrait;
use RK\HelperModule\Traits\StandardFieldsTrait;

use RuntimeException;
use ServiceUtil;
use Zikula\Core\Doctrine\EntityAccess;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for carousel item entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 *
 * @abstract
 */
abstract class AbstractCarouselItemEntity extends EntityAccess
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
    protected $_objectType = 'carouselItem';
    
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
     * the item name will not be shown. it is just for notification in the backend
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $itemName
     */
    protected $itemName = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @var string $title
     */
    protected $title = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @var string $subtitle
     */
    protected $subtitle = '';
    
    /**
     * Please be carfull with the link. The link is not validated!
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @var string $link
     */
    protected $link = '';
    
    /**
     * Item image meta data array.
     *
     * @ORM\Column(type="array")
     * @Assert\Type(type="array")
     * @var array $itemImageMeta
     */
    protected $itemImageMeta = [];
    
    /**
     * The ration of the image must be 3:1. Please format before uploading. The image will be shrinked to 1800px width. Your image should be not much less than this.
     * @ORM\Column(length=255, nullable=true)
     * @Assert\Length(min="0", max="255")
     * @Assert\File(
        mimeTypes = {"image/*"}
     * )
     * @Assert\Image(
        minRatio = 2.9,
        maxRatio = 3.1
     * )
     * @var string $itemImage
     */
    protected $itemImage = null;
    
    /**
     * Full item image path as url.
     *
     * @Assert\Type(type="string")
     * @var string $itemImageUrl
     */
    protected $itemImageUrl = '';
    
    /**
     * select white first. If this do not fit try a grey or black.
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Regex(pattern="/\s/", match=false, message="This value must not contain space chars.")
     * @Assert\Length(min="0", max="255")
     * @Assert\Regex(pattern="/^#?(([a-fA-F0-9]{3}){1,2})$/", message="This value must be a valid html colour code [#123 or #123456].")
     * @var string $titleColor
     */
    protected $titleColor = '#ffffff';
    
    /**
     * if you do not enter a date the date of today will be used
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     * @var date $itemStartDate
     */
    protected $itemStartDate;
    
    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\Date()
     * @Assert\GreaterThan("now")
     * @var date $intemEndDate
     */
    protected $intemEndDate;
    
    /**
     * Here we can filter one single item in the block advanced filtering to reuse an image. Be shure the itemEndDate is valid and not in the past.
     * @ORM\Column(length=255, unique=true)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @var string $singleItemIdentifier
     */
    protected $singleItemIdentifier = '';
    
    /**
     * if this is selected the link will open another window or tab
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $linkExternal
     */
    protected $linkExternal = false;
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Regex(pattern="/\s/", match=false, message="This value must not contain space chars.")
     * @Assert\Length(min="0", max="255")
     * @Assert\Locale()
     * @var string $itemLocale
     */
    protected $itemLocale = '';
    
    
    /**
     * Bidirectional - Many carouselItems [carousel items] are linked by one carousel [carousel] (OWNING SIDE).
     *
     * @ORM\ManyToOne(targetEntity="RK\HelperModule\Entity\CarouselEntity", inversedBy="carouselItems")
     * @ORM\JoinTable(name="rk_helper_carousel")
     * @Assert\Type(type="RK\HelperModule\Entity\CarouselEntity")
     * @var \RK\HelperModule\Entity\CarouselEntity $carousel
     */
    protected $carousel;
    
    
    /**
     * CarouselItemEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     *
     * @param TODO
     */
    public function __construct()
    {
        $this->itemStartDate = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $this->intemEndDate = \DateTime::createFromFormat('Y-m-d', '2099-12-31');
        $this->initWorkflow();
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
     * Returns the item name.
     *
     * @return string
     */
    public function getItemName()
    {
        return $this->itemName;
    }
    
    /**
     * Sets the item name.
     *
     * @param string $itemName
     *
     * @return void
     */
    public function setItemName($itemName)
    {
        $this->itemName = isset($itemName) ? $itemName : '';
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
     * Returns the subtitle.
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }
    
    /**
     * Sets the subtitle.
     *
     * @param string $subtitle
     *
     * @return void
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = isset($subtitle) ? $subtitle : '';
    }
    
    /**
     * Returns the link.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
    
    /**
     * Sets the link.
     *
     * @param string $link
     *
     * @return void
     */
    public function setLink($link)
    {
        $this->link = isset($link) ? $link : '';
    }
    
    /**
     * Returns the item image.
     *
     * @return string
     */
    public function getItemImage()
    {
        return $this->itemImage;
    }
    
    /**
     * Sets the item image.
     *
     * @param string $itemImage
     *
     * @return void
     */
    public function setItemImage($itemImage)
    {
        $this->itemImage = $itemImage;
    }
    
    /**
     * Returns the item image url.
     *
     * @return string
     */
    public function getItemImageUrl()
    {
        return $this->itemImageUrl;
    }
    
    /**
     * Sets the item image url.
     *
     * @param string $itemImageUrl
     *
     * @return void
     */
    public function setItemImageUrl($itemImageUrl)
    {
        $this->itemImageUrl = $itemImageUrl;
    }
    
    /**
     * Returns the item image meta.
     *
     * @return array
     */
    public function getItemImageMeta()
    {
        return $this->itemImageMeta;
    }
    
    /**
     * Sets the item image meta.
     *
     * @param array $itemImageMeta
     *
     * @return void
     */
    public function setItemImageMeta($itemImageMeta = [])
    {
        $this->itemImageMeta = $itemImageMeta;
    }
    
    /**
     * Returns the title color.
     *
     * @return string
     */
    public function getTitleColor()
    {
        return $this->titleColor;
    }
    
    /**
     * Sets the title color.
     *
     * @param string $titleColor
     *
     * @return void
     */
    public function setTitleColor($titleColor)
    {
        $this->titleColor = isset($titleColor) ? $titleColor : '';
    }
    
    /**
     * Returns the item start date.
     *
     * @return date
     */
    public function getItemStartDate()
    {
        return $this->itemStartDate;
    }
    
    /**
     * Sets the item start date.
     *
     * @param date $itemStartDate
     *
     * @return void
     */
    public function setItemStartDate($itemStartDate)
    {
        if (is_object($itemStartDate) && $itemStartDate instanceOf \DateTime) {
            $this->itemStartDate = $itemStartDate;
        } elseif (null === $itemStartDate || empty($itemStartDate)) {
            $this->itemStartDate = null;
        } else {
            $this->itemStartDate = new \DateTime($itemStartDate);
        }
    }
    
    /**
     * Returns the intem end date.
     *
     * @return date
     */
    public function getIntemEndDate()
    {
        return $this->intemEndDate;
    }
    
    /**
     * Sets the intem end date.
     *
     * @param date $intemEndDate
     *
     * @return void
     */
    public function setIntemEndDate($intemEndDate)
    {
        if (is_object($intemEndDate) && $intemEndDate instanceOf \DateTime) {
            $this->intemEndDate = $intemEndDate;
        } else {
            $this->intemEndDate = new \DateTime($intemEndDate);
        }
    }
    
    /**
     * Returns the single item identifier.
     *
     * @return string
     */
    public function getSingleItemIdentifier()
    {
        return $this->singleItemIdentifier;
    }
    
    /**
     * Sets the single item identifier.
     *
     * @param string $singleItemIdentifier
     *
     * @return void
     */
    public function setSingleItemIdentifier($singleItemIdentifier)
    {
        $this->singleItemIdentifier = isset($singleItemIdentifier) ? $singleItemIdentifier : '';
    }
    
    /**
     * Returns the link external.
     *
     * @return boolean
     */
    public function getLinkExternal()
    {
        return $this->linkExternal;
    }
    
    /**
     * Sets the link external.
     *
     * @param boolean $linkExternal
     *
     * @return void
     */
    public function setLinkExternal($linkExternal)
    {
        if ($linkExternal !== $this->linkExternal) {
            $this->linkExternal = (bool)$linkExternal;
        }
    }
    
    /**
     * Returns the item locale.
     *
     * @return string
     */
    public function getItemLocale()
    {
        return $this->itemLocale;
    }
    
    /**
     * Sets the item locale.
     *
     * @param string $itemLocale
     *
     * @return void
     */
    public function setItemLocale($itemLocale)
    {
        $this->itemLocale = isset($itemLocale) ? $itemLocale : '';
    }
    
    
    /**
     * Returns the carousel.
     *
     * @return \RK\HelperModule\Entity\CarouselEntity
     */
    public function getCarousel()
    {
        return $this->carousel;
    }
    
    /**
     * Sets the carousel.
     *
     * @param \RK\HelperModule\Entity\CarouselEntity $carousel
     *
     * @return void
     */
    public function setCarousel($carousel = null)
    {
        $this->carousel = $carousel;
    }
    
    
    
    /**
     * Returns the formatted title conforming to the display pattern
     * specified for this entity.
     *
     * @return string The display title
     */
    public function getTitleFromDisplayPattern()
    {
        $listHelper = ServiceUtil::get('rk_helper_module.listentries_helper');
    
        $formattedTitle = ''
                . $this->getTitle();
    
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
        $helper = $container->get('rk_helper_module.listentries_helper');
        $listEntries = $helper->getWorkflowStateEntriesForCarouselItem();
    
        $allowedValues = ['initial'];
        foreach ($listEntries as $entry) {
            $allowedValues[] = $entry['value'];
        }
    
        return $allowedValues;
    }
    
    /**
     * Checks whether the itemStartDate value is earlier than the intemEndDate value.
     * This method is used for validation.
     *
     * @Assert\IsTrue(message="The start date must be before the end date.")
     *
     * @return boolean True if data is valid else false
     */
    public function isItemStartDateBeforeIntemEndDate()
    {
        return ($this['itemStartDate'] < $this['intemEndDate']);
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
        return 'rkhelpermodule.ui_hooks.carouselitems';
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
        return 'Carousel item ' . $this->createCompositeIdentifier() . ': ' . $this->getTitleFromDisplayPattern();
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
    
        // reset upload fields
        $this->setItemImage(null);
        $this->setItemImageMeta([]);
        $this->setItemImageUrl('');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    }
}
