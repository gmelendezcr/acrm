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
     * @ORM\JoinColumn(name="ID_USUARIO_ENTREVISTA",referencedColumnName="username")
     */
    private $idUsuarioEntrevista;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="formulariosPorCentroEducativoDigitados")
     * @ORM\JoinColumn(name="ID_USUARIO_DIGITA",referencedColumnName="username")
     */
    private $idUsuarioDigita;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="formulariosPorCentroEducativoRevisados")
     * @ORM\JoinColumn(name="ID_USUARIO_REVISA",referencedColumnName="username")
     */
    private $idUsuarioRevisa;

    /**
     * @ORM\OneToMany(targetEntity="RespuestaPorFormularioPorCentroEducativo",mappedBy="idFormularioPorCentroEducativo")
     */
    private $respuestasPorFormularioPorCentroEducativo;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->respuestasPorFormularioPorCentroEducativo = new \Doctrine\Common\Collections\ArrayCollection();
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
}