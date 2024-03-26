<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * TimeSlot
 *
 * @ORM\Table(name="time_slot")
 * @ORM\Entity
 */
class TimeSlot
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="stating_time", type="time", nullable=true)
     */
    private $statingTime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ending_time", type="time", nullable=true)
     */
    private $endingTime;



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
     * Set statingTime.
     *
     * @param \DateTime|null $statingTime
     *
     * @return TimeSlot
     */
    public function setStatingTime($statingTime = null)
    {
        $this->statingTime = $statingTime;
    
        return $this;
    }

    /**
     * Get statingTime.
     *
     * @return \DateTime|null
     */
    public function getStatingTime()
    {
        return $this->statingTime;
    }

    /**
     * Set endingTime.
     *
     * @param \DateTime|null $endingTime
     *
     * @return TimeSlot
     */
    public function setEndingTime($endingTime = null)
    {
        $this->endingTime = $endingTime;
    
        return $this;
    }

    /**
     * Get endingTime.
     *
     * @return \DateTime|null
     */
    public function getEndingTime()
    {
        return $this->endingTime;
    }
}
