<?php

namespace App\Controller;

use App\Entity\Likes;
use App\Entity\User;
use App\Repository\LikesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

class LikesController extends AbstractController
{
    #[Route('/get-likes', name: 'get-likes')]
    public function get(LikesRepository $likesRepository): JsonResponse
    {
        $likes = $likesRepository->findAll();
        return $this->json($likes);
    }   

    #[Route('/add-like/{id}', name: 'add-like')]
    public function add(
        User $user,
        Request $request,
        PersistenceManagerRegistry $doctrine
    ): void
    {
        if ($request->isXmlHttpRequest() && $request->isMethod('POST'))
        {
            $em = $doctrine->getManager();

            $likes = $em->getRepository(Likes::class)->find(1);
            $likes->setNumber($likes->getNumber() + 1);

            $user->setIsLiked(true);

            $em->persist($likes);
            $em->flush();
        } 
    }   

    #[Route('/remove-like/{id}', name: 'remove-like')]
    public function remove(
        User $user,
        Request $request,
        PersistenceManagerRegistry $doctrine
    ): void
    {
        if ($request->isXmlHttpRequest() && $request->isMethod('POST'))
        {
            $em = $doctrine->getManager();

            $likes = $em->getRepository(Likes::class)->find(1);
            $likes->setNumber($likes->getNumber() - 1);

            $user->setIsLiked(false);

            $em->persist($likes);
            $em->flush();
        } 
    }  
}
