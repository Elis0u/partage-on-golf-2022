<?php

namespace App\Form;

use App\Entity\Hole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class HoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom du trou"
            ])
            ->add('distanceRouge', NumberType::class, [
                'label' => "Distance des Rouges"
            ])
            ->add('distanceBleu')
            ->add('distanceJaune')
            ->add('distanceBlanc')
            ->add('tips', TextType::class, [
                'label' => "tips du trou"
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true, // autoriser la suppression de l'image
                'delete_label' => "supprimer image",
                'download_label' => "Télécharger",
                'download_uri' => true,
                'asset_helper' => true
              ]
              )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hole::class,
        ]);
    }
}
