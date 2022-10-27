<?php

namespace App\Controller;

use App\Contracts\FoundationInterface;
use App\Entity\AbstractFoundation;
use App\Entity\User\CurrentWorkspace;
use DateTime;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractBackController
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App\Controller
 * @subpackage App\Controller\Front
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
abstract class AbstractBackController extends AbstractFoundationController
{
    protected $addPath            = false;
    protected $addPathClass       = '';
    protected $currentModule      = false;
    protected $addTitle           = '';
    protected $resetPath          = '';
    protected $resetPathTitle     = '';
    protected $bulkDeleteItemPath = false;
    protected $othersBulkItemPath = false;
    protected $hasValidatedBtn    = false;
    protected $validatedPath      = false;
    protected $entityId           = 0;
    protected $validatedText      = false;
    protected $hasSaveBtn         = false;
    protected $hasSaveAndStayBtn  = false;
    protected $saveAndStayTitle   = false;
    protected $itemsNb            = false;
    protected $otherLinks         = [];
    protected $rightLinks         = [];
    protected $isAdministrator    = false;
    /** @var \App\Entity\User\Actor $current_company_manager */
    protected $current_company_manager = null;
    /** @var array Contient les champs d'une table */
    protected $tableFields = [];
    protected $formClass   = ''; //Class addittionnel sur les forumailaire

    /**
     * Permet de savoir quel est le context de travail de l'utilisateur courant.
     * Il peut travailler comme Consultant, ou comme collaborateur dans une agence, ou comme un client.
     */
    public function loadCurrentContext()
    {
        $this->company = $this->getCompany();
        if ($this->company) {
            $this->actor_id = $this->getActor()->getId();
            if ($this->company) {
                $this->viewData['current_company']    = $this->company;
                $this->viewData['current_company_id'] = $this->company->getId();
                $this->current_company_manager        = $this->company->getActor();
                if ($this->company->isManagePlateform())
                    $this->companyType = CurrentWorkspace::ADMINISTRATOR;
                else {
                    $this->companyType = CurrentWorkspace::CONSULTANT;
                }
                $this->getUser()->current_company_id     = $this->company->getId();
                $this->getUser()->current_company_type   = $this->companyType;
                $this->getUser()->context_type           = $this->contextType;
                $this->getUser()->current_company_own_id = $this->company->getActor()->getId();
            }
            if ($this->isGranted('CAN_MANAGE_PLATEFORM')) {
                $this->isAdministrator = true;
            }
        }
    }

    /**
     * @return \App\Entity\User\Company|null
     */
    private function getCompany()
    {
        $workspace = $this->getWorkSpace();
        if ($workspace) {
            $this->contextType = $workspace->getContextType();
            return $workspace->getCompany();
        }
        return null;
    }

    /**
     * We use this method like a constructor.
     */
    protected function loadDependencies()
    {
        parent::loadDependencies();
        $this->initTableFields();
    }

