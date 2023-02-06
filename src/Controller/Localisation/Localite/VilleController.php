<?php
/* (c) Noel Kenfack <noel.kenfack@yahoo.fr> Février 2015
*/
namespace App\Controller\Localisation\Localite;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Localisation\Localite\Ville;
use App\Form\Localisation\Localite\VilleType;
use Doctrine\ORM\EntityManagerInterface;

class VilleController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function listeville(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if ($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($ville);
                $em->flush();
            $this->get('session')->getFlashBag()->add('information','la ville a été bien enregistrée.');
            }
        }
        
        $liste_ville = $em->getRepository(Ville::class)
                        ->findVillePagine($page, 15);
        $formsupp = $this->createFormBuilder()->getForm();
        
        return $this->render('Theme/Users/Adminuser/Localite/listeville.html.twig', 
        array('nombrepage' => ceil(count($liste_ville)/12),'page'=>$page,'liste_ville'=>$liste_ville,
        'formsupp'=>$formsupp->createView(), 'form'=>$form->createView()));
    }

    public function modificationville(GeneralServicetext $service, Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $ville = $em->getRepository(Ville::class)
                        ->find($id);
        if($ville != null)
        {
        $form = $this->createForm(VilleType::class, $ville);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
            }else{
                $this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
            }
            return $this->redirect($this->generateUrl('users_adminuser_villes_organisations'));
        }
        return $this->render('Theme/Users/Adminuser/Localite/modificationville.html.twig',
        array('form'=>$form->createView(),'ville'=>$ville));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
    }

    public function deleteville(Ville $ville, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
            $formsupp->handleRequest($request);
            if ($formsupp->isValid()){
                $em->remove($ville);
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','ville bien supprimé.');
                return $this->redirect($this->generateUrl('users_adminuser_villes_organisations'));
            }
        }
        $this->get('session')->getFlashBag()->add('supprime_contenu',$ville->getId());
        $this->get('session')->getFlashBag()->add('supprime_contenu',$ville->getName());
        return $this->redirect($this->generateUrl('users_adminuser_villes_organisations'));
    }
}