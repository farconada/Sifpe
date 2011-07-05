<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

/**
 * Interface que deben implementar las clases encargadas de hacer backups
 *
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 */
interface BackupServiceInterface {
    /**
     * Hace un backup
     *
     * @abstract
     * @return void
     */
    public function execBackup();

    /**
     * Establece el directorio en el que se almacenan los backups
     *
     * @abstract
     * @param $destinationDir
     * @return void
     */
    public function setDestinationDir($destinationDir);
}