<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Tests\Functional\Property;
/**
 * Testcase for Property Mapper
 * @author Fernando Arconada fernando.arconada@gmail.com
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class JsonProperty extends \F3\Sifpe\Tests\Functional\AbstractFunctionalTestCase
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
        $this->typeConverter = $this->objectManager->get('\F3\Sifpe\TypeConverters\JsonToEntityConverter');
    }

    /**
     * @test
     * @dataProvider empresasJson
     * @return void
     */
    public function convertToEmpresa($jsonParam)
    {
        $convertedObject = $this->typeConverter->convertFrom($jsonParam, '\F3\Sifpe\Domain\Model\Empresa');
        $this->assertTrue(is_object($convertedObject));
    }

    /**
     * @test
     * @dataProvider empresasJson
     * @param  $jsonParam
     * @return void
     */
    public function deleteAllEmpresas($jsonParam) {
        $convertedObject = $this->typeConverter->convertFrom($jsonParam, '\F3\Sifpe\Domain\Model\Empresa');
        $empresasAntes = $this->empresaRepository->findAll()->count();
        $output = $this->sendWebRequest('Empresa','Sifpe','delete',array('entity' =>$convertedObject),'json');
        $empresasDespues = $this->empresaRepository->findAll()->count();
        $this->assertEquals($empresasAntes-1,$empresasDespues);
    }

    public function empresasJson()
    {
        $empresasJsonArray = array();
        $empresaRespository = new \F3\Sifpe\Domain\Repository\EmpresaRepository();
        $empresas = $empresaRespository->findAll();
        foreach ($empresas as $empresa) {
            $empresasJsonArray[] = array(json_encode(array(
                                                          'id' => $empresa->getId(),
                                                          'name' => $empresa->getName()
                                                     ))
            );
        }

        return $empresasJsonArray;
    }
}