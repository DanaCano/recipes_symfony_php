<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


class ContactController extends AbstractController
{ //MailerInterface $mailer
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $manager, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        if($this->getUser()) {
            $contact->setFirstName($this->getUser()->getFirstName())
                ->setLastName($this->getUser()->getLastName())
                ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            /*$email = (new Email())
                ->from('danadevweb@gmail.com')
                ->to('danacanorodriguez@gmail.com')
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');


                $mailer->send($email);*/


            //Ici pour l'envoi des mails avec mailtrap
            $transport = Transport::fromDsn('smtp://a9c27886f5cb8e:828a636d404069@sandbox.smtp.mailtrap.io:2525?encryption=tls&auth_mode=login');
            $mailer = new Mailer($transport);
            //Ici pour l'envoi des mails
            $email = (new Email())
                ->from($contact->getEmail())
                ->to('danacanorodriguez@gmail.com')
                ->subject($contact->getSubject())
                ->html($contact->getMessage());

            $mailer->send($email);
            //jusqu'ici pour l'envoi des mails */

            $this->addFlash(
                'success',
                'Votre message a été envoyé avec succès !'
            );

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('thepages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
