<?php

namespace App\Form;

use App\Entity\Competition;
use App\Entity\CompetitionClass;
use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Назва',
            ])
            ->add('date', DateType::class, [
                'label' => 'Дата',
                'widget' => 'single_text'
            ])
            ->add('classes', EntityType::class, [
                'label' => 'Клас',
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-check-inline'];
                },
                'class' => CompetitionClass::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('region', EntityType::class, [
                'label' => 'Область',
                'class' => Region::class,
                'choice_label' => 'name',
            ])
            ->add('attachments', CollectionType::class, [
                'entry_type' => AttachmentType::class,
                'entry_options' => ['label' => false],
                'label' => 'Файли',
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'required' => false,
                'by_reference' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Додаткова інформація',
            ])
            ->add('Зберегти', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competition::class
        ]);
    }
}
