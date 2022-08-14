<?php


declare(strict_types=1);

namespace App\Form\Extension;

use App\Entity\UserGroup;
use App\WebContent\User;
use Symfony\Component\Form\CallbackTransformer;
use Sylius\Bundle\UserBundle\Form\Type\UserType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sylius\Bundle\CoreBundle\Form\Type\User\AdminUserType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;

final class AdminUserTypeExtension extends AbstractTypeExtension
{
    public function __construct(User $userService)
    {
        $this->userService = $userService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // parent::buildForm($builder, $options);
        $roleChoices = $this->userService->getRoles();
        $builder
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => false,
                'choices'  => $roleChoices,
            ]);
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [AdminUserType::class];
    }
}
