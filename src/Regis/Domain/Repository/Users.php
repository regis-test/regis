<?php

declare(strict_types=1);

namespace Regis\Domain\Repository;

use Regis\Domain\Entity;

interface Users
{
    public function save(Entity\User $user);

    public function findByGithubId(int $id): Entity\User;
    public function findByUsername(string $username): Entity\User;
    public function findById(int $id): Entity\User;
}
