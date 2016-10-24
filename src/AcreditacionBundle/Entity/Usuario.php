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
     * @var string
     *
     * @ORM\Column(name="NOMBRES", type="string", length=20, nullable=true)
     */
    private $nombres;

    /**
     * @var string
     *
     * @ORM\Column(name="APELLIDOS", type="string", length=20, nullable=true)
     */
    private $apellidos;

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
        parent::__construct();
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
     * Set nombres
     *
     * @param string $nombres
     * @return Usuario
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
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
    
    public function vaciarPropiedades()
    {
        $detalle='';
        $detalle.='usuario: ' . $this->getUsername() . "\n";
        $detalle.='nombres: ' . $this->getNombres() . "\n";
        $detalle.='apellidos: ' . $this->getApellidos() . "\n";
        $detalle.='correo: ' . $this->getEmail() . "\n";
        $roleArr=array();
        foreach ($this->getRoles() as $role) {
            if($role){
                $roleArr[]=$role;
            }
        }
        $detalle.='roles: ' . implode(', ',$roleArr) . "\n";
        return $detalle;
    }
}
