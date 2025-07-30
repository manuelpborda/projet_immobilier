<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Tipos de campos que necesito para construir el formulario
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

// Validadores para asegurar integridad del formulario
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    // Aquí defino cada campo del formulario de registro
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Este campo solicita el correo electrónico, que será único en la base de datos
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, escribe tu correo',
                    ]),
                ],
            ])

            // Aquí uso RepeatedType para que el usuario escriba la contraseña dos veces
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false, // no se guarda directamente, se procesa en el controlador
                'first_options' => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repite la contraseña'],
                'invalid_message' => 'Las contraseñas deben coincidir.',
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

            // Este campo permite definir el tipo de usuario registrado
            ->add('typeUser', ChoiceType::class, [
                'label' => 'Tipo de usuario',
                'choices' => [
                    'Cliente' => 'client',
                    'Propietario' => 'proprietaire',
                    'Administrador' => 'admin',
                ],
                'placeholder' => 'Seleccione una opción',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Debe seleccionar un tipo de usuario',
                    ]),
                ],
            ])

            // Campos adicionales opcionales para enriquecer el perfil del usuario
            ->add('firstName', null, [
                'label' => 'Nombre',
                'required' => false,
            ])
            
            ->add('phone', null, [
                'label' => 'Teléfono',
                'required' => false,
            ])

            // Checkbox obligatorio para aceptar los términos y condiciones de privacidad -----RGPD-----
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Acepto los términos y condiciones de privacidad (RGPD)',
                'mapped' => false, // no se guarda en la base de datos
                'constraints' => [
                    new IsTrue([
                        'message' => 'Debes aceptar los términos y condiciones.',
                    ]),
                ],
            ]);
    }

    // Este método asocia el formulario a la entidad User para guardar los datos
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
