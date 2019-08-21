<?php

namespace App\Tests\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class PostFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $post = new Post('simple post', 'content');
        $manager->persist($post);

        $manager->flush();
    }
}
