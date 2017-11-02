<?php

declare(strict_types=1);

namespace Regis\AnalysisContext\Application;

use Regis\AnalysisContext\Application\Vcs\Git;
use Regis\AnalysisContext\Application\Vcs\Repository;
use Regis\AnalysisContext\Domain\Model;
use Regis\AnalysisContext\Domain\Entity;

class Inspector
{
    private $git;
    /** @var Inspection[] */
    private $inspections;

    public function __construct(Git $git, array $inspections = [])
    {
        $this->git = $git;
        $this->inspections = $inspections;
    }

    public function inspect(Model\Git\Repository $repository, Model\Git\Revisions $revisions): Entity\Report
    {
        $gitRepository = $this->git->getRepository($repository);
        $gitRepository->checkout($revisions->getHead());

        $diff = $gitRepository->getDiff($revisions);

        return $this->inspectDiff($gitRepository, $diff);
    }

    private function inspectDiff(Repository $repository, Model\Git\Diff $diff): Entity\Report
    {
        $report = new Entity\Report($diff->getRawDiff());

        foreach ($this->inspections as $inspection) {
            $analysis = new Entity\Analysis($inspection->getType());

            foreach ($inspection->inspectDiff($repository, $diff) as $violation) {
                $analysis->addViolation($violation);
            }

            $report->addAnalysis($analysis);
        }

        return $report;
    }
}
