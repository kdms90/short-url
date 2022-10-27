<?php

namespace App\Controller\User\Back;

use App\Contracts\FoundationInterface;
use App\Controller\AbstractBackController;
use App\Entity\User\ActorRole;
use App\Entity\User\ActorRoleAuthorization;
use App\Form\User\ActorRoleType;
use App\Util\Tools;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * 2018 Delivery Lab
 *
 * The back main controller class.
 *
 * All back controllers classes must extends this class
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    DB\UserBundle
 * @subpackage App\Controller\User
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @Security("is_granted('ROLE_ACCESS_BO')")
 */
class RoleController extends AbstractBackController
{
    /**
     * All controller must implement bulk method for perfom delete group on entities
     *
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Security("is_granted('CAN_DELETE_ACTOR_ROLE')")
     */
    public function bulkDelete()
    {
        if ($this->request->isMethod('post')) {
            $ids = explode(',', $this->request->get('items'));
            try {
                foreach ($ids as $id) {
                    $entity = $this->em->getRepository('App\Entity\User\ActorRole')->find((int)$id);
                    if ($entity instanceof FoundationInterface) {
                        $entity->setDeleted(true);
                        $this->em->remove($entity);
                    }
                }
                $this->em->flush();
                $this->notify($this->text->trans('access_rights', [], 'external'), $this->text->trans('well_deletion', [], 'external'));

                return $this->redirectToRoute('app_user_back_roles');
            } catch (Exception $e) {
                return $this->catchException($e);
            }
        }
        $this->notify($this->text->trans('access_rights', [], 'external'), $this->text->trans('error_occur_on_deletion', [], 'external'), 'update-danger');
        return $this->redirectToRoute('app_user_back_roles');
    }

    /**
     * List all entities
     *
     * @param \App\Util\Tools $tools
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Security("is_granted('CAN_VIEW_ACTOR_ROLE')")
     */
    public function index(Tools $tools, $page = 1)
    {
        $this->initHeaderContents();
        /** @var ActorRole[] $entities */
        $manager       = $this->em->getRepository('App\Entity\User\ActorRole');
        $fromDate      = $this->request->query->get('fromDate');
        $toDate        = $this->request->query->get('toDate');
        $query         = (string)$this->request->query->get('query', '');
        $fDate         = $tools->isValidDateTimeString('d/m/Y', $fromDate) ? \DateTime::createFromFormat('d/m/Y', $fromDate) : false;
        $tate          = $tools->isValidDateTimeString('d/m/Y', $toDate) ? \DateTime::createFromFormat('d/m/Y', $toDate) : false;
        $entities      = $manager->retrieve($this->nbItemsPerPage, $page, $this->locale, $query, $fDate, $tate);
        $this->itemsNb = count($entities);
        return $this->render('user/back/role/index.html.twig',
            [
                'entities'       => $entities,
                'nbPages'        => $this->nbItemsPerPage,
                'nb_page'        => ceil($this->itemsNb / $this->nbItemsPerPage) ?: 1,
                'page'           => $page,
                'paginationPath' => 'app_user_back_roles',
            ]
        );
    }

    /**
     * Implements this method for init index actions.
     * @param \App\Contracts\FoundationInterface|null $entity
     */
    protected function initHeaderContents(FoundationInterface $entity = null)
    {
        if ($this->isGranted('CAN_ADD_ACTOR_ROLE')) {
            $this->addPath  = $this->generateUrl('app_user_back_role_add');
            $this->addTitle = $this->text->trans('add_role', [], 'external');
        }
        if ($this->isGranted('CAN_DELETE_ACTOR_ROLE')) {
            $this->bulkDeleteItemPath = $this->generateUrl('app_user_back_role_bulk_items_delete');
        }
        $this->pageTitle = $this->text->trans('Rôles daccès aux acteurs', [], 'user');
    }

