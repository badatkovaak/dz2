<?php

namespace App\Controller\Pages;

use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreatePageController extends AbstractController
{
    #[Route('/pages/create', name: 'create_link_page')]
    public function createPageRoute(LinkRepository $rep): Response
    {
        return $this->render('create/index.html.twig');
    }
}
