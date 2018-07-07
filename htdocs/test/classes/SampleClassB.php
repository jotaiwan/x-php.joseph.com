<?php
/**
 * Created by IntelliJ IDEA.
 * User: jochen
 * Date: 3/7/18
 * Time: 7:17 AM
 */


class SampleClassB {
    // string type
    private $myStringB;

    // date type
    private $myDateB;

    // array type
    private $myArrayB;

    public function __construct ( $myStringB, $myDateB, $myArrayB ) {
        $this->myStringB = $myStringB;
        $this->myDateB = $myDateB;
        $this->myArrayB = $myArrayB;
    }


    public function myFunctionB() {
        return "call from SampleClassA";
    }

    // setter: myString
    public function setMyStringB($myStringB){
        $this->myStringB = $myStringB;
    }

    // getter: myString
    public function getMyStringB(){
        return $this->myStringB;
    }


    // setter: myDate
    public function setMyDateB($myDateB){
        $this->myDateB = $myDateB;
    }


    // getter: myDate
    public function getMyDateB(){
        return $this->myDateB;
    }

    // setter: myArray
    public function setMyArrayB($myArrayB){
        $this->myArrayB = $myArrayB;
    }

    // getter: myString
    public function getMyArrayB(){
        return $this->myArrayB;
    }
}
