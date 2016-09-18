<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccionPorUsuario
 *
 * @ORM\Table(name="ACCION_POR_USUARIO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\AccionPorUsuarioRepository")
 */
class AccionPorUsuario
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ACCION_POR_USUARIO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idAccionPorUsuario;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="accionesPorUsuario")
     * @ORM\JoinColumn(name="ID_USUARIO",referencedColumnName="id")
     */
    private $idUsuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_HORA", type="datetime")
     */
    private $fechaHora;

    /**
     * @var string
     *
     * @ORM\Column(name="DIRECCION_IP", type="string", length=40)
     */
    private $direccionIp;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="TipoAccionUsuario", inversedBy="accionesPorUsuario")
     * @ORM\JoinColumn(name="ID_TIPO_ACCION_USUARIO",referencedColumnName="ID_TIPO_ACCION_USUARIO")
     */
    private $idTipoAccionUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="DETALLE_ACCION_USUARIO", type="string", length=255)
     */
    private $detalleAccionUsuario;


    /**
     * Get idAccionPorUsuario
     *
     * @return integer 
     */
    public function getIdAccionPorUsuario()
    {
        return $this->idAccionPorUsuario;
    }

    /**
     * Set idUsuario
     *
     * @param  \AcreditacionBundle\Entity\Usuario $idUsuario
     * @return AccionPorUsuario
     */
    public function setIdUsuario(\AcreditacionBundle\Entity\Usuario $idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \AcreditacionBundle\Entity\Usuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set fechaHora
     *
     * @param \DateTime $fechaHora
     * @return AccionPorUsuario
     */
    public function setFechaHora($fechaHora)
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    /**
     * Get fechaHora
     *
     * @return \DateTime 
     */
    public function getFechaHora()
    {
        return $this->fechaHora;
    }

    /**
     * Set direccionIp
     *
     * @param string $direccionIp
     * @return AccionPorUsuario
     */
    public function setDireccionIp($direccionIp)
    {
        $this->direccionIp = $direccionIp;

        return $this;
    }

    /**
     * Get direccionIp
     *
     * @return string 
     */
    public function getDireccionIp()
    {
        return $this->direccionIp;
    }

    /**
     * Set idTipoAccionUsuario
     *
     * @param  \AcreditacionBundle\Entity\TipoAccionUsuario $idTipoAccionUsuario
     * @return AccionPorUsuario
     */
    public function setIdTipoAccionUsuario(\AcreditacionBundle\Entity\TipoAccionUsuario $idTipoAccionUsuario)
    {
        $this->idTipoAccionUsuario = $idTipoAccionUsuario;

        return $this;
    }

    /**
     * Get idTipoAccionUsuario
     *
     * @return \AcreditacionBundle\Entity\TipoAccionUsuario
     */
    public function getIdTipoAccionUsuario()
    {
        return $this->idTipoAccionUsuario;
    }

    /**
     * Set detalleAccionUsuario
     *
     * @param string $detalleAccionUsuario
     * @return AccionPorUsuario
     */
    public function setDetalleAccionUsuario($detalleAccionUsuario)
    {
        $this->detalleAccionUsuario = $detalleAccionUsuario;

        return $this;
    }

    /**
     * Get detalleAccionUsuario
     *
     * @return string 
     */
    public function getDetalleAccionUsuario()
    {
        return $this->detalleAccionUsuario;
    }
}
