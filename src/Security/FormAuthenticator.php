<?php

namespace Labstag\Security;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\User;
use Labstag\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormAuthenticator extends AbstractFormLoginAuthenticator
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
     * @var string
     */
    private $route;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->container        = $container;
        $this->entityManager    = $entityManager;
        $this->urlGenerator     = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder  = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        $route       = $request->attributes->get('_route');
        $this->route = $route;

        return 'app_login' === $route && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $login       = $request->request->get('login');
        $credentials = [
            'username' => $login['username'],
            'password' => $login['password'],
            '_token'   => $login['_token'],
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        unset($userProvider);
        $token = new CsrfToken('login', $credentials['_token']);
        if (!$this->csrfTokenManager->isTokenValid($token) && 'connect_check' != $this->route) {
            throw new InvalidCsrfTokenException();
        }

        /** @var UserRepository $enm */
        $enm = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $enm->login($credentials['username']);
        if (!($user instanceof User)) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        if (!$user->isEnable()) {
            throw new CustomUserMessageAuthenticationException('Username not activate.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid(
            $user,
            $credentials['password']
        );
    }

    /**
     * @param string $providerKey
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        unset($token);
        $getTargetPath = (string) $this->getTargetPath(
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
