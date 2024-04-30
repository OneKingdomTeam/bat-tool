<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// pass the application ID and it will spit out the SVG string with
// updated fill colors based on the answers
function bat_get_colored_wheel(int $application_id) {

    $answers = (array) get_post_meta( $application_id, 'answers_data', true);

    $wheel_svg = file_get_contents(bat_get_env()['root_dir_path'] . 'public/media/new-circle-core.svg', false);
    $doc = new DOMDocument();
    $doc->loadXML($wheel_svg);
    $root_element = $doc->documentElement;

    $xpath = new DOMXPath($doc);
    $xpath->registerNamespace('svg','http://www.w3.org/2000/svg');

    $number_patter ="/[0-9]{1,2}/";

    $output = [];
    foreach( $answers as $key => $value ){

        preg_match($number_patter, $key, $beam_number);
        $xpath_beam_classname = 'beam-' . $beam_number[0];
        $xpath_segment_classname = 'segment-1';    

        $values_arr = (array) $value->segment1->answers;
        foreach( $values_arr as $answer_name => $answer_value){
            preg_match($number_patter, $answer_name, $answer_number);
            $xpath_answer_classname = 'subsegment-' . $answer_number[0];

            $output[$xpath_beam_classname][$xpath_segment_classname][$xpath_answer_classname] = $answer_value;
            $query = "//svg:g[contains(@class, '$xpath_beam_classname')]//svg:g[contains(@class, '$xpath_segment_classname')]//svg:path[contains(@class, '$xpath_answer_classname')]";
            $elements = $xpath->query($query);        

            if($elements->length > 0){
                $fill_color = '';
                if($answer_value === 0 ){
                    $fill_color = '#f11627';
                } elseif ($answer_value === 50){
                    $fill_color = '#ff9726';
                } elseif ($answer_value === 100){
                    $fill_color = '#3eb05d';
                } else {
                    $fill_color = '#1b49c1';
                }
                $elements[0]->setAttribute('fill', $fill_color);
            }

        }
    }   
    
    $processed_wheel = $doc->saveXML($root_element);

    return $processed_wheel;

}
