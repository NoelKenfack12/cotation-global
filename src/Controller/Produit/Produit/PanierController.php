<?php
namespace App\Controller\Produit\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;
use App\Entity\Localisation\Organisation\Organisation;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Localisation\Organisation\Typeorganisation;
use App\Entity\Localisation\Organisation\Serviceorganisation;
use App\Entity\Produit\Produit\Panier;
use App\Entity\Produit\Produit\PanierInput;
use App\Entity\Produit\Parametre\Forminput;
use App\Service\Localisation\Organisation\OrganisationService;
use App\Entity\Produit\Service\Pays;
use App\Entity\Users\User\Contact;
use App\Entity\Produit\Produit\Typeproduit;
use App\Entity\Produit\Produit\ProduitOrganisation;
use App\Entity\Produit\Produit\Produitpanier;

class PanierController extends AbstractController
{
    private OrganisationService $organisationService;
    public function __construct(OrganisationService $organisationService)
    {
        $this->organisationService = $organisationService;
    }

    public function cotationglobal(GeneralServicetext $service, Request $request)
    {
        return $this->render('Theme/Users/Adminuser/Panier/cotationglobal.html.twig');
    }

    public function cotationorganisation(Organisation $organisation, EntityManagerInterface $em, Request $request, $page)
    {
        $panier_organisation = $em->getRepository(Panier::class)
                                   ->findPanierOrganisationPagine($organisation->getId(), $page, 12);
        $panierId = 0;
        if($request->getMethod() == 'POST')
        {
            if(isset($_POST['panierId'])){
                $panierId = $_POST['panierId'];
                $this->get('session')->getFlashBag()->add('generationFichier','La contation a été enregistrée avec succès !');
            }
        }
        return $this->render('Theme/Users/Adminuser/Panier/cotationorganisation.html.twig', 
        array('organisation'=>$organisation, 'panier_organisation'=>$panier_organisation, 'page'=>$page, 
        'nombrepage' => ceil(count($panier_organisation)/12), 'panierId'=>$panierId));
    }

    public function nouvellecotation(Organisation $organisation)
    {
        return $this->render('Theme/Users/Adminuser/Panier/nouvellecotation.html.twig', 
        array('organisation'=>$organisation));
    }

