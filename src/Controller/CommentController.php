<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CommentController extends AbstractController
{
    #[Route('/get-comments', name: 'get-comments')]
    public function index(
        CommentRepository $commentRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $comments = $commentRepository->findAll();

        $json = $serializer->serialize(
            $comments,
            'json',
            ['groups' => ['user', 'comment']]
        );
        return $this->json($json);
    }

    #[Route('/add-comment/{id}', name: 'add-comment')]
    public function add(
        User $user,
        Request $request,
        PersistenceManagerRegistry $doctrine
    ): Response {

        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $em = $doctrine->getManager();
            $comment = new Comment();
            $currentDate = new \DateTime();

            $replyToCommentID = $request->get('replyToComment');
            $replyToComment = $em->getRepository(Comment::class)->find($replyToCommentID);

            $comment->setAuthor($user);
            $comment->setCreatedAt($currentDate);
            $comment->setContent($request->get('content'));

            if ($replyToComment) {
                $comment->setReplyToComment($replyToComment);
            }

            $em->persist($comment);
            $em->flush();
        }
        return new Response();
    }

    #[Route('/remove-comment/{id}', name: 'remove-comment')]
    public function remove(
        $id,
        PersistenceManagerRegistry $doctrine
    ): Response {
        $em = $doctrine->getManager();

        $comment = $em->getRepository(Comment::class)->find($id);

        $em->remove($comment);
        $em->flush();

        return new Response();
    }
}
