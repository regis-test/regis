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

namespace Regis\AppContext\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Regis\Kernel;

class Repository
{
    public const TYPE_GITHUB = 'github';
    public const TYPE_BITBUCKET = 'bitbucket';

    private $id;
    private $identifier;
    private $name;
    private $type;
    private $sharedSecret;
    private $isInspectionEnabled = true;
    private $isFlightModeEnabled = false;
    /** @var ArrayCollection */
    private $inspections;
    /** @var ArrayCollection */
    private $teams;
    private $owner;

    public function __construct(Kernel\User $owner, string $type, string $identifier, string $name, string $sharedSecret = null)
    {
        $this->id = Kernel\Uuid::create();
        $this->owner = $owner;
        $this->type = $type;
        $this->name = $name;
        $this->identifier = $identifier;
        $this->sharedSecret = $sharedSecret;
        $this->inspections = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public function newSharedSecret(string $sharedSecret): void
    {
        $this->sharedSecret = $sharedSecret;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOwner(): Kernel\User
    {
        return $this->owner;
    }

    public function getSharedSecret(): string
    {
        return $this->sharedSecret;
    }

    public function getInspections(): \Traversable
    {
        return $this->inspections;
    }

    public function getTeams(): \Traversable
    {
        return $this->teams;
    }

    public function isInspectionEnabled(): bool
    {
        return $this->isInspectionEnabled;
    }

    public function disableInspection(): void
    {
        $this->isInspectionEnabled = false;
    }

    public function enableInspection(): void
    {
        $this->isInspectionEnabled = true;
    }

    public function isFlightModeEnabled(): bool
    {
        return $this->isFlightModeEnabled;
    }

    public function disableFlightMode(): void
    {
        $this->isFlightModeEnabled = false;
    }

    public function enableFlightMode(): void
    {
        $this->isFlightModeEnabled = true;
    }
}
