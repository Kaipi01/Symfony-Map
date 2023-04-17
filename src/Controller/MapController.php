<?php

namespace App\Controller;

use App\Entity\Map;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\MapRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MapController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository): Response {
        $users = $userRepository->findAll();

        return $this->render('map/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/add-mark', name: 'add-mark')]
    public function add(
        Request $request,
        PersistenceManagerRegistry $doctrine
    ): Response {

        if ($request->isXmlHttpRequest() && $request->isMethod('POST'))
        {
            $map = new Map();

            $map->setName($request->get('name'));
            $map->setLatitude($request->get('latitude'));
            $map->setLongitude($request->get('longitude'));

            $em = $doctrine->getManager();
            $em->persist($map);
            $em->flush();
        }  
        return new Response();
    }

    #[Route('/get-marks', name: 'get-marks')]
    public function get(MapRepository $mapRepository): JsonResponse
    {
        $maps = $mapRepository->findAll();
        return $this->json($maps);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function remove(
        Map $map,
        PersistenceManagerRegistry $doctrine,
        MapRepository $mapRepository
    ): JsonResponse {
        $em = $doctrine->getManager();
        $em->remove($map);
        $em->flush();
        $maps = $mapRepository->findAll();

        return $this->json($maps);
    }
}
