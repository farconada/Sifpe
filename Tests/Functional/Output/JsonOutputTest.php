<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Tests\Functional\Output;

class JsonOutputTest extends \F3\FLOW3\Tests\FunctionalTestCase {
    /**
     * @test
     * @return void
     */
    public function cuentasList() {
        $output = $this->sendWebRequest('Cuenta','Sifpe','list',array(),'json');
        $decoded = json_decode($output,TRUE);
        $this->assertTrue($decoded);
    }

    /**
     * Para evitar que se borre la BD al terminar
     * @static
     * @return void
     */
    static public function tearDownAfterClass() {

    }
}
