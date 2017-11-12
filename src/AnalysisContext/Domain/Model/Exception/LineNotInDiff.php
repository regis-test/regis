<?php

declare(strict_types=1);

namespace Regis\AnalysisContext\Domain\Model\Exception;

class LineNotInDiff extends \RuntimeException
{
    public static function line(int $line): self
    {
        return new static(sprintf('Line %d is not in diff.', $line));
    }
}