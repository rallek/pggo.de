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

namespace Pggo\InstititutionenModule\Entity;

use Pggo\InstititutionenModule\Entity\Base\AbstractInstitutionEntity as BaseEntity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Pggo\InstititutionenModule\Traits\EntityWorkflowTrait;
use Pggo\InstititutionenModule\Traits\StandardFieldsTrait;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for institution entities.
 * @ORM\Entity(repositoryClass="Pggo\InstititutionenModule\Entity\Repository\InstitutionRepository")
 * @ORM\Table(name="pggo_instit_institution",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 */
class InstitutionEntity extends BaseEntity
{
    // feel free to add your own methods here
}