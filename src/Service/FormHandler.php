<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FormHandler
{

  private Request $request;

  public function __construct(
      private EntityManagerInterface $entityManager,
      private FormFactoryInterface $form,
      private Environment $twig,
      RequestStack $requestStack,
    ) 
  {
    $this->request = $requestStack->getCurrentRequest();
  }

  public function handleForm(string $formType, object $entity, string $title, string $backToListLink): Response|RedirectResponse
  { 
      $form = $this->form->create($formType,  $entity);
      $form->handleRequest($this->request);

      if ($form->isSubmitted() && $form->isValid()) {
          $this->entityManager->persist($entity);
          $this->entityManager->flush();
          return new RedirectResponse($backToListLink);
      }

      return new Response($this->twig->render('crud/edit_new.html.twig', [
          'title' => $title,
          'backToListLink' => $backToListLink,
          'entity' => $entity,
          'form' => $form->createView(),
      ]));
  }

}