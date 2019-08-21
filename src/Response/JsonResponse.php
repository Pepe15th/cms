<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;

class JsonResponse extends Response
{

    /**
     * @var mixed
     */
    private $data;

    public function __construct($data = null, $status = 200, array $headers = [])
    {
        parent::__construct("", $status, $headers);
        $this->data = $data;

        if (!$this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', 'application/json');
        }
    }

    public function convertToJson()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([new DateTimeNormalizer, $normalizer], [new JsonEncoder()]);

        $this->content = $serializer->serialize($this->data, 'json');
    }

}
