<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/micro-post/{post}', name: 'micro_post_show')]
    public function show(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'controller_name' => 'MicroPostController',
            'post' => $post
        ]);
    }

    #[Route('/micro-post/add', name: 'micro_post_add', priority: 2)]
    public function add(Request $request , EntityManagerInterface $entityManager): Response
    {
        $microPost = new MicroPost();
        $form = $this->createFormBuilder($microPost)
            ->add('title')
            ->add('text')
            ->add('submit', SubmitType::class, [
                'label' => 'Create Post'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new \DateTime());
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

    #[Route('/micro-post/edit/{post}', name: 'micro_post_edit')]
    public function edit(MicroPost $post, Request $request , EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder($post)
            ->add('title')
            ->add('text')
            ->add('submit', SubmitType::class, [
                'label' => 'Update Post'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new \DateTime());
            $entityManager->persist($post);


            $entityManager->flush();

            $this->addFlash('notice', 'Post was updated');
            return $this->redirectToRoute('micro_post');
        }
        return $this->renderForm(
            'micro_post/edit.html.twig',
            [
                'form' => $form
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
    public function addComment(MicroPost $post, Request $request , CommentRepository $comments): Response
    {
        $form = $this->createForm(CommentType::class , new Comment());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $comment = $form->getData();
           $comment->setPost($post);
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
