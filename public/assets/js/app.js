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
  let searchTimer = 0;
  let lastSearch = '';
  const visibleFaqCount = () => [...document.querySelectorAll('[data-faq-q]')].filter((el) => !el.hidden).length;
  faqSearch.addEventListener('input', () => {
    const q = faqSearch.value.trim().toLowerCase();
    document.querySelectorAll('[data-faq-q]').forEach((item) => {
      const text = item.getAttribute('data-faq-q') || '';
      item.hidden = q !== '' && !text.includes(q);
    });
    window.clearTimeout(searchTimer);
    if (q.length < 2) return;
    searchTimer = window.setTimeout(() => {
      if (q === lastSearch || typeof window.rv !== 'function') return;
      lastSearch = q;
      window.rv('search', { term: faqSearch.value.trim(), zero: visibleFaqCount() === 0 });
    }, 700);
  });
}

document.addEventListener('click', (event) => {
  const el = event.target.closest('[data-rv]');
  if (!el || typeof window.rv !== 'function') return;
  const command = el.getAttribute('data-rv');
  const name = el.getAttribute('data-rv-name') || '';
  let props = {};
  try {
    props = JSON.parse(el.getAttribute('data-rv-props') || '{}');
  } catch {
    props = {};
  }
  if (command === 'event' && name) window.rv('event', name, props);
  else if (command === 'signup') window.rv('signup', props);
  else if (command === 'purchase') window.rv('purchase', props);
  else if (command === 'search') window.rv('search', props);
});

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

