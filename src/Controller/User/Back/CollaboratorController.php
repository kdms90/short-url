<?php

namespace App\Controller\User\Back;

use App\Contracts\FoundationInterface;
use App\Controller\AbstractBackController;
use App\Entity\User\Actor;
use App\Entity\User\Collaborator;
use App\Entity\User\CurrentWorkspace;
use App\Form\User\CollaboratorType;
use App\Util\Tools;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class CollaboratorController
 * @package App\Controller\User\Back
 * @Security("is_granted('ROLE_ACCESS_BO')")
 */
class CollaboratorController extends AbstractBackController
{
    /**
     * All controller must implement bulk method for perfom delete group on entities
     *
     * @return mixed
     *
     * @Security("is_granted('CAN_DELETE_COLLABORATOR')")
     * @throws \App\Exception\ContextException
     * @throws \Doctrine\ORM\ORMException
     */
    public function bulkDelete()
    {
        $this->validContext();
        if ($this->request->isMethod('post')) {
            $ids = explode(',', $this->request->get('items'));
            foreach ($ids as $id) {
                $entity = $this->em->getRepository('App\Entity\User\Collaborator')->find((int)$id);
                if ($entity instanceof FoundationInterface) {
                    $entity->setDeleted(true);
                }
            }
            try {
                $this->em->flush();
                $this->notify($this->text->trans('operation_status', [], 'external'), $this->text->trans('well_deletion', [], 'external'));

                return $this->redirectToRoute('app_user_back_collaborators');
            } catch (OptimisticLockException $e) {
            }
        }
        $this->notify($this->text->trans('operation_status', [], 'external'), 'Une erreur s\'est collaboratore lors de la suppression', 'update-danger');
        return $this->redirectToRoute('app_user_back_collaborators');
    }

    /**
     * Affiche la liste de tous les états collaborator
     *
     * @param \App\Util\Tools $tools
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Security("is_granted('CAN_VIEW_COLLABORATOR')")
     */
    public function index(Tools $tools, $page = 1)
    {
        $this->initHeaderContents();
        /** @var Collaborator[] $entities */
        $manager       = $this->em->getRepository('App\Entity\User\Collaborator');
        $fromDate      = $this->request->query->get('fromDate');
        $toDate        = $this->request->query->get('toDate');
        $query         = (string)$this->request->query->get('query', '');
        $fDate         = $tools->isValidDateTimeString('d/m/Y', $fromDate) ? \DateTime::createFromFormat('d/m/Y', $fromDate) : false;
        $tate          = $tools->isValidDateTimeString('d/m/Y', $toDate) ? \DateTime::createFromFormat('d/m/Y', $toDate) : false;
        $entities      = $manager->retrieve($this->nbItemsPerPage, $page, $this->locale, $query, $fDate, $tate);
        $this->itemsNb = count($entities);
        return $this->render('user/back/collaborator/index.html.twig', [
            'entities'       => $entities,
            'nbPages'        => $this->nbItemsPerPage,
            'nb_page'        => ceil($this->itemsNb / $this->nbItemsPerPage) ?: 1,
            'page'           => $page,
            'paginationPath' => 'app_user_back_collaborators',
        ]);
    }

    /**
     * Implements this method for init index actions.
     * @param \App\Contracts\FoundationInterface|null $entity
     */
    protected function initHeaderContents(FoundationInterface $entity = null)
    {
        if ($this->isGranted('CAN_ADD_COLLABORATOR')) {
            $this->addPath  = $this->generateUrl('app_user_back_collaborator_add');
            $this->addTitle = $this->text->trans('Créer un utilisateur', [], 'user');
        }
        if ($this->isGranted('CAN_DELETE_COLLABORATOR')) {
            $this->bulkDeleteItemPath = $this->generateUrl('app_user_back_collaborator_bulk_items_delete');
        }
        $this->pageTitle = $this->text->trans('Utilisateurs', [], 'user');
    }

