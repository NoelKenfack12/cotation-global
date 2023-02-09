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

class PanierController extends AbstractController
{
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

    public function cotationdescription(EntityManagerInterface $em, Organisation $organisation)
    {
        if(isset($_POST['typeserviceorg'], $_POST['serviceorg']))
        {
            $typeserviceorg = $em->getRepository(Typeorganisation::class)
                                 ->find($_POST['typeserviceorg']);

            $serviceorgTab = explode('_', $_POST['serviceorg']);
            if(count($serviceorgTab) != 2)
                exit;

            $serviceorg = $em->getRepository(Serviceorganisation::class)
                                 ->find($serviceorgTab[1]);

            $dataTab = array();
            if($typeserviceorg != null and $serviceorg != null)
            {
                foreach($serviceorg->getForminputs() as $forminput)
                {
                    if(isset($_POST['required-input-'.$forminput->getId()]))
                    {
                        $arrayTab = array();
                        $arrayTab['libelle'] = $forminput->getLibelle();
                        $arrayTab['valeur'] = $_POST['required-input-'.$forminput->getId()];

                        $dataTab[] = $arrayTab;
                    }
                }

                print_r($dataTab);
                exit;
            }
        }
        return $this->render('Theme/Users/Adminuser/Panier/cotationdescription.html.twig', 
        array('organisation'=>$organisation));
    }

    public function tarificationcotation()
    {
        return $this->render('Theme/Users/Adminuser/Panier/tarificationcotation.html.twig');
    }
}