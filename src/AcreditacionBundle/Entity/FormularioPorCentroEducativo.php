<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormularioPorCentroEducativo
 *
 * @ORM\Table(name="FORMULARIO_POR_CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\FormularioPorCentroEducativoRepository")
 */
class FormularioPorCentroEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_FORMULARIO_POR_CENTRO_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idFormularioPorCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="CentroEducativo", inversedBy="formulariosPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_CENTRO_EDUCATIVO",referencedColumnName="ID_CENTRO_EDUCATIVO")
     */
    private $idCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Formulario", inversedBy="formulariosPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_FORMULARIO",referencedColumnName="ID_FORMULARIO")
     */
    private $idFormulario;

    /**
     * @var string
     *
     * @ORM\Column(name="LUGAR_APLICACION", type="string", length=255)
     */
    private $lugarAplicacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_APLICACION", type="datetime")
     */
    private $fechaAplicacion;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="EstadoFormulario", inversedBy="formulariosPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_ESTADO_FORMULARIO",referencedColumnName="ID_ESTADO_FORMULARIO")
     */
    private $idEstadoFormulario;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="formulariosPorCentroEducativoEntrevistados")
     * @ORM\JoinColumn(name="ID_USUARIO_ENTREVISTA",referencedColumnName="id")
     */
    private $idUsuarioEntrevista;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="formulariosPorCentroEducativoDigitados")
     * @ORM\JoinColumn(name="ID_USUARIO_DIGITA",referencedColumnName="id")
     */
    private $idUsuarioDigita;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="formulariosPorCentroEducativoRevisados")
     * @ORM\JoinColumn(name="ID_USUARIO_REVISA",referencedColumnName="id")
     */
    private $idUsuarioRevisa;

    /**
     * @var string
     *
     * @ORM\Column(name="OBSERVACIONES", type="string", length=255, nullable=true)
     */
    private $observaciones;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ESTADO_CRITERIO_CENTRO_EDUCATIVO", type="string", length=5, nullable=true)
     */
    private $estadoCriterioCentroEducativo;

    /**
     * @ORM\OneToMany(targetEntity="RespuestaPorFormularioPorCentroEducativo",mappedBy="idFormularioPorCentroEducativo")
     */
    private $respuestasPorFormularioPorCentroEducativo;

    /**
     * @ORM\OneToMany(targetEntity="SeccionPorFormularioPorCentroEducativo",mappedBy="idFormularioPorCentroEducativo")
     */
    private $seccionesPorFormularioPorCentroEducativo;

    /**
     * @ORM\OneToMany(targetEntity="ViewFormularioPorCentroEducativoSeccionPonderacion",mappedBy="idFormularioPorCentroEducativo")
     */
    private $formulariosPorCentroEducativoSeccionPonderacion;

    /**
     * @ORM\OneToMany(targetEntity="ViewFormularioPorCentroEducativoIndicadorPonderacion",mappedBy="idFormularioPorCentroEducativo")
     */
    private $formulariosPorCentroEducativoIndicadorPonderacion;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->respuestasPorFormularioPorCentroEducativo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formulariosPorCentroEducativoSeccionPonderacion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idFormularioPorCentroEducativo
     *
     * @return integer 
     */
    public function getIdFormularioPorCentroEducativo()
    {
        return $this->idFormularioPorCentroEducativo;
    }

    /**
     * Set idCentroEducativo
     *
     * @param  \AcreditacionBundle\Entity\CentroEducativo $idCentroEducativo
     * @return FormularioPorCentroEducativo
     */
    public function setIdCentroEducativo(\AcreditacionBundle\Entity\CentroEducativo $idCentroEducativo)
    {
        $this->idCentroEducativo = $idCentroEducativo;

        return $this;
    }

    /**
     * Get idCentroEducativo
     *
     * @return \AcreditacionBundle\Entity\CentroEducativo
     */
    public function getIdCentroEducativo()
    {
        return $this->idCentroEducativo;
    }

    /**
     * Set idFormulario
     *
     * @param  \AcreditacionBundle\Entity\Formulario $idFormulario
     * @return FormularioPorCentroEducativo
     */
    public function setIdFormulario(\AcreditacionBundle\Entity\Formulario $idFormulario)
    {
        $this->idFormulario = $idFormulario;

        return $this;
    }

    /**
     * Get idFormulario
     *
     * @return \AcreditacionBundle\Entity\Formulario
     */
    public function getIdFormulario()
    {
        return $this->idFormulario;
    }

    /**
     * Set lugarAplicacion
     *
     * @param string $lugarAplicacion
     * @return FormularioPorCentroEducativo
     */
    public function setLugarAplicacion($lugarAplicacion)
    {
        $this->lugarAplicacion = $lugarAplicacion;

        return $this;
    }

    /**
     * Get lugarAplicacion
     *
     * @return string 
     */
    public function getLugarAplicacion()
    {
        return $this->lugarAplicacion;
    }

    /**
     * Set fechaAplicacion
     *
     * @param \DateTime $fechaAplicacion
     * @return FormularioPorCentroEducativo
     */
    public function setFechaAplicacion($fechaAplicacion)
    {
        $this->fechaAplicacion = $fechaAplicacion;

        return $this;
    }

    /**
     * Get fechaAplicacion
     *
     * @return \DateTime 
     */
    public function getFechaAplicacion()
    {
        return $this->fechaAplicacion;
    }

    /**
     * Set idEstadoFormulario
     *
     * @param  \AcreditacionBundle\Entity\EstadoFormulario $idEstadoFormulario
     * @return FormularioPorCentroEducativo
     */
    public function setIdEstadoFormulario(\AcreditacionBundle\Entity\EstadoFormulario $idEstadoFormulario)
    {
        $this->idEstadoFormulario = $idEstadoFormulario;

        return $this;
    }

    /**
     * Get idEstadoFormulario
     *
     * @return \AcreditacionBundle\Entity\EstadoFormulario
     */
    public function getIdEstadoFormulario()
    {
        return $this->idEstadoFormulario;
    }

    /**
     * Set idUsuarioEntrevista
     *
     * @param  \AcreditacionBundle\Entity\Usuario $idUsuarioEntrevista
     * @return FormularioPorCentroEducativo
     */
    public function setIdUsuarioEntrevista(\AcreditacionBundle\Entity\Usuario $idUsuarioEntrevista)
    {
        $this->idUsuarioEntrevista = $idUsuarioEntrevista;

        return $this;
    }

    /**
     * Get idUsuarioEntrevista
     *
     * @return \AcreditacionBundle\Entity\Usuario
     */
    public function getIdUsuarioEntrevista()
    {
        return $this->idUsuarioEntrevista;
    }

    /**
     * Set idUsuarioDigita
     *
     * @param  \AcreditacionBundle\Entity\Usuario $idUsuarioDigita
     * @return FormularioPorCentroEducativo
     */
    public function setIdUsuarioDigita(\AcreditacionBundle\Entity\Usuario $idUsuarioDigita)
    {
        $this->idUsuarioDigita = $idUsuarioDigita;

        return $this;
    }

    /**
     * Get idUsuarioDigita
     *
     * @return \AcreditacionBundle\Entity\Usuario
     */
    public function getIdUsuarioDigita()
    {
        return $this->idUsuarioDigita;
    }

    /**
     * Set idUsuarioRevisa
     *
     * @param  \AcreditacionBundle\Entity\Usuario $idUsuarioRevisa
     * @return FormularioPorCentroEducativo
     */
    public function setIdUsuarioRevisa(\AcreditacionBundle\Entity\Usuario $idUsuarioRevisa)
    {
        $this->idUsuarioRevisa = $idUsuarioRevisa;

        return $this;
    }

    /**
     * Get idUsuarioRevisa
     *
     * @return \AcreditacionBundle\Entity\Usuario
     */
    public function getIdUsuarioRevisa()
    {
        return $this->idUsuarioRevisa;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return FormularioPorCentroEducativo
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }
    
    /**
     * Set estadoCriterioCentroEducativo
     *
     * @param string $estadoCriterioCentroEducativo
     * @return FormularioPorCentroEducativo
     */
    public function setEstadoCriterioCentroEducativo($estadoCriterioCentroEducativo)
    {
        $this->estadoCriterioCentroEducativo = $estadoCriterioCentroEducativo;

        return $this;
    }

    /**
     * Get estadoCriterioCentroEducativo
     *
     * @return string 
     */
    public function getEstadoCriterioCentroEducativo()
    {
        return $this->estadoCriterioCentroEducativo;
    }

    /**
     * Add respuestasPorFormularioPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\RespuestaPorFormularioPorCentroEducativo $respuestasPorFormularioPorCentroEducativo
     * @return FormularioPorCentroEducativo
     */
    public function addRespuestasPorFormularioPorCentroEducativo(\AcreditacionBundle\Entity\RespuestaPorFormularioPorCentroEducativo $respuestasPorFormularioPorCentroEducativo)
    {
        $this->respuestasPorFormularioPorCentroEducativo[] = $respuestasPorFormularioPorCentroEducativo;

        return $this;
    }

    /**
     * Remove respuestasPorFormularioPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\RespuestaPorFormularioPorCentroEducativo $respuestasPorFormularioPorCentroEducativo
     */
    public function removeRespuestasPorFormularioPorCentroEducativo(\AcreditacionBundle\Entity\RespuestaPorFormularioPorCentroEducativo $respuestasPorFormularioPorCentroEducativo)
    {
        $this->respuestasPorFormularioPorCentroEducativo->removeElement($respuestasPorFormularioPorCentroEducativo);
    }

    /**
     * Get respuestasPorFormularioPorCentroEducativo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRespuestasPorFormularioPorCentroEducativo()
    {
        return $this->respuestasPorFormularioPorCentroEducativo;
    }

    /**
     * Add seccionesPorFormularioPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\SeccionPorFormularioPorCentroEducativo $seccionesPorFormularioPorCentroEducativo
     * @return FormularioPorCentroEducativo
     */
    public function addSeccionesPorFormularioPorCentroEducativo(\AcreditacionBundle\Entity\SeccionPorFormularioPorCentroEducativo $seccionesPorFormularioPorCentroEducativo)
    {
        $this->seccionesPorFormularioPorCentroEducativo[] = $seccionesPorFormularioPorCentroEducativo;

        return $this;
    }

    /**
     * Add formulariosPorCentroEducativoSeccionPonderacion
     *
     * @param \AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoSeccionPonderacion $formulariosPorCentroEducativoSeccionPonderacion
     * @return FormularioPorCentroEducativo
     */
    public function addFormulariosPorCentroEducativoSeccionPonderacion(\AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoSeccionPonderacion $formulariosPorCentroEducativoSeccionPonderacion)
    {
        $this->formulariosPorCentroEducativoSeccionPonderacion[] = $formulariosPorCentroEducativoSeccionPonderacion;

        return $this;
    }

    /**
     * Remove seccionesPorFormularioPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\SeccionPorFormularioPorCentroEducativo $seccionesPorFormularioPorCentroEducativo
     */
    public function removeSeccionesPorFormularioPorCentroEducativo(\AcreditacionBundle\Entity\SeccionPorFormularioPorCentroEducativo $seccionesPorFormularioPorCentroEducativo)
    {
        $this->seccionesPorFormularioPorCentroEducativo->removeElement($seccionesPorFormularioPorCentroEducativo);
    }

    /**
     * Get seccionesPorFormularioPorCentroEducativo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeccionesPorFormularioPorCentroEducativo()
    {
        return $this->seccionesPorFormularioPorCentroEducativo;
    }

    /**
     * Remove formulariosPorCentroEducativoSeccionPonderacion
     *
     * @param \AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoSeccionPonderacion $formulariosPorCentroEducativoSeccionPonderacion
     */
    public function removeFormulariosPorCentroEducativoSeccionPonderacion(\AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoSeccionPonderacion $formulariosPorCentroEducativoSeccionPonderacion)
    {
        $this->formulariosPorCentroEducativoSeccionPonderacion->removeElement($formulariosPorCentroEducativoSeccionPonderacion);
    }

    /**
     * Get formulariosPorCentroEducativoSeccionPonderacion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormulariosPorCentroEducativoSeccionPonderacion()
    {
        return $this->formulariosPorCentroEducativoSeccionPonderacion;
    }

    /**
     * Add formulariosPorCentroEducativoIndicadorPonderacion
     *
     * @param \AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoIndicadorPonderacion $formulariosPorCentroEducativoIndicadorPonderacion
     * @return FormularioPorCentroEducativo
     */
    public function addFormulariosPorCentroEducativoIndicadorPonderacion(\AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoIndicadorPonderacion $formulariosPorCentroEducativoIndicadorPonderacion)
    {
        $this->formulariosPorCentroEducativoIndicadorPonderacion[] = $formulariosPorCentroEducativoIndicadorPonderacion;

        return $this;
    }

    /**
     * Remove formulariosPorCentroEducativoIndicadorPonderacion
     *
     * @param \AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoIndicadorPonderacion $formulariosPorCentroEducativoIndicadorPonderacion
     */
    public function removeFormulariosPorCentroEducativoIndicadorPonderacion(\AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoIndicadorPonderacion $formulariosPorCentroEducativoIndicadorPonderacion)
    {
        $this->formulariosPorCentroEducativoIndicadorPonderacion->removeElement($formulariosPorCentroEducativoIndicadorPonderacion);
    }

    /**
     * Get formulariosPorCentroEducativoIndicadorPonderacion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormulariosPorCentroEducativoIndicadorPonderacion()
    {
        return $this->formulariosPorCentroEducativoIndicadorPonderacion;
    }
}
