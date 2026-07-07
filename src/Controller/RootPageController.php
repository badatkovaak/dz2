<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RootPageController extends AbstractController
{
    #[Route('/', name: 'root')]
    public function root(): Response
    {
        return $this->render('root/index.html.twig');
    }
}
