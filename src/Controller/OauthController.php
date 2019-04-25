<?php

namespace Labstag\Controller;

use Labstag\Entity\User;
use Labstag\Services\OauthServices;
use Labstag\Entity\OauthConnectUser;
use Labstag\Lib\AbstractControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class OauthController extends AbstractControllerLib
{
    /**
     * @var OauthServices
     */
    private $oauthServices;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->oauthServices = $this->container->get(OauthServices::class);
    }
    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/connect/{oauthCode}", name="connect_start")
     */
    public function connectAction(Request $request, string $oauthCode)
    {
        $provider = $this->oauthServices->setProvider($oauthCode);

        if (is_null($provider)) {
            $this->addFlash("warning", "Connexion Oauh impossible");
            return $this->redirect(
                $this->generateUrl('front')
            );
        }

        $authUrl = $provider->getAuthorizationUrl();
        $session = $request->getSession();
        $session->set('oauth2state', $provider->getState());
        return $this->redirect(
            $authUrl
        );
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/connect/{oauthCode}/check", name="connect_check")
     */
    public function connectCheckAction(Request $request, string $oauthCode)
    {
        $provider    = $this->oauthServices->setProvider($oauthCode);
        $query       = $request->query->all();
        $session     = $request->getSession();
        $oauth2state = $session->get('oauth2state');
        if (is_null($provider) || !isset($query['code']) || $oauth2state !== $query['state']) {
            $session->remove('oauth2state');
            $this->addFlash("warning", "Probleme d'identification");
            return $this->redirect(
                $this->generateUrl('front')
            );
        }

        try {
            $tokenProvider = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $query['code'],
                ]
            );

            $session->remove('oauth2state');
            $userOauth = $provider->getResourceOwner($tokenProvider);
            $token     = $this->get('security.token_storage')->getToken();
            if (!($token instanceof AnonymousToken)) {
                $user = $token->getUser();
                $this->addOauthToUser($oauthCode, $user, $userOauth);
            }

            return $this->redirect(
                $this->generateUrl('front')
            );
        } catch (Exception $e) {
            $this->addFlash("warning", "Probleme d'identification");
            return $this->redirect(
                $this->generateUrl('front')
            );
            exit();
        }
    }

    private function addOauthToUser(string $client, User $user, GenericResourceOwner $userOauth)
    {
        $oauthConnects = $user->getOauthConnectUsers();
        $find = 0;
        foreach($oauthConnects as $oauthConnect)
        { 
            if ($oauthConnect->getName() == $client)
            {
                $find = 1;
                break;
            }
        }

        if (0 === $find) {
            $oauthConnect = new OauthConnectUser();
            $oauthConnect->setRefuser($user);
            $oauthConnect->setName($client);
        }

        $oauthConnect->setData($userOauth->toArray());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($oauthConnect);
        $entityManager->flush();
        $message = $this->setMessagefindaddOauthToUser($find, $client, $user);
        $this->addFlash("success", $message);
    }

    private function setMessagefindaddOauthToUser($find, $client, $user)
    {
        if ($find == 0) {
            return "Compte ".$client." associé à l'utilisateur ".$user;
        }
        return $message =  "Compte ".$client." associé à l'utilisateur ".$user;
    }
}
