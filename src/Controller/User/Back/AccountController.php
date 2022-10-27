<?php

namespace App\Controller\User\Back;

use App\Contracts\FoundationInterface;
use App\Controller\AbstractBackController;
use App\Entity\User\Actor;
use App\Entity\User\CurrentWorkspace;
use App\Form\User\ActorType;
use App\Util\Tools;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountController
 * @package App\Controller\User\Back
 * @Security("is_granted('ROLE_ACCESS_BO')")
 */
class AccountController extends AbstractBackController
{
    /**
     * All controller must implement bulk method for perfom delete group on entities
     *
     * @return mixed
     *
     * @Security("is_granted('CAN_DELETE_ACTOR')")
     * @throws \App\Exception\ContextException
     * @throws \Doctrine\ORM\ORMException
     */
    public function bulkDelete()
    {
        $this->validContext();
        if ($this->request->isMethod('post')) {
            $ids = explode(',', $this->request->get('items'));
            foreach ($ids as $id) {
                $entity = $this->em->getRepository('App\Entity\User\Actor')->find((int)$id);
                if ($entity instanceof FoundationInterface) {
                    $entity->setDeleted(true);
                }
            }
            try {
                $this->em->flush();
                $this->notify($this->text->trans('operation_status', [], 'external'), $this->text->trans('well_deletion', [], 'external'));

                return $this->redirectToRoute('app_user_back_accounts');
            } catch (OptimisticLockException $e) {
            }
        }
        $this->notify($this->text->trans('operation_status', [], 'external'), 'Une erreur s\'est accounte lors de la suppression', 'update-danger');
        return $this->redirectToRoute('app_user_back_accounts');
    }

    /**
     * Affiche la liste de tous les états account
     *
     * @param \App\Util\Tools $tools
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Security("is_granted('CAN_VIEW_ACTOR')")
     */
    public function index(Tools $tools, $page = 1)
    {
        $this->initHeaderContents();
        /** @var Actor[] $entities */
        $manager       = $this->em->getRepository('App\Entity\User\Actor');
        $fromDate      = $this->request->query->get('fromDate');
        $toDate        = $this->request->query->get('toDate');
        $query         = (string)$this->request->query->get('query', '');
        $fDate         = $tools->isValidDateTimeString('d/m/Y', $fromDate) ? \DateTime::createFromFormat('d/m/Y', $fromDate) : false;
        $tate          = $tools->isValidDateTimeString('d/m/Y', $toDate) ? \DateTime::createFromFormat('d/m/Y', $toDate) : false;
        $entities      = $manager->retrieve($this->nbItemsPerPage, $page, $this->locale, $query, $fDate, $tate);
        $this->itemsNb = count($entities);
        return $this->render('user/back/account/index.html.twig', [
            'entities'       => $entities,
            'nbPages'        => $this->nbItemsPerPage,
            'nb_page'        => ceil($this->itemsNb / $this->nbItemsPerPage) ?: 1,
            'page'           => $page,
            'paginationPath' => 'app_user_back_accounts',
        ]);
    }

    /**
     * Implements this method for init index actions.
     * @param \App\Contracts\FoundationInterface|null $entity
     */
    protected function initHeaderContents(FoundationInterface $entity = null)
    {
        if ($this->isGranted('CAN_ADD_ACTOR') or 1) {
            $this->addPath  = $this->generateUrl('app_user_back_account_add');
            $this->addTitle = $this->text->trans('Ajouter un compte', [], 'user');
        }
        if ($this->isGranted('CAN_DELETE_ACTOR') or 1) {
            $this->bulkDeleteItemPath = $this->generateUrl('app_user_back_account_bulk_items_delete');
        }
        $this->pageTitle = $this->text->trans('Comptes', [], 'user');
    }

