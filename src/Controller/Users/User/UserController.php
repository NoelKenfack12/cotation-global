<?php
/* (c) Noel Kenfack <noel.kenfack@yahoo.fr> Février 2015
* ce fichier est la proprieté de Zentsoft entreprise.
*/
namespace App\Controller\Users\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Servicetext\GeneralServicetext;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Form\Users\User\UserType;
use App\Form\Users\User\UserEditType;
use App\Entity\Users\User\User;
use App\Entity\Localisation\Organisation\Organisation;
use App\Entity\Localisation\Organisation\Userorganisation;
use Doctrine\Common\Collections\ArrayCollection;

class UserController extends AbstractController
{
	private $params;

	public function __construct(ParameterBagInterface $params)
	{
		$this->params = $params;
	}

	public function updateuser(GeneralServicetext $service, Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $user = $em->getRepository(User::class)
                               ->find($id);

        if($user != null)
        {
			$form = $this->createForm(UserEditType::class, $user);
			if($request->getMethod() == 'POST'){
				$form->handleRequest($request);

				if(strlen($user->getFakePassword()) >= 1)
				{
					$passuser = $user->getFakePassword();
					$salt = substr(crypt($passuser,''), 0, 16);
					$user->setSalt($salt);
					$newpassword = $service->encrypt($passuser,$salt);
					$user->setPassword($newpassword);
				}

				if ($form->isValid()){
					$em->flush();
					$this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
				}else{
					$this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
				}
				return $this->redirect($this->generateUrl('users_adminuser_users_organisation'));
			}

			return $this->render('Theme/Users/Adminuser/User/updateuser.html.twig',
			array('form'=>$form->createView(),'user'=>$user));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
	}

	public function joindreuserorganisation(GeneralServicetext $service, Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
        if(isset($_GET['id']))
        {
            $id = $_GET['id'];
        }else{
            $id = $id;
        }
        
        $user = $em->getRepository(User::class)
                    ->find($id);

        if($user != null)
        {
        if ($request->getMethod() == 'POST'){

            if(isset($_POST['rolelist'], $_POST['organisationId']))
            {
				$organisation = $em->getRepository(Organisation::class)
	                                ->Find($_POST['organisationId']);

                $liste_role = $_POST['rolelist'];

                if($organisation and count($liste_role) > 0)
				{
					$oldUserorganisation = $em->getRepository(Userorganisation::class)
										      ->findOneBy(array('user'=>$user, 'organisation'=>$organisation));

					if($oldUserorganisation != null)
					{
						$oldUserorganisation->addRole($liste_role[0]);
					}else{
						$userorganisation = new Userorganisation();
						$userorganisation->setUser($user);
						$userorganisation->setOrganisation($organisation);
						$userorganisation->addRole($liste_role[0]);
						$em->persist($userorganisation);
					}
					$user->addRole($liste_role[0]);
					$em->flush();

					$this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
				}else{
					$this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
				}
            }else{
                $this->get('session')->getFlashBag()->add('information','Une ereur a été rencontrée!');
            }
            return $this->redirect($this->generateUrl('users_adminuser_users_organisation'));
        }
        $liste_role = $service->getRoles();

		$liste_organisation = $em->getRepository(Organisation::class)
	                                ->FindAll();
        return $this->render('Theme/Users/Adminuser/User/joindreuserorganisation.html.twig',
        array('user'=>$user, 'liste_role'=>$liste_role, 'liste_organisation'=>$liste_organisation));
        }else{
            echo 'Echec ! Une erreur a été rencontrée.';
            exit;
        }
	}

	public function deleteuser(User $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formsupp = $this->createFormBuilder()->getForm(); 
        if ($request->getMethod() == 'POST'){
            $formsupp->handleRequest($request);
            if ($formsupp->isValid()){
                $em->remove($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('information','utilisateur bien supprimée.');
                return $this->redirect($this->generateUrl('users_adminuser_users_organisation'));
            }
        }
        $this->get('session')->getFlashBag()->add('supprime_organisation',$user->getId());
        $this->get('session')->getFlashBag()->add('supprime_organisation',$user->name(30));
        return $this->redirect($this->generateUrl('users_adminuser_users_organisation'));
    }

	public function ajouteradmin(Request $request, GeneralServicetext $service)
	{
		$em = $this->getDoctrine()->getManager();

		if ($request->getMethod() == 'POST' and isset($_POST['_username']) and isset($_POST['_password']) and isset($_POST['_identifiant'])){
			$username = $this->params->get('username');
			$password = $this->params->get('password');

			$userAuth = $em->getRepository(User::class)
						     ->findOneBy(array('username'=>$_POST['_identifiant']));
			if($_POST['_username'] == $username and $_POST['_password'] == $password and $userAuth != null)
			{
				$userAuth->addRole('ROLE_ADMIN');
				$em->flush();
				$this->get('session')->getFlashBag()->add('information','Administrateur enregistré avec succès');
			}else{
				$this->get('session')->getFlashBag()->add('information','Le mot de passe ou le nom d\'utilisateur est incorect.');
			}
			return $this->render('Theme/Users/User/User/ajouteradmin.html.twig');
		}

		return $this->render('Theme/Users/User/User/ajouteradmin.html.twig');
	}

	public function updateparameters(User $user, GeneralServicetext $service, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$liste_userorganisation = new ArrayCollection();
		$liste_userorganisation = $em->getRepository(Userorganisation::class)
									 ->findBy(array('user'=>$user));

		$userorg = null;
		foreach($liste_userorganisation as $userorganisation)
		{
			$userorg = $userorganisation;
			break;
		}

		if($userorg != null)
		{
			if($request->getMethod() == 'POST' and isset($_POST['oldmdp'], $_POST['newmdp'], $_POST['repeatmdp']))
			{
				if($_POST['newmdp'] == $_POST['repeatmdp'])
				{
					$passuser = $_POST['newmdp'];
					$salt = substr(crypt($passuser,''), 0, 16);
					$user->setSalt($salt);
					$newpassword = $service->encrypt($passuser,$salt);
					$user->setPassword($newpassword);

					$em->flush();
					$this->get('session')->getFlashBag()->add('information','Modification effectuée avec succès');
				}else{
					$this->get('session')->getFlashBag()->add('information','Echec, les deux mots de passe sont différent');
				}
			}
			return $this->render('Theme/Users/Adminuser/User/updateparameters.html.twig', array('organisation'=>$userorg->getOrganisation()));
		}else{
			return $this->redirect($this->generateUrl('users_user_accueil'));
		}
	}
}