    /**
     * Implements this method for override table list.
     * @see \App\Controller\AbstractBackController::getSecuredTableFields() to know how to implements table fields list.
     */
    protected function initTableFields()
    {
        $this->tableFields['fields'] = [
            'name'        => [
                'title'       => $this->text->trans('role_name', [], 'external'),
                'lang'        => false, // Permet de savoir si c'est un champ multilingue
                'hasDate'     => true, // Permet de savoir si on affiche un champ date juste en dessous
                'detail_link' => 'app_user_back_role_detail', // Permet d'afficher le lien détail
            ],
            'description' => [
                'title' => $this->text->trans('Description', [], 'form'),
            ],
        ];
        //Action par défaut
        $this->tableFields['defaultAction'] = [
            'path' => 'app_user_back_role_detail',
            'role' => 'CAN_VIEW_ACTOR_ROLE',
        ];
        //Actions sur la table
        $this->tableFields['actions'] = [
            'edit'   => [
                'text' => $this->text->trans('edit', [], 'external'),
                'path' => 'app_user_back_role_edit',
                'role' => 'CAN_EDIT_ACTOR_ROLE',
            ],
            'delete' => [
                'text' => $this->text->trans('delete', [], 'external'),
                'path' => 'app_user_back_role_delete',
                'icon' => 'ion-trash-a',
                'role' => 'CAN_DELETE_ACTOR_ROLE',
            ],
        ];
        //Actions d'accès rapides
        $this->tableFields['othersActions'] = [
            'add'    => [
                'path' => 'app_user_back_role_add',
                'icon' => 'fa fa-plus',
                'role' => 'CAN_ADD_ACTOR_ROLE',
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
        $this->tableFields['searchPath']         = 'app_user_back_roles';
        $this->tableFields['advancedFilterPath'] = 'app_user_back_roles';
        $this->tableFields['resetFilterPath']    = 'app_user_back_roles';
    }

    /**
     * Add new user role
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('CAN_ADD_ACTOR_ROLE')")
     */
    public function add()
    {
        $this->hasSaveBtn        = true;
        $this->hasSaveAndStayBtn = true;
        $this->resetPath         = $this->generateUrl('app_user_back_roles');
        $this->resetPathTitle    = $this->text->trans('all_roles', [], 'external');
        $this->pageTitle         = $this->text->trans('add_new_role', [], 'external');
        $entity                  = new ActorRole();
        $entity->setAuthor($this->getActor());
        $entity->setCompany($this->company);
        $rules           = $this->availablesRules();
        $additionalRules = $this->additionalAvailablesRules();
        $form            = $this->createForm(ActorRoleType::class, $entity, ['oldRoles' => $rules, 'additionalRules' => $additionalRules]);
        // handle form
        $form->handleRequest($this->request);
        // check if post sent and form is valid
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->em->persist($entity);
                    foreach ($rules as $keyGroup => $value) {
                        foreach ($form->get($keyGroup)->getData() as $rule) {
                            $authorization = new ActorRoleAuthorization($entity, $rule, $keyGroup);
                            $this->em->persist($authorization);
                        }
                    }
                    foreach ($additionalRules as $keyGroup => $value) {
                        foreach ($form->get($keyGroup)->getData() as $rule) {
                            $authorization = new ActorRoleAuthorization($entity, $rule, $keyGroup);
                            $this->em->persist($authorization);
                        }
                    }
                    $this->em->flush();
                    $this->notify($this->text->trans('access_rights', [], 'external'), $this->text->trans('notify.successful_update', [], 'core'));
                    if ($form->get('saveAndStay')->isClicked()) {
                        return $this->redirectToRoute('app_user_back_role_edit', ['id' => $entity->getId()]);
                    }
                    return $this->redirectToRoute('app_user_back_roles');
                } catch (Exception $e) {
                    return $this->catchException($e);
                }
            }
        }
        return $this->render('user/back/role/add.html.twig', [
            'item'            => $entity,
            'rules'           => $rules,
            'additionalRules' => $additionalRules,
            'form'            => $form->createView(),
            'roleMembers'     => $entity->getMembers(),
        ]);
    }

    /**
     * Liste des roles par défaut sur les users
     *
     * @return array
     */
    protected function availablesRules()
    {
        $accoutingRules                                  = [];
        $accoutingRulesFull                              = [];
        $rulesDefinitions                                = [];
        $rulesDefinitions['user_collaborator']           = $this->getParameter('user_collaborator');
        $rulesDefinitions['user_consultant']             = $this->getParameter('user_consultant');
        $rulesDefinitions['user_supplier']               = $this->getParameter('user_supplier');
        $rulesDefinitions['user_consultant_application'] = $this->getParameter('user_consultant_application');
        $rulesDefinitions['user_buying_agency']          = $this->getParameter('user_buying_agency');
        $rulesDefinitions['user_buying_agency_type']     = $this->getParameter('user_buying_agency_type');
        $rulesDefinitions['user_actor']                  = $this->getParameter('user_actor');
        if ($this->company->isManagePlateform()) {
            $rulesDefinitions['user_agency']     = $this->getParameter('user_agency');
            $rulesDefinitions['user_speciality'] = $this->getParameter('user_speciality');
            $rulesDefinitions['user_degree']     = $this->getParameter('user_degree');
            $rulesDefinitions['user_domain']     = $this->getParameter('user_domain');
            $rulesDefinitions['user_skill']      = $this->getParameter('user_skill');
        }
        $rulesDefinitions['user_invitation'] = $this->getParameter('user_invitation');
        $rulesDefinitions['user_role']       = $this->getParameter('user_role');
        foreach ($rulesDefinitions as $key => $definition) {
            foreach ($definition as $rule) {
                $tmp = explode('_', $rule);
                if (!empty($tmp[1])) {
                    if (strtolower($tmp[1]) == 'add')
                        $accoutingRules['Droit de création'] = $rule;
                    if (strtolower($tmp[1]) == 'edit')
                        $accoutingRules['Droit de modification'] = $rule;
                    if (strtolower($tmp[1]) == 'delete')
                        $accoutingRules['Droit de suppression'] = $rule;
                    if (strtolower($tmp[1]) == 'view')
                        $accoutingRules['Droit de lecture'] = $rule;
                }
            }
            $accoutingRulesFull[$key] = array_unique($accoutingRules);
        }

        return $accoutingRulesFull;
    }

    /**
     * Liste des roles par défaut sur les users
     *
     * @return array
     */
    protected function additionalAvailablesRules()
    {
        $rulesDefinitions                 = [];
        $rulesDefinitions['company_role'] = $this->getParameter('company_role');
//        $rulesDefinitions['plateform_role']     = $this->getParameter('plateform_role');
        $rulesDefinitions['talent_acquisition'] = $this->getParameter('talent_acquisition');

        foreach ($rulesDefinitions as $key => $definition) {
            $roleDefinitionWithLabel = [];
            foreach ($definition as $rule) {
                $roleDefinitionWithLabel['rules.' . strtolower($rule)] = $rule;
            }
            $rulesDefinitions[$key] = $roleDefinitionWithLabel;
        }
        return $rulesDefinitions;
    }

    /**
     * Edit entity
     *
     * @param \App\Entity\User\ActorRole|null $entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('CAN_EDIT_ACTOR_ROLE')")
     */
    public function edit(ActorRole $entity = null)
    {
        $this->isObject($entity, 'Rôle user');
        $this->resetPath         = $this->generateUrl('app_user_back_roles');
        $this->resetPathTitle    = $this->text->trans('all_roles', [], 'external');
        $this->pageTitle         = $this->text->trans('update_access_rights', ['%name%' => $entity->getI18n($this->locale)->getName()], 'external');
        $this->hasSaveBtn        = true;
        $this->hasSaveAndStayBtn = true;
        $rules                   = $this->availablesRules();
        $additionalRules         = $this->additionalAvailablesRules();
        $oldAuthorizations       = $this->em->getRepository('App\Entity\User\ActorRoleAuthorization')->findRoleAndGroup($entity->getId());
        $form                    = $this->createForm(ActorRoleType::class, $entity, [
                'oldRoles'          => $rules,
                'additionalRules'   => $additionalRules,
                'oldAuthorizations' => $oldAuthorizations,
            ]
        );
        // handle form
        $form->handleRequest($this->request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->em->persist($entity);
                    $authorizationActions    = $entity->getAuthorizationsActions();
                    $newAuthorizationActions = [];
                    $postData                = $this->request->get('app_user_userrole');
                    $roleRepo                = $this->em->getRepository('App\Entity\User\ActorRoleAuthorization');
                    foreach ($rules as $keyGroup => $value) {
                        if (!empty($postData[$keyGroup]))
                            foreach ($postData[$keyGroup] as $rule) {
                                $newAuthorizationActions[] = $rule;
                                $authorization             = $roleRepo->findOneBy(['key' => $rule, 'role' => $entity]);
                                if (!$authorization) {
                                    $authorization = new ActorRoleAuthorization($entity, $rule, $keyGroup);
                                    $this->em->persist($authorization);
                                }
                            }
                    }
                    foreach ($additionalRules as $keyGroup => $value) {
                        if (!empty($postData[$keyGroup]))
                            foreach ($postData[$keyGroup] as $rule) {
                                $newAuthorizationActions[] = $rule;
                                $authorization             = $roleRepo->findOneBy(['key' => $rule, 'role' => $entity]);
                                if (!$authorization) {
                                    $authorization = new ActorRoleAuthorization($entity, $rule, $keyGroup);
                                    $this->em->persist($authorization);
                                }
                            }
                    }
                    //We remove unset rule
                    foreach ($authorizationActions as $action) {
                        if (!in_array($action, $newAuthorizationActions)) {
                            $old = $roleRepo->findOneBy(['key' => $action, 'role' => $entity]);
                            if ($old) {
                                $this->em->remove($old);
                            }
                        }
                    }
                    $this->em->flush();
                    $this->notify($this->text->trans('access_rights', [], 'external'), $this->text->trans('notify.successful_update', [], 'core'));
                    if ($form->get('saveAndStay')->isClicked()) {
                        return $this->redirectToRoute('app_user_back_role_edit', ['id' => $entity->getId()]);
                    }
                    return $this->redirectToRoute('app_user_back_roles');
                } catch (Exception $e) {
                    return $this->catchException($e);
                }
            }
        }
        return $this->render('user/back/role/add.html.twig', [
            'rules'           => $rules,
            'additionalRules' => $additionalRules,
            'item'            => $entity,
            'form'            => $form->createView(),
            'roleMembers'     => $entity->getMembers(),
        ]);
    }

    /**
     * Delete entity
     *
     * @param \App\Entity\User\ActorRole|null $entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('CAN_DELETE_ACTOR_ROLE')")
     */
    public function delete(ActorRole $entity = null)
    {
        $this->isObject($entity, 'Rôle user');
        $this->resetPath  = $this->generateUrl('app_user_back_roles');
        $this->pageTitle  = $this->text->trans('deleting_access_rights', ['%name%' => $entity->getI18n($this->locale)->getName()], 'external');
        $this->hasSaveBtn = true;
        if ($this->request->isMethod('post')) {
            // save
            $entity->setDeleted(true);
            try {
                $this->em->persist($entity);
                $this->em->flush();
                $this->notify($this->text->trans('access_rights', [], 'external'), $this->text->trans('notify.successful_update', [], 'core'));
                return $this->redirectToRoute('app_user_back_roles');
            } catch (Exception $e) {
                return $this->catchException($e);
            }
        }

        return $this->render('user/back/role/delete.html.twig', [
            'item' => $entity,
        ]);

    }
}
