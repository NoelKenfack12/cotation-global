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

    public function cotationorganisation($id)
    {
        return $this->render('Theme/Users/Adminuser/Panier/cotationorganisation.html.twig');
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

    public function cotationdescription(EntityManagerInterface $em, GeneralServicetext $service, Panier $panier)
    {
        
        $liste_contact = $em->getRepository(Contact::class)
                        ->findAll();
        $liste_pays = $em->getRepository(Pays::class)
                        ->findAll();
        return $this->render('Theme/Users/Adminuser/Panier/cotationdescription.html.twig', 
        array('organisation'=>$panier->getOrganisation(), 'panier'=>$panier, 'liste_pays'=>$liste_pays, 
        'liste_contact'=>$liste_contact));
    }

    public function tarificationcotation()
    {
        return $this->render('Theme/Users/Adminuser/Panier/tarificationcotation.html.twig');
    }
}