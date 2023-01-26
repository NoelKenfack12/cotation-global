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
use App\Entity\Users\User\User;

class UserController extends AbstractController
{
private $params;

public function __construct(ParameterBagInterface $params)
{
	$this->params = $params;
}

}