<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
/* use Symfony\Component\HttpKernel\Attribute\MapQueryParameter; */
use Symfony\Component\Routing\Attribute\Route;

class RootController extends AbstractController
{
    #[Route('/', name: 'root')]
    public function root(
        /* #[MapQueryParameter] string $url */
    ): Response {
        return $this->render('root.html.twig');
    }
}
