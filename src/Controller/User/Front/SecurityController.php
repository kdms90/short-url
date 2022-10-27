<?php

namespace App\Controller\User\Front;


use App\Contracts\TokenGeneratorInterface;
use App\Controller\AbstractFrontController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SecurityController
 * @package App\Controller\User\Front
 */
class SecurityController extends AbstractFrontController
{
    /**
     * @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface|null
     */
    private $tokenManager;

    /**
     * SecurityController constructor.
     * @param \Symfony\Component\HttpFoundation\RequestStack $request
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface $params
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \App\Contracts\TokenGeneratorInterface $tokenGenerator
     * @param \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface|null $tokenManager
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, ParameterBagInterface $params, EventDispatcherInterface $eventDispatcher, TokenGeneratorInterface $tokenGenerator, CsrfTokenManagerInterface $tokenManager = null)
    {
        parent::__construct($request, $entityManager, $translator, $params, $eventDispatcher, $tokenGenerator);
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form         = $this->container->get('form.factory')
            ->createNamedBuilder('login_form')
            ->add('_username', null, ['label' => 'Email'])
            ->add('_password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('ok', SubmitType::class, ['label' => 'Me connecter', 'attr' => ['class' => 'btn-primary btn-block']])
            ->getForm();
        $csrfToken    = $this->tokenManager
            ? $this->tokenManager->getToken('authenticate')->getValue()
            : null;
        return $this->render('user/front/auth/login.html.twig', [
            'mainNavLogin'  => true, 'title' => 'Connexion',
            'form'          => $form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token'    => $csrfToken,
        ]);
    }

    /**
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login2(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form         = $this->get('form.factory')
            ->createNamedBuilder('login_form')
            ->add('_username', null, ['label' => 'Email'])
            ->add('_password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('ok', SubmitType::class, ['label' => 'Me connecter', 'attr' => ['class' => 'btn-primary btn-block']])
            ->getForm();
        $csrfToken    = $this->tokenManager
            ? $this->tokenManager->getToken('authenticate')->getValue()
            : null;
        return $this->render('user/front/auth/login2.html.twig', [
            'mainNavLogin'  => true, 'title' => 'Connexion',
            'form'          => $form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token'    => $csrfToken,
        ]);
    }
}
