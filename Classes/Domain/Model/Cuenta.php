<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Domain\Model;

/**
 * Una cuenta
 *
 * @scope prototype
 * @Table(name="cuenta")
 * @entity
 * @author Fernando Arconada
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @package Sifpe
 */
class Cuenta implements \F3\Sifpe\Domain\EntityInterface {
    /**
	 * id
	 *
	 * @var integer
     * @Id
	 * @Column(name="id", type="integer")
     * @GeneratedValue
	 */
	protected $id = '';

    /**
	 * nombre
	 *
	 * @var string
	 * @validate Text, StringLength(minimum = 1, maximum = 80)
	 * @Column(length="80")
	 */
	protected $name = '';

    /**
	 * @var \F3\Sifpe\Domain\Model\GrupoCuenta
	 * @ManyToOne(inversedBy="cuentas")
     * @JoinColumn(name="grupo_cuenta_id", referencedColumnName="id")
	 */
	protected $grupo;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection<\F3\Sifpe\Domain\Model\Gasto>
     * @OneToMany(mappedBy="cuenta", cascade={"all"})
     */
    protected $gastos;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection<\F3\Sifpe\Domain\Model\Ingreso>
     * @OneToMany(mappedBy="cuenta", cascade={"all"})
     */
    protected $ingresos;

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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \F3\Sifpe\Domain\Model\GrupoCuenta $grupo
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * @return \F3\Sifpe\Domain\Model\GrupoCuenta
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    public function getGastos()
    {
        return $this->gastos;
    }


    public function getIngresos()
    {
        return $this->ingresos;
    }
    
    /**
     * Devuelve la Cuenta como un array asociativo no jerarquico,
     * es decir mostrando el grupo ID en vez de otro array
     * @return array
     */
    public function toArray(){
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'grupo' => $this->getGrupo()->getId()
        );
    }

}