<?php

namespace App\Controller\Pages;

use App\Entity\Link;
use App\Entity\User;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreatePageController extends AbstractController
{
    #[Route('/pages/create', name: 'create_link_page')]
    public function new(#[CurrentUser] User $user, Request $request, LinkRepository $rep, ValidatorInterface $val): Response
    {
        $link = new Link();

        $form = $this->createForm(LinkType::class, $link);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $link = $form->getData();
            $rep->finishCreation($link, $user, $val);
            $user->addLink($link);
            $rep->save($link);
            return $this->redirectToRoute('link_list_page');
        }

        return $this->render('create/form.html.twig', ['form' => $form]);
    }

    /* #[Route('/pages/create', name: 'create_link_page')] */
    /* public function createPageRoute(LinkRepository $rep): Response */
    /* { */
    /* return $this->render('create/index.html.twig'); */
    /* } */
}
