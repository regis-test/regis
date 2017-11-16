<?php

declare(strict_types=1);

namespace Tests\Regis\AppContext\Application\CommandHandler\Repository;

use PHPUnit\Framework\TestCase;
use Regis\AppContext\Application\Command;
use Regis\AppContext\Application\CommandHandler;
use Regis\AppContext\Domain\Entity;
use Regis\AppContext\Domain\Repository;
use Regis\Kernel;

class DisableInspectionsTest extends TestCase
{
    private $repositoriesRepo;
    /** @var CommandHandler\Repository\DisableInspections */
    private $handler;

    public function setUp()
    {
        $this->repositoriesRepo = $this->createMock(Repository\Repositories::class);

        $this->handler = new CommandHandler\Repository\DisableInspections($this->repositoriesRepo);
    }

    public function testItRemovesTheUserFromTheTeam()
    {
        $owner = $this->createMock(Kernel\User::class);
        $repo = new Entity\Repository($owner, Entity\Repository::TYPE_GITHUB, 'super/repo', 'repo name');

        $command = new Command\Repository\DisableInspections($repo);

        $this->repositoriesRepo->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Entity\Repository $repo) {
                return !$repo->isInspectionEnabled();
            }));

        $this->handler->handle($command);
    }
}
