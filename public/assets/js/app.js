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

(() => {
  const flyout = document.querySelector('[data-palette-flyout]');
  if (!flyout) return;

  const titleEl = flyout.querySelector('[data-palette-flyout-title]');
  const textEl = flyout.querySelector('[data-palette-flyout-text]');
  const compact = window.matchMedia('(max-width: 980px)');

  const hideFlyout = () => {
    flyout.hidden = true;
    flyout.classList.remove('is-below');
    flyout.style.left = '';
    flyout.style.top = '';
    flyout.style.width = '';
  };

  const placeFlyout = (btn) => {
    const hint = btn.dataset.hint;
    if (!hint || btn.classList.contains('is-dragging')) {
      hideFlyout();
      return;
    }
    const label = btn.querySelector('.palette-item-label');
    const dot = btn.querySelector('.dot');
    if (titleEl) titleEl.textContent = label?.textContent?.trim() || '';
    if (textEl) textEl.textContent = hint;
    flyout.style.setProperty('--tip-accent', dot?.style.background || 'var(--line)');
    flyout.hidden = false;
    flyout.classList.toggle('is-below', compact.matches);

    const r = btn.getBoundingClientRect();
    const gap = 12;
    if (compact.matches) {
      flyout.style.width = `${Math.max(160, r.width)}px`;
      flyout.style.left = `${Math.max(8, r.left)}px`;
      flyout.style.top = `${r.bottom + 8}px`;
      const tip = flyout.getBoundingClientRect();
      if (tip.right > window.innerWidth - 8) {
        flyout.style.left = `${Math.max(8, window.innerWidth - tip.width - 8)}px`;
      }
      if (tip.bottom > window.innerHeight - 8) {
        flyout.style.top = `${Math.max(8, r.top - tip.height - 8)}px`;
      }
      return;
    }

    flyout.style.width = '';
    flyout.style.left = `${r.right + gap}px`;
    flyout.style.top = `${r.top + r.height / 2}px`;
    const tip = flyout.getBoundingClientRect();
    if (tip.right > window.innerWidth - 8) {
      flyout.style.left = `${Math.max(8, window.innerWidth - tip.width - 8)}px`;
    }
    if (tip.top < 8) {
      flyout.style.top = `${8 + tip.height / 2}px`;
    } else if (tip.bottom > window.innerHeight - 8) {
      flyout.style.top = `${window.innerHeight - 8 - tip.height / 2}px`;
    }
  };

  document.querySelectorAll('.palette-item[data-hint]').forEach((btn) => {
    btn.addEventListener('pointerenter', () => placeFlyout(btn));
    btn.addEventListener('pointerleave', hideFlyout);
    btn.addEventListener('focus', () => placeFlyout(btn));
    btn.addEventListener('blur', hideFlyout);
    btn.addEventListener('pointerdown', hideFlyout);
  });
  document.querySelector('.builder-side')?.addEventListener('scroll', hideFlyout, { passive: true });
  window.addEventListener('resize', hideFlyout);
})();

document.querySelector('[data-builder-side-toggle]')?.addEventListener('click', (event) => {
  const builder = document.querySelector('[data-builder]');
  if (!builder) return;
  const open = !builder.classList.contains('is-palette-open');
  builder.classList.toggle('is-palette-open', open);
  event.currentTarget.setAttribute('aria-expanded', open ? 'true' : 'false');
});

const closeInfoPops = (except) => {
  document.querySelectorAll('.builder-side-label.is-open').forEach((el) => {
    if (el === except) return;
    el.classList.remove('is-open');
    const btn = el.querySelector('[data-info-toggle]');
    const pop = el.querySelector('.builder-info-pop');
    if (btn) btn.setAttribute('aria-expanded', 'false');
    if (pop) pop.hidden = true;
  });
};

document.addEventListener('click', (event) => {
  const btn = event.target.closest('[data-info-toggle]');
  if (btn) {
    const wrap = btn.closest('.builder-side-label');
    const open = !wrap?.classList.contains('is-open');
    closeInfoPops(open ? wrap : null);
    if (wrap) {
      wrap.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      const pop = wrap.querySelector('.builder-info-pop');
      if (pop) pop.hidden = !open;
    }
    return;
  }
  if (!event.target.closest('.builder-side-label')) closeInfoPops();
});

document.addEventListener('keydown', (event) => {
  if (event.key !== 'Escape') return;
  closeInfoPops();
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

document.querySelectorAll('[data-share-form]').forEach((form) => {
  const copyInput = form.querySelector('[data-copy-value]');

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

(() => {
  const modal = document.querySelector('[data-confirm-modal]');
  if (!modal) return;

  const titleEl = modal.querySelector('[data-confirm-title]');
  const textEl = modal.querySelector('[data-confirm-text]');
  const okBtn = modal.querySelector('[data-confirm-ok]');
  let pending = null;

  const open = (form) => {
    pending = form;
    const name = (form.getAttribute('data-confirm-name') || '').trim();
    const customTitle = (form.getAttribute('data-confirm-title') || '').trim();
    const customText = (form.getAttribute('data-confirm-text') || '').trim();
    if (titleEl) {
      titleEl.textContent = customTitle || (name ? `Supprimer « ${name} » ?` : 'Supprimer ce circuit ?');
    }
    if (textEl) {
      textEl.textContent = customText || 'Cette action est définitive. Le circuit et sa projection seront perdus.';
    }
    modal.hidden = false;
    document.body.classList.add('is-locked');
    okBtn?.focus();
  };

  const close = () => {
    pending = null;
    modal.hidden = true;
    document.body.classList.remove('is-locked');
  };

  document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (form.dataset.confirmed === '1') return;
      event.preventDefault();
      open(form);
    });
  });

  okBtn?.addEventListener('click', () => {
    if (!pending) return;
    const form = pending;
    form.dataset.confirmed = '1';
    close();
    form.submit();
  });

  modal.addEventListener('click', (event) => {
    if (event.target.closest('[data-confirm-dismiss]')) close();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !modal.hidden) close();
  });
})();

document.querySelectorAll('[data-access-row]').forEach((row) => {
  const check = row.querySelector('[data-access-circuit]');
  const select = row.querySelector('select');
  if (!check || !select) return;
  const sync = () => {
    select.disabled = !check.checked;
  };
  check.addEventListener('change', sync);
  row.closest('form')?.addEventListener('submit', () => {
    if (check.checked) select.disabled = false;
  });
  sync();
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
