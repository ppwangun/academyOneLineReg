<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ClassOfStudyHasAcademicYear
 *
 * @ORM\Table(name="class_of_study_has_academic_year", indexes={@ORM\Index(name="fk_class_of_study_has_academic_year_class_of_study_has_acad_idx", columns={"class_of_study_has_academic_year_class_of_study_id", "class_of_study_has_academic_year_academic_year_id"}), @ORM\Index(name="fk_class_of_study_has_academic_year_academic_year1_idx", columns={"academic_year_id"}), @ORM\Index(name="fk_class_of_study_has_academic_year_class_of_study1_idx", columns={"class_of_study_id"})})
 * @ORM\Entity
 */
class ClassOfStudyHasAcademicYear
{
    /**
     * @var int|null
     *
     * @ORM\Column(name="schoolCertificateGenerationStatus", type="integer", nullable=true)
     */
    private $schoolcertificategenerationstatus = '0';

    /**
     * @var \AcademicYear
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;

    /**
     * @var \ClassOfStudy
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;

    /**
     * @var \ClassOfStudyHasAcademicYear
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudyHasAcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_has_academic_year_class_of_study_id", referencedColumnName="class_of_study_id"),
     *   @ORM\JoinColumn(name="class_of_study_has_academic_year_academic_year_id", referencedColumnName="academic_year_id")
     * })
     */
    private $classOfStudyHasAcademicYearClassOfStudy;



    /**
     * Set schoolcertificategenerationstatus.
     *
     * @param int|null $schoolcertificategenerationstatus
     *
     * @return ClassOfStudyHasAcademicYear
     */
    public function setSchoolcertificategenerationstatus($schoolcertificategenerationstatus = null)
    {
        $this->schoolcertificategenerationstatus = $schoolcertificategenerationstatus;

        return $this;
    }

    /**
     * Get schoolcertificategenerationstatus.
     *
     * @return int|null
     */
    public function getSchoolcertificategenerationstatus()
    {
        return $this->schoolcertificategenerationstatus;
    }

    /**
     * Set classOfStudy.
     *
     * @param \ClassOfStudy $classOfStudy
     *
     * @return ClassOfStudyHasAcademicYear
     */
    public function setClassOfStudy(\ClassOfStudy $classOfStudy)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy.
     *
     * @return \ClassOfStudy
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }

    /**
     * Set classOfStudyHasAcademicYearClassOfStudy.
     *
     * @param \ClassOfStudyHasAcademicYear|null $classOfStudyHasAcademicYearClassOfStudy
     *
     * @return ClassOfStudyHasAcademicYear
     */
    public function setClassOfStudyHasAcademicYearClassOfStudy(\ClassOfStudyHasAcademicYear $classOfStudyHasAcademicYearClassOfStudy = null)
    {
        $this->classOfStudyHasAcademicYearClassOfStudy = $classOfStudyHasAcademicYearClassOfStudy;

        return $this;
    }

    /**
     * Get classOfStudyHasAcademicYearClassOfStudy.
     *
     * @return \ClassOfStudyHasAcademicYear|null
     */
    public function getClassOfStudyHasAcademicYearClassOfStudy()
    {
        return $this->classOfStudyHasAcademicYearClassOfStudy;
    }

    /**
     * Set academicYear.
     *
     * @param \AcademicYear $academicYear
     *
     * @return ClassOfStudyHasAcademicYear
     */
    public function setAcademicYear(\AcademicYear $academicYear)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear.
     *
     * @return \AcademicYear
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }
}
