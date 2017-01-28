<?php
/**
 * Team.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://pggo.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace Pggo\TeamModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Zikula\CategoriesModule\Entity\AbstractCategoryAssignment;

/**
 * Entity extension domain class storing person categories.
 *
 * This is the base category class for person entities.
 */
abstract class AbstractPersonCategoryEntity extends AbstractCategoryAssignment
{
    /**
     * @ORM\ManyToOne(targetEntity="\Pggo\TeamModule\Entity\PersonEntity", inversedBy="categories")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id")
     * @var \Pggo\TeamModule\Entity\PersonEntity
     */
    protected $entity;
    
    /**
     * Get reference to owning entity.
     *
     * @return \Pggo\TeamModule\Entity\PersonEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }
    
    /**
     * Set reference to owning entity.
     *
     * @param \Pggo\TeamModule\Entity\PersonEntity $entity
     */
    public function setEntity(/*\Pggo\TeamModule\Entity\PersonEntity */$entity)
    {
        $this->entity = $entity;
    }
}