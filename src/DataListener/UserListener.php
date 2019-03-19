<?php
namespace App\DataListener;

use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener implements EventSubscriber
{
    
    /**
     * password Encoder
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
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $this->plainPassword($entity);
        // $enm  = $args->getEntityManager();
        // $meta = $enm->getClassMetadata(get_class($entity));
        // $enm->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $this->plainPassword($entity);
        $enm  = $args->getEntityManager();
        $meta = $enm->getClassMetadata(get_class($entity));
        $enm->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    private function plainPassword(User $entity)
    {
        $plainPassword = $entity->getPlainPassword();
        if ($plainPassword === '')
        {
            return;
        }

        $encodePassword = $this->passwordEncoder->encodePassword(
            $entity,
            $plainPassword
        );

        $entity->setPassword($encodePassword);
    }
}