    public function createcotationIdentification(EntityManagerInterface $em, GeneralServicetext $service, Organisation $organisation)
    {
        if(isset($_POST['typeserviceorg']))
        {
            $typeserviceorg = $em->getRepository(Typeorganisation::class)
                                ->find($_POST['typeserviceorg']);

            if($typeserviceorg != null and isset($_POST['serviceorg_'.$typeserviceorg->getId()]))
            {
                $serviceorgTab = explode('_', $_POST['serviceorg_'.$typeserviceorg->getId()]);
                if(count($serviceorgTab) != 2)
                    exit;

                    
                $serviceorg = $em->getRepository(Serviceorganisation::class)
                                ->find($serviceorgTab[1]);

                $dataTab = array();
                $failedRequired = false;

                
                if($typeserviceorg != null and $serviceorg != null)
                {
                    echo $typeserviceorg->getName();
                    echo $serviceorg->getNom();

                    foreach($serviceorg->getForminputs() as $forminput)
                    {
                        
                        if(isset($_POST['required-input-'.$typeserviceorg->getId().'service'.$serviceorg->getId().'-input'.$forminput->getId()]))
                        {
                            $currentValue = $_POST['required-input-'.$typeserviceorg->getId().'service'.$serviceorg->getId().'-input'.$forminput->getId()];
                            if(strlen($currentValue) > 0)
                            {
                                $arrayTab = array();
                                $arrayTab['id'] = $forminput->getId();
                                $arrayTab['libelle'] = $forminput->getLibelle();
                                $arrayTab['valeur'] = $currentValue;

                                $dataTab[] = $arrayTab;
                            }else{
                                $failedRequired = true;
                                break;
                            }
                        }else if(isset($_POST['optional-input-'.$typeserviceorg->getId().'service'.$serviceorg->getId().'-input'.$forminput->getId()]))
                        {
                            $currentValue = $_POST['optional-input-'.$typeserviceorg->getId().'service'.$serviceorg->getId().'-input'.$forminput->getId()];

                            if(strlen($currentValue) > 0)
                            {
                                $arrayTab = array();
                                $arrayTab['id'] = $forminput->getId();
                                $arrayTab['libelle'] = $forminput->getLibelle();
                                $arrayTab['valeur'] = $currentValue;
                                $dataTab[] = $arrayTab;
                            }
                        }
                    }

                    //print_r($dataTab);
                    //exit;

                    if($failedRequired == false)
                    {
                        $panier = new Panier();
                        $panier->setStatus('pending');
                        $panier->setTypeorganisation($typeserviceorg);
                        $panier->setServiceorganisation($serviceorg);
                        $panier->setOrganisation($organisation);
                        $em->persist($panier);
                        for($i = 0; $i < count($dataTab); $i++)
                        {
                            $forminput = $em->getRepository(Forminput::class)
                                            ->find($dataTab[$i]['id']);
                            if($forminput != null)
                            {
                                $panierInput =  new PanierInput();
                                $panierInput->setPanier($panier);
                                $panierInput->setForminput($forminput);
                                $panierInput->setValeur($dataTab[$i]['valeur']);
                                $em->persist($panierInput);
                            }
                        }
                        $em->flush();

                        $this->organisationService->editDataCommande($panier, $organisation);

                        return $this->redirect($this->generateUrl('users_adminuser_cotation_description', array('id'=>$panier->getId())));
                    }else{
                        $this->get('session')->getFlashBag()->add('information','Données invalides. Champ requis non renseigné.');
                        return $this->redirect($this->generateUrl('users_adminuser_nouvelle_cotation', array('id'=>$organisation->getId())));
                    }
                }else{
                    $this->get('session')->getFlashBag()->add('information','Données invalides. Service introuvable');
                    return $this->redirect($this->generateUrl('users_adminuser_nouvelle_cotation', array('id'=>$organisation->getId())));
                }
            }

            
        }else{
            $this->get('session')->getFlashBag()->add('information','Données invalides. Service non spécifié');
            return $this->redirect($this->generateUrl('users_adminuser_nouvelle_cotation', array('id'=>$organisation->getId())));
        }
    }

