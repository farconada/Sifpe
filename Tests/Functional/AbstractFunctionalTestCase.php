<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Tests\Functional;
/**
 * Clase abstracta para cargar fixtures en la BD
 * @author Fernando Arconada fernando.arconada@gmail.com
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class AbstractFunctionalTestCase extends \F3\FLOW3\Tests\FunctionalTestCase
{
    public function setUp()
    {
        parent::setUp();
        $configurationManager = $this->objectManager->get('F3\FLOW3\Configuration\ConfigurationManager');
        $settings = $configurationManager->getConfiguration(\F3\FLOW3\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS);

        $entityManagerFactory = $this->objectManager->get('\F3\FLOW3\Persistence\Doctrine\EntityManagerFactory');
        $entityManager = $entityManagerFactory->create();
        if (isset($settings['FLOW3']['persistence']['backendOptions']['fixtures'])) {
            $fixturesSqlFile = $settings['FLOW3']['persistence']['backendOptions']['fixtures'];
            if (is_readable($fixturesSqlFile)) {
                $sql = file_get_contents($fixturesSqlFile);
                $connection = $entityManager->getConnection();
                try {
                    $connection->exec($sql);
                } catch (\PDOException $e) {
                    echo  $e->getMessage() . PHP_EOL;
                    echo  $e->getTraceAsString() . PHP_EOL;
                }
            }
        }

    }

    static public function tearDownAfterClass()
    {
    }


}
