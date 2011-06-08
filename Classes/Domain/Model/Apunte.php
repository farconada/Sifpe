<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Domain\Model;
/**
 * Apunte
 *
 * un apunte gasto o ingreso
 */
 
/**
* @MappedSuperclass
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
	 * @var \F3\Sifpe\Domain\Model\Empresa
	 * @ManyToOne
     * @JoinColumn(name="empresa_id", referencedColumnName="id")
	 */
    protected $empresa;

    /**
	 * @var \F3\Sifpe\Domain\Model\Cuenta
	 * @ManyToOne
     * @JoinColumn(name="cuenta_id", referencedColumnName="id")
	 */
    protected $cuenta;

    /**
     * @var float
     * @validate decimal
     * @Column(type="decimal")
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
}