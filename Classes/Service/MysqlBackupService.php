<?php
declare(ENCODING = 'utf-8') ;
namespace F3\Sifpe\Service;

class MysqlBackupService implements BackupServiceInterface {
    protected $destinationDir = '';
    /**
	 * @var \F3\FLOW3\Object\ObjectManagerInterface
     * @inject
	 */
	protected $objectManager;
    
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

    public function setDestinationDir($destinationDir)
    {
        $this->destinationDir = $destinationDir;
    }

    public function getDestinationDir()
    {
        return $this->destinationDir;
    }

}