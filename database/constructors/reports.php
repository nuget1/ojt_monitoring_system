<?php
class user
{
    private $reportId;
    private $studentId;
    private $weekNum;
    private $hoursWorked;
    private $reportFile;
    private $submittedAt;
    private $comment;
    private $verified;

    public function __construct($reportId, $studentId, $weekNum, $hoursWorked, $reportFile, $submittedAt, $comment, $verified)
    {
        $this->reportId = $reportId;
        $this->studentId = $studentId;
        $this->weekNum = $weekNum;
        $this->hoursWorked = $hoursWorked;
        $this->reportFile = $reportFile;
        $this->submittedAt = $submittedAt;
        $this->comment = $comment;
        $this->verified = $verified;
    }

    public function getReportId(){
        return $this->reportId;
    }

    public function setReportId($reportId){
        return $this->reportId = $reportId;
    }

    public function getStudent(){
        return $this->studentId;
    }

    public function setStudent($student){
        return $this->studentId = $student;
    }

    public function getWeekNum(){
        return $this->weekNum;
    }

    public function setWeekNum($weekNum){
        return $this->weekNum = $weekNum;
    }

    public function getHoursWorked(){
        return $this->hoursWorked;
    }

    public function setHoursWorked($hoursWorked){
        return $this->hoursWorked = $hoursWorked;
    }

    public function getReportFile(){
        return $this->reportFile;
    }

    public function setReportFile($reportFile){
        return $this->reportFile = $reportFile;
    }

    public function getSubmittedAt(){
        return $this->submittedAt;
    }

    public function setSubmittedAt($submittedAt){
        return $this->submittedAt = $submittedAt;
    }

    public function getComment(){
        return $this->comment;
    }

    public function setComment($comment){
        return $this->comment = $comment;;
    }

    public function getVerified(){
        return $this->verified;
    }

    public function setVerified($verified){
        return $this->verified = $verified;;
    }
}
