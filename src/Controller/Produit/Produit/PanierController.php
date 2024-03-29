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
use App\Entity\Produit\Parametre\OptionFormInput;
use App\Service\Localisation\Organisation\OrganisationService;
use App\Entity\Produit\Service\Pays;
use App\Entity\Users\User\Contact;
use App\Entity\Produit\Produit\Forfaitpanier;
use App\Entity\Produit\Produit\Typeproduit;
use App\Entity\Produit\Produit\ProduitOrganisation;
use App\Entity\Produit\Produit\Produitpanier;
use App\Service\AfPdf\PDF;

class PanierController extends AbstractController
{
    private OrganisationService $organisationService;
    public function __construct(OrganisationService $organisationService)
    {
        $this->organisationService = $organisationService;
    }

    public function cotationglobal(GeneralServicetext $service, Request $request, EntityManagerInterface $em, $page)
    {
        
        $panier_organisation = $em->getRepository(Panier::class)
                                   ->findPanierAdminPagine($page, 12);

        return $this->render('Theme/Users/Adminuser/Panier/cotationglobal.html.twig', 
        array('panier_organisation'=>$panier_organisation, 'nombrepage' => ceil(count($panier_organisation)/12), 'page'=>$page));
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
                $panier = $em->getRepository(Panier::class)
                             ->find($panierId);
                
                if($panier != null)
                {
                    if(isset($_POST['amountPanier']))
                    {
                        $panier->setMontant($_POST['amountPanier']);
                    }

                    $panier->setStatus('pending');
                    $em->flush();
                }
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
                {
                    $this->get('session')->getFlashBag()->add('information','Données invalides. Aucun service sélectionné');
                    return $this->redirect($this->generateUrl('users_adminuser_nouvelle_cotation', array('id'=>$organisation->getId())));
                }

                $serviceorg = $em->getRepository(Serviceorganisation::class)
                                 ->find($serviceorgTab[1]);

                $dataTab = array();
                $failedRequired = false;

                if($typeserviceorg != null and $serviceorg != null)
                {
                    //echo $typeserviceorg->getName();
                    //echo $serviceorg->getNom();

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

                                if($forminput->getTypeInput() == 'select')
                                {
                                    $optionFormInput = $em->getRepository(OptionFormInput::class)
                                                          ->find($currentValue);
                                    if($optionFormInput != null)
                                    {
                                        $arrayTab['valeur'] = $optionFormInput->getLibelle();
                                    }else{
                                        $arrayTab['valeur'] = $currentValue;
                                    }
                                }else{
                                    $arrayTab['valeur'] = $currentValue;
                                }
                                
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

                                if($forminput->getTypeInput() == 'select')
                                {
                                    $optionFormInput = $em->getRepository(OptionFormInput::class)
                                                          ->find($currentValue);
                                    if($optionFormInput != null)
                                    {
                                        $arrayTab['valeur'] = $optionFormInput->getLibelle();
                                    }else{
                                        $arrayTab['valeur'] = $currentValue;
                                    }
                                }else{
                                    $arrayTab['valeur'] = $currentValue;
                                }
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
                        $panier->setUser($this->getUser());
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

                        $session = $this->get('session');
                        $session->set('_myCard',$panier->getId());

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
            }else{
                $this->get('session')->getFlashBag()->add('information','Données invalides. Service non spécifié');
                return $this->redirect($this->generateUrl('users_adminuser_nouvelle_cotation', array('id'=>$organisation->getId())));
            }
        }else{
            $this->get('session')->getFlashBag()->add('information','Données invalides. Type de service non spécifié');
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
                            ->setOrganisation($panier->getOrganisation())
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

                            if($forminput->getTypeInput() == 'select')
                            {
                                $optionFormInput = $em->getRepository(OptionFormInput::class)
                                                        ->find($currentValue);
                                if($optionFormInput != null)
                                {
                                    $arrayTab['valeur'] = $optionFormInput->getLibelle();
                                }else{
                                    $arrayTab['valeur'] = $currentValue;
                                }
                            }else{
                                $arrayTab['valeur'] = $currentValue;
                            }

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

                            if($forminput->getTypeInput() == 'select')
                            {
                                $optionFormInput = $em->getRepository(OptionFormInput::class)
                                                        ->find($currentValue);
                                if($optionFormInput != null)
                                {
                                    $arrayTab['valeur'] = $optionFormInput->getLibelle();
                                }else{
                                    $arrayTab['valeur'] = $currentValue;
                                }
                            }else{
                                $arrayTab['valeur'] = $currentValue;
                            }

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

                    return $this->redirect($this->generateUrl('users_adminuser_tarification_cotation', array('id'=>$panier->getId(), 'addproduct'=>1)));
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

    public function tarificationcotation(EntityManagerInterface $em, Panier $panier, $addproduct)
    {
        $liste_typeProd = $em->getRepository(Typeproduit::class)
                             ->myfindAll();
        
        if($addproduct == 1)
        {
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
        }
        
        $panier->setEm($em);
        
        $formsupp = $this->createFormBuilder()->getForm();

        foreach($liste_typeProd as $typeProd)
        {
            $currentProduits = $panier->getProduitOrganisationType($typeProd);

            $totalSection = 0;
            if(count($currentProduits) > 0)
            {
                foreach($currentProduits as $produitpanier)
                {
                    $totalItem = ($produitpanier->getMontant() * $produitpanier->getQuantite()) + ($produitpanier->getTaxe() * $produitpanier->getQuantite());
                    $totalSection = $totalSection + $totalItem;
                }
            }

            $oldForfaitpanier = $em->getRepository(Forfaitpanier::class)
                                   ->FindOneBy(array('typeproduit'=>$typeProd, 'panier'=>$panier), array('date'=>'desc'), 1);
            if($oldForfaitpanier != null)
            {
                $oldForfaitpanier->setMontant($totalSection);
            }else{
                $faitpanier = new Forfaitpanier();
                $faitpanier->setPanier($panier);
                $faitpanier->setTypeproduit($typeProd);
                $faitpanier->setMontant($totalSection);
                $em->persist($faitpanier);
            }
            $em->flush();
        }

        return $this->render('Theme/Users/Adminuser/Panier/tarificationcotation.html.twig',
        array('organisation'=>$panier->getOrganisation(), 'panier'=>$panier, 'liste_typeProd'=>$liste_typeProd,
        'formsupp'=>$formsupp->createView()));
    }

    public function impressionfichecotation(EntityManagerInterface $em, GeneralServicetext $service, Panier $panier)
    {
        $liste_typeProd = $em->getRepository(Typeproduit::class)
                             ->myfindAll();
        $panier->setEm($em);

        $pdf = new PDF('P','mm','A4');
        $addpagedeux = false;

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $page = 1;

        if($panier->getContact() != null)
        {
            $clientName = $panier->getContact()->getNom();
        }else{
            $clientName = 'Noel K.';
        }
        $pdf->myheader('Facture N: '.$panier->getReference().' du: '.$panier->getDate()->format('Y-m-d H:i:s'),$clientName,'N. Client: '.$panier->getClientNumber());

        $liste_input_one = $em->getRepository(PanierInput::class)
                              ->findInputPanier($panier->getId(), 'indentification');
        $liste_input_two = $em->getRepository(PanierInput::class)
                              ->findInputPanier($panier->getId(), 'detailcontenu');

        $currentPosition = $pdf->GetY() +5;
        $pdf->SetY($currentPosition);
        foreach($liste_input_one as $input)
        {
            $pdf->addLeftHeding($service->retireAccent($input->getForminput()->name(25)), $service->retireAccent($input->name(35)));
        }

        $currentYIndex = $pdf->GetY();

        $pdf->setY($currentPosition);
        foreach($liste_input_two as $input)
        {
            $pdf->addRightHeding($service->retireAccent($input->getForminput()->name(18)), $service->retireAccent($input->name(35)));
        }

        if($panier->getPaysorigin() != null)
        {
            $pdf->addRightHeding('Pays d\'origin', $panier->getPaysorigin()->getNom());
        }
        if($panier->getPaysprovenance() != null)
        {
            $pdf->addRightHeding('Pays de provenance', $panier->getPaysprovenance()->getNom());
        }

        if($pdf->GetY() < $currentYIndex)
        {
            $pdf->setY($currentYIndex);
        }

        $pdf->headerdescription();

        
        $nbligne = 0;
        $total = 0;
        $nbarticle = 0;
        
        foreach($liste_typeProd as $typeproduit)
        {
            $pdf->addProduct($typeproduit->getNom(),'', '', '', '', true);

            $currentProduits = $panier->getProduitOrganisationType($typeproduit);
            $totalSection = 0;
            foreach($currentProduits as $produitpanier)
            {
                $totalRow = $produitpanier->getMontant()*$produitpanier->getQuantite() + $produitpanier->getTaxe()*$produitpanier->getQuantite();
                $total = $total + $totalRow;

                $totalSection = $totalSection + $totalRow;
                
                $nbarticle = $nbarticle + 1; 
                $nomProduit = $produitpanier->getProduitOrganisation()->getProduit()->name(45);
                $pdf->addProduct($service->retireAccent($nomProduit),$produitpanier->getMontant(), $produitpanier->getQuantite(), $produitpanier->getTaxe(), $totalRow);

                if($nbligne >= 30 and $nbligne <= 36)
                {
                    $addpagedeux = true;
                    $y = $pdf->GetY();
                    if ($y  >= 250){
                        $pdf->AddPage();
                        $pdf->setY(15);
                        $addpagedeux = false;
                    }
                }else{
                    $addpagedeux = false;
                }

                $nbligne++;
            }

            if($nbligne >= 30 and $nbligne <= 36)
            {
                $addpagedeux = true;
                $y = $pdf->GetY();
                if ($y  >= 250){
                    $pdf->AddPage();
                    $pdf->setY(15);
                    $addpagedeux = false;
                }
            }else{
                $addpagedeux = false;
            }

            $nbligne++;

            $pdf->addProduct('Total '.$typeproduit->getNom(),'', '', '', $totalSection, true);

            if($nbligne >= 30 and $nbligne <= 36)
            {
                $addpagedeux = true;
                $y = $pdf->GetY();
                if ($y  >= 250){
                    $pdf->AddPage();
                    $pdf->setY(15);
                    $addpagedeux = false;
                }
            }else{
                $addpagedeux = false;
            }

            $nbligne++;
        }
        $pdf->addProduct('Total ','', '', '', $total, true);

        if($addpagedeux == true)
        {
            $pdf->AddPage();
            $addpagedeux = false;
        }

        $pdf->statistique('0'.$nbarticle, '0,00',$total, 'Livraison : Yaoundé','Quartier: Biyem-Assi', 'Tel: 693839823',$total,$page, $clientName,$panier->getOrganisation()->getNom(),'La Direction De Transit ');
        $pdf->Output();
    }

    public function downloadForfaitcotation(EntityManagerInterface $em, GeneralServicetext $service, Panier $panier)
    {
        $liste_typeProd = $em->getRepository(Typeproduit::class)
                             ->myfindAll();
        $panier->setEm($em);

        $pdf = new PDF('P','mm','A4');
        $addpagedeux = false;

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $page = 1;

        if($panier->getContact() != null)
        {
            $clientName = $panier->getContact()->getNom();
        }else{
            $clientName = 'Noel K.';
        }
        $pdf->myheader('Facture N: '.$panier->getReference().' du: '.$panier->getDate()->format('Y-m-d H:i:s'),$clientName,'N. Client: '.$panier->getClientNumber());

        $liste_input_one = $em->getRepository(PanierInput::class)
                              ->findInputPanier($panier->getId(), 'indentification');
        $liste_input_two = $em->getRepository(PanierInput::class)
                              ->findInputPanier($panier->getId(), 'detailcontenu');

        $currentPosition = $pdf->GetY() +5;
        $pdf->SetY($currentPosition);
        foreach($liste_input_one as $input)
        {
            $pdf->addLeftHeding($service->retireAccent($input->getForminput()->name(25)), $service->retireAccent($input->name(35)));
        }

        $currentYIndex = $pdf->GetY();

        $pdf->setY($currentPosition);
        foreach($liste_input_two as $input)
        {
            $pdf->addRightHeding($service->retireAccent($input->getForminput()->name(18)), $service->retireAccent($input->name(35)));
        }

        if($panier->getPaysorigin() != null)
        {
            $pdf->addRightHeding('Pays d\'origin', $panier->getPaysorigin()->getNom());
        }
        if($panier->getPaysprovenance() != null)
        {
            $pdf->addRightHeding('Pays de provenance', $panier->getPaysprovenance()->getNom());
        }

        if($pdf->GetY() < $currentYIndex)
        {
            $pdf->setY($currentYIndex);
        }

        $pdf->headerdescription();

        
        $nbligne = 0;
        $total = 0;
        $nbarticle = 0;
        

            $liste_forfait = $em->getRepository(Forfaitpanier::class)
                                ->findBy(array('panier'=>$panier));

            $totalSection = 0;
            foreach($liste_forfait as $forfaitpanier)
            {
                $totalRow = $forfaitpanier->getMontant();
                $total = $total + $totalRow;
                $nbarticle = $nbarticle + 1; 
                $nomProduit = $forfaitpanier->getTypeproduit()->getNom();
                $pdf->addProduct($service->retireAccent($nomProduit),$forfaitpanier->getMontant(), 1, 0, $totalRow);

                if($nbligne >= 30 and $nbligne <= 36)
                {
                    $addpagedeux = true;
                    $y = $pdf->GetY();
                    if ($y  >= 250){
                        $pdf->AddPage();
                        $pdf->setY(15);
                        $addpagedeux = false;
                    }
                }else{
                    $addpagedeux = false;
                }

                $nbligne++;
            }

        $pdf->addProduct('Total ','', '', '', $total, true);

        if($addpagedeux == true)
        {
            $pdf->AddPage();
            $addpagedeux = false;
        }

        $pdf->statistique('0'.$nbarticle, '0,00',$total, 'Livraison : Yaoundé','Quartier: Biyem-Assi', 'Tel: 693839823',$total,$page, $clientName,$panier->getOrganisation()->getNom(),'La Direction De Transit ');
        $pdf->Output();
    }

    public function editcommande()
    {
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = 0;
        }
        
        $em = $this->getDoctrine()->getManager();
        $prodpan = $em->getRepository(Produitpanier::class)
                        ->find($id);
        if($prodpan != null)
        {
            if(isset($_GET['quantite']))
            {
                $quantite = $_GET['quantite'];
                $prodpan->setQuantite($quantite);
            }
            if(isset($_GET['montant']))
            {
                $montant = $_GET['montant'];
                $prodpan->setMontant($montant);
            }
            if(isset($_GET['taxe']))
            {
                $taxe = $_GET['taxe'];
                $prodpan->setTaxe($taxe);
            }

            $em->flush();
            echo 1;
            exit;
        }else{
            echo 0;
            exit;
        }
    }

