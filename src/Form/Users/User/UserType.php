<?php

namespace App\Form\Users\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Users\User\User;


class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class ,array('attr'=>array('placeholder'=>'Nom','class'=>'form-control')))
            ->add('prenom',TextType::class ,array('attr'=>array('placeholder'=>'Prenom','class'=>'form-control')))
            ->add('username',EmailType::class ,array('attr'=>array('placeholder'=>'email','class'=>'form-control')))
            ->add('telephone',TextType::class ,array('attr'=>array('placeholder'=>'Numero de téléphone','class'=>'form-control')))
            ->add('fakePassword',PasswordType::class ,array('attr'=>array('placeholder'=>'Mot de passe','class'=>'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
    */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'users_userbundle_user';
    }
}
