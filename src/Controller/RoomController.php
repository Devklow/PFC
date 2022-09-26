<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Entity\Room;
use App\Entity\User;

class RoomController extends AbstractController
{

     /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/create', name: 'app_create')]
    public function Create(Request $request, ManagerRegistry $doctrine): Response
    {
        $session = $request->getSession();
        if($request->request->count()>0){
            $roomname = $request->request->get('room');
            $visibility = $request->request->get('Visibility');
            $round = $request->request->get('round');
            if(empty($roomname)){
                if($session->has('Name')){
                    $roomname = $session->get('Name')  . "'s room";
                }
                else{
                    $name = "Guest" . strval(rand(1000, 9999));
                    $roomname = $name  . "'s room";
                    $session->set('Name', $name);
                }
            }
            $entityManager = $doctrine->getManager();
            $room = new Room();
            $room->setName($roomname);
            $room->setVisibility($visibility);
            $room->setMaxRound($round);
            $entityManager->persist($room);
            $entityManager->flush();
            $id = $room->getId();
            return $this->redirectToRoute('app_room', ['Room' => $id]);
        }
        else{
            return $this->render('room/create.html.twig', []);
        }
    }

    #[Route('/{Room}', name: 'app_room', requirements: ['Room' => '\d+'])]
    public function room($Room, Request $request, ManagerRegistry $doctrine): Response
    {
        $session = $request->getSession();
        if($request->request->count()>0 && !empty($request->request->get('pseudo')) || $this->security->getUser()!=null){
            $MyRoom = $doctrine->getRepository(Room::class)->find($Room);
            if($this->security->getUser()!=null){
                $name = $this->security->getUser()->getUsername();
            }
            else{
                $name = $request->request->get('pseudo');
                $session->set('Name', $name);
            }
            if($MyRoom !=null){
                if($MyRoom->getHost()==$name){
                    $name .= " (2)";
                }
                $entityManager = $doctrine->getManager();
                if($MyRoom->getHost() == null){
                    $MyRoom->setHost($name);
                    $MyRoom->setCreated(true);
                    if($this->security->getUser()!=null){
                        $HID = $this->security->getUser()->getId();
                        $MyRoom->setHID($HID);
                    }
                }
                    else if($MyRoom->getPlayer() == null){
                        $MyRoom->setPlayer($name);
                        if($this->security->getUser()!=null){
                            $PID = $this->security->getUser()->getId();
                            $MyRoom->setPID($PID);
                        }
                    }
                    else{
                        return $this->redirectToRoute('app_index', []);
                    }
                    if($MyRoom->getPID() == $MyRoom->getHID()){
                        $MyRoom->setPID(null);
                    }
                    $entityManager->persist($MyRoom);
                    $entityManager->flush();
                    return $this->render('room/index.html.twig', [
                        'id' => $Room,
                        'Host' => $MyRoom->getHost(),
                        'Player' => $MyRoom->getPlayer(),
                        'Room' => $Room
                    ]);
            }
            else{
                return $this->redirectToRoute('app_index', []);
            }
        }
        else{
            if($session->has('Name')){
                $name = $session->get('Name');
            }
            else{
                $name = "Guest";
                $number = strval(rand(1000, 9999));
                $name .= $number;
            }
            return $this->render('room/join.html.twig', ['Room' => $Room, 'name'=>$name]);
        }
    }
}
