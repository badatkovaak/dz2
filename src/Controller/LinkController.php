<?php

namespace App\Controller;

use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LinkController extends AbstractController
{
    #[Route('/link', name: 'get_all_links', methods: ['GET'])]
    public function getAllLinks(LinkRepository $rep): Response
    {
        return $this->json($rep->getAllLinks());
    }

    #[Route('/link/{id}', name: 'get_link_by_id', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getLinkByIdRoute(int $id, LinkRepository $rep): Response
    {
        $link = $rep->getLinkById($id);
        if (is_null($link)) {
            return $this->json(['status' => 'Error! Id not found.'], 404);
        }

        return $this->json($link);
    }

    #[Route('/link/url/{url}', name: 'get_link_by_url', methods: ['GET'])]
    public function getLinkByUrlRoute(string $url, LinkRepository $rep): Response
    {
        $link = $rep->getLinkByUrl($url);
        if (is_null($link)) {
            return $this->json(['status' => 'Error! No link with that url.'], 404);
        }

        return $this->json($link);
    }

    #[Route('/link', name: 'create_link', methods: ['POST'])]
    public function createLinkRoute(Request $request, LinkRepository $rep): Response
    {
        $content = $request->getContent();

        if (!json_validate($content)) {
            return $this->json(['status' => 'Error! Not a valid JSON.'], 400);
        }

        $link = $rep->fromJson($content);

        if (is_null($link)) {
            return $this->json(['status' => 'Error! Error during decoding.'], 400);
        }

        $rep->save($link);

        return $this->json(['status' => 'Success!']);
    }

    #[Route('/link/{id}', name: 'update_link', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function updateLinkRoute(int $id, Request $request, LinkRepository $rep): Response
    {
        $link = $rep->getLinkById($id);

        if (is_null($link)) {
            return $this->json(['status' => 'Error! Link with this id does not exist.'], 400);
        }

        $content = $request->getContent();

        if (!json_validate($content)) {
            return $this->json(['status' => 'Error! Not a valid JSON.'], 400);
        }

        if (!$rep->updateFromJson($content)) {
            return $this->json(['status' => 'Error! Error while updating.'], 400);
        }

        return $this->json(['status' => 'Success!']);
    }

    #[Route('/link/{id}', name: 'delete_link', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function deleteLinkRoute(int $id, LinkRepository $rep): Response
    {
        $link = $rep->getLinkById($id);

        if (is_null($link)) {
            return $this->json(['status' => 'Error! Error while deleting the link.'], 400);
        }

        return $this->json(['status' => 'Success!']);
    }
}
