<?php

namespace App\Controller;

use App\Service\FormHandler;
use App\Entity\StockArticle;
use App\Form\StockArticleType;
use App\Repository\StockArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


#[Route('/article/stocks')]

final class StockArticleController extends AbstractController
{
    #[Route(name: 'app_stock_article_index', methods: ['GET'])]
    public function index(RouterInterface $router, StockArticleRepository $stockArticleRepository): Response
    {
        return $this->render('article_stock/index.html.twig', [
            'articles_stocks' => $stockArticleRepository->findAll(),
            'createLink' => $router->generate('app_stock_article_new'),
        ]);
    }

    #[Route('/new', name: 'app_stock_article_new', methods: ['GET', 'POST'])]
    public function new(FormHandler $formHandler)
    {
        return $formHandler->handleForm(StockArticleType::class, new StockArticle(), 'Créer un article en stock', $this->generateUrl('app_stock_article_index'));
    }

    #[Route('/{id}/edit', name: 'app_stock_article_edit',  requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, StockArticle $stockArticle): RedirectResponse|Response
    {
      $form = $this->createForm(StockArticleType::class, $stockArticle);
      
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

        $stockArticleArea = $entityManager->getRepository(StockArticle::class)->findOneBySameArticleAndArea($stockArticle);

        $entityManager->persist($stockArticle);
        
        if ($stockArticleArea){
            $stockArticleArea->setQuantity($stockArticleArea->getQuantity() + $stockArticle->getQuantity());
            $entityManager->persist($stockArticleArea);
            $entityManager->remove($stockArticle);
        }
        
        $entityManager->flush();
        return new RedirectResponse($this->generateUrl('app_stock_article_index'));
      }

        return new Response($this->render('crud/edit_new.html.twig', [
          'title' => 'Modifier un article en stock',
          'backToListLink' => $this->generateUrl('app_stock_article_index'),
          'entity' => $stockArticle,
          'form' => $form->createView(),
      ]));
    
    }





    #[Route('/{id}/increase', name: 'app_stock_article_increase_quantity',  requirements: ['id' => '\d+'], defaults:['increase' => true], methods: ['GET', 'POST'])]
    #[Route('/{id}/decrease', name: 'app_stock_article_decrease_quantity',  requirements: ['id' => '\d+'], defaults:['increase' => false], methods: ['GET', 'POST'])]
    public function increaseQuantity(EntityManagerInterface $entityManager, StockArticle $stockArticle, bool $increase):JsonResponse 
    {
        if ($increase) {
            $stockArticle->setQuantity($stockArticle->getQuantity() + 1);
        } else {
            if ($stockArticle->getQuantity() > 0) {
                $stockArticle->setQuantity($stockArticle->getQuantity() - 1);
            }
        }
        $entityManager->persist($stockArticle);
        $entityManager->flush();
        
        return new JsonResponse(['quantity' => $stockArticle->getQuantity()], Response::HTTP_OK);

    }


    #[Route('/{id}/delete', name: 'app_stock_article_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, StockArticle $stock): Response
    {
    
        $entityManager->remove($stock);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_stock_article_index', [], Response::HTTP_SEE_OTHER);
    }

}
