<?php

declare(strict_types=1);

namespace Regis\Application\CommandHandler\User;

use Regis\Application\Command;
use Regis\Domain\Entity\User;
use Regis\Domain\Repository;

class CreateOrUpdateUser
{
    private $usersRepo;

    public function __construct(Repository\Users $usersRepo)
    {
        $this->usersRepo = $usersRepo;
    }

    public function handle(Command\User\CreateOrUpdateUser $command)
    {
        try {
            $user = $this->usersRepo->findByGithubId($command->getGithubId());
            $user->changeEmail($command->getEmail());
            $user->changeGithubAccessToken($command->getAccessToken());
        } catch (Repository\Exception\NotFound $e) {
            $user = User::createUser($command->getUsername(), $command->getGithubId(), $command->getAccessToken(), $command->getEmail());
        }

        $this->usersRepo->save($user);

        return $user;
    }
}
