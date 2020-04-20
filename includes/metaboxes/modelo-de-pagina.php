<?php
/***
 * Importar esta página no functions.php
 */
require_once get_template_directory() . '/includes/metaboxes/generator.php';

function add_modelo_meta_box()
{
  add_meta_box(
    'modelo_meta_box',          // Metabox ID
    'modelo',                   // Título
    'modelo_meta_box_html',     // Callback do formulário
    'modelo'                   // Post type
  );
}
add_action('add_meta_boxes', 'add_modelo_meta_box');

function modelo_meta_box_html($post)
{
    metabox_styling();
    metabox_begin();
    
    metabox_title("Geral");
        
    metabox_end();
}

function modelo_meta_box_save($post_id)
{
    if(isset($_POST) && !empty($_POST))
    {
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        $fields_list = [
            "EXEMPLO_DE_CHAVE",
        ];

        metabox_save($fields_list, $post_id);
    }
    
    return $post_id;
}
add_action('save_post_modelo', 'modelo_meta_box_save', 100, 1);