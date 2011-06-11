<?php
declare(ENCODING = 'utf-8');
namespace F3\Sifpe\Domain\Repository;
 
/**
 * ApunteRepository
 *
 * 
 *
 */
  
class ApunteRepository extends \F3\FLOW3\Persistence\Repository {

    /**
     * Devuelve los Apuntes de un mes ya sea el mes actual (por defecto) o X meses atras desde este mes
     * Se tienen en cuenta los meses enteros de mes a mes y no de dia a dia,
     * Si hoy es 14-06-2011 1 mes atras devolveria los apuntes de 1 al 31 de Mayo
     * 
     * @param int $mesesAtras Numero de meses atras para los que devolver el listado
     * @return \Doctrine_Collection
     */
    public function findApuntesDelMes($mesesAtras = 0) {
        $mesesAtras = $mesesAtras + 0;
        $fechaInicial = new \DateTime("first day of $mesesAtras month ago");
        $fechaFinal = new \DateTime("last day of $mesesAtras month ago");

        $query = $this->createQuery();
        $query = $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('fecha',$fechaInicial),
                $query->lessThanOrEqual('fecha',$fechaFinal)
            )
        );

        return $query->execute();
    }

    /**
     * Devuelve un entero que representa el numero de meses que hay registrados en la base de datos desde hoy
     *
     * @return int
     */
    public function getTotalMesesRegistrados() {
        $query = $this->createQuery();
        $primerApunte = $query->setOrderings(array('fecha' => \F3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING ))->setLimit(1)->execute()->getFirst();
        $ultimoApunte = $query->setOrderings(array('fecha' => \F3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING ))->setLimit(1)->execute()->getFirst();
        $dateInterval = $primerApunte->getFecha()->diff($ultimoApunte->getFecha());
        $mesesRegistrados = ($dateInterval->y * 12) + $dateInterval->m;

        return $mesesRegistrados;
    }
}