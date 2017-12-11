<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\RestViewInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelViewSubscriber
 */
class KernelViewSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => 'kernelEventsView',
        ];
    }

    /**
     * Simple Kernel Events View
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function kernelEventsView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        $data = [
            'data' => [],
        ];
        if ($result instanceof RestViewInterface) {
            $data['data'][] = $result->jsonSerialize();
        }

        if (is_array($result) || $result instanceof \Traversable) {
            foreach ($result as $item) {
                if ($item instanceof RestViewInterface) {
                    $data['data'][] = $item->jsonSerialize();
                }
            }
        }

        if ($result instanceof FormInterface) {
            $this->handleErrors($event);

            return;
        }

        $response = new JsonResponse($data);
        $event->setResponse($response);
    }

    /**
     * Handle errors
     *
     * @param GetResponseForControllerResultEvent $event
     */
    protected function handleErrors(GetResponseForControllerResultEvent $event)
    {
        /** @var FormInterface $result */
        $result = $event->getControllerResult();
        $errors = $result->getErrors(true);
        $data['errors'] = [];
        foreach ($errors as $error) {
            $data['errors'][] = ['message' => $error->getMessage()];
        }

        $response = new JsonResponse($data, 400);
        $event->setResponse($response);
    }
}
