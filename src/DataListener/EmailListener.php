<?php

namespace Labstag\DataListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\Configuration;
use Labstag\Entity\Email;
use Labstag\Entity\Templates;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class EmailListener implements EventSubscriber
{

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, RouterInterface $router)
    {
        $this->container = $container;
        $this->router    = $router;
    }

    /**
     * Sur quoi écouter.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Email) {
            return;
        }

        $this->checkEmail($entity, $args);
    }

    private function setConfigurationParam($args)
    {
        if (isset($this->configParams)) {
            return;
        }

        $manager    = $args->getEntityManager();
        $repository = $manager->getRepository(Configuration::class);
        $data       = $repository->findAll();
        $config     = [];
        foreach ($data as $row) {
            $key          = $row->getName();
            $value        = $row->getValue();
            $config[$key] = $value;
        }

        $this->configParams = $config;
    }

    private function checkEmail(Email $entity, $args)
    {
        $check = $entity->isChecked();
        if (true === $check) {
            return;
        }

        $search     = ['code' => 'checked-mail'];
        $manager    = $args->getEntityManager();
        $repository = $manager->getRepository(Templates::class);
        $templates  = $repository->findOneBy($search);
        $html       = $templates->getHtml();
        $text       = $templates->getText();
        $user       = $entity->getRefuser();
        $this->setConfigurationParam($args);
        $before = [
            '%site%',
            '%username%',
            '%email%',
            '%url%',
        ];
        $after   = [
            $this->configParams['site_title'],
            $user->getUsername(),
            $entity->getAdresse(),
            $this->router->generate(
                'check-email',
                [
                    'id' => $entity->getId(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
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
}
