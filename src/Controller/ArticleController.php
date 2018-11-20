<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/api/articles", name="article_index_api", methods="GET")
     */
    public function indexApi(ArticleRepository $articleRepository, Request $request, ParameterBagInterface $parameterBag): Response
    {
        $articles = $articleRepository->findAll();

        $data = [];

        foreach ($articles as $article) {
            $item['title'] = $article->getTitle();
            $item['content'] = $article->getContent();
            $item['createdAt'] = $article->getCreatedAt()->format('Y-m-d');
            $data['article'][] = $item;
        }

        $response = new JsonResponse($data);
        $response->headers->set('contentType', 'json');

        return $response;

        return $this->render('article/index.html.twig', ['articles' => $articleRepository->findAll()]);
    }

    /**
     * @Route("/", name="article_index", methods="GET")
     */
    public function index(ArticleRepository $articleRepository, Request $request, ParameterBagInterface $parameterBag): Response
    {
        dump(getenv('APP_SECRET'));
        return $this->render('article/index.html.twig', ['articles' => $articleRepository->findAll()]);
    }

    /**
     * @Route("/new", name="article_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->imageUpload();
            $article->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{article}", name="article_show", methods="GET", requirements={"article"="\d+"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods="GET|POST")
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->imageUpload();

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('article-updated', 'Success!');

            return $this->redirectToRoute('article_edit', ['id' => $article->getId()]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods="DELETE")
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em = $this->get('doctrine.entity_manager');
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/{article}/new-comment", name="article_create_comment", methods="POST")
     */
    public function newComment(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = (new Comment())
            ->setArticle($article)
            ->setAuthor($this->getUser())
            ->setText($request->get('text'));

        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('article-updated', 'Success!');

        return $this->redirectToRoute('article_show', ['article' => $article->getId()]);
    }
}