<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;


class ProfilController extends AbstractController
{

     /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/profil', name: 'app_authenticated_profil')]
    public function index(ManagerRegistry $doctrine): Response
    {
        if($this->security->getUser()!=null){
            return $this->redirectToRoute('app_profil', ['profil' => $this->security->getUser()->getUsername()]);
        }
        else{
            return $this->redirectToRoute('app_index');
        }
    }

    #[Route('/profil/{profil}', name: 'app_profil')]
    public function profil($profil, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $user = $repository->findOneBy(['username' => $profil]);
        
        $signs =array(  1 => $user->getR(),
                        2 => $user->getP(),
                        3 => $user->getS(),
                        0 => 1);
        $favorite = array_search(max($signs), $signs);
        return $this->render('profil/index.html.twig', [
            'profil' => $profil,
            'rock' => $user->getR(),
            'paper' => $user->getP(),
            'scissors' => $user->getS(),
            'win' => $user->getWin(),
            'lose' => $user->getLose(),
            'eq' => $user->getEq(),
            'favorite' => $favorite
        ]);
    }
}
