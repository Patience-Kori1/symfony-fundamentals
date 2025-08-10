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
        //$datas = $em->getRepository(Crud::class)->findAll() Méthode longue de la ligne précédente
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

    #[Route('/update/{id}', name: 'app_update_form')]
    public function update(Request $request , EntityManagerInterface $em, $id, CrudRepository $repository ): Response
    {
        $crud = $repository->find($id);
        //$crud = $em->getRepository(Crud::class)->find($id) Méthode longue de la ligne précédente
        $form = $this -> createForm(CrudTpeType::class, $crud);
        $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $em->persist($crud);
                $em ->flush();
                $this->addFlash('notice', 'Modification réussie');
                return $this->redirectToRoute('app_home_page');
            }
        return $this->render('form/createForm.html.twig', [
            'form' => $form ->createView(),
        ]);
    }
    
    #[Route('/delete/{id}', name:'app_delete_form')]
    
    function delete(Request $request, $id, CrudRepository $repository, EntityManagerInterface $em)
    {
        $crud = $repository->find($id);
        $em->remove($crud);
        $em->flush();

        $this->addFlash('notice', 'Supression effectuée avec réussie');
        return $this->redirectToRoute('app_home_page');

    }
}
