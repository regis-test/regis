<?php

declare(strict_types=1);

namespace Regis\AnalysisContext\Application\Inspection;

use Regis\AnalysisContext\Application\Inspection;
use Regis\AnalysisContext\Application\Vcs;
use Regis\AnalysisContext\Domain\Model\Exception\LineNotInDiff;
use Regis\AnalysisContext\Domain\Model\Git as Model;
use Regis\AnalysisContext\Domain\Entity\Violation;

class CodeSniffer implements Inspection
{
    private $codeSniffer;

    public function __construct(CodeSnifferRunner $codeSniffer)
    {
        $this->codeSniffer = $codeSniffer;
    }

    public function getType(): string
    {
        return 'phpcs';
    }

    public function inspectDiff(Vcs\Repository $repository, Model\Diff $diff): \Traversable
    {
        /** @var Model\Diff\File $file */
        foreach ($diff->getAddedPhpFiles() as $file) {
            $report = $this->codeSniffer->execute($file->getNewName(), $file->getNewContent());

            foreach ($report['files'] as $fileReport) {
                yield from $this->buildViolations($file, $fileReport);
            }
        }
    }

    private function buildViolations(Model\Diff\File $file, array $report): \Traversable
    {
        foreach ($report['messages'] as $message) {
            try {
                $position = $file->findPositionForLine($message['line']);
            } catch (LineNotInDiff $e) {
                continue;
            }

            if ($message['type'] === 'ERROR') {
                yield Violation::newError($file->getNewName(), $message['line'], $position, $message['message']);
            } else {
                yield Violation::newWarning($file->getNewName(), $message['line'], $position, $message['message']);
            }
        }
    }
}
