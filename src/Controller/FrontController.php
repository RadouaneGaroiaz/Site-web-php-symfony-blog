<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\DateGenerator;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home( UserRepository $repoUser, Request $request, EntityManagerInterface $manager, DateGenerator $dateGenerator)
    {




        // si l'utilisateur est connectée
        if ($this->getUser()) {
            // id utilisateur
            $user = $this->getUser()->getId();

            // object of utilisateur
            $userParam = $repoUser->find($user);


                return $this->redirectToRoute('home');
            }   


        return $this->render('front/home.html.twig', [

        ]);
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentation()
    {
        return $this->render('front/presentation.html.twig', [
            'variable' => 'variable',
        ]);
    }
}
