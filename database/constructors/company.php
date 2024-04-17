<?php
class user
{
    private $companyId;
    private $companyName;
    private $companyAddress;

    public function __construct($companyId, $companyName, $companyAddress){
        $this->companyId = $companyId;
        $this->companyName = $companyName;
        $this->companyAddress = $companyAddress;
    }

    public function getCompanyId(){
        return $this->companyId;
    }

    public function setCompanyId($companyId){
        return $this->companyId = $companyId;
    }

    public function getCompanyName(){
        return $this->companyName;
    }

    public function setCompanyName($companyName){
        return $this->companyName = $companyName;
    }

    public function getCompanyAddress(){
        return $this->companyAddress;
    }

    public function setCompanyAddress($companyAddress){
        return $this->companyAddress = $companyAddress;
    }
}
