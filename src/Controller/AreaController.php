<?php

namespace App\Controller;

use App\Service\FormHandler;

use App\Entity\Area;
use App\Form\AreaType;
use App\Repository\AreaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route('/area')]
final class AreaController extends AbstractController
{
    #[Route(name: 'app_area_index', methods: ['GET'])]
    public function index(RouterInterface $router, AreaRepository $areaRepository): Response
    {
        return $this->render('area/index.html.twig', [
            'areas' => $areaRepository->findAll(),
            'createLink' => $router->generate('app_area_new'),
        ]);
    }

    #[Route('/new', name: 'app_area_new', methods: ['GET', 'POST'])]
    public function new(FormHandler $formHandler)
    {
        return $formHandler->handleForm(AreaType::class, new Area(), 'Créer un emplacement', $this->generateUrl('app_area_index'));
    }

    #[Route('/{id}', name: 'app_area_show', methods: ['GET'])]
    public function show(RouterInterface $router, Area $area): Response
    {
        return $this->render('area/show.html.twig', [
            'entity' => $area,
            'deleteLink' => $router->generate('app_area_delete', ['id' => $area->getId()]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_area_edit', methods: ['GET', 'POST'])]
    public function edit(FormHandler $formHandler, Area $area)
    {
       return $formHandler->handleForm(AreaType::class, $area, 'Modifier un emplacement', $this->generateUrl('app_area_index'));
    }

    #[Route('/{id}/delete', name: 'app_area_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, Area $area): Response
    {
    
        $entityManager->remove($area);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_area_index', [], Response::HTTP_SEE_OTHER);
    }

}