    /**
     * Implements this method for override table list.
     * @see \App\Controller\AbstractBackController::getSecuredTableFields() to know how to implements table fields list.
     */
    protected function initTableFields()
    {
        $this->tableFields['fields'] = [
            'access.thumbnail'    => [
                'callback'    => 'thumbnailUrl', // Callback appelé. Cette methode doit être défini dans la classe de l'entité
                'isThumbnail' => true,
            ],
            'fullname'            => [
                'title'       => $this->text->trans('user.fullname', [], 'form'),
                'hasDate'     => true, // Permet de savoir si on affiche un champ date juste en dessous
                'detail_link' => 'app_user_back_account_detail', // Permet d'afficher le lien détail
            ],
            'access_id'           => [
                'title'    => $this->text->trans('Matricule', [], 'form'),
                'callback' => 'accessId', // Callback appelé. Cette methode doit être défini dans la classe de l'entité
            ],
            'access_email'        => [
                'title'    => $this->text->trans('Adresse E-mail', [], 'form'),
                'callback' => 'accessEmail', // Callback appelé. Cette methode doit être défini dans la classe de l'entité
            ],
            'address_phoneNumber' => [
                'title'    => $this->text->trans('address.phoneNumber', [], 'form'),
                'callback' => 'addressPhone', // Callback appelé. Cette methode doit être défini dans la classe de l'entité
            ],
            'address_street'      => [
                'title'    => $this->text->trans('address.address', [], 'form'),
                'callback' => 'addressStreet', // Callback appelé. Cette methode doit être défini dans la classe de l'entité
            ],
        ];
        //Action par défaut
        $this->tableFields['defaultAction'] = [
            'path' => 'app_user_back_account_detail',
            'role' => 'CAN_VIEW_ACTOR',
        ];
        //Actions sur la table
        $this->tableFields['actions'] = [
            'edit'   => [
                'text' => $this->text->trans('edit', [], 'external'),
                'path' => 'app_user_back_account_edit',
                'role' => 'CAN_EDIT_ACTOR',
            ],
            'delete' => [
                'text' => $this->text->trans('delete', [], 'external'),
                'path' => 'app_user_back_account_delete',
                'icon' => 'ion-trash-a',
                'role' => 'CAN_DELETE_ACTOR',
            ],
        ];
        //Actions d'accès rapides
        $this->tableFields['othersActions'] = [
            'add'    => [
                'path' => 'app_user_back_accounts',
                'icon' => 'fa fa-plus',
                'role' => 'CAN_EDIT_ACTOR',
            ],
            'pdf'    => [
                'path' => 'app_user_back_accounts',
                'icon' => 'fa fa-file-pdf',
                'role' => 'CAN_DELETE_ACTOR',
            ],
            'excel'  => [
                'path' => 'app_user_back_accounts',
                'icon' => 'fa fa-file-excel',
                'role' => 'CAN_DELETE_ACTOR',
            ],
            'upload' => [
                'path' => 'app_user_back_accounts',
                'icon' => 'fa fa-cloud-download-alt',
                'role' => 'CAN_DELETE_ACTOR',
            ],
            'stats'  => [
                'path' => 'app_user_back_accounts',
                'icon' => 'fa fa-chart-bar',
                'role' => 'CAN_DELETE_ACTOR',
            ],
        ];
        //Filtres sur la table
        $this->tableFields['searchPath']         = 'app_user_back_accounts';
        $this->tableFields['advancedFilterPath'] = 'app_user_back_accounts';
        $this->tableFields['resetFilterPath']    = 'app_user_back_accounts';
    }

