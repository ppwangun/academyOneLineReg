<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * TeacherPaymentRate
 *
 * @ORM\Table(name="teacher_payment_rate")
 * @ORM\Entity
 */
class TeacherPaymentRate
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
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_default_payment", type="boolean", nullable=true)
     */
    private $isDefaultPayment = '0';



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
     * Set description.
     *
     * @param string|null $description
     *
     * @return TeacherPaymentRate
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isDefaultPayment.
     *
     * @param bool|null $isDefaultPayment
     *
     * @return TeacherPaymentRate
     */
    public function setIsDefaultPayment($isDefaultPayment = 0)
    {
        $this->isDefaultPayment = $isDefaultPayment;

        return $this;
    }

    /**
     * Get isDefaultPayment.
     *
     * @return bool|null
     */
    public function getIsDefaultPayment()
    {
        return $this->isDefaultPayment;
    }
}

