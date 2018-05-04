<?php

/**
 * Statische Definition der Shortcodes
 */

namespace imbaa\Affilipus\Core\Output;
use imbaa\Affilipus\Core as CORE;


class imbafShortcodeDefinitions {


    public static $shortcodes = array(

        /* Internal Shortcodes */

        'affilipus_shortcode_table' => array(
            'code'        => 'affilipus_shortcode_table',
            'alias'       => 'afp_shortcode_table',
            'callback'    => 'shortcode_table',
            'hr_name'     => 'Affilipus Shortcode Table',
            'description' => 'Gibt eine Tabelle mit allen Shortcodes des Plugins aus',
            'templates'   => false,
            'generator'   => false,
            'public'    => false
        ),

        'affilipus_shortcode_info' => array(
            'code'        => 'affilipus_shortcode_info',
            'alias'       => 'afp_shortcode_info',
            'callback'    => 'shortcode_info',
            'hr_name'     => 'Affilipus Shortcode Info',
            'description' => 'Gibt Informationen zu einem Shortcode in Tabellenform aus.',
            'templates'   => false,
            'generator'   => false,
            'public'    => false
        ),

        /* User Shortcodes */

        /* Text Shortcodes */

        'affilipus_user_description' => array(
            'code'      => 'affilipus_user_description',
            'alias'     => 'afp_user_description',
            'callback'  => 'user_description',
            'hr_name'   => 'Benutzerdefinierte Produktbeschreibung',
            'description' => 'Gibt die benutzerdefinierte Beschreibung eines Produktes aus. Sollte nur innerhalb eines Produktes eingesetzt werden.',
            'description_long' => '',
            'templates' => false,
            'generator' => false,
            'public' => true
        ),
        'affilipus_product_picture' => [
            'code' => 'affilipus_product_picture',
            'alias' => 'afp_product_picture',
            'callback' => 'affilipus_product_picture',
            'hr_name' => 'Produktbild',
            'description' => 'Gibt Produktbild aus',
            'templates' => true,
            'generator' => true,
            'public' => true
        ],
        'affilipus_default_description' => array(
            'code'      => 'affilipus_default_description',
            'alias'     => 'afp_default_description',
            'callback'  => 'default_description',
            'hr_name'   => 'Aus API importierte Produktbeschreibung',
            'description' => 'Gibt die aus der API importierte Produktbeschreibung aus.',
            'description_long' => '',
            'templates' => false,
            'generator' => false,
            'public' => true
        ),
        'affilipus_package_dimensions' => array(
            'code'      => 'affilipus_package_dimensions',
            'alias'     => 'afp_package_dimensions',
            'callback'  => 'package_size',
            'hr_name'   => 'Verpackungsgröße',
            'description' => 'Gibt die Größe des Produktes im verpackten Zustand aus.',
            'description_long' => '',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),
        'affilipus_item_dimensions' => array(
            'code'      => 'affilipus_item_dimensions',
            'alias'     => 'afp_item_dimensions',
            'callback'  => 'item_size',
            'hr_name'   => 'Produktgröße',
            'description' => 'Gibt die Abmessungen des Produktes im ausgepackten Zustand aus.',
            'description_long' => '',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),
        'affilipus_item_weight' => array(
            'code'      => 'affilipus_item_weight',
            'alias'     => 'afp_item_weight',
            'callback'  => 'item_weight',
            'hr_name'   => 'Produktgewicht',
            'description' => 'Gibt das Gewicht eines Produktes aus.',
            'description_long' => '',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),
        'affilipus_package_weight' => array(
            'code'      => 'affilipus_package_weight',
            'alias'     => 'afp_package_weight',
            'callback'  => 'package_weight',
            'hr_name'   => 'Verpackungsgewicht',
            'description' => 'Gibt das Gewicht eines Produktes inkl. Verpackungsgewicht aus.',
            'description_long' => '',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),
        'affilipus_reflink' => array(
            'code'      => 'affilipus_reflink',
            'alias'     => 'afp_reflink',
            'callback'  => 'affilipus_reflink',
            'hr_name'   => 'Einzelner Reflink',
            'description' => 'Gibt einen Produkt Reflink aus.',
            'description_long' => '',
            'templates' => false,
            'generator' => false,
            'public' => true
        ),
        'affilipus_price' => array(
            'code'      => 'affilipus_price',
            'alias'     => 'afp_price',
            'callback'  => 'affilipus_price',
            'hr_name'   => 'Produkt Preis',
            'description' => 'Gibt den voreingestellten Preis (z.b. Listen- oder Angebotspreis) eines Produktes aus.',
            'description_long' => '',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),
        'affilipus_product_name' => array(
            'code'      => 'affilipus_product_name',
            'alias'     => 'afp_product_name',
            'callback'  => 'affilipus_product_name',
            'hr_name'   => 'Produktname',
            'description' => 'Gibt den Namen eines Produktes anhand seiner Produkt-ID aus.',
            'description_long' => '',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),
        'affilipus_review_rating_text' => array(
            'code'      => 'affilipus_review_rating_text',
            'alias'     => 'afp_review_rating_text',
            'callback'  => 'review_rating_text',
            'hr_name'   => 'Sterne Bewertung Text',
            'description' => 'Gibt die Sterne-Bewertung eines Produktes als Text aus',
            'description_long' => '',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),
        'affilipus_review_text' => array(
            'code'      => 'affilipus_review_text',
            'alias'     => 'afp_review_text',
            'callback'  => 'review_text',
            'hr_name'   => 'Review Text',
            'description' => 'Gibt den Review-Text eines Produktes in Textform aus.',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),

        /* HTML Shortcodes */

        'affilipus_feature_list' => array(
            'code'      => 'affilipus_feature_list',
            'alias'     => 'afp_feature_list',
            'callback'  => 'feature_list',
            'hr_name'   => 'Feature Liste',
            'description' => 'Feature-Liste eines Produktes',
            'description_long' => '',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),
        'affilipus_price_list' => array(
            'code'      => 'affilipus_price_list',
            'alias'     => 'afp_price_list',
            'callback'  => 'price_list',
            'hr_name'   => 'Preistabelle',
            'description' => 'Gibt eine Preis-Tabelle mit Produkt und Unterprodukten aus.',
            'description_long' => '',
            'templates' => true,
            'generator' => true,
            'public' => true
        ),

        'affilipus_button' => array(
            'code'      => 'affilipus_button',
            'alias'     => 'afp_button',
            'callback'  => 'affilipus_button',
            'hr_name'   => 'Affilipus Button',
            'description' => 'Gibt einen Button aus.',
            'description_long' => '',
            'templates' => true,
            'generator' => true,
            'public' => true
        ),

        'affilipus_buy_button' => array(
            'code'      => 'affilipus_buy_button',
            'alias'     => 'afp_buy_button',
            'callback'  => 'buy_button',
            'hr_name'   => 'Jetzt kaufen Button',
            'description' => 'Gibt einen "Jetzt Kaufen" Button aus.',
            'description_long' => '',
            'templates' => true,
            'generator' => true,
            'public' => true
        ),
        'affilipus_add2cart_button' => array(
            'code'      => 'affilipus_add2cart_button',
            'alias'     => 'afp_add2cart_button',
            'callback'  => 'add2cart_button',
            'hr_name'   => 'Amazon Warenkorb Button',
            'description' => 'Gibt einen Button aus mit dem ein Amazon-Produkt in den Warenkorb gelegt werden kann.',
            'description_long' => '',
            'templates' => true,
            'generator' => true,
            'public' => true
        ),
        'affilipus_topseller' => array(
            'code'      => 'affilipus_topseller',
            'alias'     => 'afp_topseller',
            'callback'  => 'affilipus_topseller',
            'hr_name'   => 'Topseller Liste',
            'description' => 'Gibt eine List von Topseller Produkten aus.',
            'description_long' => '',
            'templates' => true,
            'generator' => true,
            'public' => true
        ),
        'affilipus_product_box' => array(
            'code'      => 'affilipus_product_box',
            'alias'     => 'afp_product_box',
            'callback'  => 'product_box',
            'hr_name'   => 'Produktbox',
            'description' => 'Gibt ein Produkt über die Produkt-ID oder die ASIN aus.',
            'description_long' => '',
            //'documentation_link' => 'https://affilipus.com/produktboxen/',
            'templates' => true,
            'generator' => true,
            'public' => true
        ),
        'affilipus_review_box' => array(
            'code'      => 'affilipus_review_box',
            'alias'     => 'afp_review_box',
            'callback'  => 'review_box',
            'hr_name'   => 'Review Box',
            'description' => 'Erstellt eine Review-Box mit Review Text und Sternebewertung.',
            'description_long' => '',
            'templates' => true,
            'generator' => true,
            'public' => true
        ),
        'affilipus_comptable' => array(
            'code'      => 'affilipus_comptable',
            'alias'     => 'afp_comptable',
            'callback'  => 'comptable',
            'hr_name'   => 'Vergleichstabelle (klein)',
            'description' => 'Erstellt eine Vergleichstabelle für Produkte',
            'description_long' => '',
            'templates' => true,
            'generator' => true,
            'public' => true
        ),

        'affilipus_comptable_big' => array(
            'code'      => 'affilipus_comptable_big',
            'alias'     => 'afp_comptable_big',
            'callback'  => 'comptable_big',
            'hr_name'   => 'Vergleichstabelle (groß)',
            'description' => 'Erstellt eine große Vergleichstabelle für bis zu 50 Produkte',
            'description_long' => '',
            'templates' => true,
            'generator' => true,
            'public' => false
        ),

        'affilipus_amazon_reviews' => array(
            'code'      => 'affilipus_amazon_reviews',
            'alias'     => 'afp_amazon_reviews',
            'callback'  => 'amazon_reviews',
            'hr_name'   => 'Amazon Reviews',
            'description' => 'Gibt Amazon Reviews zu einem Produkt in einem Iframe aus.',
            'description_long' => '',
            'templates' => false,
            'generator' => true,
            'public' => true
        ),
        'affilipus_grid' => array(
            'code'      => 'affilipus_grid',
            'alias'     => 'afp_grid',
            'callback'  => 'grid',
            'hr_name'   => 'Produkt Grid',
            'description' => 'Gibt ein Grid mit Produkten aus',
            'description_long' => '',
            'templates' => true,
            'generator' => true,
            'public' => true
        )

    );




}