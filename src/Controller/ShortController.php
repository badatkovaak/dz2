<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\LinkRepository;
use App\Service\LinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ShortController extends AbstractController
{
    #[Route('/short/{shortUrl}', name: 'short_url_redirect')]
    public function shortRoute(#[CurrentUser] User $user, string $shortUrl, LinkService $service, LinkRepository $rep): Response
    {
        $url = $service->shortLinkHandler($user, $shortUrl, $rep);

        if (is_null($url)) {
            return $this->json(['status' => 'Error!']);
        }

        return $this->redirect($url);
    }
}
