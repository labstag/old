<?php

namespace Labstag\DataListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\User;
use Labstag\Entity\Templates;
use Labstag\Lib\EventSubscriberLib;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener extends EventSubscriberLib
{

    /**
     * @var Router
     */
    protected $router;

    /**
     * password Encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->container       = $container;
        $this->router          = $router;
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
        if (!$entity instanceof User) {
            return;
        }

        $this->lost($entity, $args);
        $this->plainPassword($entity);
        // $manager  = $args->getEntityManager();
        // $meta = $manager->getClassMetadata(get_class($entity));
        // $manager->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $this->lost($entity, $args);
        $this->plainPassword($entity);
        $manager = $args->getEntityManager();
        $meta    = $manager->getClassMetadata(get_class($entity));
        $manager->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    private function lost(User $entity, $args)
    {
        if (!$entity->isLost()) {
            return;
        }

        $manager    = $args->getEntityManager();
        $repository = $manager->getRepository(templates::class);
        $search     = ['code' => 'lost-password'];
        $templates  = $repository->findOneBy($search);
        $html       = $templates->getHtml();
        $text       = $templates->getText();
        $this->setConfigurationParam($args);
        $before  = [
            '%site%',
            '%username%',
            '%url%',
            '%date%'
        ];
        $after   = [
            $this->configParams['site_title'],
            $entity->getUsername(),
            $this->router->generate(
                'change-password',
                [
                    'id' => $entity->getId(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            date('d/m/Y')
        ];
        $html    = str_replace($before, $after, $html);
        $text    = str_replace($before, $after, $text);
        $message = new Swift_Message();
        $sujet   = str_replace(
            '%site%',
            $this->configParams['site_title'],
            $templates->getname()
        );
        $message->setSubject($sujet);
        $message->setFrom($user->getEmail());
        $message->setTo($this->configParams['site_no-reply']);
        $message->setBody($html, 'text/html');
        $message->addPart($text, 'text/plain');
        $mailer = $this->container->get('swiftmailer.mailer.default');
        $mailer->send($message);
    }

    private function plainPassword(User $entity)
    {
        $plainPassword = $entity->getPlainPassword();
        if ('' === $plainPassword || is_null($plainPassword)) {
            return;
        }

        $encodePassword = $this->passwordEncoder->encodePassword(
            $entity,
            $plainPassword
        );

        $entity->setPassword($encodePassword);
    }
}
