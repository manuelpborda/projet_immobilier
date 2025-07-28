<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    /**
     * En este método construyo el formulario de registro de usuario,
     * agregando los campos necesarios para mi aplicación inmobiliaria,
     * incluyendo el tipo de usuario para permitir múltiples roles desde el registro.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Campo de email, que uso como identificador único para cada usuario
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduce tu correo electrónico',
                    ]),
                ],
            ])
            // Campo de contraseña, usando 'mapped' => false para que se procese manualmente en el controlador
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Contraseña',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduce una contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Tu contraseña debe tener al menos {{ limit }} caracteres',
                        // el límite máximo ayuda a prevenir ataques de denegación de servicio
                        'max' => 4096,
                    ]),
                ],
            ])
            // Campo para elegir el tipo de usuario (cliente, propietario, agente)
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo de usuario',
                'choices'  => [
                    // Defino aquí las opciones posibles según los requerimientos del proyecto
                    'Cliente' => 'client',
                    'Propietario' => 'proprietaire',
                    'Agente (Super Admin)' => 'agent',
                ],
                'required' => true,
                'placeholder' => 'Seleccione el tipo',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Debes seleccionar un tipo de usuario',
                    ]),
                ],
            ]);
    }

    /**
     * Configuro aquí el formulario para que trabaje con la entidad User,
     * así Symfony vincula los datos del formulario con el objeto User.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
