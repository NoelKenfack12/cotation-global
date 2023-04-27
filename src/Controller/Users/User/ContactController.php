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
use App\Entity\Users\User\Contact;
use App\Entity\Localisation\Organisation\Organisation;

class ContactController extends AbstractController
{
    public function contactssuccursale(Organisation $organisation, EntityManagerInterface $em, GeneralServicetext $service, $page)
    {
        $liste_contact = $em->getRepository(Contact::class)
                   ->findContactOrganisation($organisation->getId(), $page, 12);

        return $this->render('Theme/Users/Adminuser/User/contactssuccursale.html.twig', 
        array('liste_contact'=>$liste_contact, 'page'=>$page, 'nombrepage' => ceil(count($liste_contact)/12), 'organisation'=>$organisation));
    }

    public function contactsclient(EntityManagerInterface $em, GeneralServicetext $service, $page)
    {
        $liste_contact = $em->getRepository(Contact::class)
                            ->findContactClient($page, 12);

        return $this->render('Theme/Users/Adminuser/User/contactsclient.html.twig', 
        array('liste_contact'=>$liste_contact, 'page'=>$page, 'nombrepage' => ceil(count($liste_contact)/12)));
    }
}