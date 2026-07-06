<?php

namespace App\Controller;

use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShortController extends AbstractController
{
    #[Route('/short/{shortUrl}', name: 'short_url_redirect')]
    public function shortRoute(string $shortUrl, LinkRepository $rep): Response
    {
        $link = $rep->getLinkByUrl($shortUrl);

        if (is_null($link)) {
            return $this->json(['status' => 'Error!']);
        }

        $rep->updateTimeAndUsage($link);
        return $this->redirect($link->getLongUrl());
    }
}
