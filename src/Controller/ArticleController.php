<?php

namespace App\Controller;

use Amp\Future;
use App\Page\Block\ArticleContent;
use App\Page\Block\LastArticles;
use App\Page\Block\RelatedArticles;
use App\Page\Builder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Amp\Future\await;

class ArticleController extends AbstractController
{
    public function __construct(
        private readonly Builder $builder,
    ) {
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $data = await([
            'LastArticles' => $this->builder->get(LastArticles::class, ['size' => 30]),
        ]);

        dump($data);

        return $this->render('pages/index.html.twig', $data);
    }

    #[Route('/article/{id}', name: 'app_article')]
    public function article(int $id): Response
    {
        $data = await([
            'ArticleContent' => $this->builder->get(ArticleContent::class, ['id' => $id]),
            'RelatedArticles' => $this->builder->get(RelatedArticles::class, ['id' => $id]),
            'LastArticles' => $this->builder->get(LastArticles::class, ['size' => 3]),
        ]);

        dump($data);

        return $this->render('pages/article.html.twig', $data);
    }
}