    public function cotationdescription(EntityManagerInterface $em, GeneralServicetext $service, Request $request, Panier $panier)
    {
        $form_required = $em->getRepository(Forminput::class)
                            ->findBy(array('serviceorganisation'=>$panier->getServiceorganisation(), 'required'=>true, 'position'=>'detailcontenu'));

        $form_optional = $em->getRepository(Forminput::class)
                            ->findBy(array('serviceorganisation'=>$panier->getServiceorganisation(), 'required'=>false, 'position'=>'detailcontenu'));

        if($request->getMethod() == 'POST')
        {
            if(isset($_POST['paysorigine']))
            {
                $paysorigine = $em->getRepository(Pays::class)
                                  ->find($_POST['paysorigine']);
                if($paysorigine != null)
                {
                    $panier->setPaysorigin($paysorigine);
                }
            }

            if(isset($_POST['paysprovenance']))
            {
                $paysprovenance = $em->getRepository(Pays::class)
                                  ->find($_POST['paysprovenance']);
                if($paysprovenance != null)
                {
                    $panier->setPaysprovenance($paysprovenance);
                }
            }

            $valid = true;
            if(isset($_POST['infosclient']) and $_POST['infosclient'] != 0)
            {
                if($_POST['infosclient'] == 1 and isset($_POST['clientId']))
                {
                    $contact = $em->getRepository(Contact::class)
                                 ->find($_POST['clientId']);
                    if($contact != null)
                    {
                        $panier->setContact($contact);
                    }else{
                        $this->get('session')->getFlashBag()->add('information','Données invalides. Client non identifié');
                        $valid = false;
                    }
                }else if($_POST['infosclient'] == 2 and isset($_POST['username'], $_POST['telephone'], $_POST['email']))
                {
                    $contact = new Contact();
                    $contact->setNom($_POST['username'])
                            ->setTelephone($_POST['telephone'])
                            ->setEmail($_POST['email']);
                    $em->persist($contact);
                    $panier->setContact($contact);
                }else{
                    $this->get('session')->getFlashBag()->add('information','Données invalides. Client non spécifié');
                    $valid = false;
                }
            }else{
                $valid = false;
                $this->get('session')->getFlashBag()->add('information','Données invalides. Fraude identification client');
            }

            if($valid == true)
            {
                $dataTab = array();
                foreach($form_required as $forminput)
                {
                    if(isset($_POST['required-input-'.$forminput->getId()]))
                    {
                        $currentValue = $_POST['required-input-'.$forminput->getId()];
                        if(strlen($currentValue) > 0)
                        {
                            $arrayTab = array();
                            $arrayTab['id'] = $forminput->getId();
                            $arrayTab['libelle'] = $forminput->getLibelle();
                            $arrayTab['valeur'] = $currentValue;

                            $dataTab[] = $arrayTab;
                        }else{
                            $valid = false;
                            break;
                        }
                        
                    }else{
                        $this->get('session')->getFlashBag()->add('information','Données invalides. Champ requis non renseigné');
                        $valid = false;
                        break; 
                    }
                }

                foreach($form_optional as $forminput)
                {
                    if(isset($_POST['optional-input-'.$forminput->getId()]))
                    {
                        $currentValue = $_POST['optional-input-'.$forminput->getId()];
                        if(strlen($currentValue) > 0)
                        {
                            $arrayTab = array();
                            $arrayTab['id'] = $forminput->getId();
                            $arrayTab['libelle'] = $forminput->getLibelle();
                            $arrayTab['valeur'] = $currentValue;

                            $dataTab[] = $arrayTab;
                        }
                    }
                }

                if($valid == true)
                {
                    for($i = 0; $i < count($dataTab); $i++)
                    {
                        $forminput = $em->getRepository(Forminput::class)
                                        ->find($dataTab[$i]['id']);
                        if($forminput != null)
                        {
                            $panierInput =  new PanierInput();
                            $panierInput->setPanier($panier);
                            $panierInput->setForminput($forminput);
                            $panierInput->setValeur($dataTab[$i]['valeur']);
                            $em->persist($panierInput);
                        }
                    }
                    $em->flush();

                    return $this->redirect($this->generateUrl('users_adminuser_tarification_cotation', array('id'=>$panier->getId())));
                }
            }
        }

        $liste_contact = $em->getRepository(Contact::class)
                        ->findAll();
        $liste_pays = $em->getRepository(Pays::class)
                        ->findAll();
        return $this->render('Theme/Users/Adminuser/Panier/cotationdescription.html.twig', 
        array('organisation'=>$panier->getOrganisation(), 'panier'=>$panier, 'liste_pays'=>$liste_pays, 
        'liste_contact'=>$liste_contact, 'form_required'=>$form_required, 'form_optional'=>$form_optional));
    }

    public function tarificationcotation(EntityManagerInterface $em, Panier $panier)
    {
        $liste_typeProd = $em->getRepository(Typeproduit::class)
                             ->myfindAll();
        $produit_organisation = $em->getRepository(ProduitOrganisation::class)
                                  ->FindBy(array('organisation'=>$panier->getOrganisation(), 'selectDefault'=>true));
        foreach($produit_organisation as $produitorganisation)
        {
            $oldProduitpanier = $em->getRepository(Produitpanier::class)
                                  ->FindOneBy(array('produitOrganisation'=>$produitorganisation, 'panier'=>$panier), array('date'=>'desc'), 1);
            
            if($oldProduitpanier == null)
            {
                $produitpanier = new Produitpanier();
                $produitpanier->setProduitOrganisation($produitorganisation)
                              ->setPanier($panier)
                              ->setMontant($produitorganisation->getMontant());
                
                $em->persist($produitpanier);
            }
            $em->flush();
        }

        $panier->setEm($em);

        return $this->render('Theme/Users/Adminuser/Panier/tarificationcotation.html.twig',
        array('organisation'=>$panier->getOrganisation(), 'panier'=>$panier, 'liste_typeProd'=>$liste_typeProd));
    }
}