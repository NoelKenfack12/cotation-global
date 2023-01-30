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
use Doctrine\ORM\EntityManagerInterface;

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

    public function gestionorganisations($id)
    {
        return $this->render('Theme/Users/Adminuser/Organisation/gestionorganisations.html.twig');
    }
}