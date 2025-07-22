<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudTpeType;
use App\Repository\CrudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function homePage(CrudRepository $repository ): Response
    {
        $datas = $repository->findAll();
        return $this->render('home_page/homePage.html.twig', [
            'datas'=> $datas,
        ]);
    }

    #[Route('/create', name: 'app_create')]
    public function create(Request $request , EntityManagerInterface $em): Response
    {
        $crud = new Crud();
        $form = $this -> createForm(CrudTpeType::class, $crud);
        $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $em->persist($crud);
                $em ->flush();

                $this->addFlash('success', 'Données enregistrées avec succès !');
                return $this->redirectToRoute('app_home_page');
            }
        return $this->render('form/createForm.html.twig', [
            'form' => $form ->createView(),

        ]);  
    }
}
