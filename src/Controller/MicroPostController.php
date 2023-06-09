<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'micro_post')]
    public function index(MicroPostRepository $posts): Response
    {

        return $this->render('micro_post/index.html.twig', [
            'controller_name' => 'MicroPostController',
            'posts' => $posts->findAllWithComment()
        ]);
    }

    #[Route('/micro-post/top-liked', name: 'app_micro_post_topliked')]
    public function topLiked(MicroPostRepository $posts): Response
    {

        return $this->render('micro_post/top_liked.html.twig', [
            'posts' => $posts->findAllWithMinLikes(2)
        ]);
    }

    #[Route('/micro-post/follows', name: 'app_micro_post_follows')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function follows(MicroPostRepository $posts): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        return $this->render('micro_post/follows.html.twig', [
            'controller_name' => 'MicroPostController',
            'posts' => $posts->findAllByAuthors(
                $currentUser->getFollows()
            )
        ]);
    }

    #[Route('/micro-post/{post}', name: 'micro_post_show')]
    #[IsGranted(MicroPost::VIEW, 'post')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render(
            'micro_post/show.html.twig',
            [
                'post' => $post,
            ]
        );
    }

    #[Route('/micro-post/add', name: 'micro_post_add', priority: 2)]
//    #[IsGranted("IS_AUTHENTICATED_FULLY")] // This is any one logged in can add a post
    #[IsGranted("ROLE_VERIFIED")] // This is any one logged in and verified can add a post
    public function add(Request $request , EntityManagerInterface $entityManager): Response {
        // hot to get the current user
        $user = $this->getUser();

        $microPost = new MicroPost();
        $form = $this->createForm(MicroPostType::class , $microPost);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($user);
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('notice', 'Post was created');
            return $this->redirectToRoute('micro_post');
        }
        return $this->renderForm(
            'micro_post/add.html.twig',
            [
            'form' => $form
            ]
        );
    }

    #[Route('/micro-post/{post}/edit', name: 'micro_post_edit')]
    #[IsGranted(MicroPost::EDIT, 'post')]
    public function edit(
        MicroPost $post,
        Request $request,
        MicroPostRepository $posts
    ): Response {
        $form = $this->createForm(
            MicroPostType::class,
            $post
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $posts->add($post, true);

            // Add a flash
            $this->addFlash(
                'success',
                'Your micro post have been updated.'
            );

            return $this->redirectToRoute('micro_post');
            // Redirect
        }

        return $this->renderForm(
            'micro_post/edit.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }
    #[Route('/micro-post/delete/{post}', name: 'micro_post_delete')]
    public function delete(MicroPost $post, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('notice', 'Post was deleted');
        return $this->redirectToRoute('micro_post');
    }

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(MicroPost $post, Request $request , CommentRepository $comments): Response
    {
        $form = $this->createForm(CommentType::class , new Comment());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $comment = $form->getData();
           $comment->setPost($post);
           $comment->setAuther($this->getUser());
           $comments->save($comment, true);


            $this->addFlash('notice', 'Comment was created');
            return $this->redirectToRoute('micro_post_show', ['post' => $post->getId()]);
        }
        return $this->renderForm(
            'micro_post/comment.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }

}
