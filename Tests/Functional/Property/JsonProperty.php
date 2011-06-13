<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Tests\Functional\Property;
/**
 * Testcase for Property Mapper
 * @author Fernando Arconada fernando.arconada@gmail.com
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class JsonProperty extends \F3\Sifpe\Tests\Functional\AbstractFunctionalTestCase {
    /**
	 *
	 * @var \F3\Sifpe\TypeConverters\JsonToEntityConverter
	 */
	protected $typeConverter;

    /**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->typeConverter = $this->objectManager->get('\F3\Sifpe\TypeConverters\JsonToEntityConverter');
	}

    /**
     * @test
     * @dataProvider empresasJson
     * @return void
     */
    public function convertToEmpresa($jsonParam) {
       $convertedObject = $this->typeConverter->convertFrom($jsonParam,'\F3\Sifpe\Domain\Model\Empresa');
       $this->assertTrue(is_object($convertedObject));
    }

    public function empresasJson() {
        $empresasJsonArray = array();
        $empresasJsonArray[] = array(json_encode(array(
                                              'id'      => 1,
                                              'name'    => 'Fernando'
                                           ))
        );
        return $empresasJsonArray;
    }
}