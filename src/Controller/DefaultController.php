<?php

namespace App\Controller;

use App\Entity\SecurityUser;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ): Response
    {
        $users = $entityManager->getRepository(SecurityUser::class)->findAll();

        dump($users);

        $user = new SecurityUser();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $form->get('password')
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
}
