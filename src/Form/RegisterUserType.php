<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, ['label' => ' ', 'attr' => ['placeholder' => 'Prénom', 'class' => 'form-control form-control-user']])
            ->add('lastname', null, ['label' => ' ', 'attr' => ['placeholder' => 'Nom de famille', 'class' => 'form-control form-control-user']])
            ->add('mail', EmailType::class, ['label' => ' ', 'attr' => ['placeholder' => 'E-mail', 'class' => 'form-control form-control-user']])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => ' ', 'attr' => ['placeholder' => 'Mot de passe', 'class' => 'form-control form-control-user']],
                'second_options' => ['label' => ' ', 'attr' => ['placeholder' => 'Confirmer', 'class' => 'form-control form-control-user']],
                'invalid_message' => 'Les mots de passe doivent être identiques'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Créer un compte', 'attr' => ['placeholder' => 'Confirmer', 'class' => 'btn btn-primary btn-user btn-block']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
