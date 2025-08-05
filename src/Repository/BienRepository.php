<?php

namespace App\Repository;

use App\Entity\Bien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio de la entidad Bien.
 * Aquí centralizo todas las consultas complejas relacionadas a inmuebles,
 * como estadísticas y filtros personalizados.
 */
class BienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bien::class);
    }

    /**
     * Devuelve un array con el número de inmuebles por tipo (ej: Casa, Apartamento).
     * Este método me permite mostrar estadísticas agrupadas en el panel admin.
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
     * Devuelve un array con el número de inmuebles por ciudad.
     * Este dato me sirve para ver la distribución geográfica de propiedades.
     */
    public function countByCity(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.ville AS ville, COUNT(b.id) AS count')
            ->groupBy('b.ville')
            ->getQuery()
            ->getArrayResult();
    }
}
