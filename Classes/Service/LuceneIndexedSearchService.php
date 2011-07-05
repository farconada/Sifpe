<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;
require_once 'Zend/Search/Lucene.php';

/**
 * Clase que se encarga de indexar objetos de tipo apunte en un indice Lucene
 *
 * Necesita Zend Framework en el include path
 *
 * @link http://framework.zend.com/manual/en/zend.search.lucene.html
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 * @scope singleton
 */
class LuceneIndexedSearchService implements IndexSearchInterface, IndexManagerInterface
{
    /**
     * @var array con los setting de Settings.yaml bajo la key Sifpe
     */
    protected $settings;

    /**
     * Objeto que gestiona el indice Lucene
     *
     * @var object Zend_Search_Lucene
     */
    protected $index;

    /**
     * Indexa un array de objeto de tipo entidad
     *
     * @param array $objects array de objetos a indexar
     * @return void
     */
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
        $this->index->commit();
        $this->index->optimize();
    }

    /**
     * Borrar una coleccion de objeto de tipo entidad
     *
     * @param array $objects borra del indice todos los objetos del array
     * @return void
     */
    public function deleteCollection($objects)
    {
        foreach ($objects as $object) {
            if ($object instanceof \F3\Sifpe\Domain\Model\Apunte) {
                $this->deleteApunteIndex($object);
            }
        }
        $this->index->commit();
        $this->index->optimize();
    }

    /**
     * Borra del indice todos los objetos que cumplen con el criterio keyword=valor
     *
     * @param string $keyword
     * @param string $value
     * @return void
     */
    public function deleteByKeyword($keyword, $value)
    {
        $hits = array();
        $hits = $this->index->find($keyword . ':' . $value);
        foreach ($hits as $hit) {
            $this->index->delete($hit->id);
        }
        $this->index->commit();
        $this->index->optimize();

    }

    /**
     * Borra el indice completo
     *
     * @return void
     */
    public function deleteAll()
    {
        // TODO: Implement deleteAll() method.
    }

    /**
     * Injecto el objeto de settings
     *
     * @link http://flow3.typo3.org/documentation/manuals/flow3/flow3.objectframework/#id36285025
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Inicializa el objeto y crea o abre el indice de Lucene
     *
     * @link http://flow3.typo3.org/documentation/manuals/flow3/flow3.objectframework/#id36283856
     * @throws \F3\FLOW3\Error\Exception
     * @return void
     */
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

    /**
     * Finaliza el objeto y hace un commit del indice para evitar tenerlo que hacer en cada update o delete
     *
     * @link http://flow3.typo3.org/documentation/manuals/flow3/flow3.objectframework/#id36283856
     * @return void
     */
    public function shutdownObject() {
		$this->index->commit();
	}


    /**
     * Busca un objeto en el indice y devuelve una coleccion de hits que cumplen con el criterio de busqueda
     *
     * @param string $query query string
     * @return array
     */
    public function find($queryString)
    {
        $queryString = \Zend_Search_Lucene_Search_QueryParser::parse($queryString,'utf-8');
        $hits = $this->index->find($queryString);
        return $hits;
    }

    /**
     * Actualiza el indice de un Apunte
     *
     * @param \F3\Sifpe\Domain\Model\Apunte $apunte
     * @return void
     */
    public function updateApunteIndex(\F3\Sifpe\Domain\Model\Apunte $apunte)
    {
        $this->deleteApunteIndex($apunte);
        $this->addApunteIndex($apunte);
    }

    /**
     * Indexa un Apunte
     *
     * Todos los campos se guardan UTF-8
     * El objId es el ID del objeto para poderlo recuperar de la capa de persistencia buscando por ese ID
     * El class permite distinguir el tipo de apunte Gasto/Ingreso
     *
     * @param \F3\Sifpe\Domain\Model\Apunte $apunte
     * @return void
     */
    public function addApunteIndex(\F3\Sifpe\Domain\Model\Apunte $apunte){
        $doc = new \Zend_Search_Lucene_Document();
        $doc->addField(\Zend_Search_Lucene_Field::Keyword('objId', $apunte->getId(),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::Keyword('class', get_class($apunte),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::Text('empresa', $apunte->getEmpresa()->getName(),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::Text('cuenta', $apunte->getCuenta()->getName(),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::UnStored('notas', $apunte->getNotas(),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::UnStored('cantidad', $apunte->getCantidad(),'utf-8'));
        $doc->addField(\Zend_Search_Lucene_Field::UnStored('fecha', $apunte->getFecha()->format('Ymd'),'utf-8'));
        $this->index->addDocument($doc);
    }

    /**
     * Borra un apunte del indice
     *
     * @param \F3\Sifpe\Domain\Model\Apunte $apunte
     * @return void
     */
    public function deleteApunteIndex(\F3\Sifpe\Domain\Model\Apunte $apunte)
    {
        $hits = array();
        $hits = $this->index->find('objId:' . $apunte->getId() . ' AND class:' . str_replace('\\', '\\\\', get_class($apunte)));
        foreach ($hits as $hit) {
            $this->index->delete($hit->id);
        }
    }


}