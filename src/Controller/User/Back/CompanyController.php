<?php

namespace App\Controller\User\Back;

use App\Contracts\FoundationInterface;
use App\Controller\AbstractBackController;
use App\Form\User\CompanyType;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormError;

/**
 * 2018 Delivery Lab
 *
 * The back main controller class.
 *
 * All backend controllers classes must extends this class
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    App\Controller\User
 * @subpackage App\Controller\User\Back
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @Security("is_granted('ROLE_ACCESS_BO')")
 */
class CompanyController extends AbstractBackController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Security("is_granted('CAN_EDIT_COMPANY')")
     */
    public function edit()
    {
        $entity = $this->company;
        $this->isObject($entity, 'Company');
        $this->pageTitle = 'Entrer les informations de votre compagnie.';
        $form            = $this->createForm(CompanyType::class, $entity, ['edit_accounting_account' => false]);
        $form->handleRequest($this->request);
        $data = [
            'form'   => '',
            'status' => 0,
        ];
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->em->persist($entity);
                    $this->em->flush();
                    $data['status'] = 200;
                    return $this->json($data);
                } catch (Exception $e) {
                    $form->addError(new FormError($e->getMessage()));
                    $data['form'] = $this->renderView('user/back/company/ajax/add.html.twig', [
                        'form'      => $form->createView(),
                        'formClass' => 'companyForm',
                        'routePath' => $this->generateUrl('app_user_back_company_edit'),
                    ]);
                    return $this->json($data);
                }
            }
        }
        $data['form'] = $this->renderView('user/back/company/ajax/add.html.twig', [
            'form'      => $form->createView(),
            'formClass' => 'companyForm',
            'routePath' => $this->generateUrl('app_user_back_company_edit'),
        ]);
        return $this->json($data);
    }

    /**
     * Implements this method for init index actions.
     * @param \App\Contracts\FoundationInterface|null $entity
     */
    protected function initHeaderContents(FoundationInterface $entity = null)
    {
        // TODO: Implement initHeaderContents() method.
    }
}
