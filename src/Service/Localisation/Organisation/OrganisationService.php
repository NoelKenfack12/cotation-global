<?php
namespace App\Service\Localisation\Organisation;

use App\Service\Servicetext\GeneralServicetext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Entity\Localisation\Organisation\Organisation;
use App\Entity\Produit\Produit\Panier;
use Doctrine\ORM\EntityManagerInterface;

class OrganisationService{
    
    private $helperService;
    private $_entityManager;

    public function __construct(EntityManagerInterface $entityManager, GeneralServicetext $helperService)
    {
        $this->helperService = $helperService;
        $this->_entityManager = $entityManager;
    }

    public function editDataCommande(Panier $panier, Organisation $organisation)
    {
        $numberPanier = $this->_entityManager->getRepository(Panier::class)
                           ->countPanierOrganisation($organisation->getId());
        $numberCommande = $this->helperService->editNumberCommande($numberPanier + 1);

        $reference = 'GI'.$organisation->getVille()->getAbbreviation().''.$numberCommande;

        $clientNumber = $panier->getTypeorganisation()->getCode().''.$numberCommande.'/'.substr("".date('Y'), -2);

        $panier->setReference($reference);
        $panier->setClientNumber($clientNumber);
        $this->_entityManager->flush();
    }
}