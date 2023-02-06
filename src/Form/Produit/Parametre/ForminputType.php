<?php

namespace App\Form\Produit\Parametre;

use App\Entity\Produit\Parametre\Forminput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use App\Entity\Localisation\Organisation\Serviceorganisation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ForminputType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, array('attr'=>array('class'=>'form-control input-lg','placeholder'=>'Nom du champ')))
            ->add('libelle', TextType::class, array('attr'=>array('class'=>'form-control input-lg','placeholder'=>'Libéllé sur le champ')))
            ->add('typeInput', ChoiceType::class, [
                'choices' => [ 
                    'Champ Text' => 'text',
                    'Champ nombre' => 'number',
                    'Champ selection' => 'select',
                    '1 month' => 'textarea',
                ],
                'group_by' => function($choice, $key, $value) {
                    if ($choice <= new \DateTime('+3 days')) {
                        return 'Soon';
                    }
            
                    return 'Later';
                }])
            ->add('placeholder', TextType::class, array('attr'=>array('class'=>'form-control input-lg','placeholder'=>'Placeholder du champ')))
            ->add('required', CheckboxType::class, array('attr'=>array('class'=>'form-control input-lg')))
            ->add('serviceorganisation',EntityType::class, array(
                'class'=>Serviceorganisation::class,
                'choice_label'=>'nom',
                'multiple'=>false, 
                'attr'=>array('class'=>'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forminput::class,
        ]);
    }
}
