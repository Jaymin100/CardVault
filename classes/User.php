<?php
Class User {
    //proerties
    public string $username;
    public  int $ID;
    //Methods
    function getUser(){ // getter for user
        return $this->username;
    }
    function setUser($username){ //setter for user
        $this->username = $username;
    }
    function getID(){ // getter for id
        return $this->ID;
    }
    function setID($ID){ // setter for id
        $this->ID = $ID;
    }
}



?>