<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
//    #[Route('/', name: 'app_user_profile')]
//    public function index(UserProfileRepository $profiles): Response
//    {
//        $user = new User();
//        $user->setEmail('aziz.sa0@gmail.com');
//        $user->setPassword('123456');
//        $user->setRoles(['ROLE_USER']);
//
//        $profile = new UserProfile();
//        $profile->setUser($user);
//        $profiles->save($profile, true);
//
//        return $this->render('user_profile/index.html.twig', [
//            'controller_name' => $profile->getUser()->getEmail(),
//        ]);
//    }

    #[Route('/user-profile/{id}', name: 'app_user_profile_show')]
    public function show(UserProfile $profile): Response
    {
        // get current user
        $user = $profile->getUser();
        // get current user profile
        $userProfile = $user->getUserProfile();

        return $this->render('user_profile/show.html.twig', [
            'user' => $user,
            'user_profile' => $userProfile,
        ]);
    }




}
