<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

interface IndexedSearchInterface {
    public function find($queryString);
}