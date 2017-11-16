<?php

declare(strict_types=1);

namespace Tests\Regis\AppContext\Infrastructure\Symfony\Bundle\AppBundle\Security;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Regis\AppContext\Domain\Entity\User;
use Regis\AppContext\Infrastructure\Symfony\Bundle\AppBundle\Security\Context;

class ContextTest extends TestCase
{
    public function testItFetchesTheUserFromTheTokenStorage()
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $token = $this->createMock(TokenInterface::class);
        $user = $this->createMock(User::class);

        $context = new Context($tokenStorage);

        $tokenStorage->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->assertSame($user, $context->getUser());
    }
}
