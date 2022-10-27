<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class AbstractFrontController
 *
 * @link       http://github.com/kdms90
 *
 * @since      4.0.0
 * @package App\Controller
 * @subpackage App\Controller\Front
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
abstract class AbstractFrontController extends AbstractFoundationController
{
    /**
     * Renders a view.
     * We override it to add global data
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     * @param Response $response A response instance
     *
     * @return Response A Response instance
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $this->requiredView();
        return parent::render($view, array_merge($this->viewData, $parameters), $response);
    }

    /**
     * Enable to set default view content
     */
    private function requiredView()
    {
        $this->viewData['pageTitle']     = $this->pageTitle;
        $this->viewData['pageSubTitle']  = $this->pageSubTitle;
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     *
     * @final since version 3.4
     */
    protected function renderView(string $view, array $parameters = []): string
    {
        $this->requiredView();
        return parent::renderView($view, array_merge($this->viewData, $parameters));
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $errors
     * @param string $password
     * @param string $confirmPlainPassword
     * @param string $propertyPath
     * @return array|\Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected function validatePassword(ConstraintViolationListInterface $errors, string $password, string $confirmPlainPassword, string $propertyPath = "confirmPlainPassword"): ConstraintViolationListInterface
    {
        if (strlen($password) < 8) {
            $errors->add(new ConstraintViolation('Mot de passe trop court (8 caractères minimum)!', null, [], $confirmPlainPassword, $propertyPath, $confirmPlainPassword));
        } else {
            if ($password != $confirmPlainPassword) {
                $errors->add(new ConstraintViolation('Les mots de passe ne correspondent pas!', null, [], $confirmPlainPassword, $propertyPath, $confirmPlainPassword));
            }
        }
        if (!preg_match("#[0-9]+#", $password)) {
            $errors->add(new ConstraintViolation('Le mot de passe doit inclure au moins un numéro!', null, [], $confirmPlainPassword, $propertyPath, $confirmPlainPassword));
        }
        if (!preg_match("#[a-z]+#", $password)) {
            $errors->add(new ConstraintViolation('Le mot de passe doit contenir au moins une lettre minuscule!', null, [], $confirmPlainPassword, $propertyPath, $confirmPlainPassword));
        }
        if (!preg_match("#[A-Z]+#", $password)) {
            $errors->add(new ConstraintViolation('Le mot de passe doit contenir au moins une lettre majuscule!', null, [], $confirmPlainPassword, $propertyPath, $confirmPlainPassword));
        }
        return $errors;
    }
}
