<?php

namespace Application\Entity;


use Application\Entity\ClassOfStudy;
use Application\Entity\Degree;
use Application\Entity\TrainingCurriculum;

use Doctrine\ORM\Mapping as ORM;

/**
 * DegreeHasClassOfStudy
 *
 * @ORM\Table(name="degree_has_class_of_study", indexes={@ORM\Index(name="fk_degree_has_class_of_study_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_degree_has_class_of_study_degree1_idx", columns={"degree_id"}), @ORM\Index(name="fk_degree_has_class_of_study_training_curriculum1_idx", columns={"training_curriculum_id"})})
 * @ORM\Entity
 */
class DegreeHasClassOfStudy
{
    /**
     * @var ClassOfStudy
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
     * @var Degree
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Degree")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_id", referencedColumnName="id")
     * })
     */
    private $degree;

    /**
     * @var TrainingCurriculum
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TrainingCurriculum")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="training_curriculum_id", referencedColumnName="id")
     * })
     */
    private $trainingCurriculum;



    /**
     * Set classOfStudy.
     *
     * @param ClassOfStudy $classOfStudy
     *
     * @return DegreeHasClassOfStudy
     */
    public function setClassOfStudy(ClassOfStudy $classOfStudy)
    {
        $this->classOfStudy = $classOfStudy;
    
        return $this;
    }

    /**
     * Get classOfStudy.
     *
     * @return ClassOfStudy
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }

    /**
     * Set degree.
     *
     * @param Degree $degree
     *
     * @return DegreeHasClassOfStudy
     */
    public function setDegree(Degree $degree)
    {
        $this->degree = $degree;
    
        return $this;
    }

    /**
     * Get degree.
     *
     * @return Degree
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set trainingCurriculum.
     *
     * @param TrainingCurriculum $trainingCurriculum
     *
     * @return DegreeHasClassOfStudy
     */
    public function setTrainingCurriculum(TrainingCurriculum $trainingCurriculum)
    {
        $this->trainingCurriculum = $trainingCurriculum;
    
        return $this;
    }

    /**
     * Get trainingCurriculum.
     *
     * @return TrainingCurriculum
     */
    public function getTrainingCurriculum()
    {
        return $this->trainingCurriculum;
    }
}
