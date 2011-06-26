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
        //Solo es valido para MySQL
        $date = new \DateTime();
        $cmd = '/usr/bin/mysqldump -uroot flow3 >'.$this->settings['backupDir'].'backup-'.$date->format('Ymd').'.sql';
        system('echo "'.$cmd.'"| at now +1min');
    }
 
}