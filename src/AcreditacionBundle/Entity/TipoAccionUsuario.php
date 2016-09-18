<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoAccionUsuario
 *
 * @ORM\Table(name="TIPO_ACCION_USUARIO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\TipoAccionUsuarioRepository")
 */
class TipoAccionUsuario
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_TIPO_ACCION_USUARIO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idTipoAccionUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_TIPO_ACCION_USUARIO", type="string", length=2, unique=true)
     */
    private $codTipoAccionUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION_TIPO_ACCION_USUARIO", type="string", length=255)
     */
    private $descripcionTipoAccionUsuario;

    /**
     * @ORM\OneToMany(targetEntity="AccionPorUsuario",mappedBy="idAccionPorUsuario")
     */
    private $accionesPorUsuario;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accionesPorUsuario = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idTipoAccionUsuario
     *
     * @return integer 
     */
    public function getIdTipoAccionUsuario()
    {
        return $this->idTipoAccionUsuario;
    }

    /**
     * Set codTipoAccionUsuario
     *
     * @param string $codTipoAccionUsuario
     * @return TipoAccionUsuario
     */
    public function setCodTipoAccionUsuario($codTipoAccionUsuario)
    {
        $this->codTipoAccionUsuario = $codTipoAccionUsuario;

        return $this;
    }

    /**
     * Get codTipoAccionUsuario
     *
     * @return string 
     */
    public function getCodTipoAccionUsuario()
    {
        return $this->codTipoAccionUsuario;
    }

    /**
     * Set descripcionTipoAccionUsuario
     *
     * @param string $descripcionTipoAccionUsuario
     * @return TipoAccionUsuario
     */
    public function setDescripcionTipoAccionUsuario($descripcionTipoAccionUsuario)
    {
        $this->descripcionTipoAccionUsuario = $descripcionTipoAccionUsuario;

        return $this;
    }

    /**
     * Get descripcionTipoAccionUsuario
     *
     * @return string 
     */
    public function getDescripcionTipoAccionUsuario()
    {
        return $this->descripcionTipoAccionUsuario;
    }

    /**
     * Add accionesPorUsuario
     *
     * @param \AcreditacionBundle\Entity\TipoAccionUsuario $accionesPorUsuario
     * @return TipoAccionUsuario
     */
    public function addAccionesPorUsuario(\AcreditacionBundle\Entity\TipoAccionUsuario $accionesPorUsuario)
    {
        $this->accionesPorUsuario[] = $accionesPorUsuario;

        return $this;
    }

    /**
     * Remove accionesPorUsuario
     *
     * @param \AcreditacionBundle\Entity\TipoAccionUsuario $accionesPorUsuario
     */
    public function removeAccionesPorUsuario(\AcreditacionBundle\Entity\TipoAccionUsuario $accionesPorUsuario)
    {
        $this->accionesPorUsuario->removeElement($accionesPorUsuario);
    }

    /**
     * Get accionesPorUsuario
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccionesPorUsuario()
    {
        return $this->accionesPorUsuario;
    }
}
