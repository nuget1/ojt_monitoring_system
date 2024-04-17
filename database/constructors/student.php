<?php
class user
{
    private $studentId;
    private $userId;
    private $firstName;
    private $lastName;
    private $course;
    private $classCode;

    public function __construct($studentId, $userId, $firstName, $lastName, $course, $classCode)
    {
        $this->studentId = $studentId;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->course = $course;
        $this->classCode = $classCode;
    }

    public function getStudent(){
        return $this->studentId;
    }

    public function setStudent($student){
        return $this->studentId = $student;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setUserId($userId){
        return $this->userId = $userId;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function setFirstName($firstName){
        return $this->firstName = $firstName;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function setLastName($lastName){
        return $this->lastName = $lastName;
    }

    public function getCourse(){
        return $this->course;
    }

    public function setCourse($course){
        return $this->course = $course;
    }

    public function getClassCode(){
        return $this->classCode;
    }

    public function setClassCode($classCode){
        return $this->classCode = $classCode;;
    }
}
