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

namespace Regis\AnalysisContext\Application\Command;

use Regis\AnalysisContext\Domain\Model\Git;

class InspectRevisions
{
    private $inspectionId;
    private $repository;
    private $revisions;

    public function __construct(string $inspectionId, Git\Repository $repository, Git\Revisions $revisions)
    {
        $this->inspectionId = $inspectionId;
        $this->repository = $repository;
        $this->revisions = $revisions;
    }

    public function getInspectionId(): string
    {
        return $this->inspectionId;
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
