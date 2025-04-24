<?php

class Card
{
    private $Question;
    private $Answer; // Fixed typo

    public function __construct($Question, $Answer)
    {
        $this->Question = $Question;
        $this->Answer = $Answer;
    }
   

    public function getQuestion()
    {
        return $this->Question;
    }

    public function setQuestion($Question)
    {
        $this->Question = $Question;
    }

    public function getAnswer() // Fixed typo
    {
        return $this->Answer;
    }

    public function setAnswer($Answer) // Fixed typo
    {
        $this->Answer = $Answer;
    }
}
?>