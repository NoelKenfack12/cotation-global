<?php
namespace App\Controller\Produit\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit\Produit\Produit;
use App\Form\Produit\Produit\ProduitType;

class ProduitController extends AbstractController
{
    public function produitorganisation(GeneralServicetext $service, Request $request)
    {
        return $this->render('Theme/Users/Adminuser/Produitorganisation/servicesorganisation.html.twig');
    }

    public function nouveauproduit(Request $request, EntityManagerInterface $em)
    {
        $produit = new Produit();
	    $formProd = $this->createForm(ProduitType::class, $produit);

        if($request->getMethod() == 'POST')
        {
            $formProd->handleRequest($request);
            if($formProd->isValid()){
                $em->persist($produit);
                $em->flush();
            }else{
                $this->get('session')->getFlashBag()->add('information','Données invalides.');
            }
        }
        return $this->redirect($this->generateUrl('users_adminuser_type_produit'));
    }

    public function modificationproduit(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $produit = $em->getRepository(Produit::class)
                        ->find($id);
        if($produit != null)
        {
        $form = $this->createForm(ProduitType::class, $produit);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
            }else{
                $this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
            }
            return $this->redirect($this->generateUrl('users_adminuser_type_produit'));
        }
        return $this->render('Theme/Users/Adminuser/Produit/modificationproduit.html.twig',
        array('form'=>$form->createView(),'produit'=>$produit));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function deleteproduit(Produit $produit, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
            $formsupp->handleRequest($request);
            if ($formsupp->isValid()){
                $em->remove($produit);
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','produit bien supprimée.');
                return $this->redirect($this->generateUrl('users_adminuser_type_produit'));
            }
        }
        $this->get('session')->getFlashBag()->add('supprime_produit', $produit->getId());
        $this->get('session')->getFlashBag()->add('supprime_produit', $produit->getNom());
        return $this->redirect($this->generateUrl('users_adminuser_type_produit'));
    }
}