<?php

declare(strict_types=1);

namespace Regis\Application\CommandHandler\Inspection;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

use Regis\Application\Command;
use Regis\Application\Entity;
use Regis\Application\Repository;

class Schedule
{
    private $repositoriesRepo;
    private $inspectionsRepo;

    public function __construct(ProducerInterface $producer, Repository\Repositories $repositoriesRepo, Repository\Inspections $inspectionsRepo)
    {
        $this->producer = $producer;
        $this->repositoriesRepo = $repositoriesRepo;
        $this->inspectionsRepo = $inspectionsRepo;
    }

    public function handle(Command\Inspection\Schedule $command)
    {
        $pullRequest = $command->getPullRequest();
        $repository = $this->repositoriesRepo->find($pullRequest->getRepositoryIdentifier());

        // crreate the inspection
        $inspection = Entity\Inspection::create($repository, $pullRequest);
        $this->inspectionsRepo->save($inspection);

        // and schedule it
        $this->producer->publish(json_encode([
            'inspection' => $inspection->getId(),
            'pull_request' => $pullRequest->toArray(),
        ]));
    }
}