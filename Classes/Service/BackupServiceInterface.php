<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

interface BackupServiceInterface {
    public function execBackup();
    public function setDestinationDir($destinationDir);
}