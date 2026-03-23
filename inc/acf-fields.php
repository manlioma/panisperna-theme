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
            'key'   => 'field_evento_tipo_tag',
            'label' => 'Tag Tipo (es. GdL, Presentazione)',
            'name'  => 'evento_tipo_tag',
            'type'  => 'text',
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
            'label' => 'Nome persona',
            'name'  => 'consiglio_nome',
            'type'  => 'text',
        ],
        [
            'key'   => 'field_consiglio_foto',
            'label' => 'Foto persona',
            'name'  => 'consiglio_foto',
            'type'  => 'image',
            'return_format' => 'array',
            'preview_size'  => 'consiglio-portrait',
        ],
        [
            'key'   => 'field_consiglio_libri',
            'label' => 'Libri consigliati',
            'name'  => 'consiglio_libri',
            'type'  => 'relationship',
            'post_type' => ['product'],
            'return_format' => 'object',
            'min' => 1,
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
   LIBRERIA (pagina)
   -------------------------------------------------------------------------- */

acf_add_local_field_group([
    'key'    => 'group_libreria',
    'title'  => 'Pagina Libreria',
    'fields' => [
        [
            'key'   => 'field_libreria_hero_heading',
            'label' => 'Titolo Hero',
            'name'  => 'libreria_hero_heading',
            'type'  => 'text',
        ],
        [
            'key'   => 'field_libreria_hero_images',
            'label' => 'Immagini Hero',
            'name'  => 'libreria_hero_images',
            'type'  => 'gallery',
            'return_format' => 'array',
        ],
        [
            'key'   => 'field_libreria_testo',
            'label' => 'Testo descrittivo',
            'name'  => 'libreria_testo',
            'type'  => 'wysiwyg',
        ],
        [
            'key'   => 'field_libreria_galleria',
            'label' => 'Galleria foto libreria',
            'name'  => 'libreria_galleria',
            'type'  => 'gallery',
            'return_format' => 'array',
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'page_template',
                'operator' => '==',
                'value'    => 'page-libreria.php',
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
        // Prefooter (globale, usato come fallback)
        [
            'key'   => 'field_global_prefooter_text',
            'label' => 'Prefooter - Testo (globale)',
            'name'  => 'global_prefooter_text',
            'type'  => 'text',
            'default_value' => 'Hai dei pacchetti da consigliare o dei libri da chiederci?',
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
   CONTRATTI (pagina informativa)
   -------------------------------------------------------------------------- */

acf_add_local_field_group([
    'key'    => 'group_contratti',
    'title'  => 'Pagina Contratti',
    'fields' => [
        [
            'key'   => 'field_contratti_sezioni',
            'label' => 'Sezioni',
            'name'  => 'contratti_sezioni',
            'type'  => 'repeater',
            'layout' => 'block',
            'sub_fields' => [
                [
                    'key'   => 'field_sezione_titolo',
                    'label' => 'Titolo sezione',
                    'name'  => 'titolo',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_sezione_contenuto',
                    'label' => 'Contenuto',
                    'name'  => 'contenuto',
                    'type'  => 'wysiwyg',
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'page_template',
                'operator' => '==',
                'value'    => 'page-contratti.php',
            ],
        ],
    ],
]);
