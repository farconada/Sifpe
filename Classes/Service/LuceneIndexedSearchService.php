<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;
require_once 'Zend/Search/Lucene.php';

/**
 * @scope singleton
 */
class LuceneIndexedSearchService implements IndexSearchInterface, IndexManagerInterface
{
    protected $settings;

    protected $index;

    public function indexCollection($objects)
    {
        if(count($objects)) {
            $this->deleteByKeyword('class',str_replace('\\', '\\\\', get_class($objects[0])));
        }
        foreach ($objects as $object) {
            if ($object instanceof \F3\Sifpe\Domain\Model\Apunte) {
                $this->addApunteIndex($object);
            }
        }
        $this->index->optimize();
    }

    public function deleteCollection($objects)
    {
        foreach ($objects as $object) {
            if ($object instanceof \F3\Sifpe\Domain\Model\Apunte) {
                $this->deleteApunteIndex($object);
            }
        }
        $this->index->optimize();
    }

    public function deleteByKeyword($keyword, $value)
    {
        $hits = array();
        $hits = $this->index->find($keyword . ':' . $value);
        foreach ($hits as $hit) {
            $this->index->delete($hit->id);
        }
        $this->index->optimize();

    }

    public function deleteAll()
    {
        // TODO: Implement deleteAll() method.
    }

    /**
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings)
    {
        $this->settings = $settings;
    }

    public function initializeObject()
    {
        \Zend_Search_Lucene_Analysis_Analyzer::setDefault(new \Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive());
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
        $queryString = \Zend_Search_Lucene_Search_QueryParser::parse($queryString,'utf-8');
        $hits = $this->index->find($queryString);
        return $hits;
    }

    public function updateApunteIndex($apunte)
    {
        $this->deleteApunteIndex($apunte);
        $this->addApunteIndex($apunte);
    }

    public function addApunteIndex($apunte){
        $doc = new \Zend_Search_Lucene_Document();
        $doc->addField(\Zend_Search_Lucene_Field::Keyword('objId', $apunte->getId()));
        $doc->addField(\Zend_Search_Lucene_Field::Keyword('class', get_class($apunte),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::Text('empresa', $apunte->getEmpresa()->getName(),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::Text('cuenta', $apunte->getCuenta()->getName(),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::UnStored('notas', $apunte->getNotas(),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::UnIndexed('cantidad', $apunte->getCantidad()));
        $doc->addField(\Zend_Search_Lucene_Field::UnIndexed('fecha', $apunte->getFecha()->format('Ymd')));
        $this->index->addDocument($doc);
        $this->index->commit();
    }

    public function deleteApunteIndex(\F3\Sifpe\Domain\Model\Apunte $apunte)
    {
        $hits = array();
        $hits = $this->index->find('objId:' . $apunte->getId() . ' AND class:' . str_replace('\\', '\\\\', get_class($apunte)));
        foreach ($hits as $hit) {
            $this->index->delete($hit->id);
        }
        $this->index->commit();
    }


}