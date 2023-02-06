<?php

namespace App\Controller\Localisation\Organisation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Localisation\Organisation\Typeorganisation;
use App\Entity\Localisation\Organisation\Organisation;
use App\Entity\Localisation\Organisation\TypeorgServiceorg;
use App\Entity\Localisation\Organisation\Serviceorganisation;
use App\Form\Localisation\Organisation\TypeorganisationType;
use App\Form\Localisation\Organisation\OrganisationType;
use App\Entity\Localisation\Organisation\TypeorgOrganisation;
use Doctrine\ORM\EntityManagerInterface;

class TypeorganisationController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function typeorganisation(Request $request, EntityManagerInterface $em, $page)
    {
        $typeorganisation = new Typeorganisation();
	    $formTypeOrg = $this->createForm(TypeorganisationType::class, $typeorganisation);

        $organisation = new Organisation();
	    $formOrg = $this->createForm(OrganisationType::class, $organisation);

        if($request->getMethod() == 'POST')
        {
            $formTypeOrg->handleRequest($request);
            if($formTypeOrg->isValid()){
                $em->persist($typeorganisation);
                $em->flush();
            }else{
                $this->get('session')->getFlashBag()->add('information','Données invalides.');
            }
        }

        $liste_typeOrg = $em->getRepository(Typeorganisation::class)
	                        ->FindAll();
        $liste_Org = $em->getRepository(Organisation::class)
                        ->findOrganisationPagine($page, 12);
        $formsupp = $this->createFormBuilder()->getForm(); 
        return $this->render('Theme/Users/Adminuser/Organisation/typeorganisation.html.twig', 
        array('formTypeOrg'=>$formTypeOrg->createView(), 'liste_typeOrg'=>$liste_typeOrg, 'formsupp'=>$formsupp->createView(), 
        'formOrg'=>$formOrg->createView(), 'liste_Org'=>$liste_Org, 'nombrepage' => ceil(count($liste_Org)/12),'page'=>$page));
    }

    public function modificationtypeorg(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $typeorganisation = $em->getRepository(Typeorganisation::class)
                        ->find($id);

        if($typeorganisation != null)
        {
        $form = $this->createForm(TypeorganisationType::class, $typeorganisation);
        if ($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if ($form->isValid()){
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
            }else{
                $this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
            }
            return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
        }
        return $this->render('Theme/Users/Adminuser/Organisation/modificationtypeorg.html.twig',
        array('form'=>$form->createView(),'typeorganisation'=>$typeorganisation));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function deletetypeorg(Typeorganisation $typeorganisation, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
        $formsupp->handleRequest($request);
        if ($formsupp->isValid()){
            $liste_typeorganisation = $typeorganisation->getTypeorgServiceorgs();
            if(count($liste_typeorganisation) > 0){
                $this->get('session')->getFlashBag()->add('information','Action réfusée: Ce type d\'organisation est lié à un ou plusieurs services.');
            }else{
                $em->remove($typeorganisation);
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','Type d\'organisation bien supprimé.');
            }
            return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
        }
        }
        $this->get('session')->getFlashBag()->add('supprime_service',$typeorganisation->getId());
        $this->get('session')->getFlashBag()->add('supprime_service',$typeorganisation->getName());
        return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
    }

    public function joindreserviceorg(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $typeorganisation = $em->getRepository(Typeorganisation::class)
                        ->find($id);

        if($typeorganisation != null)
        {

        if ($request->getMethod() == 'POST'){

            if(isset($_POST['servicelist']))
            {
                $liste_service = array_map('intval', $_POST['servicelist']);

                /*echo count($liste_service);

                var_dump($liste_service);

                exit;*/

                $select_serviceOrg = $em->getRepository(Serviceorganisation::class)
	                                ->FindBy(array('id'=>$liste_service));
                foreach($select_serviceOrg as $serviceOrg)
                {
                    $oldServiceOrganisation = $em->getRepository(TypeorgServiceorg::class)
	                                            ->findOneBy(array('serviceorganisation'=>$serviceOrg, 'typeorganisation'=>$typeorganisation), array('date'=>'desc'),1);
                    if($oldServiceOrganisation == null)
                    {
                        $erviceOrganisation = new TypeorgServiceorg();
                        $erviceOrganisation->setServiceorganisation($serviceOrg);
                        $erviceOrganisation->setTypeorganisation($typeorganisation);
                        $em->persist($erviceOrganisation);
                        $em->flush();
                    }
                }

                $em->flush();
                $this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
            }else{
                $this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
            }
            return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
        }

        $liste_serviceOrg = $em->getRepository(Serviceorganisation::class)
	                        ->FindAll();

        $tabTypeService = $em->getRepository(TypeorgServiceorg::class)
	                        ->findAllServiceType($typeorganisation->getId());
        
        return $this->render('Theme/Users/Adminuser/Organisation/joindreserviceorg.html.twig',
        array('typeorganisation'=>$typeorganisation, 'liste_serviceOrg'=>$liste_serviceOrg, 'tabTypeService'=>$tabTypeService));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function deleteserviceorgtypeorg(TypeorgServiceorg $typeorgServiceorg, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
        $formsupp->handleRequest($request);
        if ($formsupp->isValid()){
            $em->remove($typeorgServiceorg);
            $em->flush();
            $this->get('session')->getFlashBag()->add('information','Type d\'organisation bien supprimé.');
            return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
        }
        }
        $this->get('session')->getFlashBag()->add('supprime_serviceorg',$typeorgServiceorg->getId());
        $this->get('session')->getFlashBag()->add('supprime_serviceorg',$typeorgServiceorg->getServiceorganisation()->getNom());
        return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
    }

    public function joindreorganisation(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $organisation = $em->getRepository(Organisation::class)
                        ->find($id);

        if($organisation != null)
        {

        if ($request->getMethod() == 'POST'){

            if(isset($_POST['typeorglist']))
            {
                $liste_typeorg = array_map('intval', $_POST['typeorglist']);


                $select_typeOrg = $em->getRepository(Typeorganisation::class)
	                                ->FindBy(array('id'=>$liste_typeorg));
                foreach($select_typeOrg as $typeOrg)
                {
                    $oldTypeOrgOrganisation = $em->getRepository(TypeorgOrganisation::class)
	                                            ->findOneBy(array('organisation'=>$organisation, 'typeorganisation'=>$typeOrg), array('date'=>'desc'),1);
                    if($oldTypeOrgOrganisation == null)
                    {
                        $typeOrgOrganisation = new TypeorgOrganisation();
                        $typeOrgOrganisation->setOrganisation($organisation);
                        $typeOrgOrganisation->setTypeorganisation($typeOrg);
                        $em->persist($typeOrgOrganisation);
                        $em->flush();
                    }
                }

                $em->flush();
                $this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
            }else{
                $this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
            }
            return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
        }

        $liste_typeOrg = $em->getRepository(Typeorganisation::class)
	                        ->FindAll();

        $tabTypeOrganisation = $em->getRepository(TypeorgOrganisation::class)
	                        ->findAllOrganisationType($organisation->getId());
        
        return $this->render('Theme/Users/Adminuser/Organisation/joindreorganisation.html.twig',
        array('organisation'=>$organisation, 'liste_typeOrg'=>$liste_typeOrg, 'tabTypeOrganisation'=>$tabTypeOrganisation));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function deleteorganisationtypeorg(TypeorgOrganisation $typeorgOrganisation, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
        $formsupp->handleRequest($request);
        if ($formsupp->isValid()){
            $em->remove($typeorgOrganisation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('information','Type d\'organisation bien supprimé.');
            return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
        }
        }
        $this->get('session')->getFlashBag()->add('supprime_typeorg',$typeorgOrganisation->getId());
        $this->get('session')->getFlashBag()->add('supprime_typeorg',$typeorgOrganisation->getTypeorganisation()->getName());
        return $this->redirect($this->generateUrl('users_adminuser_type_organisation'));
    }
}