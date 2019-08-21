<?php declare(strict_types=1);

namespace App\Service\EventListener;

use App\Response\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class JsonResponseTransformerListener
{
    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        /** @var JsonResponse $response */
        $response = $event->getResponse();

        if ($response instanceof JsonResponse) {
            $response->convertToJson();
        }
    }
}
