<?php

namespace Labstag\DataListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\Templates;
use Labstag\Entity\User;
use Labstag\Lib\EventSubscriberLib;
use Labstag\Repository\TemplatesRepository;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener extends EventSubscriberLib
{

    /**
     * @var RouterInterface|Router
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

    public function __construct(
        ContainerInterface $container,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->container       = $container;
        $this->router          = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Sur quoi écouter.
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
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

    public function preUpdate(LifecycleEventArgs $args): void
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

    private function lost(User $entity, LifecycleEventArgs $args): void
    {
        if (!$entity->isLost()) {
            return;
        }

        $manager = $args->getEntityManager();
        /** @var TemplatesRepository $repository */
        $repository = $manager->getRepository(templates::class);
        $search     = ['code' => 'lost-password'];
        /** @var Templates $templates */
        $templates = $repository->findOneBy($search);
        $html      = $templates->getHtml();
        $text      = $templates->getText();
        $this->setConfigurationParam($args);
        $replace = [
            '%site%'     => $this->configParams['site_title'],
            '%username%' => $entity->getUsername(),
            '%url%'      => $this->router->generate(
                'change-password',
                [
                    'id' => $entity->getId(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
            '%date%'     => date('d/m/Y'),
        ];

        $html    = strtr($html, $replace);
        $text    = strtr($text, $replace);
        $message = new Swift_Message();
        $sujet   = str_replace(
            '%site%',
            $this->configParams['site_title'],
            $templates->getname()
        );
        $message->setSubject($sujet);
        $message->setFrom($entity->getEmail());
        $message->setTo($this->configParams['site_no-reply']);
        $message->setBody($html, 'text/html');
        $message->addPart($text, 'text/plain');
        /** @var Swift_Mailer $mailer */
        $mailer = $this->container->get('swiftmailer.mailer.default');
        $mailer->send($message);
    }

    private function plainPassword(User $entity): void
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