    /**
     * Ajout d'un état account
     *
     * @Security("is_granted('CAN_ADD_ACTOR')")
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->hasSaveBtn        = true;
        $this->hasSaveAndStayBtn = true;
        $this->resetPath         = $this->generateUrl('app_user_back_accounts');
        $this->pageTitle         = '';
        $this->pageTitle         = $this->text->trans('Création d\'un nouveau compte', [], 'user');
        $entity                  = new Actor();
        $entity->setAuthor($this->getActor());
        $entity->setMatricule($this->getNextSequence(Actor::getSequence()));
        $this->isAdministrator = true;
        $form                  = $this->createForm(ActorType::class, $entity);
        // handle form
        $form->handleRequest($this->request);
        // check if post sent and form is valid
        if ($form->isSubmitted()) {
            $plainPassword = $form->get('access')->get('plainPassword')->get('mainField')->getData();
            if (empty($plainPassword))
                $form->get('access')->get('plainPassword')->get('mainField')->addError(new FormError($this->text->trans('Veuillez entrer un mot de passe', [], 'actor')));
            else if (strlen($plainPassword) < 6) {
                $form->get('access')->get('plainPassword')->get('mainField')->addError(new FormError($this->text->trans('Le mot de passe doit avoir au moins 6 caractères', [], 'actor')));
            }
            // save
            if ($form->isValid()) {
                if ($entity->getThumbnail() && $entity->getThumbnail()->getFile() == null) {
                    if (!$entity->getThumbnail()->getId())
                        $entity->setThumbnail(null);
                    else if ((int)$entity->getThumbnail()->getRemove() == 1) {
                        $this->em->remove($entity->getThumbnail());
                        $entity->setThumbnail(null);
                    }
                }
                $access = $entity->getAccess();
                $access->addRole('ROLE_ACCESS_BO');
                // 3) Encode the password (you could also do this via Doctrine listener)
                $password = $passwordEncoder->encodePassword($access, $access->getPlainPassword());
                $access->setPassword($password);
                $this->em->persist($access);
                $entity->setAccess($access);
                $this->em->persist($entity);
                //Set Current Work Context
                /** @var CurrentWorkspace $workSpace */
                $workSpace = new CurrentWorkspace();
                $workSpace->setUser($entity);
                $workSpace->setCompany($entity->getCompany());
                $workSpace->setContextType(CurrentWorkspace::COLLABORATOR);
                try {
                    $this->em->persist($workSpace);
                    $this->em->flush();
                    $this->notify($this->text->trans('Compte', [], 'user'), $this->text->trans('notify.successful_update', [], 'core'));
                    if ($form->get('saveAndStay')->isClicked()) {
                        return $this->redirectToRoute('app_user_back_account_edit', ['id' => $entity->getId()]);
                    }

                    return $this->redirectToRoute('app_user_back_accounts');
                } catch (Exception $e) {
                    return $this->catchException($e);
                }
            }
        }
        return $this->render('user/back/account/add.html.twig', [
            'item' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Mise à jour
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param \App\Entity\User\Actor|null $entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Security("is_granted('CAN_EDIT_ACTOR')")
     */
    public function edit(UserPasswordEncoderInterface $passwordEncoder, Actor $entity = null)
    {
        $this->isObject($entity, 'Actor');
        $this->hasSaveBtn        = true;
        $this->hasSaveAndStayBtn = true;
        $this->resetPath         = $this->generateUrl('app_user_back_accounts');
        $this->pageTitle         = $this->text->trans('Mise à jour du compte %name%', ['%name%' => $entity->getFullname()], 'user');
        $this->isAdministrator   = true;
        $form                    = $this->createForm(ActorType::class, $entity);
        // handle form
        $form->handleRequest($this->request);
        // check if post sent and form is valid
        if ($form->isSubmitted()) {
            $access = $entity->getAccess();
            $access->addRole('ROLE_ACCESS_BO');
            // 3) Encode the password (you could also do this via Doctrine listener)
            if (!empty($access->getPlainPassword()) && strlen($access->getPlainPassword())) {
                $password = $passwordEncoder->encodePassword($access, $access->getPlainPassword());
                $access->setPassword($password);
            }
            // save
            if ($form->isValid()) {
                if ($entity->getThumbnail() && $entity->getThumbnail()->getFile() == null) {
                    if (!$entity->getThumbnail()->getId())
                        $entity->setThumbnail(null);
                    else if ((int)$entity->getThumbnail()->getRemove() == 1) {
                        $this->em->remove($entity->getThumbnail());
                        $entity->setThumbnail(null);
                    }
                }
                try {
                    $this->em->persist($access);
                    $this->em->persist($entity);
                    $this->em->flush();
                    $this->notify($this->text->trans('Compte', [], 'user'), $this->text->trans('notify.successful_update', [], 'core'));
                    if ($form->get('saveAndStay')->isClicked()) {
                        return $this->redirectToRoute('app_user_back_account_edit', ['id' => $entity->getId()]);
                    }

                    return $this->redirectToRoute('app_user_back_accounts');
                } catch (Exception $e) {
                    return $this->catchException($e);
                }
            }
        }

        return $this->render('user/back/account/add.html.twig', [
            'item' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete entity
     *
     * @param \App\Entity\User\Actor|null $entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('CAN_DELETE_ACTOR')")
     */
    public function delete(Actor $entity = null)
    {
        $this->isObject($entity, $this->text->trans('Compte', [], 'user'));
        $this->resetPath  = $this->generateUrl('app_user_back_accounts');
        $this->pageTitle  = $this->text->trans('Suppression du comptes %name%', ['%name%' => $entity->getFullname()], 'user');
        $this->hasSaveBtn = true;
        // check if post sent and form is valid
        if ($this->request->isMethod('post')) {
            // save
            $entity->setDeleted(true);
            try {
                $this->em->persist($entity);
                $this->em->flush();
                $this->notify($this->text->trans('Compte', [], 'user'), $this->text->trans('notify.successful_update', [], 'core'));

                return $this->redirectToRoute('app_user_back_accounts');
            } catch (Exception $e) {
                return $this->catchException($e);
            }
        }

        return $this->render('user/back/account/delete.html.twig', [
            'item' => $entity,
        ]);
    }
}
