<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ExamSession
 *
 * @ORM\Table(name="exam_session", indexes={@ORM\Index(name="fk_exam_session_semester1_idx", columns={"semester_id"})})
 * @ORM\Entity
 */
class ExamSession
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
     * @ORM\Column(name="sesion name", type="string", length=45, nullable=true)
     */
    private $sesionName;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="begeningDate", type="datetime", nullable=true)
     */
    private $begeningdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="endingDate", type="datetime", nullable=true)
     */
    private $endingdate;

    /**
     * @var \Semester
     *
     * @ORM\ManyToOne(targetEntity="Semester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     * })
     */
    private $semester;



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
     * Set sesionName.
     *
     * @param string|null $sesionName
     *
     * @return ExamSession
     */
    public function setSesionName($sesionName = null)
    {
        $this->sesionName = $sesionName;

        return $this;
    }

    /**
     * Get sesionName.
     *
     * @return string|null
     */
    public function getSesionName()
    {
        return $this->sesionName;
    }

    /**
     * Set begeningdate.
     *
     * @param \DateTime|null $begeningdate
     *
     * @return ExamSession
     */
    public function setBegeningdate($begeningdate = null)
    {
        $this->begeningdate = $begeningdate;

        return $this;
    }

    /**
     * Get begeningdate.
     *
     * @return \DateTime|null
     */
    public function getBegeningdate()
    {
        return $this->begeningdate;
    }

    /**
     * Set endingdate.
     *
     * @param \DateTime|null $endingdate
     *
     * @return ExamSession
     */
    public function setEndingdate($endingdate = null)
    {
        $this->endingdate = $endingdate;

        return $this;
    }

    /**
     * Get endingdate.
     *
     * @return \DateTime|null
     */
    public function getEndingdate()
    {
        return $this->endingdate;
    }

    /**
     * Set semester.
     *
     * @param \Semester|null $semester
     *
     * @return ExamSession
     */
    public function setSemester(\Semester $semester = null)
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester.
     *
     * @return \Semester|null
     */
    public function getSemester()
    {
        return $this->semester;
    }
}
