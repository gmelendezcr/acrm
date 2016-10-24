<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SeccionPorFormularioPorCentroEducativo
 *
 * @ORM\Table(name="SECCION_POR_FORMULARIO_POR_CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\SeccionPorFormularioPorCentroEducativoRepository")
 */
class SeccionPorFormularioPorCentroEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_SECCION_POR_FORMULARIO_POR_CENTROEDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idSeccionPorFormularioPorCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="FormularioPorCentroEducativo", inversedBy="seccionesPorFormularioPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_FORMULARIO_POR_CENTRO_EDUCATIVO",referencedColumnName="ID_FORMULARIO_POR_CENTRO_EDUCATIVO")
     */
    private $idFormularioPorCentroEducativo;
     
     /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Seccion", inversedBy="seccionesPorFormularioPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_SECCION",referencedColumnName="ID_SECCION")
     */
    private $idSeccion;

    /**
     * @var string
     *
     * @ORM\Column(name="OBSERVACION", type="string", length=5000)
     */
    private $observacion;


    /**
     * Get idSeccionPorFormularioPorCentroEducativo
     *
     * @return int
     */
    public function getIdSeccionPorFormularioPorCentroEducativo()
    {
        return $this->idSeccionPorFormularioPorCentroEducativo;
    }

    /**
     * Set idFormularioPorCentroEducativo
     *
     * @param  \AcreditacionBundle\Entity\FormularioPorCentroEducativo $idFormularioPorCentroEducativo
     * @return SeccionPorFormularioPorCentroEducativo
     */
    public function setIdFormularioPorCentroEducativo($idFormularioPorCentroEducativo)
    {
        $this->idFormularioPorCentroEducativo = $idFormularioPorCentroEducativo;

        return $this;
    }

    /**
     * Get idFormularioPorCentroEducativo
     *
     * @return \AcreditacionBundle\Entity\FormularioPorCentroEducativo
     */
    public function getIdFormularioPorCentroEducativo()
    {
        return $this->idFormularioPorCentroEducativo;
    }

    /**
     * Set idSeccion
     *
     * @param  \AcreditacionBundle\Entity\Seccion $idSeccion
     * @return SeccionPorFormularioPorCentroEducativo
     */
    public function setIdSeccion(\AcreditacionBundle\Entity\Seccion $idSeccion)
    {
        $this->idSeccion = $idSeccion;

        return $this;
    }

    /**
     * Get idSeccion
     *
     * @return \AcreditacionBundle\Entity\Seccion
     */
    public function getIdSeccion()
    {
        return $this->idSeccion;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return SeccionPorFormularioPorCentroEducativo
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    public function vaciarPropiedades()
    {
        $detalle='';
        $detalle.='centro educativo: ' . $this->getIdFormularioPorCentroEducativo()->getIdCentroEducativo()->__toString() . "\n";
        $detalle.='formulario: ' . $this->getIdFormularioPorCentroEducativo()->getIdFormulario()->__toString() . "\n";
        $detalle.='criterio: ' . $this->getIdSeccion()->__toString() . "\n";
        $detalle.='observación: ' . $this->getObservacion() . "\n";
        return $detalle;
    }
}
