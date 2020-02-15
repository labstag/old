<?php

namespace Labstag\Controller;

use Labstag\Entity\Email;
use Labstag\Entity\Phone;
use Labstag\Lib\ControllerLib;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class CheckController extends ControllerLib
{
    /**
     * @Route("/check/email/{id}", name="check-email")
     */
    public function email(Email $email): RedirectResponse
    {
        if (false == $email->isChecked()) {
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

    /**
     * @Route("/check/phone/{id}", name="check-phone")
     */
    public function phone(Phone $phone): RedirectResponse
    {
        if (false == $phone->isChecked()) {
            $manager = $this->getDoctrine()->getManager();
            $phone->setChecked(true);
            $manager->persist($phone);
            $manager->flush();
            $this->addFlash('success', 'Téléphone activé');
        }

        return $this->redirect(
            $this->generateUrl('front'),
            301
        );
    }
}
