<?php

namespace App\Controller;

use App\Entity\Link;
use Doctrine\ORM\EntityManagerInterface as EMInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShortController extends AbstractController
{
    #[Route('/short/{shortUrl}', name: 'short_url_redirect')]
    public function shortRoute(string $shortUrl, EMInterface $em): Response
    {
        $link = Link::getLinkByUrl($shortUrl, $em);
        $link->updateTimeAndUsage($link, $em);
        return $this->redirect($link->getLongUrl());
    }
}
