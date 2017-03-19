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

namespace Pggo\InstititutionenModule\Form\Type\Field\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Zikula\UsersModule\Entity\RepositoryInterface\UserRepositoryInterface;
use Zikula\UsersModule\Entity\UserEntity;
use Pggo\InstititutionenModule\Form\DataTransformer\UserFieldTransformer;

/**
 * User field type base class.
 */
abstract class AbstractUserType extends AbstractType
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * UserType constructor.
     *
     * @param UserRepositoryInterface $userRepository UserRepository service instance
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new UserFieldTransformer($this->userRepository);
        $builder->addModelTransformer($transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['inline_usage'] = $options['inline_usage'];

        $fieldName = $form->getConfig()->getName();
        $parentData = $form->getParent()->getData();
        $accessor = PropertyAccess::createPropertyAccessor();
        $fieldNameGetter = 'get' . ucfirst($fieldName);
        $user = null !== $parentData && method_exists($parentData, $fieldNameGetter) ? $accessor->getValue($parentData, $fieldNameGetter) : null;

        $view->vars['user_name'] = null !== $user && is_object($user) ? $user->getUname() : '';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'inline_usage' => false
            ])
            ->setAllowedTypes([
                'inline_usage' => 'bool'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\TextType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pggoinstititutionenmodule_field_user';
    }
}