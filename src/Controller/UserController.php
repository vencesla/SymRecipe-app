<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'user.edit')]
    public function edit(
        int $id,
        UserRepository $repo,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher): Response
    {
        $user = $repo->findOneBy(['id'=> $id]);

        if(!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }

        if($this->getUser() !== $user) {
            return $this->redirectToRoute('recipe.index');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Les informations de votre compte ont bien été modifiiées.'
                );

                return $this->redirectToRoute('recipe.index');
            }else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/utilisateur/edition-mot-de-passe/{id}', name:'user.edit.password', methods:['get', 'post'])]
    public function editPassword(
        UserPasswordHasherInterface $hasher,
        Request $request,
        UserRepository $repo,
        int $id,
        EntityManagerInterface $manager
    ):Response
    {
        if($repo->findOneBy(['id'=> $id])) {
            $user = $repo->findOneBy(['id'=> $id]);
        }else{
            $this->addFlash(
                'warning',
                'Votre utilisateur n\'existe pas'
            );
        }

        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {
                $user->setPlainPassword(
                    $form->getData()['newPassword']);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Les informations de votre compte ont bien été modifiiées.'
                );

                return $this->redirectToRoute('recipe.index');
            }else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form'=> $form->createView(),
        ]);
    }
}
