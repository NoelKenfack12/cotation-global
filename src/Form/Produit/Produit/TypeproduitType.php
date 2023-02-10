<?php

namespace App\Form\Produit\Produit;

use App\Entity\Produit\Produit\Typeproduit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TypeproduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,array('attr'=>array('placeholder'=>'Nom du type de produit','class'=>'form-control')))
            ->add('description',TextType::class,array('attr'=>array('placeholder'=>'Description du type de produit','class'=>'form-control')))
            ->add('rang',IntegerType::class,array('attr'=>array('placeholder'=>'Rang dans le classement','class'=>'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Typeproduit::class,
        ]);
    }
}
