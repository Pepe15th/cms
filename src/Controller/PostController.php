<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Response\JsonResponse;
use App\Service\Doctrine\Paginator;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;

class PostController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route(path="/api/post", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns published posts and paginator",
     *     @SWG\Schema(
     *         @SWG\Property(property="paginator",
     * 	           @SWG\Property(property="page", type="number"),
     * 	           @SWG\Property(property="pagesCount", type="number"),
     * 	           @SWG\Property(property="itemsPerPage", type="number"),
     * 	           @SWG\Property(property="itemsCount", type="number"),
     *         ),
     *         @SWG\Property(property="items", type="array", @SWG\Items(ref=@Model(type=Post::class))),
     *     )
     * )
     * @SWG\Parameter(name="page", in="query", type="number")
     * @SWG\Parameter(name="itemsPerPage", in="query", type="number")
     * @SWG\Tag(name="posts")
     */
    public function getList(Request $request): JsonResponse
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        /** @var PostRepository $postRepository */
        $postsQb = $postRepository->findPublished();

        $packedPosts = Paginator::packResponse($postsQb, $request);

        return new JsonResponse($packedPosts);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @Route(path="/api/post/{postId}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns published post by id",
     *     @SWG\Schema(ref=@Model(type=Post::class))
     * )
     * @SWG\Parameter(name="postId", in="path", type="number")
     * @SWG\Tag(name="posts")
     */
    public function getOne(Request $request): JsonResponse
    {
        $postId = (int) $request->get('postId');

        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        /** @var PostRepository $postRepository */
        $post = $postRepository->findPublishedById($postId)
            ->getQuery()
            ->getOneOrNullResult();

        if ($post) {
            return new JsonResponse($post);
        }

        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }
}
