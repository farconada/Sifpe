<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;
 
/*
 * UtilController
 *
 * Gestion de Backups
 */
class UtilController extends \F3\FLOW3\MVC\Controller\ActionController {

    /**
     * Variable que tiene un objeto que implementa una interface de clase que sirve para gestionar los backups de la BD
     *
     * Mediante DI se inserta un una clase concreta en Objects.yaml
     * Usado para generar dumps de MySQL pero reemplazable para otras BD con usando DI
     *
     * @var /F3/Sifpe/Service/BackupServiceInterface
     * @inject
     */
    protected $backupService;

    /**
     * Variable que tiene un objeto que implementa una interface de clase que sirve para gestionar objetos indexados
     *
     * @var \F3\Sifpe\Service\IndexManagerInterface
     * @inject
     */
    protected $indexManager;

    /**
     * Pantalla principal con el menu de opciones y listado de backups actuales
     *
     * Las opciones del menu se indican en la vista en el controlador solo se calculan los backups disponibles
     * los nombre de los ficheros de backups deben tener el formato backup-*
     *
     * @return void
     */
    public function indexAction() {
        if (!isset($this->settings['backupDir']) || !is_writable($this->settings['backupDir'])) {
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

    /**
     * Indexa todos los objetos del tipo especificado
     *
     * @param string $objectModel especifica el nombre la clase a indexar
     * @return void
     */
    public function indexAllObjectsAction($objectModel){
        $query = $this->persistenceManager->createQueryForType($objectModel);
        $objects = $query->execute();
        $this->indexManager->indexCollection($objects);
    }

    /**
     * Hace un backup de la BD
     *
     * @return void
     */
    public function doBackupAction(){
        $this->backupService->setDestinationDir($this->settings['backupDir']);
        $this->backupService->execBackup();
    }

    /**
     * Es un metodo que se dispara cada vez que se elimina un objeto y hace un backup de la BD antes de eleminar dicho objeto
     *
     * Se dispara mediente signal/slot y se especifica los disparadores en Package.php
     *
     * @param \F3\Sifpe\Domain\EntityInterface $entity el objeto a eliminar
     * @return void
     */
    public function slotRecordPreDeleted(\F3\Sifpe\Domain\EntityInterface $entity) {
        $this->backupService->setDestinationDir($this->settings['backupDir']);
        $this->backupService->execBackup();
    }

 
}
