<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudTpeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function homePage(): Response
    {
        return $this->render('home_page/homePage.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }

    #[Route('/create', name: 'app_create_form')]
    public function create_form(Request $request): Response
    {
        $crud = new Crud();
        $form = $this -> createForm(CrudTpeType::class, $crud);
        $form->handleRequest($request);
    
        return $this->render('form/createForm.html.twig', [
            'form' => $form ->createView(),
        ]);
    }
}
