<?php

namespace App\Form\Localisation\Organisation;

use App\Entity\Localisation\Organisation\Serviceorganisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class ServiceorganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, array('attr'=>array('class'=>'form-control input-lg','placeholder'=>'Nom du service')))
            ->add('description',TextareaType::class, array('attr'=>array('class'=>'form-control input-lg','placeholder'=>'Description du service')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serviceorganisation::class,
        ]);
    }
}
