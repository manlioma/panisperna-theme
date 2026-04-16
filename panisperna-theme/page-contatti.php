<?php
/**
 * Template Name: Contatti
 *
 * @package Panisperna
 */

get_header();

$hero_label       = '';
$hero_title       = 'Contatti';
$hero_image       = '';
include locate_template('template-parts/hero-page.php');
?>

<main class="site-main content-area">
    <section class="section">
        <div class="contatti-layout">
            <div class="contatti-layout__intro">
                <p class="h1" style="font-style:italic;">Scrivici per condividere con noi richieste, opinioni e idee!</p>
            </div>
            <div class="contatti-layout__form">
                <div class="contatti-form-area">
                <form class="contact-form" method="post" action="">
                    <div class="contact-form__row">
                        <div class="contact-form__field">
                            <label for="cf-nome">Nome</label>
                            <input type="text" id="cf-nome" name="nome" required>
                        </div>
                        <div class="contact-form__field">
                            <label for="cf-cognome">Cognome</label>
                            <input type="text" id="cf-cognome" name="cognome" required>
                        </div>
                    </div>
                    <div class="contact-form__field">
                        <label for="cf-email">e-mail</label>
                        <input type="email" id="cf-email" name="email" required>
                    </div>
                    <div class="contact-form__field">
                        <label for="cf-messaggio">
                            Messaggio
                            <span style="float:right;font-weight:400;font-size:14px;">Scrivimi un messaggio e ti risponderò il prima possibile</span>
                        </label>
                        <textarea id="cf-messaggio" name="messaggio" rows="4" maxlength="500" required></textarea>
                        <span class="contact-form__counter">0/500 caratteri</span>
                    </div>
                    <button type="submit" class="contact-form__submit">INVIA</button>
                </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer();
