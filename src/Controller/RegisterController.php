<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
	#[Route('/register', name: 'app_register')]
	public function index(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
	{
		$em = $doctrine->getManager();

		$user = new User();
		$form = $this->createForm(RegisterType::class, $user);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $session = $request->getSession();
            $session->set('notification', "Utilisateur crée avec succès");
            $session->set('type_notif', "alert-success");

            return $this->redirectToRoute('list_articles');
        }
		return $this->render('register/index.html.twig', [
			'form' => $form->createView(),
		]);
	}
}
