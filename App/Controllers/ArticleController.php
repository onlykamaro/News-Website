<?php

declare(strict_types=1);

namespace App\Controllers;

use App\NewsApi;
use App\Response;

class ArticleController
{
    private NewsApi $api;

    public function __construct()
    {
        $this->api = new NewsApi();
    }

    public function index(array $vars = []): Response
    {
        $id = isset($vars['id']) ? (int)$vars['id'] : 0;
        switch ($id) {
            case 1:
                $response = $this->topHeadlines();
                break;
            default:
                $response = $this->everything();
                break;
        }
        return $response;
    }

    private function everything(): Response
    {
        $q = $_GET['question'] ?? '*';
        $sources = $_GET['sources'] ?? null;
        $domains = $_GET['domains'] ?? null;
        $excludeDomains = $_GET['excludeDomains'] ?? null;
        $from = null;
        if (isset($_GET['from']) && $_GET['from'] != 0) {
            $from = $_GET['from'];
        }
        $to = null;
        if (isset($_GET['to']) && $_GET['to'] != 0) {
            $to = $_GET['to'];
        }
        $language = $_GET['language'] ?? null;
        $sortBy = $_GET['sortBy'] ?? null;
        $pageSize = $_GET['pageSize'] ?? null;
        $page = $_GET['page'] ?? null;

        $articles = $this->api->fetchEverything(
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

        return new Response('article/index', [
            'articles' => $articles->getArticles()
        ]);
    }

    private function topHeadlines(): Response
    {
        $q = $_GET['question'] ?? null;
        $sources = $_GET['sources'] ?? null;
        $country = $_GET['country'] ?? null;
        $category = $_GET['category'] ?? null;
        $pageSize = $_GET['pageSize'] ?? null;
        $page = $_GET['page'] ?? null;

        $articles = $this->api->fetchTopHeadLines(
            $q,
            $sources,
            $country,
            $category,
            $pageSize,
            $page
        );

        return new Response('article/index', [
            'articles' => $articles->getArticles()
        ]);
    }
}