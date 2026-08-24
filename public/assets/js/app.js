document.addEventListener('click', (event) => {
  const trigger = event.target.closest('[data-faq]');
  if (!trigger) return;
  const item = trigger.closest('.faq-item');
  if (!item) return;
  item.classList.toggle('open');
  const sign = item.querySelector('.sign');
  if (sign) sign.textContent = item.classList.contains('open') ? '−' : '+';
});

document.querySelectorAll('[data-filter]').forEach((btn) => {
  btn.addEventListener('click', () => {
    const value = btn.getAttribute('data-filter');
    const group = btn.getAttribute('data-group') || 'default';
    document.querySelectorAll(`[data-group="${group}"]`).forEach((el) => el.classList.toggle('active', el === btn));
    document.querySelectorAll(`[data-filter-item][data-filter-group="${group}"]`).forEach((el) => {
      const tags = (el.getAttribute('data-filter-item') || '').split(',');
      el.hidden = value !== 'Tout' && !tags.includes(value);
    });
    const count = document.querySelector(`[data-filter-count="${group}"]`);
    if (count) {
      const n = [...document.querySelectorAll(`[data-filter-item][data-filter-group="${group}"]`)].filter((el) => !el.hidden).length;
      count.textContent = n + (n > 1 ? ' éléments' : ' élément');
    }
  });
});

const pwd = document.querySelector('[data-password]');
if (pwd) {
  const bars = document.querySelectorAll('[data-pwd-bar]');
  const checks = document.querySelectorAll('[data-pwd-check]');
  pwd.addEventListener('input', () => {
    const v = pwd.value;
    const rules = [
      v.length >= 12,
      /[a-z]/.test(v) && /[A-Z]/.test(v),
      /[0-9\W]/.test(v),
    ];
    const score = rules.filter(Boolean).length + (v.length >= 16 ? 1 : 0);
    bars.forEach((bar, i) => bar.classList.toggle('on', i < score));
    checks.forEach((el, i) => {
      el.textContent = (rules[i] ? '✓ ' : '· ') + el.getAttribute('data-label');
    });
  });
}

const faqSearch = document.querySelector('[data-faq-search]');
if (faqSearch) {
  faqSearch.addEventListener('input', () => {
    const q = faqSearch.value.trim().toLowerCase();
    document.querySelectorAll('[data-faq-q]').forEach((item) => {
      const text = item.getAttribute('data-faq-q') || '';
      item.hidden = q !== '' && !text.includes(q);
    });
  });
}

document.querySelectorAll('[data-cycle]').forEach((btn) => {
  btn.addEventListener('click', () => {
    const annual = btn.getAttribute('data-cycle') === 'Annuel';
    document.querySelectorAll('[data-cycle]').forEach((el) => el.classList.toggle('active', el === btn));
    document.querySelectorAll('[data-price="complet"]').forEach((el) => { el.textContent = annual ? '49 €' : '4,90 €'; });
    document.querySelectorAll('[data-unit="complet"]').forEach((el) => { el.textContent = annual ? 'par an — 2 mois offerts' : 'par mois'; });
    document.querySelectorAll('[data-price="foyer"]').forEach((el) => { el.textContent = annual ? '79 €' : '7,90 €'; });
    document.querySelectorAll('[data-unit="foyer"]').forEach((el) => { el.textContent = annual ? 'par an — 2 mois offerts' : 'par mois'; });
  });
});
