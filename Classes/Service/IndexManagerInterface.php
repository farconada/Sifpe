<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

/**
 * Interface que deden implementar las clases que gestionen objetos indexados
 *
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 */
interface IndexManagerInterface {
    /**
     * Indexa un array de objeto de tipo entidad
     *
     * @abstract
     * @param array $objects array de objetos a indexar
     * @return void
     */
    public function indexCollection($objects);

    /**
     * Borrar una coleccion de objeto de tipo entidad
     *
     * @abstract
     * @param array $objects borra del indice todos los objetos del array
     * @return void
     */
    public function deleteCollection($objects);

    /**
     * Borra del indice todos los objetos que cumplen con el criterio keyword=valor
     *
     * @abstract
     * @param string $keyword
     * @param string $value
     * @return void
     */
    public function deleteByKeyword($keyword,$value);

    /**
     * Borra el indice completo
     *
     * @abstract
     * @return void
     */
    public function deleteAll();
}