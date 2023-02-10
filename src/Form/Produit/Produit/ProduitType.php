<?php

namespace App\Form\Produit\Produit;

use App\Entity\Produit\Produit\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Produit\Produit\Typeproduit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,array('attr'=>array('placeholder'=>'Nom du produit','class'=>'form-control')))
            ->add('description',TextareaType::class,array('attr'=>array('placeholder'=>'Indications sur le produit','class'=>'form-control')))
            ->add('montant',TextType::class,array('attr'=>array('placeholder'=>'Nom du produit','class'=>'form-control')))
            ->add('typeproduit',EntityType::class, array(
                'class'=> Typeproduit::class,
                'choice_label'=>'nom',
                'multiple'=>false, 
                'attr'=>array('class'=>'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
