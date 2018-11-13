<?php

namespace App\Controller;

use App\Entity\Article;
use App\Exception\MyException;
use App\Repository\ArticleRepository;
use App\Service\MyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(ArticleRepository $articleRepository, MyService $myService)
    {
        //$articles = $articleRepository->findAll();

        try {
            $articles = $myService->getAllArticles();
        } catch (MyException $e) {
            return $this->redirectToRoute('article_index');
        } catch (\Exception $e) {
            return $this->redirectToRoute('article_index');
        }

        return $this->render('main/index.html.twig', compact('articles'));
    }
//
//    /**
//     * @Route("/article/{id}", name="article")
//     */
//    public function article($id, ArticleRepository $articleRepository)
//    {
//        $article = $articleRepository->find($id);
//
//        return $this->render('main/article.html.twig', compact('article'));
//    }
}
