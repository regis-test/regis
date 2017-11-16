<?php

declare(strict_types=1);

namespace Tests\Regis\GithubContext\Infrastructure\Github;

use PHPUnit\Framework\TestCase;
use Github\Client as VendorClient;
use Psr\Log\LoggerInterface;
use Regis\GithubContext\Domain\Entity\GithubDetails;
use Regis\GithubContext\Domain\Entity\Repository;
use Regis\GithubContext\Infrastructure\Github\Client;
use Regis\GithubContext\Infrastructure\Github\ClientFactory;

class ClientFactoryTest extends TestCase
{
    /** @var ClientFactory */
    private $clientFactory;

    public function setUp()
    {
        $vendorClient = $this->createMock(VendorClient::class);
        $logger = $this->createMock(LoggerInterface::class);

        $this->clientFactory = new ClientFactory($vendorClient, $logger);
    }

    public function testCreateForRepository()
    {
        $repository = $this->createMock(Repository::class);
        $owner = $this->createMock(GithubDetails::class);

        $repository->expects($this->once())
            ->method('getOwner')
            ->willReturn($owner);

        $client = $this->clientFactory->createForRepository($repository);

        $this->assertInstanceOf(Client::class, $client);
    }
}
