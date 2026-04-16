<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ClassOfStudyHasExamSession
 *
 * @ORM\Table(name="class_of_study_has_exam_session", indexes={@ORM\Index(name="fk_class_of_study_has_exam_session_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_class_of_study_has_exam_session_exam_session1_idx", columns={"exam_session_id"})})
 * @ORM\Entity
 */
class ClassOfStudyHasExamSession
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
     * @var bool|null
     *
     * @ORM\Column(name="session_status", type="boolean", nullable=true)
     */
    private $sessionStatus = '0';

    /**
     * @var \ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;

    /**
     * @var \ExamSession
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
     * Set sessionStatus.
     *
     * @param bool|null $sessionStatus
     *
     * @return ClassOfStudyHasExamSession
     */
    public function setSessionStatus($sessionStatus = null)
    {
        $this->sessionStatus = $sessionStatus;

        return $this;
    }

    /**
     * Get sessionStatus.
     *
     * @return bool|null
     */
    public function getSessionStatus()
    {
        return $this->sessionStatus;
    }

    /**
     * Set classOfStudy.
     *
     * @param \ClassOfStudy|null $classOfStudy
     *
     * @return ClassOfStudyHasExamSession
     */
    public function setClassOfStudy(\ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy.
     *
     * @return \ClassOfStudy|null
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }

    /**
     * Set examSession.
     *
     * @param \ExamSession|null $examSession
     *
     * @return ClassOfStudyHasExamSession
     */
    public function setExamSession(\ExamSession $examSession = null)
    {
        $this->examSession = $examSession;

        return $this;
    }

    /**
     * Get examSession.
     *
     * @return \ExamSession|null
     */
    public function getExamSession()
    {
        return $this->examSession;
    }
}
