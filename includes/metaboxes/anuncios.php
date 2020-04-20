<?php
/***
 * Importar esta página no functions.php
 */
require_once get_template_directory() . '/includes/metaboxes/generator.php';

function add_modelo_meta_box()
{
  add_meta_box(
    'anuncio_meta_box',          // Metabox ID
    'Dados do Anúncio',                   // Título
    'modelo_meta_box_html',     // Callback do formulário
    'anuncios'                   // Post type
  );
}
add_action('add_meta_boxes', 'add_modelo_meta_box');

function modelo_meta_box_html($post)
{
    metabox_styling();
    metabox_begin();
    
    metabox_title("Dados Gerais");
    metabox_text_field("ANUNCIO_TITLE", "Nome do comércio/serviço:");
    metabox_text_field("ANUNCIO_CATEGORIA", "Categoria profissional:");
    metabox_text_field("ANUNCIO_ADDRESS", "Endereço:");
    metabox_text_field("ANUNCIO_WHATSAPP", "WhatsApp:");
    metabox_text_field("ANUNCIO_TELEFONE", "Telefone Fixo:");
    metabox_text_field("ANUNCIO_INSTAGRAM", "Instagram:");
    metabox_textarea_field("ANUNCIO_OBS", "Observações:");
    metabox_image_field("ANUNCIO_LOGO", "Logo/marca:");
        
    metabox_end();
}

function anuncios_meta_box_save($post_id)
{
    if(isset($_POST) && !empty($_POST))
    {
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        $fields_list = [
            "ANUNCIO_TITLE",
            "ANUNCIO_CATEGORIA",
            "ANUNCIO_ADDRESS",
            "ANUNCIO_WHATSAPP",
            "ANUNCIO_TELEFONE",
            "ANUNCIO_INSTAGRAM",
            "ANUNCIO_OBS",
            "ANUNCIO_LOGO"
        ];

        metabox_save($fields_list, $post_id);
    }
    
    return $post_id;
}
add_action('save_post_anuncios', 'anuncios_meta_box_save', 100, 1);