<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoFormulario
 *
 * @ORM\Table(name="ESTADO_FORMULARIO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\EstadoFormularioRepository")
 */
class EstadoFormulario
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ESTADO_FORMULARIO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idEstadoFormulario;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_ESTADO_FORMULARIO", type="string", length=2)
     */
    private $codEstadoFormulario;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_ESTADO_FORMULARIO", type="string", length=100)
     */
    private $nbrEstadoFormulario;

    /**
     * @ORM\OneToMany(targetEntity="FormularioPorCentroEducativo",mappedBy="idEstadoFormulario")
     */
    private $formulariosPorCentroEducativo;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formulariosPorCentroEducativo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idEstadoFormulario
     *
     * @return integer 
     */
    public function getIdEstadoFormulario()
    {
        return $this->idEstadoFormulario;
    }

    /**
     * Set codEstadoFormulario
     *
     * @param string $codEstadoFormulario
     * @return EstadoFormulario
     */
    public function setCodEstadoFormulario($codEstadoFormulario)
    {
        $this->codEstadoFormulario = $codEstadoFormulario;

        return $this;
    }

    /**
     * Get codEstadoFormulario
     *
     * @return string 
     */
    public function getCodEstadoFormulario()
    {
        return $this->codEstadoFormulario;
    }

    /**
     * Set nbrEstadoFormulario
     *
     * @param string $nbrEstadoFormulario
     * @return EstadoFormulario
     */
    public function setNbrEstadoFormulario($nbrEstadoFormulario)
    {
        $this->nbrEstadoFormulario = $nbrEstadoFormulario;

        return $this;
    }

    /**
     * Get nbrEstadoFormulario
     *
     * @return string 
     */
    public function getNbrEstadoFormulario()
    {
        return $this->nbrEstadoFormulario;
    }

    /**
     * Add formulariosPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativo
     * @return EstadoFormulario
     */
    public function addFormulariosPorCentroEducativo(\AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativo)
    {
        $this->formulariosPorCentroEducativo[] = $formulariosPorCentroEducativo;

        return $this;
    }

    /**
     * Remove formulariosPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativo
     */
    public function removeFormulariosPorCentroEducativo(\AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativo)
    {
        $this->formulariosPorCentroEducativo->removeElement($formulariosPorCentroEducativo);
    }

    /**
     * Get formulariosPorCentroEducativo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormulariosPorCentroEducativo()
    {
        return $this->formulariosPorCentroEducativo;
    }
}
