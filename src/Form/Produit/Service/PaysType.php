<?php

namespace App\Form\Produit\Service;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Produit\Service\Continent;
use App\Entity\Produit\Service\Pays;

class PaysType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,array('attr'=>array('placeholder'=>'Nom du pays','class'=>'form-control')))
            ->add('siteweb',TextType::class,array('attr'=>array('placeholder'=>'Site web','class'=>'form-control')))
            ->add('citoyen',TextType::class,array('attr'=>array('placeholder'=>'Citoyen','class'=>'form-control')))
            ->add('citoyenne',TextType::class,array('attr'=>array('placeholder'=>'Citoyenne','class'=>'form-control')))
            ->add('code',TextType::class,array('attr'=>array('placeholder'=>'Code du pays','class'=>'form-control')))
            ->add('domaine',TextType::class,array('attr'=>array('placeholder'=>'Extension','class'=>'form-control')))
            ->add('drapeau',DrapeauType::class)
            ->add('continent',EntityType::class, array(
			'class'=> Continent::class,
			'choice_label'=>'nom',
			'multiple'=>false, 
			'attr'=>array('class'=>'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Pays::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'produit_servicebundle_pays';
    }
}