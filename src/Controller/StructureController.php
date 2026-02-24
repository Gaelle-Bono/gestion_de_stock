<?php

namespace App\Controller;
use App\Service\FormHandler;

use App\Entity\Structure;
use App\Form\StructureType;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route('/structure')]
final class StructureController extends AbstractController
{
    #[Route(name: 'app_structure_index', methods: ['GET'])]
    public function index(RouterInterface $router,StructureRepository $structureRepository): Response
    {
        return $this->render('structure/index.html.twig', [
            'structures' => $structureRepository->findAll(),
            'createLink' => $router->generate('app_structure_new'),
        ]);
    }

    #[Route('/new', name: 'app_structure_new', methods: ['GET', 'POST'])]
    public function new(FormHandler $formHandler)
    {
        return $formHandler->handleForm(StructureType::class, new Structure(), 'Créer une structure', $this->generateUrl('app_structure_index'));
    }

    #[Route('/{id}', name: 'app_structure_show', methods: ['GET'])]
    public function show(RouterInterface $router, Structure $structure): Response
    {
        return $this->render('structure/show.html.twig', [
            'entity' => $structure,
            'deleteLink' => $router->generate('app_structure_delete', ['id' => $structure->getId()]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_structure_edit', methods: ['GET', 'POST'])]
    public function edit(FormHandler $formHandler, Structure $structure)
    {
        return $formHandler->handleForm(StructureType::class, $structure, 'Modifier une structure', $this->generateUrl('app_structure_index'));
    }

 #[Route('/{id}/delete', name: 'app_structure_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, Structure $structure): Response
    {
    
        $entityManager->remove($structure);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
    }

}
