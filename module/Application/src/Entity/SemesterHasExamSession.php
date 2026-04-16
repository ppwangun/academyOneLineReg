<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;


use Application\Entity\ExamSession;
use Application\Entity\Semester;
/**
 * SemesterHasExamSession
 *
 * @ORM\Table(name="semester_has_exam_session", indexes={@ORM\Index(name="fk_semester_has_exam_session_exam_session1_idx", columns={"exam_session_id"}), @ORM\Index(name="fk_semester_has_exam_session_semester1_idx", columns={"semester_id"})})
 * @ORM\Entity
 */
class SemesterHasExamSession
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
     * @var Semester
     *
     * @ORM\ManyToOne(targetEntity="Semester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     * })
     */
    private $semester;

    /**
     * @var ExamSession
     *
     * @ORM\ManyToOne(targetEntity="ExamSession")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_session_id", referencedColumnName="id")
     * })
     */
    private $examSession;



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
     * Set semester.
     *
     * @param Semester|null $semester
     *
     * @return SemesterHasExamSession
     */
    public function setSemester(Semester $semester = null)
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester.
     *
     * @return Semester|null
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * Set examSession.
     *
     * @param ExamSession|null $examSession
     *
     * @return SemesterHasExamSession
     */
    public function setExamSession(ExamSession $examSession = null)
    {
        $this->examSession = $examSession;

        return $this;
    }

    /**
     * Get examSession.
     *
     * @return ExamSession|null
     */
    public function getExamSession()
    {
        return $this->examSession;
    }
}
