<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Domain;

/**
 * Esta interfaz es comun para todos los objetos del Modelo y especifica que deben tener un metodo getId
 * Esto es necesario porque no se usan los identificadores por defecto de FLOW3 ya que es una BD heredada
 */
interface EntityInterface {
    public function getId();
}


