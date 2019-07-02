<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('designation')
            ->add('created_at')
            ->add('updated_at')
            ->add('etagere')
            ->add('type')
            ->add('imageFile', VichImageType::class,[
                'required' => false,
                'allow_delete' => true,
                'download_label' => 'image',
                'download_uri' => true,
                'image_uri' => true,
                'imagine_pattern' => 'my_thumb'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
