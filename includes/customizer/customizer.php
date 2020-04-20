<?php

require get_template_directory() . '/includes/customizer/customizer-example.php';

function customizer_theme($wp_customize){
    // customizer_example($wp_customize);
}

add_action('customize_register', 'customizer_theme');