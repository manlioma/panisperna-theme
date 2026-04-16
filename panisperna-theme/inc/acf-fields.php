<?php
/**
 * ACF Field Groups - Registrati in PHP per version control
 *
 * @package Panisperna
 */

defined('ABSPATH') || exit;

if (!function_exists('acf_add_local_field_group')) {
    return;
}

/* --------------------------------------------------------------------------
   HOMEPAGE
   -------------------------------------------------------------------------- */

acf_add_local_field_group([
    'key'      => 'group_homepage',
    'title'    => 'Homepage',
    'fields'   => [
        // Hero
        [
            'key'   => 'field_hero_label',
            'label' => 'Hero - Etichetta',
            'name'  => 'hero_label',
            'type'  => 'text',
            'default_value' => 'Libreria',
        ],
        [
            'key'   => 'field_hero_heading',
            'label' => 'Hero - Titolo',
            'name'  => 'hero_heading',
            'type'  => 'textarea',
            'rows'  => 2,
        ],
        [
            'key'   => 'field_hero_images',
            'label' => 'Hero - Galleria immagini',
            'name'  => 'hero_images',
            'type'  => 'gallery',
            'return_format' => 'array',
            'min'   => 3,
            'max'   => 4,
            'preview_size'  => 'hero-image',
        ],
        // Sezione Pacchetti
        [
            'key'   => 'field_pacchetti_title',
            'label' => 'Pacchetti - Titolo',
            'name'  => 'pacchetti_title',
            'type'  => 'text',
            'default_value' => 'I nostri pacchetti',
        ],
        [
            'key'   => 'field_pacchetti_description',
            'label' => 'Pacchetti - Descrizione',
            'name'  => 'pacchetti_description',
            'type'  => 'textarea',
            'rows'  => 2,
        ],
        // Sezione Letti da noi
        [
            'key'   => 'field_letti_title',
            'label' => 'Letti da noi - Titolo',
            'name'  => 'letti_title',
            'type'  => 'text',
            'default_value' => 'Letti da noi',
        ],
        [
            'key'   => 'field_letti_description',
            'label' => 'Letti da noi - Descrizione',
            'name'  => 'letti_description',
            'type'  => 'textarea',
            'rows'  => 2,
        ],
        // Sezione Eventi
        [
            'key'   => 'field_eventi_title',
            'label' => 'Eventi - Titolo',
            'name'  => 'eventi_title',
            'type'  => 'text',
            'default_value' => 'Prossimi eventi',
        ],
        [
            'key'   => 'field_eventi_description',
            'label' => 'Eventi - Descrizione',
            'name'  => 'eventi_description',
            'type'  => 'textarea',
            'rows'  => 2,
        ],
        // Sezione Consigliati
        [
            'key'   => 'field_consigliati_title',
            'label' => 'Consigliati - Titolo',
            'name'  => 'consigliati_title',
            'type'  => 'text',
            'default_value' => 'Consigliati da voi',
        ],
        [
            'key'   => 'field_consigliati_description',
            'label' => 'Consigliati - Descrizione',
            'name'  => 'consigliati_description',
            'type'  => 'textarea',
            'rows'  => 2,
        ],
        // Prefooter
        [
            'key'   => 'field_prefooter_text',
            'label' => 'Prefooter - Testo',
            'name'  => 'prefooter_text',
            'type'  => 'text',
            'default_value' => 'Hai dei pacchetti da consigliare o dei libri da chiederci?',
        ],
        [
            'key'   => 'field_prefooter_cta_text',
            'label' => 'Prefooter - CTA',
            'name'  => 'prefooter_cta_text',
            'type'  => 'text',
            'default_value' => 'Contattaci',
        ],
        [
            'key'   => 'field_prefooter_cta_link',
            'label' => 'Prefooter - CTA Link',
            'name'  => 'prefooter_cta_link',
            'type'  => 'url',
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'page_template',
                'operator' => '==',
                'value'    => 'page-homepage.php',
            ],
        ],
    ],
]);

/* --------------------------------------------------------------------------
   EVENTO (CPT)
   -------------------------------------------------------------------------- */

