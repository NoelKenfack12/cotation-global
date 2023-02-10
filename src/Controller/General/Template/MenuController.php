<?php

namespace App\Controller\General\Template;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Security\TokenAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Service\Servicetext\GeneralServicetext;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users\User\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Localisation\Organisation\Userorganisation;
use Doctrine\Common\Collections\ArrayCollection;

class MenuController extends AbstractController
{
    private $authenticator;
    private $guardHandler;

    public function __construct(ParameterBagInterface $params, TokenAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler)
    {
        $this->params = $params;
        $this->authenticator = $authenticator;
        $this->guardHandler = $guardHandler;
    }

    public function menubare(GeneralServicetext $service, Request $request, $position, $organisationId)
    {
        $user = null;
		$em = $this->getDoctrine()->getManager();
		if($this->getUser() == null and isset($_COOKIE["PIDSESSREM"]) and $_COOKIE["PIDSESSREM"] != 'delete')
		{
			$cookies = $_COOKIE["PIDSESSREM"];

			$username = trim($service->decrypt($cookies, $this->params->get('saltcookies')));

			if($service->email($username) || $service->telephone($username))
			{
				$repository = $em->getRepository(User::class);
				$user = $repository->findOneBy(array('username'=>$username));
				if($user != null)
				{
					$response = $this->guardHandler->authenticateUserAndHandleSuccess(
						$user,          // the User object you just created
						$request,
						$this->authenticator, // authenticator whose onAuthenticationSuccess you want to use
						'main'          // the name of your firewall in security.yaml
					);
				}
			}
		}
		$liste_userorganisation = new ArrayCollection();
		if($this->getUser() != null)
		{
			$liste_userorganisation = $em->getRepository(Userorganisation::class)
                                 ->findBy(array('user'=>$this->getUser()));
		}
		

        return $this->render('Theme/General/Template/Menu/menubare.html.twig', 
		array('position'=>$position, 'organisationId'=>$organisationId, 'liste_userorganisation'=>$liste_userorganisation));
    }
	
    public function footer(EntityManagerInterface $em, $position)
    {			
        return $this->render('Theme/General/Template/Menu/footer.html.twig', array('position'=>$position));
    }

	public function menuleft($position)
    {
        return $this->render('Theme/General/Template/Menu/menuleft.html.twig', array('position'=>$position));
    }

	public function menuleftorg($position, $organisationId)
	{
		return $this->render('Theme/General/Template/Menu/menuleftorg.html.twig', 
		array('position'=>$position, 'organisationId'=>$organisationId));
	}
}
