<?php

namespace App;

use App\Models\Article;
use App\Models\ArticleCollection;
use Carbon\Carbon;
use jcobhams\NewsApi\NewsApi as Api;

class NewsApi
{
    private Api $newsApi;

    public function __construct()
    {
        $apiKey = $_ENV['API_KEY'];
        $this->newsApi = new Api($apiKey);
    }

    public function fetchEverything(
        $q,
        $sources,
        $domains,
        $excludeDomains,
        $from,
        $to,
        $language,
        $sortBy,
        $pageSize,
        $page
    ): ArticleCollection
    {
        $articles = new ArticleCollection();

        $response = $this->newsApi->getEverything(
            $q,
            $sources,
            $domains,
            $excludeDomains,
            $from,
            $to,
            $language,
            $sortBy,
            $pageSize,
            $page
        );

        $this->extracted($response, $articles);
        return $articles;
    }

    public function fetchTopHeadLines(
        $q,
        $sources,
        $country,
        $category,
        $pageSize,
        $page
    ): ArticleCollection
    {
        $articles = new ArticleCollection();

        $response = $this->newsApi->getTopHeadLines(
            $q,
            $sources,
            $country,
            $category,
            $pageSize,
            $page
        );

        $this->extracted($response, $articles);
        return $articles;
    }

    private function extracted($response, ArticleCollection $articles): void
    {
        foreach ($response->articles as $article) {
            $author = ($article->author !== null) ? $article->author : '';
            $description = ($article->description !== null) ? $article->description : '';
            $urlToImage = ($article->urlToImage !== null) ? $article->urlToImage : 'img/not-found.png';
            $content = ($article->content !== null) ? $article->content : '';

            $articles->add(new Article(
                $article->source->name,
                $author,
                $article->title,
                $description,
                $article->url,
                $urlToImage,
                Carbon::parse($article->publishedAt),
                $content
            ));
        }
    }

    public function categories(): ?array
    {
        return $this->newsApi->getCategories();
    }
}