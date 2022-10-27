<?php


namespace App\Controller;

use App\Contracts\TokenGeneratorInterface;
use App\Entity\User\Actor;
use App\Entity\User\CurrentWorkspace;
use App\EventListener\AppEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AbstractFoundationController The foundation controller class.
 *
 * All controllers classes must extends this class
 *
 * @link       http://github.com/kdms90
 *
 * @since      5.0.0
 * @package    App
 * @subpackage App\Controller
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
abstract class AbstractFoundationController extends AbstractController
{
    /**
     * @var EntityManager entity manager object.
     */
    protected $em;
    /**
     * @var TranslatorInterface translator component.
     */
    protected $text;
    /**
     * @var Session current session.
     */
    protected $session;
    /**
     * @var array availables locales defined in parameters file.
     */
    protected $locales;
    /**
     * @var Request $request current request.
     */
    protected $request;
    /**
     * @var bool $isAjaxRequest check if is an ajax request.
     */
    protected $isAjaxRequest;
    /**
     * @var  string $webFolder path to web folder
     */
    protected $webFolder;
    /**
     * @var int $nbItemsPerPage Define number of items to show in one page
     */
    protected $nbItemsPerPage = 10;
    /**
     * Event to be trigger
     *
     * @var EventDispatcherInterface $eventDispatcher
     */
    protected $eventDispatcher;

    /** @var int $actor_id current actor ID */
    protected $actor_id;
    /** @var string Détermine sous quelle forme d'intervenant l'utilisateur travaille dans la compagnie actuelle */
    protected $contextType = '';

    /** @var string $currency utilisee dans l'instace encours. La dévise de la compagnie connectée */
    protected $currency = 'xaf';

    /** @var string $locale la langue de la compagnie */
    protected $locale = 'fr';
    /**
     * All availables images sizes
     *
     * @var array
     */
    protected $imageSizes;

    /** @var \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface $params */
    protected $params;
    /**
     * @var array data to sent in view.
     */
    protected $viewData     = [
        'hasError'   => false, //Check if response has error.
        'entities'   => null, //Entities to use in loop if is not null.
        'entity'     => null, //Current entity to display.
        'activeMenu' => 'home', //Determines active menu.
        'showBox'    => false, //Check if we display confirm box alert.
        'locales'    => [], //Registers locales.
        'currency'   => 'xaf', //Default company currency.
        'locale'     => 'fr', //Default company locale.
    ];
    protected $pageTitle    = '';
    protected $pageSubTitle = '';
    /**
     * @var \App\Contracts\TokenGeneratorInterface
     */
    protected $tokenGenerator;
    /** @var string $host permet d'avoir l'hôte à partir duquel les mails sont envoyés, on utilisera plus l'hote par défaut */
    protected $host;

    /**
     * AbstractFoundationController constructor.
     *
     * @param \Symfony\Component\HttpFoundation\RequestStack $request
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface $params
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \App\Contracts\TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager, TranslatorInterface $translator, ParameterBagInterface $params, EventDispatcherInterface $eventDispatcher, TokenGeneratorInterface $tokenGenerator)
    {
        $this->params          = $params;
        $this->request         = $request->getCurrentRequest();
        $this->em              = $entityManager;
        $this->text            = $translator;
        $this->tokenGenerator  = $tokenGenerator;
        $this->eventDispatcher = $eventDispatcher;
        $this->loadDependencies();
    }

    /**
     * We use this method like a constructor.
     */
    protected function loadDependencies()
    {
        $this->locales                   = $this->params->get('locales');
        $this->isAjaxRequest             = $this->request->isXmlHttpRequest();
        $this->locale                    = $this->request->getLocale();
        $this->viewData['locales']       = $this->locales;
        $this->viewData['locale']        = $this->locale;
        $this->viewData['currency']      = $this->currency;
        $this->viewData['currentLocale'] = $this->locale;
        $this->webFolder                 = __DIR__ . '/../../public/';
        $this->viewData['company']       = null;
        $this->viewData['contextType']   = null;
        $this->imageSizes                = [];
        $this->host                      = $this->params->get('host');
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        //Start session if is not started
        $this->session = $this->request->getSession();
        if (!$this->session->isStarted())
            $this->session->start();
    }

    /**
     * @return object|\App\Entity\User\Access|null
     */
    public function getCurrentUser()
    {
        return $this->getUser();
    }

    /**
     * Permet d'appeler une methode d'une classe en tenant compte de la profondeur.
     * Actuelement utiliser dans les FormType pour le champ de type select2Entity
     * @param $entity
     * @param $methodName
     * @return mixed
     */
    public function callMethod($entity, $methodName)
    {
        $methods = explode(".", $methodName);
        foreach ($methods as $method)
            if (method_exists($entity, 'get' . ucfirst($method))) $entity = $entity->{'get' . ucfirst($method)}();
            else if (method_exists($entity, $method)) $entity = $entity->$method();
            else if (method_exists($entity, ucfirst($method))) $entity = $entity->{ucfirst($method)}();
        return $entity;
    }

    /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     *
     * @param       $data
     * @param int $status
     * @param array $headers
     * @param array $context
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $headers['Access-Control-Allow-Origin'] = '*';
        return parent::json($this->utf8ize($data), $status, $headers, $context);
    }

    /** Use it for json_encode some corrupt UTF-8 chars
     * useful for = malformed utf-8 characters possibly incorrectly encoded by json_encode
     *
     * @param $mixed
     *
     * @return array|null|string|string[]
     */
    private function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } else if (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
        }
        return $mixed;
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
        return parent::render($view, array_merge($this->viewData, $parameters), $response);
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
        return parent::renderView($view, array_merge($this->viewData, $parameters));
    }

    /**
     * @param string $eventType type of event if fire.
     * @param \App\EventListener\AppEvent $event
     * @see \App\AppEvents class to see all registers events.
     *
     */
    protected function triggerEvent($eventType, AppEvent $event)
    {
        $this->eventDispatcher->dispatch($event, $eventType);
    }

    /**
     * Enable to force download file
     *
     * @param        $file
     * @param null $fileName
     * @param string $disposition
     *
     * @return BinaryFileResponse
     */
    protected function forceDownload($file, $fileName = null, $disposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT)
    {
        $response = new BinaryFileResponse($file);
        $response->setContentDisposition($disposition, $fileName === null ? $response->getFile()->getFilename() : $fileName);
        $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @return \App\Entity\User\CurrentWorkspace|mixed|null
     */
    protected function getWorkSpace()
    {
        if ($this->getActor())
            return $this->em->getRepository('App\Entity\User\CurrentWorkspace')->findOneBy(['actor' => $this->getActor()]);
        return null;
    }

    /**
     * @return \App\Entity\User\Actor|null
     */
    protected function getActor()
    {
        if ($this->getUser())
            return $this->getUser()->getActor();
        return null;
    }

    /**
     * @param \App\Entity\User\Actor $actor
     * @param $contextType
     * @return \App\Entity\User\CurrentWorkspace
     */
    protected function setWorkContext(Actor $actor, $contextType = CurrentWorkspace::GUEST)
    {
        //Set Current Work Context
        /** @var CurrentWorkspace $workSpace */
        $workSpace = $this->em->getRepository("App:User\CurrentWorkspace")->findOneBy(["actor" => $actor]);
        if (!$workSpace)
            $workSpace = new CurrentWorkspace();
        $workSpace->setActor($actor);
        $workSpace->setContextType($contextType);
        return $workSpace;
    }
}
