<?php

// Meta Components

function metabox_styling()
{
    require_once get_template_directory() . '/includes/metaboxes/styling.php';
}

function metabox_begin()
{
    echo '<div class="metaboxes">';
}

function metabox_end()
{
    echo '</div>';
}

// Basic Components

function metabox_title($section)
{
    echo "<h3 class=\"metabox__title\">$section</h3>";
}

function metabox_save($fields_list, $post_id)
{
    foreach($fields_list as $key)
    {  
        update_post_meta($post_id, $key, $_POST[$key]);
    }
}

function metabox_text_field($name, $label)
{
    ?>
    <div class="metabox metabox__row-2-10">
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <input type="text" name="<?php echo $name; ?>" value="<?php echo esc_attr(get_post_meta(get_the_ID(), $name, true)) ?>"/>
    </div>
  <?php
}

function metabox_image_field($name, $label)
{
    wp_enqueue_script('jquery');
    ?>
    <script>
        jQuery(document).ready(function ($) {
            jQuery(document).on("click", ".<?php echo $name; ?>", function (e) {
                e.preventDefault();
                var $button = jQuery(this);
                console.log("executou");
            
                // Create the media frame.
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Selecione ou arraste uma imagem para cá',
                    library: { // remove these to show all
                        type: 'image' // specific mime
                    },
                    button: {
                        text: 'Select'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });
            
                // When an image is selected, run a callback.
                file_frame.on('select', function () {
                    // We set multiple to false so only get one image from the uploader
            
                    var attachment = file_frame.state().get('selection').first().toJSON();
            
                    $button.siblings('input').val(attachment.url);
                    $button.siblings('input').trigger('change');
            
                });
        
                // Finally, open the modal
                file_frame.open();
            });
        });
    </script>

    <?php 
        $current = get_post_meta(get_the_ID(), $name, true);
    ?>

    <div class="metabox metabox__row-2-8-2">
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <input class="widefat" id="<?php echo ""; ?>"
            placeholder="URL da Imagem"
            name="<?php echo $name; ?>" type="text"
            value="<?php echo $current ?>" />
        <button class="<?php echo $name; ?> button button-primary">Upload</button>
    </div>    
    <?php
}

function metabox_textarea_field($name, $label)
{
    ?>
    <div class="metabox metabox__row-2-10">
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <textarea rows="4" name="<?php echo $name; ?>"><?php echo esc_attr(get_post_meta(get_the_ID(), $name, true)) ?></textarea>
    </div>
    <?php 
}

/**
 * @param array $options Lista de opções de tipo array($value => $text).
 */
function metabox_select_field($name, $label, $options)
{
    $current = get_post_meta(get_the_ID(), $name, true);
    ?>
    <div class="metabox metabox__row-2-10">
        <label for="$name"><?php echo $label; ?></label>
        <select name="<?php echo $name; ?>">
            <?php foreach($options as $option):?>
                <option value="<?php echo esc_attr($option["value"]); ?>" <?php if($current == $option["value"]) echo "selected"; ?>><?php echo esc_attr($option["text"]); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}

function metabox_date_field($name, $label)
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('#date').datepicker({
                    dateFormat: 'dd-mm-yy'
                });
            });
        </script>
    <div class="metabox metabox__row-2-10">
        <label for="<?php echo $name; ?>"><?php echo $label;?></label>
        <input value="<?php echo esc_attr(get_post_meta(get_the_ID(), $name, true)) ?>" id="date" name="<?php echo $name; ?>" placeholder="Selecionar data..." />
    </div>
    <?php

}

function metabox_html($field_name, $title)
{
    ?>
    <h3><?php echo $title;?></h3>
    <?php
    $content = get_post_meta(get_the_ID(), $field_name, true);
    wp_editor($content, $field_name, ["media_buttons" => false]);
}

// Multi Components

