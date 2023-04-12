<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /** Ici le controller de connexion
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */

    #[Route('/connexion', name: 'security.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        return $this->render('thepages/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);

    }

    /**
     * Ici le logout se gerer tout seul grâce à symfony et ici la route vers la deconnexion
     * @return void
     */

    #[Route('/deconnexion', 'security.logout')]
    public function logout():void
    {
        //rien à mettre ici
    }

    /**
     * Ici le controller pour enregistrer un utilisateur
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

    #[Route('/inscription', 'security.registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $manager) : Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a été créé avec succès !'
            );

            return $this->redirectToRoute('security.login');
        }

        return $this->render('thepages/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

