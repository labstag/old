<?php

namespace Labstag\DataListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\Chapitre;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChapitreListener implements EventSubscriber
{

    /**
     * password Encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Sur quoi écouter.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Chapitre) {
            return;
        }

        $this->page($entity);
        // $enm  = $args->getEntityManager();
        // $meta = $enm->getClassMetadata(get_class($entity));
        // $enm->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Chapitre) {
            return;
        }

        $this->page($entity);
        $enm  = $args->getEntityManager();
        $meta = $enm->getClassMetadata(get_class($entity));
        $enm->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    private function page(Chapitre $entity)
    {
        $page = $entity->getPage();
        if (0 != $page) {
            return;
        }

        $histoire  = $entity->getRefhistory();
        $chapitres = $histoire->getChapitres();
        $page      = (count($chapitres) + 1);
        $entity->setPage($page);
    }
}
