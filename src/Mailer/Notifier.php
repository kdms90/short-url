<?php

namespace App\Mailer;

use App\Util\Push;
use App\Util\Tools;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class Notifier
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    App\Mailer
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
abstract class Notifier
{
    /** @var  TranslatorInterface */
    protected $translator;
    /** @var  \Swift_Mailer */
    protected $mailer;
    /** @var  UrlGeneratorInterface */
    protected $router;
    /** @var  Environment */
    protected $twig;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /** @var Tools */
    protected $tools;

    protected $textBody;

    protected $subject;

    protected $fromEmail       = 'info@lmp.org';
    protected $fromEmailName   = 'The Best Group';
    protected $senderEmail     = 'info@lmp.org';
    protected $senderEmailName = 'The Best Group';
    protected $replyToName     = 'The Best Group';
    protected $replyToEmail    = 'noreply@lmp.org';
    /**
     * @var \App\Util\Push pour ajouter les notification dans l'espace d'un acteur
     */
    protected $push;

    /**
     * Notifier constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Swift_Mailer $mailer
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $router
     * @param \Twig\Environment $twig
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \App\Util\Tools $tools
     * @param \App\Util\Push $push
     */
    public function __construct(EntityManagerInterface $entityManager, Swift_Mailer $mailer, UrlGeneratorInterface $router, Environment $twig, TranslatorInterface $translator, Tools $tools, Push $push)
    {
        $this->mailer     = $mailer;
        $this->router     = $router;
        $this->translator = $translator;
        $this->twig       = $twig;
        $this->tools      = $tools;
        $this->em         = $entityManager;
        $this->push       = $push;
    }

    /**
     * Just to check if email go
     */
    public function testEmails()
    {
        $content        = 'Holisticly underwhelm web-enabled niche markets and 24/365 schemas. Conveniently revolutionize backend products vis-a-vis distinctive models. Energistically evolve world-class products through stand-alone value. Seamlessly disintermediate orthogonal.';
        $this->textBody = $content;
        $context        = [
            'content' => $content,
            'title'   => 'Test envoi de mail',
        ];
        $content        = $this->twig->render('notification/front/email/email-layout.html.twig', $context);
        $this->sendMessage($content, 'info@mk-groupe.com', 'mokan12467@mailt.top', null);
    }

    /**
     * @param string $content
     * @param string $fromEmail
     * @param string $toEmail
     * @param string $replyTo
     * @param array $attachments
     * @param array $Ccs
     *
     * @return int
     */
    protected function sendMessage($content, $fromEmail, $toEmail, $replyTo = 'noreply@lmp.org', $attachments = [], $Ccs = [])
    {
        if (!$replyTo)
            $replyTo = $this->replyToEmail;
        $message = new Swift_Message();
        $message->setSubject($this->subject)
            ->setFrom($fromEmail, $this->fromEmailName)
            ->setSender($fromEmail, $this->senderEmailName)
            ->setTo(trim($toEmail))
            ->setReplyTo($replyTo, $this->replyToName);
        if (count($Ccs)) {
            $first = true;
            foreach ($Ccs as $Cc) {
                if ($first) {
                    $first = false;
                    $message->setCc($Cc);
                } else
                    $message->addCc($Cc);
            }
        }
        if (is_array($attachments) && count($attachments)) {
            foreach ($attachments as $attachment) {
                if (file_exists($attachment)) {
                    $message->attach(Swift_Attachment::fromPath($attachment));
                }
            }
        }
        $message->addPart($this->textBody, 'text/plain');
        $message->setBody($content, 'text/html');
        return $this->mailer->send($message);
    }
}
