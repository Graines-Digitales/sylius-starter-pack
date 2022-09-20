<?php


declare(strict_types=1);

namespace App\Form\Extension;

use App\WebContent\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Sylius\Bundle\CoreBundle\Form\Type\User\AdminUserType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


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