acf_add_local_field_group([
    'key'    => 'group_evento',
    'title'  => 'Dettagli Evento',
    'fields' => [
        [
            'key'   => 'field_evento_data',
            'label' => 'Data Evento',
            'name'  => 'evento_data',
            'type'  => 'date_picker',
            'display_format' => 'd/m/Y',
            'return_format'  => 'd/m/Y',
        ],
        [
            'key'   => 'field_evento_ora',
            'label' => 'Ora Evento',
            'name'  => 'evento_ora',
            'type'  => 'time_picker',
            'display_format' => 'H:i',
            'return_format'  => 'H:i',
        ],
        [
            'key'   => 'field_evento_luogo',
            'label' => 'Luogo',
            'name'  => 'evento_luogo',
            'type'  => 'text',
            'default_value' => 'Libreria Panisperna 220',
        ],
        [
            'key'   => 'field_evento_libro',
            'label' => 'Libro collegato',
            'name'  => 'evento_libro',
            'type'  => 'post_object',
            'post_type' => ['product'],
            'return_format' => 'id',
            'allow_null' => 1,
        ],
        [
            'key'   => 'field_evento_gallery',
            'label' => 'Gallery foto',
            'name'  => 'evento_gallery',
            'type'  => 'gallery',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'instructions'  => 'Carica le foto dell\'evento. Se vuoto, il carosello non viene mostrato.',
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'evento',
            ],
        ],
    ],
]);

/* --------------------------------------------------------------------------
   CONSIGLIO (CPT)
   -------------------------------------------------------------------------- */

acf_add_local_field_group([
    'key'    => 'group_consiglio',
    'title'  => 'Dettagli Consiglio',
    'fields' => [
        [
            'key'   => 'field_consiglio_nome',
            'label' => 'Nome persona (presenta)',
            'name'  => 'consiglio_nome',
            'type'  => 'text',
        ],
        [
            'key'   => 'field_consiglio_autore',
            'label' => 'Autore/autrice',
            'name'  => 'consiglio_autore',
            'type'  => 'text',
            'instructions' => 'Nome dell\'autore/autrice del libro consigliato.',
        ],
        [
            'key'   => 'field_consiglio_video',
            'label' => 'Video YouTube (URL o embed)',
            'name'  => 'consiglio_video',
            'type'  => 'oembed',
            'instructions' => 'Incolla URL YouTube. Il video viene mostrato nel hero.',
        ],
        [
            'key'   => 'field_consiglio_video_orientamento',
            'label' => 'Orientamento video',
            'name'  => 'consiglio_video_orientamento',
            'type'  => 'select',
            'instructions' => 'Seleziona se il video è orizzontale (16:9) o verticale (9:16).',
            'choices' => [
                'orizzontale' => 'Orizzontale (16:9)',
                'verticale'   => 'Verticale (9:16)',
            ],
            'default_value' => 'orizzontale',
            'conditional_logic' => [
                [
                    [
                        'field' => 'field_consiglio_video',
                        'operator' => '!=empty',
                    ],
                ],
            ],
        ],
        [
            'key'   => 'field_consiglio_libri',
            'label' => 'Libri consigliati',
            'name'  => 'consiglio_libri',
            'type'  => 'relationship',
            'post_type' => ['product'],
            'return_format' => 'object',
            'min' => 0,
            'max' => 10,
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'consiglio',
            ],
        ],
    ],
]);

/* --------------------------------------------------------------------------
   HERO SUBTITLE (tutte le pagine)
   -------------------------------------------------------------------------- */

acf_add_local_field_group([
    'key'    => 'group_hero_subtitle',
    'title'  => 'Hero - Sottotitolo',
    'fields' => [
        [
            'key'   => 'field_hero_subtitle',
            'label' => 'Sottotitolo Hero',
            'name'  => 'hero_subtitle',
            'type'  => 'textarea',
            'rows'  => 2,
            'instructions' => 'Testo sotto il titolo nella sezione hero della pagina.',
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'page',
            ],
        ],
    ],
    'position' => 'side',
]);

/* --------------------------------------------------------------------------
   LIBRERIA — Sezioni contenuto (ACF Flexible Content)
   -------------------------------------------------------------------------- */

