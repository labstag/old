<?php

namespace Labstag\Controller;

use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Lib\ControllerLib;
use Labstag\Lib\GenericProviderLib;
use Labstag\Repository\OauthConnectUserRepository;
use Labstag\Service\OauthService;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Core\Security;

class OauthController extends ControllerLib
{

    /**
     * @var OauthService
     */
    private $oauthService;

    public function __construct(
        ContainerInterface $container,
        OauthService $oauthService,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        RouterInterface $router
    )
    {
        parent::__construct($container, $paginator, $requestStack, $router);
        $this->oauthService = $oauthService;
    }

    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/lost/{oauthCode}", name="connect_lost")
     */
    public function lostAction(
        Request $request,
        string $oauthCode,
        Security $security,
        OauthConnectUserRepository $repository
    ): RedirectResponse
    {
        /** @var User $user */
        $user = $security->getUser();
        /** @var string $referer */
        $referer = $request->headers->get('referer');
        $session = $request->getSession();
        $session->set('referer', $referer);
        /** @var string $url */
        $url = $this->generateUrl('front');
        if ('' == $referer) {
            $referer = $url;
        }

        /**
         * @var OauthConnectUser
         */
        $entity  = $repository->findOneOauthByUser($oauthCode, $user);
        $manager = $this->getDoctrine()->getManager();
        if ($entity instanceof OauthConnectUser) {
            $manager->remove($entity);
            $manager->flush();
            $this->addFlash('success', 'Connexion Oauh '.$oauthCode.' dissocié');
        }

        return $this->redirect($referer);
    }

    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/connect/{oauthCode}", name="connect_start")
     */
    public function connectAction(Request $request, string $oauthCode): RedirectResponse
    {
        /** @var GenericProviderLib $provider */
        $provider = $this->oauthService->setProvider($oauthCode);
        $session  = $request->getSession();
        /** @var string $referer */
        $referer = $request->headers->get('referer');
        $session->set('referer', $referer);
        /** @var string $url */
        $url = $this->generateUrl('front');
        if ('' == $referer) {
            $referer = $url;
        }

        if (!($provider instanceof GenericProviderLib)) {
            $this->addFlash('warning', 'Connexion Oauh impossible');

            return $this->redirect($referer);
        }

        $authUrl = $provider->getAuthorizationUrl();
        $session = $request->getSession();
        $referer = $request->headers->get('referer');
        $session->set('referer', $referer);
        $session->set('oauth2state', $provider->getState());

        return $this->redirect($authUrl);
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/connect/{oauthCode}/check", name="connect_check")
     */
    public function connectCheckAction(Request $request, string $oauthCode): RedirectResponse
    {
        /** @var GenericProviderLib $provider */
        $provider    = $this->oauthService->setProvider($oauthCode);
        $query       = $request->query->all();
        $session     = $request->getSession();
        $referer     = $session->get('referer');
        $oauth2state = $session->get('oauth2state');
        /** @var string $url */
        $url = $this->generateUrl('front');
        if ('' == $referer) {
            $referer = $url;
        }

        if (!($provider instanceof GenericProviderLib) || !isset($query['code']) || $oauth2state !== $query['state']) {
            $session->remove('oauth2state');
            $session->remove('referer');
            $this->addFlash('warning', "Probleme d'identification");

            return $this->redirect($referer);
        }

        try {
            /** @var AccessToken $tokenProvider */
            $tokenProvider = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $query['code'],
                ]
            );

            $session->remove('oauth2state');
            $session->remove('referer');
            /** @var UsageTrackingTokenStorage $tokenStorage */
            $tokenStorage = $this->get('security.token_storage');
            /** @var TokenInterface $token */
            $token        = $tokenStorage->getToken();
            if (!($token instanceof AnonymousToken)) {
                $userOauth = $provider->getResourceOwner($tokenProvider);
                $user      = $token->getUser();
                if (!is_object($user) || !($user instanceof User)) {
                    $this->addFlash('warning', "Probleme d'identification");
                    return $this->redirect($referer);
                }

                /** @var User $user */
                $this->addOauthToUser($oauthCode, $user, $userOauth);
            }

            return $this->redirect($referer);
        } catch (Exception $exception) {
            $this->addFlash('warning', "Probleme d'identification");

            return $this->redirect($referer);
        }
    }

    /**
     * @param mixed $oauthConnect
     */
    private function findOAuthIdentity(User $user, string $identity, string $client, &$oauthConnect = null): bool
    {
        $oauthConnects = $user->getOauthConnectUsers();
        foreach ($oauthConnects as $oauthConnect) {
            if ($oauthConnect->getName() == $client && $oauthConnect->getIdentity() == $identity) {
                return true;
            }
        }

        $oauthConnect = null;
        return false;
    }

    /**
     * @param GenericResourceOwner|ResourceOwnerInterface $userOauth
     */
    private function addOauthToUser(string $client, User $user, $userOauth): void
    {
        $data     = $userOauth->toArray();
        $identity = $this->oauthService->getIdentity($data, $client);
        $find     = $this->findOAuthIdentity($user, $identity, $client, $oauthConnect);
        $manager  = $this->getDoctrine()->getManager();
        /** @var OauthConnectUserRepository $repository */
        $repository = $manager->getRepository(OauthConnectUser::class);
        if (false === $find) {
            /** @var OauthConnectUser|null $oauthConnect */
            $oauthConnect = $repository->findOauthNotUser($user, $identity, $client);
            if (is_null($oauthConnect)) {
                $oauthConnect = new OauthConnectUser();
                $oauthConnect->setRefuser($user);
                $oauthConnect->setName($client);
            }

            /** @var User $refuser */
            $refuser = $oauthConnect->getRefuser();
            if ($refuser->getId() !== $user->getId()) {
                $oauthConnect = null;
            }
        }

        if ($oauthConnect instanceof OauthConnectUser) {
            $oauthConnect->setData($userOauth->toArray());
            $manager->persist($oauthConnect);
            $manager->flush();
            $this->addFlash('success', 'Compte associé');
            return;
        }

        $this->addFlash('warning', "Impossible d'associer le compte");
    }
}
