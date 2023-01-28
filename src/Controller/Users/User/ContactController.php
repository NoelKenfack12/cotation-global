<?php

namespace App\Controller\Users\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Security\TokenAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;

class ContactController extends AbstractController
{
    public function contactssuccursale(GeneralServicetext $service)
    {
        return $this->render('Theme/Users/Adminuser/User/contactssuccursale.html.twig');
    }
}