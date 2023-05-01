<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'article"
            ])
            ->add('content',  CKEditorType::class, [
                'label' => "Contenu de l'article",
                "config_name" => 'basic'
            ])
            ->add('imageName', HiddenType::class)
            ->add('imageFile', VichImageType::class, [
              'required' => false,
              'allow_delete' => true, // autoriser la suppression de l'image
              'delete_label' => "supprimer image",
              'download_label' => "Télécharger",
              'download_uri' => true,
              'asset_helper' => true,
              'label' => "Image",
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
