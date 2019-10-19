<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\DistanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @var DistanceRepository
     */
    private $distanceRepository;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    public function __construct(DistanceRepository $distanceRepository, CommentRepository $commentRepository)
    {
        $this->distanceRepository = $distanceRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @Route("/distance/{distanceId}/comments", name="comments_list")
     *
     * @param Request $request
     * @param int $distanceId
     * @return Response
     */
    public function listAction(Request $request, int $distanceId): Response
    {
//        $distance = $this->distanceRepository->find($distanceId);

        /** @var Comment[] $comments */
        $comments = $this->commentRepository->findForDistance($distanceId);
        $result = [];

        foreach ($comments as $comment) {
            $parent = $comment->getParent() ? $comment->getParent()->getId() : null;
            $result[] = [
                'id' => $comment->getId(),
                'text' => $comment->getText(),
                'date' => $comment->getDate(),
                'user' => $comment->getUser()->getEmail(),
                'parent_id' => $parent
            ];
        }
//        $this->recursiveWalk($comments);
//        dump();
//
//        exit;

        return new JsonResponse($this->buildTree($result));
    }

    public function buildTree(array &$elements, $parentId = 0) {
        $branch = [];
        foreach ($elements as &$element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                } else {
                    $element['children'] = [];
                }
                $branch[] = $element;
                unset($element);
            }
        }
        return $branch;
    }
}