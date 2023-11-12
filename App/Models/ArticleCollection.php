<?php

declare(strict_types=1);

namespace App\Models;

class ArticleCollection
{
    private array $articles;

    public function __construct()
    {
        $this->articles = [];
    }

    public function add(Article $article): void
    {
        $this->articles[] = $article;
    }

    public function getArticles(): array
    {
        return $this->articles;
    }
}