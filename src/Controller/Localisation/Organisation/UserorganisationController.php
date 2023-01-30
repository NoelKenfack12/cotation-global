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

class UserorganisationController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function listeuserorganisation()
    {
        return $this->render('Theme/Users/Adminuser/Organisation/listeuserorganisation.html.twig');
    }
}