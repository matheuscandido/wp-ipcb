<?php

require_once get_template_directory() . '/includes/utils.php';

function customizer_default_value( $section, $group, $field ){

    $id = customizer_get_id($section, $group, $field);

    switch ($id) {
        // case customizer_get_id("my-section", "header", "title"):
        //     return "Default value!";
        //     break;

        default:
            return $id;
    }   


}

?>