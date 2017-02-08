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

namespace RK\HelperModule\Form\DataTransformer\Base;

use Symfony\Component\Form\DataTransformerInterface;
use Zikula\UsersModule\Entity\RepositoryInterface\UserRepositoryInterface;
use Zikula\UsersModule\Entity\UserEntity;

/**
 * User field transformer base class.
 *
 * This data transformer treats user fields.
 */
abstract class AbstractUserFieldTransformer implements DataTransformerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * UserFieldTransformer constructor.
     *
     * @param UserRepositoryInterface $userRepository UserRepository service instance
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Transforms the object values to the normalised value.
     *
     * @param UserEntity|null $value
     *
     * @return int|null
     */
    public function transform($value)
    {
        if (null === $value || !$value) {
            return null;
        }

        if ($value instanceof UserEntity) {
            return $value->getUid();
        }

        return intval($value);
    }

    /**
     * Transforms the form value back to the user entity.
     *
     * @param int $value
     *
     * @return UserEntity|null
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        return $this->userRepository->find($value);
    }
}
