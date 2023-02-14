<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, PersistenceManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createFormBuilder()
            ->add('username')
            ->add('email')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Hasło'],
                'second_options' => ['label' => 'Powtórz Hasło']
            ])
            ->add('Zarejestruj', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $user = new User();
            $user->setUserName($data['username']);
            $user->setEmail($data['email']);
            $user->setPassword(
                $passwordHasher->hashPassword($user, $data['password'])
            );
            dump($user);
            dump($data);
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect('../login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
