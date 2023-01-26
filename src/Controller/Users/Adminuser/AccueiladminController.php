<?php

namespace App\Controller\Users\Adminuser;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class AccueiladminController extends AbstractController
{
    public function accueiladmin()
    {
        return $this->render('Theme/Users/Adminuser/Dashboard/accueiladmin.html.twig');
    }
}