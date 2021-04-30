<?php


class Deadline
{
    public $deadline_id;
    public $type;
    public $desc;
    public $subject;
    public $date;
    public $time;

    function __construct($deadline_id, $type, $desc, $subject, $date, $time)
    {
        $this->deadline_id = $deadline_id;
        $this->type = $type;
        $this->desc = $desc;
        $this->subject = $subject;
        $this->date = $date;
        $this->time = $time;
    }
}