<?php

/*
 * Regis – Static analysis as a service
 * Copyright (C) 2016-2017 Kévin Gomez <contact@kevingomez.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace Regis\GithubContext\Domain\Entity;

use Regis\GithubContext\Domain\Model;

class PullRequestInspection extends Inspection
{
    private $pullRequestNumber;

    public static function create(Repository $repository, Model\PullRequest $pullRequest): self
    {
        /** @var PullRequestInspection $inspection */
        $inspection = parent::createForRevisions($repository, $pullRequest->getHead(), $pullRequest->getBase());
        $inspection->pullRequestNumber = $pullRequest->getNumber();

        return $inspection;
    }

    public function getType(): string
    {
        return self::TYPE_GITHUB_PR;
    }

    public function getPullRequestNumber(): int
    {
        return $this->pullRequestNumber;
    }

    public function getPullRequest(): Model\PullRequest
    {
        return new Model\PullRequest(
            $this->getRepository()->toIdentifier(),
            $this->pullRequestNumber,
            $this->getHead(),
            $this->getBase()
        );
    }
}
