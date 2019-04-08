<?php

namespace Labstag\Security;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\User;
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

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
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
    }

    public function supports(Request $request)
    {
        $route       = $request->attributes->get('_route');
        $this->route = $route;

        $test1 = 'app_login' === $route && $request->isMethod('POST');
        $user  = $this->container->get('security.token_storage')->getToken();
        // $test2 = 'connect_check' === $route && (is_null($user) || !($user->getUser() instanceof User));

        // return $test1 || $test2;
        return $test1;
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
        $token = new CsrfToken('login', $credentials['_token']);
        if (!$this->csrfTokenManager->isTokenValid($token) && 'connect_check' != $this->route) {
            throw new InvalidCsrfTokenException();
        }

        $enm  = $this->entityManager->getRepository(User::class);
        $user = $enm->login($credentials['username']);
        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException(
                'Username could not be found.'
            );
        }

        if (!$user->isEnable()) {
            throw new CustomUserMessageAuthenticationException(
                'Username not activate.'
            );
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
        // return new RedirectResponse(
        //     $this->urlGenerator->generate('some_route')
        // );
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }
}
