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
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'label' => 'Дата',
                'html5' => false,
                'required' => true,
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
                'required' => false
            ])
            ->add('Save', SubmitType::class, [
                'label' => 'Зберегти',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Competition::class
        ]);
    }
}
