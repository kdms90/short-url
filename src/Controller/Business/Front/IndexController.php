<?php

namespace App\Controller\Business\Front;

use App\Contracts\TokenGeneratorInterface;
use App\Controller\AbstractFrontController;
use App\Entity\Business\Url;
use App\Form\Business\UrlType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * Class IndexControllerAbstract
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App\Controller
 * @subpackage App\Controller\Front
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class IndexController extends AbstractFrontController
{
    /**
     * Add short link
     *
     * @param \App\Contracts\TokenGeneratorInterface $tokenGenerator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(TokenGeneratorInterface $tokenGenerator)
    {
        $url  = new Url();
        $form = $this->createForm(UrlType::class, $url);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($this->request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $token = strtolower($tokenGenerator->generateToken(4));
                $url->setRewrited($token);
                try {
                    $this->em->getConnection()->beginTransaction();
                    $this->em->persist($url);
                    $this->em->flush();
                    $this->em->clear();
                    $this->em->getConnection()->commit();
                    $data['form'] = $this->renderView('business/front/index/ajax/confirm-url.html.twig', [
                        'url'         => $url,
                        'rewritedUrl' => $this->generateUrl('app_business_front_url_find', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL),
                    ]);
                    return $this->json($data);
                } catch (Exception $e) {
                    $this->em->getConnection()->rollBack();
                    $form->addError(new FormError($e->getMessage()));
                    $data['form'] = $this->renderView('business/front/index/ajax/form.html.twig', [
                        'form' => $form->createView(),
                    ]);
                    return $this->json($data);
                }
            }
            $data['form'] = $this->renderView('business/front/index/ajax/form.html.twig', [
                'form' => $form->createView(),
            ]);
            return $this->json($data);
        }
        return $this->render('business/front/index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Redirect to orginal link and increase total views
     *
     * @param string|null $token
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function find(string $token = null)
    {
        $url = $this->em->getRepository(Url::class)->findOneBy(['rewrited' => $token]);
        if (!$url)
            return $this->redirectToRoute("app_foundation_front_homepage");

        try {
            $url->increaseViews();
            $this->em->getConnection()->beginTransaction();
            $this->em->persist($url);
            $this->em->flush();
            $this->em->clear();
            $this->em->getConnection()->commit();
            return $this->redirect($url->getOriginal());
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            return $this->redirectToRoute("app_foundation_front_homepage");
        }
    }
}