    public function deleteproduitpanier(Produitpanier $produitpanier, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        $panier = $produitpanier->getPanier();
        if ($request->getMethod() == 'POST'){
            $formsupp->handleRequest($request);
            if ($formsupp->isValid()){
                $em->remove($produitpanier);
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','Produit bien supprimé.');
                return $this->redirect($this->generateUrl('users_adminuser_tarification_cotation', array('id'=>$panier->getId())));
            }
        }
        $this->get('session')->getFlashBag()->add('supprime_contenu',$produitpanier->getId());
        $this->get('session')->getFlashBag()->add('supprime_contenu',$produitpanier->getProduitOrganisation()->getProduit()->getNom());
        return $this->redirect($this->generateUrl('users_adminuser_tarification_cotation', array('id'=>$panier->getId())));
    }

    public function addnewProduitPanier(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $panier = $em->getRepository(Panier::class)
                        ->find($id);
        if($panier != null)
        {
        if($request->getMethod() == 'POST'){
            
            if(isset($_POST['produitOrganisationId']))
            {
                $produitOrganisation = $em->getRepository(ProduitOrganisation::class)->find($_POST['produitOrganisationId']);

                if($produitOrganisation != null)
                {
                    $oldProdPan = $em->getRepository(Produitpanier::class)
                                     ->findOneBy(array('produitOrganisation'=>$produitOrganisation, 'panier'=>$panier), array('date'=>'desc'), 1);
                    if($oldProdPan == null)
                    {
                        $produitpanier = new Produitpanier();
                        $produitpanier->setProduitOrganisation($produitOrganisation)
                                    ->setPanier($panier)
                                    ->setMontant($produitOrganisation->getMontant());
                        
                        $em->persist($produitpanier);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('information','Produit ajouté avec succès !');
                    }
                }
            }
            return $this->redirect($this->generateUrl('users_adminuser_tarification_cotation', array('id'=>$panier->getId())));
        }

        $liste_produitOrganisation = $em->getRepository(ProduitOrganisation::class)
                                      ->findBy(array('organisation'=>$panier->getOrganisation()), array('date'=>'desc'));

        return $this->render('Theme/Produit/Produit/Panier/addnewProduitPanier.html.twig',
        array('panier'=>$panier, 'liste_produitOrganisation'=>$liste_produitOrganisation));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function changestatut(Panier $panier, EntityManagerInterface $em, $position)
    {
        if($position == 'up')
        {
            if($panier->getStatus() == 'active')
            {
                $panier->setStatus('cancel');
                $this->get('session')->getFlashBag()->add('information','Statut du produit changé avec succès !');
            }else if($panier->getStatus() == 'cancel')
            {
                $panier->setStatus('active');
                $this->get('session')->getFlashBag()->add('information','Statut du produit changé avec succès !');
            }
            $em->flush();
        }else if($position == 'corbeille')
        {
            $panier->setStatus('corbeille');
            $em->flush();
        }
        return $this->redirect($this->generateUrl('users_adminuser_cotation_organisation', array('id'=>$panier->getOrganisation()->getId())));
    }

    public function removeCollectionProduit(Panier $panier, EntityManagerInterface $em)
    {
        if(isset($_GET['chaine']))
        {
            $tabProdPan = explode(',', $_GET['chaine']);

            if(count($tabProdPan) > 0)
            {
                $liste_ProdPan = $em->getRepository(Produitpanier::class)
                                    ->findBy(array('panier'=>$panier->getId(),'id'=>$tabProdPan), array('date'=>'desc'));
                foreach($liste_ProdPan as $prodPan)
                {
                    $em->remove($prodPan);
                }
                $em->flush();
                echo 1;
                exit;
            }else{
                echo -1;
                exit;
            }
        }else{
            echo 0;
            exit;
        }
    }

    public function activateStatusPanier(Panier $panier, EntityManagerInterface $em)
    {
        if($panier->getStatus() == 'pending' and $panier->getMontant() > 0)
        {
            $panier->setStatus('active');
            $em->flush();
            $this->get('session')->getFlashBag()->add('information','Statut de la commande changé avec succès !');
        }else{
            $this->get('session')->getFlashBag()->add('information','Echec, Le status de la commande doit être en cours, pour pouvoir être activé !');
        }
        return $this->redirect($this->generateUrl('users_adminuser_cotation_organisation', array('id'=>$panier->getOrganisation()->getId())));
    }
}