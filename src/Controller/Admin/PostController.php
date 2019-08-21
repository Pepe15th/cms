<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Response\JsonResponse;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use App\Service\Doctrine\Paginator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;

class PostController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route(path="/api/admin/post", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns all posts and paginator",
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
     * @SWG\Tag(name="admin posts")
     */
    public function getList(Request $request): JsonResponse
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        /** @var PostRepository $postRepository */
        $postsQb = $postRepository->createBaseQueryBuilder();

        $packedPosts = Paginator::packResponse($postsQb, $request);

        return new JsonResponse($packedPosts);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws Exception
     * @Route(path="/api/admin/post", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns created post",
     *     @SWG\Schema(ref=@Model(type=Post::class))
     * )
     * @SWG\Parameter(name="title", in="query", type="string")
     * @SWG\Parameter(name="content", in="query", type="string")
     * @SWG\Tag(name="admin posts")
     */
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $title = $request->get('title');
        $content = $request->get('content');

        $post = new Post($title, $content);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);

        $errors = $validator->validate($post);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = ['propertyPath' => $error->getPropertyPath(), 'message' => $error->getMessage()];
            }
            return new JsonResponse($errorMessages, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse($post);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws Exception
     * @Route(path="/api/admin/post", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated post",
     *     @SWG\Schema(ref=@Model(type=Post::class))
     * )
     * @SWG\Parameter(name="postId", in="query", type="number", required=true)
     * @SWG\Parameter(name="title", in="query", type="string", required=true)
     * @SWG\Parameter(name="content", in="query", type="string", required=true)
     * @SWG\Tag(name="admin posts")
     */
    public function update(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $postId = (int) $request->get('postId');
        $title = $request->get('title');
        $content = $request->get('content');

        $post = $this->getPost($postId);
        if (!$post) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $post->setTitle($title);
        $post->setContent($content);
        $post->setModifiedAt(new \DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);

        $errors = $validator->validate($post);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = ['propertyPath' => $error->getPropertyPath(), 'message' => $error->getMessage()];
            }
            return new JsonResponse($errorMessages, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse($post);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route(path="/api/admin/post/{postId}", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns empty body"
     * )
     * @SWG\Parameter(name="postId", in="path", type="number")
     * @SWG\Tag(name="admin posts")
     */
    public function delete(Request $request): JsonResponse
    {
        $postId = (int) $request->get('postId');

        $post = $this->getPost($postId);
        if ($post) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();

            return new JsonResponse([]);
        }

        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @Route(path="/api/admin/post/publish/{postId}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns published post",
     *     @SWG\Schema(ref=@Model(type=Post::class))
     * )
     * @SWG\Parameter(name="postId", in="path", type="number")
     * @SWG\Tag(name="admin posts")
     */
    public function publish(Request $request): JsonResponse
    {
        $postId = (int) $request->get('postId');

        $post = $this->getPost($postId);
        if ($post) {
            $entityManager = $this->getDoctrine()->getManager();
            $post->publish();
            $entityManager->persist($post);
            $entityManager->flush();

            return new JsonResponse($post);
        }

        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }

    /**
     * @param int $postId
     * @return Post|JsonResponse
     */
    private function getPost(int $postId)
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        /** @var Post $post */
        $post = $postRepository->find($postId);

        if (!$post) {
            return null;
        }
        return $post;
    }
}
