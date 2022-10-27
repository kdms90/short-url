<?php

namespace App\Controller\User\Back;

use App\Contracts\FoundationInterface;
use App\Controller\AbstractBackController;
use App\Entity\User\Actor;
use App\Entity\User\CurrentWorkspace;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ProfileController
 * @package App\Controller\User\Back
 * @Security("is_granted('ROLE_ACCESS_BO')")
 */
class ProfileController extends AbstractBackController
{
    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $this->initHeaderContents();
        /** @var Actor[] $entities */
        $entities      = $this->em->getRepository('App\Entity\User\Actor')->retrieve($this->nbItemsPerPage, 1);
        $this->itemsNb = count($entities);
        if ($this->companyType === CurrentWorkspace::CONSULTANT_TYPE) {
            $entity = $this->company->getActor()->getConsultant();
            /** @var \App\Entity\User\ConsultantExpertise[] $items */
            $items = $this->em->getRepository('App:User\ConsultantExpertise')->retrieveOfConsultant($entity->getId(), 10, 1);
            return $this->render('user/back/profile/index-consultant.html.twig', [
                'item'       => $entity,
                'expertises' => $items,
            ]);
        } else {
            return $this->render('user/back/profile/index.html.twig', [
                'item'       => $this->getActor(),
                'expertises' => [],
            ]);
        }
    }

    /**
     * Implements this method for init index actions.
     * @param \App\Contracts\FoundationInterface|null $entity
     */
    protected function initHeaderContents(FoundationInterface $entity = null)
    {
        $this->addPath   = $this->generateUrl('app_user_back_profile_settings');
        $this->addTitle  = $this->text->trans('Paramètres du profil', [], 'user');
        $this->pageTitle = $this->text->trans('Mon profil', [], 'user');
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function settings()
    {
        $this->addPath   = $this->generateUrl('app_user_back_profile');
        $this->addTitle  = $this->text->trans('Mon profil', [], 'user');
        $this->pageTitle = $this->text->trans('Paramètres profil', [], 'user');
        /** @var Actor[] $entities */
        $entities      = $this->em->getRepository('App\Entity\User\Actor')->retrieve($this->nbItemsPerPage, 1);
        $this->itemsNb = count($entities);
        return $this->render('user/back/profile/settings.html.twig', [
            'item' => $this->getActor(),
        ]);
    }
}
