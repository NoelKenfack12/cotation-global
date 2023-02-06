<?php
namespace App\Controller\Produit\Parametre;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Produit\Parametre\ForminputType;
use App\Entity\Produit\Parametre\Forminput;
use App\Entity\Produit\Parametre\Optionforminput;
use App\Service\Servicetext\GeneralServicetext;

class ForminputController extends AbstractController
{
	public function ajouterforminput(GeneralServicetext $service, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$forminput = new Forminput();
		$form = $this->createForm(ForminputType::class, $forminput);
		
		if ($request->getMethod() == 'POST') {
		$form->handleRequest($request);
		if ($form->isValid()){

			$em->persist($forminput);
			$em->flush();
			$this->get('session')->getFlashBag()->add('information','le champ a été bien enregistrée.');
		}else{
			$this->get('session')->getFlashBag()->add('information','Echec, Une erreur a été rencontrée');
		}
		}
		return $this->redirect($this->generateUrl('users_adminuser_services_organisation'));
	}


	public function updateforminput(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $forminput = $em->getRepository(Forminput::class)
                        ->find($id);

        if($forminput != null)
        {
        $form = $this->createForm(ForminputType::class, $forminput);
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
        return $this->render('Theme/Users/Adminuser/Parametre/updateserviceorg.html.twig',
        array('form'=>$form->createView(),'forminput'=>$forminput));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

	public function deleteforminput(Forminput $forminput, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
        $formsupp->handleRequest($request);
        if ($formsupp->isValid()){
            $liste_optioninput = $forminput->getOptionFormInputs();
            if(count($liste_optioninput) > 0){
                $this->get('session')->getFlashBag()->add('information','Action réfusée: Ce service contient déjà les champs de formulaire.');
            }else{
                $em->remove($forminput);
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','continent bien supprimé.');
            }
            return $this->redirect($this->generateUrl('users_adminuser_services_organisation'));
        }
        }
        $this->get('session')->getFlashBag()->add('supprime_forminput',$forminput->getId());
        $this->get('session')->getFlashBag()->add('supprime_forminput',$forminput->getNom());
        return $this->redirect($this->generateUrl('users_adminuser_services_organisation'));
    }

	public function newuptionforminput(Forminput $forminput, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST' and isset($_POST['option'])){
			$optionforminput = new Optionforminput();
			$optionforminput->setForminput($forminput);
			$optionforminput->setLibelle($_POST['option']);

			$em->persist($optionforminput);
			$em->flush();
			$this->get('session')->getFlashBag()->add('information','l\'option a été bien enregistrée.');
        }
        return $this->redirect($this->generateUrl('users_adminuser_services_organisation'));
	}
}
