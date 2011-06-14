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
     * @dataProvider listEmpresasJson
     * @return void
     */
    public function convertToEmpresa($jsonParam)
    {
        $convertedObject = $this->typeConverter->convertFrom($jsonParam, '\F3\Sifpe\Domain\Model\Empresa');
        $this->assertEquals('F3\Sifpe\Domain\Model\Empresa',get_class($convertedObject));
    }

}