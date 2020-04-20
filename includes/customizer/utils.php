<?php

require_once(get_template_directory() . '/includes/customizer/default.php');

function customizer_get_value($section, $group, $field){
    echo get_theme_mod(customizer_get_id($section, $group, $field), customizer_default_value($section, $group, $field));
}

function customizer_get_id($section, $group, $field){
    return $section . '__' .  $group . "__" . $field;   // Example: "home__header__title" ($section__$group__$field)
}

function customizer_text_field($wp_customize, $section, $group, $field, $label){

    $text_field_id =  customizer_get_id($section, $group, $field);
    $text_field_default = customizer_default_value($section, $group, $field);

    $wp_customize->add_setting($text_field_id, array(
        'default'           =>          $text_field_default,
        'type'              =>          'theme_mod'
    ));
    
    $wp_customize->add_control($text_field_id, array(
        'label'             =>          $label,
        'section'           =>          $section,
        'priority'          =>          1
    ));
}

function customizer_section($wp_customize, $section, $label, $description){
    $wp_customize->add_section($section, array(
        'title'             =>      $label,
        'description'       =>      $description,
        'priority'          =>      130 
    ));
}
