<?php
namespace App\Controller\Produit\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;

class ProduitController extends AbstractController
{
    public function servicesorganisation(GeneralServicetext $service, Request $request)
    {
        return $this->render('Theme/Users/Adminuser/Produitorganisation/servicesorganisation.html.twig');
    }
}