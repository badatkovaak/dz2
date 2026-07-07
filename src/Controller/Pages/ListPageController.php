<?php

namespace App\Controller\Pages;

use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListPageController extends AbstractController
{
    #[Route('/pages/list', name: 'link_list')]
    public function listRoute(LinkRepository $rep): Response
    {
        return $this->render('list/index.html.twig', ['links' => $rep->getAllLinks()]);
    }
}
