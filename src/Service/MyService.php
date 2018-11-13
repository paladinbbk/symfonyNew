<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use Symfony\Component\DependencyInjection\Container;
use App\Exception\MyException;

class MyService
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getAllArticles()
    {
        if (!$error = true) {
            throw new MyException();
        }

        return $this->articleRepository->findAll();
    }
}