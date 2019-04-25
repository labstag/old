<?php

namespace Labstag\Security;

use Labstag\Entity\User;
use Labstag\Services\OauthServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Labstag\Entity\OauthConnectUser;

class OauthAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

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
     * @var String
     */
    private $route;

    /**
     * @var OauthServices
     */
    private $oauthServices;

    /**
     * @var String
     */
    private $oauthCode;
    /**
     * @var Request
     */
    protected $request;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->container        = $container;
        $this->entityManager    = $entityManager;
        $this->urlGenerator     = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder  = $passwordEncoder;
        $this->requestStack     = $container->get('request_stack');
        $this->request          = $this->requestStack->getCurrentRequest();
        $this->oauthServices    = $this->container->get(OauthServices::class);
        $this->oauthCode        = $this->request->attributes->get('oauthCode');
    }

    public function supports(Request $request)
    {
        $route           = $request->attributes->get('_route');
        $this->route     = $route;
        $user            = $this->container->get('security.token_storage')->getToken();
        
        return 'connect_check' === $route && (is_null($user) || !($user->getUser() instanceof User));
    }

    public function getCredentials(Request $request)
    {
        $provider    = $this->oauthServices->setProvider($this->oauthCode);
        $query       = $request->query->all();
        $session     = $request->getSession();
        $oauth2state = $session->get('oauth2state');
        if (is_null($provider) || !isset($query['code']) || $oauth2state !== $query['state']) {
            $credentials = [];

            return $credentials;
        }

        try {
            $tokenProvider = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $query['code'],
                ]
            );
            $userOauth = $provider->getResourceOwner($tokenProvider);

            $credentials['user'] = $userOauth;

            return $credentials;
        } catch (Exception $e) {
            $credentials = [];

            return $credentials;
        }
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!isset($credentials['user'])) {
            throw new CustomUserMessageAuthenticationException(
                'Connexion impossible avec ce service.'
            );
        }

        $enm = $this->entityManager->getRepository(OauthConnectUser::class);

        $identity = $this->oauthServices->getIdentity(
            $credentials['user']->toArray(),
            $this->oauthCode
        );
        $oauthConnectUser = $enm->login($identity, $this->oauthCode);
        if (!$oauthConnectUser) {
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
        return true;
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ) {
        $getTargetPath = $this->getTargetPath(
            $request->getSession(),
            $providerKey
        );
        if ($targetPath = $getTargetPath) {
            return new RedirectResponse($targetPath);
        }

        // For example :
        return new RedirectResponse(
            $this->urlGenerator->generate('front')
        );
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }
}
