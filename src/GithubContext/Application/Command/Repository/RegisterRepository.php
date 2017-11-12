<?php

declare(strict_types=1);

namespace Regis\GithubContext\Application\Command\Repository;

use Regis\GithubContext\Domain\Entity\User;

class RegisterRepository
{
    private $owner;
    private $identifier;
    private $sharedSecret;

    public function __construct(User $owner, string $identifier, string $sharedSecret = null)
    {
        $this->owner = $owner;
        $this->identifier = $identifier;
        $this->sharedSecret = $sharedSecret;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getSharedSecret()
    {
        return $this->sharedSecret;
    }
}