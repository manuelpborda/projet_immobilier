<?php

namespace App\Repository;

use App\Entity\Bien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio de la entidad Bien.
 * Centraliza todas las consultas complejas relacionadas a inmuebles,
 * como estadísticas y filtros personalizados.
 */
class BienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bien::class);
    }

    /**
     * Devuelve un array con el número de inmuebles por tipo ordenados de mayor a menor.
     */
    public function countByType(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.typeDeBien AS typeDeBien, COUNT(b.id) AS count')
            ->groupBy('b.typeDeBien')
            ->orderBy('count', 'DESC') // Optimización para ver qué tipo de inmueble predomina
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Devuelve un array con el número de inmuebles por ciudad ordenados por volumen.
     */
    public function countByCity(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.ville AS ville, COUNT(b.id) AS count')
            ->groupBy('b.ville')
            ->orderBy('count', 'DESC') // Optimización para identificar zonas calientes o de mayor inventario
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * MOTOR DE BÚSQUEDA DINÁMICA
     * Permite filtrar los inmuebles de la vitrina comercial según los criterios del usuario.
     * * @param array $filters Contiene las llaves opcionales (ville, typeDeBien, tipoTransaccion, estrato, precioMin, precioMax)
     * @return Bien[]
     */
    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('b');

        // Filtrar por ciudad (ignora mayúsculas/minúsculas de forma segura)
        if (!empty($filters['ville'])) {
            $qb->andWhere('LOWER(b.ville) LIKE LOWER(:ville)')
               ->setParameter('ville', '%' . $filters['ville'] . '%');
        }

        // Filtrar por Tipo de Bien (Apartamento, Casa, Finca, etc.)
        if (!empty($filters['typeDeBien'])) {
            $qb->andWhere('b.typeDeBien = :typeDeBien')
               ->setParameter('typeDeBien', $filters['typeDeBien']);
        }

        // Filtrar por Tipo de Transacción (Venta, Arriendo, Vacacional)
        if (!empty($filters['tipoTransaccion'])) {
            $qb->andWhere('b.tipoTransaccion = :tipoTransaccion')
               ->setParameter('tipoTransaccion', $filters['tipoTransaccion']);
        }

        // Filtrar por Estrato Socioeconómico colombiano (1 al 6)
        if (!empty($filters['estrato'])) {
            $qb->andWhere('b.estrato = :estrato')
               ->setParameter('estrato', $filters['estrato']);
        }

        // Rango de precio mínimo
        if (!empty($filters['precioMin'])) {
            $qb->andWhere('b.prix >= :precioMin')
               ->setParameter('precioMin', $filters['precioMin']);
        }

        // Rango de precio máximo
        if (!empty($filters['precioMax'])) {
            $qb->andWhere('b.prix <= :precioMax')
               ->setParameter('precioMax', $filters['precioMax']);
        }

        // Ordenamos para mostrar siempre los inmuebles más nuevos primero
        $qb->orderBy('b.id', 'DESC');

        return $qb->getQuery()->getResult();
    }
    /**
 * Devuelve una lista plana de valores únicos para un campo específico.
 * Utilizado para rellenar los selects de los filtros dinámicos en el Home.
 */
public function findDistinctValues(string $field): array
{
    $results = $this->createQueryBuilder('b')
        ->select(sprintf('DISTINCT b.%s', $field))
        ->where(sprintf('b.%s IS NOT NULL', $field))
        ->getQuery()
        ->getScalarResult();

    return array_column($results, $field);
}
}