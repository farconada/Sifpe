<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Tests\Functional\Output;

class JsonOutputTest extends \F3\Sifpe\Tests\Functional\AbstractFunctionalTestCase {
    private $controllers = array('Cuenta', 'GrupoCuenta', 'Empresa','Gasto','Ingreso');

    /**
     * @test
     * @return void
     */
    public function listValidJson() {
        foreach($this->controllers as $controller) {
            $output = $this->sendWebRequest($controller,'Sifpe','list',array(),'json');
            $decoded = json_decode($output,TRUE);
            $this->assertTrue(is_array($decoded));
        }

    }

    public function successMsg(){

    }

}
