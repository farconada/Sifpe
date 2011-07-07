<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Domain\Model;
/**
 * Apunte
 *
 * Un apunte gasto o ingreso
 * Los gastos y los ingresos son iguales, apuntes, que se gestionan en dos tablas separadas, no es necesario repetir el
 * mapeo para Doctrine por separado si no que se hace en la clase apunte y luego la heredaran los Gastos e Ingresos
 */
 
/**
* @MappedSuperclass
* @HasLifecycleCallbacks
* @entity
*/
class Apunte implements \F3\Sifpe\Domain\EntityInterface {
 
    /**
     * @identity
     * @var integer
     * @Id
     * @GeneratedValue
     */
    protected $id;


    /**
     * fecha
     * @var \DateTime
     * @validate \DateTime
     * @Column(type="date")
     */
    protected $fecha;

    /**
	 * notas
	 *
	 * @var string
	 * @validate Text, StringLength(minimum = 1, maximum = 80)
	 * @Column(type="text", length="80")
	 */
    protected $notas;

    /**
     * @var float
     * @validate decimal
     * @Column(type="decimal", precision=10, scale=2)
     */
    protected $cantidad;


    /**
     * @param float $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param \F3\Sifpe\Domain\Model\Cuenta $cuenta
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;
    }

    /**
     * @return \F3\Sifpe\Domain\Model\Cuenta
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * @param \F3\Sifpe\Domain\Model\Empresa $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return \F3\Sifpe\Domain\Model\Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param \F3\Sifpe\Domain\Model\DateTime $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return \F3\Sifpe\Domain\Model\DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $notas
     */
    public function setNotas($notas)
    {
        $this->notas = $notas;
    }

    /**
     * @return string
     */
    public function getNotas()
    {
        return $this->notas;
    }

    /**
     * Devuelve el Apunte como un array asociativo no jerarquico,
     * es decir mostrando el las relaciones como ID en vez de otro array
     * @return array
     */
    public function toArray(){
        return array(
            'id' => $this->getId(),
            'fecha' => $this->getFecha()->format('Y-m-d') ,
            'notas' => $this->getNotas(),
            'empresa' => $this->getEmpresa()->getId(),
            'cuenta' => $this->getCuenta()->getId(),
            'cantidad' => $this->getCantidad()
        );
    }
}