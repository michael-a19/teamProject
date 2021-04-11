<?php
class Event
{
    public $desc;
    public $start_time;
    public $end_time;
    public $date;
    public $subject;
    public $event_type;
    public $scheduled_event_id;

    function __construct($desc, $start_time, $end_time, $date, $subject, $event_type, $scheduled_event_id)
    {
        $this->desc = $desc;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->date = $date;
        $this->subject = $subject;
        $this->event_type = $event_type;
        $this->scheduled_event_id = $scheduled_event_id;
    }
}
?>
