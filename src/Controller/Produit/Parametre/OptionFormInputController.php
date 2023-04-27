<?php
namespace App\Controller\Produit\Parametre;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Produit\Parametre\ForminputType;
use App\Entity\Produit\Parametre\Forminput;
use App\Entity\Produit\Parametre\OptionFormInput;
use App\Service\Servicetext\GeneralServicetext;

class OptionFormInputController extends AbstractController
{
    public function deleteoption(OptionFormInput $optionforminput, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
        $formsupp->handleRequest($request);
        if ($formsupp->isValid()){

            $em->remove($optionforminput);
            $em->flush();
            $this->get('session')->getFlashBag()->add('information','continent bien supprimé.');

            return $this->redirect($this->generateUrl('users_adminuser_services_organisation'));
        }
        }
        $this->get('session')->getFlashBag()->add('supprime_option_forminput',$optionforminput->getId());
        $this->get('session')->getFlashBag()->add('supprime_option_forminput',$optionforminput->getLibelle());
        return $this->redirect($this->generateUrl('users_adminuser_services_organisation'));
    }
}