<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class ArticleController extends AbstractController
{
	#[Route('/', name: 'app_articles')]
	public function index(ManagerRegistry $doctrine): Response
	{

		$em = $doctrine->getManager();

		$articles = $em->getRepository(Article::class)->findAll();

		return $this->render('article/index.html.twig', [
			'articles' => $articles,
		]);
	}

	#[Route('/edit-article/{id_article}', name: 'edit_article')]
	public function editArticle(ManagerRegistry $doctrine): Response
	{

		$em = $doctrine->getManager();

		$articles = $em->getRepository(Article::class)->findAll();

		return $this->render('article/index.html.twig', [
			'articles' => $articles,
		]);
	}

	#[Route('/view-article/{id_article}', name: 'view_article')]
	public function viewArticle(ManagerRegistry $doctrine): Response
	{

		$em = $doctrine->getManager();

		$articles = $em->getRepository(Article::class)->findAll();

		return $this->render('article/index.html.twig', [
			'articles' => $articles,
		]);
	}
	#[Route('/view-article/add', name: 'add_article')]
	public function addArticle(ManagerRegistry $doctrine): Response
	{
		$em = $doctrine->getManager();

		$articles = $em->getRepository(Article::class)->findAll();

		return $this->render('article/index.html.twig', [
			'articles' => $articles,
		]);
	}
}
