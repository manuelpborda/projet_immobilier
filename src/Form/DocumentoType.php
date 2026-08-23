<?php

namespace App\Form;

use App\Entity\Documento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DocumentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipoDocumento', ChoiceType::class, [
                'label' => 'Tipo de Documento',
                'choices' => [
                    '📜 Contrato de Corretaje / Mandato' => 'Contrato',
                    '🏠 Escritura Oficial o Título Propiedad' => 'Escritura',
                    '🧾 Factura o Recibo de Pago' => 'Factura',
                    '📂 Certificado de Libertad / Impuestos' => 'Oficial',
                    '📁 Otro Documento Informativo' => 'Otro',
                ],
                'attr' => ['class' => 'form-select']
            ])
            ->add('archivo', FileType::class, [
                'label' => 'Seleccionar Archivo (PDF, JPG, PNG)',
                'mapped' => false, // No mapeado directo a la entidad para procesarlo a mano
                'required' => true,
                'attr' => ['class' => 'form-control', 'accept' => '.pdf, .jpg, .jpeg, .png'],
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'maxSizeMessage' => 'El documento legal no puede superar los 20 MB.',
                        'mimeTypes' => ['application/pdf', 'image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Por seguridad, solo se permiten archivos PDF o imágenes JPG/PNG.',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Documento::class,
        ]);
    }
}