<?php
/**
 * Created by IntelliJ IDEA.
 * User: jochen
 * Date: 24/6/18
 * Time: 8:11 PM
 */

foreach (glob("classes/*.php") as $filename)
{
    include_once $filename;
}


$sampleClass = new SampleClassA("show string", "2000-01-01", array("wow"));

echo "Sample class: " . var_dump($sampleClass) . "<p/>";

echo "myString ==> " . $sampleClass->getMyString() . "<p/>";
echo "myDate ==> " . $sampleClass->getMyDate() . "<p/>";
echo "myArray ==> " . json_encode($sampleClass->getMyArray()) . "<p/>";

?>