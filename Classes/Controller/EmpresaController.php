<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Controller;

/**
 * Clase responsable de la gestion de Empresas
 *
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 */
class EmpresaController extends AbstractController {

    /**
	 * @inject
	 * @var \F3\Sifpe\Domain\Repository\EmpresaRepository
	 */
	protected $entityRepository;

    /**
     * Establece los TypeConverter necesarios para que los parametros pasados como JSON sean convertidos a
     * objetos de tipo Domain\Model\Empresa
     *
     * @return void
     */
    public function initializeAction() {
        if (isset($this->arguments['entity'])) {
            $this->arguments->getArgument('entity')
                    ->getPropertyMappingConfiguration()
                    ->setTypeConverter(new \F3\Sifpe\TypeConverters\JsonToEntityConverter('\F3\Sifpe\Domain\Model\Empresa'));
        }
        parent::initializeAction();
    }

    public function listAction() {
        parent::listAction();
        $this->setCacheHeaders();
    }
}
 