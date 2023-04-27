<?php
namespace App\Controller\Produit\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;
use App\Entity\Produit\Produit\Typeproduit;
use App\Entity\Produit\Produit\Produit;
use App\Form\Produit\Produit\TypeproduitType;
use App\Form\Produit\Produit\ProduitType;
use Doctrine\ORM\EntityManagerInterface;

class TypeproduitController extends AbstractController
{
    public function listetypeproduit(GeneralServicetext $service, Request $request, EntityManagerInterface $em, $page)
    {
        $typeproduit = new Typeproduit();
	    $formTypeProd = $this->createForm(TypeproduitType::class, $typeproduit);

        $produit = new Produit();
	    $formProd = $this->createForm(ProduitType::class, $produit);

        if($request->getMethod() == 'POST')
        {
            $formTypeProd->handleRequest($request);
            if($formTypeProd->isValid()){
                $em->persist($typeproduit);
                $em->flush();
            }else{
                $this->get('session')->getFlashBag()->add('information','Données invalides.');
            }
        }

        $liste_typeProd = $em->getRepository(Typeproduit::class)
	                         ->FindAll();
        $liste_Prod = $em->getRepository(Produit::class)
                        ->findProduitPagine($page, 12);
        $formsupp = $this->createFormBuilder()->getForm(); 

        return $this->render('Theme/Users/Adminuser/Produit/listetypeproduit.html.twig',
        array('formTypeProd'=>$formTypeProd->createView(), 'liste_typeProd'=>$liste_typeProd, 'formsupp'=>$formsupp->createView(), 
        'formProd'=>$formProd->createView(), 'liste_Prod'=>$liste_Prod, 'nombrepage' => ceil(count($liste_Prod)/12),'page'=>$page));
    }

    public function modificationtypeproduit(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $typeproduit = $em->getRepository(Typeproduit::class)
                        ->find($id);
        if($typeproduit != null)
        {
        $form = $this->createForm(TypeproduitType::class, $typeproduit);

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
        return $this->render('Theme/Users/Adminuser/Produit/modificationtypeproduit.html.twig',
        array('form'=>$form->createView(),'typeproduit'=>$typeproduit));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function suppressiontypeproduit(Typeproduit $typeproduit, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
            $formsupp->handleRequest($request);
            if ($formsupp->isValid()){
                $em->remove($typeproduit);
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','type de produit bien supprimée.');
                return $this->redirect($this->generateUrl('users_adminuser_type_produit'));
            }
        }
        $this->get('session')->getFlashBag()->add('supprime_typeproduit', $typeproduit->getId());
        $this->get('session')->getFlashBag()->add('supprime_typeproduit', $typeproduit->getNom());
        return $this->redirect($this->generateUrl('users_adminuser_type_produit'));
    }
}