<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\ProfileImageType;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class SettingsProfileController extends AbstractController
{
    #[Route('/settings/profile', name: 'app_settings_profile')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function profile(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        /* @var User $user */
        $user = $this->getUser();
        $userProfile =  $user->getUserProfile() ?? new UserProfile();

        $form = $this->createForm(
            UserProfileType::class, $userProfile
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfile = $form->getData();
            $userProfile->setUser($user);
            $entityManager->persist($userProfile);
            $entityManager->flush();
            $this->addFlash('success', 'Profile updated');
            return $this->redirectToRoute('app_settings_profile');
        }

        return $this->render('settings_profile/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/settings/profile-image', name: 'app_settings_profile_image')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function profileImage(
       Request $request,
        SluggerInterface $slugger,
        UserRepository $users
    ): response
    {

        $form = $this->createForm(
            ProfileImageType::class,
        );

        /* @var User $user */
        $user = $this->getUser();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profileImageFile = $form->get('profileImage')->getData();
            if ($profileImageFile) {
                $originalFilename = pathinfo($profileImageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profileImageFile->guessExtension();
                try {
                    $profileImageFile->move(
                        $this->getParameter('profile_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Profile image upload failed');
                    return $this->redirectToRoute('app_settings_profile_image');
                }

                $profile = $user->getUserProfile() ?? new UserProfile();
                $profile->setImage($newFilename);
                $user->setUserProfile($profile);
                $users->save($user, true);
                $this->addFlash('success', 'Profile image updated');
                return $this->redirectToRoute('app_settings_profile_image');
            }
        }

        return $this->render(
            'settings_profile/profile_image.html.twig',
            [
                'form' => $form->createView(),
            ]

        );

    }
}
