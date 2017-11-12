<?php

declare(strict_types=1);

namespace Regis\AnalysisContext\Application\Command;

use Regis\AnalysisContext\Domain\Model\Git;

class RunAnalyses
{
    private $repository;
    private $revisions;

    public function __construct(Git\Repository $repository, Git\Revisions $revisions)
    {
        $this->repository = $repository;
        $this->revisions = $revisions;
    }

    public function getRepository(): Git\Repository
    {
        return $this->repository;
    }

    public function getRevisions(): Git\Revisions
    {
        return $this->revisions;
    }
}