<?php

namespace App\Form;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('biography', CKEditorType::class, [
                "config_name" => 'basic'
            ])
            ->add('birthday', DateType::class, [
                'days' => range(1,31),
                'years' => range(1930, 2022),
                'format' => 'dd MMMM yyyy',
            ])
            ->add('avatarName', HiddenType::class)
            ->add('avatarFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => "supprimer image",
                'download_uri' => false,
                'asset_helper' => true
              ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
