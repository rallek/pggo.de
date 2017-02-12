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

namespace Pggo\InstititutionenModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Pggo\InstititutionenModule\Traits\EntityWorkflowTrait;
use Pggo\InstititutionenModule\Traits\StandardFieldsTrait;

use RuntimeException;
use ServiceUtil;
use Zikula\Core\Doctrine\EntityAccess;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for institution entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 *
 * @abstract
 */
abstract class AbstractInstitutionEntity extends EntityAccess
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
    protected $_objectType = 'institution';
    
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
     * @var string $name
     */
    protected $name = '';
    
    /**
     * Image meta data array.
     *
     * @ORM\Column(type="array")
     * @Assert\Type(type="array")
     * @var array $imageMeta
     */
    protected $imageMeta = [];
    
    /**
     * @ORM\Column(length=255, nullable=true)
     * @Assert\Length(min="0", max="255")
     * @Assert\File(
        mimeTypes = {"image/*"}
     * )
     * @Assert\Image(
     * )
     * @var string $image
     */
    protected $image = null;
    
    /**
     * Full image path as url.
     *
     * @Assert\Type(type="string")
     * @var string $imageUrl
     */
    protected $imageUrl = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @var string $copyright
     */
    protected $copyright = '';
    
    /**
     * @ORM\Column(type="text", length=8000)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="8000")
     * @var text $description
     */
    protected $description = '';
    
    
    /**
     * @ORM\OneToMany(targetEntity="\Pggo\InstititutionenModule\Entity\InstitutionCategoryEntity", 
     *                mappedBy="entity", cascade={"all"}, 
     *                orphanRemoval=true)
     * @var \Pggo\InstititutionenModule\Entity\InstitutionCategoryEntity
     */
    protected $categories = null;
    
    /**
     * Bidirectional - One institution [institution] has many pictures [pictures] (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="Pggo\InstititutionenModule\Entity\PictureEntity", mappedBy="institution")
     * @ORM\JoinTable(name="pggo_instit_institutionpictures")
     * @var \Pggo\InstititutionenModule\Entity\PictureEntity[] $pictures
     */
    protected $pictures = null;
    
    
    /**
     * InstitutionEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     *
     * @param TODO
     */
    public function __construct()
    {
        $this->initWorkflow();
        $this->pictures = new ArrayCollection();
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
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = isset($name) ? $name : '';
    }
    
    /**
     * Returns the image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Sets the image.
     *
     * @param string $image
     *
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
    
    /**
     * Returns the image url.
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }
    
    /**
     * Sets the image url.
     *
     * @param string $imageUrl
     *
     * @return void
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }
    
    /**
     * Returns the image meta.
     *
     * @return array
     */
    public function getImageMeta()
    {
        return $this->imageMeta;
    }
    
    /**
     * Sets the image meta.
     *
     * @param array $imageMeta
     *
     * @return void
     */
    public function setImageMeta($imageMeta = [])
    {
        $this->imageMeta = $imageMeta;
    }
    
    /**
     * Returns the copyright.
     *
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }
    
    /**
     * Sets the copyright.
     *
     * @param string $copyright
     *
     * @return void
     */
    public function setCopyright($copyright)
    {
        $this->copyright = isset($copyright) ? $copyright : '';
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
     * @param \Pggo\InstititutionenModule\Entity\InstitutionCategoryEntity $element
     *
     * @return bool|int
     */
    private function collectionContains(ArrayCollection $collection, \Pggo\InstititutionenModule\Entity\InstitutionCategoryEntity $element)
    {
        foreach ($collection as $key => $category) {
            /** @var \Pggo\InstititutionenModule\Entity\InstitutionCategoryEntity $category */
            if ($category->getCategoryRegistryId() == $element->getCategoryRegistryId()
                && $category->getCategory() == $element->getCategory()
            ) {
                return $key;
            }
        }
    
        return false;
    }
    
    /**
     * Returns the pictures.
     *
     * @return \Pggo\InstititutionenModule\Entity\PictureEntity[]
     */
    public function getPictures()
    {
        return $this->pictures;
    }
    
    /**
     * Sets the pictures.
     *
     * @param \Pggo\InstititutionenModule\Entity\PictureEntity[] $pictures
     *
     * @return void
     */
    public function setPictures($pictures)
    {
        foreach ($pictures as $pictureSingle) {
            $this->addPictures($pictureSingle);
        }
    }
    
    /**
     * Adds an instance of \Pggo\InstititutionenModule\Entity\PictureEntity to the list of pictures.
     *
     * @param \Pggo\InstititutionenModule\Entity\PictureEntity $picture The instance to be added to the collection
     *
     * @return void
     */
    public function addPictures(\Pggo\InstititutionenModule\Entity\PictureEntity $picture)
    {
        $this->pictures->add($picture);
        $picture->setInstitution($this);
    }
    
    /**
     * Removes an instance of \Pggo\InstititutionenModule\Entity\PictureEntity from the list of pictures.
     *
     * @param \Pggo\InstititutionenModule\Entity\PictureEntity $picture The instance to be removed from the collection
     *
     * @return void
     */
    public function removePictures(\Pggo\InstititutionenModule\Entity\PictureEntity $picture)
    {
        $this->pictures->removeElement($picture);
        $picture->setInstitution(null);
    }
    
    
    
    /**
     * Returns the formatted title conforming to the display pattern
     * specified for this entity.
     *
     * @return string The display title
     */
    public function getTitleFromDisplayPattern()
    {
        $listHelper = ServiceUtil::get('pggo_instititutionen_module.listentries_helper');
    
        $formattedTitle = ''
                . $this->getName();
    
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
        $helper = $container->get('pggo_instititutionen_module.listentries_helper');
        $listEntries = $helper->getWorkflowStateEntriesForInstitution();
    
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
        return 'pggoinstititutionenmodule.ui_hooks.institutions';
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
        return 'Institution ' . $this->createCompositeIdentifier() . ': ' . $this->getTitleFromDisplayPattern();
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
        $this->setImage(null);
        $this->setImageMeta([]);
        $this->setImageUrl('');
    
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
