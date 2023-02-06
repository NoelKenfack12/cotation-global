<?php

namespace App\Form\Localisation\Organisation;

use App\Entity\Localisation\Organisation\Organisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use App\Entity\Localisation\Localite\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, array('attr'=>array('class'=>'form-control input-lg','placeholder'=>'Nom de l\'organisation')))
            ->add('ville',EntityType::class, array(
                'class'=> Ville::class,
                'choice_label'=>'name',
                'multiple'=>false, 
                'attr'=>array('class'=>'form-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organisation::class,
        ]);
    }
}
