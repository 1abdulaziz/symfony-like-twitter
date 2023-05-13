<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    private array $messages = ['hello','hi','hey','yo'];

    #[Route('/{limit<\d+>?3}', name: 'app_hello')]
    public function index($limit): Response {
//        $post = $posts->find(1);
//        // create new comment
//        $comment = new Comment();
//        $comment->setText('This is a comment' . time());
//        $comment->setPost($post);
//        $post->addComment($comment);
//        $posts->save($post, true);
//dd($post);
        return $this->render('hello/index.html.twig', [
            'controller_name' => implode(',', array_slice($this->messages, 0, $limit)),
        ]);
    }

    #[Route('/hello/{id}', name: 'app_hello_name')]
    public function showOne(string $id): Response
    {
        return $this->render('hello/show.html.twig', [
            'controller_name' => $this->messages[$id],
        ]);
    }
}
