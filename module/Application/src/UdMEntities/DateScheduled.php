<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * DateScheduled
 *
 * @ORM\Table(name="date_scheduled")
 * @ORM\Entity
 */
class DateScheduled
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $date;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="start_time_scheduled", type="time", nullable=true)
     */
    private $startTimeScheduled;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end_time_scheduled", type="time", nullable=true)
     */
    private $endTimeScheduled;



    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set startTimeScheduled.
     *
     * @param \DateTime|null $startTimeScheduled
     *
     * @return DateScheduled
     */
    public function setStartTimeScheduled($startTimeScheduled = null)
    {
        $this->startTimeScheduled = $startTimeScheduled;
    
        return $this;
    }

    /**
     * Get startTimeScheduled.
     *
     * @return \DateTime|null
     */
    public function getStartTimeScheduled()
    {
        return $this->startTimeScheduled;
    }

    /**
     * Set endTimeScheduled.
     *
     * @param \DateTime|null $endTimeScheduled
     *
     * @return DateScheduled
     */
    public function setEndTimeScheduled($endTimeScheduled = null)
    {
        $this->endTimeScheduled = $endTimeScheduled;
    
        return $this;
    }

    /**
     * Get endTimeScheduled.
     *
     * @return \DateTime|null
     */
    public function getEndTimeScheduled()
    {
        return $this->endTimeScheduled;
    }
}
