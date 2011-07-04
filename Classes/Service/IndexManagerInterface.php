<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

interface IndexManagerInterface {
    public function indexCollection($objects);
    public function deleteCollection($objects);
    public function deleteByKeyword($keyword,$value);
    public function deleteAll();
}