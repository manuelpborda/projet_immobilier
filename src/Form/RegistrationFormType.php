<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Tipos de campos
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

// Validadores
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, escribe tu correo',
                    ]),
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'label' => false,
                'first_options' => [
                    'label' => 'Crea tu contraseña',
                    'attr' => ['placeholder' => 'Mínimo 6 caracteres']
                ],
                'second_options' => [
                    'label' => 'Confirma tu contraseña',
                    'attr' => ['placeholder' => 'Vuelve a escribir tu contraseña']
                ],
                'invalid_message' => 'Las contraseñas no coinciden, por favor verifica.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, escribe una contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres',
                        'max' => 4096,
                    ]),
                ],
            ])

            // --- PROTECCIÓN APLICADA: Solo opciones públicas permitidas ---
            ->add('typeUser', ChoiceType::class, [
                'label' => 'Tipo de usuario',
                'choices' => [
                    'Cliente' => 'client',
                    'Propietario' => 'proprietaire',
                ],
                'placeholder' => 'Seleccione una opción',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Debe seleccionar un tipo de usuario',
                    ]),
                ],
            ])

            ->add('firstName', TextType::class, [
                'label' => 'Nombre',
                'required' => false,
            ])

            ->add('lastName', TextType::class, [
                'label' => 'Apellido',
                'required' => false,
            ])
            
            ->add('phone', TextType::class, [
                'label' => 'Teléfono / Celular',
                'required' => false,
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Acepto la política de tratamiento de datos personales (Ley 1581 de 2012 / Habeas Data)',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Debes autorizar el tratamiento de tus datos para registrarte.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}