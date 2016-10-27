<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formulario
 *
 * @ORM\Table(name="FORMULARIO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\FormularioRepository")
 */
class Formulario
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_FORMULARIO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idFormulario;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_FORMULARIO", type="string", length=20, unique=true)
     */
    private $codFormulario;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_FORMULARIO", type="string", length=100)
     */
    private $nbrFormulario;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION_FORMULARIO", type="string", length=1000, nullable=true)
     */
    private $descripcionFormulario;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="Seccion",mappedBy="idFormulario")
     */
    private $secciones;

    /**
     * @ORM\OneToMany(targetEntity="EstadoFormulario",mappedBy="idFormulario")
     */
    private $formulariosPorCentroEducativo;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->secciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formulariosPorCentroEducativo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idFormulario
     *
     * @return integer 
     */
    public function getIdFormulario()
    {
        return $this->idFormulario;
    }

    /**
     * Set codFormulario
     *
     * @param string $codFormulario
     * @return Formulario
     */
    public function setCodFormulario($codFormulario)
    {
        $this->codFormulario = $codFormulario;

        return $this;
    }

    /**
     * Get codFormulario
     *
     * @return string 
     */
    public function getCodFormulario()
    {
        return $this->codFormulario;
    }

    /**
     * Set nbrFormulario
     *
     * @param string $nbrFormulario
     * @return Formulario
     */
    public function setNbrFormulario($nbrFormulario)
    {
        $this->nbrFormulario = $nbrFormulario;

        return $this;
    }

    /**
     * Get nbrFormulario
     *
     * @return string 
     */
    public function getNbrFormulario()
    {
        return $this->nbrFormulario;
    }

    /**
     * Set descripcionFormulario
     *
     * @param string $descripcionFormulario
     * @return Formulario
     */
    public function setDescripcionFormulario($descripcionFormulario)
    {
        $this->descripcionFormulario = $descripcionFormulario;

        return $this;
    }

    /**
     * Get descripcionFormulario
     *
     * @return string 
     */
    public function getDescripcionFormulario()
    {
        return $this->descripcionFormulario;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return Formulario
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return string 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Add secciones
     *
     * @param \AcreditacionBundle\Entity\Seccion $secciones
     * @return Formulario
     */
    public function addSecciones(\AcreditacionBundle\Entity\Seccion $secciones)
    {
        $this->secciones[] = $secciones;

        return $this;
    }

    /**
     * Remove secciones
     *
     * @param \AcreditacionBundle\Entity\Seccion $secciones
     */
    public function removeSecciones(\AcreditacionBundle\Entity\Seccion $secciones)
    {
        $this->secciones->removeElement($secciones);
    }

    /**
     * Get secciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecciones()
    {
        return $this->secciones;
    }

    /**
     * Add formulariosPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativo
     * @return Formulario
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

    public function __toString(){
        return $this->getCodFormulario() . ' - ' . $this->getNbrFormulario();
    }
}
