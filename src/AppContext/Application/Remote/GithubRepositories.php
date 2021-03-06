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

namespace Regis\AppContext\Application\Remote;

use Regis\AppContext\Domain\Model;
use Regis\AppContext\Domain\Entity;
use Regis\GithubContext\Application\Github\ClientFactory as GithubClientFactory;
use Regis\GithubContext\Domain\Model as GithubModel;
use Regis\GithubContext\Domain\Repository as GithubRepository;
use Regis\GithubContext\Domain\Repository\Exception as GithubException;
use Regis\Kernel;

class GithubRepositories implements Repositories
{
    private $clientFactory;
    private $usersRepo;

    public function __construct(GithubClientFactory $clientFactory, GithubRepository\Users $usersRepo)
    {
        $this->clientFactory = $clientFactory;
        $this->usersRepo = $usersRepo;
    }

    public function forUser(Kernel\User $user): iterable
    {
        try {
            $githubUser = $this->usersRepo->findByAccountId($user->accountId());
        } catch (GithubException\NotFound $e) {
            return [];
        }

        $githubClient = $this->clientFactory->createForUser($githubUser);

        /** @var GithubModel\Repository $repository */
        foreach ($githubClient->listRepositories() as $repository) {
            yield new Model\Repository(
                $repository->getIdentifier(),
                $repository->getIdentifier(),
                $repository->getPublicUrl(),
                Entity\Repository::TYPE_GITHUB
            );
        }
    }
}
