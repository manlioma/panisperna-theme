(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('form.contact-form');
        if (!form) return;

        var ta = form.querySelector('#cf-messaggio');
        var counter = form.querySelector('.contact-form__counter');
        if (ta && counter) {
            var update = function () {
                counter.textContent = ta.value.length + '/500 caratteri';
            };
            ta.addEventListener('input', update);
            update();
        }

        function showErr(form, btn, origLabel) {
            var existing = form.querySelector('.contact-form__notice--err');
            if (!existing) {
                var err = document.createElement('p');
                err.className = 'contact-form__notice contact-form__notice--err';
                err.setAttribute('role', 'alert');
                err.textContent = 'Invio non riuscito. Riprova piu tardi.';
                form.insertBefore(err, form.querySelector('.contact-form__submit'));
            }
            if (btn) { btn.disabled = false; btn.textContent = origLabel || 'INVIA'; }
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = form.querySelector('.contact-form__submit');
            var origLabel = btn ? btn.textContent : '';
            if (btn) { btn.disabled = true; btn.textContent = 'Invio...'; }

            var fd = new FormData(form);
            fd.append('action', 'panisperna_contact');
            fd.append('nonce', panisperna_contact_data.nonce);

            fetch(panisperna_contact_data.ajax_url, {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            }).then(function (r) { return r.json(); })
              .then(function (data) {
                  if (data && data.success) {
                      var ok = document.createElement('p');
                      ok.className = 'contact-form__notice contact-form__notice--ok';
                      ok.setAttribute('role', 'status');
                      ok.textContent = 'Grazie! Il tuo messaggio e stato inviato.';
                      form.parentNode.replaceChild(ok, form);
                  } else {
                      showErr(form, btn, origLabel);
                  }
              }).catch(function () { showErr(form, btn, origLabel); });
        });
    });
})();
