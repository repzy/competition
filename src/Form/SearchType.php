<?php

namespace App\Form;

use App\Entity\CompetitionClass;
use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'label' => false,
                'required' => false,
                'placeholder' => 'Область'
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Назва'
                ]
            ])
        ;
    }
}