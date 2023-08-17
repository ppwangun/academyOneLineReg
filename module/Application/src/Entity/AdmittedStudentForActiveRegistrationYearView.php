<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * AdmittedStudentView
 *
 * @ORM\Table(name="admitted_student_for_active_registration_year_view")
 * @ORM\Entity
 */
class AdmittedStudentForActiveRegistrationYearView
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_admission", type="datetime", nullable=true)
     */
    private $dateAdmission;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=45, nullable=true)
     */
    private $phoneNumber;

     /**
     * @var float
     *
     * @ORM\Column(name="fees_paid", type="float", precision=10, scale=0, nullable=true)
     */
    private $feesPaid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="string", length=255, nullable=true)
     */
    private $classe;
    
     /**
     * @var string
     *
     * @ORM\Column(name="filiere", type="string", length=255, nullable=true)
     */
    private $filiere;
    
    
     /**
     * @var string
     *
     * @ORM\Column(name="faculte", type="string", length=255, nullable=true)
     */
    private $faculte;




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
    public function numDossier()
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Admission
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
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
     * Get dateAdmission
     *
     * @return \DateTime
     */
    public function getDateAdmission()
    {
        return $this->dateAdmission;
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
     * Get classe
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Get feesPaid
     *
     * @return float
     */
    public function getFeesPaid()
    {
        return $this->feesPaid;
    }
}
