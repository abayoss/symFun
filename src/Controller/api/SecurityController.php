<?php

namespace App\Controller\api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager, UserRepository $userRepo)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
        $this->userRepo = $userRepo;
    }

    /**
     * @Route("/api/register", name="api_security_register")
     */
    public function register(Request $request)
    {
        $req = $request->request;
        $user = $this->userRepo->findOneBy(['email' => $req->get('username')]);
        if (!is_null($user)) {
            return $this->json('email already in use');
        }
        $user = new User();
        $user->setEmail($req->get('username'));
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $req->get('password')
        ));
        $this->manager->persist($user);
        $this->manager->flush();
        return $this->json($user);
    }
}
