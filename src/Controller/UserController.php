<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    /** Ici le controller qui permet d'editer/le nom et prénom avec un check
     * de mot de passe pour avoir la securité de que il s'agit de vraimente de l'utilisateur qui a envie de faire le changement
     * @param User $selectedUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */

    #[Security("is_granted('ROLE_USER') and user === selectedUser")]
    #[Route('/utilisateur/edition/{id}', name: 'user.update', methods: ['GET', 'POST'])]
    public function update(User $selectedUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        /*if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        s'il sont differente j'envoi vers recipe.index ses propre recettes.
        if($this->getUser() !== $user) {
            return $this->redirectToRoute('recipe.index');
        }*/

        $form = $this->createForm(UserType::class, $selectedUser);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            if($hasher->isPasswordValid($selectedUser, $form->getData()->getPlainPassword()) ) {

                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Vos informations ont été bien modifiées !'
                );

                return $this->redirectToRoute('recipe.index');
            }else {

                $this->addFlash(
                    'warning',
                    'Le mot de passe écrit est incorrect !'
                );

            }

        }
        return $this->render('thepages/user/update.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * Ici le controller qui permet d'editer/modifier le mot de passe
     * @param User $selectedUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */

    #[Security("is_granted('ROLE_USER') and user === selectedUser")]
    #[Route('/utilisateur/edition-mot-de-passe/{id}', 'user.update.password', methods: ['GET', 'POST'])]

    public function updatePassword(User $selectedUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        /*if(!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }

        if($this->getUser() !== $user) {
            return $this->redirectToRoute('recipe.index');
        }*/

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($hasher->isPasswordValid($selectedUser, $form->getData()['plainPassword'])) {
                $selectedUser->setUpdatedAt(new \DateTimeImmutable());
                $selectedUser->setPlainPassword(
                    $form->getData()['newPassword']
                );

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifiés !'
                );

                $manager->persist($selectedUser);
                $manager->flush();

                return $this->redirectToRoute('recipe.index');

            }else {

                $this->addFlash(
                    'warning',
                    'Le mot de passe n\'est incorrect !'
                );
            }

        }

        return $this->render('thepages/user/update_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}





