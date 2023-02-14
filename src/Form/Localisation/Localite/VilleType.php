<?php

namespace App\Form\Localisation\Localite;

use App\Entity\Localisation\Localite\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Produit\Service\Pays;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,array('attr'=>array('placeholder'=>'Nom de la ville','class'=>'form-control')))
            ->add('abbreviation', TextType::class,array('attr'=>array('placeholder'=>'Code d\'identification','class'=>'form-control')))
            ->add('pays',EntityType::class, array(
                'class'=> Pays::class,
                'choice_label'=>'nom',
                'multiple'=>false, 
                'attr'=>array('class'=>'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
