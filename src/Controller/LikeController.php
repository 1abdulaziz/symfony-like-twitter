<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(
        MicroPost $post,
        MicroPostRepository $posts,
        Request $request,
    ): Response
    {
        $currentUser = $this->getUser();
        $post->addLikedBy($currentUser);
        $posts->save($post, true);

        // referer GET LAST URL
        return $this->redirect($request->headers->get('referer'));

    }

    #[Route('/unlike/{id}', name: 'app_unlike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function unlike(
        MicroPost $post,
        MicroPostRepository $posts,
        Request $request
    ): Response
    {
        $currentUser = $this->getUser();
        $post->removeLikedBy($currentUser);
        $posts->save($post, true);
        return $this->redirect($request->headers->get('referer'));

    }
}
