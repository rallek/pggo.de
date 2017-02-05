<?php
/**
 * MediaAttach.
 *
 * @copyright Ralf Koester (Pggo)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Ralf Koester <ralf@familie-koester.de>.
 * @link http://pggo.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace Pggo\MediaAttachModule\Event\Base;

use Symfony\Component\EventDispatcher\Event;
use Pggo\MediaAttachModule\Entity\FileEntity;

/**
 * Event base class for filtering file processing.
 */
class AbstractFilterFileEvent extends Event
{
    /**
     * @var FileEntity Reference to treated entity instance.
     */
    protected $file;

    public function __construct(FileEntity $file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }
}
