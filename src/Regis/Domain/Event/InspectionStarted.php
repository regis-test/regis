<?php

declare(strict_types=1);

namespace Regis\Domain\Event;

use Regis\Domain\Event;
use Regis\Domain\Model\Github\PullRequest;

class InspectionStarted implements Event
{
    private $pullRequest;

    public function __construct(PullRequest $pullRequest)
    {
        $this->pullRequest = $pullRequest;
    }

    public function getPullRequest(): PullRequest
    {
        return $this->pullRequest;
    }

    public function getEventName(): string
    {
        return Event::INSPECTION_STARTED;
    }
}