function metabox_multi_text($name, $label)
{
    wp_enqueue_script('jquery');
    ?>
    <script>
        jQuery(document).ready(function() {
            jQuery("#<?php echo $name; ?>__button").click(function(e){
                e.preventDefault();
                
                var newValue = jQuery("#<?php echo $name; ?>__input").val().replace(/"/g, '&quot;');

                if(newValue === "") return;

                var newInput = jQuery('<div class="metabox__added__item"><input type="hidden" name="<?php echo $name;?>[]"value="'+newValue+'" />' + newValue + '<button class="<?php echo $name; ?>__delete">Remover</button></div>');              
                jQuery("#<?php echo $name; ?>__added").append(newInput);
                jQuery("#<?php echo $name; ?>__input").val("");
            });

            jQuery(document).on("click", ".<?php echo $name; ?>__delete", function(e){
                e.preventDefault();
                jQuery(this).closest("div").remove();
            });
        });
    </script>
    <div class="metabox metabox__row-2-8-2">
        <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
        <input type="text" id="<?php echo $name; ?>__input">
        <button class="adicionar" id="<?php echo $name; ?>__button">+ Adicionar</button>
        
        <div id="<?php echo $name ?>__added" class="metabox__added">
            <?php
                $values = get_post_meta(get_the_ID(), $name, false);
                if(isset($values) && !empty($values[0])):
                foreach($values[0] as $value):
            ?>
                <div class="metabox__added__item"><input type="hidden" name="<?php echo $name;?>[]" value="<?php echo esc_attr($value); ?>"/><?php echo esc_attr($value); ?><button class="<?php echo $name; ?>__delete">Remover</button></div>
            <?php endforeach; endif;?>
        </div>
    </div>
    
    <?php
}

function metabox_multi_img($images_name, $label)
{
    wp_enqueue_script('jquery');
    ?>
    <script>
        jQuery(document).ready(function() {
            // adicionar novo ítem
            jQuery("#<?php echo $images_name; ?>__button").click(function(e){
                e.preventDefault();

                var newImage = jQuery("#<?php echo $images_name; ?>__field").val();

                if(newImage === "") return;

                var newOverviewFinal = jQuery(
                    '<div class="metabox__added__item">' +
                    '<div class="metabox__flex-column">' +
                    '<div><input type="hidden" name="<?php echo $images_name;?>[]" value="' + newImage + '" /><img width="100" src="' + newImage + '" /></div>' +
                    '</div>' +
                    '<button id="<?php echo $images_name; ?>__delete" >Remover</button>' +
                    '</div>'
                );

                jQuery("#<?php echo $images_name; ?>__added").append(newOverviewFinal);
                jQuery("#<?php echo $images_name; ?>__field").val("");
            });

            // apagar ítem
            jQuery(document).on("click", "#<?php echo $images_name; ?>__delete", function(e){
                e.preventDefault();
                jQuery(this).closest("div").remove();
            });

            // upload de imagem
            jQuery(document).on("click", "#<?php echo $images_name; ?>__upload", function (e) {
                e.preventDefault();
                var $button = jQuery(this);

                // Create the media frame.
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Selecione ou arraste uma imagem para cá',
                    library: { // remove these to show all
                        type: 'image' // specific mime
                    },
                    button: {
                        text: 'Select'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function () {
                    // We set multiple to false so only get one image from the uploader

                    var attachment = file_frame.state().get('selection').first().toJSON();

                    $button.siblings('input').val(attachment.url);
                    $button.siblings('input').trigger('change');

                });

                // Finally, open the modal
                file_frame.open();
            });
        });
    </script>

    <div class="metabox metabox__row-2-8-2">

        <label><?php echo $label; ?></label>
        <div class="metabox__flex-column">
            <div class="metabox__flex-row">
                <input class="widefat" id="<?php echo $images_name; ?>__field" placeholder="URL da Imagem" name="<?php echo $images_name; ?>" type="text"/>
                <button id="<?php echo $images_name; ?>__upload" class="button button-primary">Upload</button>
            </div>
        </div>
        <button class="adicionar" id="<?php echo $images_name; ?>__button">+ Adicionar</button>

        <div id="<?php echo $images_name; ?>__added" class="metabox__added">
            <?php
            $images = get_post_meta(get_the_ID(), $images_name, false);

            if($images):
                $images = $images[0];

                for($i = 0; $i < count($images); $i++): ?>
                    <div class="metabox__added__item">
                        <div class="metabox__flex-column">
                            <div><input type="hidden" name="<?php echo $images_name;?>[]" value="<?php echo $images[$i]; ?>"/><img width="100" src="<?php echo $images[$i]; ?>"></div>
                        </div>
                        <button id="<?php echo $images_name; ?>__delete">Remover</button>
                    </div>
                <?php endfor;
            endif;
            ?>
        </div>
    </div>

    <?php
}

function metabox_multi_img_text($images_name, $texts_name, $label)
{
    wp_enqueue_script('jquery');
    ?>
    <script>
        jQuery(document).ready(function() {
            // adicionar novo ítem
            jQuery("#<?php echo $texts_name; ?>__button").click(function(e){
                e.preventDefault();

                var newTitle = jQuery("#<?php echo $texts_name; ?>__field").val().replace(/"/g, '&quot;');
                var newImage = jQuery("#<?php echo $images_name; ?>__field").val();

                if(newTitle === "" || newImage === "") return;

                var newOverviewFinal = jQuery(
                    '<div class="metabox__added__item">' +
                    '<div class="metabox__flex-column">' +
                    '<div><input type="hidden" name="<?php echo $images_name;?>[]" value="' + newImage + '" /><img width="100" src="' + newImage + '" /></div>' +
                    '<h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="'+ newTitle +'"/>' + newTitle + '</h3>' +
                    '</div>' +
                    '<button id="<?php echo $texts_name; ?>__delete" >Remover</button>' +
                    '</div>'
                );

                jQuery("#overview__added").append(newOverviewFinal);
                jQuery("#<?php echo $texts_name; ?>__field").val("");
                jQuery("#<?php echo $images_name; ?>__field").val("");
            });

            // apagar ítem
            jQuery(document).on("click", "#<?php echo $texts_name; ?>__delete", function(e){
                e.preventDefault();
                jQuery(this).closest("div").remove();
            });

            // upload de imagem
            jQuery(document).on("click", "#<?php echo $images_name; ?>__upload", function (e) {
                e.preventDefault();
                var $button = jQuery(this);

                // Create the media frame.
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Selecione ou arraste uma imagem para cá',
                    library: { // remove these to show all
                        type: 'image' // specific mime
                    },
                    button: {
                        text: 'Select'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function () {
                    // We set multiple to false so only get one image from the uploader

                    var attachment = file_frame.state().get('selection').first().toJSON();

                    $button.siblings('input').val(attachment.url);
                    $button.siblings('input').trigger('change');

                });

                // Finally, open the modal
                file_frame.open();
            });
        });
    </script>

    <div class="metabox metabox__row-2-8-2">

        <label><?php echo $label; ?></label>
        <div class="metabox__flex-column">
            <input type="text" id="<?php echo $texts_name; ?>__field" placeholder="Título">
            <div class="metabox__flex-row">
                <input class="widefat" id="<?php echo $images_name; ?>__field" placeholder="URL da Imagem" name="<?php echo $images_name; ?>" type="text"/>
                <button id="<?php echo $images_name; ?>__upload" class="button button-primary">Upload</button>
            </div>
        </div>
        <button class="adicionar" id="<?php echo $texts_name; ?>__button">+ Adicionar</button>

        <div id="overview__added" class="metabox__added">
            <?php
            $texts = get_post_meta(get_the_ID(), $texts_name, false);
            $images = get_post_meta(get_the_ID(), $images_name, false);

            if($texts && $images):
                $texts = $texts[0];
                $images = $images[0];

                for($i = 0; $i < count($texts); $i++): ?>
                    <div class="metabox__added__item">
                        <div class="metabox__flex-column">
                            <div><input type="hidden" name="<?php echo $images_name;?>[]" value="<?php echo $images[$i]; ?>"/><img width="100" src="<?php echo $images[$i]; ?>"></div>
                            <h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="<?php echo esc_attr($texts[$i]); ?>"/><?php echo esc_attr($texts[$i]); ?></h3>
                        </div>
                        <button id="<?php echo $texts_name; ?>__delete">Remover</button>
                    </div>
                <?php endfor;
            endif;
            ?>
        </div>
    </div>

    <?php
}

function metabox_multi_img_text_textarea($images_name, $texts_name, $textareas_name, $label)
{
    wp_enqueue_script('jquery');
    ?>
    <script>
        jQuery(document).ready(function() {
            // adicionar novo ítem
            jQuery("#<?php echo $texts_name; ?>__button").click(function(e){
                e.preventDefault();
                
                var newTitle = jQuery("#<?php echo $texts_name; ?>__field").val().replace(/"/g, '&quot;');
                var newDescription = jQuery("#<?php echo $textareas_name; ?>__field").val().replace(/"/g, '&quot;');
                var newImage = jQuery("#<?php echo $images_name; ?>__field").val();
                console.log("chegou aqui");

                if(newTitle === "" || newDescription === "" || newImage === "") return;

                var newOverviewFinal = jQuery(
                    '<div class="metabox__added__item">' +
                        '<div class="metabox__flex-column">' +
                            '<div><input type="hidden" name="<?php echo $images_name;?>[]" value="' + newImage + '" /><img width="100" src="' + newImage + '" /></div>' +
                            '<h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="'+ newTitle +'"/>' + newTitle + '</h3>' +
                            '<p><input type="hidden" name="<?php echo $textareas_name;?>[]" value="' + newDescription + '"/>' + newDescription + '</p>' +
                        '</div>' +
                        '<button id="<?php echo $texts_name; ?>__delete" >Remover</button>' +
                    '</div>'
                );

                jQuery("#<?php echo $texts_name ?>__added").append(newOverviewFinal);
                jQuery("#<?php echo $texts_name; ?>__field").val("");
                jQuery("#<?php echo $textareas_name; ?>__field").val("");
                jQuery("#<?php echo $images_name; ?>__field").val("");
            });

            // apagar ítem
            jQuery(document).on("click", "#<?php echo $texts_name; ?>__delete", function(e){
                e.preventDefault();
                jQuery(this).closest("div").remove();
            });

            // upload de imagem
            jQuery(document).on("click", "#<?php echo $images_name; ?>__upload", function (e) {
                e.preventDefault();
                console.log("clicou");
                var $button = jQuery(this);
            
                // Create the media frame.
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Selecione ou arraste uma imagem para cá',
                    library: { // remove these to show all
                        type: 'image' // specific mime
                    },
                    button: {
                        text: 'Select'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });
            
                // When an image is selected, run a callback.
                file_frame.on('select', function () {
                    // We set multiple to false so only get one image from the uploader
            
                    var attachment = file_frame.state().get('selection').first().toJSON();
            
                    $button.siblings('input').val(attachment.url);
                    $button.siblings('input').trigger('change');
            
                });
        
                // Finally, open the modal
                file_frame.open();
            });
        });
    </script>

    <div class="metabox metabox__row-2-8-2">

        <label><?php echo $label; ?></label>
        <div class="metabox__flex-column">
            <input type="text" id="<?php echo $texts_name; ?>__field" placeholder="Título">
            <textarea rows="4" placeholder="Descrição" id="<?php echo $textareas_name; ?>__field"></textarea>
            <div class="metabox__flex-row">
                <input class="widefat" id="<?php echo $images_name; ?>__field" placeholder="URL da Imagem" name="<?php echo $images_name; ?>" type="text"/>
                <button id="<?php echo $images_name; ?>__upload" class="button button-primary">Upload</button>
            </div>
        </div>
        <button class="adicionar" id="<?php echo $texts_name; ?>__button">+ Adicionar</button>
        
        <div id="<?php echo $texts_name ?>__added" class="metabox__added">
            <?php
                $texts = get_post_meta(get_the_ID(), $texts_name, false);
                $textareas = get_post_meta(get_the_ID(), $textareas_name, false);
                $images = get_post_meta(get_the_ID(), $images_name, false);

                if($texts && $textareas && $images):
                    $texts = $texts[0];
                    $textareas = $textareas[0];
                    $images = $images[0];

                    for($i = 0; $i < count($texts); $i++): ?>
                        <div class="metabox__added__item">
                            <div class="metabox__flex-column">
                                <div><input type="hidden" name="<?php echo $images_name;?>[]" value="<?php echo $images[$i]; ?>"/><img width="100" src="<?php echo $images[$i]; ?>"></div>
                                <h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="<?php echo esc_attr($texts[$i]); ?>"/><?php echo esc_attr($texts[$i]); ?></h3>
                                <p><input type="hidden" name="<?php echo $textareas_name;?>[]" value="<?php echo esc_attr($textareas[$i]); ?>"/><?php echo esc_attr($textareas[$i]); ?></p>
                            </div>
                            <button id="<?php echo $texts_name; ?>__delete">Remover</button>
                        </div>
                    <?php endfor;
                endif;
            ?>
        </div>
    </div>
    
    <?php
}

function metabox_multi_img_text_text_textarea(
    $images_name, 
    $texts_name, 
    $subtexts_name, 
    $textareas_name, 
    $label,
    $texts_placeholder = "Título",
    $subtexts_placeholder = "Subtítulo")
{
    wp_enqueue_script('jquery');
    ?>
    <script>
        jQuery(document).ready(function() {
            // adicionar novo ítem
            jQuery("#<?php echo $texts_name; ?>__button").click(function(e){
                e.preventDefault();
                console.log("clicou mano");
                
                var newTitle = jQuery("#<?php echo $texts_name; ?>__field").val().replace(/"/g, '&quot;');
                var newSubTitle = jQuery("#<?php echo $subtexts_name; ?>__field").val().replace(/"/g, '&quot;');
                var newDescription = jQuery("#<?php echo $textareas_name; ?>__field").val().replace(/"/g, '&quot;');
                var newImage = jQuery("#<?php echo $images_name; ?>__field").val();

                if(newTitle === "" || newDescription === "" || newImage === "" || newSubTitle === "") return;

                var newOverviewFinal = jQuery(
                    '<div class="metabox__added__item">' +
                        '<div class="metabox__flex-column">' +
                            '<div><input type="hidden" name="<?php echo $images_name;?>[]" value="' + newImage + '" /><img width="100" src="' + newImage + '" /></div>' +
                            '<h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="'+ newTitle +'"/>' + newTitle + '</h3>' +
                            '<h4><input type="hidden" name="<?php echo $subtexts_name; ?>[]" value="' + newSubTitle + '"/>' + newSubTitle + '</h4>' +
                            '<p><input type="hidden" name="<?php echo $textareas_name;?>[]" value="' + newDescription + '"/>' + newDescription + '</p>' +
                        '</div>' +
                        '<button id="<?php echo $texts_name; ?>__delete" >Remover</button>' +
                    '</div>'
                );

                jQuery("#<?php echo $texts_name ?>__added").append(newOverviewFinal);
                jQuery("#<?php echo $texts_name; ?>__field").val("");
                jQuery("#<?php echo $subtexts_name; ?>__field").val("");
                jQuery("#<?php echo $textareas_name; ?>__field").val("");
                jQuery("#<?php echo $images_name; ?>__field").val("");
            });

            // apagar ítem
            jQuery(document).on("click", "#<?php echo $texts_name; ?>__delete", function(e){
                e.preventDefault();
                jQuery(this).closest("div").remove();
            });

            // upload de imagem
            jQuery(document).on("click", "#<?php echo $images_name; ?>__upload", function (e) {
                e.preventDefault();
                console.log("clicou");
                var $button = jQuery(this);
            
                // Create the media frame.
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Selecione ou arraste uma imagem para cá',
                    library: { // remove these to show all
                        type: 'image' // specific mime
                    },
                    button: {
                        text: 'Select'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });
            
                // When an image is selected, run a callback.
                file_frame.on('select', function () {
                    // We set multiple to false so only get one image from the uploader
            
                    var attachment = file_frame.state().get('selection').first().toJSON();
            
                    $button.siblings('input').val(attachment.url);
                    $button.siblings('input').trigger('change');
            
                });
        
                // Finally, open the modal
                file_frame.open();
            });
        });
    </script>

    <div class="metabox metabox__row-2-8-2">

        <label><?php echo $label; ?></label>
        <div class="metabox__flex-column">
            <input type="text" id="<?php echo $texts_name; ?>__field" placeholder="<?php echo $texts_placeholder; ?>">
            <input type="text" id="<?php echo $subtexts_name; ?>__field" placeholder="<?php echo $subtexts_placeholder; ?>">
            <textarea rows="4" placeholder="Descrição" id="<?php echo $textareas_name; ?>__field"></textarea>
            <div class="metabox__flex-row">
                <input class="widefat" id="<?php echo $images_name; ?>__field" placeholder="URL da Imagem" name="<?php echo $images_name; ?>" type="text"/>
                <button id="<?php echo $images_name; ?>__upload" class="button button-primary">Upload</button>
            </div>
        </div>
        <button class="adicionar" id="<?php echo $texts_name; ?>__button">+ Adicionar</button>
        
        <div id="<?php echo $texts_name ?>__added" class="metabox__added">
            <?php
                $texts = get_post_meta(get_the_ID(), $texts_name, false);
                $subtexts = get_post_meta(get_the_ID(), $subtexts_name, false);
                $textareas = get_post_meta(get_the_ID(), $textareas_name, false);
                $images = get_post_meta(get_the_ID(), $images_name, false);

                if($texts && $subtexts && $textareas && $images):
                    $texts = $texts[0];
                    $subtexts = $subtexts[0];
                    $textareas = $textareas[0];
                    $images = $images[0];

                    for($i = 0; $i < count($texts); $i++): ?>
                        <div class="metabox__added__item">
                            <div class="metabox__flex-column">
                                <div><input type="hidden" name="<?php echo $images_name;?>[]" value="<?php echo $images[$i]; ?>"/><img width="100" src="<?php echo $images[$i]; ?>"></div>
                                <h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="<?php echo esc_attr($texts[$i]); ?>"/><?php echo esc_attr($texts[$i]); ?></h3>
                                <h4><input type="hidden" name="<?php echo $subtexts_name;?>[]" value="<?php echo esc_attr($subtexts[$i]); ?>"/><?php echo esc_attr($subtexts[$i]); ?></h4>
                                <p><input type="hidden" name="<?php echo $textareas_name;?>[]" value="<?php echo esc_attr($textareas[$i]); ?>"/><?php echo esc_attr($textareas[$i]); ?></p>
                            </div>
                            <button id="<?php echo $texts_name; ?>__delete">Remover</button>
                        </div>
                    <?php endfor;
                endif;
            ?>
        </div>
    </div>
    
    <?php
}

function metabox_multi_text_textarea($title_name, $description_name, $label)
{
    wp_enqueue_script('jquery');
    ?>
    <script>
        jQuery(document).ready(function() {

            jQuery("#<?php echo $title_name; ?>__button").click(function(e){
                e.preventDefault();
                
                var newTitle = jQuery("#<?php echo $title_name; ?>__field").val().replace(/"/g, '&quot;');
                var newDescription = jQuery("#<?php echo $description_name; ?>__field").val().replace(/"/g, '&quot;');

                if (newTitle === "" || newDescription === "") return;

                var newOverviewFinal = jQuery(
                    '<div class="metabox__added__item">' +
                        '<div class="metabox__flex-column">' +
                            '<h3><input type="hidden" name="<?php echo $title_name;?>[]" value="'+ newTitle +'"/>' + newTitle + '</h3>' + 
                            '<p><input type="hidden" name="<?php echo $description_name;?>[]" value="' + newDescription + '"/>' + newDescription + '</p>' +
                        '</div>' +
                        '<button id="<?php echo $title_name; ?>__delete">Remover</button>' +
                    '</div>'
                );

                jQuery("#<?php echo $title_name ?>__added").append(newOverviewFinal);
                jQuery("#<?php echo $title_name; ?>__field").val("");
                jQuery("#<?php echo $description_name; ?>__field").val("");
            });

            jQuery(document).on("click", "#<?php echo $title_name; ?>__delete", function(e){
                e.preventDefault();
                jQuery(this).closest("div").remove();
            });
        });
    </script>

    <div class="metabox metabox__row-2-8-2">

        <label><?php echo $label; ?></label>
        <input type="text" id="<?php echo $title_name; ?>__field" placeholder="Título">
        <button class="adicionar" id="<?php echo $title_name; ?>__button">+ Adicionar</button>
        <div></div>
        <textarea rows="4" placeholder="Descrição" id="<?php echo $description_name; ?>__field"></textarea>
        
        <div id="<?php echo $title_name ?>__added" class="metabox__added">
            <?php
                $ferramentas_overview_final_titulo = get_post_meta(get_the_ID(), $title_name, false);
                $ferramentas_overview_final_descricao = get_post_meta(get_the_ID(), $description_name, false);
                
                if($ferramentas_overview_final_titulo && $ferramentas_overview_final_descricao):
                    $ferramentas_overview_final_titulo = $ferramentas_overview_final_titulo[0];
                    $ferramentas_overview_final_descricao = $ferramentas_overview_final_descricao[0];

                    for($i = 0; $i < count($ferramentas_overview_final_titulo); $i++): ?>
                        <div class="metabox__added__item">
                            <div class="metabox__flex-column">
                                <h3><input type="hidden" name="<?php echo $title_name;?>[]" value="<?php echo esc_attr($ferramentas_overview_final_titulo[$i]); ?>"/><?php echo esc_attr($ferramentas_overview_final_titulo[$i]); ?></h3>
                                <p><input type="hidden" name="<?php echo $description_name;?>[]" value="<?php echo esc_attr($ferramentas_overview_final_descricao[$i]); ?>"/><?php echo esc_attr($ferramentas_overview_final_descricao[$i]); ?></p>
                            </div>
                            <button id="<?php echo $title_name; ?>__delete">Remover</button>
                        </div>
                    <?php endfor;
                endif; ?>
        </div>
    </div>
    
    <?php
}

function metabox_multi_img_text_html($images_name, $texts_name, $html_name, $label)
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('tiny_mce');
    ?>
    <script>
        jQuery(document).ready(function() {
            // adicionar novo ítem
            jQuery("#<?php echo $texts_name; ?>__button").click(function(e){
                e.preventDefault();
                
                var newTitle = jQuery("#<?php echo $texts_name; ?>__field").val().replace(/"/g, '&quot;');
                var newHtml = tinymce.get('<?php echo $html_name; ?>').getContent().replace(/"/g, '&quot;');
                var newImage = jQuery("#<?php echo $images_name; ?>__field").val();

                if(newTitle === "" || newHtml === "" || newImage === "") return;

                var newOverviewFinal = jQuery(
                    '<div class="metabox__added__item">' +
                        '<div class="metabox__flex-column">' +
                            '<div><input type="hidden" name="<?php echo $images_name;?>[]" value="' + newImage + '" /><img width="100" src="' + newImage + '" /></div>' +
                            '<h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="'+ newTitle +'"/>' + newTitle + '</h3>' +
                            '<p><input type="hidden" name="<?php echo $html_name;?>[]" value="' + newHtml + '"/>' + newHtml + '</p>' +
                        '</div>' +
                        '<button id="<?php echo $texts_name; ?>__delete" >Remover</button>' +
                    '</div>'
                );

                jQuery("#<?php echo $texts_name ?>__added").append(newOverviewFinal);
                jQuery("#<?php echo $texts_name; ?>__field").val("");
                tinymce.get('<?php echo $html_name; ?>').setContent("");
                jQuery("#<?php echo $images_name; ?>__field").val("");
            });

            // apagar ítem
            jQuery(document).on("click", "#<?php echo $texts_name; ?>__delete", function(e){
                e.preventDefault();
                jQuery(this).closest("div").remove();
            });

            // upload de imagem
            jQuery(document).on("click", "#<?php echo $images_name; ?>__upload", function (e) {
                e.preventDefault();
                console.log("clicou");
                var $button = jQuery(this);
            
                // Create the media frame.
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Selecione ou arraste uma imagem para cá',
                    library: { // remove these to show all
                        type: 'image' // specific mime
                    },
                    button: {
                        text: 'Select'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });
            
                // When an image is selected, run a callback.
                file_frame.on('select', function () {
                    // We set multiple to false so only get one image from the uploader
            
                    var attachment = file_frame.state().get('selection').first().toJSON();
            
                    $button.siblings('input').val(attachment.url);
                    $button.siblings('input').trigger('change');
            
                });
        
                // Finally, open the modal
                file_frame.open();
            });
        });
    </script>

    <div class="metabox metabox__row-2-8-2">
        <label><?php echo $label; ?></label>
        <div class="metabox__flex-column">
            <input type="text" id="<?php echo $texts_name; ?>__field" placeholder="Título">
            <?php wp_editor("", $html_name, ["media_buttons" => false]); ?>
            <div class="metabox__flex-row">
                <input class="widefat" id="<?php echo $images_name; ?>__field" placeholder="URL da Imagem" name="<?php echo $images_name; ?>" type="text"/>
                <button id="<?php echo $images_name; ?>__upload" class="button button-primary">Upload</button>
            </div>
        </div>
        <button class="adicionar" id="<?php echo $texts_name; ?>__button">+ Adicionar</button>
        
        <div id="<?php echo $texts_name ?>__added" class="metabox__added">
            <?php
                $texts = get_post_meta(get_the_ID(), $texts_name, false);
                $htmls = get_post_meta(get_the_ID(), $html_name, false);
                $images = get_post_meta(get_the_ID(), $images_name, false);

                if($texts && $htmls && $images):
                    $texts = $texts[0];
                    $htmls = $htmls[0];
                    $images = $images[0];

                    for($i = 0; $i < count($texts); $i++): ?>
                        <div class="metabox__added__item">
                            <div class="metabox__flex-column">
                                <div><input type="hidden" name="<?php echo $images_name;?>[]" value="<?php echo $images[$i]; ?>"/><img width="100" src="<?php echo $images[$i]; ?>"></div>
                                <h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="<?php echo esc_attr($texts[$i]); ?>"/><?php echo esc_attr($texts[$i]); ?></h3>
                                <p><input type="hidden" name="<?php echo $html_name;?>[]" value="<?php echo str_replace("\"", "&quot;", $htmls[$i]); ?>"/><?php echo str_replace("\"", "&quot;", $htmls[$i]); ?></p>
                            </div>
                            <button id="<?php echo $texts_name; ?>__delete">Remover</button>
                        </div>
                    <?php endfor;
                endif;
            ?>
        </div>
    </div>
    <?php
}

function metabox_multi_img_text_text_html($images_name, $texts_name, $subtexts_name, $html_name, $label)
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('tiny_mce');
    ?>
    <script>
        jQuery(document).ready(function() {
            // adicionar novo ítem
            jQuery("#<?php echo $texts_name; ?>__button").click(function(e){
                e.preventDefault();
                
                var newTitle = jQuery("#<?php echo $texts_name; ?>__field").val().replace(/"/g, '&quot;');
                var newSubtitle = jQuery("#<?php echo $subtexts_name; ?>__field").val().replace(/"/g, '&quot;');
                var newHtml = tinymce.get('<?php echo $html_name; ?>').getContent().replace(/"/g, '&quot;');
                var newImage = jQuery("#<?php echo $images_name; ?>__field").val();
                console.log("chegou aqui");

                if(newTitle === "" || newSubtitle === "" || newHtml === "" || newImage === "") return;

                var newOverviewFinal = jQuery(
                    '<div class="metabox__added__item">' +
                        '<div class="metabox__flex-column">' +
                            '<div><input type="hidden" name="<?php echo $images_name;?>[]" value="' + newImage + '" /><img width="100" src="' + newImage + '" /></div>' +
                            '<h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="'+ newTitle +'"/>' + newTitle + '</h3>' +
                            '<h4><input type="hidden" name="<?php echo $subtexts_name;?>[]" value="'+ newSubtitle +'"/>' + newSubtitle + '</h4>' +
                            '<p><input type="hidden" name="<?php echo $html_name;?>[]" value="' + newHtml + '"/>' + newHtml + '</p>' +
                        '</div>' +
                        '<button id="<?php echo $texts_name; ?>__delete" >Remover</button>' +
                    '</div>'
                );

                jQuery("#<?php echo $texts_name ?>__added").append(newOverviewFinal);
                jQuery("#<?php echo $texts_name; ?>__field").val("");
                jQuery("#<?php echo $subtexts_name; ?>__field").val("");
                tinymce.get('<?php echo $html_name; ?>').setContent("");
                jQuery("#<?php echo $images_name; ?>__field").val("");
            });

            // apagar ítem
            jQuery(document).on("click", "#<?php echo $texts_name; ?>__delete", function(e){
                e.preventDefault();
                jQuery(this).closest("div").remove();
            });

            // upload de imagem
            jQuery(document).on("click", "#<?php echo $images_name; ?>__upload", function (e) {
                e.preventDefault();
                console.log("clicou");
                var $button = jQuery(this);
            
                // Create the media frame.
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Selecione ou arraste uma imagem para cá',
                    library: { // remove these to show all
                        type: 'image' // specific mime
                    },
                    button: {
                        text: 'Select'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });
            
                // When an image is selected, run a callback.
                file_frame.on('select', function () {
                    // We set multiple to false so only get one image from the uploader
            
                    var attachment = file_frame.state().get('selection').first().toJSON();
            
                    $button.siblings('input').val(attachment.url);
                    $button.siblings('input').trigger('change');
            
                });
        
                // Finally, open the modal
                file_frame.open();
            });
        });
    </script>

    <div class="metabox metabox__row-2-8-2">
        <label><?php echo $label; ?></label>
        <div class="metabox__flex-column">
            <input type="text" id="<?php echo $texts_name; ?>__field" placeholder="Título">
            <input type="text" id="<?php echo $subtexts_name; ?>__field" placeholder="Subtítulo">
            <?php wp_editor("", $html_name, ["media_buttons" => false]); ?>
            <div class="metabox__flex-row">
                <input class="widefat" id="<?php echo $images_name; ?>__field" placeholder="URL da Imagem" name="<?php echo $images_name; ?>" type="text"/>
                <button id="<?php echo $images_name; ?>__upload" class="button button-primary">Upload</button>
            </div>
        </div>
        <button class="adicionar" id="<?php echo $texts_name; ?>__button">+ Adicionar</button>
        
        <div id="<?php echo $texts_name ?>__added" class="metabox__added">
            <?php
                $texts = get_post_meta(get_the_ID(), $texts_name, false);
                $subtexts = get_post_meta(get_the_ID(), $subtexts_name, false);
                $htmls = get_post_meta(get_the_ID(), $html_name, false);
                $images = get_post_meta(get_the_ID(), $images_name, false);

                if($texts && $htmls && $images && $subtexts):
                    $texts = $texts[0];
                    $subtexts = $subtexts[0];
                    $htmls = $htmls[0];
                    $images = $images[0];

                    for($i = 0; $i < count($texts); $i++): ?>
                        <div class="metabox__added__item">
                            <div class="metabox__flex-column">
                                <div><input type="hidden" name="<?php echo $images_name;?>[]" value="<?php echo $images[$i]; ?>"/><img width="100" src="<?php echo $images[$i]; ?>"></div>
                                <h3><input type="hidden" name="<?php echo $texts_name;?>[]" value="<?php echo esc_attr($texts[$i]); ?>"/><?php echo esc_attr($texts[$i]); ?></h3>
                                <h4><input type="hidden" name="<?php echo $subtexts_name;?>[]" value="<?php echo esc_attr($subtexts[$i]); ?>"/><?php echo esc_attr($subtexts[$i]); ?></h3>
                                <p><input type="hidden" name="<?php echo $html_name;?>[]" value="<?php echo str_replace("\"", "&quot;", $htmls[$i]); ?>"/><?php echo str_replace("\"", "&quot;", $htmls[$i]); ?></p>
                            </div>
                            <button id="<?php echo $texts_name; ?>__delete">Remover</button>
                        </div>
                    <?php endfor;
                endif;
            ?>
        </div>
    </div>
    <?php
}