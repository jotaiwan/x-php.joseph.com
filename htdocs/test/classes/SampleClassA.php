<?php
/**
 * Created by IntelliJ IDEA.
 * User: jochen
 * Date: 24/6/18
 * Time: 7:55 PM
 */

class SampleClassA {

    // Inject
    private $sampleClassB;

    // string type
    private $myString;

    // date type
    private $myDate;

    // array type
    private $myArray;

    public function __construct ( $myString, $myDate, $myArray ) {
        $this->myString = $myString;
        $this->myDate = $myDate;
        $this->myArray = $myArray;

        $this->sampleClassB = new SampleClassB($this);
    }

    public function myFunctionA() {
         // call SampleClassB function
        $value = $this->sampleClassB->myFunctionB();
    }

    public function getSampleClassB() {
        return $this->sampleClassB;
    }


    // setter: myString
    public function setMyString($myString){
        $this->myString = $myString;
    }

    // getter: myString
    public function getMyString(){
        return $this->myString;
    }


    // setter: myDate
    public function setMyDate($myDate){
        $this->myDate = $myDate;
    }


    // getter: myDate
    public function getMyDate(){
        return $this->myDate;
    }

    // setter: myArray
    public function setMyArray($myArray){
        $this->myArray = $myArray;
    }

    // getter: myString
    public function getMyArray(){
        return $this->myArray;
    }

}

?>