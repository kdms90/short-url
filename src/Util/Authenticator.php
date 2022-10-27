<?php


namespace App\Util;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeHandlerInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

/**
 * Class Authenticator
 *
 * @since      2.0.0
 * @package    App
 * @subpackage App\Util
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class Authenticator
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;
    /** @var \Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface */
    private $sessionStrategy;
    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;
    /** @var \Symfony\Component\Security\Http\RememberMe\RememberMeHandlerInterface */
    private $rememberMeHandler;
    /**
     * @var \Symfony\Component\Security\Core\User\UserCheckerInterface
     */
    private $userChecker;
    /**
     * @var \Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory
     */
    private $encoder;

    /**
     * Authenticator constructor.
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface $sessionStrategy
     * @param \Symfony\Component\Security\Core\User\UserCheckerInterface $userChecker
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface $encoderFactory
     * @param \Symfony\Component\Security\Http\RememberMe\RememberMeHandlerInterface|null $rememberMeHandler
     */
    public function __construct(TokenStorageInterface $tokenStorage, SessionAuthenticationStrategyInterface $sessionStrategy, UserCheckerInterface $userChecker, RequestStack $requestStack, PasswordHasherFactoryInterface $encoderFactory, RememberMeHandlerInterface $rememberMeHandler = null)
    {
        $this->tokenStorage      = $tokenStorage;
        $this->sessionStrategy   = $sessionStrategy;
        $this->userChecker       = $userChecker;
        $this->requestStack      = $requestStack;
        $this->encoder           = $encoderFactory;
        $this->rememberMeHandler = $rememberMeHandler;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param string $firewallName
     * @param \Symfony\Component\HttpFoundation\Response|null $response
     */
    final public function authenticate(UserInterface $user, string $firewallName = 'lmp_db_provider', Response $response = null)
    {
        $this->userChecker->checkPreAuth($user);
        $token   = $this->createToken($firewallName, $user);
        $request = $this->requestStack->getCurrentRequest();
        if (null !== $request) {
            $this->sessionStrategy->onAuthentication($request, $token);

            if (null !== $response && null !== $this->rememberMeHandler) {
                $this->rememberMeHandler->createRememberMeCookie($user);
            }
        }

        $this->tokenStorage->setToken($token);
    }

    /**
     * @param string $firewall
     * @param UserInterface $user
     *
     * @return UsernamePasswordToken
     */
    private function createToken($firewall, UserInterface $user)
    {
        return new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
    }
}
