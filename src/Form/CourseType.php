<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Region;
use App\Form\HoleType;
use App\Entity\KindCourse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom du golf",
                'attr' => [
                    'placeholder' => 'Indiquer si c\'est le 9 ou 18 trous'
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description du golf',
            ])
            ->add('adress', TextType::class, [
                'label' => "Adresse du golf"
            ])
            ->add('phone', TelType::class, [
                'label' => "Téléphone du golf",
                'attr' => [
                    'placeholder' => '00 00 00 00 00'
                ]
            ])
            ->add('website', TextType::class, [
                'label' => "Siteweb du golf"
            ])
            ->add('avatarName', HiddenType::class)
            ->add('avatarFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true, 
                'delete_label' => "supprimer image",
                'asset_helper' => true
              ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'expanded' => true,
                'choice_label' => 'label',
            ])
            ->add('kind', EntityType::class, [
                'class' => KindCourse::class,
                'expanded' => true,
                'choice_label' => 'label',
            ])
            ->add('holes', CollectionType::class, [
                'entry_type' => HoleType::class,
                'label' => "Ajout de trous",
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