    /**
     * Implements this method for override table list.
     * @see \App\Controller\AbstractBackController::getSecuredTableFields() to know how to implements table fields list.
     */
    protected function initTableFields()
    {
        $this->tableFields['fields'] = [
            'access.thumbnail' => [
                'callback'    => 'thumbnailUrl', // Callback appelé. Cette methode doit être défini dans la classe de l'entité
                'isThumbnail' => true,
            ],
            'fullname'         => [
                'title'       => $this->text->trans('Nom du collaborator', [], 'form'),
                'hasDate'     => true, // Permet de savoir si on affiche un champ date juste en dessous
                'detail_link' => 'app_user_back_collaborator_detail', // Permet d'afficher le lien détail
            ],
            'email'            => [
                'title' => $this->text->trans('Adresse E-mail', [], 'form'),
            ],
            'addressPhone'     => [
                'title' => $this->text->trans('address.phoneNumber', [], 'form'),
            ],
            'address_street'   => [
                'title'    => $this->text->trans('address.address', [], 'form'),
                'callback' => 'addressStreet', // Callback appelé. Cette methode doit être défini dans la classe de l'entité
            ],
        ];
        //Action par défaut
        $this->tableFields['defaultAction'] = [
            'path' => 'app_user_back_collaborator_detail',
            'role' => 'CAN_VIEW_COLLABORATOR',
        ];
        //Actions sur la table
        $this->tableFields['actions'] = [
            'edit'   => [
                'text' => $this->text->trans('edit', [], 'external'),
                'path' => 'app_user_back_collaborator_edit',
                'role' => 'CAN_EDIT_COLLABORATOR',
            ],
            'delete' => [
                'text' => $this->text->trans('delete', [], 'external'),
                'path' => 'app_user_back_collaborator_delete',
                'icon' => 'ion-trash-a',
                'role' => 'CAN_DELETE_COLLABORATOR',
            ],
        ];
        //Actions d'accès rapides
        $this->tableFields['othersActions'] = [
            'add'    => [
                'path' => 'app_user_back_collaborators',
                'icon' => 'fa fa-plus',
                'role' => 'CAN_EDIT_COLLABORATOR',
            ],
            'pdf'    => [
                'path' => 'app_user_back_collaborators',
                'icon' => 'fa fa-file-pdf',
                'role' => 'CAN_DELETE_COLLABORATOR',
            ],
            'excel'  => [
                'path' => 'app_user_back_collaborators',
                'icon' => 'fa fa-file-excel',
                'role' => 'CAN_DELETE_COLLABORATOR',
            ],
            'upload' => [
                'path' => 'app_user_back_collaborators',
                'icon' => 'fa fa-cloud-download-alt',
                'role' => 'CAN_DELETE_COLLABORATOR',
            ],
            'stats'  => [
                'path' => 'app_user_back_collaborators',
                'icon' => 'fa fa-chart-bar',
                'role' => 'CAN_DELETE_COLLABORATOR',
            ],
        ];
        //Filtres sur la table
        $this->tableFields['searchPath']         = 'app_user_back_collaborators';
        $this->tableFields['advancedFilterPath'] = 'app_user_back_collaborators';
        $this->tableFields['resetFilterPath']    = 'app_user_back_collaborators';
    }

