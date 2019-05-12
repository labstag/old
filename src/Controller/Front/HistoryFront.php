<?php

namespace Labstag\Controller\Front;

use Labstag\Entity\History;
use Labstag\Entity\User;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\ChapitreRepository;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/histoires")
 */
class HistoryFront extends ControllerLib
{
    /**
     * @Route("/", name="history_list")
     */
    public function histoires(HistoryRepository $repository)
    {
        $histoires = $repository->findAllActive();
        $this->paginator($histoires);

        return $this->twig('front/history/list.html.twig');
    }

    /**
     * @Route("/user/{user}", name="history_user")
     */
    public function user(User $user, HistoryRepository $repository)
    {
        $histoires = $repository->findAllActiveByUser($user);
        $this->paginator($histoires);

        return $this->twig('front/history/list.html.twig');
    }

    /**
     * @Route("/histoire/{slug}", name="history_show")
     */
    public function histoire(History $history, ChapitreRepository $repository)
    {
        $idChapitre = $this->request->query->get('chapitre');
        $chapitres  = $history->getChapitresEnabled();
        $datatwig   = [
            'entity'    => $history,
            'chapitres' => $chapitres,
        ];
        if ('' == $idChapitre) {
            $idChapitre = 1;
        }

        foreach ($chapitres as $chapitre) {
            if ($chapitre->getPosition() == ($idChapitre - 1) && $chapitre->isEnable()) {
                $datatwig['chapitre'] = $chapitre;

                break;
            }
        }

        return $this->twig('front/history/histoire.html.twig', $datatwig);
    }
}
