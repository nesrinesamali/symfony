<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
    
        /**
         * @Route("/showService/{name}", name="app_service")
         */
        public function showService($name): Response
        {
            $message = "Service " . $name;
    
            return $this->render('service/showService.html.twig', [
                'name' => $message,
            ]);
        }
        /**
     * @Route("/goToIndex", name="app_go_to_index")
     */
    public function goToIndex(UrlGeneratorInterface $urlGenerator): RedirectResponse
    {
        // Générer l'URL de redirection vers l'action index du contrôleur HomeController
        $url = $urlGenerator->generate('app_home');

        // Effectuer la redirection vers l'action index du contrôleur HomeController
        return $this->redirect($url);
    }
}
