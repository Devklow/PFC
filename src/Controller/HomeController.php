<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\RoomRepository;
use App\Entity\Room;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function Index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Room::class);
        $rooms = $repository->findBy(
            ['Created' => true,
            'Player' => null,
            'Visibility' => true
            ]);

        return $this->render('home/index.html.twig', [
            'rooms' => $rooms
        ]);
    }

    #[Route('/home', name: 'app_home')]
    public function Home(): Response
    {
        return $this->redirectToRoute('app_index');
    }
}