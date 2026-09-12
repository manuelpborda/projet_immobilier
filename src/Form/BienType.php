<?php

namespace App\Form;

use App\Entity\Bien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType; // <-- Importación para el enlace de YouTube
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // 1. DATOS GENERALES
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título del inmueble',
                'attr' => ['placeholder' => 'Ej: Espectacular apartamento al norte de Bogotá']
            ])
            ->add('matricula', TextType::class, [
                'label' => 'Matrícula Inmobiliaria',
                'required' => false,
                'attr' => ['placeholder' => 'Identificador oficial del inmueble']
            ])
            ->add('tipoTransaccion', ChoiceType::class, [
                'label' => 'Tipo de Transacción / Negocio',
                'choices' => [
                    'Venta' => 'Venta',
                    'Arriendo' => 'Arriendo',
                ],
            ])
            ->add('typeDeBien', ChoiceType::class, [
                'label' => 'Tipo de Inmueble',
                'choices' => [
                    'Apartamento' => 'Apartamento',
                    'Casa' => 'Casa',
                    'Apartaestudio' => 'Apartaestudio',
                    'Finca' => 'Finca',
                    'Local Comercial' => 'Local Comercial',
                    'Lote' => 'Lote',
                ],
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Precio de Venta/Alquiler (COP)',
                'attr' => [
                    'placeholder' => 'Ej: 600000000'
                ],
                'help' => 'Escribe el número entero seguido, sin usar puntos ni comas.',
                'help_attr' => [
                    'style' => 'color: #555555; font-size: 0.85rem; margin-top: 5px; display: block;'
                ]
            ])
            ->add('prixAdministration', NumberType::class, [
                'label' => 'Valor Administración (COP)',
                'required' => false,
                'attr' => ['placeholder' => 'Solo números']
            ])
            ->add('etatDuBien', ChoiceType::class, [
                'label' => 'Estado físico de la propiedad',
                'placeholder' => 'Selecciona el estado...',
                'choices' => [
                    'Nuevo / Estrenar' => 'Nuevo',
                    'Usado en excelente estado' => 'Usado',
                    'En planos / Construcción' => 'En planos',
                    'Remodelado' => 'Remodelado',
                ],
            ])
            ->add('anoConstruccion', IntegerType::class, [
                'label' => 'Año de construcción',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: 2015']
            ])
            ->add('habitaciones', IntegerType::class, [
                'label' => 'Alcobas',
                'attr' => ['min' => 0]
            ])
            ->add('banos', IntegerType::class, [
                'label' => 'Baños',
                'attr' => ['min' => 0]
            ])
            ->add('garajes', ChoiceType::class, [
                'label' => 'Garajes',
                'choices' => [
                    '0 (Sin garaje)' => '0',
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4 o más' => '4+',
                    'Comunal / Compartido' => 'Comunal',
                ],
                'attr' => ['min' => 0]
            ])
            ->add('estrato', ChoiceType::class, [
                'label' => 'Estrato',
                'placeholder' => 'Selecciona el estrato...',
                'required' => false,
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    'Campestre' => 'Campestre'
                ],
            ])
            ->add('numeroPiso', IntegerType::class, [
                'label' => 'Número de piso de la unidad',
                'required' => false,
            ])
            ->add('surfaceM2', NumberType::class, [
                'label' => 'Área privada (m2)'
            ])
            ->add('areaConstruida', NumberType::class, [
                'label' => 'Área construida (m2)',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción detallada del inmueble',
                'required' => false,
                'attr' => ['rows' => 5],
                'constraints' => [
                    new Callback([$this, 'validarDescripcionSinDatosContacto'])
                ]
            ]);

        // 2. UBICACIÓN GEOGRÁFICA
        $builder
            ->add('departamento', ChoiceType::class, [
                'label' => 'Departamento',
                'placeholder' => 'Seleccione un departamento...',
                'choices' => [
                    'Antioquia' => 'Antioquia', 'Atlántico' => 'Atlántico', 'Bogotá D.C.' => 'Bogotá D.C.', 
                    'Bolívar' => 'Bolívar', 'Boyacá' => 'Boyacá', 'Caldas' => 'Caldas', 'Cauca' => 'Cauca', 
                    'Cesar' => 'Cesar', 'Córdoba' => 'Córdoba', 'Cundinamarca' => 'Cundinamarca', 
                    'Huila' => 'Huila', 'La Guajira' => 'La Guajira', 'Magdalena' => 'Magdalena', 
                    'Meta' => 'Meta', 'Nariño' => 'Nariño', 'Norte de Santander' => 'Norte de Santander', 
                    'Quindío' => 'Quindío', 'Risaralda' => 'Risaralda', 'Santander' => 'Santander', 
                    'Sucre' => 'Sucre', 'Tolima' => 'Tolima', 'Valle del Cauca' => 'Valle del Cauca'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => ['class' => 'd-none']
            ])
            ->add('barrio', TextType::class, [
                'label' => 'Zona / Barrio',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: Chapinero']
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Dirección (Información privada)'
            ]);

       // 3. MULTIMEDIA
        $builder->add('urlVideo', UrlType::class, [
            'label' => '🎥 Enlace de video en YouTube (Opcional)',
            'required' => false,
            'attr' => [
                'placeholder' => 'Ej: https://www.youtube.com/watch?v=...'
            ],
            'help' => 'Copia y pega la URL completa de tu video de YouTube para mostrar un recorrido virtual.',
            'help_attr' => [
                'style' => 'color: #555555; font-size: 0.85rem; margin-top: 5px; display: block;'
            ]
        ]);

        for ($i = 1; $i <= 20; $i++) {
            $builder->add('foto' . $i, FileType::class, [
                // Etiqueta destacada si es la primera foto
                'label' => $i === 1 ? '⭐ Foto de Portada (Imagen Principal)' : 'Archivo ' . $i . ' (Opcional)',
                'mapped' => false, 
                'required' => false,
                'attr' => [
                    'accept' => 'image/jpeg, image/png, image/webp, image/gif, video/mp4, video/webm, video/quicktime, video/x-msvideo'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '200M',
                        'maxSizeMessage' => 'El archivo supera los 200 MB permitidos en la plataforma.',
                        'mimeTypes' => [
                            'image/jpeg', 'image/png', 'image/webp', 'image/gif',
                            'video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo',
                        ],
                        'mimeTypesMessage' => 'Sube un archivo válido: Imágenes o Videos permitidos.',
                    ])
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }

    public function validarDescripcionSinDatosContacto($value, ExecutionContextInterface $context): void
    {
        if (!$value) {
            return;
        }
        $patronTelefono = '/\b(?:\+?57)?\s*3\d{2}\s*\d{3}\s*\d{4}\b|\b\d{7,10}\b/';
        $patronCorreo = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';

        if (preg_match($patronTelefono, $value) || preg_match($patronCorreo, $value)) {
            $context->buildViolation('Por políticas de seguridad, no está permitido incluir números de teléfono ni correos electrónicos en la descripción.')
                ->addViolation();
        }
    }
}