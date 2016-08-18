<?php

namespace Regis\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;

use Regis\Domain\Entity;
use Regis\Domain\Repository;

class DoctrineRepositories implements Repository\Repositories
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(Entity\Repository $team)
    {
        $this->em->persist($team);
        $this->em->flush();
    }

    /**
     * TODO handle teams
     */
    public function findForUser(Entity\User $user): \Traversable
    {
        return new \ArrayIterator($this->em->getRepository(Entity\Repository::class)->findBy([
            'owner' => $user,
        ]));
    }

    public function find(string $id): Entity\Repository
    {
        $repository = $this->em->getRepository(Entity\Repository::class)->find($id);

        if ($repository === null) {
            throw Repository\Exception\NotFound::forIdentifier($id);
        }
        
        return $repository;
    }
}
