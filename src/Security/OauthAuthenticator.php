<?php

namespace Labstag\Security;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Service\OauthService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class OauthAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var string
     */
    private $route;

    /**
     * @var OauthService
     */
    private $OauthService;

    /**
     * @var string
     */
    private $oauthCode;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, OauthService $OauthService, RequestStack $requestStack, TokenStorageInterface $tokenStorage)
    {
        $this->container        = $container;
        $this->entityManager    = $entityManager;
        $this->urlGenerator     = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder  = $passwordEncoder;
        $this->requestStack     = $requestStack;
        $this->request          = $this->requestStack->getCurrentRequest();
        $this->OauthService     = $OauthService;
        $this->tokenStorage     = $tokenStorage;
        $this->oauthCode        = $this->request->attributes->get('oauthCode');
    }

    public function supports(Request $request)
    {
        $route       = $request->attributes->get('_route');
        $this->route = $route;
        $user        = $this->tokenStorage->getToken();

        return 'connect_check' === $route && (is_null($user) || !($user->getUser() instanceof User));
    }

    public function getCredentials(Request $request)
    {
        $provider    = $this->OauthService->setProvider($this->oauthCode);
        $query       = $request->query->all();
        $session     = $request->getSession();
        $oauth2state = $session->get('oauth2state');
        if (is_null($provider) || !isset($query['code']) || $oauth2state !== $query['state']) {
            return [];
        }

        try {
            $tokenProvider = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $query['code'],
                ]
            );
            $userOauth     = $provider->getResourceOwner($tokenProvider);

            $credentials['user'] = $userOauth;

            return $credentials;
        } catch (Exception $e) {
            return [];
        }
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        unset($userProvider);
        if (!isset($credentials['user'])) {
            throw new CustomUserMessageAuthenticationException(
                'Connexion impossible avec ce service.'
            );
        }

        $enm = $this->entityManager->getRepository(OauthConnectUser::class);

        $identity         = $this->OauthService->getIdentity(
            $credentials['user']->toArray(),
            $this->oauthCode
        );
        $oauthConnectUser = $enm->login($identity, $this->oauthCode);
        if (!$oauthConnectUser || '' == $identity) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException(
                'Username could not be found.'
            );
        }

        $user = $oauthConnectUser->getRefuser();
        if (!$user->isEnable()) {
            throw new CustomUserMessageAuthenticationException(
                'Username not activate.'
            );
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        unset($credentials, $user);

        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        unset($token);
        $getTargetPath = $this->getTargetPath(
            $request->getSession(),
            $providerKey
        );

        return new RedirectResponse($getTargetPath);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }
}
