<?php

namespace App\Controller;

use App\Entity\SecurityUser;
use App\Entity\Video;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Services\MyService;

class DefaultController extends AbstractController
{

    #[Route('/admin/{id}/delete-video', name: 'delete_video')]
    public function deleteVideo(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        Video $video
    ): Response
    {
        dd($video);

        return new Response('Video deleted');
    }

    #[Route('/default', name: 'app_default')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        MyService $myService
    ): Response
    {
        $users = $entityManager->getRepository(SecurityUser::class)->findAll();

        dump($users);

        $user = new SecurityUser();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $form->get('password')
                ->getData())
            );
            $user->setEmail($form->get('email')->getData());
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_default');
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
