<?php

namespace App\Controller;

use App\Entity\Link;
use Doctrine\ORM\EntityManagerInterface as EMInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LinkController extends AbstractController
{
    #[Route('/link', name: 'get_all_links', methods: ['GET'])]
    public function getAllLinks(EMInterface $em): Response
    {
        return $this->json(Link::getAllLinks($em));
    }

    #[Route('/link/{id}', name: 'get_link_by_id', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getLinkByIdRoute(int $id, EMInterface $em): Response
    {
        $link = Link::getLinkById($id, $em);
        if (is_null($link)) {
            return new Response('', 404);
        }

        return $this->json($link);
    }

    #[Route('/link/url/{url}', name: 'get_link_by_url', methods: ['GET'])]
    public function getLinkByUrlRoute(string $url, EMInterface $em): Response
    {
        $link = Link::getLinkByUrl($url, $em);
        if (is_null($link)) {
            return new Response('', 404);
        }

        return $this->json($link);
    }

    #[Route('/link', name: 'create_link', methods: ['POST'])]
    public function createLinkRoute(Request $request, EMInterface $em): Response
    {
        $content = $request->getContent();

        if (!json_validate($content)) {
            return new Response('Error! Not a valid JSON.' . PHP_EOL, 400);
        }

        $link = Link::fromJson($content, $em);

        if (is_null($link)) {
            return new Response('Error! Error during decoding.' . PHP_EOL, 400);
        }

        Link::saveLink($link, $em);

        return new Response('Success!' . PHP_EOL);
    }

    #[Route('/link/{id}', name: 'update_link', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function updateLinkRoute(int $id, Request $request, EMInterface $em): Response
    {
        $link = Link::getLinkById($id, $em);

        if (is_null($link)) {
            return new Response('Error! Link with this id does not exist.' . PHP_EOL, 404);
        }

        $content = $request->getContent();

        if (!json_validate($content)) {
            return new Response('Error! Not a valid JSON.' . PHP_EOL, 400);
        }

        if (!$link->updateFromJson($content, $em)) {
            return new Response('Error! Error while updating.' . PHP_EOL, 400);
        }

        return new Response('Success!' . PHP_EOL);
    }

    #[Route('/link/{id}', name: 'delete_link', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function deleteLinkRoute(int $id, EMInterface $em): Response
    {
        if (!Link::deleteLink($id, $em)) {
            return new Response('Error! Error while deleting the link.' . PHP_EOL, 400);
        }

        return new Response('Success!' . PHP_EOL);
    }
}
