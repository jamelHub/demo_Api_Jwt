<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\AuthoredEntityInterface;
use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\PublishedDateEntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PublishedGetEntitySubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW=>['setPublished',EventPriorities::PRE_WRITE]
        ];
    }
    public function  setPublished (GetResponseForControllerResultEvent $event)
    {
        $entity=$event->getControllerResult();
        $method=$event->getRequest()->getMethod();
        if(!$entity  instanceof  PublishedDateEntityInterface  || Request::METHOD_POST != $method)
        {
            return;
        }
    $entity->setPublished(new \DateTime());
    }
}