(() => {
  const root = document.querySelector('[data-hero-demo]');
  if (!root) return;

  const scene = root.querySelector('.hero-scene');
  const stage = root.querySelector('.hero-stage') || root;
  const cursorEl = root.querySelector('[data-hero-cursor]');
  const ghostEl = root.querySelector('[data-hero-ghost]');
  const dragEl = root.querySelector('[data-hero-drag]');
  const zoomEl = root.querySelector('[data-hero-zoom]');
  const unassignedEl = root.querySelector('[data-hero-unassigned]');
  const savedEl = root.querySelector('[data-hero-saved]');
  const nodeEls = [...root.querySelectorAll('[data-hero-node]')];
  const wireEls = [...root.querySelectorAll('[data-hero-wire]')];
  const flowEls = [...root.querySelectorAll('[data-hero-flow]')];
  const NODE_W = 232;
  const PORT_Y = 51;
  const LEP_HOME = { x: 630, y: 520 };
  const compact = window.matchMedia('(max-width: 980px)');

  const nodeMap = Object.fromEntries(nodeEls.map((el) => [el.dataset.heroNode, el]));
  const pos = Object.fromEntries(nodeEls.map((el) => [el.dataset.heroNode, {
    x: Number(el.dataset.x),
    y: Number(el.dataset.y),
  }]));

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  let cam = { x: 310, y: 230, z: 1.16 };
  let cursor = { mode: 'canvas', x: 80, y: 90, on: false, down: false };
  let gen = 0;
  let timers = [];
  let rafs = [];

  const easeInOut = (t) => (t < 0.5 ? 4 * t * t * t : 1 - ((-2 * t + 2) ** 3) / 2);
  const lerp = (a, b, t) => a + (b - a) * t;
  const clamp = (n, a, b) => Math.min(b, Math.max(a, n));

  const chromePad = () => {
    if (compact.matches) return { l: 16, r: 16, t: 44, b: 36 };
    return { l: 24, r: 24, t: 20, b: 20 };
  };

  const viewBox = () => ({
    w: stage.clientWidth,
    h: stage.clientHeight,
    l: stage.offsetLeft,
    t: stage.offsetTop,
  });

  const fitScale = () => {
    const pad = chromePad();
    const box = viewBox();
    const w = Math.max(160, box.w - pad.l - pad.r);
    const h = Math.max(140, box.h - pad.t - pad.b);
    return Math.min(w / 920, h / 720);
  };

  const camOffset = () => {
    const pad = chromePad();
    const box = viewBox();
    const s = fitScale() * cam.z;
    const viewW = box.w - pad.l - pad.r;
    const viewH = box.h - pad.t - pad.b;
    return {
      s,
      tx: pad.l + viewW / 2 - cam.x * s,
      ty: pad.t + viewH / 2 - cam.y * s,
    };
  };

  const sceneToCanvas = (sx, sy) => {
    const { s, tx, ty } = camOffset();
    const box = viewBox();
    return { x: box.l + tx + sx * s, y: box.t + ty + sy * s };
  };

  const portOut = (id) => ({ x: pos[id].x + NODE_W, y: pos[id].y + PORT_Y });
  const portIn = (id) => ({ x: pos[id].x, y: pos[id].y + PORT_Y });

  const curve = (a, b) => {
    const dx = Math.max(50, Math.abs(b.x - a.x) * 0.42);
    return `M ${a.x} ${a.y} C ${a.x + dx} ${a.y}, ${b.x - dx} ${b.y}, ${b.x} ${b.y}`;
  };

  const placeWire = (el) => {
    const a = portOut(el.dataset.from);
    const b = portIn(el.dataset.to);
    el.setAttribute('d', curve(a, b));
    const flow = root.querySelector(`[data-hero-flow="${el.dataset.heroWire}"]`);
    if (!flow) return;
    try {
      const len = el.getTotalLength();
      const pt = el.getPointAtLength(len * 0.55);
      flow.style.left = `${pt.x}px`;
      flow.style.top = `${pt.y - 10}px`;
    } catch {
      flow.style.left = `${(a.x + b.x) / 2}px`;
      flow.style.top = `${(a.y + b.y) / 2 - 10}px`;
    }
  };

  const redrawWires = () => wireEls.forEach(placeWire);

  const cursorCanvas = () => (
    cursor.mode === 'scene' ? sceneToCanvas(cursor.x, cursor.y) : { x: cursor.x, y: cursor.y }
  );

  const paint = () => {
    const { s, tx, ty } = camOffset();
    scene.style.transform = `translate(${tx}px, ${ty}px) scale(${s})`;
    if (zoomEl) zoomEl.textContent = `${Math.round(cam.z * 100)} %`;
    if (!cursorEl) return;
    const p = cursorCanvas();
    const press = cursor.down ? ' scale(.88)' : '';
    cursorEl.style.transform = `translate(${p.x}px, ${p.y}px)${press}`;
    cursorEl.classList.toggle('is-on', cursor.on);
    cursorEl.classList.toggle('is-down', cursor.down);
  };

  const setNodePos = (id, x, y) => {
    pos[id].x = x;
    pos[id].y = y;
    const el = nodeMap[id];
    el.style.left = `${x}px`;
    el.style.top = `${y}px`;
  };

  const select = (id, on = true) => {
    nodeEls.forEach((el) => el.classList.toggle('is-selected', on && el.dataset.heroNode === id));
  };

  const showNode = (id) => {
    const el = nodeMap[id];
    el.classList.remove('is-pending', 'is-leaving');
    el.classList.add('is-on');
  };

  const showWire = (id) => {
    const wire = root.querySelector(`[data-hero-wire="${id}"]`);
    const flow = root.querySelector(`[data-hero-flow="${id}"]`);
    placeWire(wire);
    wire?.classList.remove('is-pending', 'is-leaving');
    wire?.classList.add('is-on');
    flow?.classList.remove('is-pending', 'is-leaving');
    flow?.classList.add('is-on');
  };

  const setVal = (key, text) => {
    const el = root.querySelector(`[data-hero-val="${key}"]`);
    if (!el) return;
    el.textContent = text;
    el.classList.remove('is-flash');
    void el.offsetWidth;
    el.classList.add('is-flash');
  };

  const setUnassigned = (text, ok) => {
    if (!unassignedEl) return;
    unassignedEl.textContent = `non affecté · ${text}`;
    unassignedEl.classList.toggle('is-ok', ok);
    unassignedEl.classList.toggle('is-warn', !ok);
  };

  const setSaved = (text) => {
    if (!savedEl) return;
    const amountEl = savedEl.querySelector('[data-hero-saved-amount]');
    const prev = amountEl ? amountEl.textContent : savedEl.textContent;
    if (amountEl) amountEl.textContent = text;
    else savedEl.textContent = `dans 5 ans · ${text}`;
    const on = text !== '0 €';
    savedEl.classList.toggle('is-on', on);
    savedEl.classList.toggle('is-empty', !on);
    const same = amountEl ? prev === text : prev === `dans 5 ans · ${text}`;
    if (!on || same || root.classList.contains('is-reset')) return;
    savedEl.classList.remove('is-pop');
    void savedEl.offsetWidth;
    savedEl.classList.add('is-pop');
  };

  const applyCircuitVals = (complete) => {
    setVal('compte-reste', complete ? '0 €' : '7 160 €');
    setVal('repart-in', complete ? '3 665 €' : '0 €');
    setVal('repart-pct', complete ? '100 %' : '0 %');
    setVal('prelev-in', complete ? '3 254 €' : '0 €');
    setVal('livreta-in', complete ? '1 466 €' : '0 €');
    setVal('livreta-proj', complete ? '22 950 €' : '0 €');
    setVal('ldds-in', complete ? '1 100 €' : '0 €');
    setVal('ldds-proj', complete ? '12 000 €' : '0 €');
    setVal('lep-in', complete ? '1 100 €' : '0 €');
    setVal('lep-proj', complete ? '10 000 €' : '0 €');
    setUnassigned(complete ? '0 €' : '7 160 €', complete);
    setSaved(complete ? '44 950 €' : '0 €');
  };

  const palettePoint = (kind) => {
    const el = root.querySelector(`[data-hero-kind="${kind}"]`);
    if (!el) return { x: 70, y: 120 };
    const cr = root.getBoundingClientRect();
    const r = el.getBoundingClientRect();
    return { x: r.left - cr.left + r.width / 2, y: r.top - cr.top + r.height / 2 };
  };

  const hotPalette = (kind, dragging = false) => {
    root.querySelectorAll('[data-hero-kind]').forEach((el) => {
      const on = el.dataset.heroKind === kind;
      el.classList.toggle('is-hot', on && !dragging);
      el.classList.toggle('is-dragging', on && dragging);
    });
  };

  const clearPalette = () => {
    root.querySelectorAll('[data-hero-kind]').forEach((el) => {
      el.classList.remove('is-hot', 'is-dragging');
    });
  };

  const ghostHtml = (kind, title, color) => `
    <div class="bar" style="background:${color}"></div>
    <span class="kind" style="color:${color}">${kind}</span>
    <div class="title">${title}</div>
    <div style="height:11px;"></div>
    <i class="port port-in"></i>
    <i class="port port-out" style="background:${color}"></i>
  `;

  const showGhost = (label, title, color, x, y) => {
    ghostEl.hidden = false;
    ghostEl.style.color = color;
    ghostEl.innerHTML = ghostHtml(label, title, color);
    ghostEl.style.left = `${x}px`;
    ghostEl.style.top = `${y}px`;
  };

  const hideGhost = () => { ghostEl.hidden = true; };

  const clearPorts = () => {
    root.querySelectorAll('.hero-node .port').forEach((el) => el.classList.remove('is-armed', 'is-target'));
  };

  const stop = () => {
    gen += 1;
    timers.forEach((id) => clearTimeout(id));
    rafs.forEach((id) => cancelAnimationFrame(id));
    timers = [];
    rafs = [];
  };

  const alive = (token) => token === gen;

  const sleep = (ms, token) => new Promise((resolve) => {
    timers.push(setTimeout(() => resolve(alive(token)), ms));
  });

  const tween = (ms, fn, token, easing = easeInOut) => new Promise((resolve) => {
    const t0 = performance.now();
    const tick = (now) => {
      if (!alive(token)) return resolve(false);
      const t = Math.min(1, (now - t0) / ms);
      fn(easing(t));
      paint();
      if (t < 1) rafs.push(requestAnimationFrame(tick));
      else resolve(true);
    };
    rafs.push(requestAnimationFrame(tick));
  });

  const moveCursorCanvas = (x, y, ms, token) => {
    const from = cursorCanvas();
    cursor.mode = 'canvas';
    return tween(ms, (t) => {
      cursor.x = lerp(from.x, x, t);
      cursor.y = lerp(from.y, y, t);
    }, token);
  };

  const moveCursorScene = (x, y, ms, token) => {
    const from = cursor.mode === 'scene' ? { x: cursor.x, y: cursor.y } : (() => {
      const { s, tx, ty } = camOffset();
      const box = viewBox();
      return { x: (cursor.x - box.l - tx) / s, y: (cursor.y - box.t - ty) / s };
    })();
    cursor.mode = 'scene';
    return tween(ms, (t) => {
      cursor.x = lerp(from.x, x, t);
      cursor.y = lerp(from.y, y, t);
    }, token);
  };

  const cameraTo = (x, y, z, ms, token) => {
    const from = { ...cam };
    return tween(ms, (t) => {
      cam.x = lerp(from.x, x, t);
      cam.y = lerp(from.y, y, t);
      cam.z = lerp(from.z, z, t);
    }, token);
  };

  const distDur = (ax, ay, bx, by, min = 420, max = 1200) => (
    clamp(Math.hypot(bx - ax, by - ay) * 0.85, min, max)
  );

  const together = async (token, ...fns) => {
    const res = await Promise.all(fns);
    return res.every(Boolean) && alive(token);
  };

  const resetState = (complete) => {
    root.classList.add('is-reset');
    nodeEls.forEach((el) => {
      const id = el.dataset.heroNode;
      const pending = !['salaire', 'ae', 'loyers', 'compte'].includes(id);
      setNodePos(id, Number(el.dataset.x), Number(el.dataset.y));
      el.classList.remove('is-selected', 'is-leaving', 'is-on');
      el.classList.toggle('is-pending', pending && !complete);
      if (complete || !pending) el.classList.remove('is-pending');
    });
    wireEls.forEach((el) => {
      const pending = !['j-c', 'ae-c', 'lo-c'].includes(el.dataset.heroWire);
      el.classList.remove('is-leaving');
      el.classList.toggle('is-on', complete || !pending);
      el.classList.toggle('is-pending', pending && !complete);
      placeWire(el);
    });
    flowEls.forEach((el) => {
      const pending = !['j-c', 'ae-c', 'lo-c'].includes(el.dataset.heroFlow);
      el.classList.remove('is-leaving');
      el.classList.toggle('is-on', complete || !pending);
      el.classList.toggle('is-pending', pending && !complete);
    });
    applyCircuitVals(complete);
    hideGhost();
    dragEl.classList.remove('is-on');
    clearPalette();
    clearPorts();
    void root.offsetWidth;
    root.classList.remove('is-reset');
  };

  const applyComplete = () => {
    setNodePos('lep', LEP_HOME.x, LEP_HOME.y);
    resetState(true);
    setNodePos('lep', LEP_HOME.x, LEP_HOME.y);
    redrawWires();
    cam = { x: 460, y: 330, z: 0.98 };
    cursor.on = false;
    paint();
  };

  const play = async () => {
    const token = ++gen;
    resetState(false);
    cam = { x: 380, y: 240, z: 1.08 };
    cursor = { mode: 'scene', x: 140, y: 90, on: false, down: false };
    paint();
    if (!(await sleep(280, token))) return;

    cursor.on = true;
    paint();
    if (!(await moveCursorScene(140, 88, 520, token))) return;
    if (!(await cameraTo(380, 230, 1.14, 900, token))) return;
    if (!(await moveCursorScene(136, 92, 640, token))) return;
    select('salaire');
    if (!(await sleep(420, token))) return;

    if (!(await together(token,
      moveCursorScene(136, 232, 700, token),
      cameraTo(380, 250, 1.12, 700, token),
    ))) return;
    select('ae');
    if (!(await sleep(360, token))) return;

    if (!(await together(token,
      moveCursorScene(430, 168, 820, token),
      cameraTo(430, 200, 1.32, 900, token),
    ))) return;
    select('compte');
    if (!(await sleep(500, token))) return;

    const grab = async (kind, id, label, title, color, focus) => {
      const pal = palettePoint(kind);
      if (!(await moveCursorCanvas(pal.x, pal.y, distDur(cursorCanvas().x, cursorCanvas().y, pal.x, pal.y), token))) return false;
      hotPalette(kind);
      if (!(await sleep(220, token))) return false;
      cursor.down = true;
      hotPalette(kind, true);
      paint();
      const drop = { x: pos[id].x + 36, y: pos[id].y + 18 };
      showGhost(label, title, color, pos[id].x + 80, pos[id].y - 30);
      if (!(await together(token,
        moveCursorScene(drop.x, drop.y, 980, token),
        cameraTo(focus.x, focus.y, focus.z, 980, token),
        tween(980, (t) => {
          const gx = lerp(pos[id].x + 80, pos[id].x, t);
          const gy = lerp(pos[id].y - 30, pos[id].y, t);
          ghostEl.style.left = `${gx}px`;
          ghostEl.style.top = `${gy}px`;
        }, token),
      ))) return false;
      hideGhost();
      showNode(id);
      select(id);
      cursor.down = false;
      clearPalette();
      paint();
      if (!(await sleep(380, token))) return false;
      return true;
    };

    const connect = async (fromId, toId, wireId, focus) => {
      const a = portOut(fromId);
      const b = portIn(toId);
      if (!(await moveCursorScene(a.x, a.y, 620, token))) return false;
      nodeMap[fromId].querySelector('.port-out')?.classList.add('is-armed');
      if (!(await sleep(180, token))) return false;
      cursor.down = true;
      paint();
      dragEl.classList.add('is-on');
      if (!(await tween(860, (t) => {
        const x = lerp(a.x, b.x, t);
        const y = lerp(a.y, b.y, t);
        cursor.x = x;
        cursor.y = y;
        dragEl.setAttribute('d', curve(a, { x, y }));
        if (t > 0.82) nodeMap[toId].querySelector('.port-in')?.classList.add('is-target');
      }, token))) return false;
      dragEl.classList.remove('is-on');
      showWire(wireId);
      cursor.down = false;
      clearPorts();
      paint();
      if (focus) {
        if (!(await cameraTo(focus.x, focus.y, focus.z, 700, token))) return false;
      } else if (!(await sleep(480, token))) return false;
      return alive(token);
    };

    select(null);
    if (!(await grab('depense', 'prelev', 'Dépense', 'Prélèvements', 'oklch(0.55 0.16 25)', { x: 560, y: 150, z: 1.22 }))) return;
    if (!(await connect('compte', 'prelev', 'c-p', { x: 500, y: 160, z: 1.18 }))) return;
    setVal('prelev-in', '3 254 €');
    setVal('compte-reste', '3 906 €');
    setUnassigned('3 906 €', false);
    if (!(await sleep(700, token))) return;

    if (!(await cameraTo(390, 280, 1.08, 800, token))) return;
    if (!(await grab('repartiteur', 'repart', 'Répartiteur', 'Répartiteur épargne', 'oklch(0.68 0.18 38)', { x: 420, y: 300, z: 1.16 }))) return;
    if (!(await connect('compte', 'repart', 'c-r'))) return;
    setVal('compte-reste', '0 €');
    setVal('repart-in', '3 665 €');
    setUnassigned('0 €', true);
    if (!(await sleep(640, token))) return;

    if (!(await cameraTo(540, 280, 1.04, 760, token))) return;
    if (!(await grab('livret', 'livreta', 'Livret', 'Livret A', 'oklch(0.48 0.11 240)', { x: 620, y: 220, z: 1.2 }))) return;
    if (!(await connect('repart', 'livreta', 'r-a'))) return;
    setVal('repart-pct', '40 %');
    setVal('livreta-in', '1 466 €');
    setVal('livreta-proj', '22 950 €');
    setSaved('22 950 €');
    if (!(await together(token,
      cameraTo(720, 230, 1.58, 1100, token),
      moveCursorScene(760, 250, 900, token),
    ))) return;
    select('livreta');
    nodeMap.livreta.querySelector('[data-hero-val="livreta-proj"]')?.classList.add('is-flash');
    if (!(await sleep(1400, token))) return;

    if (!(await cameraTo(560, 340, 1.06, 900, token))) return;
    select(null);
    if (!(await grab('livret', 'ldds', 'Livret', 'LDDS', 'oklch(0.48 0.11 240)', { x: 600, y: 340, z: 1.12 }))) return;
    if (!(await connect('repart', 'ldds', 'r-d'))) return;
    setVal('repart-pct', '70 %');
    setVal('ldds-in', '1 100 €');
    setVal('ldds-proj', '12 000 €');
    setSaved('34 950 €');
    if (!(await sleep(900, token))) return;

    if (!(await grab('livret', 'lep', 'Livret', 'LEP', 'oklch(0.48 0.11 240)', { x: 600, y: 420, z: 1.1 }))) return;
    if (!(await connect('repart', 'lep', 'r-e'))) return;
    setVal('repart-pct', '100 %');
    setVal('lep-in', '1 100 €');
    setVal('lep-proj', '10 000 €');
    setSaved('44 950 €');
    if (!(await sleep(900, token))) return;

    select('lep');
    if (!(await moveCursorScene(pos.lep.x + 86, pos.lep.y + 22, 480, token))) return;
    cursor.down = true;
    paint();
    const from = { ...pos.lep };
    if (!(await together(token,
      cameraTo(600, 460, 1.14, 900, token),
      tween(980, (t) => {
        const x = lerp(from.x, LEP_HOME.x, t);
        const y = lerp(from.y, LEP_HOME.y, t);
        setNodePos('lep', x, y);
        cursor.x = x + 86;
        cursor.y = y + 22;
        redrawWires();
      }, token),
    ))) return;
    cursor.down = false;
    select(null);
    paint();
    if (!(await sleep(500, token))) return;

    if (!(await together(token,
      cameraTo(360, 240, 1.16, 1100, token),
      moveCursorScene(140, 120, 900, token),
    ))) return;
    if (!(await cameraTo(700, 360, 1.2, 2400, token))) return;
    if (!(await cameraTo(460, 330, 0.98, 1200, token))) return;
    if (!(await moveCursorScene(430, 180, 700, token))) return;
    if (!(await sleep(2200, token))) return;

    ['prelev', 'repart', 'livreta', 'ldds', 'lep'].forEach((id) => nodeMap[id].classList.add('is-leaving'));
    ['c-p', 'c-r', 'r-a', 'r-d', 'r-e'].forEach((id) => {
      root.querySelector(`[data-hero-wire="${id}"]`)?.classList.add('is-leaving');
      root.querySelector(`[data-hero-flow="${id}"]`)?.classList.add('is-leaving');
    });
    if (!(await sleep(640, token))) return;
    if (!alive(token)) return;
    play();
  };

  redrawWires();

  let playing = false;
  const freeze = () => {
    root.classList.add('is-static');
    applyComplete();
  };
  const start = () => {
    if (compact.matches || reduced) {
      freeze();
      return;
    }
    root.classList.remove('is-static');
    if (playing) stop();
    playing = true;
    play();
  };
  const park = () => {
    playing = false;
    stop();
    resetState(false);
    cam = { x: 380, y: 240, z: 1.08 };
    cursor.on = false;
    paint();
  };

  if (reduced || compact.matches) {
    freeze();
  } else {
    window.addEventListener('resize', paint);
    const io = 'IntersectionObserver' in window
      ? new IntersectionObserver((entries) => {
        const entry = entries[0];
        if (!entry) return;
        if (entry.isIntersecting) {
          if (document.visibilityState !== 'hidden' && !playing) start();
        } else {
          park();
        }
      }, { threshold: 0.08 })
      : null;
    if (io) io.observe(root);
    else start();

    document.addEventListener('visibilitychange', () => {
      if (compact.matches || reduced) return;
      if (document.visibilityState === 'hidden') {
        park();
        return;
      }
      const r = root.getBoundingClientRect();
      if (r.bottom > 80 && r.top < window.innerHeight - 80) start();
    });
  }

  compact.addEventListener('change', () => {
    if (compact.matches || reduced) {
      stop();
      playing = false;
      freeze();
      return;
    }
    const r = root.getBoundingClientRect();
    if (r.bottom > 80 && r.top < window.innerHeight - 80) start();
    else paint();
  });
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
