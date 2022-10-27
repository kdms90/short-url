<?php


namespace App\Util;

use App\Entity\Notification\Notification;
use App\Entity\User\Actor;
use App\Entity\User\Company;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class Push
 * @package App\Util
 */
class Push
{
    /**
     * @var EntityManager entity manager object.
     */
    protected $em;
    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * Push constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->em         = $entityManager;
        $this->translator = $translator;
    }

    /**
     * Permet de pusher une notification
     *
     * @param \App\Entity\User\Company $company
     * @param \App\Entity\User\Actor $actor acteur qui reçoit la notification
     * @param int $resourceType type de la ressource
     * @param string $resourceLink lien vers la ressource
     */
    public function add(Actor $actor, $resourceType, $resourceLink)
    {
        try {
            $notification = new Notification();
            $notification->setActor($actor);
            $notification->setResourceType($resourceType);
            $notification->setResourceLink($resourceLink);
            $notification->setIconClass($this->getIconClass($resourceType));
            $this->em->persist($notification);
            $this->em->flush();
            $this->em->clear();
        } catch (Exception $e) {
        }
    }

    /**
     * @param $type
     * @return mixed|string
     */
    public function getIconClass($type)
    {
        $class = [
            Notification::APP_EVALUATION_AFTER_RESOURCE_EVALUATION    => 'ik ik-info',
            Notification::APP_EVALUATION_PENDING_EVALUATION_EVALUATOR => 'ik ik-more-horizontal',
            Notification::APP_EVALUATION_THANK_EVALUATOR              => 'ik ik-info',
            Notification::APP_USER_CONFIRMATION_REGISTRATION_EMAIL    => 'ik ik-info',
            Notification::APP_USER_RELAUCH_UPDATE_PROFILE             => 'ik ik-user-minus',
        ];
        return !empty($class[$type]) ? $class[$type] : 'ik ik-alert-circle';
    }

    /**
     * Permet de récupérer les notifications d'un acteur dans une company
     *
     * @param \App\Entity\User\Company $company
     * @param \App\Entity\User\Actor $actor
     * @param $itemPerPage
     * @param $page
     * @return mixed
     */
    public function get(Company $company, Actor $actor, $itemPerPage = 10, $page = 1)
    {
        return $this->em->getRepository("App:Notification\Notification")->retrieveOfActor($company->getId(), $actor->getId(), $itemPerPage, $page);
    }

    /**
     * Permet de récupérer le nombre de notification non lues
     *
     * @param \App\Entity\User\Company $company
     * @param \App\Entity\User\Actor $actor
     * @return integer
     */
    public function count(Company $company, Actor $actor)
    {
        return $this->em->getRepository("App:Notification\Notification")->retrieveOfActor($company->getId(), $actor->getId(), 1, 1)->count();
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return [
            Notification::APP_OPPORTUNITY_GOOD_FOR_SUBMISSION         => [
                'label'   => $this->translator->trans('Opportunité à évaluer', [], 'notification'),
                'content' => $this->translator->trans('Vous avez une nouvelle opportunité à évaluer', [], 'notification'),
            ],
            Notification::APP_OPPORTUNITY_CHANGE_GO_STATUS            => [
                'label'   => $this->translator->trans('Opportunité', [], 'notification'),
                'content' => $this->translator->trans('Il y a une nouvelle proposition à rediger', [], 'notification'),
            ],
            Notification::APP_EVALUATION_AFTER_RESOURCE_EVALUATION    => [
                'label'   => $this->translator->trans('Evaluation', [], 'notification'),
                'content' => $this->translator->trans('Vous avez été évaluer', [], 'notification'),
            ],
            Notification::APP_EVALUATION_PENDING_EVALUATION_EVALUATOR => [
                'label'   => $this->translator->trans('Evaluation', [], 'notification'),
                'content' => $this->translator->trans('Nouvelle évaluation en attente', [], 'notification'),
            ],
            Notification::APP_EVALUATION_THANK_EVALUATOR              => [
                'label'   => $this->translator->trans('Evaluation', [], 'notification'),
                'content' => $this->translator->trans('Merci d\'avoir participer à l\'évaluation...', [], 'notification'),
            ],
            Notification::APP_USER_CONFIRMATION_REGISTRATION_EMAIL    => [
                'label'   => $this->translator->trans('Bon pour validation', [], 'notification'),
                'content' => $this->translator->trans('Bon pour validation', [], 'notification'),
            ],
            Notification::APP_USER_RELAUCH_UPDATE_PROFILE             => [
                'label'   => $this->translator->trans('Demande de mise à jour profil', [], 'notification'),
                'content' => $this->translator->trans('La mise à jour de votre profil est attendue', [], 'notification'),
            ],
        ];
    }
}
