<?php

declare(strict_types=1);

namespace Regis\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use RulerZ\RulerZ;
use RulerZ\Spec\Specification;

use Regis\Domain\Entity;
use Regis\Domain\Repository;

class DoctrineRepositories implements Repository\Repositories
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var RulerZ */
    private $rulerz;

    public function __construct(EntityManagerInterface $em, RulerZ $rulerz)
    {
        $this->em = $em;
        $this->rulerz = $rulerz;
    }

    public function save(Entity\Repository $team)
    {
        $this->em->persist($team);
        $this->em->flush();
    }

    public function matching(Specification $spec): \Traversable
    {
        /** @var QueryBuilder $qb */
        $qb = $this->repo()->createQueryBuilder('t');

        return $this->rulerz->filterSpec($qb, $spec);
    }

    public function find(string $id): Entity\Repository
    {
        $repository = $this->repo()->find($id);

        if ($repository === null) {
            throw Repository\Exception\NotFound::forIdentifier($id);
        }
        
        return $repository;
    }

    private function repo(): EntityRepository
    {
        return $this->em->getRepository(Entity\Repository::class);
    }
}
