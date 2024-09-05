<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StudentAdminRegistration
 *
 * @ORM\Table(name="student_admin_registration", indexes={@ORM\Index(name="fk_student_admin_registration_admin_registration1_idx", columns={"admin_registration_id"}), @ORM\Index(name="fk_student_admin_registration_student1_idx", columns={"student_id"})})
 * @ORM\Entity
 */
class StudentAdminRegistration
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
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @var \AdminRegistration
     *
     * @ORM\ManyToOne(targetEntity="AdminRegistration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_registration_id", referencedColumnName="id")
     * })
     */
    private $adminRegistration;

    /**
     * @var \Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;



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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return StudentAdminRegistration
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return StudentAdminRegistration
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set adminRegistration
     *
     * @param \AdminRegistration $adminRegistration
     *
     * @return StudentAdminRegistration
     */
    public function setAdminRegistration(\AdminRegistration $adminRegistration = null)
    {
        $this->adminRegistration = $adminRegistration;

        return $this;
    }

    /**
     * Get adminRegistration
     *
     * @return \AdminRegistration
     */
    public function getAdminRegistration()
    {
        return $this->adminRegistration;
    }

    /**
     * Set student
     *
     * @param \Student $student
     *
     * @return StudentAdminRegistration
     */
    public function setStudent(\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
