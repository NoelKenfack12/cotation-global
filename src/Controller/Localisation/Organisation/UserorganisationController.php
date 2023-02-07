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
use App\Form\Users\User\UserType;
use App\Entity\Users\User\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Localisation\Organisation\Userorganisation;

class UserorganisationController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function listeuserorganisation(Request $request, EntityManagerInterface $em, GeneralServicetext $seneralServicetext, $page)
    {
        $user = new User($seneralServicetext);
	    $formUser = $this->createForm(UserType::class, $user);

        if($request->getMethod() == 'POST')
        {
            $passuser = $user->getFakePassword();
            $salt = substr(crypt($passuser,''), 0, 16);
            $user->setSalt($salt);
            $newpassword = $seneralServicetext->encrypt($passuser,$salt);
            $user->setPassword($newpassword);

            $formUser->handleRequest($request);
            if ($formUser->isValid()){
                $em->persist($user);
                $em->flush();
            }else{
                $this->get('session')->getFlashBag()->add('information','Données invalides.');
            }
        }

        $liste_user = $em->getRepository(User::class)
                         ->findUserPagine($page, 12);
        $formsupp = $this->createFormBuilder()->getForm();
        return $this->render('Theme/Users/Adminuser/Organisation/listeuserorganisation.html.twig',
        array('formUser'=>$formUser->createView(), 'liste_user'=>$liste_user, 
        'formsupp'=>$formsupp->createView(),'nombrepage' => ceil(count($liste_user)/12),'page'=>$page));
    }
}