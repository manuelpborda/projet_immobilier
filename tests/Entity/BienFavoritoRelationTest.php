<?php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Bien;
use App\Entity\Favorito;

/**
 * En este test unitario voy a comprobar la lógica más simple y realista
 * de mi aplicación: "marcar y desmarcar un Bien como favorito".
 * 
 * Mi objetivo es verificar:
 * - que addFavorito() agrega el objeto Favorito a la colección del Bien,
 * - que también establece la relación inversa (Favorito->getBien()),
 * - y que removeFavorito() revierte correctamente ambos efectos.
 */
class BienFavoritoRelationTest extends TestCase
{
    public function testAddFavoritoEstableceRelacionBidireccional(): void
    {
        // 1) Preparo los objetos del dominio
        //    Creo un Bien (propiedad) y un Favorito vacío.
        $bien = new Bien();
        $favorito = new Favorito();

        // 2) Marco el bien como favorito
        //    (en mi dominio, esto lo modela el método addFavorito del Bien).
        $bien->addFavorito($favorito);

        // 3) Afirmo lo que espero que suceda:
        //    a) El Favorito quedó dentro de la colección del Bien.
        $this->assertTrue($bien->getFavoritos()->contains($favorito), 
            "El Bien debería contener el Favorito tras addFavorito().");

        //    b) La relación inversa también se estableció:
        //       el Favorito sabe qué Bien tiene asociado.
        $this->assertSame($bien, $favorito->getBien(), 
            "El Favorito debería apuntar al mismo Bien tras addFavorito().");
    }

    public function testRemoveFavoritoRompeLaRelacion(): void
    {
        // 1) Preparo un Bien con un Favorito ya agregado
        $bien = new Bien();
        $favorito = new Favorito();
        $bien->addFavorito($favorito);

        // 2) Desmarco el favorito
        $bien->removeFavorito($favorito);

        // 3) Verifico que se eliminó de la colección
        $this->assertFalse($bien->getFavoritos()->contains($favorito),
            "El Favorito no debería permanecer en la colección tras removeFavorito().");

        // 4) Verifico que también se limpió la relación inversa
        $this->assertNull($favorito->getBien(),
            "El Favorito no debería quedar apuntando al Bien tras removeFavorito().");
    }
}
