<?php
/* (c) Noel Kenfack <noel.kenfack@yahoo.fr> Février 2015
*/
namespace App\Controller\Localisation\Organisation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Localisation\Organisation\Typeorganisation;
use App\Form\Localisation\Organisation\TypeorganisationType;
use App\Entity\Localisation\Organisation\Organisation;
use App\Form\Localisation\Organisation\OrganisationType;
use App\Entity\Produit\Produit\Panier;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit\Produit\ProduitOrganisation;
use App\Entity\Users\User\Contact;
use App\Entity\Localisation\Organisation\Userorganisation;

class OrganisationController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function listeorganisation(Request $request, EntityManagerInterface $em)
    {
        $typeorganisation = new Typeorganisation();
	    $formTypeOrg = $this->createForm(TypeorganisationType::class, $typeorganisation);

        return $this->render('Theme/Users/Adminuser/Organisation/listeorganisation.html.twig', 
        array('formTypeOrg'=>$formTypeOrg->createView()));
    }

    public function gestionorganisations(Organisation $organisation, EntityManagerInterface $em)
    {
        $amountCotation = $em->getRepository(Panier::class)
                                  ->findAmountCotationOrg($organisation->getId());
        $cotationAnnuler = $em->getRepository(Panier::class)
                                  ->findCotationStatusOrg($organisation->getId(), 'cancel');
        $cotationBruillon = $em->getRepository(Panier::class)
                                  ->findCotationStatusOrg($organisation->getId(), 'pending');
        $cotationActive = $em->getRepository(Panier::class)
                                  ->findCotationStatusOrg($organisation->getId(), 'active');
        $cotationCorbeille = $em->getRepository(Panier::class)
                                  ->findCotationStatusOrg($organisation->getId(), 'corbeille');

        $amountCotationAnnuler = $em->getRepository(Panier::class)
                                  ->findAmountCotationStatusOrg($organisation->getId(), 'cancel');
        $amountCotationActive = $em->getRepository(Panier::class)
                                  ->findAmountCotationStatusOrg($organisation->getId(), 'active');
        $amountCotationCorbeille = $em->getRepository(Panier::class)
                                  ->findAmountCotationStatusOrg($organisation->getId(), 'corbeille');

        $nbProduitOrganisation = $em->getRepository(ProduitOrganisation::class)
                                  ->findProduitOrganisation($organisation->getId(), 'all');
        $nbSelectProduitOrganisation = $em->getRepository(ProduitOrganisation::class)
                                  ->findProduitOrganisation($organisation->getId(), 'select');

        $nbAdmin = $em->getRepository(Userorganisation::class)
                       ->findUserOrganisation($organisation->getId());

        $nbClient = $em->getRepository(Contact::class)
                        ->countContactOrganisation($organisation->getId());

        return $this->render('Theme/Users/Adminuser/Organisation/gestionorganisations.html.twig', 
        array('organisation'=>$organisation, 'amountCotation'=>$amountCotation, 'cotationAnnuler'=>$cotationAnnuler, 
        'cotationBruillon'=>$cotationBruillon, 'cotationActive'=>$cotationActive, 'cotationCorbeille'=>$cotationCorbeille,
        'amountCotationAnnuler'=>$amountCotationAnnuler, 'amountCotationActive'=>$amountCotationActive, 'amountCotationCorbeille'=>$amountCotationCorbeille,
        'nbProduitOrganisation'=>$nbProduitOrganisation, 'nbSelectProduitOrganisation'=>$nbSelectProduitOrganisation, 'nbAdmin'=>$nbAdmin, 'nbClient'=>$nbClient));
    }

    public function nouvelleorganisation(Request $request, EntityManagerInterface $em)
    {
        $organisation = new Organisation();
	    $formOrg = $this->createForm(OrganisationType::class, $organisation);

        if($request->getMethod() == 'POST')
        {
            $formOrg->handleRequest($request);
            if($formOrg->isValid()){
                $em->persist($organisation);
                $em->flush();

                $this->get('session')->getFlashBag()->add('information','Création de la nouvelle organisation effectuée avec succès');
            }else{
                $this->get('session')->getFlashBag()->add('information','Données invalides.');
            }
        }
        return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
    }

    public function modificationorganisation(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $organisation = $em->getRepository(Organisation::class)
                        ->find($id);
        if($organisation != null)
        {
        $form = $this->createForm(OrganisationType::class, $organisation);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
            }else{
                $this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
            }
            return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
        }
        return $this->render('Theme/Users/Adminuser/Organisation/modificationorganisation.html.twig',
        array('form'=>$form->createView(),'organisation'=>$organisation));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function deleteorganisation(Organisation $organisation, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
            $formsupp->handleRequest($request);
            if ($formsupp->isValid()){
                $em->remove($organisation);
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','organisation bien supprimée.');
                return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
            }
        }
        $this->get('session')->getFlashBag()->add('supprime_organisation',$organisation->getId());
        $this->get('session')->getFlashBag()->add('supprime_organisation',$organisation->getNom());
        return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
    }
}