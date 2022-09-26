<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Room;
use App\Entity\User;


class RoomApiController extends AbstractController
{
    #[Route('/room/api/get/{Room}', name: 'app_room_api')]
    public function index($Room, ManagerRegistry $doctrine): Response
    {
        $MyRoom = $doctrine->getRepository(Room::class)->find($Room);
        $HP = false;
        $PP = false;
        if($MyRoom->getPC()!=null){
            $PP = true;
        }
        if($MyRoom->getHC()!=null){
            $HP = true;
        }
        if($HP && $PP){
            $PC = $MyRoom->getPC();
            $HC = $MyRoom->getHC();
        }
        else{
            $PC = null;
            $HC = null;
        }
        $info = array(
            'Host' => $MyRoom->getHost(),
            'Player' => $MyRoom->getPlayer(),
            'HP' => $HP,
            'PP' => $PP,
            'Winner' => $MyRoom->getWinner(),
            'MatchWinner' => $MyRoom->getMatchWinner(),
            'HostReady' => $MyRoom->IsHostReady(),
            'PlayerReady' => $MyRoom->IsPlayerReady(),
            'CurrentRound' => $MyRoom->getCurrentRound(),
            'MaxRound' => $MyRoom->getMaxRound(),
            'PRV' => $MyRoom->getPRV(),
            'HRV' => $MyRoom->getHRV(),
            'PC' => $PC,
            'HC' => $HC
        );
        return new JsonResponse($info);
    }
    #[Route('/room/api/disconnect', name: 'app_room_disconnect_api')]
    public function disconnect(ManagerRegistry $doctrine, Request $request): Response
    {
        $RoomId = $request->request->get('room');
        $name = $request->request->get('name');
        $Room = $doctrine->getRepository(Room::class)->find($RoomId);
        $entityManager = $doctrine->getManager();
        if($name ==  $Room->getHost())
        {
            if($Room->getPlayer()==null){
                $Room->setHost(null);
                $Room->setHID(null);
            }
            else{
                $Room->setHost($Room->getPlayer());
                $Room->setHID($Room->getPID());
                $Room->setHostReady(null);
                $Room->setHC(null);
                $Room->setPlayer(null);
                $Room->setPID(null);
                $Room->setPlayerReady(null);
                $Room->setPC(null);
                $Room->setCurrentRound(null);
                $Room->setHRV(null);
                $Room->setPRV(null);
                $Room->setWinner(null);
                $Room->setMatchWinner(null);
            }
        }
        else if($name ==  $Room->getPlayer()){
            $Room->setPlayer(null);
            $Room->setPID(null);
            $Room->setPlayerReady(null);
            $Room->setPC(null);
            $Room->setHC(null);
            $Room->setCurrentRound(null);
            $Room->setHostReady(null);
            $Room->setHRV(null);
            $Room->setPRV(null);
            $Room->setWinner(null);
            $Room->setMatchWinner(null);
        }
        if($Room->getHost()==null && $Room->getPlayer()==null){
            $entityManager->remove($Room);
            $entityManager->flush();
        }
        else{
            $entityManager->persist($Room);
            $entityManager->flush();
        }
    }

