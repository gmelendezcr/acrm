<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CentroEducativo
 *
 * @ORM\Table(name="CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\CentroEducativoRepository")
 */
class CentroEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_CENTRO_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_CENTRO_EDUCATIVO", type="string", length=20, unique=true)
     */
    private $codCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_CENTRO_EDUCATIVO", type="string", length=255)
     */
    private $nbrCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="DIRECCION_CENTRO_EDUCATIVO", type="string", length=255)
     */
    private $direccionCentroEducativo;

    /**
     * @var int
     *
     * @ORM\Column(name="TOTAL_ALUMNOS", type="integer", nullable=true)
     */
    private $totalAlumnos;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Municipio", inversedBy="centrosEducativos")
     * @ORM\JoinColumn(name="ID_MUNICIPIO",referencedColumnName="ID_MUNICIPIO")
     */
    private $idMunicipio;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="JornadaCentroEducativo", inversedBy="centrosEducativos")
     * @ORM\JoinColumn(name="ID_JORNADA_CENTRO_EDUCATIVO",referencedColumnName="ID_JORNADA_CENTRO_EDUCATIVO")
     */
    private $idJornadaCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="TamannoCentroEducativo", inversedBy="centrosEducativos")
     * @ORM\JoinColumn(name="ID_TAMANNO_CENTRO_EDUCATIVO",referencedColumnName="ID_TAMANNO_CENTRO_EDUCATIVO")
     */
    private $idTamannoCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="GradoEscolarPorCentroEducativo",mappedBy="idCentroEducativo")
     */
    private $gradosEscolaresPorCentroEducativo;

    /**
     * @ORM\OneToMany(targetEntity="FormularioPorCentroEducativo",mappedBy="idCentroEducativo")
     */
    private $formulariosPorCentroEducativo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gradosEscolaresPorCentroEducativo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formulariosPorCentroEducativo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idCentroEducativo
     *
     * @return integer 
     */
    public function getIdCentroEducativo()
    {
        return $this->idCentroEducativo;
    }

    /**
     * Set codCentroEducativo
     *
     * @param string $codCentroEducativo
     * @return CentroEducativo
     */
    public function setCodCentroEducativo($codCentroEducativo)
    {
        $this->codCentroEducativo = $codCentroEducativo;

        return $this;
    }

    /**
     * Get codCentroEducativo
     *
     * @return string 
     */
    public function getCodCentroEducativo()
    {
        return $this->codCentroEducativo;
    }

    /**
     * Set nbrCentroEducativo
     *
     * @param string $nbrCentroEducativo
     * @return CentroEducativo
     */
    public function setNbrCentroEducativo($nbrCentroEducativo)
    {
        $this->nbrCentroEducativo = $nbrCentroEducativo;

        return $this;
    }

    /**
     * Get nbrCentroEducativo
     *
     * @return string 
     */
    public function getNbrCentroEducativo()
    {
        return $this->nbrCentroEducativo;
    }

    /**
     * Set direccionCentroEducativo
     *
     * @param string $direccionCentroEducativo
     * @return CentroEducativo
     */
    public function setDireccionCentroEducativo($direccionCentroEducativo)
    {
        $this->direccionCentroEducativo = $direccionCentroEducativo;

        return $this;
    }

    /**
     * Get direccionCentroEducativo
     *
     * @return string 
     */
    public function getDireccionCentroEducativo()
    {
        return $this->direccionCentroEducativo;
    }

    /**
     * Set totalAlumnos
     *
     * @param integer $totalAlumnos
     * @return CentroEducativo
     */
    public function setTotalAlumnos($totalAlumnos)
    {
        $this->totalAlumnos = $totalAlumnos;

        return $this;
    }

    /**
     * Get totalAlumnos
     *
     * @return integer 
     */
    public function getTotalAlumnos()
    {
        return $this->totalAlumnos;
    }

    /**
     * Set idMunicipio
     *
     * @param  \AcreditacionBundle\Entity\Municipio $idMunicipio
     * @return CentroEducativo
     */
    public function setIdMunicipio(\AcreditacionBundle\Entity\Municipio $idMunicipio)
    {
        $this->idMunicipio = $idMunicipio;

        return $this;
    }

    /**
     * Get idMunicipio
     *
     * @return \AcreditacionBundle\Entity\Municipio
     */
    public function getIdMunicipio()
    {
        return $this->idMunicipio;
    }

    /**
     * Set idJornadaCentroEducativo
     *
     * @param  \AcreditacionBundle\Entity\JornadaCentroEducativo $idJornadaCentroEducativo
     * @return CentroEducativo
     */
    public function setIdJornadaCentroEducativo(\AcreditacionBundle\Entity\JornadaCentroEducativo $idJornadaCentroEducativo)
    {
        $this->idJornadaCentroEducativo = $idJornadaCentroEducativo;

        return $this;
    }

    /**
     * Get idJornadaCentroEducativo
     *
     * @return \AcreditacionBundle\Entity\JornadaCentroEducativo
     */
    public function getIdJornadaCentroEducativo()
    {
        return $this->idJornadaCentroEducativo;
    }

    /**
     * Set idTamannoCentroEducativo
     *
     * @param  \AcreditacionBundle\Entity\TamannoCentroEducativo $idTamannoCentroEducativo
     * @return CentroEducativo
     */
    public function setIdTamannoCentroEducativo(\AcreditacionBundle\Entity\TamannoCentroEducativo $idTamannoCentroEducativo)
    {
        $this->idTamannoCentroEducativo = $idTamannoCentroEducativo;

        return $this;
    }

    /**
     * Get idTamannoCentroEducativo
     *
     * @return \AcreditacionBundle\Entity\TamannoCentroEducativo
     */
    public function getIdTamannoCentroEducativo()
    {
        return $this->idTamannoCentroEducativo;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return CentroEducativo
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
     * Add gradosEscolaresPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $gradosEscolaresPorCentroEducativo
     * @return CentroEducativo
     */
    public function addGradosEscolaresPorCentroEducativo(\AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $gradosEscolaresPorCentroEducativo)
    {
        $this->gradosEscolaresPorCentroEducativo[] = $gradosEscolaresPorCentroEducativo;

        return $this;
    }

    /**
     * Remove gradosEscolaresPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $gradosEscolaresPorCentroEducativo
     */
    public function removeGradosEscolaresPorCentroEducativo(\AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $gradosEscolaresPorCentroEducativo)
    {
        $this->gradosEscolaresPorCentroEducativo->removeElement($gradosEscolaresPorCentroEducativo);
    }

    /**
     * Get gradosEscolaresPorCentroEducativo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGradosEscolaresPorCentroEducativo()
    {
        return $this->gradosEscolaresPorCentroEducativo;
    }

    /**
     * Add formulariosPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativo
     * @return CentroEducativo
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
