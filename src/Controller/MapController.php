<?php

namespace App\Controller;

use App\Entity\Map;
use App\Form\MapType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\MapRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

#[Route('/map', name: 'app_map.')]
class MapController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request,MapRepository $mapRepository, PersistenceManagerRegistry $doctrine)
    {
        $maps = $mapRepository->findAll();
        $map = new Map();

        $form = $this->createForm(MapType::class, $map);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            dump($map);
            $em->persist($map);
            $em->flush();

            return $this->redirect('../map');
        }
        dump($maps);

        return $this->render('map/index.html.twig', [
            'maps' =>  $maps,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function remove(Map $map, PersistenceManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        $em->remove($map);
        $em->flush();
        $this->addFlash('sukces','Znacznik został usunięty');

        return $this->redirect('../');
    }
}
