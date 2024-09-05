<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * ProspectiveStudentView
 *
 * @ORM\Table(name="prospective_student_view")
 * @ORM\Entity
 */
class ProspectiveStudentView
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="num_dossier", type="string", length=45, nullable=true)
     */
    private $numDossier;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;



    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=45, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="choix_formation_1", type="string", length=45, nullable=true)
     */
    private $choixFormation1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="choix_formation_2", type="string", length=45, nullable=true)
     */
    private $choixFormation2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="choix_formation_3", type="string", length=45, nullable=true)
     */
    private $choixFormation3;




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
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }



    /**
     * Get numDossier
     *
     * @return string
     */
    public function getNumDossier()
    {
        return $this->numDossier;
    }



    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }



    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }



    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }


    


    /**
     * Get choixFormation1.
     *
     * @return string|null
     */
    public function getChoixFormation1()
    {
        return $this->choixFormation1;
    }


    /**
     * Get choixFormation2.
     *
     * @return string|null
     */
    public function getChoixFormation2()
    {
        return $this->choixFormation2;
    }


    /**
     * Get choixFormation3.
     *
     * @return string|null
     */
    public function getChoixFormation3()
    {
        return $this->choixFormation3;
    }
    


}
