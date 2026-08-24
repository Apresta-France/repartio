const siteHeader = document.querySelector('[data-site-header]');
const navToggle = document.querySelector('[data-nav-toggle]');
const navBackdrop = document.querySelector('[data-nav-close]');
const sidebar = document.querySelector('#app-sidebar');
const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
const sidebarBackdrop = document.querySelector('[data-sidebar-close]');

const setOpen = (open, header, toggle, backdrop) => {
  if (!header || !toggle) return;
  header.classList.toggle('is-open', open);
  toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  if (backdrop) {
    backdrop.hidden = !open;
    backdrop.classList.toggle('is-on', open);
  }
  document.body.classList.toggle('is-locked', open || Boolean(sidebar?.classList.contains('is-open')));
};

navToggle?.addEventListener('click', () => {
  setOpen(!siteHeader.classList.contains('is-open'), siteHeader, navToggle, navBackdrop);
});
navBackdrop?.addEventListener('click', () => setOpen(false, siteHeader, navToggle, navBackdrop));

sidebarToggle?.addEventListener('click', () => {
  const open = !sidebar.classList.contains('is-open');
  sidebar.classList.toggle('is-open', open);
  sidebarToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  if (sidebarBackdrop) {
    sidebarBackdrop.hidden = !open;
    sidebarBackdrop.classList.toggle('is-on', open);
  }
  document.body.classList.toggle('is-locked', open);
});
sidebarBackdrop?.addEventListener('click', () => {
  sidebar?.classList.remove('is-open');
  sidebarToggle?.setAttribute('aria-expanded', 'false');
  sidebarBackdrop.hidden = true;
  sidebarBackdrop.classList.remove('is-on');
  document.body.classList.remove('is-locked');
});

document.querySelector('[data-builder-side-toggle]')?.addEventListener('click', (event) => {
  const builder = document.querySelector('[data-builder]');
  if (!builder) return;
  const open = !builder.classList.contains('is-palette-open');
  builder.classList.toggle('is-palette-open', open);
  event.currentTarget.setAttribute('aria-expanded', open ? 'true' : 'false');
});

document.addEventListener('keydown', (event) => {
  if (event.key !== 'Escape') return;
  setOpen(false, siteHeader, navToggle, navBackdrop);
  if (sidebar?.classList.contains('is-open')) {
    sidebar.classList.remove('is-open');
    sidebarToggle?.setAttribute('aria-expanded', 'false');
    if (sidebarBackdrop) {
      sidebarBackdrop.hidden = true;
      sidebarBackdrop.classList.remove('is-on');
    }
    document.body.classList.remove('is-locked');
  }
});

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

const shareModal = document.querySelector('[data-share-modal]');
const openShare = () => {
  if (!shareModal) return;
  shareModal.hidden = false;
  document.body.classList.add('is-locked');
};
const closeShare = () => {
  if (!shareModal) return;
  shareModal.hidden = true;
  document.body.classList.remove('is-locked');
};
document.querySelector('[data-share-open]')?.addEventListener('click', openShare);
shareModal?.addEventListener('click', (event) => {
  if (event.target.closest('[data-share-dismiss]')) closeShare();
});
document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape' && shareModal && !shareModal.hidden) closeShare();
});

const slugifyLive = (text) => (text || '')
  .normalize('NFD')
  .replace(/[\u0300-\u036f]/g, '')
  .toLowerCase()
  .replace(/[^a-z0-9]+/g, '-')
  .replace(/^-+|-+$/g, '');

document.querySelectorAll('[data-share-form]').forEach((form) => {
  const title = form.querySelector('[data-share-title]');
  const slug = form.querySelector('[data-share-slug]');
  const copyInput = form.querySelector('[data-copy-value]');
  const prefix = (copyInput?.value || '').replace(/[^/]+$/, '');
  let slugTouched = Boolean(slug?.getAttribute('data-existing'));

  const syncUrl = () => {
    if (!copyInput || !slug) return;
    const value = slugifyLive(slug.value) || 'circuit';
    copyInput.value = prefix + value;
  };

  title?.addEventListener('input', () => {
    if (slugTouched || !slug) return;
    slug.value = slugifyLive(title.value);
    syncUrl();
  });
  slug?.addEventListener('input', () => {
    slugTouched = true;
    slug.value = slugifyLive(slug.value);
    syncUrl();
  });

  form.querySelector('[data-copy]')?.addEventListener('click', async () => {
    const value = copyInput?.value || '';
    if (!value) return;
    try {
      await navigator.clipboard.writeText(value);
      const btn = form.querySelector('[data-copy]');
      if (!btn) return;
      const prev = btn.textContent;
      btn.textContent = 'Copié';
      setTimeout(() => { btn.textContent = prev; }, 1400);
    } catch {
      copyInput?.select();
    }
  });
});

(() => {
  const root = document.querySelector('[data-auth-circuit]');
  if (!root) return;

  const items = [...root.querySelectorAll('[data-appear]')];
  if (!items.length) return;

  const showAll = () => items.forEach((el) => el.classList.add('is-on'));
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    showAll();
    return;
  }

  let timers = [];
  const clearTimers = () => {
    timers.forEach((id) => clearTimeout(id));
    timers = [];
  };

  const play = () => {
    clearTimers();
    root.classList.add('is-reset');
    items.forEach((el) => el.classList.remove('is-on'));
    void root.offsetWidth;
    root.classList.remove('is-reset', 'is-fading');
    items.forEach((el) => {
      const delay = Number(el.getAttribute('data-appear')) || 0;
      timers.push(setTimeout(() => el.classList.add('is-on'), delay));
    });
    timers.push(setTimeout(() => root.classList.add('is-fading'), 15500));
    timers.push(setTimeout(play, 16400));
  };

  const start = () => play();
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      if (entries.some((entry) => entry.isIntersecting)) {
        io.disconnect();
        start();
      }
    }, { threshold: 0.12 });
    io.observe(root);
  } else {
    start();
  }
})();

document.querySelectorAll('[data-flash]').forEach((el) => {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const dismiss = () => {
    if (el.dataset.flashOut) return;
    el.dataset.flashOut = '1';
    const remove = () => {
      const stack = el.closest('.flash-stack');
      el.remove();
      if (stack && !stack.querySelector('[data-flash]')) stack.remove();
    };
    if (reduced) {
      remove();
      return;
    }
    el.classList.add('is-out');
    el.addEventListener('animationend', remove, { once: true });
    setTimeout(remove, 280);
  };
  el.querySelector('[data-flash-close]')?.addEventListener('click', dismiss);
  const delay = el.classList.contains('flash-error') ? 7000 : 5000;
  let timer = setTimeout(dismiss, delay);
  el.addEventListener('mouseenter', () => clearTimeout(timer));
  el.addEventListener('mouseleave', () => { timer = setTimeout(dismiss, 2200); });
});

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
