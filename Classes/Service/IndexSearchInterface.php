<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

interface IndexSearchInterface {
    public function find($query);
}