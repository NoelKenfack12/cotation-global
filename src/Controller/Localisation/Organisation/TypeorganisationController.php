<?php

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

class TypeorganisationController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function typeorganisation(Request $request, EntityManagerInterface $em)
    {
        $typeorganisation = new Typeorganisation();
	    $formTypeOrg = $this->createForm(TypeorganisationType::class, $typeorganisation);

        if($request->getMethod() == 'POST')
        {
            $formTypeOrg->handleRequest($request);
            if ($formTypeOrg->isValid()){
                $em->persist($typeorganisation);
                $em->flush();
            }else{
                $this->get('session')->getFlashBag()->add('information','Données invalides.');
            }
        }else{
        $this->get('session')->getFlashBag()->add('information','Données en mode get.');
        }

        $liste_typeOrg = $em->getRepository(Typeorganisation::class)
	                        ->FindAll();

        return $this->render('Theme/Users/Adminuser/Organisation/typeorganisation.html.twig', 
        array('formTypeOrg'=>$formTypeOrg->createView(), 'liste_typeOrg'=>$liste_typeOrg));
    }
}