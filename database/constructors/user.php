<?php
class user{
    private $userId;
    private $email;
    private $password;
    private $type;

    public function __construct($userId, $email, $password, $type){
        $this -> userId = $userId;
        $this -> email = $email;
        $this -> password = $password;
        $this -> type = $type;
    }
    
    public function getUserId(){
        return $this -> userId;
    }
    
    public function setUserId($userId){
        $this -> userId = $userId;
    }
    
    public function getEmail(){
        return $this -> email;
    }
    
    public function setEmail($email){
        $this -> email = $email;
    }
    
    public function getPassword(){
        return $this -> password;
    }
    
    public function setPassword($password){
        $this -> password = $password;
    }
    
    public function getType(){
        return $this -> type;
    }
    
    public function setType($type){
        $this -> type = $type;
    }
}


?>