    /**
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\ORM\ORMException
     * @Security("is_granted('CAN_ADD_COLLABORATOR')")
     */
    public function add(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->hasSaveBtn        = true;
        $this->hasSaveAndStayBtn = true;
        $this->resetPath         = $this->generateUrl('app_user_back_collaborators');
        $this->pageTitle         = '';
        $this->pageTitle         = $this->text->trans('Création d\'un collaborator', [], 'user');
        $entity                  = new Collaborator();
        $entity->setAuthor($this->getActor());
        $entity->setCompany($this->company);
        $entity->setMatricule($this->getNextSequence(Collaborator::getSequence()));
        $entity->getActor()->setMatricule($this->getNextSequence(Actor::getSequence()));
        $form = $this->createForm(CollaboratorType::class, $entity);
        // handle form
        $form->handleRequest($this->request);
        // check if post sent and form is valid
        if ($form->isSubmitted()) {
            $accessForm    = $form->get('actor')->get('access');
            $plainPassword = $accessForm->get('plainPassword')->get('mainField')->getData();
            if (empty($plainPassword))
                $accessForm->get('plainPassword')->get('mainField')->addError(new FormError($this->text->trans('Veuillez entrer un mot de passe', [], 'actor')));
            else if (strlen($plainPassword) < 6) {
                $accessForm->get('plainPassword')->get('mainField')->addError(new FormError($this->text->trans('Le mot de passe doit avoir au moins 6 caractères', [], 'actor')));
            }
            // save
            if ($form->isValid()) {
                $actor = $entity->getActor();
                if ($actor->getThumbnail() && $actor->getThumbnail()->getFile() == null) {
                    if (!$actor->getThumbnail()->getId())
                        $actor->setThumbnail(null);
                    else if ((int)$actor->getThumbnail()->getRemove() == 1) {
                        $this->em->remove($actor->getThumbnail());
                        $actor->setThumbnail(null);
                    }
                }
                $access = $actor->getAccess();
                $access->addRole('ROLE_ACCESS_BO');
                // 3) Encode the password (you could also do this via Doctrine listener)
                $password = $passwordEncoder->encodePassword($access, $access->getPlainPassword());
                $access->setPassword($password);
                try {
                    $this->em->getConnection()->beginTransaction();
                    $this->em->persist($access);
                    $actor->setAccess($access);
                    $actor->getCompany()->setActor($actor);
                    $entity->setActor($actor);
                    $this->em->persist($entity);
                    //Set Current Work Context
                    /** @var CurrentWorkspace $workSpace */
                    $workSpace = new CurrentWorkspace();
                    $workSpace->setActor($actor);
                    $workSpace->setCompany($actor->getCompany());
                    $workSpace->setContextType(CurrentWorkspace::COLLABORATOR);
                    $this->em->persist($workSpace);
                    $this->em->flush();
                    $this->em->getConnection()->commit();
                    $this->notify($this->text->trans('Collaborator', [], 'user'), $this->text->trans('notify.successful_add', [], 'core'));
                    if ($form->get('saveAndStay')->isClicked()) {
                        return $this->redirectToRoute('app_user_back_collaborator_edit', ['id' => $entity->getId()]);
                    }
                    return $this->redirectToRoute('app_user_back_collaborators');
                } catch (Exception $e) {
                    $this->em->getConnection()->rollback();
                    return $this->catchException($e);
                }
            }
        }
        return $this->render('user/back/collaborator/add.html.twig', [
            'item' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param \App\Entity\User\Collaborator|null $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\ORM\ORMException
     * @Security("is_granted('CAN_EDIT_COLLABORATOR')")
     */
    public function edit(UserPasswordEncoderInterface $passwordEncoder, Collaborator $entity = null)
    {
        $this->isObject($entity, 'Collaborator');
        $this->hasSaveBtn        = true;
        $this->hasSaveAndStayBtn = true;
        $this->resetPath         = $this->generateUrl('app_user_back_collaborators');
        $this->pageTitle         = $this->text->trans('Mise à jour du utilisateur %name%', ['%name%' => $entity->getFullname()], 'user');
        $this->isAdministrator   = true;
        $form                    = $this->createForm(CollaboratorType::class, $entity);
        // handle form
        $form->handleRequest($this->request);
        // check if post sent and form is valid
        if ($form->isSubmitted()) {
            $actor = $entity->getActor();
            if ($actor->getThumbnail() && $actor->getThumbnail()->getFile() == null) {
                if (!$actor->getThumbnail()->getId())
                    $actor->setThumbnail(null);
                else if ((int)$actor->getThumbnail()->getRemove() == 1) {
                    $this->em->remove($actor->getThumbnail());
                    $actor->setThumbnail(null);
                }
            }
            $access = $actor->getAccess();
            // 3) Encode the password (you could also do this via Doctrine listener)
            if (!empty($access->getPlainPassword()) && strlen($access->getPlainPassword())) {
                $password = $passwordEncoder->encodePassword($access, $access->getPlainPassword());
                $access->setPassword($password);
            }
            // save
            if ($form->isValid()) {
                if ($actor->getThumbnail() && $actor->getThumbnail()->getFile() == null) {
                    if (!$actor->getThumbnail()->getId())
                        $actor->setThumbnail(null);
                    else if ((int)$actor->getThumbnail()->getRemove() == 1) {
                        $this->em->remove($actor->getThumbnail());
                        $actor->setThumbnail(null);
                    }
                }
                try {
                    $this->em->getConnection()->beginTransaction();
                    $actor->setAccess($access);
                    $entity->setActor($actor);
                    $this->em->persist($access);
                    $this->em->persist($entity);
                    $this->em->flush();
                    $this->em->getConnection()->commit();
                    $this->notify($this->text->trans('Collaborator', [], 'user'), $this->text->trans('notify.successful_update', [], 'core'));
                    if ($form->get('saveAndStay')->isClicked()) {
                        return $this->redirectToRoute('app_user_back_collaborator_edit', ['id' => $entity->getId()]);
                    }

                    return $this->redirectToRoute('app_user_back_collaborators');
                } catch (Exception $e) {
                    $this->em->getConnection()->rollback();
                    return $this->catchException($e);
                }
            }
        }

        return $this->render('user/back/collaborator/add.html.twig', [
            'item' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete entity
     *
     * @param \App\Entity\User\Collaborator|null $entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('CAN_DELETE_COLLABORATOR')")
     */
    public function delete(Collaborator $entity = null)
    {
        $this->isObject($entity, $this->text->trans('Collaborator', [], 'user'));
        $this->resetPath  = $this->generateUrl('app_user_back_collaborators');
        $this->pageTitle  = $this->text->trans('Suppression de l\'utilisateur %name%', ['%name%' => $entity->getFullname()], 'user');
        $this->hasSaveBtn = true;
        // check if post sent and form is valid
        if ($this->request->isMethod('post')) {
            // save
            $entity->setDeleted(true);
            try {
                $this->em->persist($entity);
                $this->em->flush();
                $this->notify($this->text->trans('Collaborator', [], 'user'), $this->text->trans('notify.successful_update', [], 'core'));

                return $this->redirectToRoute('app_user_back_collaborators');
            } catch (Exception $e) {
                return $this->catchException($e);
            }
        }

        return $this->render('user/back/collaborator/delete.html.twig', [
            'item' => $entity,
        ]);
    }
}
