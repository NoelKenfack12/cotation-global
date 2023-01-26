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
            ->add('nom',TextType::class,array('attr'=>array('placeholder'=>'Nom du pays','style'=>'width: 100%;')))
            ->add('siteweb',TextType::class,array('attr'=>array('placeholder'=>'Site web','style'=>'width: 100%;')))
            ->add('citoyen',TextType::class,array('attr'=>array('placeholder'=>'Citoyen','style'=>'width: 100%;')))
            ->add('citoyenne',TextType::class,array('attr'=>array('placeholder'=>'Citoyenne','style'=>'width: 100%;')))
            ->add('code',TextType::class,array('attr'=>array('placeholder'=>'Code du pays','style'=>'width: 100%;')))
            ->add('domaine',TextType::class,array('attr'=>array('placeholder'=>'Extension','style'=>'width: 100%;')))
            ->add('drapeau',DrapeauType::class)
            ->add('file',FileType::class, array('required'=>false))
            ->add('continent',EntityType::class, array(
			'class'=> Continent::class,
			'choice_label'=>'nom',
			'multiple'=>false, 
			'attr'=>array('class'=>'form-control input-lg')))
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