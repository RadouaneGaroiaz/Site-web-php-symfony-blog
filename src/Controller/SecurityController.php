<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\DailyCount;
use App\Entity\LastConnection;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        // creation nouveau objet
        $user = new User();
        
        // creation de la formulaire associer a l'objet
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);



            $userCount = new DailyCount();
            $userCount->setUser($user);
            $manager->persist($userCount);

            //  ajouter la derniere connexion
            $userConnection = new LastConnection();
            $userConnection->setUser($user);
            $userConnection->setUpdatedAt(new \DateTime);
            $manager->persist($userConnection);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login'); // redirection après l'enregistrement
        }


        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //  en cas d'erreur login
        $error = $authenticationUtils->getLastAuthenticationError();
        // dernier username saisai par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="security_logout", methods={"GET"})
     */
    public function logout()
    {

        throw new \Exception('logout');
    }
}
