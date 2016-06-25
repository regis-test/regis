<?php

namespace Regis\Application\Repository;

use Regis\Application\Entity;

class InMemoryRepositories implements Repositories
{
    private $repositories = [];

    public function __construct(array $repositories)
    {
        foreach ($repositories as $identifier => $repository) {
            $this->repositories[$identifier] = new Entity\Repository($identifier, $repository['secret']);
        }
    }

    public function save(Entity\Repository $repository)
    {
        $this->repositories[$repository->getIdentifier()] = $repository;
    }

    public function findAll(): \Traversable
    {
        foreach ($this->repositories as $identifier => $repo) {
            yield new Entity\Repository($identifier, $repo['secret']);
        }
    }

    public function find(string $identifier): Entity\Repository
    {
        if (!array_key_exists($identifier, $this->repositories)) {
            throw Exception\NotFound::forIdentifier($identifier);
        }
        
        return $this->repositories[$identifier];
    }
}
