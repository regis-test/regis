<?php

declare(strict_types=1);

namespace Regis\Bundle\WebhooksBundle\Worker;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Regis\Bundle\WebhooksBundle\Event\DomainEventWrapper;
use Regis\Domain\Event;
use Regis\Domain\Inspector;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class WebhookEvent implements ConsumerInterface
{
    private $inspector;
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, Inspector $inspector)
    {
        $this->dispatcher = $dispatcher;
        $this->inspector = $inspector;
    }

    public function execute(AMQPMessage $msg)
    {
        $event = unserialize($msg->body);

        if ($event instanceof Event\PullRequestOpened || $event instanceof Event\PullRequestSynced) {
            $pullRequest = $event->getPullRequest();

            $this->dispatch(Event::INSPECTION_STARTED, new Event\InspectionStarted($pullRequest));

            $this->inspector->inspect($pullRequest);

            $this->dispatch(Event::INSPECTION_FINISHED, new Event\InspectionFinished($pullRequest));
        }
    }

    private function dispatch(string $eventName, Event $event)
    {
        $this->dispatcher->dispatch($eventName, new DomainEventWrapper($event));
    }
}