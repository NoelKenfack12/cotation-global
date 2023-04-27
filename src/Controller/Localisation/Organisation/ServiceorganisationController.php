<?php

namespace App\Controller\Localisation\Organisation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Localisation\Organisation\Serviceorganisation;
use App\Form\Localisation\Organisation\ServiceorganisationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit\Parametre\Forminput;
use App\Form\Produit\Parametre\ForminputType;

class ServiceorganisationController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function servicesorganisation(Request $request, EntityManagerInterface $em)
    {
        $serviceorganisation = new Serviceorganisation();
	    $formService = $this->createForm(ServiceorganisationType::class, $serviceorganisation);

        $forminput = new Forminput();
	    $form = $this->createForm(ForminputType::class, $forminput);

        if($request->getMethod() == 'POST')
        {
            $formService->handleRequest($request);
            if ($formService->isValid()){
                $em->persist($serviceorganisation);
                $em->flush();
            }else{
                $this->get('session')->getFlashBag()->add('information','Données invalides.');
            }
        }

        $liste_serviceOrg = $em->getRepository(Serviceorganisation::class)
	                        ->FindAll();
        $formsupp = $this->createFormBuilder()->getForm(); 
        return $this->render('Theme/Users/Adminuser/Serviceorganisation/servicesorganisation.html.twig', 
        array('formService'=>$formService->createView(), 'liste_serviceOrg'=>$liste_serviceOrg, 
        'form'=>$form->createView(), 'formsupp'=>$formsupp->createView()));
    }

    public function updateserviceorg(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $serviceorganisation = $em->getRepository(Serviceorganisation::class)
                        ->find($id);

        if($serviceorganisation != null)
        {
        $form = $this->createForm(ServiceorganisationType::class, $serviceorganisation);
        if ($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if ($form->isValid()){
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
            }else{
                $this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
            }
            return $this->redirect($this->generateUrl('users_adminuser_services_organisation'));
        }
        return $this->render('Theme/Users/Adminuser/Serviceorganisation/updateserviceorg.html.twig',
        array('form'=>$form->createView(),'serviceorganisation'=>$serviceorganisation));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function supprimerserviceorg(Serviceorganisation $serviceorganisation, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
        $formsupp->handleRequest($request);
        if ($formsupp->isValid()){
            $liste_forminput = $serviceorganisation->getForminputs();
            if(count($liste_forminput) > 0){
                $this->get('session')->getFlashBag()->add('information','Action réfusée: Ce service contient déjà les champs de formulaire.');
            }else{
                $em->remove($serviceorganisation);
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','continent bien supprimé.');
            }
            return $this->redirect($this->generateUrl('users_adminuser_services_organisation'));
        }
        }
        $this->get('session')->getFlashBag()->add('supprime_service',$serviceorganisation->getId());
        $this->get('session')->getFlashBag()->add('supprime_service',$serviceorganisation->getNom());
        return $this->redirect($this->generateUrl('users_adminuser_services_organisation'));
    }
}