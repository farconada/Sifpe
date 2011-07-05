<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

/**
 * Clase que se encarga de hacer backups de MySQL
 *
 * @link http://framework.zend.com/manual/en/zend.search.lucene.html
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 * @scope prototype
 */
class MysqlBackupService implements BackupServiceInterface {

    /**
     * @var string directorio donde guardar los backups
     */
    protected $destinationDir = '';
    /**
	 * @var \F3\FLOW3\Object\ObjectManagerInterface
     * @inject
	 */
	protected $objectManager;

    /**
     * Hace un backup
     *
     * @return void
     */
    public function execBackup()
    {
        $configurationManager = $this->objectManager->get('F3\FLOW3\Configuration\ConfigurationManager');
        $settings = $configurationManager->getConfiguration(\F3\FLOW3\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS);
        $dbname = $settings['FLOW3']['persistence']['backendOptions']['dbname'];
        $dbuser = $settings['FLOW3']['persistence']['backendOptions']['user'];
        $dbpassword = $settings['FLOW3']['persistence']['backendOptions']['password'];

        $date = new \DateTime();

        //Solo es valido para MySQL
        if ($settings['FLOW3']['persistence']['backendOptions']['driver']  == 'pdo_mysql') {
            $mysqldumpCmd = '/usr/bin/mysqldump -u'.$dbuser . ' ' . $dbname;
            if ($dbpassword) {
                $mysqldumpCmd = $mysqldumpCmd.' -p'.$dbpassword;
            }
            $cmd = $mysqldumpCmd . '>'.$this->getDestinationDir().'backup-'.$date->format('Ymd').'.sql';
        }

        system('echo "'.$cmd.'"| at now +1min');
    }

    /**
     * Establece el directorio en el que se almacenan los backups
     *
     * @param $destinationDir
     * @return void
     */
    public function setDestinationDir($destinationDir)
    {
        $this->destinationDir = $destinationDir;
    }

    /**
     * Devuelve el directorio en el que se guardan los backups
     *
     * @return string
     */
    public function getDestinationDir()
    {
        return $this->destinationDir;
    }

}