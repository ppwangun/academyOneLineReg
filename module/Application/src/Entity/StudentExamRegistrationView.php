<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * ExamRegistration
 *
 * @ORM\Table(name="student_exam_registration_view", indexes={@ORM\Index(name="fk_exam_registration_exam1_idx", columns={"exam_id"}), @ORM\Index(name="fk_exam_registration_student1_idx", columns={"student_id"})})
 * @ORM\Entity
 */
class StudentExamRegistrationView
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * 
     */
    private $id;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="acadYrId", type="integer", nullable=false)
    * @ORM\Id
    * 
    */
    private $acadYrId;       
    

    /**
    * @var string
    *
    * @ORM\Column(name="code_exam", type="string", length=45, nullable=true)
    */
    private $codeExam;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="coshs", type="integer", nullable=true)
    */
    private $coshs;

    /**
    * @var string
    *
    * @ORM\Column(name="code_ue", type="string", length=45, nullable=true)
    */
    private $codeUe;
    
    /**
    * @var string
    *
    * @ORM\Column(name="nom_ue", type="string", length=255, nullable=true)
    */
    private $nomUe;
    
    /**
    * @var string
    *
    * @ORM\Column(name="classe", type="string", length=255, nullable=true)
    */
    private $classe;
    
    /**
    * @var string
    *
    * @ORM\Column(name="semester", type="string", nullable=true)
    */
    private $semester;

    /**
    * @var integer
    *
    * @ORM\Column(name="student_id", type="integer", nullable=false)
    */
    private $studentId;
    /**
    * @var string
    *
    * @ORM\Column(name="matricule", type="string", length=45, nullable=true)
    */
    private $matricule;
    
    /**
    * @var string
    *
    * @ORM\Column(name="name", type="string", length=255, nullable=true)
    */
    private $nom;
    
    /**
    * @var string
    *
    * @ORM\Column(name="student_current_classe", type="string", length=255, nullable=true)
    */
    private $studentCurrentClasse;

    /**
     * @var string
     *
     * @ORM\Column(name="attendance", type="string", length=45, nullable=true)
     */
    private $attendance;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_anonymat", type="integer", nullable=true)
     */
    private $anonymat;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;    

    /**
     * @var float
     *
     * @ORM\Column(name="registered_mark", type="float", precision=10, scale=0, nullable=true)
     */
    private $registeredMark = '0';
    
    /**
     * @var float
     *
     * @ORM\Column(name="validated_mark", type="float", precision=10, scale=0, nullable=true)
     */
    private $validatedMark = '0';
    
    /**
     * @var float
     *
     * @ORM\Column(name="confirmed_mark", type="float", precision=10, scale=0, nullable=true)
     */
    private $confirmedMark = '0';
    
     /**
     * @var integer
     *
     * @ORM\Column(name="isMarkFromCatchUpExam", type="integer", nullable=true)
     */
    private $isMarkFromCatchUpExam = '0';
    
     /**
     * @var integer
     *
     * @ORM\Column(name="isMarkRegistered", type="integer", nullable=true)
     */
    private $isMarkRegistered = '0'; 
    
    /**
     * @var integer
     *
     * @ORM\Column(name="isMarkConfirmed", type="integer", nullable=true)
     */
    private $isMarkConfirmed = '0';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="isMarkValidated", type="integer", nullable=true)
     */
    private $isMarkValidated = '0';    

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
     * Set attendance
     *
     * @param string $attendance
     *
     * @return ExamRegistration
     */
    public function setAttendance($attendance)
    {
        $this->attendance = $attendance;

        return $this;
    }

    /**
     * Get attendance
     *
     * @return string
     */
    public function getAttendance()
    {
        return $this->attendance;
    }
    
    /**
     * Get matricule
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
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
     * Set numAnonymat
     *
     * @param integer $numAnonymat
     *
     * @return ExamRegistration
     */
    public function setNumAnonymat($numAnonymat)
    {
        $this->numAnonymat = $numAnonymat;

        return $this;
    }

    /**
     * Get numAnonymat
     *
     * @return integer
     */
    public function getNumAnonymat()
    {
        return $this->numAnonymat;
    }

    /**
     * Set registeredMark
     *
     * @param float $registeredMark
     *
     * @return ExamRegistration
     */
    public function setRegisteredMark($registeredMark)
    {
        $this->registeredMark = $registeredMark;

        return $this;
    }

    /**
     * Get registeredMark
     *
     * @return float
     */
    public function getRegisteredMark()
    {
        return $this->registeredMark;
    }

    /**
     * Set validatedMark
     *
     * @param float $validatedMark
     *
     * @return ExamRegistration
     */
    public function setValidatedMark($validatedMark)
    {
        $this->validatedMark = $validatedMark;

        return $this;
    }

    /**
     * Get validatedMark
     *
     * @return float
     */
    public function getValidatedMark()
    {
        return $this->validatedMark;
    }

    /**
     * Set confirmedMark
     *
     * @param float $confirmedMark
     *
     * @return ExamRegistration
     */
    public function setConfirmedMark($confirmedMark)
    {
        $this->confirmedMark = $confirmedMark;

        return $this;
    }

    /**
     * Get confirmedMark
     *
     * @return float
     */
    public function getConfirmedMark()
    {
        return $this->confirmedMark;
    }

    /**
     * Set dateRegistered
     *
     * @param \DateTime $dateRegistered
     *
     * @return ExamRegistration
     */
    public function setDateRegistered($dateRegistered)
    {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    /**
     * Get dateRegistered
     *
     * @return \DateTime
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }

    /**
     * Set dateValidated
     *
     * @param \DateTime $dateValidated
     *
     * @return ExamRegistration
     */
    public function setDateValidated($dateValidated)
    {
        $this->dateValidated = $dateValidated;

        return $this;
    }

    /**
     * Get dateValidated
     *
     * @return \DateTime
     */
    public function getDateValidated()
    {
        return $this->dateValidated;
    }

    /**
     * Set dateConfirmed
     *
     * @param \DateTime $dateConfirmed
     *
     * @return ExamRegistration
     */
    public function setDateConfirmed($dateConfirmed)
    {
        $this->dateConfirmed = $dateConfirmed;

        return $this;
    }

    /**
     * Get dateConfirmed
     *
     * @return \DateTime
     */
    public function getDateConfirmed()
    {
        return $this->dateConfirmed;
    }

    /**
     * Set isMarkRegistered
     *
     * @param integer $isMarkRegistered
     *
     * @return ExamRegistration
     */
    public function setIsMarkRegistered($isMarkRegistered)
    {
        $this->isMarkRegistered = $isMarkRegistered;

        return $this;
    }

    /**
     * Get isMarkRegistered
     *
     * @return integer
     */
    public function getIsMarkRegistered()
    {
        return $this->isMarkRegistered;
    }

    /**
     * Set isMarkValidated
     *
     * @param integer $isMarkValidated
     *
     * @return ExamRegistration
     */
    public function setIsMarkValidated($isMarkValidated)
    {
        $this->isMarkValidated = $isMarkValidated;

        return $this;
    }

    /**
     * Get isMarkValidated
     *
     * @return integer
     */
    public function getIsMarkValidated()
    {
        return $this->isMarkValidated;
    }

    /**
     * Set isMarkConfirmed
     *
     * @param integer $isMarkConfirmed
     *
     * @return ExamRegistration
     */
    public function setIsMarkConfirmed($isMarkConfirmed)
    {
        $this->isMarkConfirmed = $isMarkConfirmed;

        return $this;
    }

    /**
     * Get isMarkConfirmed
     *
     * @return integer
     */
    public function getIsMarkConfirmed()
    {
        return $this->isMarkConfirmed;
    }

    /**
     * Get isMarkFromCatchUpExam
     *
     * @return integer
     */
    public function getIsMarkFromCatchUpExam()
    {
        return $this->isMarkFromCatchUpExam;
    }
}
