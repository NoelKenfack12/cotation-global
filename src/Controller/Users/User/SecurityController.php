<?php

namespace App\Controller\Users\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Security\TokenAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Entity\Users\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;

class SecurityController extends AbstractController
{
    private $params;
    private $authenticator;
    private $guardHandler;
    private $_entityManager;

    public function __construct(ParameterBagInterface $params, TokenAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler, EntityManagerInterface $entityManager)
    {
        $this->params = $params;
        $this->authenticator = $authenticator;
        $this->guardHandler = $guardHandler;
        $this->_entityManager = $entityManager;
    }

    public function accueilsite(GeneralServicetext $service)
    {
        return $this->render('Theme/Users/User/Security/accueilsite.html.twig');
    }

    public function login(GeneralServicetext $service, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if($this->getUser() != null){ //IS_AUTHENTICATED_REMEMBERED
            return $this->redirect($this->generateUrl('users_user_accueil'));
        }

        //connexion sécurisé user.
        $error_login = '';
        $last_username = null;
        if($request->getMethod() == 'POST' and $this->getUser() == null){
            if (isset($_POST['_username']) and isset($_POST['_password'])){
                $repository = $em->getRepository(User::class);
                $user = $repository->findOneBy(array('username'=>$_POST['_username']));
                
                if($user != null)
                {
                    if($_POST['_password'] == $service->decrypt($user->getPassword(),$user->getSalt()))
                    {
                        $response = $this->guardHandler->authenticateUserAndHandleSuccess(
                            $user,          // the User object you just created
                            $request,
                            $this->authenticator, // authenticator whose onAuthenticationSuccess you want to use
                            'main'          // the name of your firewall in security.yaml
                        );


                        //$token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                        //$this->get('security.token_storage')->setToken($token);
                        //$this->get('session')->set('_security_main', serialize($token));

                        // Verifie si le cookie n existe pas
                        if((!isset($_COOKIE["PIDSESSREM"]) or $_COOKIE["PIDSESSREM"] == 'delete') and isset($_POST['_remember_me']) and $_POST['_remember_me'] == true)
                        {
                            // Stock les infos du cookie
                            $cookie_info = array(
                                'name'  => 'PIDSESSREM',
                                'value' => $service->encrypt($user->getUsername(),$this->params->get('saltcookies')),
                                'time'  => time() + (3600 * 24 * 360)
                            );
                            // Cree le cookie
                            setCookie($cookie_info['name'], $cookie_info['value'], $cookie_info['time'],'/');
                            setCookie('PIDSESSDUR',$cookie_info['time'], $cookie_info['time'],'/');
                        }

                        $session = $this->get('session');
                        $target_link = $session->get('_security.welcome.target_path');
                        if($target_link != null and strlen($target_link) > 5)
                        {
                            return $this->redirect($target_link);
                        }else{
                            return $this->redirect($this->generateUrl('users_user_accueil'));
                        }
                    }else{
                        $error_login = '<span style="color: red;">Echec: Mot de passe ou Email invalide.</span>';
                        $last_username = $_POST['_username'];
                    }
                }else{
                    $last_username = $_POST['_username'];
                }
            }
        }

        return $this->render('Theme/Users/User/Security/login.html.twig',
        array('last_username' => $last_username,'error'=> $error_login));
    }
}
