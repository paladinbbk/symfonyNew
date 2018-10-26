<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use Symfony\Component\DependencyInjection\Container;

class MyService
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getAllArticles()
    {
        return $this->articleRepository->findAll();
    }
}