<?php

namespace Labstag\Controller;

use Labstag\Lib\ControllerLib;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/test", name="test")
     */
    public function test(Swift_Mailer $mailer): JsonResponse
    {
        $message = new Swift_Message();
        $message->setSubject('Hello Email');
        $message->setFrom('send@example.com');
        $message->setTo('recipient@example.com');
        $message->setBody(
            'html',
            // $this->renderView(
            //     // templates/emails/registration.html.twig
            //     'emails/registration.html.twig',
            //     ['name' => $name]
            // ),
            'text/html'
        );
        // you can remove the following code if you don't define a text version for your emails
        $message->addPart(
            'text',
            // $this->renderView(
            //     // templates/emails/registration.txt.twig
            //     'emails/registration.txt.twig',
            //     ['name' => $name]
            // ),
            'text/plain'
        );
        $mailer->send($message);
        
        return new JsonResponse([]);
    }
}
