<?php
/**
 * Template Name: Category Page
 */

get_header('shop');

defined('ABSPATH') || exit;

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'de';

/**
 * -----------------------------------
 * STEP 1: Detect current parent slug from URL
 * -----------------------------------
 * Works with:
 * /produkt-kategorie/grusskarten/
 * /fr/produkt-kategorie/grusskarten/
 * /en/produkt-kategorie/grusskarten/
 */
$request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$path_parts   = explode('/', $request_path);

$allowed_slugs = [
    'grusskarten',
    'kunstdrucke',
    'originalgrafik',
    'papeterie-und-sonstiges',
];

$source_parent_slug = '';

foreach ($path_parts as $part) {
    if (in_array($part, $allowed_slugs, true)) {
        $source_parent_slug = $part;
        break;
    }
}

/**
 * -----------------------------------
 * STEP 2: Get DE source category
 * -----------------------------------
 */
$source_parent_cat = $source_parent_slug
    ? get_term_by('slug', $source_parent_slug, 'product_cat')
    : false;

/**
 * -----------------------------------
 * STEP 3: Translate category to current language
 * -----------------------------------
 */
$parent_cat = $source_parent_cat;

if ($source_parent_cat && function_exists('pll_get_term') && $current_lang !== 'de') {
    $translated_term_id = pll_get_term($source_parent_cat->term_id, $current_lang);

    if ($translated_term_id) {
        $translated_term = get_term($translated_term_id, 'product_cat');
        if ($translated_term && !is_wp_error($translated_term)) {
            $parent_cat = $translated_term;
        }
    }
}

/**
 * -----------------------------------
 * STEP 4: Banner image from ACF
 * -----------------------------------
 */
$dummy_image_id  = get_field('kategorie-banner');
$dummy_image_url = $dummy_image_id ? wp_get_attachment_url($dummy_image_id) : '';
?>

<div class="container">
    <nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php pll_e('Home'); ?></a>
        &nbsp;/&nbsp;
        <span><?php the_title(); ?></span>
    </nav>
</div>

<main class="page-content">
    <div class="container">

        <div id="cat_header">
            <h2>
                <?php echo ($parent_cat && !is_wp_error($parent_cat)) ? esc_html($parent_cat->name) : esc_html(get_the_title()); ?>
            </h2>

            <?php if (!empty($dummy_image_url)) : ?>
                <img src="<?php echo esc_url($dummy_image_url); ?>" alt="<?php echo ($parent_cat && !is_wp_error($parent_cat)) ? esc_attr($parent_cat->name) : esc_attr(get_the_title()); ?>">
            <?php endif; ?>
        </div>

        <div class="container-fluid">
            <div class="productlist">

                <?php
                if ($parent_cat && !is_wp_error($parent_cat)) {

                    $terms = get_terms([
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true,
                        'parent'     => $parent_cat->term_id,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'lang'       => $current_lang,
                    ]);

                    if (!empty($terms) && !is_wp_error($terms)) {

                        foreach ($terms as $term) {

                            // ACF taxonomy image field
                            $thumbnail_id = get_field('kategorie_bild_' . $current_lang, 'product_cat_' . $term->term_id);

                            if (empty($thumbnail_id)) {
                                $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                            }

                            $image = wp_get_attachment_url($thumbnail_id);

                            if (empty($image) || str_contains($image, 'placeholder')) {
                                $image = wp_get_attachment_url(409615);
                            }
                            ?>

                            <a class="item" href="<?php echo esc_url(get_term_link($term, 'product_cat')); ?>">
                                <img class="item__image"
                                     title="<?php echo esc_attr($term->name); ?>"
                                     alt="<?php echo esc_attr($term->name); ?>"
                                     src="<?php echo esc_url($image); ?>">

                                <span class="item__name"><?php echo esc_html($term->name); ?></span>
                                <span class="button hollow"><?php pll_e('Produktserie ansehen'); ?></span>
                            </a>

                            <?php
                        }

                    } else {
                        echo '<p>' . esc_html(function_exists('pll__') ? pll__('Keine Kategorien gefunden') : 'Keine Kategorien gefunden') . '</p>';
                    }

                } else {
                    echo '<p>' . esc_html(function_exists('pll__') ? pll__('Hauptkategorie nicht gefunden') : 'Hauptkategorie nicht gefunden') . '</p>';
                }
                ?>

            </div>
        </div>
    </div>
</main>

<?php get_footer('shop'); ?>