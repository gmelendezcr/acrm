<?php

namespace AcreditacionBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="USUARIO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\UsuarioRepository")
 */
class Usuario extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="FormularioPorCentroEducativo",mappedBy="idUsuarioEntrevista")
     */
    private $formulariosPorCentroEducativoEntrevistados;

    /**
     * @ORM\OneToMany(targetEntity="FormularioPorCentroEducativo",mappedBy="idUsuarioDigita")
     */
    private $formulariosPorCentroEducativoDigitados;

    /**
     * @ORM\OneToMany(targetEntity="FormularioPorCentroEducativo",mappedBy="idUsuarioRevisa")
     */
    private $formulariosPorCentroEducativoRevisados;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formulariosPorCentroEducativoEntrevistados = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formulariosPorCentroEducativoDigitados = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formulariosPorCentroEducativoRevisados = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add formulariosPorCentroEducativoEntrevistados
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoEntrevistados
     * @return Usuario
     */
    public function addFormulariosPorCentroEducativoEntrevistados(\AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoEntrevistados)
    {
        $this->formulariosPorCentroEducativoEntrevistados[] = $formulariosPorCentroEducativoEntrevistados;

        return $this;
    }

    /**
     * Remove formulariosPorCentroEducativoEntrevistados
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoEntrevistados
     */
    public function removeFormulariosPorCentroEducativoEntrevistados(\AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoEntrevistados)
    {
        $this->formulariosPorCentroEducativoEntrevistados->removeElement($formulariosPorCentroEducativoEntrevistados);
    }

    /**
     * Get formulariosPorCentroEducativoEntrevistados
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormulariosPorCentroEducativoEntrevistados()
    {
        return $this->formulariosPorCentroEducativoEntrevistados;
    }

    /**
     * Add formulariosPorCentroEducativoDigitados
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoDigitados
     * @return Usuario
     */
    public function addFormulariosPorCentroEducativoDigitados(\AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoDigitados)
    {
        $this->formulariosPorCentroEducativoDigitados[] = $formulariosPorCentroEducativoDigitados;

        return $this;
    }

    /**
     * Remove formulariosPorCentroEducativoDigitados
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoDigitados
     */
    public function removeFormulariosPorCentroEducativoDigitados(\AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoDigitados)
    {
        $this->formulariosPorCentroEducativoDigitados->removeElement($formulariosPorCentroEducativoDigitados);
    }

    /**
     * Get formulariosPorCentroEducativoDigitados
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormulariosPorCentroEducativoDigitados()
    {
        return $this->formulariosPorCentroEducativoDigitados;
    }

    /**
     * Add formulariosPorCentroEducativoRevisados
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoRevisados
     * @return Usuario
     */
    public function addFormulariosPorCentroEducativoRevisados(\AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoRevisados)
    {
        $this->formulariosPorCentroEducativoRevisados[] = $formulariosPorCentroEducativoRevisados;

        return $this;
    }

    /**
     * Remove formulariosPorCentroEducativoRevisados
     *
     * @param \AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoRevisados
     */
    public function removeFormulariosPorCentroEducativoRevisados(\AcreditacionBundle\Entity\FormularioPorCentroEducativo $formulariosPorCentroEducativoRevisados)
    {
        $this->formulariosPorCentroEducativoRevisados->removeElement($formulariosPorCentroEducativoRevisados);
    }

    /**
     * Get formulariosPorCentroEducativoRevisados
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormulariosPorCentroEducativoRevisados()
    {
        return $this->formulariosPorCentroEducativoRevisados;
    }
}
