<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Confirmer le mot de passe'),
                'invalid_message' => 'Les 2 mots de passe ne sont pas identiques.',
            ))
            ->add('mail', EmailType::class)
            ->add('firstname')
            ->add('lastname')
            ->add('role', ChoiceType::class, ['choices' =>
                [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_COMM' => 'ROLE_COMM',
                    'ROLE_REVIEWER' => 'ROLE_REVIEWER',
                    'ROLE_USER' => 'ROLE_USER',
                ],
                'mapped' => false,
                'data' => $options['data']->getRoles()[0]
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'attr' => ['data-toggle' => 'toggle'],
                'data' => $options['data']->getEnabled()
            ])
            ->add('send', SubmitType::class, ['label' => 'Envoyer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
