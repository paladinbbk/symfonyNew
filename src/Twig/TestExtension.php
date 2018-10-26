<?php

namespace App\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TestExtension extends AbstractExtension
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('getArticleUrlById', [$this, 'getArticleUrlById']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getArticleUrlById', [$this, 'getArticleUrlById']),
        ];
    }

    public function getArticleUrlById($id)
    {
        return $this->router->generate('article_show', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function doSomething($value)
    {
        // ...
    }
}
