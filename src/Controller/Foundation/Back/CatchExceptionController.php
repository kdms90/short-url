<?php

namespace App\Controller\Foundation\Back;

use App\Contracts\FoundationInterface;
use App\Controller\AbstractBackController;
use App\Entity\User\CurrentWorkspace;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class IndexController
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App\Controller
 * @subpackage App\Controller\Back
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @Security("is_granted('ROLE_USER')")
 */
class CatchExceptionController extends AbstractBackController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $this->pageTitle = 'Tableau de bord';
        //On pourra faire un log ici
        $path      = $this->session->get('app_exception_route');
        $exception = $this->session->get('app_exception_message');
        return $this->render('foundation/back/catch-exception/flush-execution.html.twig', [
            'exception' => $exception,
            'path'      => $path,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function wrongWorkContext()
    {
        $this->pageTitle = 'Context de travail inconnu';
        return $this->render('foundation/back/catch-exception/wrong-context.html.twig', [
            'form' => $this->contextForm()->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    protected function contextForm()
    {
        if ($this->getActor())
            $this->actor_id = $this->getActor()->getId();
        $choices           = [];
        $companyRepo       = $this->em->getRepository('App:User\Company');
        $currentEnterprise = '';
        if ($this->company)
            $currentEnterprise = $this->company->getId() . '#' . $this->contextType;

        return $this->createFormBuilder()
            ->add('company', ChoiceType::class, [
                'choices'            => $choices,
                "attr"               => [
                    'class' => 'selectpicker',
                ],
                'data'               => $currentEnterprise,
                'group_by'           => function ($choiceValue, $key, $value) {
                    if (strpos($choiceValue, '#' . CurrentWorkspace::LEARNER) !== false) {
                        return $this->text->trans('Vous y êtes comme gestionnaire (Mon compte élève).', [], 'actor');
                    }
                    if (strpos($choiceValue, '#' . CurrentWorkspace::TEACHER) !== false) {
                        return $this->text->trans('Vous y êtes comme enseignant.', [], 'actor');
                    }
                    if (strpos($choiceValue, '#' . CurrentWorkspace::COLLABORATOR) !== false) {
                        return $this->text->trans('Vous y êtes comme collaborateur.', [], 'actor');
                    }
                    return 'Indéfini';
                },
                'expanded'           => false,
                'required'           => true,
                'multiple'           => false,
                'label'              => $this->text->trans('Choisir dans quelle compagnie effectuer les opérations', [], 'actor'),
                'translation_domain' => 'actor',
            ])
            ->getForm();
    }

    /**
     * Implements this method for init index actions.
     * @param \App\Contracts\FoundationInterface|null $entity
     */
    protected function initHeaderContents(FoundationInterface $entity = null)
    {
        // TODO: Implement initHeaderContents() method.
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedServices(): array
    {
        return [];
    }
}
