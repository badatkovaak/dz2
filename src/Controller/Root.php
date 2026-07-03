<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends AbstractController
{
    #[Route('/hello', name: "hello")]
    public function index(string $name) : Response {
        return new Response("Hi mom and $name");
    }
}
