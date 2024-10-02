<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\Teacher;

/**
 * FileDocument
 *
 * @ORM\Table(name="file_document", indexes={@ORM\Index(name="fk_file_document_teacher1_idx", columns={"teacher_id"})})
 * @ORM\Entity
 */
class FileDocument
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cover_letter", type="string", length=45, nullable=true)
     */
    private $coverLetter;

    /**
     * @var string|null
     *
     * @ORM\Column(name="resume", type="string", length=45, nullable=true)
     */
    private $resume;

    /**
     * @var string|null
     *
     * @ORM\Column(name="certificate", type="string", length=45, nullable=true)
     */
    private $certificate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="grade_justification", type="string", length=45, nullable=true)
     */
    private $gradeJustification;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rib", type="string", length=45, nullable=true)
     */
    private $rib;

    /**
     * @var string|null
     *
     * @ORM\Column(name="current_function", type="string", length=45, nullable=true)
     */
    private $currentFunction;

    /**
     * @var string|null
     *
     * @ORM\Column(name="picture", type="string", length=45, nullable=true)
     */
    private $picture;

    /**
     * @var Teacher
     *
     * @ORM\ManyToOne(targetEntity="Teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     */
    private $teacher;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set coverLetter.
     *
     * @param string|null $coverLetter
     *
     * @return FileDocument
     */
    public function setCoverLetter($coverLetter = null)
    {
        $this->coverLetter = $coverLetter;
    
        return $this;
    }

    /**
     * Get coverLetter.
     *
     * @return string|null
     */
    public function getCoverLetter()
    {
        return $this->coverLetter;
    }

    /**
     * Set resume.
     *
     * @param string|null $resume
     *
     * @return FileDocument
     */
    public function setResume($resume = null)
    {
        $this->resume = $resume;
    
        return $this;
    }

    /**
     * Get resume.
     *
     * @return string|null
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set certificate.
     *
     * @param string|null $certificate
     *
     * @return FileDocument
     */
    public function setCertificate($certificate = null)
    {
        $this->certificate = $certificate;
    
        return $this;
    }

    /**
     * Get certificate.
     *
     * @return string|null
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Set gradeJustification.
     *
     * @param string|null $gradeJustification
     *
     * @return FileDocument
     */
    public function setGradeJustification($gradeJustification = null)
    {
        $this->gradeJustification = $gradeJustification;
    
        return $this;
    }

    /**
     * Get gradeJustification.
     *
     * @return string|null
     */
    public function getGradeJustification()
    {
        return $this->gradeJustification;
    }

    /**
     * Set rib.
     *
     * @param string|null $rib
     *
     * @return FileDocument
     */
    public function setRib($rib = null)
    {
        $this->rib = $rib;
    
        return $this;
    }

    /**
     * Get rib.
     *
     * @return string|null
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set currentFunction.
     *
     * @param string|null $currentFunction
     *
     * @return FileDocument
     */
    public function setCurrentFunction($currentFunction = null)
    {
        $this->currentFunction = $currentFunction;
    
        return $this;
    }

    /**
     * Get currentFunction.
     *
     * @return string|null
     */
    public function getCurrentFunction()
    {
        return $this->currentFunction;
    }

    /**
     * Set picture.
     *
     * @param string|null $picture
     *
     * @return FileDocument
     */
    public function setPicture($picture = null)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture.
     *
     * @return string|null
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set teacher.
     *
     * @param Teacher|null $teacher
     *
     * @return FileDocument
     */
    public function setTeacher(Teacher $teacher = null)
    {
        $this->teacher = $teacher;
    
        return $this;
    }

    /**
     * Get teacher.
     *
     * @return Teacher|null
     */
    public function getTeacher()
    {
        return $this->teacher;
    }
}
