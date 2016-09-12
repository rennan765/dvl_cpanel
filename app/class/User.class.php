<?php

class User{
    private $id;
    private $email;
    private $pass;
    private $type; /* THIS ATTRIBUTE VARIATES ACCORDING THE CLIENT. THE DEFAULT VALUE IS 0 FOR THE SYSTEM ADMINISTRATOR. SMALLER THE VALUE, GREATTER THE IMPORTANCE. */
    private $active;
    
    public function setId($id){
        $this->id = $id;
    }
    public function getId(){
        return $this->id;
    }
    
    public function setEmail($email){
        $this->email = $email;
    }
    public function getEmail(){
        return $this->email;
    }
    
    public function setPass($pass){
        $this->pass = $pass;
    }
    public function getPass(){
        return $this->pass;
    }
    
    public function setType($type){
        $this->type = $type;
    }
    public function getType(){
        return $this->type;
    }
    
    public function setActive($active){
        $this->active = $active;
    }
    public function getActive(){
        return $this->active;
    }
    
    public function __construct($id, $email, $pass, $type, $active) {
        $this->id = $id;
        $this->email = $email;
        $this->pass = $pass;
        $this->type = $type;
        $this->active = $active;
    }
    
}