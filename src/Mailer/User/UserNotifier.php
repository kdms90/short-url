<?php

namespace App\Mailer\User;

use App\Mailer\Notifier;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Class Notifier
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    DB\UserBundle\Mailer
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class UserNotifier extends Notifier
{
    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $access
     * @param string $locale
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendConfirmationEmailMessage(UserInterface $access, $locale = 'en')
    {
        $url = $this->router->generate('app_user_front_confirm_account', ['token' => $access->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL);
        /** @var EmailTemplate $template */
        $template = '"<p><b>Bonjour M./Mdme/Mlle {{ user_fullname }},</b></p><p style=\"margin-top: 10px;\">Votre compte a été crée sur Short URL. Nous vous prions de cliquer sur le lien suivant pour le valider <a style=\"color: #0077b5; text-decoration: underline;\" href=\"{{confirmation_url}}\">{{confirmation_url}}</a> </p>';
        //We send message
        $this->subject = "Confirmation de votre compte";
        $tplPatern     = uniqid('string_template_', true);
        $env           = new Environment(new ArrayLoader([$tplPatern => $template]));
        $env->setCache(false);
        $content        = $env->render(
            $tplPatern, [
            'user_fullname'      => $access->getFullname(),
            'user_email_address' => $access->getEmail(),
            'confirmation_url'   => $url,
            'login_uri'          => $this->router->generate('app_user_front_login', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        $this->textBody = $content;
        $context        = [
            'content' => $content,
            'title'   => "Votre compte",
        ];
        $content        = $this->twig->render('notification/front/email/email-layout.html.twig', $context);
        $this->sendMessage($content, 'info@short-url.com', $access->getEmail(), null);
    }
}
