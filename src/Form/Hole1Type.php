<?php

namespace App\Form;

use App\Entity\Hole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Hole1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('tips')
            ->add('distanceBleu')
            ->add('distanceRouge')
            ->add('distanceJaune')
            ->add('distanceBlanc')
            ->add('imageName')
            ->add('course')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hole::class,
        ]);
    }
}
