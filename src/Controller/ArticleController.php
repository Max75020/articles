<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\ArticleType;

class ArticleController extends AbstractController
{
	#[Route('/', name: 'list_articles')]
	public function index(Request $request, ManagerRegistry $doctrine): Response
	{

		$em = $doctrine->getManager();

		$articles = $em->getRepository(Article::class)->findAll();

		$session = $request->getSession();
		$notification = $session->get('notification');
		$type_notif = $session->get('type_notif');

		return $this->render('article/index.html.twig', [
			'articles' => $articles,
			'notification' => $notification,
			'type_notif' => $type_notif
		]);
	}

	#[Route('/edit-article/{id_article}', name: 'edit_article')]
	public function editArticle($id_article, ManagerRegistry $doctrine, Request $request): Response
	{

		$em = $doctrine->getManager();

		$article = $em->getRepository(Article::class)->find($id_article);
		if ($article === null) {
			return $this->redirectToRoute('list_articles');
		}
		$form = $this->createForm(ArticleType::class, $article);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			if ($form->get('submit')->isClicked()) {
				// Le bouton Valider a été cliqué
				$article->setBody(nl2br($article->getBody()));
				$em->flush();
		
				$session = $request->getSession();
				$session->set('notification', "Article modifié avec succès");
				$session->set('type_notif', "alert-success");
		
				return $this->redirectToRoute('list_articles');
			} elseif ($form->get('delete')->isClicked()) {
				// Le bouton Supprimer a été cliqué
				$em->remove($article);
				$em->flush();
		
				$session = $request->getSession();
				$session->set('notification', "Article supprimé avec succès");
				$session->set('type_notif', "alert-success");
		
				return $this->redirectToRoute('list_articles');
			}
		}
		

		return $this->render('article/editArticle.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('/view-article/{id_article}', name: 'view_article')]
	public function viewArticle($id_article, ManagerRegistry $doctrine): Response
	{

		$em = $doctrine->getManager();

		$article = $em->getRepository(Article::class)->find($id_article);
		if ($article === null) {
			return $this->redirectToRoute('list_articles');
		}

		return $this->render('article/viewArticle.html.twig', [
			'article' => $article,
		]);
	}

	#[Route('/article-add', name: 'add_article')]
	public function addArticle(Request $request, ManagerRegistry $doctrine): Response
	{
		$em = $doctrine->getManager();

		$article = new Article();
		$form = $this->createForm(ArticleType::class, $article);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$article = $form->getData();

			$article->setBody(nl2br($article->getBody()));

			$em->persist($article);
			$em->flush();

			$session = $request->getSession();
			$session->set('notification', "Article ajouté avec succès");
			$session->set('type_notif', "alert-success");

			return $this->redirectToRoute('list_articles');
		}
		return $this->render('article/addArticle.html.twig', [
			'form' => $form->createView(),
		]);
	}

	#[Route('/delete-article/{id_article}', name: 'delete_article')]
	public function deleteArticle($id_article, ManagerRegistry $doctrine, Request $request): Response
	{
		// Doctrine gère grace à des fonction les données en BDD
		$em = $doctrine->getManager();
		// Récupération de l'article en BDD via son id
		$article = $em->getRepository(Article::class)->find($id_article);

		// Suppresion en BDD
		$em->remove($article);
		$em->flush();

		$session = $request->getSession();
		$session->set('notification', "Article supprimé avec succès");
		$session->set('type_notif', "alert-success");

		return $this->redirectToRoute('list_articles');
	}
}
