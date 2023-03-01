<?php
namespace App\Controller\Produit\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;
use App\Entity\Localisation\Organisation\Organisation;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit\Produit\Produit;
use App\Entity\Produit\Produit\ProduitOrganisation;
use App\Entity\Produit\Produit\Produitpanier;

class ProduitorganisationController extends AbstractController
{
    public function servicesorganisation(Organisation $organisation, GeneralServicetext $service, EntityManagerInterface $em, Request $request, $page)
    {
        $produit_organisation = $em->getRepository(ProduitOrganisation::class)
                                   ->findProduitOrganisationPagine($organisation->getId(), $page, 12);

        $formsupp = $this->createFormBuilder()->getForm();
        return $this->render('Theme/Users/Adminuser/Produitorganisation/servicesorganisation.html.twig',
        array('organisation'=>$organisation, 'produit_organisation'=>$produit_organisation, 'page'=>$page, 
        'nombrepage' => ceil(count($produit_organisation)/12), 'formsupp'=>$formsupp->createView()));
    }

    public function servicestargeorganisation(Organisation $organisation, EntityManagerInterface $em, $page)
    {
        $produitOrg_List = $em->getRepository(ProduitOrganisation::class)
                              ->findProduitOrganisationArray($organisation->getId());

        if(count($produitOrg_List) == 0)
        {
            $liste_Prod = $em->getRepository(Produit::class)
                        ->findProduitPagine($page, 12);
        }else{
            $liste_Prod = $em->getRepository(Produit::class)
                             ->findProduitExitListe($produitOrg_List, $page, 12); 
        }
        

        return $this->render('Theme/Users/Adminuser/Produitorganisation/servicestargeorganisation.html.twig',
        array('organisation'=>$organisation, 'liste_Prod'=>$liste_Prod, 'page'=>$page, 'nombrepage' => ceil(count($liste_Prod)/12)));
    }

    public function targuernouveauproduit(Organisation $organisation, GeneralServicetext $service, Request $request, $id)
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
            $produitOrganisation = $em->getRepository(ProduitOrganisation::class)
                                      ->findOneBy(array('produit'=>$produit, 'organisation'=>$organisation), array('date'=>'desc'), 1);

            if($request->getMethod() == 'POST'){
                if(isset($_POST['montant']) and isset($_POST['selectionDefault']))
                {
                    if($produitOrganisation != null)
                    {
                        $produitOrganisation->setMontant($_POST['montant']);
                        $produitOrganisation->setSelectDefault($_POST['selectionDefault']);
                    }else{
                        $newProduitOrganisation = new ProduitOrganisation();
                        $newProduitOrganisation->setProduit($produit);
                        $newProduitOrganisation->setOrganisation($organisation);
                        $newProduitOrganisation->setMontant($_POST['montant']);
                        $newProduitOrganisation->setSelectDefault($_POST['selectionDefault']);
                        $em->persist($newProduitOrganisation);
                    }
                    $em->flush();
                }else{
                    $this->get('session')->getFlashBag()->add('information','Données invalides.');
                }
                
                return $this->redirect($this->generateUrl('users_adminuser_services_targe_organisation', array('id'=>$organisation->getId())));
            }

            return $this->render('Theme/Users/Adminuser/Produitorganisation/targuernouveauproduit.html.twig',
            array('produit'=>$produit, 'organisation'=>$organisation, 'produitOrganisation'=>$produitOrganisation));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function deleteproduitorg(ProduitOrganisation $produitOrganisation, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
            $formsupp->handleRequest($request);
            if($formsupp->isValid()){

                $oldProduitpanier = $em->getRepository(Produitpanier::class)
                                       ->FindBy(array('produitOrganisation'=>$produitOrganisation), array('date'=>'desc'));
                if(count($oldProduitpanier) == 0)
                {
                    $em->remove($produitOrganisation);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('information','produit bien supprimé.');
                }else{
                    $this->get('session')->getFlashBag()->add('information','Echec, ce produit est déjà dans une commande');
                }
                
                return $this->redirect($this->generateUrl('users_adminuser_produit_organisation', array('id'=>$produitOrganisation->getOrganisation()->getId())));
            }
        }
        $this->get('session')->getFlashBag()->add('supprime_contenu',$produitOrganisation->getId());
        $this->get('session')->getFlashBag()->add('supprime_contenu',$produitOrganisation->getProduit()->getNom());
        return $this->redirect($this->generateUrl('users_adminuser_produit_organisation', array('id'=>$produitOrganisation->getOrganisation()->getId())));
    }
}