    #[Route('/room/api/play', name: 'app_room_play_api')]
    public function play(ManagerRegistry $doctrine, Request $request): Response
    {
        $RoomId = $request->request->get('room');
        $name = $request->request->get('name');
        $value = $request->request->get('value');
        $ready = $request->request->get('ready');
        $entityManager = $doctrine->getManager();
        $Room = $doctrine->getRepository(Room::class)->find($RoomId);
        $CheckWin = false;
        if($ready && !($Room->IsHostReady() && $Room->IsPlayerReady())){
            if($name==$Room->getHost()){
                $Room->setHostReady(true);
            }
            else{
                $Room->setPlayerReady(true);
            }
            $Room->setWinner(null);
            $Room->setMatchWinner(null);
            $Room->setPC(null);
            $Room->setHC(null);
            if(($Room->IsPlayerReady() || $Room->IsHostReady()) && $Room->getCurrentRound()==$Room->getMaxRound()){
                $Room->setCurrentRound(0);
            }
            else if($Room->IsPlayerReady() && $Room->IsHostReady()){
                $Room->setCurrentRound($Room->getCurrentRound()+1);
            }
            $entityManager->persist($Room);
            $entityManager->flush();
        }
        if($value>=1 && $value <=3){
            if($name == $Room->getHost())
            {
                $Room->setHC($value);
                $entityManager->persist($Room);
                $entityManager->flush();
                if($Room->getPC()!=null){
                    $CheckWin = true;
                }
            } 
            else if($name == $Room->getPlayer()){
                $Room->setPC($value);
                $entityManager->persist($Room);
                $entityManager->flush();
                if($Room->getHC()!=null){
                    $CheckWin = true;
                }
            }
        }
        if($CheckWin){
            $Room->setHostReady(false);
            $Room->setPlayerReady(false);
            $HC=$Room->getHC();
            $PC=$Room->getPC();
            $HID=$Room->getHID();
            $PID=$Room->getPID();
            $CurrentRound=$Room->getCurrentRound();
            $MaxRound=$Room->getMaxRound();
            $Players =array($Room->getHost() => $Room->getHC(),
                            $Room->getPlayer() => $Room->getPC());
            if($Room->getHC()==$Room->getPC()){
                $Winner="Eq";
            }
            else if(($Room->getHC()+$Room->getPC())%2==1){
                $Winner = array_search(max($Players), $Players);
            }
            else{
                $Winner = array_search(min($Players), $Players);
            }
            $Room->SetWinner($Winner);

            if($Winner == $Room->getHost()){
                $Room->setHRV($Room->getHRV()+1);
            }
            if($Winner == $Room->getPlayer()){
                $Room->setPRV($Room->getPRV()+1);
            }

            if($CurrentRound==$MaxRound){
                if($Room->getHRV()==$Room->getPRV()){
                    $Room->setMatchWinner("Eq");
                }
                else{
                    $Players2=array($Room->getHost() => $Room->getHRV(),
                                    $Room->getPlayer() => $Room->getPRV());
                    $MatchWinner = array_search(max($Players2), $Players2);
                    $Room->setMatchWinner($MatchWinner);
                    $Room->setHRV(null);
                    $Room->setPRV(null);
                    if($HID!=null){
                        $Player = $doctrine->getRepository(User::class)->find($HID);
                        if($MatchWinner=="Eq"){
                            $Player->setEq($Player->getEq()+1);
                        }
                        if($MatchWinner == $Room->getHost()){
                            $Player->setWin($Player->getWin()+1);
                        }
                        else{
                            $Player->setLose($Player->getLose()+1);
                        }
                        $entityManager->persist($Player);
                        $entityManager->flush();
                    }
                    if($PID!=null){
                        $Player = $doctrine->getRepository(User::class)->find($PID);
                        if($Winner=="Eq"){
                            $Player->setEq($Player->getEq()+1);
                        }
                        if($Winner == $Room->getHost()){
                            $Player->setWin($Player->getWin()+1);
                        }
                        else{
                            $Player->setLose($Player->getLose()+1);
                        }
                        $entityManager->persist($Player);
                        $entityManager->flush();
                    }
                }
            }

            if($HID!=null){
                $Player = $doctrine->getRepository(User::class)->find($HID);
                if($HC==1){
                    $Player->setR($Player->getR()+1);
                }
                else if($HC==2){
                    $Player->setP($Player->getP()+1);
                }
                else{
                    $Player->setS($Player->getS()+1);
                }
                $entityManager->persist($Player);
                $entityManager->flush();
            }
            if($PID!=null){
                $Player = $doctrine->getRepository(User::class)->find($PID);
                if($PC==1){
                    $Player->setR($Player->getR()+1);
                }
                else if($PC==2){
                    $Player->setP($Player->getP()+1);
                }
                else{
                    $Player->setS($Player->getS()+1);
                }
                $entityManager->persist($Player);
                $entityManager->flush();
            }
            $entityManager->persist($Room);
            $entityManager->flush();
        }
        return new JsonResponse("OK");
    }

    #[Route('/room/api/update', name: 'app_room_update_api')]
    public function update(ManagerRegistry $doctrine, Request $request): Response
    {
        $RoomId = $request->request->get('room');
        $name = $request->request->get('name');
        $entityManager = $doctrine->getManager();
        $Room = $doctrine->getRepository(Room::class)->find($RoomId);
        if(($Room->getCurrentRound()==0 || $Room->getCurrentRound()==null || ($Room->getCurrentRound()==$Room->getMaxRound() && $Room->getMatchWinner()!=null)) && $Room->getHost()==$name){
            $Visibility = $request->request->get('visibility');
            $MaxRound = $request->request->get('round');
            $Room->setVisibility($Visibility);
            $Room->setMaxRound($MaxRound);
            $Room->setCurrentRound(null);
            $Room->setPlayerReady(false);
            $Room->setHostReady(false);
            $entityManager->persist($Room);
            $entityManager->flush();
        }
        return new JsonResponse("OK");
    }
}
