<?php
class user
{
    private $studentId;
    private $companyId;
    private $advisorId;
    private $dateStarted;
    private $dateEnded;

    public function __construct($studentId, $companyId, $advisorId, $dateStarted, $dateEnded)
    {
        $this->studentId = $studentId;
        $this->companyId = $companyId;
        $this->advisorId = $advisorId;
        $this->dateStarted = $dateStarted;
        $this->dateEnded = $dateEnded;
    }

    public function getStudent(){
        return $this->studentId;
    }

    public function setStudent($student){
        return $this->studentId = $student;
    }

    public function getCompanyId(){
        return $this->companyId;
    }

    public function setCompanyId($companyId){
        return $this->companyId = $companyId;
    }

    public function getAdvisorId(){
        return $this->advisorId;
    }

    public function setAdvisorId($advisorId){
        return $this->advisorId = $advisorId;
    }

    public function getDateStarted(){
        return $this->dateStarted;
    }

    public function setDateStarted($dateStarted){
        return $this->dateStarted = $dateStarted;
    }

    public function getDateEnded(){
        return $this->dateEnded;
    }

    public function setDateEnded($dateEnded){
        return $this->dateEnded = $dateEnded;
    }
}
