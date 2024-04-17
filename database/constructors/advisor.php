<?php
class user
{
    private $advisorId;
    private $userId;
    private $firstName;
    private $lastName;

    public function __construct($advisorId, $userId, $firstName, $lastName){
        $this->advisorId = $advisorId;
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getadvisorId(){
        return $this->advisorId;
    }

    public function setadvisorId($advisorId){
        return $this->advisorId = $advisorId;
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
}