acf_add_local_field_group([
    'key'    => 'group_libreria_sezioni',
    'title'  => 'Sezioni Pagina',
    'fields' => [
        [
            'key'    => 'field_libreria_sezioni',
            'label'  => 'Sezioni',
            'name'   => 'sezioni',
            'type'   => 'flexible_content',
            'button_label' => 'Aggiungi sezione',
            'layouts' => [
                [
                    'key'   => 'layout_citazione',
                    'name'  => 'citazione',
                    'label' => 'Citazione',
                    'display' => 'block',
                    'sub_fields' => [
                        [
                            'key'   => 'field_citazione_testo',
                            'label' => 'Testo citazione',
                            'name'  => 'testo',
                            'type'  => 'textarea',
                            'rows'  => 3,
                        ],
                        [
                            'key'   => 'field_citazione_autore',
                            'label' => 'Autore',
                            'name'  => 'autore',
                            'type'  => 'text',
                        ],
                    ],
                ],
                [
                    'key'   => 'layout_carousel',
                    'name'  => 'carousel',
                    'label' => 'Carousel Foto',
                    'display' => 'block',
                    'sub_fields' => [
                        [
                            'key'   => 'field_carousel_immagini',
                            'label' => 'Immagini',
                            'name'  => 'immagini',
                            'type'  => 'gallery',
                            'return_format' => 'array',
                            'min'   => 1,
                        ],
                    ],
                ],
                [
                    'key'   => 'layout_testo',
                    'name'  => 'testo',
                    'label' => 'Testo',
                    'display' => 'block',
                    'sub_fields' => [
                        [
                            'key'   => 'field_testo_titolo',
                            'label' => 'Titolo (opzionale)',
                            'name'  => 'titolo',
                            'type'  => 'text',
                        ],
                        [
                            'key'   => 'field_testo_contenuto',
                            'label' => 'Contenuto',
                            'name'  => 'contenuto',
                            'type'  => 'wysiwyg',
                            'toolbar' => 'basic',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'page',
            ],
            [
                'param'    => 'page_template',
                'operator' => '!=',
                'value'    => 'page-homepage.php',
            ],
        ],
    ],
]);

/* --------------------------------------------------------------------------
   OPZIONI GLOBALI (Footer, Contatti, Social)
   -------------------------------------------------------------------------- */

acf_add_local_field_group([
    'key'    => 'group_global_settings',
    'title'  => 'Impostazioni Globali',
    'fields' => [
        // Contatti
        [
            'key'   => 'field_indirizzo',
            'label' => 'Indirizzo',
            'name'  => 'indirizzo',
            'type'  => 'text',
            'default_value' => 'Via Panisperna 220',
        ],
        [
            'key'   => 'field_orari_lun_sab',
            'label' => 'Orari Lun-Sab',
            'name'  => 'orari_lun_sab',
            'type'  => 'text',
            'default_value' => '10:00 - 20:00',
        ],
        [
            'key'   => 'field_orari_dom',
            'label' => 'Orari Domenica',
            'name'  => 'orari_dom',
            'type'  => 'text',
            'default_value' => '11:00 - 13.30 / 16:30 - 20:00',
        ],
        // Social
        [
            'key'   => 'field_instagram_url',
            'label' => 'Instagram URL',
            'name'  => 'instagram_url',
            'type'  => 'url',
        ],
        [
            'key'   => 'field_facebook_url',
            'label' => 'Facebook URL',
            'name'  => 'facebook_url',
            'type'  => 'url',
        ],
        [
            'key'   => 'field_newsletter_url',
            'label' => 'Newsletter URL',
            'name'  => 'newsletter_url',
            'type'  => 'url',
        ],
        // Letti da noi (shop page)
        [
            'key'   => 'field_shop_letti_products',
            'label' => 'Letti da noi - Prodotti',
            'name'  => 'shop_letti_products',
            'type'  => 'relationship',
            'post_type' => ['product'],
            'return_format' => 'id',
            'min' => 0,
            'max' => 4,
            'instructions' => 'Seleziona 4 libri da mostrare nella sezione "Letti da noi" della pagina Shop.',
        ],
        // Pacchetto Featured (home + shop) — legacy single selector
        [
            'key'   => 'field_featured_pacchetto',
            'label' => 'Pacchetto in evidenza (singolo, legacy)',
            'name'  => 'featured_pacchetto',
            'type'  => 'post_object',
            'post_type' => ['product'],
            'return_format' => 'id',
            'instructions' => 'Legacy: usato come fallback se il campo multiplo sotto è vuoto.',
            'taxonomy' => ['product_type:woosb'],
        ],
        // PH10-PACFEAT: Featured pacchetti (2) — new multi selector
        [
            'key'           => 'field_featured_pacchetti_multi',
            'label'         => 'Pacchetti in evidenza (2)',
            'name'          => 'featured_pacchetti',
            'type'          => 'relationship',
            'post_type'     => ['product'],
            'filters'       => ['search'],
            'min'           => 0,
            'max'           => 2,
            'return_format' => 'id',
            'instructions'  => 'Seleziona fino a 2 pacchetti da mostrare in evidenza. Se vuoto, usa i primi 2 bundle.',
        ],
        // Prefooter (globale, usato come fallback)
        [
            'key'   => 'field_global_prefooter_text',
            'label' => 'Prefooter - Testo (globale)',
            'name'  => 'global_prefooter_text',
            'type'  => 'text',
            'default_value' => 'Hai dei pacchetti da consigliare o dei libri da chiederci?',
        ],
        [
            'key'   => 'field_whatsapp_url',
            'label' => 'WhatsApp - Link canale',
            'name'  => 'whatsapp_url',
            'type'  => 'url',
            'instructions' => 'URL del canale WhatsApp. Se vuoto, il widget "Chiedi in libreria" non appare.',
        ],
        [
            'key'   => 'field_shipping_note',
            'label' => 'Nota spedizione (tooltip prodotto)',
            'name'  => 'shipping_note',
            'type'  => 'textarea',
            'rows'  => 3,
            'instructions' => 'Testo mostrato nel tooltip "Più spese di spedizione" nella pagina prodotto.',
            'default_value' => 'Le spese di spedizione vengono calcolate al checkout in base alla destinazione.',
        ],
        [
            'key'   => 'field_global_prefooter_cta',
            'label' => 'Prefooter - CTA (globale)',
            'name'  => 'global_prefooter_cta',
            'type'  => 'text',
            'default_value' => 'Contattaci',
        ],
        [
            'key'   => 'field_global_prefooter_link',
            'label' => 'Prefooter - Link (globale)',
            'name'  => 'global_prefooter_link',
            'type'  => 'url',
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'options_page',
                'operator' => '==',
                'value'    => 'panisperna-settings',
            ],
        ],
    ],
]);

/* --------------------------------------------------------------------------
   SHOP / PROPOSTE (sezioni)
   -------------------------------------------------------------------------- */

add_action('acf/init', function () {
    if (!function_exists('wc_get_page_id')) return;
    acf_add_local_field_group([
        'key'      => 'group_shop',
        'title'    => 'Shop - Sezioni',
        'fields'   => [
            [
                'key'   => 'field_shop_pacchetti_title',
                'label' => 'Pacchetti - Titolo',
                'name'  => 'shop_pacchetti_title',
                'type'  => 'text',
                'default_value' => 'I nostri pacchetti',
            ],
            [
                'key'   => 'field_shop_pacchetti_description',
                'label' => 'Pacchetti - Sottotitolo',
                'name'  => 'shop_pacchetti_description',
                'type'  => 'textarea',
                'rows'  => 2,
            ],
            [
                'key'   => 'field_shop_letti_title',
                'label' => 'Letti da noi - Titolo',
                'name'  => 'shop_letti_title',
                'type'  => 'text',
                'default_value' => 'Letti da noi',
            ],
            [
                'key'   => 'field_shop_letti_description',
                'label' => 'Letti da noi - Sottotitolo',
                'name'  => 'shop_letti_description',
                'type'  => 'textarea',
                'rows'  => 2,
            ],
            [
                'key'   => 'field_shop_collezione_title',
                'label' => 'La nostra collezione - Titolo',
                'name'  => 'shop_collezione_title',
                'type'  => 'text',
                'default_value' => 'La nostra collezione',
            ],
            [
                'key'   => 'field_shop_collezione_description',
                'label' => 'La nostra collezione - Sottotitolo',
                'name'  => 'shop_collezione_description',
                'type'  => 'textarea',
                'rows'  => 2,
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'page',
                    'operator' => '==',
                    'value'    => wc_get_page_id('shop'),
                ],
            ],
        ],
    ]);
}, 20);

