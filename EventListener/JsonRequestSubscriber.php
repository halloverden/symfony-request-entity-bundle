<?php

namespace HalloVerden\RequestEntityBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class JsonRequestSubscriber implements EventSubscriberInterface {

  /**
   * @param RequestEvent $event
   */
  public function onKernelRequest(RequestEvent $event): void {
    $request = $event->getRequest();

    if ($request->getContentTypeFormat() === 'json') {
      $data = json_decode($request->getContent(), true);
      $request->request->replace(is_array($data) ? $data : []);
    }
  }

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents(): array {
    return [
      KernelEvents::REQUEST => ['onKernelRequest', 30]
    ];
  }

}
