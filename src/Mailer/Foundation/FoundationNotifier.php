<?php

namespace App\Mailer\Foundation;

use App\Entity\Contact;
use App\Entity\Notification\EmailTemplate;
use App\Entity\Parameter;
use App\Mailer\Notifier;

/**
 * Class Notifier
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    App\Mailer\Foundation
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class FoundationNotifier extends Notifier
{
    /**
     * @param \App\Entity\Contact $contact
     * @param string $locale
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function notifyContactAuthor(Contact $contact, $locale = 'fr')
    {
        $this->subject = 'Contact';
        $shopName      = 'The Best Group';
        /** @var EmailTemplate $template */
        $content        = 'Bonjour ' . $contact->getFullname();
        $content        .= '<p>Votre message a été bien envoyé à <strong>' . $shopName . '</strong></p>';
        $content        .= '<p>Sujet :<strong>' . $contact->getSubject() . '</strong></p>';
        $content        .= '<p>Email :<strong>' . $contact->getEmail() . '</strong></p>';
        $content        .= '<p>Contenu du message : <strong>' . $contact->getMessage() . '</strong></p>';
        $content        .= '<p>Vous serez notifié lorsqu\'une réponse vous sera adressée.</p>';
        $context        = [
            'content' => $content,
            'title'   => 'Contact ',
        ];
        $this->textBody = $content;
        $content        = $this->twig->render('notification/front/email/email-layout.html.twig', $context);
        //We send message
        $this->sendMessage($content, 'info@mk-groupe.com', $contact->getEmail(), null);
    }

    /**
     * @param \App\Entity\Contact $contact
     * @param string $locale
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function notifyContactCompany(Contact $contact, $locale = 'fr')
    {
        $authorName = $contact->getFullname();
        /** @var EmailTemplate $template */
        $content        = 'Bonjour The Best Group';
        $content        .= '<p>Vous avez reçu un message de <strong>' . $authorName . '</strong></p>';
        $content        .= '<p>Sujet :<strong>' . $contact->getSubject() . '</strong></p>';
        $content        .= '<p>Email :<strong>' . $contact->getEmail() . '</strong></p>';
        $content        .= '<p>Contenu du message : <strong>' . $contact->getMessage() . '</strong></p>';
        $content        .= '<p>Veuillez accéder à votre compte pour lui donner une réponse.</p>';
        $context        = [
            'content' => $content,
            'title'   => 'Contact de ' . $authorName,
        ];
        $this->textBody = $content;
        $content        = $this->twig->render('notification/front/email/email-layout.html.twig', $context);
        /** @var Parameter $contactEmails */
        $contactEmails = $this->em->getRepository('App\Entity\Parameter')->findOneBy(['code' => Parameter::APP_ADMIN_EMAIL]);
        if ($contactEmails) {
            $emails = explode(',', $contactEmails->getValue());
            foreach ($emails as $sentTo)
                $this->sendMessage($content, 'info@mk-groupe.com', trim($sentTo), null);
        }

    }


    /**
     * @param        $email
     * @param string $locale
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function notifySubscriberByEmailToNewsletter($email, $locale = 'fr')
    {
        $this->subject = '[The Best Group] Votre abonnement';
        /** @var EmailTemplate $template */
        $content        = 'Bonjour, <br/>Nous vous informons que votre inscription à la newsletter a été bien enregistrée.';
        $context        = [
            'content' => $content,
            'title'   => 'Votre abonnement à la lettre d\'information',
        ];
        $this->textBody = $content;
        $content        = $this->twig->render('notification/front/email/email-layout.html.twig', $context);
        //We send message
        $this->sendMessage($content, 'info@mk-groupe.com', $email, null);
    }
}
