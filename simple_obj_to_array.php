<?php

// There are number of ways to read content from xml file

// Can simply load xml file using simplexml lib, it will create xml object.
$xml = simplexml_load_file("input/input.xml");

// 1. Now convert xml object to Jason object, and use that object to display or process.
$json= json_encode((array)$xml);
print_r($json);

// 2. Or we can convert xml object to an array, and use that array to display using foreach().
$array = json_decode(json_encode((array)$xml), TRUE);
print_r($array);

// 3. Or create custom function to convert xml object to an array, For example.
function xml2array($xml, $out = array())
{
    foreach ((array) $xml as $index => $node)
    {
        $out[$index] = (is_object($node)) ? xml2array($node) : $node;
    }
    return $out;
}
print_r(xml2array($xml));

// 4. Have another custom fuction to convert xml object to an array.
function xml2array_1($xml)
{
    $array = array();

    foreach ($xml as $element)
    {
        $tag = $element->getName();
        $e = get_object_vars($element);
        if (!empty($e))
        {
            $array[$tag] = $element instanceof SimpleXMLElement ? xml2array($element) : $e;
        }
        else
        {
            $array[$tag] = trim($element);
        }
    }

    return $array;
}
print_r(xml2array_1($xml));


##########

// Now if you want to save this array to xml.
// $xml = new SimpleXMLElement('<SiteConfidence/>');
// array_walk_recursive($array, array ($xml, 'addChild'));
// var_dump($xml->asXML());



##########
// initializing or creating array
$student_info = $array;

// creating object of SimpleXMLElement
$xml_student_info = new SimpleXMLElement("<?xml version=\"1.0\"?><SiteConfidence></SiteConfidence>");

// function call to convert array to xml
array_to_xml($student_info,$xml_student_info);

//saving generated xml file
print $xml_student_info->asXML();


// function defination to convert array to xml
function array_to_xml($student_info, &$xml_student_info) {
    foreach($student_info as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_student_info->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                $subnode = $xml_student_info->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }
        else {
            $xml_student_info->addChild("$key",htmlspecialchars("$value"));
        }
    }
}
