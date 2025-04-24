<?php
Class User {
    //proerties
    public string $username;
    public  int $ID;

    public string $account_ID;
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
    function getAccountID() { // Getter for account_ID
        return $this->account_ID;
    }

    function setAccountID($account_ID) { // Setter for account_ID
        $this->account_ID = $account_ID;
    }
}



?>