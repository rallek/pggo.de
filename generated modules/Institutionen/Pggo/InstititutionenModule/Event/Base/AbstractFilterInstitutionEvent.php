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

namespace Pggo\InstititutionenModule\Event\Base;

use Symfony\Component\EventDispatcher\Event;
use Pggo\InstititutionenModule\Entity\InstitutionEntity;

/**
 * Event base class for filtering institution processing.
 */
class AbstractFilterInstitutionEvent extends Event
{
    /**
     * @var InstitutionEntity Reference to treated entity instance.
     */
    protected $institution;

    public function __construct(InstitutionEntity $institution)
    {
        $this->institution = $institution;
    }

    public function getInstitution()
    {
        return $this->institution;
    }
}
