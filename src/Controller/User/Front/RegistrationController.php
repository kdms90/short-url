<?php

namespace App\Controller\User\Front;

use App\Controller\AbstractFrontController;
use App\Entity\User\Access;
use App\Form\User\AccessType;
use App\Util\Authenticator;
use Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class RegistrationController
 * @package App\Controller\User\Front
 */
class RegistrationController extends AbstractFrontController
{
    /**
     * @param \App\Util\Authenticator $authenticator
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function register(Authenticator $authenticator, UserPasswordHasherInterface $passwordEncoder)
    {
        $access = new Access();
        $form   = $this->createForm(AccessType::class, $access, ['isFront' => true]);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($this->request);
        if ($form->isSubmitted()) {
            $plainPassword = $form->get('plainPassword')->get('mainField')->getData();
            if (empty($plainPassword))
                $form->get('plainPassword')->get('mainField')->addError(new FormError($this->text->trans('Veuillez entrer un mot de passe', [], 'actor')));
            else if (strlen($plainPassword) < 6) {
                $form->get('plainPassword')->get('mainField')->addError(new FormError($this->text->trans('Le mot de passe doit avoir au moins 6 caractères', [], 'actor')));
            }
            if ($form->isValid()) {
                $actor   = $access->getActor();
                $access->addRole('ROLE_SUPER_ADMIN');
                $access->setConfirmationToken($this->tokenGenerator->generateToken());
                // 3) Encode the password (you could also do this via Doctrine listener)
                $password = $passwordEncoder->hashPassword($access, $access->getPlainPassword());
                $access->setPassword($password);
                try {
                    $this->em->getConnection()->beginTransaction();
                    $this->em->persist($actor);
                    $this->em->persist($access);
                    $this->em->flush();
                    $this->em->clear();
                    $this->em->getConnection()->commit();
                    $data['form']       = $this->renderView('user/front/registration/ajax/confirm-email.html.twig', [
                        'email' => $access->getEmail(),
                    ]);
                    return $this->json($data);
                } catch (Exception $e) {
                    $this->em->getConnection()->rollBack();
                    $form->addError(new FormError($e->getMessage()));
                    $data['form'] = $this->renderView('user/front/registration/ajax/form.html.twig', [
                        'form' => $form->createView(),
                    ]);
                    return $this->json($data);
                }
            }
            $data['form'] = $this->renderView('user/front/registration/ajax/form.html.twig', [
                'form' => $form->createView(),
            ]);
            return $this->json($data);
        }
        return $this->render('user/front/registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Receive the confirmation token from user email provider, login the user.
     *
     * @param \App\Util\Authenticator $authenticator
     * @param string $token
     *
     * @return Response
     */
    public function confirm(Authenticator $authenticator, $token)
    {
        $userManager = $this->em->getRepository('App:User\Access');
        $user        = $userManager->findOneBy(['confirmationToken' => $token]);
        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }
        $user->setConfirmationToken(null);
        $user->setIsActive(true);
        try {
            $this->em->flush($user);
            $authenticator->authenticate($user);
            return $this->redirectToRoute('app_user_front_profile');
        } catch (Exception $e) {
        }
    }

}
