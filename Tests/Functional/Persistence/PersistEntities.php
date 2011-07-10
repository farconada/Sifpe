<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Tests\Functional\Persistence;
/**
 * Testcase para guardar y borrar elementos
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 */
class PersistEntities extends \F3\Sifpe\Tests\Functional\AbstractFunctionalTestCase
{
    /**
     *
     * @var \F3\Sifpe\TypeConverters\JsonToEntityConverter
     */
    protected $typeConverter;

    /**
     * @inject
     * @var \F3\Sifpe\Domain\Repository\EmpresaRepository
     */
    protected $empresaRepository;

    /**
     * @var boolean
     */
    static protected $testablePersistenceEnabled = TRUE;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->empresaRepository = new \F3\Sifpe\Domain\Repository\EmpresaRepository();
        $this->grupocuentaRepository = new \F3\Sifpe\Domain\Repository\GrupoCuentaRepository();
        $this->typeConverter = $this->objectManager->get('\F3\Sifpe\TypeConverters\JsonToEntityConverter');
    }


    /**
     * @test
     * @dataProvider listEmpresasJson
     * @param  $jsonParam
     * @return void
     */
    public function deleteEmpresa($jsonParam) {
        $this->deleteEntityFromJson($this->empresaRepository,'Empresa',$jsonParam);
    }

    /**
     * @test
     * @dataProvider listGruposDeCuentasJson
     * @param  $jsonParam
     * @return void
     */
    public function deleteGruposDeCuentas($jsonParam) {
        $this->deleteEntityFromJson($this->grupocuentaRepository,'GrupoCuenta',$jsonParam);
    }

    private function deleteEntityFromJson($repository,$type,$jsonParam) {
        $convertedObject = $this->typeConverter->convertFrom($jsonParam, '\F3\Sifpe\Domain\Model\\' . $type);
        $elementosAntes = $repository->findAll()->count();
        $output = $this->sendWebRequest($type,'Sifpe','delete',array('entity' =>$convertedObject),'json');
        $elementosDespues = $repository->findAll()->count();
        $this->assertEquals($elementosAntes-1,$elementosDespues);
    }


}