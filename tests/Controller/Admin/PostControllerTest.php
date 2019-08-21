<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Tests\DataFixtures\PostFixtures;
use App\Tests\FixtureTestCase;
use Doctrine\ORM\EntityManager;

class PostControllerTest extends FixtureTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->addFixture(new PostFixtures());
        $this->executeFixtures(); // recreate fixtures

        $kernel = static::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->postRepository = $this->entityManager->getRepository(Post::class);
    }

    /**
     * publish simple post and then check if it exists in list of published posts
     *
     * @return void
     */
    public function testPublish(): void
    {
        $client = $this->makeClient();

        $postId = $this->getSimplePostId();
        $client->request('GET', "/api/admin/post/publish/$postId");
        $this->assertStatusCode(200, $client);

        $client->request('GET', "/api/post");
        $jsonResponse = $client->getResponse()->getContent();
        $response = json_decode($jsonResponse, true);
        $this->assertContains($postId, array_column($response['items'], 'id'));
    }

    /**
     * @return int|null
     */
    private function getSimplePostId(): ?int
    {
        /** @var Post $post */
        $post = $this->postRepository->findOneBy(['title' => 'simple post']);

        return $post->getId();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
