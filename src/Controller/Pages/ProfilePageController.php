<?php

namespace App\Controller\Pages;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProfilePageController extends AbstractController
{
    #[Route('/pages/profile', name: 'profile_page')]
    public function profilePageRoute(#[CurrentUser] User $user): Response
    {
        return $this->render('profile/index.html.twig', ['user' => $user]);
    }
}
