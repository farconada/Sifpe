<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;
require_once 'Zend/Search/Lucene.php';

/**
 * @scope singleton
 */
class LuceneIndexedSearchService implements IndexSearchInterface{
    protected $settings;

    protected $index;

    /**
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings)
    {
        $this->settings = $settings;
    }

    public function initializeObject(){
        if (!isset($this->settings['luceneDir']) || !is_readable($this->settings['luceneDir'])) {
            throw new \F3\FLOW3\Error\Exception('Falta la configuracion del directorio de Lucene o el directorio no se puede leer', 1309201437);
        }
        try {
            $this->index = \Zend_Search_Lucene::open($this->settings['luceneDir']);
        } catch (\Zend_Search_Lucene_Exception $ex) {
            $this->index = \Zend_Search_Lucene::create($this->settings['luceneDir']);
        }
    }

    public function find($queryString)
    {
        $hits = $this->index->find($queryString);
        foreach ($hits as $hit) {
            $this->index->delete($hit->id);
        }
    }

    private function updateApunteIndex($apunte)
    {
        $this->deleteApunteIndex($apunte);

        $doc = new \Zend_Search_Lucene_Document();
        $doc->addField(\Zend_Search_Lucene_Field::Keyword('id', $apunte->getId()));
        $doc->addField(\Zend_Search_Lucene_Field::Keyword('class', get_class($apunte)));
        $doc->addField(\Zend_Search_Lucene_Field::Text('empresa', $apunte->getEmpresa()->getName()));
        $doc->addField(\Zend_Search_Lucene_Field::Text('cuenta', $apunte->getCuenta()->getName()));
        $doc->addField(\Zend_Search_Lucene_Field::UnStored('notas', $apunte->getNotas()));
        $doc->addField(\Zend_Search_Lucene_Field::UnIndexed('cantidad', $apunte->getCantidad()));
        $doc->addField(\Zend_Search_Lucene_Field::UnIndexed('fecha', $apunte->getFecha()->format('Ymd')));
        $this->index->addDocument($doc);
    }

    private function deleteApunteIndex(\F3\Sifpe\Domain\Model\Apunte $apunte)
    {
        $hits = $this->index->find('id:' . $apunte->getId() . ' AND class:' . str_replace('\\','\\\\',get_class($apunte)));
        foreach ($hits as $hit) {
            $this->index->delete($hit->id);
        }
    }


}