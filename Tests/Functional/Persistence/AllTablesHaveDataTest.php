<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Tests\Functional\Persistence;

class AllTablesHaveDataTest extends \F3\FLOW3\Tests\FunctionalTestCase {

    /**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;
    
    /**
     * @inject
	 * @var \F3\Sifpe\Domain\Repository\CuentaRepository
	 */
	protected $cuentaRepository;

    /**
     * @inject
	 * @var \F3\Sifpe\Domain\Repository\EmpresaRepository
	 */
	protected $empresaRepository;

    /**
     * @inject
     * @var \F3\Sifpe\Domain\Repository\GastoRepository
     */
    protected $gastoRepository;

    /**
     * @inject
     * @var \F3\Sifpe\Domain\Repository\IngresoRepository
     */
    protected $ingresoRepository;

    /**
     * @inject
	 * @var \F3\Sifpe\Domain\Repository\GrupoCuentaRepository
	 */
	protected $grupocuentaRepository;


    public function setUp() {
		parent::setUp();
        $configurationManager = $this->objectManager->get('F3\FLOW3\Configuration\ConfigurationManager');
		$settingsConfiguration = $configurationManager->getConfiguration(\F3\FLOW3\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS);
        $all_query = file_get_contents('/tmp/sifpe3.sql');
        $con = mysql_connect($settingsConfiguration['FLOW3']['persistence']['backendOptions']['host'],$settingsConfiguration['FLOW3']['persistence']['backendOptions']['user']);
        mysql_select_db($settingsConfiguration['FLOW3']['persistence']['backendOptions']['dbname'],$con);
        mysql_query($all_query,$con);
        mysql_close($con);
        
		$this->cuentaRepository = new \F3\Sifpe\Domain\Repository\CuentaRepository();
        $this->empresaRepository = new \F3\Sifpe\Domain\Repository\EmpresaRepository();
        $this->grupocuentaRepository = new \F3\Sifpe\Domain\Repository\GrupoCuentaRepository();
        $this->ingresoRepository = new \F3\Sifpe\Domain\Repository\IngresoRepository();
        $this->gastoRepository = new \F3\Sifpe\Domain\Repository\GastoRepository();
	}
    /**
     * @test
     * @return void
     */
    public function cuentaHasData() {
        $items = $this->cuentaRepository->findAll();
        var_dump($items->count());
        $this->assertGreaterThan(0,$items->count());
    }

    /**
     * @test
     * @return void
     */
    public function gastoHasData() {
        $items = $this->gastoRepository->findAll();
        $this->assertGreaterThan(0,$items->count());
    }

    /**
     * @test
     * @return void
     */
    public function ingresoHasData() {
        $items = $this->ingresoRepository->findAll();
        $this->assertGreaterThan(0,$items->count());
    }

    /**
     * @test
     * @return void
     */
    public function empresaHasData() {
        $items = $this->empresaRepository->findAll();
        $this->assertGreaterThan(0,$items->count());
    }

    /**
     * @test
     * @return void
     */
    public function grupoCuentaHasData() {
        $items = $this->grupocuentaRepository->findAll();
        $this->assertGreaterThan(0,$items->count());
    }


    public function tearDown() {

    }

}