<?php
namespace App\Controller\Produit\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;

class TypeproduitController extends AbstractController
{
    public function listetypeproduit(GeneralServicetext $service, Request $request)
    {
        return $this->render('Theme/Users/Adminuser/Produit/listetypeproduit.html.twig');
    }
}