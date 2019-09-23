<?php

namespace Labstag\Controller;

use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\OauthConnectUserRepository;
use Labstag\Service\OauthService;
use League\OAuth2\Client\Provider\GenericResourceOwner;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Security;

class OauthController extends ControllerLib
{

    /**
     * @var OauthService
     */
    private $OauthService;

    public function __construct(ContainerInterface $container, OauthService $OauthService, PaginatorInterface $paginator, RequestStack $requestStack, RouterInterface $router)
    {
        parent::__construct($container, $paginator, $requestStack, $router);
        $this->OauthService = $OauthService;
    }

    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/lost/{oauthCode}", name="connect_lost")
     */
    public function lostAction(Request $request, string $oauthCode, Security $security, OauthConnectUserRepository $repository)
    {
        $user    = $security->getUser();
        $referer = $request->headers->get('referer');
        $session = $request->getSession();
        $session->set('referer', $referer);
        $url = $this->generateUrl('front');
        if ('' != $referer) {
            $url = $referer;
        }

        $entity  = $repository->findOneOauthByUser($oauthCode, $user);
        $manager = $this->getDoctrine()->getManager();
        if ($entity) {
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
    public function connectAction(Request $request, string $oauthCode)
    {
        $provider = $this->OauthService->setProvider($oauthCode);
        $session  = $request->getSession();
        $referer  = $request->headers->get('referer');
        $session->set('referer', $referer);
        $url = $this->generateUrl('front');
        if ('' != $referer) {
            $url = $referer;
        }

        if (is_null($provider)) {
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
    public function connectCheckAction(Request $request, string $oauthCode)
    {
        $provider    = $this->OauthService->setProvider($oauthCode);
        $query       = $request->query->all();
        $session     = $request->getSession();
        $referer     = $session->get('referer');
        $oauth2state = $session->get('oauth2state');
        $url         = $this->generateUrl('front');
        if ('' != $referer) {
            $url = $referer;
        }

        if (is_null($provider) || !isset($query['code']) || $oauth2state !== $query['state']) {
            $session->remove('oauth2state');
            $session->remove('referer');
            $this->addFlash('warning', "Probleme d'identification");

            return $this->redirect($referer);
        }

        try {
            $tokenProvider = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $query['code'],
                ]
            );

            $session->remove('oauth2state');
            $session->remove('referer');
            $userOauth = $provider->getResourceOwner($tokenProvider);
            $token     = $this->get('security.token_storage')->getToken();
            if (!($token instanceof AnonymousToken)) {
                $user = $token->getUser();
                $this->addOauthToUser($oauthCode, $user, $userOauth);
            }

            return $this->redirect($referer);
        } catch (Exception $e) {
            $this->addFlash('warning', "Probleme d'identification");

            return $this->redirect($referer);
        }
    }

    private function addOauthToUser(string $client, User $user, GenericResourceOwner $userOauth)
    {
        $oauthConnects = $user->getOauthConnectUsers();
        $find          = 0;
        $data          = $userOauth->toArray();
        $identity      = $this->OauthService->getIdentity($data, $client);
        // @var OauthConnectUser
        foreach ($oauthConnects as $oauthConnect) {
            if ($oauthConnect->getName() == $client && $oauthConnect->getIdentity() == $identity) {
                $find = 1;

                break;
            }
        }

        $manager    = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository(OauthConnectUser::class);
        // @var OauthConnectUserRepository $repository
        if (0 == $find) {
            $auths = $repository->findOauthNotUser($user, $identity, $client);
            if (!is_null($auths)) {
                $find = 1;
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
        $this->addFlash('success', $message);
    }

    private function setMessagefindaddOauthToUser($find, $client, $user)
    {
        if (0 == $find) {
            return 'Compte '.$client." associé à l'utilisateur ".$user;
        }

        return 'Compte '.$client." associé à l'utilisateur ".$user;
    }
}
