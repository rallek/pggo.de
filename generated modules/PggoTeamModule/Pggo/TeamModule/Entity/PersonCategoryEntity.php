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

namespace Pggo\TeamModule\Entity;

use Pggo\TeamModule\Entity\Base\AbstractPersonCategoryEntity as BaseEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity extension domain class storing person categories.
 *
 * This is the concrete category class for person entities.
 * @ORM\Entity(repositoryClass="\Pggo\TeamModule\Entity\Repository\PersonCategoryRepository")
 * @ORM\Table(name="pggo_team_person_category",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="cat_unq", columns={"registryId", "categoryId", "entityId"})
 *     }
 * )
 */
class PersonCategoryEntity extends BaseEntity
{
    // feel free to add your own methods here
}
