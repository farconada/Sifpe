<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;
 
/*
 * BackupController
 *
 * Gestion de Backups
 */
class BackupController extends \F3\FLOW3\MVC\Controller\ActionController {
 
    /**
     * indexAction
     * @return void
     */
    public function indexAction() {
        if (!isset($this->settings['backupDir']) || !is_readable($this->settings['backupDir'])) {
            throw new \F3\FLOW3\Error\Exception('Falta la configuracion del directorio de backup o el directorio no se puede leer',1309161900);
        }
        $iterator = new \DirectoryIterator($this->settings['backupDir']);
        $files = array();
        $i = 0;
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && preg_match('/backup-.*/',$fileinfo->getFilename())) {
                $files[$i]['filename'] = $fileinfo->getFilename();
                $files[$i]['ctime'] = $fileinfo->getCTime();
                $i++;
            }
        }
        $this->view->assign('files',$files);
    }

    public function doBackupAction(){
        $this->execBackup();
    }

    public function slotRecordPreDeleted(\F3\Sifpe\Domain\EntityInterface $entity) {
        $this->execBackup();
    }
    
    private function execBackup() {
        //Solo es valido para MySQL
        $date = new \DateTime();
        $cmd = '/usr/bin/mysqldump -uroot flow3 >'.$this->settings['backupDir'].'backup-'.$date->format('Ymd').'.sql';
        system('echo "'.$cmd.'"| at now +1min');
    }
 
}