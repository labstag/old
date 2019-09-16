<?php

namespace Labstag\Controller;

use Labstag\Form\Front\ContactType;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\TemplatesRepository;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends ControllerLib
{
    /**
     * @Route("/", name="front")
     */
    public function index(): RedirectResponse
    {
        return $this->redirect(
            $this->generateUrl('posts_list'),
            301
        );
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(TemplatesRepository $repository, Swift_Mailer $mailer): Response
    {
        $search    = ['code' => 'contact'];
        $templates = $repository->findOneBy($search);
        if (!$templates) {
            throw new HttpException(403, "Erreur de base de données. Veuillez contacter l'administrateur");
        }

        $form = $this->createForm(
            ContactType::class,
            [],
            [
                'action' => $this->generateUrl('contact'),
            ]
        );
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $this->request->request->get('contact');
            $html    = $templates->getHtml();
            $text    = $templates->getText();
            $this->setConfigurationParam();
            $before  = [
                '%sujet%',
                '%site%',
                '%name%',
                '%email%',
                '%message%',
            ];
            $after   = [
                $contact['sujet'],
                $this->paramViews['config']['site_title'],
                $contact['name'],
                $contact['email'],
                $contact['content'],
            ];
            $html    = str_replace($before, $after, $html);
            $text    = str_replace($before, $after, $text);
            $message = new Swift_Message();
            $sujet   = $templates->getname();
            $sujet   = str_replace(
                '%site%',
                $this->paramViews['config']['site_title'],
                $sujet
            );
            $message->setSubject($sujet);
            $message->setFrom($this->paramViews['config']['site_email']);
            $message->setTo($this->paramViews['config']['site_no-reply']);
            $message->setBody($html, 'text/html');
            $message->addPart($text, 'text/plain');
            $mailer->send($message);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Message envoyé');
        }

        return $this->twig(
            'front/contact.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
