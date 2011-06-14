<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Domain\Model;

/**
 * Una empresa
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @scope prototype
 * @Table(name="empresa")
 * @entity
 */
class Empresa implements \F3\Sifpe\Domain\EntityInterface {
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
     * @var \Doctrine\Common\Collections\ArrayCollection<\F3\Sifpe\Domain\Model\Gasto>
     * @OneToMany(mappedBy="empresa", cascade={"all"})
     */
    protected $gastos;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection<\F3\Sifpe\Domain\Model\Ingreso>
     * @OneToMany(mappedBy="empresa", cascade={"all"})
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


    public function getGastos()
    {
        return $this->gastos;
    }


    public function getIngresos()
    {
        return $this->ingresos;
    }

}