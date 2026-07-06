<?php

namespace App\Controller;

use App\Entity\Link;
use Doctrine\ORM\EntityManagerInterface as EMInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListPageController extends AbstractController
{
    #[Route('/list', name: 'link_list')]
    public function listRoute(EMInterface $em): Response
    {
        return $this->render('list.html.twig', ['links' => Link::getAllLinks($em)]);
    }
}
