<?php

namespace Labstag\Controller;

use Labstag\Entity\Email;
use Labstag\Lib\ControllerLib;
use Symfony\Component\Routing\Annotation\Route;

class CheckController extends ControllerLib
{
    /**
     * @Route("/check/email/{id}", name="check-email")
     */
    public function email(Email $email)
    {
        if (false == $email->getChecked()) {
            $manager = $this->getDoctrine()->getManager();
            $email->setChecked(true);
            $manager->persist($email);
            $manager->flush();
            $this->addFlash('success', 'Courriel activé');
        }

        return $this->redirect(
            $this->generateUrl('front'),
            301
        );
    }
}
