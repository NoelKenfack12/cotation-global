<?php
namespace App\Controller\Produit\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;

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

    public function nouvellecotation($id)
    {
        return $this->render('Theme/Users/Adminuser/Panier/nouvellecotation.html.twig');
    }

    public function cotationdescription()
    {
        return $this->render('Theme/Users/Adminuser/Panier/cotationdescription.html.twig');
    }

    public function tarificationcotation()
    {
        return $this->render('Theme/Users/Adminuser/Panier/tarificationcotation.html.twig');
    }
}