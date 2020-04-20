<?php /* Template Name: Página de Anúncios */ ?>
<?php 

$nome = get_post_meta(get_the_ID(), "ANUNCIO_TITLE", true);
$categoria = get_post_meta(get_the_ID(), "ANUNCIO_TITLE", true);
$whatsapp = get_post_meta(get_the_ID(), "ANUNCIO_TITLE", true);
$telefone = get_post_meta(get_the_ID(), "ANUNCIO_TITLE", true);
$instagram = get_post_meta(get_the_ID(), "ANUNCIO_TITLE", true);
$observacoes = get_post_meta(get_the_ID(), "ANUNCIO_TITLE", true);
$logo = get_post_meta(get_the_ID(), "ANUNCIO_TITLE", true);

$query_anuncios = new WP_Query(['post_type' => 'anuncios']);
$anuncios = $query_anuncios->posts;

?>

<?php get_header() ?>

<section class="header">
    <div class="wrapper">
        <img src="<?php get_template_directory()."/assets/min-images/ipbwhite.png" ?>" alt="">
        <h2>Guia Comunitário de Comércio e Serviços</h2>
    </div>
</section>

<section class="content">
    <div class="wrapper">
        <div class="grid">
            <?php foreach($anuncios as $anuncio): ?>
            <div class="anuncio">
                <img src="<?php echo get_post_meta($anuncio->ID, "ANUNCIO_LOGO")[0];?>" alt="">
                <div class="anuncio__right">
                    <h3><?php echo get_post_meta($anuncio->ID, "ANUNCIO_TITLE")[0];?></h3>
                    <h4><?php echo get_post_meta($anuncio->ID, "ANUNCIO_CATEGORIA")[0];?></h4>
                    <p>
                        <span>Endereço:</span> 
                        <?php echo get_post_meta($anuncio->ID, "ANUNCIO_ADDRESS")[0];?>
                    </p>
                    <p>
                        WhatsApp: 
                        <?php echo get_post_meta($anuncio->ID, "ANUNCIO_WHATSAPP")[0];?>
                    </p>
                    <p>
                        Telefone: 
                        <?php echo get_post_meta($anuncio->ID, "ANUNCIO_TELEFONE")[0];?>
                    </p>
                    <p>
                        Instagram: 
                        <?php echo get_post_meta($anuncio->ID, "ANUNCIO_INSTAGRAM")[0];?>
                    </p>
                    <p>
                        Detalhes:
                        <?php echo get_post_meta($anuncio->ID, "ANUNCIO_OBS")[0];?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer()?>