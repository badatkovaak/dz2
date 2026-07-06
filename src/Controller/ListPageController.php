<?php

namespace App\Controller;

use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListPageController extends AbstractController
{
    #[Route('/list', name: 'link_list')]
    public function listRoute(LinkRepository $rep): Response
    {
        return $this->render('list.html.twig', ['links' => $rep->getAllLinks()]);
    }
}