    /**
     * Implements this method for override table list.
     * @see \App\Controller\AbstractBackController::getSecuredTableFields() to know how to implements table fields list.
     */
    protected function initTableFields()
    {
    }

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
        $this->initDefaultViewData();
        return parent::render($view, array_merge($this->viewData, $parameters), $response);
    }

    /**
     * Permet de passer les valeurs par défaut à la vue
     */
    private function initDefaultViewData()
    {
        $this->viewData['currentModule']      = $this->currentModule;
        $this->viewData['addPath']            = $this->addPath;
        $this->viewData['addPathClass']       = $this->addPathClass;
        $this->viewData['addTitle']           = $this->addTitle;
        $this->viewData['hasValidatedBtn']    = $this->hasValidatedBtn;
        $this->viewData['validatedText']      = $this->validatedText;
        $this->viewData['entityId']           = $this->entityId;
        $this->viewData['validatedPath']      = $this->validatedPath;
        $this->viewData['hasSaveAndStayBtn']  = $this->hasSaveAndStayBtn;
        $this->viewData['saveAndStayTitle']   = $this->saveAndStayTitle;
        $this->viewData['pageTitle']          = $this->pageTitle;
        $this->viewData['pageSubTitle']       = $this->pageSubTitle;
        $this->viewData['resetPath']          = $this->resetPath;
        $this->viewData['resetPathTitle']     = $this->resetPathTitle;
        $this->viewData['itemsNb']            = $this->itemsNb;
        $this->viewData['hasSaveBtn']         = $this->hasSaveBtn;
        $this->viewData['otherLinks']         = $this->otherLinks;
        $this->viewData['rightLinks']         = $this->rightLinks;
        $this->viewData['bulkDeleteItemPath'] = $this->bulkDeleteItemPath;
        $this->viewData['othersBulkItemPath'] = $this->othersBulkItemPath;
        $this->viewData['formClass']          = $this->formClass;
        $this->viewData['tableFields']        = $this->getSecuredTableFields();
    }

    /**
     * Retourne les éléments d'une liste qui seront affichés
     *
     * @return array
     */
    private function getSecuredTableFields()
    {
        $defaults                                 = [
            'fields'              => [
                'id' => [
                    'title' => $this->text->trans('ID', [], 'form'),
                    'show'  => false,
                    'pk'    => true,
                ],
            ],
            'defaultAction'       => [],
            'actions'             => [],
            'othersActions'       => [],
            'rightDetailsOptions' => [],
            'searchPath'          => null,
            'filterPath'          => null,
            'advancedFilterPath'  => null,
            'resetFilterPath'     => null,
            'routeParams'         => $this->request->attributes->get('_route_params', []),
        ];
        $defaultsFieldsValues                     = [
            'title'             => '',
            'pk'                => false,
            'lang'              => false,
            'width'             => false,
            'align'             => false,
            'type'              => false,
            'default_currency'  => $this->currency,
            'class_action'      => '',
            'hasDate'           => false,
            'switchValues'      => [],   // Est utiliser uniquement lorsque le type = 'switch'
            'isThumbnail'       => false,
            'show'              => true, // Permet de savoir si on affiche la colonne
            'enabledExcerpt'    => true, // Permet de savoir si on affiche un nombre limité de caractères.
            'key'               => 'id', // Elément contenant la valeur à utiliser pour le lien détail
            'callback'          => null,
            'detail_link'       => null, // Permet d'afficher le lien détail
            'role'              => null, // Role pour voir le champ
            'currency_callback' => null, // Permet d'appeller une fonction pour recuperer le currency en cas d'un champ de type monnaie
        ];
        $defaultAction                            = [
            'text'         => $this->text->trans('detail', [], 'external'),
            'icon'         => 'fa fa-search-plus',
            'key'          => 'id',// Elément contenant la valeur à utiliser pour le lien détail
            'path'         => null,// Route vers l'action par défaut
            'role'         => null,// Role d'accès à l'action
            'isBasic'      => true,//Permet d'activer la sidebar de détail
            'class_action' => '',
            'buttonClass'  => 'bg-secondary text-light',
        ];
        $defaultRightSideSetting                  = [
            'title' => $this->text->trans('detail', [], 'external'),
        ];
        $defaultsActionsFields                    = [
            'text'         => '',
            'path'         => null,
            'key'          => 'id',
            'icon'         => 'ion-compose',
            'callback'     => false,
            'role'         => null,
            'class_action' => '',
        ];
        $this->tableFields                        = array_merge($defaults, $this->tableFields);
        $this->tableFields['fields']              = array_merge($defaults['fields'], $this->tableFields['fields']);
        $this->tableFields['defaultAction']       = array_merge($defaultAction, $this->tableFields['defaultAction']);
        $this->tableFields['rightDetailsOptions'] = array_merge($defaultRightSideSetting, $this->tableFields['rightDetailsOptions']);

        foreach ($this->tableFields['fields'] as $field => $values) {
            $this->tableFields['fields'][$field] = array_merge($defaultsFieldsValues, $this->tableFields['fields'][$field]);
        }
        //On se rassure de l'intégrité des actions du menu déroulant
        foreach ($this->tableFields['actions'] as $fieldAction => $values) {
            $this->tableFields['actions'][$fieldAction] = array_merge($defaultsActionsFields, $this->tableFields['actions'][$fieldAction]);
        }
        //On se rassure de l'intégrité des actions du menu déroulant
        foreach ($this->tableFields['othersActions'] as $fieldAction => $values) {
            $this->tableFields['othersActions'][$fieldAction] = array_merge($defaultsActionsFields, $this->tableFields['othersActions'][$fieldAction]);
        }
        $this->viewData['tableFieldsColumnTotal'] = count($this->tableFields['fields']) + 1;
        return $this->tableFields;
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
        $this->initDefaultViewData();
        return parent::renderView($view, array_merge($this->viewData, $parameters));
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string $type
     * @param null $data
     * @param array $options
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        $defaults = ['locale' => $this->locale, 'actorId' => $this->actor_id, 'isAdministrator' => $this->isAdministrator];
        $form     = $this->container->get('form.factory')->create($type, $data, array_merge($options, $defaults));
        $form
            ->add('submit', SubmitType::class, [
                'label' => 'save',
                'attr'  => [
                    'class' => 'submit-main-form-btn d-none',
                ],
            ])
            ->add('saveAndStay', SubmitType::class, [
                'label' => 'save',
                'attr'  => [
                    'class' => 'submit-main-form-btn-stay d-none',
                ],
            ]);
        return $form;
    }

    /**
     * Implements this method for init index actions.
     * @param \App\Contracts\FoundationInterface|null $entity
     */
    abstract protected function initHeaderContents(FoundationInterface $entity = null);

    /**
     * Add notification after an action in application
     *
     * @param        $title
     * @param        $message
     * @param string $type
     */
    protected function notify($title, $message, $type = 'update-success')
    {
        $this->addFlash('notifyTitle', $title);
        $this->addFlash('notify', $message);
        $this->addFlash('type', $type);
    }

    /**
     * Initialisation d'une entité
     *
     * @param \App\Entity\AbstractFoundation $entity
     * @return \App\Entity\AbstractFoundation
     */
    protected function initEntity(AbstractFoundation $entity): AbstractFoundation
    {
        try {
            $entity->setDateAdd(new DateTime());
            $entity->setAuthor($this->getActor());
        } catch (Exception $e) {

        }
        return $entity;
    }
}
