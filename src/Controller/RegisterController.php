<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Form\RegisterType;

class RegisterController extends AbstractController
{
	#[Route('/register', name: 'app_register')]
	public function index(ManagerRegistry $doctrine, Request $request): Response
	{
		$em = $doctrine->getManager();

		$user = new User();
		$form = $this->createForm(RegisterType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user = $form->getData();

			$em->persist($user);
			$em->flush();

			$session = $request->getSession();
			$session->set('notification', "user ajouté avec succès");
			$session->set('type_notif', "alert-success");

		}
		return $this->render('register/index.html.twig', [
			'form' => $form->createView(),
		]);
	}
}
