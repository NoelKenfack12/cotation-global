<?php

namespace App\Controller\Users\Adminuser;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit\Produit\Panier;
use App\Entity\Produit\Produit\Produit;
use App\Entity\Produit\Produit\ProduitOrganisation;
use App\Entity\Users\User\Contact;
use App\Entity\Localisation\Organisation\Userorganisation;
use App\Entity\Localisation\Organisation\Organisation;

class AccueiladminController extends AbstractController
{
    public function accueiladmin(EntityManagerInterface $em)
    {
        $amountCotation = $em->getRepository(Panier::class)
                                  ->findAmountCotationGlobal();
        $cotationAnnuler = $em->getRepository(Panier::class)
                                  ->findCotationStatusGlobal('cancel');
        $cotationBruillon = $em->getRepository(Panier::class)
                                  ->findCotationStatusGlobal('pending');
        $cotationActive = $em->getRepository(Panier::class)
                                  ->findCotationStatusGlobal('active');
        $cotationCorbeille = $em->getRepository(Panier::class)
                                  ->findCotationStatusGlobal('corbeille');

        $amountCotationAnnuler = $em->getRepository(Panier::class)
                                  ->findAmountCotationStatusGlobal('cancel');
        $amountCotationActive = $em->getRepository(Panier::class)
                                  ->findAmountCotationStatusGlobal('active');
        $amountCotationCorbeille = $em->getRepository(Panier::class)
                                  ->findAmountCotationStatusGlobal('corbeille');

        $nbProduitGlobal = $em->getRepository(Produit::class)
                              ->countProduitGlobal();

        $nbAdmin = $em->getRepository(Userorganisation::class)
                       ->countAdminGlobal();

        $nbClient = $em->getRepository(Contact::class)
                        ->countContactGlobal();

        $nbOrganisation = $em->getRepository(Organisation::class)
                        ->countOrganisationGlobal();

        return $this->render('Theme/Users/Adminuser/Dashboard/accueiladmin.html.twig',
        array('amountCotation'=>$amountCotation, 'cotationAnnuler'=>$cotationAnnuler, 
        'cotationBruillon'=>$cotationBruillon, 'cotationActive'=>$cotationActive, 'cotationCorbeille'=>$cotationCorbeille,
        'amountCotationAnnuler'=>$amountCotationAnnuler, 'amountCotationActive'=>$amountCotationActive, 
        'amountCotationCorbeille'=>$amountCotationCorbeille, 'nbProduitGlobal'=>$nbProduitGlobal, 'nbAdmin'=>$nbAdmin,
        'nbClient'=>$nbClient, 'nbOrganisation'=>$nbOrganisation));
    }
}