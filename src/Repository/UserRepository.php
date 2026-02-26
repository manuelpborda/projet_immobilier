<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Cuenta cantidad de inmuebles agrupados por tipo
     * Este código es parte de tu lógica original, no lo modifico.
     */
    public function countByType(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.typeDeBien AS typeDeBien, COUNT(b.id) AS count')
            ->groupBy('b.typeDeBien')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Cuenta cantidad de inmuebles agrupados por ciudad
     * Este código es parte de tu lógica original, no lo modifico.
     */
    public function countByCity(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.ville AS ville, COUNT(b.id) AS count')
            ->groupBy('b.ville')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * NUEVO MÉTODO: Cuenta usuarios que contienen un rol específico (campo JSON)
     * Compatible con Doctrine y MySQL sin usar JSON_CONTAINS.
     */
    public function countByRole(string $role): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"' . $role . '"%') // Busca el rol como texto en el array JSON
            ->getQuery()
            ->getSingleScalarResult();
    }
}
