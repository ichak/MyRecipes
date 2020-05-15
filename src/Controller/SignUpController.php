<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
{
    /**
     * @Route("/signup", name="sign_up")
     */
    public function index()
    {
        return $this->render('sign_up/index.html.twig', [
            'controller_name' => 'SignUpController',
        ]);
    }
}
