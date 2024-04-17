<?php
class user
{
    private $requirementId;
    private $studentId;
    private $dateOdSubmission;
    private $completed;

    public function __construct($studentId, $requirementId, $dateOdSubmission, $completed)
    {
        $this->studentId = $studentId;
        $this->requirementId = $requirementId;
        $this->dateOdSubmission = $dateOdSubmission;
        $this->completed = $completed;
    }

    public function getStudent(){
        return $this->studentId;
    }

    public function setStudent($student){
        return $this->studentId = $student;
    }

    public function getRequirementId(){
        return $this->requirementId;
    }

    public function setRequirementId($requirementId){
        return $this->requirementId = $requirementId;
    }

    public function getDateOdSubmission(){
        return $this->dateOdSubmission;
    }

    public function setDateOdSubmission($dateOdSubmission){
        return $this->dateOdSubmission = $dateOdSubmission;
    }

    public function getCompleted(){
        return $this->completed;
    }

    public function setCompleted($completed){
        return $this->completed = $completed;
    }
}
