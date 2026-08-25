(() => {
  const root = document.querySelector('[data-builder]');
  if (!root) return;

  const KINDS = {
    revenu: { label: 'Revenu', color: 'oklch(0.48 0.10 152)', hasIn: false, hasOut: true },
    compte: { label: 'Compte', color: 'oklch(0.48 0.10 248)', hasIn: true, hasOut: true },
    repartiteur: { label: 'Répartiteur', color: 'oklch(0.48 0.12 300)', hasIn: true, hasOut: true },
    livret: { label: 'Livret', color: 'oklch(0.55 0.11 62)', hasIn: true, hasOut: true },
    depense: { label: 'Dépense', color: 'oklch(0.52 0.14 32)', hasIn: true, hasOut: false },
    groupe: { label: 'Groupe', color: 'oklch(0.50 0.04 255)', hasIn: false, hasOut: false, annotation: true },
    note: { label: 'Note', color: 'oklch(0.55 0.10 85)', hasIn: false, hasOut: false, annotation: true },
  };

  const TINTS = {
    slate: { fill: 'oklch(0.96 0.012 255 / 0.58)', stroke: 'oklch(0.78 0.03 255)', ink: 'oklch(0.36 0.04 255)', block: 'oklch(0.48 0.10 248)', label: 'Bleu' },
    teal: { fill: 'oklch(0.95 0.03 192 / 0.52)', stroke: 'oklch(0.70 0.07 192)', ink: 'oklch(0.36 0.07 195)', block: 'oklch(0.48 0.10 152)', label: 'Vert' },
    orange: { fill: 'oklch(0.96 0.05 55 / 0.52)', stroke: 'oklch(0.76 0.10 50)', ink: 'oklch(0.46 0.12 45)', block: 'oklch(0.52 0.14 32)', label: 'Orange' },
    violet: { fill: 'oklch(0.95 0.035 300 / 0.52)', stroke: 'oklch(0.74 0.07 300)', ink: 'oklch(0.38 0.09 300)', block: 'oklch(0.48 0.12 300)', label: 'Violet' },
    rose: { fill: 'oklch(0.96 0.035 15 / 0.52)', stroke: 'oklch(0.76 0.07 15)', ink: 'oklch(0.44 0.10 15)', block: 'oklch(0.52 0.14 15)', label: 'Rose' },
    amber: { fill: 'oklch(0.96 0.055 85 / 0.78)', stroke: 'oklch(0.78 0.09 85)', ink: 'oklch(0.40 0.09 70)', block: 'oklch(0.55 0.11 62)', label: 'Ambre' },
  };

  const DEFAULT_TINT = {
    revenu: 'teal',
    compte: 'slate',
    repartiteur: 'violet',
    livret: 'amber',
    depense: 'orange',
    groupe: 'slate',
    note: 'amber',
  };

  const PRESETS = {
    revenu: [
      { group: 'Modèles', items: [
        { id: 'salaire', title: 'Salaire', hint: 'Revenu mensuel net à saisir.', values: { title: 'Salaire', amount: 0 } },
        { id: 'ae', title: 'Auto-entreprise', hint: 'Chiffre d’affaires lissé sur le mois.', values: { title: 'Auto-entreprise', amount: 0 } },
        { id: 'loyers', title: 'Loyers', hint: 'Revenus locatifs.', values: { title: 'Loyers', amount: 0 } },
        { id: 'alloc', title: 'Allocations', hint: 'Prestations et aides.', values: { title: 'Allocations', amount: 0 } },
      ]},
      { group: 'Libre', items: [
        { id: 'blank', title: 'Partir vierge', hint: 'Un revenu sans préremplissage.', values: { title: 'Nouveau revenu', amount: 0 }, blank: true },
      ]},
    ],
    compte: [
      { group: 'Comptes', items: [
        { id: 'courant', title: 'Compte courant', hint: 'Compte personnel de réception.', values: { title: 'Compte courant' } },
        { id: 'joint-factures', title: 'Joint factures', hint: 'Prélèvements et charges fixes.', values: { title: 'Joint Factures' } },
        { id: 'joint-quotidien', title: 'Joint quotidien', hint: 'Dépenses courantes du foyer.', values: { title: 'Joint Quotidien' } },
        { id: 'pro', title: 'Compte professionnel', hint: 'Encaissements d’activité.', values: { title: 'Compte professionnel' } },
      ]},
      { group: 'Épargne réglementée', items: [
        { id: 'livret-a', title: 'Livret A', hint: 'Taux 1,70 % · plafond 22 950 €', values: { kind: 'livret', title: 'Livret A', rate: 1.7, cap: 22950, start: 0, preset: 'livret-a' } },
        { id: 'ldds', title: 'LDDS', hint: 'Taux 1,70 % · plafond 12 000 €', values: { kind: 'livret', title: 'LDDS', rate: 1.7, cap: 12000, start: 0, preset: 'ldds' } },
        { id: 'lep', title: 'LEP', hint: 'Taux 2,50 % · plafond 10 000 €', values: { kind: 'livret', title: 'LEP', rate: 2.5, cap: 10000, start: 0, preset: 'lep' } },
      ]},
      { group: 'Libre', items: [
        { id: 'blank', title: 'Partir vierge', hint: 'Un compte sans rôle particulier.', values: { title: 'Nouveau compte' }, blank: true },
      ]},
    ],
    livret: [
      { group: 'Épargne réglementée', items: [
        { id: 'livret-a', title: 'Livret A', hint: 'Taux 1,70 % · plafond 22 950 €', values: { title: 'Livret A', rate: 1.7, cap: 22950, start: 0, preset: 'livret-a' } },
        { id: 'ldds', title: 'LDDS', hint: 'Taux 1,70 % · plafond 12 000 €', values: { title: 'LDDS', rate: 1.7, cap: 12000, start: 0, preset: 'ldds' } },
        { id: 'lep', title: 'LEP', hint: 'Taux 2,50 % · plafond 10 000 €', values: { title: 'LEP', rate: 2.5, cap: 10000, start: 0, preset: 'lep' } },
        { id: 'jeune', title: 'Livret Jeune', hint: 'Taux 1,70 % · plafond 1 600 €', values: { title: 'Livret Jeune', rate: 1.7, cap: 1600, start: 0, preset: 'jeune' } },
      ]},
      { group: 'Libre', items: [
        { id: 'blank', title: 'Partir vierge', hint: 'Taux et plafond à saisir vous-même.', values: { title: 'Livret', rate: 0, cap: 0, start: 0 }, blank: true },
      ]},
    ],
    repartiteur: [
      { group: 'Modèles', items: [
        { id: 'epargne', title: 'Épargne', hint: 'Ventile le solde vers les livrets.', values: { title: 'Épargne' } },
        { id: 'parts', title: 'Parts égales', hint: 'À relier ensuite en pourcentages.', values: { title: 'Répartition' } },
      ]},
      { group: 'Libre', items: [
        { id: 'blank', title: 'Partir vierge', hint: 'Un répartiteur sans intitulé.', values: { title: 'Répartiteur' }, blank: true },
      ]},
    ],
    depense: [
      { group: 'Modèles', items: [
        { id: 'prelevements', title: 'Prélèvements', hint: 'Charges fixes du mois.', values: { title: 'Prélèvements' } },
        { id: 'courantes', title: 'Dépenses courantes', hint: 'Quotidien et courses.', values: { title: 'Dépenses courantes' } },
        { id: 'libres', title: 'Dépenses libres', hint: 'Loisir et imprévu.', values: { title: 'Dépenses libres' } },
        { id: 'urssaf', title: 'Cotisations URSSAF', hint: 'Provision mensuelle d’activité.', values: { title: 'Cotisations URSSAF' } },
        { id: 'loyer', title: 'Loyer', hint: 'Loyer ou crédit immobilier.', values: { title: 'Loyer' } },
      ]},
      { group: 'Libre', items: [
        { id: 'blank', title: 'Partir vierge', hint: 'Une dépense sans intitulé.', values: { title: 'Dépense' }, blank: true },
      ]},
    ],
    groupe: [
      { group: 'Regrouper', items: [
        { id: 'famille', title: 'Famille', hint: 'Comptes et dépenses du foyer.', values: { title: 'Famille', tint: 'teal' } },
        { id: 'enfants', title: 'Enfants', hint: 'Allocations, livrets, frais.', values: { title: 'Enfants', tint: 'violet' } },
        { id: 'epargne', title: 'Épargne', hint: 'Livrets et répartiteur.', values: { title: 'Épargne', tint: 'orange' } },
        { id: 'charges', title: 'Charges', hint: 'Prélèvements et fixes.', values: { title: 'Charges', tint: 'rose' } },
        { id: 'activite', title: 'Activité', hint: 'Revenus pro et provisions.', values: { title: 'Activité', tint: 'slate' } },
      ]},
      { group: 'Libre', items: [
        { id: 'blank', title: 'Partir vierge', hint: 'Un cadre à titrer vous-même.', values: { title: 'Groupe', tint: 'slate' }, blank: true },
      ]},
    ],
    note: [
      { group: 'Modèles', items: [
        { id: 'hypothese', title: 'Hypothèse', hint: 'Un chiffre à relire plus tard.', values: { title: 'Hypothèse', tint: 'amber' } },
        { id: 'rappel', title: 'À vérifier', hint: 'Point à confirmer sur le plan.', values: { title: 'À vérifier', tint: 'rose' } },
        { id: 'contexte', title: 'Contexte', hint: 'Pourquoi ce câblage.', values: { title: 'Contexte', tint: 'slate' } },
      ]},
      { group: 'Libre', items: [
        { id: 'blank', title: 'Partir vierge', hint: 'Une note sans intitulé.', values: { title: 'Note', tint: 'amber' }, blank: true },
      ]},
    ],
  };

  const LIVRET_PRESETS = {
    'livret-a': { title: 'Livret A', rate: 1.7, cap: 22950 },
    ldds: { title: 'LDDS', rate: 1.7, cap: 12000 },
    lep: { title: 'LEP', rate: 2.5, cap: 10000 },
    jeune: { title: 'Livret Jeune', rate: 1.7, cap: 1600 },
    custom: { title: 'Livret', rate: 0, cap: 0 },
  };

  const readonly = root.hasAttribute('data-readonly');
  let payloadBroken = false;
  let initial = {};
  try {
    const parsed = JSON.parse(root.getAttribute('data-payload') || '{}');
    initial = parsed && typeof parsed === 'object' ? parsed : {};
  } catch (err) {
    initial = {};
    payloadBroken = true;
  }
  const state = {
    nodes: (initial.nodes || []).map(normalizeNode),
    edges: (initial.edges || []).map(normalizeEdge),
    horizon: clampHorizon(initial.horizon),
    scale: 0.85,
    tx: 24,
    ty: 24,
    selected: null,
    openEdge: null,
    connectFrom: null,
    connectSide: null,
    seq: 1,
  };

  const canvas = root.querySelector('[data-canvas]');
  const layer = root.querySelector('[data-layer]');
  const svg = root.querySelector('[data-edges]');
  const labels = root.querySelector('[data-labels]');
  const nameInput = root.querySelector('[data-name]');
  const payloadInput = root.querySelector('[data-payload-input]');
  const form = root.querySelector('[data-save-form]');
  const propsForm = root.querySelector('[data-props-form]');
  const propsEmpty = root.querySelector('[data-props-empty]');
  const modal = document.querySelector('[data-preset-modal]');
  const presetList = modal?.querySelector('[data-preset-list]');
  const setupModal = document.querySelector('[data-setup-modal]');
  const setupForm = setupModal?.querySelector('[data-setup-form]');
  const setupName = setupModal?.querySelector('[data-setup-name]');
  const setupHorizon = setupModal?.querySelector('[data-setup-horizon]');
  const scenarioModal = document.querySelector('[data-scenario-modal]');
  const reportModal = document.querySelector('[data-report-modal]');
  const reportBody = reportModal?.querySelector('[data-report-body]');
  const reportTitle = reportModal?.querySelector('#report-title');
  const horizonInput = root.querySelector('[data-horizon]');
  const saveBtn = form?.querySelector('[data-save-btn]');
  const horizonUnitWrap = root.querySelector('[data-horizon-unit]');
  const horizonUnitToggle = root.querySelector('[data-horizon-unit-toggle]');
  const horizonUnitMenu = root.querySelector('[data-horizon-unit-menu]');
  const horizonUnitLabel = root.querySelector('[data-horizon-unit-label]');
  let horizonUnit = 'mois';
  let savedSnap = null;
  let saving = false;
  let SCENARIOS = {};
  try {
    SCENARIOS = JSON.parse(document.querySelector('[data-scenarios]')?.textContent || '{}');
  } catch (e) {
    SCENARIOS = {};
  }
  let lastCompute = null;
  let playMonth = Number(initial.horizon) || 60;
  let playPinnedToEnd = true;
  let timeBound = false;
  let pendingDrop = null;
  const flow = { paths: {}, pills: {}, pellets: [] };
  const phase = {};
  let lastTick = 0;
  let flowPaused = false;
  try {
    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    flowPaused = motion.matches;
    motion.addEventListener('change', (ev) => { flowPaused = ev.matches; });
  } catch (e) {}

  function uid(prefix) {
    return prefix + Math.random().toString(36).slice(2, 8);
  }

  function isAnnotation(n) {
    const kind = typeof n === 'string' ? n : n?.kind;
    return Boolean(KINDS[kind]?.annotation);
  }

  function defaultTint(kind) {
    return DEFAULT_TINT[kind] || 'slate';
  }

  function tintOf(n) {
    return TINTS[n.tint] || TINTS[defaultTint(n?.kind)] || TINTS.slate;
  }

  function colorOf(n) {
    return tintOf(n).block || KINDS[n.kind]?.color || '#999';
  }

  function livretFullMonth(C, id) {
    const when = C?.full?.[id];
    return (when === null || when === undefined) ? null : when;
  }

  function isLivretFullNow(n, C) {
    if (n?.kind !== 'livret') return false;
    const when = livretFullMonth(C, n.id);
    return when !== null && currentMonth() >= when;
  }

  function needsAmount(n) {
    if (n?.kind !== 'revenu' && n?.kind !== 'depense') return false;
    return !(Number(n.amount) > 0);
  }

  function accentColorOf(n, C) {
    return isLivretFullNow(n, C) ? TINTS.orange.block : colorOf(n);
  }

  function paintNodeTone(el, n, C) {
    if (!el || isAnnotation(n)) return;
    const color = accentColorOf(n, C);
    const full = isLivretFullNow(n, C);
    el.classList.toggle('is-full', full);
    const bar = el.querySelector('.node-bar');
    const kind = el.querySelector('.node-kind');
    const portIn = el.querySelector('.port-in');
    const portOut = el.querySelector('.port-out');
    if (bar) bar.style.background = color;
    if (kind) kind.style.color = color;
    if (portIn) portIn.style.borderColor = color;
    if (portOut) {
      portOut.style.background = color;
      portOut.style.boxShadow = '0 0 0 1px ' + color;
    }
    state.edges.forEach((e) => {
      if (e.from !== n.id) return;
      const path = flow.paths[e.id];
      if (!path) return;
      path.setAttribute('stroke', e._amt > 0.5 ? color : 'oklch(0.82 0.02 255)');
      flow.pellets.forEach((p) => {
        if (p.eid === e.id) p.c.setAttribute('fill', color);
      });
    });
  }

  function tintPicker(n, mode) {
    const current = TINTS[n.tint] ? n.tint : defaultTint(n.kind);
    return Object.entries(TINTS).map(([id, t]) => {
      const bg = mode === 'block' ? t.block : t.fill;
      const bd = mode === 'block' ? t.block : t.stroke;
      return `<button type="button" class="prop-tint${current === id ? ' is-on' : ''}" data-prop-tint="${id}" style="background:${bg};border-color:${bd}" title="${t.label}" aria-label="${t.label}"></button>`;
    }).join('');
  }

  function tintControl(n, mode) {
    const tint = tintOf(n);
    const bg = mode === 'block' ? tint.block : tint.fill;
    const bd = mode === 'block' ? tint.block : tint.stroke;
    const label = tint.label || 'Couleur';
    return `
      <div class="prop-color">
        <button type="button" class="prop-color-swatch" data-prop-color-toggle style="background:${bg};border-color:${bd}" title="Couleur · ${label}" aria-label="Changer la couleur, ${label}" aria-expanded="false" aria-haspopup="true"></button>
        <div class="prop-color-menu" hidden>
          <div class="prop-tints">${tintPicker(n, mode)}</div>
        </div>
      </div>
    `;
  }

  function closeTintMenus(except) {
    propsForm?.querySelectorAll('.prop-color.is-open').forEach((el) => {
      if (el === except) return;
      el.classList.remove('is-open');
      const btn = el.querySelector('[data-prop-color-toggle]');
      const menu = el.querySelector('.prop-color-menu');
      if (btn) btn.setAttribute('aria-expanded', 'false');
      if (menu) menu.hidden = true;
    });
  }

  function normalizeLineItem(item) {
    return {
      id: item?.id || uid('i'),
      title: typeof item?.title === 'string' ? item.title : '',
      amount: Math.max(0, Number(item?.amount) || 0),
    };
  }

  function depenseItemsTotal(n) {
    return (n.items || []).reduce((sum, item) => sum + Math.max(0, Number(item.amount) || 0), 0);
  }

  function syncDepenseItems(n) {
    if (!n || n.kind !== 'depense') return;
    if (!Array.isArray(n.items)) n.items = [];
    n.amount = depenseItemsTotal(n);
    syncDepenseAmount(n);
  }

  function addDepenseItem(n, title = '') {
    if (!n || n.kind !== 'depense') return null;
    if (!Array.isArray(n.items)) n.items = [];
    const item = normalizeLineItem({ title, amount: 0 });
    n.items.push(item);
    syncDepenseItems(n);
    return item;
  }

  function lectureRows(n, C) {
    return nodeStats(n, C).rows.filter((row) => row[0] !== 'Déjà dessus');
  }

  function refreshLectureStats(n) {
    if (!n || isAnnotation(n) || !propsForm) return;
    const C = lastCompute || compute();
    lastCompute = C;
    const box = propsForm.querySelector('.prop-stats');
    if (box) box.innerHTML = statRowsHtml(lectureRows(n, C), 'prop-stat');
  }

  function refreshDepenseReadout(n) {
    if (!n || n.kind !== 'depense' || !propsForm) return;
    const total = propsForm.querySelector('[data-items-total]');
    if (total) total.textContent = euro(n.amount) + ' / mois';
    refreshLectureStats(n);
    propsForm.querySelectorAll('[data-edge-value]').forEach((input) => {
      const edge = state.edges.find((x) => x.id === input.getAttribute('data-edge-value'));
      if (!edge) return;
      input.value = String(edge.value);
      const amt = input.closest('.prop-link')?.querySelector('.prop-link-amt');
      if (amt) amt.textContent = euro(edge._amt) + ' / mois';
    });
  }

  function normalizeNode(n) {
    const kind = KINDS[n.kind] ? n.kind : 'compte';
    const node = {
      id: n.id || uid('n'),
      kind,
      title: n.title || KINDS[kind]?.label || 'Bloc',
      x: Number.isFinite(Number(n.x)) ? Number(n.x) : 80,
      y: Number.isFinite(Number(n.y)) ? Number(n.y) : 80,
      amount: Number(n.amount) || 0,
      start: Number(n.start) || 0,
      rate: Number(n.rate) || 0,
      cap: Number(n.cap) || 0,
      preset: n.preset || '',
      note: typeof n.note === 'string' ? n.note : '',
      tint: TINTS[n.tint] ? n.tint : defaultTint(kind),
    };
    if (kind === 'depense') {
      if (Array.isArray(n.items)) {
        node.items = n.items.map(normalizeLineItem);
      } else if (node.amount > 0) {
        node.items = [normalizeLineItem({ title: node.title || 'Montant', amount: node.amount })];
      } else {
        node.items = [];
      }
      node.amount = depenseItemsTotal(node);
    }
    if (kind === 'groupe' || kind === 'note') {
      node.w = Math.max(kind === 'groupe' ? 280 : 160, Number(n.w) || (kind === 'groupe' ? 560 : 200));
      if (kind === 'groupe') node.h = Math.max(160, Number(n.h) || 340);
    }
    return node;
  }

  function nodesInside(g, seen) {
    const bag = seen || {};
    const w = g.w || g._w || 560;
    const h = g.h || g._h || 340;
    const found = [];
    state.nodes.forEach((n) => {
      if (n.id === g.id || bag[n.id]) return;
      const nw = n.kind === 'groupe' ? (n.w || n._w || 560) : (n._w || 244);
      const nh = n.kind === 'groupe' ? (n.h || n._h || 340) : (n._h || 100);
      const cx = n.x + nw / 2;
      const cy = n.y + nh / 2;
      if (cx >= g.x && cy >= g.y && cx <= g.x + w && cy <= g.y + h) {
        bag[n.id] = 1;
        found.push(n);
        if (n.kind === 'groupe') found.push(...nodesInside(n, bag));
      }
    });
    return found;
  }

  function normalizeEdge(e) {
    const value = Number(e.value ?? e.amount ?? 0) || 0;
    let mode = e.mode;
    if (!mode) mode = value > 0 ? 'fixe' : 'reste';
    return { id: e.id || uid('e'), from: e.from, to: e.to, mode, value };
  }

  function euro(n) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(n || 0)) + ' €';
  }

  function nodeById(id) {
    return state.nodes.find((n) => n.id === id);
  }

  function applyTransform() {
    layer.style.transform = `translate(${state.tx}px, ${state.ty}px) scale(${state.scale})`;
    const zoom = root.querySelector('[data-zoom]');
    if (zoom) zoom.textContent = Math.round(state.scale * 100) + '%';
  }

  function screenToWorld(cx, cy) {
    const r = canvas.getBoundingClientRect();
    return { x: (cx - r.left - state.tx) / state.scale, y: (cy - r.top - state.ty) / state.scale };
  }

  function compute() {
    const graph = state.nodes.filter((n) => !isAnnotation(n));
    const byId = {};
    const outs = {};
    const indeg = {};
    graph.forEach((n) => { byId[n.id] = n; outs[n.id] = []; indeg[n.id] = 0; });
    state.edges.forEach((e) => {
      if (!byId[e.from] || !byId[e.to]) return;
      outs[e.from].push(e);
      indeg[e.to] += 1;
    });

    const q = graph.filter((n) => indeg[n.id] === 0).map((n) => n.id);
    const order = [];
    const seen = {};
    while (q.length) {
      const id = q.shift();
      if (seen[id]) continue;
      seen[id] = 1;
      order.push(id);
      (outs[id] || []).forEach((e) => {
        indeg[e.to] -= 1;
        if (indeg[e.to] === 0) q.push(e.to);
      });
    }
    const cycle = order.length !== graph.length;
    graph.forEach((n) => { if (!seen[n.id]) order.push(n.id); });

    const inflow = {};
    const kept = {};
    const over = {};
    graph.forEach((n) => { inflow[n.id] = 0; });
    state.edges.forEach((e) => { e._amt = 0; });

    order.forEach((id) => {
      const n = byId[id];
      if (!n) return;
      const av = n.kind === 'revenu' ? Math.max(0, n.amount) : inflow[id];
      const list = outs[id] || [];
      let rem = av;
      let want = 0;

      list.filter((e) => e.mode === 'fixe').forEach((e) => {
        const ask = Math.max(0, e.value);
        want += ask;
        const a = Math.min(ask, Math.max(0, rem));
        e._amt = a;
        rem -= a;
      });
      list.filter((e) => e.mode === 'pct').forEach((e) => {
        const ask = av * Math.max(0, e.value) / 100;
        want += ask;
        const a = Math.min(ask, Math.max(0, rem));
        e._amt = a;
        rem -= a;
      });
      const rest = list.filter((e) => e.mode === 'reste');
      if (rest.length) {
        const share = Math.max(0, rem) / rest.length;
        rest.forEach((e) => { e._amt = share; });
        rem -= share * rest.length;
      }
      over[id] = want > av + 0.5 && list.length > 0;
      kept[id] = Math.max(0, rem);
      list.forEach((e) => { inflow[e.to] += e._amt; });
    });

    const fin = {};
    const full = {};
    const sat = [];
    const livrets = [];
    const usedColors = new Set();
    const deposited = {};
    let proj = 0;
    graph.forEach((n) => {
      if (n.kind !== 'livret') return;
      let b = Math.max(0, n.start);
      const start = b;
      const cap = n.cap > 0 ? n.cap : Infinity;
      const add = kept[n.id] || 0;
      const balances = [b];
      const interests = [0];
      const deposits = [0];
      let cumInterest = 0;
      let cumDeposit = 0;
      let when = null;
      if (Number.isFinite(cap) && b >= cap - 0.5) when = 0;
      let firstDeposit = add;
      for (let m = 1; m <= state.horizon; m += 1) {
        const interest = b * (Math.max(0, n.rate) / 100) / 12;
        cumInterest += interest;
        b += interest;
        const deposit = Number.isFinite(cap) ? Math.min(add, Math.max(0, cap - b)) : add;
        if (m === 1) firstDeposit = deposit;
        cumDeposit += deposit;
        b += deposit;
        balances.push(b);
        interests.push(cumInterest);
        deposits.push(cumDeposit);
        if (when === null && Number.isFinite(cap) && b >= cap - 0.5) when = m;
      }
      deposited[n.id] = firstDeposit;
      fin[n.id] = b;
      full[n.id] = when;
      proj += b;
      if (when !== null) sat.push({ name: n.title, m: when });
      livrets.push({
        id: n.id,
        title: n.title,
        color: chartColorOf(n, usedColors),
        cap: n.cap > 0 ? n.cap : 0,
        add,
        start,
        rate: Math.max(0, n.rate || 0),
        balances,
        interests,
        deposits,
        full: when,
      });
    });

    const total = [];
    const delta = [];
    for (let m = 0; m <= state.horizon; m += 1) {
      let sum = 0;
      livrets.forEach((liv) => { sum += liv.balances[m] || 0; });
      total.push(sum);
      delta.push(m === 0 ? 0 : sum - total[m - 1]);
    }

    let inn = 0;
    let out = 0;
    let saved = 0;
    let leftover = 0;
    graph.forEach((n) => {
      const outAmt = (outs[n.id] || []).reduce((s, e) => s + e._amt, 0);
      if (n.kind === 'revenu') {
        inn += Math.max(0, n.amount);
        leftover += kept[n.id] || 0;
      }
      else if (n.kind === 'depense') out += (kept[n.id] || 0) + outAmt;
      else if (n.kind === 'livret') {
        const add = kept[n.id] || 0;
        const deposit = deposited[n.id] ?? add;
        saved += deposit;
        leftover += Math.max(0, add - deposit);
      }
      else leftover += kept[n.id] || 0;
    });

    return { byId, outs, inflow, kept, over, cycle, inn, out, saved, leftover, proj, fin, full, sat, series: { livrets, total, delta } };
  }

  const CHART_COLORS = [
    'oklch(0.55 0.11 62)',
    'oklch(0.48 0.10 152)',
    'oklch(0.48 0.10 248)',
    'oklch(0.48 0.12 300)',
    'oklch(0.52 0.14 32)',
    'oklch(0.50 0.12 15)',
    'oklch(0.46 0.08 220)',
    'oklch(0.58 0.10 85)',
  ];

  function chartColorOf(n, used) {
    const base = colorOf(n);
    if (!used.has(base)) {
      used.add(base);
      return base;
    }
    const alt = CHART_COLORS.find((c) => !used.has(c)) || CHART_COLORS[used.size % CHART_COLORS.length];
    used.add(alt);
    return alt;
  }

  function signedEuro(n) {
    const v = Math.round(n || 0);
    return (v > 0 ? '+' : '') + euro(v);
  }

  function yearsLabel(months) {
    if (months % 12 !== 0) return months + ' mois';
    const y = months / 12;
    return y + ' an' + (y > 1 ? 's' : '');
  }

  function currentMonth() {
    return Math.min(state.horizon, Math.max(0, playMonth | 0));
  }

  function syncPlayBound() {
    if (playPinnedToEnd) playMonth = state.horizon;
    else playMonth = currentMonth();
  }

  function playLabel(month) {
    if (month <= 0) return 'Aujourd’hui';
    if (month >= state.horizon) return 'Dans ' + yearsLabel(state.horizon);
    return 'Au mois ' + month;
  }

  function playTitle(month) {
    if (month <= 0) return 'Départ';
    if (month >= state.horizon) return 'Horizon · ' + yearsLabel(state.horizon);
    if (month % 12 === 0) return 'Mois ' + month + ' · ' + yearsLabel(month);
    return 'Mois ' + month;
  }

  const REPORT_HABIT = /course|resto|restaurant|caf[eé]|sortie|loisir|essence|abonnement/i;

  function reportSpanLabel(month) {
    if (month <= 0) return '';
    return yearsLabel(month);
  }

  function reportMarks() {
    const H = state.horizon;
    const marks = [];
    const add = (m) => {
      const n = Math.min(H, Math.max(0, Math.round(Number(m) || 0)));
      if (n > 0 && !marks.includes(n)) marks.push(n);
    };
    [12, 24, 36, 60, 120, H, currentMonth()].forEach(add);
    return marks.sort((a, b) => a - b);
  }

  function reportSpendLines() {
    const C = lastCompute || compute();
    lastCompute = C;
    const bag = {};
    state.nodes.forEach((n) => {
      if (n.kind !== 'depense') return;
      const items = (n.items || []).filter((item) => (item.amount || 0) > 0.5 || String(item.title || '').trim());
      if (items.length) {
        items.forEach((item) => {
          const monthly = Math.max(0, Number(item.amount) || 0);
          if (monthly < 0.5) return;
          const title = String(item.title || '').trim() || n.title || 'Poste';
          const key = title.toLowerCase();
          if (!bag[key]) bag[key] = { title, monthly: 0 };
          bag[key].monthly += monthly;
        });
        return;
      }
      const monthly = Math.max(0, Number(n.amount) || C.inflow[n.id] || 0);
      if (monthly < 0.5) return;
      const title = n.title || 'Dépense';
      const key = title.toLowerCase();
      if (!bag[key]) bag[key] = { title, monthly: 0 };
      bag[key].monthly += monthly;
    });
    return Object.values(bag)
      .map((row) => ({ ...row, total: row.monthly * currentMonth() }))
      .sort((a, b) => b.total - a.total || b.monthly - a.monthly);
  }

  function reportInsights(lines, month) {
    if (month <= 0 || !lines.length) return [];
    const span = reportSpanLabel(month);
    const picked = [];
    lines.forEach((row) => {
      if (picked.length >= 3) return;
      if (REPORT_HABIT.test(row.title)) picked.push(row);
    });
    lines.forEach((row) => {
      if (picked.length >= 3) return;
      if (!picked.includes(row)) picked.push(row);
    });
    return picked.map((row) => ({
      title: row.title,
      text: 'un budget de ' + euro(row.monthly) + '/mois pendant ' + span + ', soit ' + euro(row.total) + '.',
    }));
  }

  function playTotal(C) {
    const series = C?.series;
    if (!series || !series.total) return C?.proj || 0;
    return series.total[currentMonth()] ?? (C.proj || 0);
  }

  function livretOf(C, id) {
    return (C?.series?.livrets || []).find((l) => l.id === id) || null;
  }

  function livretAt(C, id, month) {
    const liv = livretOf(C, id);
    if (!liv) return C?.fin?.[id] || 0;
    return liv.balances[month] ?? (liv.balances[liv.balances.length - 1] || 0);
  }

  function livretInterestAt(C, id, month) {
    const liv = livretOf(C, id);
    if (!liv || !liv.interests) return 0;
    return liv.interests[month] ?? (liv.interests[liv.interests.length - 1] || 0);
  }

  function livretYearRows(liv) {
    const horizon = (liv?.balances?.length || 1) - 1;
    const rows = [];
    if (!liv || horizon < 1) return rows;
    let prev = 0;
    let year = 1;
    while ((year - 1) * 12 < horizon) {
      const month = Math.min(year * 12, horizon);
      const months = month - (year - 1) * 12;
      const cum = liv.interests?.[month] || 0;
      rows.push({
        year,
        month,
        months,
        label: months === 12
          ? 'An ' + year
          : (year === 1 && horizon < 12 ? months + ' mois' : 'An ' + year + ' · ' + months + ' mois'),
        produced: cum - prev,
        cum,
        put: (liv.start || 0) + (liv.deposits?.[month] || 0),
        balance: liv.balances[month] || 0,
      });
      prev = cum;
      year += 1;
    }
    return rows;
  }

  function livretInterestHtml(n, C) {
    if (n.kind !== 'livret' || !(n.rate > 0)) return '';
    const liv = livretOf(C, n.id);
    if (!liv) return '';
    const end = state.horizon;
    const interest = liv.interests?.[end] || 0;
    const put = (liv.start || 0) + (liv.deposits?.[end] || 0);
    const bal = liv.balances[end] || 0;
    const years = livretYearRows(liv);
    const now = currentMonth();
    const share = bal > 0.5 ? Math.min(100, Math.round((interest / bal) * 100)) : 0;
    const putPct = bal > 0.5 ? Math.max(0, 100 - share) : 100;
    return `
      <div class="prop-interest" data-interest>
        <div class="eyebrow">Intérêts dans le temps</div>
        <p class="builder-hint prop-interest-lead">À l’horizon, le solde projeté = ce que vous versez + ce que le taux produit.</p>
        <div class="prop-interest-kpis">
          <div>
            <span>Versé</span>
            <b class="mono">${euro(put)}</b>
          </div>
          <div>
            <span>Intérêts</span>
            <b class="mono is-proj">${euro(interest)}</b>
          </div>
        </div>
        <div class="prop-interest-mix" aria-hidden="true">
          <i class="is-put" style="width:${putPct}%"></i>
          <i class="is-yield" style="width:${share}%"></i>
        </div>
        <div class="prop-interest-mix-legend">
          <span>Versements</span>
          <span>${share ? share + ' % du solde' : 'Pas encore d’intérêts'}</span>
        </div>
        <div class="prop-interest-years">
          ${years.map((row) => {
            const current = now > (row.year - 1) * 12 && now <= row.month;
            return `<div class="prop-interest-year${current ? ' is-now' : ''}" data-interest-year="${row.year}">
              <div class="prop-interest-year-top">
                <span>${escapeHtml(row.label)}</span>
                <b class="mono">${euro(row.balance)}</b>
              </div>
              <div class="prop-interest-year-sub">
                <span>${signedEuro(row.produced)} cette année</span>
                <span>${euro(row.cum)} cumulés</span>
              </div>
            </div>`;
          }).join('')}
        </div>
      </div>
    `;
  }

  function paintInterestYears() {
    if (!propsForm) return;
    const now = currentMonth();
    propsForm.querySelectorAll('[data-interest-year]').forEach((el) => {
      const year = Number(el.dataset.interestYear);
      const start = (year - 1) * 12;
      const end = Math.min(year * 12, state.horizon);
      el.classList.toggle('is-now', now > start && now <= end);
    });
  }

  function refreshLivretInterest(n) {
    if (!n || n.kind !== 'livret' || !propsForm) return;
    const C = lastCompute || compute();
    lastCompute = C;
    const box = propsForm.querySelector('.prop-stats');
    if (box) box.innerHTML = statRowsHtml(lectureRows(n, C), 'prop-stat');
    const host = propsForm.querySelector('[data-interest]');
    const html = livretInterestHtml(n, C).trim();
    if (host && html) {
      const tmp = document.createElement('div');
      tmp.innerHTML = html;
      const next = tmp.firstElementChild;
      if (next) host.replaceWith(next);
    } else if (host && !html) {
      host.remove();
    } else if (!host && html) {
      const lecture = propsForm.querySelector('.prop-stats')?.closest('div');
      if (lecture) lecture.insertAdjacentHTML('afterend', html);
    }
  }

  function setPlayMonth(month, fromUser) {
    const next = Math.min(state.horizon, Math.max(0, Math.round(Number(month) || 0)));
    if (fromUser) playPinnedToEnd = next >= state.horizon;
    playMonth = next;
    syncTimePlay();
    refreshPlayReadings();
  }

  function timeMonthFromX(clientX) {
    const stage = root.querySelector('.builder-time-stage');
    if (!stage) return currentMonth();
    const r = stage.getBoundingClientRect();
    const t = (clientX - r.left) / Math.max(1, r.width);
    return Math.round(Math.min(1, Math.max(0, t)) * state.horizon);
  }

  function timeTicks(horizon) {
    const step = horizon <= 12 ? 3 : horizon <= 36 ? 6 : horizon <= 120 ? 12 : 24;
    const out = [0];
    for (let m = step; m < horizon; m += step) out.push(m);
    if (out[out.length - 1] !== horizon) out.push(horizon);
    return out;
  }

  function svgEl(name, attrs) {
    const el = document.createElementNS('http://www.w3.org/2000/svg', name);
    Object.entries(attrs || {}).forEach(([k, v]) => {
      if (v !== undefined && v !== null) el.setAttribute(k, String(v));
    });
    return el;
  }

  function areaPath(top, bottom, w, y0, y1, max) {
    const n = top.length - 1;
    if (n < 1) return '';
    const xAt = (i) => (i / n) * w;
    const yAt = (v) => y1 - (max > 0 ? (Math.max(0, v) / max) * (y1 - y0) : 0);
    let d = 'M 0 ' + yAt(top[0]).toFixed(1);
    for (let i = 1; i <= n; i += 1) d += ' L ' + xAt(i).toFixed(1) + ' ' + yAt(top[i]).toFixed(1);
    for (let i = n; i >= 0; i -= 1) d += ' L ' + xAt(i).toFixed(1) + ' ' + yAt(bottom[i]).toFixed(1);
    return d + ' Z';
  }

  function timeFullMarks(livrets, horizon) {
    const bag = {};
    (livrets || []).forEach((liv) => {
      if (liv.full === null || liv.full === undefined) return;
      const m = liv.full;
      if (m < 0 || m > horizon) return;
      if (!bag[m]) bag[m] = [];
      bag[m].push(liv);
    });
    return Object.keys(bag).map(Number).sort((a, b) => a - b).map((m) => ({ month: m, livrets: bag[m] }));
  }

  function timeFullLabel(mark) {
    const names = mark.livrets.map((liv) => liv.title).join(', ');
    const many = mark.livrets.length > 1;
    if (mark.month === 0) return names + (many ? ' déjà pleins' : ' déjà plein');
    const when = mark.month % 12 === 0 ? yearsLabel(mark.month) : 'mois ' + mark.month;
    return names + (many ? ' pleins' : ' plein') + ' · ' + when;
  }

  function paintTimeFulls() {
    const host = root.querySelector('[data-time-fulls]');
    if (!host) return;
    const C = lastCompute;
    const totals = C?.series?.total || [];
    const marks = timeFullMarks(C?.series?.livrets, state.horizon);
    const max = Math.max(1, ...totals) * 1.08;
    const now = currentMonth();
    const Y0 = 3;
    const Y1 = 29;
    host.innerHTML = marks.map((mark) => {
      const left = state.horizon ? (mark.month / state.horizon) * 100 : 0;
      const val = totals[mark.month] || 0;
      const y = Y1 - (max > 0 ? (Math.max(0, val) / max) * (Y1 - Y0) : 0);
      const top = (y / 32) * 100;
      const label = timeFullLabel(mark);
      const reached = now >= mark.month;
      const short = mark.livrets.map((liv) => liv.title).join(', ');
      return `<button type="button" class="builder-time-full${reached ? ' is-reached' : ''}" data-time-full="${mark.month}" style="left:${left.toFixed(2)}%" title="${escapeAttr(label)}" aria-label="${escapeAttr(label)}"><span>${escapeHtml(short)}</span><i style="top:${top.toFixed(2)}%"></i></button>`;
    }).join('');
  }

  function linePath(values, w, y0, y1, max) {
    const n = values.length - 1;
    if (n < 1) return '';
    const xAt = (i) => (i / n) * w;
    const yAt = (v) => y1 - (max > 0 ? (Math.max(0, v) / max) * (y1 - y0) : 0);
    return values.map((v, i) => (i ? 'L' : 'M') + ' ' + xAt(i).toFixed(1) + ' ' + yAt(v).toFixed(1)).join(' ');
  }

  function drawTimeChart() {
    const svg = root.querySelector('[data-time-svg]');
    const empty = root.querySelector('[data-time-empty]');
    const ticks = root.querySelector('[data-time-ticks]');
    const scrub = root.querySelector('[data-time-scrub]');
    const C = lastCompute || compute();
    lastCompute = C;
    syncPlayBound();
    const series = C.series || { livrets: [], total: [0], delta: [0] };
    const livrets = series.livrets || [];
    const has = livrets.length > 0;
    if (empty) empty.hidden = has;
    const timeBox = root.querySelector('[data-time]');
    if (timeBox) timeBox.classList.toggle('is-empty', !has);
    if (scrub) {
      scrub.max = String(state.horizon);
      scrub.value = String(currentMonth());
    }
    if (ticks) {
      ticks.innerHTML = timeTicks(state.horizon).map((m) => {
        const left = state.horizon ? (m / state.horizon) * 100 : 0;
        return `<span style="left:${left.toFixed(2)}%">${m === 0 ? 'M0' : yearsLabel(m)}</span>`;
      }).join('');
    }
    if (!svg) return;
    svg.innerHTML = '';
    const W = 1000;
    const Y0 = 3;
    const Y1 = 29;
    const max = Math.max(1, ...(series.total || [0])) * 1.08;
    const played = state.horizon ? currentMonth() / state.horizon : 0;
    const defs = svgEl('defs');
    const grad = svgEl('linearGradient', { id: 'time-fill-total', x1: '0', y1: '0', x2: '0', y2: '1' });
    grad.appendChild(svgEl('stop', { offset: '0%', 'stop-color': 'oklch(0.58 0.12 195)', 'stop-opacity': '0.38' }));
    grad.appendChild(svgEl('stop', { offset: '100%', 'stop-color': 'oklch(0.58 0.12 195)', 'stop-opacity': '0' }));
    defs.appendChild(grad);
    const clip = svgEl('clipPath', { id: 'time-played' });
    clip.appendChild(svgEl('rect', { 'data-time-clip': '', x: '0', y: '0', width: (played * W).toFixed(1), height: '32' }));
    defs.appendChild(clip);
    svg.appendChild(defs);

    const zeros = (series.total || []).map(() => 0);
    if (series.total && series.total.length > 1) {
      svg.appendChild(svgEl('path', {
        d: areaPath(series.total, zeros, W, Y0, Y1, max),
        fill: 'url(#time-fill-total)',
        opacity: '0.45',
      }));
      svg.appendChild(svgEl('path', {
        d: areaPath(series.total, zeros, W, Y0, Y1, max),
        fill: 'url(#time-fill-total)',
        'clip-path': 'url(#time-played)',
      }));
      svg.appendChild(svgEl('path', {
        d: linePath(series.total, W, Y0, Y1, max),
        fill: 'none',
        stroke: 'oklch(0.62 0.04 195)',
        'stroke-width': '2.8',
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-dasharray': '4 5',
        opacity: '0.42',
        'vector-effect': 'non-scaling-stroke',
      }));
      svg.appendChild(svgEl('path', {
        d: linePath(series.total, W, Y0, Y1, max),
        fill: 'none',
        stroke: 'oklch(0.42 0.12 195)',
        'stroke-width': '3.2',
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'clip-path': 'url(#time-played)',
        'vector-effect': 'non-scaling-stroke',
      }));
    }
    paintTimeFulls();
  }

  function syncTimePlay() {
    const month = currentMonth();
    const C = lastCompute;
    const series = C?.series;
    const total = playTotal(C);
    const dlt = series?.delta?.[month] || 0;
    const set = (sel, val) => { const el = root.querySelector(sel); if (el) el.textContent = val; };
    set('[data-time-label]', playTitle(month));
    set('[data-time-total]', euro(total));
    set('[data-time-delta]', signedEuro(dlt));
    const deltaEl = root.querySelector('[data-time-delta]');
    if (deltaEl) deltaEl.classList.toggle('is-flat', Math.abs(dlt) < 0.5);
    const scrub = root.querySelector('[data-time-scrub]');
    if (scrub) {
      if (scrub.max !== String(state.horizon)) scrub.max = String(state.horizon);
      if (scrub.value !== String(month)) scrub.value = String(month);
    }
    const pct = state.horizon ? (month / state.horizon) * 100 : 0;
    const plot = root.querySelector('[data-time-plot]');
    if (plot) plot.style.setProperty('--pct', pct.toFixed(2) + '%');
    const clip = root.querySelector('[data-time-clip]');
    if (clip) clip.setAttribute('width', ((pct / 100) * 1000).toFixed(1));
    const cursor = root.querySelector('[data-time-cursor]');
    if (cursor) cursor.style.left = pct + '%';
    root.querySelectorAll('[data-time-full]').forEach((el) => {
      const at = Number(el.getAttribute('data-time-full'));
      el.classList.toggle('is-reached', month >= at);
    });
  }

  function showTimeTip(month, clientX) {
    const tip = root.querySelector('[data-time-tip]');
    const ghost = root.querySelector('[data-time-ghost]');
    const stage = root.querySelector('.builder-time-stage');
    const C = lastCompute;
    if (!tip || !stage || !C?.series) return;
    const selected = nodeById(state.selected);
    const liv = selected?.kind === 'livret' ? livretOf(C, selected.id) : null;
    const total = liv ? (liv.balances[month] || 0) : (C.series.total[month] || 0);
    const prev = liv
      ? (month > 0 ? (liv.balances[month - 1] || 0) : liv.balances[0] || 0)
      : (month > 0 ? (C.series.total[month - 1] || 0) : C.series.total[0] || 0);
    const dlt = liv ? total - prev : (C.series.delta[month] || 0);
    const yieldAt = liv ? (liv.interests?.[month] || 0) : 0;
    const satNow = (C.series.livrets || []).filter((item) => item.full === month);
    const satHtml = satNow.length
      ? `<em class="is-full">${escapeHtml(satNow.map((item) => item.title).join(', '))} plein${satNow.length > 1 ? 's' : ''}</em>`
      : '';
    tip.hidden = false;
    tip.innerHTML = `<strong>${playTitle(month)}</strong><span class="mono">${euro(total)}</span><em>${signedEuro(dlt)}</em>${liv && (liv.rate || 0) > 0 ? `<em class="is-yield">${euro(yieldAt)} d’intérêts</em>` : ''}${satHtml}`;
    const r = stage.getBoundingClientRect();
    const x = Math.min(r.width - 8, Math.max(8, clientX - r.left));
    tip.style.left = x + 'px';
    if (ghost) {
      ghost.hidden = false;
      ghost.style.left = (state.horizon ? (month / state.horizon) * 100 : 0) + '%';
    }
  }

  function hideTimeTip() {
    const tip = root.querySelector('[data-time-tip]');
    const ghost = root.querySelector('[data-time-ghost]');
    if (tip) tip.hidden = true;
    if (ghost) ghost.hidden = true;
  }

  function refreshPlayReadings() {
    const C = lastCompute;
    if (!C) return;
    state.nodes.forEach((n) => {
      if (isAnnotation(n)) return;
      const el = layer.querySelector(`[data-node="${CSS.escape(n.id)}"]`);
      if (!el) return;
      const stats = nodeStats(n, C);
      stats.rows.forEach(([k, v, cls, key]) => {
        if (!key) return;
        const row = el.querySelector(`[data-play="${key}"]`);
        if (!row) return;
        const label = row.querySelector('span');
        const val = row.querySelector('b');
        if (label) label.textContent = k;
        if (val) val.textContent = v;
      });
      if (n.kind === 'livret') {
        paintNodeTone(el, n, C);
        if (stats.chip && !stats.chip.bad) {
          const chip = el.querySelector('.node-chip:not(.is-bad)');
          if (chip) {
            chip.textContent = stats.chip.text;
            chip.classList.toggle('is-full', !!stats.chip.full);
          }
        }
      }
    });
    if (propsForm && !propsForm.hidden) {
      const n = nodeById(state.selected);
      if (n && !isAnnotation(n)) {
        const stats = nodeStats(n, C);
        stats.rows.forEach(([k, v, cls, key]) => {
          if (!key) return;
          const row = propsForm.querySelector(`[data-play="${key}"]`);
          if (!row) return;
          const label = row.querySelector('span');
          const val = row.querySelector('b');
          if (label) label.textContent = k;
          if (val) val.textContent = v;
        });
        paintInterestYears();
      }
    }
    renderSide();
  }

  function bindTimeControls() {
    if (timeBound) return;
    const plot = root.querySelector('[data-time-plot]');
    const scrub = root.querySelector('[data-time-scrub]');
    if (!plot && !scrub) return;
    timeBound = true;
    let dragging = false;

    const applyFromEvent = (ev) => {
      setPlayMonth(timeMonthFromX(ev.clientX), true);
    };

    plot?.addEventListener('pointerdown', (ev) => {
      if (ev.target.closest('.builder-time-empty')) return;
      const mark = ev.target.closest('[data-time-full]');
      if (mark) {
        ev.preventDefault();
        ev.stopPropagation();
        setPlayMonth(Number(mark.getAttribute('data-time-full')) || 0, true);
        return;
      }
      dragging = true;
      plot.setPointerCapture?.(ev.pointerId);
      applyFromEvent(ev);
      ev.preventDefault();
    });
    plot?.addEventListener('pointermove', (ev) => {
      const month = timeMonthFromX(ev.clientX);
      if (dragging) setPlayMonth(month, true);
      else showTimeTip(month, ev.clientX);
    });
    plot?.addEventListener('pointerup', (ev) => {
      dragging = false;
      try { plot.releasePointerCapture?.(ev.pointerId); } catch (e) {}
    });
    plot?.addEventListener('pointerleave', () => {
      if (!dragging) hideTimeTip();
    });
    scrub?.addEventListener('input', () => {
      setPlayMonth(parseInt(scrub.value, 10), true);
    });
    root.querySelectorAll('[data-time-step]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const step = Number(btn.getAttribute('data-time-step')) || 0;
        setPlayMonth(currentMonth() + step, true);
      });
    });
    if (typeof ResizeObserver !== 'undefined') {
      const box = root.querySelector('[data-time]');
      if (box) {
        let raf = 0;
        const ro = new ResizeObserver(() => {
          cancelAnimationFrame(raf);
          raf = requestAnimationFrame(() => {
            if (lastCompute) {
              drawTimeChart();
              syncTimePlay();
            }
          });
        });
        ro.observe(box);
      }
    }
  }

  function portPoint(n, side) {
    const w = n._w || 244;
    const h = n._h || 102;
    return side === 'out' ? { x: n.x + w, y: n.y + 51 } : { x: n.x, y: n.y + 51 };
  }

  function curve(a, b) {
    const dx = Math.max(50, Math.abs(b.x - a.x) * 0.42);
    return `M ${a.x} ${a.y} C ${a.x + dx} ${a.y}, ${b.x - dx} ${b.y}, ${b.x} ${b.y}`;
  }

  function pointAtX(path, len, tx) {
    let lo = 0;
    let hi = len;
    let pt;
    for (let i = 0; i < 22; i += 1) {
      const mid = (lo + hi) / 2;
      pt = path.getPointAtLength(mid);
      if (pt.x < tx) lo = mid;
      else hi = mid;
    }
    return path.getPointAtLength((lo + hi) / 2);
  }

  function overlapsNode(pt, n) {
    if (!n || n.kind === 'groupe') return false;
    const w = n._w || 244;
    const h = n._h || 100;
    return pt.x + 36 > n.x && pt.x - 36 < n.x + w && pt.y + 10 > n.y && pt.y - 10 < n.y + h;
  }

  function dodgeNodes(pt, path, fromId, toId) {
    const hit = state.nodes.some((n) => n.id !== fromId && n.id !== toId && overlapsNode(pt, n));
    if (!hit || !path) return pt;
    const len = path.getTotalLength();
    if (!len) return pt;
    for (let i = 1; i <= 12; i += 1) {
      const t = 0.18 + (0.64 * i) / 12;
      const q = path.getPointAtLength(len * t);
      if (!state.nodes.some((n) => n.id !== fromId && n.id !== toId && overlapsNode(q, n))) return q;
    }
    return pt;
  }

  function labelPoint(path, a, b, idx) {
    const len = path.getTotalLength();
    if (!len) return { x: (a.x + b.x) / 2, y: (a.y + b.y) / 2 };
    const ax = a.x + (a._w || 244);
    const bx = b.x;
    let mid;
    if (bx - ax > 52) {
      const span = bx - ax;
      mid = pointAtX(path, len, ax + span * Math.min(0.78, 0.42 + idx * 0.14));
    } else {
      mid = path.getPointAtLength(len * Math.min(0.72, 0.46 + idx * 0.1));
    }
    return dodgeNodes(mid, path, a.id, b.id);
  }

  function spawnPellets(e, path, color) {
    if (flowPaused || e._amt <= 0.5) return;
    const len = path.getTotalLength();
    if (!len) return;
    const count = Math.min(5, 1 + Math.floor(e._amt / 700));
    const speed = 48 + Math.min(70, e._amt / 28);
    const prev = phase[e.id] || [];
    const keep = [];
    const r = 2.6 + Math.min(2.2, e._amt / 2600);
    for (let i = 0; i < count; i += 1) {
      const c = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
      c.setAttribute('r', String(r));
      c.setAttribute('class', 'pellet');
      c.setAttribute('fill', color);
      svg.appendChild(c);
      const d0 = (prev[i] !== undefined && prev[i] < len) ? prev[i] : (len / count) * i;
      keep.push(d0);
      flow.pellets.push({ c, path, len, d: d0, v: speed, eid: e.id, idx: i });
    }
    phase[e.id] = keep;
  }

  function redrawWires() {
    const C = lastCompute;
    if (!C) return;
    state.edges.forEach((e) => {
      const path = flow.paths[e.id];
      const a = nodeById(e.from);
      const b = nodeById(e.to);
      if (!path || !a || !b) return;
      path.setAttribute('d', curve(portPoint(a, 'out'), portPoint(b, 'in')));
      const len = path.getTotalLength();
      flow.pellets.forEach((p) => {
        if (p.eid === e.id) p.len = len;
      });
      const pill = flow.pills[e.id] || labels?.querySelector(`[data-edge="${CSS.escape(e.id)}"]`);
      if (!pill) return;
      const sibs = C.outs[e.from] || [];
      const idx = Math.max(0, sibs.indexOf(e));
      const mid = labelPoint(path, a, b, idx);
      pill.style.left = mid.x + 'px';
      pill.style.top = mid.y + 'px';
    });
  }

  function tick(t) {
    const dt = Math.min(0.05, (t - lastTick) / 1000);
    lastTick = t;
    if (!flowPaused) {
      for (let i = 0; i < flow.pellets.length; i += 1) {
        const p = flow.pellets[i];
        if (!p.len) continue;
        p.d = (p.d + p.v * dt) % p.len;
        if (phase[p.eid]) phase[p.eid][p.idx] = p.d;
        const q = p.path.getPointAtLength(p.d);
        p.c.setAttribute('cx', q.x);
        p.c.setAttribute('cy', q.y);
      }
    }
    requestAnimationFrame(tick);
  }

  function nodeStats(n, C) {
    if (isAnnotation(n)) return { rows: [], chip: '' };
    const inflow = C.inflow[n.id] || 0;
    const kept = C.kept[n.id] || 0;
    const out = (C.outs[n.id] || []).reduce((s, e) => s + e._amt, 0);
    const rows = [];
    if (n.kind === 'revenu') {
      rows.push(['Par mois', euro(n.amount), n.amount > 0 ? 'is-gain' : '']);
      rows.push(['Sort', euro(out)]);
    } else if (n.kind === 'depense') {
      (n.items || []).forEach((item) => {
        if (!String(item.title || '').trim() && !item.amount) return;
        rows.push([String(item.title || '').trim() || 'Poste', euro(item.amount), 'is-line']);
      });
      const monthlyLoss = (Number(n.amount) || 0) > 0;
      rows.push(['Par mois', euro(n.amount), monthlyLoss ? 'is-loss' : '']);
      if (Math.abs(inflow - (Number(n.amount) || 0)) > 0.5) {
        rows.push(['Reçoit', euro(inflow), !monthlyLoss && inflow > 0 ? 'is-loss' : '']);
      }
      const span = currentMonth();
      rows.push([span <= 0 ? 'Aujourd’hui' : 'Sur ' + span + ' mois', euro(inflow * span), '', 'span']);
    } else if (n.kind === 'livret') {
      rows.push(['Reçoit', euro(inflow)]);
    } else {
      rows.push(['Reçoit', euro(inflow), inflow > 0 ? 'is-gain' : '']);
    }
    if (n.kind === 'compte') rows.push(['Reste dessus', euro(kept)]);
    if (n.kind === 'livret') {
      const month = currentMonth();
      const bal = livretAt(C, n.id, month);
      const start = Number(n.start) || 0;
      rows.push(['Déjà dessus', euro(start), start > 0 ? 'is-gain' : '']);
      rows.push(['Taux', (n.rate || 0).toString().replace('.', ',') + ' %']);
      rows.push([playLabel(month), euro(bal), bal > 0 ? 'is-gain' : '', 'proj']);
      if ((n.rate || 0) > 0) {
        rows.push(['Intérêts', euro(livretInterestAt(C, n.id, month)), 'is-proj', 'interest']);
      }
    }
    let chip = '';
    if (n.kind === 'repartiteur') {
      chip = kept > 0.5 ? { text: euro(kept) + ' non ventilés', bad: true } : { text: 'tout est ventilé', bad: false };
    }
    if (n.kind === 'livret' && C.full[n.id] !== null && C.full[n.id] !== undefined) {
      const fullAt = C.full[n.id];
      const month = currentMonth();
      if (fullAt === 0) chip = { text: 'déjà plein', bad: false, full: true };
      else if (month >= fullAt) chip = { text: 'plein depuis le mois ' + fullAt, bad: false, full: true };
      else chip = { text: 'plein dans ' + (fullAt - month) + ' mois', bad: false, full: false };
    }
    if (C.over[n.id]) chip = { text: 'sorties > entrées', bad: true };
    if (needsAmount(n) && !(chip && chip.bad)) {
      chip = {
        text: n.kind === 'depense' ? 'postes à saisir' : 'montant à saisir',
        pending: true,
      };
    }
    return { rows, chip };
  }

  function renderCanvas() {
    const C = lastCompute || compute();
    lastCompute = C;
    layer.querySelectorAll('.node').forEach((el) => el.remove());
    svg.innerHTML = '';
    if (labels) labels.innerHTML = '';
    flow.paths = {};
    flow.pills = {};
    flow.pellets = [];

    const ordered = [
      ...state.nodes.filter((n) => n.kind === 'groupe'),
      ...state.nodes.filter((n) => n.kind !== 'groupe'),
    ];
    ordered.forEach((n) => {
      const meta = KINDS[n.kind];
      const el = document.createElement('div');
      el.dataset.node = n.id;
      el.style.left = n.x + 'px';
      el.style.top = n.y + 'px';
      const selected = state.selected === n.id ? ' is-selected' : '';
      const kill = readonly ? '' : `<button type="button" class="node-kill" data-del="${escapeAttr(n.id)}" title="Supprimer">×</button>`;
      if (!readonly) el.setAttribute('data-drag', n.id);

      if (n.kind === 'groupe') {
        const tint = tintOf(n);
        el.className = 'node is-group' + selected;
        el.style.width = (n.w || 560) + 'px';
        el.style.height = (n.h || 340) + 'px';
        el.style.background = tint.fill;
        el.style.borderColor = tint.stroke;
        el.innerHTML = `
          <div class="group-label" style="color:${tint.ink};border-color:${tint.stroke}">
            <span class="group-title">${escapeHtml(n.title)}</span>
            ${kill}
          </div>
          ${n.note ? `<div class="group-note">${escapeHtml(n.note)}</div>` : ''}
          ${readonly ? '' : `<div class="group-resize" data-resize="${escapeAttr(n.id)}" title="Redimensionner"></div>`}
        `;
      } else if (n.kind === 'note') {
        const tint = tintOf(n);
        el.className = 'node is-note' + selected;
        el.style.width = (n.w || 200) + 'px';
        el.style.background = tint.fill;
        el.style.borderColor = tint.stroke;
        el.innerHTML = `
          <div class="node-inner">
            <div class="node-head">
              <span class="node-kind" style="color:${tint.ink}">Note</span>
              ${kill}
            </div>
            <div class="node-title">${escapeHtml(n.title)}</div>
            ${n.note ? `<div class="node-body"><div class="node-note is-open">${escapeHtml(n.note)}</div></div>` : ''}
          </div>
        `;
      } else {
        const stats = nodeStats(n, C);
        const color = accentColorOf(n, C);
        const full = isLivretFullNow(n, C);
        const pending = needsAmount(n);
        el.className = 'node' + selected + (full ? ' is-full' : '') + (pending ? ' is-pending' : '');
        el.innerHTML = `
          <div class="node-inner">
            <div class="node-bar" style="background:${color}"></div>
            <div class="node-head">
              <span class="node-kind" style="color:${color}">${meta.label}</span>
              ${kill}
            </div>
            <div class="node-title">${escapeHtml(n.title)}</div>
            <div class="node-body">
              ${statRowsHtml(stats.rows, 'node-stat')}
              ${stats.chip ? `<div class="node-chip${stats.chip.bad ? ' is-bad' : ''}${stats.chip.full ? ' is-full' : ''}${stats.chip.pending ? ' is-pending' : ''}">${escapeHtml(stats.chip.text)}</div>` : ''}
              ${n.note ? `<div class="node-note">${escapeHtml(n.note)}</div>` : ''}
            </div>
          </div>
          ${readonly ? '' : (meta.hasIn ? `<div class="port port-in" data-port-in="${escapeAttr(n.id)}" style="border:2px solid ${color}" title="Entrée"></div>` : '')}
          ${readonly ? '' : (meta.hasOut ? `<div class="port port-out" data-port-out="${escapeAttr(n.id)}" style="background:${color};border:2px solid #fff;box-shadow:0 0 0 1px ${color}" title="Sortie"></div>` : '')}
        `;
      }
      layer.appendChild(el);
      n._w = n.kind === 'groupe' ? (n.w || el.offsetWidth) : el.offsetWidth;
      n._h = n.kind === 'groupe' ? (n.h || el.offsetHeight) : el.offsetHeight;
    });

    if (state.connectFrom) {
      const sel = state.connectSide === 'in'
        ? `[data-port-in="${state.connectFrom}"]`
        : `[data-port-out="${state.connectFrom}"]`;
      const armed = layer.querySelector(sel);
      if (armed) armed.classList.add('is-armed');
    }

    state.edges.forEach((e) => {
      const a = C.byId[e.from];
      const b = C.byId[e.to];
      if (!a || !b) return;
      const p1 = portPoint(a, 'out');
      const p2 = portPoint(b, 'in');
      const color = accentColorOf(a, C);
      const hot = e._amt > 0.5;
      const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.setAttribute('d', curve(p1, p2));
      path.setAttribute('class', 'builder-wire' + (hot ? ' is-hot' : ''));
      path.setAttribute('stroke', hot ? color : 'oklch(0.82 0.02 255)');
      path.setAttribute('stroke-width', hot ? '2.5' : '1.5');
      svg.appendChild(path);
      flow.paths[e.id] = path;

      if (labels) {
        const sibs = C.outs[e.from] || [];
        const idx = Math.max(0, sibs.indexOf(e));
        const mid = labelPoint(path, a, b, idx);
        const pill = document.createElement('button');
        pill.type = 'button';
        const tag = e.mode === 'pct' ? e.value + ' %' : (e.mode === 'fixe' ? 'fixe' : 'reste');
        pill.className = 'edge-pill' + (hot ? '' : ' is-zero') + (state.openEdge === e.id ? ' is-open' : '');
        if (readonly) pill.disabled = true;
        pill.dataset.edge = e.id;
        pill.style.left = mid.x + 'px';
        pill.style.top = mid.y + 'px';
        pill.innerHTML = `${euro(e._amt)}<i>${tag}</i>`;
        labels.appendChild(pill);
        flow.pills[e.id] = pill;
      }

      spawnPellets(e, path, color);
    });
    applyTourFocus();
  }

  function renderSide() {
    const C = lastCompute || compute();
    lastCompute = C;
    const set = (sel, val) => { const el = root.querySelector(sel); if (el) el.textContent = val; };
    set('[data-stat="in"]', euro(C.inn));
    set('[data-stat="out"]', euro(C.out));
    set('[data-stat="saved"]', euro(C.saved));
    set('[data-stat="unassigned"]', euro(C.leftover));
    set('[data-stat="proj"]', euro(playTotal(C)));
    set('[data-horizon-label]', playLabel(currentMonth()));
    const hint = root.querySelector('[data-stat="proj-hint"]');
    if (hint) {
      hint.textContent = C.sat.length
        ? 'Saturés d’ici là : ' + C.sat.map((s) => s.name + ' (' + (s.m === 0 ? 'déjà' : s.m + ' m') + ')').join(', ') + '.'
        : (C.proj > 0 ? 'Aucun livret n’atteint son plafond sur la période.' : '');
    }
    const warns = [];
    if (C.cycle) warns.push('Le circuit contient une boucle. Retirez un lien pour la casser.');
    state.nodes.forEach((n) => {
      if (isAnnotation(n)) return;
      if (C.over[n.id]) warns.push('« ' + n.title + ' » distribue plus qu’il ne reçoit.');
      if (n.kind === 'repartiteur' && (C.kept[n.id] || 0) > 0.5) warns.push('« ' + n.title + ' » garde ' + euro(C.kept[n.id]) + ' sans destination.');
    });
    const box = root.querySelector('[data-warns]');
    if (box) box.innerHTML = warns.slice(0, 4).map((t) => '<div class="builder-warn">' + escapeHtml(t) + '</div>').join('');
    const empty = root.querySelector('[data-empty]');
    if (empty) empty.hidden = state.nodes.length > 0;
    if (reportOpen()) fillReport();
  }

  function renderProps() {
    const n = nodeById(state.selected);
    if (!n) {
      if (propsEmpty) propsEmpty.hidden = false;
      if (propsForm) { propsForm.hidden = true; propsForm.innerHTML = ''; }
      root.classList.remove('is-props-open');
      return;
    }
    const C = lastCompute || compute();
    lastCompute = C;
    const meta = KINDS[n.kind];
    if (isAnnotation(n)) {
      const tint = tintOf(n);
      if (propsEmpty) propsEmpty.hidden = true;
      if (!propsForm) return;
      propsForm.hidden = false;
      propsForm.innerHTML = `
        <div class="prop-block">
          <div class="prop-head">
            <span class="dot" style="background:${tint.stroke}"></span>
            <div>
              <div class="eyebrow">${meta.label}</div>
              <div class="prop-kind">${escapeHtml(n.title)}</div>
            </div>
          </div>
          <div class="prop-field">
            <span>${n.kind === 'groupe' ? 'Titre du groupe' : 'Titre'}</span>
            <div class="prop-name-row">
              <input data-prop="title" value="${escapeAttr(n.title)}">
              ${tintControl(n, 'fill')}
            </div>
          </div>
          <label class="prop-field">
            <span>${n.kind === 'note' ? 'Texte' : 'Commentaire'}</span>
            <textarea data-prop="note" rows="4" placeholder="${n.kind === 'note' ? 'Rappel, hypothèse, détail…' : 'Optionnel'}">${escapeHtml(n.note)}</textarea>
          </label>
          ${n.kind === 'groupe' ? '<p class="builder-hint">Glissez le cadre pour emmener les blocs qu’il contient. Redimensionnez par le coin bas-droit.</p>' : '<p class="builder-hint">La note n’entre pas dans les calculs.</p>'}
          <button type="button" class="btn btn-ghost" data-del-selected>Supprimer ${n.kind === 'groupe' ? 'le groupe' : 'la note'}</button>
        </div>
      `;
      return;
    }
    const stats = nodeStats(n, C);
    const outs = (C.outs[n.id] || []);
    if (propsEmpty) propsEmpty.hidden = true;
    if (!propsForm) return;
    propsForm.hidden = false;
    propsForm.innerHTML = `
      <div class="prop-block">
        <div class="prop-head">
          <span class="dot" style="background:${colorOf(n)}"></span>
          <div>
            <div class="eyebrow">${meta.label}</div>
            <div class="prop-kind">${escapeHtml(n.title)}</div>
          </div>
        </div>
        <div class="prop-field">
          <span>Nom du bloc</span>
          <div class="prop-name-row">
            <input data-prop="title" value="${escapeAttr(n.title)}">
            ${tintControl(n, 'block')}
          </div>
        </div>
        ${n.kind === 'revenu' ? `
          <label class="prop-field">
            <span>Montant par mois</span>
            <input data-prop="amount" type="number" min="0" step="1" value="${n.amount}">
          </label>` : ''}
        ${n.kind === 'depense' ? `
          <div data-items-block>
            <div class="prop-items-head">
              <div class="eyebrow">Postes du mois</div>
              <button type="button" class="btn btn-ghost" data-item-add style="min-height:0;padding:4px 8px;font-size:12px;">Ajouter</button>
            </div>
            <div class="prop-items">
              ${(n.items || []).length ? (n.items || []).map((item) => `
                <div class="prop-item" data-item-edit="${escapeAttr(item.id)}">
                  <input data-item-title="${escapeAttr(item.id)}" type="text" placeholder="Essence, loyer, EDF…" value="${escapeAttr(item.title)}" aria-label="Titre du poste">
                  <input data-item-amount="${escapeAttr(item.id)}" type="number" min="0" step="1" value="${item.amount}" aria-label="Montant du poste">
                  <button type="button" class="btn btn-ghost" data-item-del="${escapeAttr(item.id)}" style="min-height:0;padding:4px 8px;font-size:12px;" title="Retirer ce poste" aria-label="Retirer ce poste">×</button>
                </div>`).join('') : '<p class="builder-hint">Ajoutez loyer, EDF, essence… Le total alimente le bloc.</p>'}
            </div>
            ${(n.items || []).length ? `<div class="prop-items-total" data-items-total>${euro(n.amount)} / mois</div>` : ''}
          </div>` : ''}
        ${n.kind === 'livret' ? `
          <label class="prop-field">
            <span>Type de livret</span>
            <select data-prop="preset">
              <option value="livret-a"${n.preset === 'livret-a' ? ' selected' : ''}>Livret A — 1,70 % · 22 950 €</option>
              <option value="ldds"${n.preset === 'ldds' ? ' selected' : ''}>LDDS — 1,70 % · 12 000 €</option>
              <option value="lep"${n.preset === 'lep' ? ' selected' : ''}>LEP — 2,50 % · 10 000 €</option>
              <option value="jeune"${n.preset === 'jeune' ? ' selected' : ''}>Livret Jeune — 1,70 % · 1 600 €</option>
              <option value="custom"${!n.preset || n.preset === 'custom' ? ' selected' : ''}>Personnalisé</option>
            </select>
          </label>
          <label class="prop-field">
            <span>Déjà dessus</span>
            <input data-prop="start" type="number" min="0" step="1" value="${n.start}">
          </label>
          <div class="prop-grid">
            <label class="prop-field">
              <span>Taux (%)</span>
              <input data-prop="rate" type="number" min="0" step="0.01" value="${n.rate}">
            </label>
            <label class="prop-field">
              <span>Plafond</span>
              <input data-prop="cap" type="number" min="0" step="1" value="${n.cap}">
            </label>
          </div>` : ''}
        <div>
          <div class="eyebrow" style="margin-bottom:4px;">Lecture</div>
          <div class="prop-stats">
            ${statRowsHtml(lectureRows(n, C), 'prop-stat')}
          </div>
        </div>
        ${livretInterestHtml(n, C)}
        <label class="prop-field">
          <span>Commentaire</span>
          <textarea data-prop="note" rows="${n.kind === 'depense' ? 2 : 4}" placeholder="Précisions, hypothèse, rappel…">${escapeHtml(n.note)}</textarea>
        </label>
        ${n.kind === 'depense' ? `
          <div>
            <div class="eyebrow" style="margin-bottom:8px;">Liens entrants</div>
            <div class="prop-links">
              ${state.edges.filter((e) => e.to === n.id).length ? state.edges.filter((e) => e.to === n.id).map((e) => {
                const src = C.byId[e.from];
                return `<div class="prop-link" data-edge-edit="${e.id}">
                  <div class="prop-link-top">
                    <span>← ${escapeHtml(src ? src.title : e.from)}</span>
                    <button type="button" class="btn btn-ghost" data-edge-del="${e.id}" style="min-height:0;padding:4px 8px;font-size:12px;">Retirer</button>
                  </div>
                  <div class="prop-link-row">
                    <select data-edge-mode="${e.id}">
                      <option value="reste"${e.mode === 'reste' ? ' selected' : ''}>Le reste</option>
                      <option value="pct"${e.mode === 'pct' ? ' selected' : ''}>Pourcentage</option>
                      <option value="fixe"${e.mode === 'fixe' ? ' selected' : ''}>Montant fixe</option>
                    </select>
                    ${e.mode === 'reste' ? '' : `<input data-edge-value="${e.id}" type="number" min="0" step="1" value="${e.value}">`}
                  </div>
                  <div class="prop-link-amt">${euro(e._amt)} / mois</div>
                </div>`;
              }).join('') : '<p class="builder-hint">Aucun lien. Reliez un compte ou un revenu vers ce bloc, puis saisissez le montant.</p>'}
            </div>
          </div>` : ''}
        ${meta.hasOut ? `
          <div>
            <div class="eyebrow" style="margin-bottom:8px;">Liens sortants</div>
            <div class="prop-links">
              ${outs.length ? outs.map((e) => {
                const dest = C.byId[e.to];
                return `<div class="prop-link" data-edge-edit="${e.id}">
                  <div class="prop-link-top">
                    <span>→ ${escapeHtml(dest ? dest.title : e.to)}</span>
                    <button type="button" class="btn btn-ghost" data-edge-del="${e.id}" style="min-height:0;padding:4px 8px;font-size:12px;">Retirer</button>
                  </div>
                  <div class="prop-link-row">
                    <select data-edge-mode="${e.id}">
                      <option value="reste"${e.mode === 'reste' ? ' selected' : ''}>Le reste</option>
                      <option value="pct"${e.mode === 'pct' ? ' selected' : ''}>Pourcentage</option>
                      <option value="fixe"${e.mode === 'fixe' ? ' selected' : ''}>Montant fixe</option>
                    </select>
                    ${e.mode === 'reste' ? '' : `<input data-edge-value="${e.id}" type="number" min="0" step="${e.mode === 'pct' ? '1' : '1'}" value="${e.value}">`}
                  </div>
                  <div class="prop-link-amt">${euro(e._amt)} / mois</div>
                </div>`;
              }).join('') : '<p class="builder-hint">Aucun lien. Restez cliqué sur un point, puis glissez jusqu’au point opposé d’un autre bloc.</p>'}
            </div>
          </div>` : ''}
        <button type="button" class="btn btn-ghost" data-del-selected>Supprimer le bloc</button>
      </div>
    `;
  }

  function render(opts = {}) {
    lastCompute = compute();
    syncPlayBound();
    renderCanvas();
    renderSide();
    drawTimeChart();
    syncTimePlay();
    applyTransform();
    syncPayload();
    if (opts.props !== false) renderProps();
  }

  function circuitSnap() {
    return JSON.stringify({
      name: (nameInput?.value || '').trim(),
      horizon: state.horizon,
      nodes: state.nodes.map(({ _w, _h, ...n }) => n),
      edges: state.edges.map(({ _amt, ...e }) => e),
    });
  }

  function isDirty() {
    return savedSnap !== null && circuitSnap() !== savedSnap;
  }

  function syncSaveButton() {
    if (!saveBtn) return;
    const dirty = isDirty();
    saveBtn.disabled = !dirty;
    saveBtn.textContent = dirty ? 'Enregistrer' : 'Enregistré';
    saveBtn.classList.toggle('btn-orange', dirty);
    saveBtn.classList.toggle('is-saved', !dirty);
    saveBtn.setAttribute('aria-disabled', dirty ? 'false' : 'true');
  }

  function markSaved() {
    savedSnap = circuitSnap();
    syncSaveButton();
  }

  function horizonDisplay() {
    return horizonUnit === 'ans' ? Math.max(1, Math.round(state.horizon / 12)) : state.horizon;
  }

  function syncHorizonInput() {
    if (!horizonInput) return;
    if (horizonUnit === 'ans') {
      horizonInput.min = '1';
      horizonInput.max = '30';
    } else {
      horizonInput.min = '1';
      horizonInput.max = '360';
    }
    horizonInput.value = String(horizonDisplay());
  }

  function applyHorizonFromInput() {
    const raw = parseInt(horizonInput?.value, 10);
    if (horizonUnit === 'ans') {
      const years = Number.isNaN(raw) ? 5 : Math.min(30, Math.max(1, raw));
      state.horizon = Math.min(360, Math.max(12, years * 12));
    } else {
      state.horizon = Number.isNaN(raw) ? 60 : Math.min(360, Math.max(1, raw));
    }
  }

  function horizonUnitOpen() {
    return Boolean(horizonUnitWrap?.classList.contains('is-open'));
  }

  function closeHorizonUnitMenu() {
    if (!horizonUnitWrap || !horizonUnitMenu || !horizonUnitToggle) return;
    horizonUnitWrap.classList.remove('is-open');
    horizonUnitMenu.hidden = true;
    horizonUnitToggle.setAttribute('aria-expanded', 'false');
  }

  function openHorizonUnitMenu() {
    if (!horizonUnitWrap || !horizonUnitMenu || !horizonUnitToggle) return;
    horizonUnitWrap.classList.add('is-open');
    horizonUnitMenu.hidden = false;
    horizonUnitToggle.setAttribute('aria-expanded', 'true');
  }

  function setHorizonUnit(unit) {
    horizonUnit = unit === 'ans' ? 'ans' : 'mois';
    if (horizonUnitLabel) horizonUnitLabel.textContent = horizonUnit;
    horizonUnitWrap?.querySelectorAll('[data-horizon-unit-opt]').forEach((btn) => {
      btn.setAttribute('aria-selected', btn.getAttribute('data-horizon-unit-opt') === horizonUnit ? 'true' : 'false');
    });
    syncHorizonInput();
    closeHorizonUnitMenu();
  }

  function syncPayload() {
    if (payloadInput) {
      payloadInput.value = JSON.stringify({
        horizon: state.horizon,
        nodes: state.nodes.map(({ _w, _h, ...n }) => n),
        edges: state.edges.map(({ _amt, ...e }) => e),
      });
    }
    syncSaveButton();
  }

  function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  }
  function escapeAttr(s) {
    return escapeHtml(s);
  }
  function clampHorizon(value) {
    const n = Number(value);
    if (!Number.isFinite(n) || n < 1) return 60;
    return Math.min(360, Math.round(n));
  }
  function statRowsHtml(rows, clsPrefix) {
    const plain = clsPrefix === 'prop-stat';
    return (rows || []).map(([k, v, cls, key]) => {
      const classes = String(cls || '').trim().split(/\s+/).filter(Boolean);
      const rowExtra = classes.filter((c) => c === 'is-line').join(' ');
      const valExtra = classes.filter((c) => {
        if (c === 'is-line') return false;
        if (plain && (c === 'is-gain' || c === 'is-loss')) return false;
        return true;
      }).join(' ');
      return `<div class="${clsPrefix}${rowExtra ? ' ' + rowExtra : ''}"${key ? ` data-play="${escapeAttr(key)}"` : ''}><span>${escapeHtml(k)}</span><b${valExtra ? ` class="${escapeAttr(valExtra)}"` : ''}>${escapeHtml(v)}</b></div>`;
    }).join('');
  }

  function markSelected(id) {
    state.selected = id;
    state.openEdge = null;
    if (id) root.classList.add('is-props-open');
    else root.classList.remove('is-props-open');
    const toggle = root.querySelector('[data-props-toggle]');
    if (toggle) toggle.setAttribute('aria-expanded', id ? 'true' : 'false');
    if (id && state.edges.some((e) => e.from === id)) dismissSplitCoach();
  }

  function paintSelection() {
    layer?.querySelectorAll('.node').forEach((el) => {
      el.classList.toggle('is-selected', el.dataset.node === state.selected);
    });
  }

  function selectNode(id) {
    markSelected(id);
    render();
  }

  function selectNodeLive(id) {
    if (state.selected === id) {
      paintSelection();
      return;
    }
    markSelected(id);
    paintSelection();
    renderProps();
  }

  function addNode(kind, x, y, extra = {}) {
    const n = normalizeNode({
      id: uid('n'),
      kind,
      title: extra.title || KINDS[kind].label,
      x, y,
      ...extra,
    });
    if (n.kind === 'depense' && !(n.items && n.items.length)) {
      addDepenseItem(n);
    }
    state.nodes.push(n);
    if (n.kind === 'depense') offerItemsCoach();
    else offerLinkCoach();
    return n;
  }

  function revealDepenseItem(itemId) {
    requestAnimationFrame(() => {
      const sel = itemId
        ? `[data-item-title="${CSS.escape(itemId)}"]`
        : '[data-item-title]';
      const field = propsForm?.querySelector(sel);
      if (!field) return;
      const panel = root.querySelector('[data-props]');
      const box = field.closest('.prop-item') || field;
      if (panel) {
        const panelRect = panel.getBoundingClientRect();
        const boxRect = box.getBoundingClientRect();
        if (boxRect.top < panelRect.top || boxRect.bottom > panelRect.bottom) {
          box.scrollIntoView({ block: 'nearest', inline: 'nearest' });
        }
      }
      field.focus();
    });
  }

  function removeNode(id) {
    state.nodes = state.nodes.filter((n) => n.id !== id);
    state.edges = state.edges.filter((e) => e.from !== id && e.to !== id);
    if (state.selected === id) state.selected = null;
    if (state.connectFrom === id) cancelLink();
  }

  function syncDepenseAmount(n) {
    const ins = state.edges.filter((e) => e.to === n.id);
    if (!ins.length) return;
    const driven = Array.isArray(n.items) && n.items.length > 0;
    if (!driven && n.amount <= 0) {
      ins.forEach((edge) => {
        if (edge.mode === 'fixe') {
          edge.mode = 'reste';
          edge.value = 0;
        }
      });
      return;
    }
    if (n.amount <= 0) return;
    const edge = ins.length === 1 ? ins[0] : (ins.find((e) => e.mode === 'fixe') || null);
    if (!edge) return;
    edge.mode = 'fixe';
    edge.value = n.amount;
  }

  function addEdge(from, to) {
    if (from === to) return;
    const src = nodeById(from);
    const destNode = nodeById(to);
    if (!src || !destNode || isAnnotation(src) || isAnnotation(destNode)) return;
    if (state.edges.some((e) => e.from === from && e.to === to)) return;
    const dest = destNode;
    const already = state.edges.some((e) => e.from === from);
    const useFixe = dest && dest.kind === 'depense' && dest.amount > 0;
    state.edges.push({
      id: uid('e'),
      from,
      to,
      mode: useFixe ? 'fixe' : (already ? 'pct' : 'reste'),
      value: useFixe ? dest.amount : (already ? 25 : 0),
    });
    dismissLinkCoach();
    offerSplitCoach();
  }

  function kindSize(kind) {
    if (kind === 'groupe') return { w: 560, h: 340 };
    if (kind === 'note') return { w: 200, h: 90 };
    return { w: 244, h: 118 };
  }

  function boxesOverlap(a, b, pad = 20) {
    return a.x < b.x + b.w + pad
      && a.x + a.w + pad > b.x
      && a.y < b.y + b.h + pad
      && a.y + a.h + pad > b.y;
  }

  function contentBounds() {
    if (!state.nodes.length) return null;
    let x1 = Infinity;
    let y1 = Infinity;
    let x2 = -Infinity;
    let y2 = -Infinity;
    state.nodes.forEach((n) => {
      const b = nodeBox(n);
      x1 = Math.min(x1, b.x);
      y1 = Math.min(y1, b.y);
      x2 = Math.max(x2, b.x + b.w);
      y2 = Math.max(y2, b.y + b.h);
    });
    return { x: x1, y: y1, w: x2 - x1, h: y2 - y1 };
  }

  function isSpotFree(box) {
    return !state.nodes.some((n) => boxesOverlap(box, nodeBox(n)));
  }

  function viewportBox() {
    const r = canvas.getBoundingClientRect();
    const tl = screenToWorld(r.left, r.top);
    const br = screenToWorld(r.right, r.bottom);
    return { x: tl.x, y: tl.y, w: br.x - tl.x, h: br.y - tl.y };
  }

  function visibleArea(box, view) {
    const x1 = Math.max(box.x, view.x);
    const y1 = Math.max(box.y, view.y);
    const x2 = Math.min(box.x + box.w, view.x + view.w);
    const y2 = Math.min(box.y + box.h, view.y + view.h);
    return Math.max(0, x2 - x1) * Math.max(0, y2 - y1);
  }

  function findClearSpot(kind, preferredX, preferredY) {
    const size = kindSize(kind);
    const preferred = { x: preferredX, y: preferredY, w: size.w, h: size.h };
    const bounds = contentBounds();
    if (!bounds) return { x: preferredX, y: preferredY };
    const insideCluster = boxesOverlap(preferred, bounds, 0);
    if (!insideCluster && isSpotFree(preferred)) return { x: preferredX, y: preferredY };

    const gap = 32;
    const alignY = Math.max(bounds.y, Math.min(preferredY, bounds.y + Math.max(0, bounds.h - size.h)));
    const alignX = Math.max(bounds.x, Math.min(preferredX, bounds.x + Math.max(0, bounds.w - size.w)));
    const slots = [
      { x: bounds.x + bounds.w + gap, y: alignY },
      { x: alignX, y: bounds.y + bounds.h + gap },
      { x: bounds.x - size.w - gap, y: alignY },
      { x: alignX, y: bounds.y - size.h - gap },
    ];
    const view = viewportBox();
    slots.sort((a, b) => {
      const va = visibleArea({ x: a.x, y: a.y, w: size.w, h: size.h }, view);
      const vb = visibleArea({ x: b.x, y: b.y, w: size.w, h: size.h }, view);
      if (vb !== va) return vb - va;
      return Math.hypot(a.x - preferredX, a.y - preferredY) - Math.hypot(b.x - preferredX, b.y - preferredY);
    });
    for (let i = 0; i < slots.length; i += 1) {
      const s = slots[i];
      if (isSpotFree({ x: s.x, y: s.y, w: size.w, h: size.h })) {
        return { x: Math.round(s.x), y: Math.round(s.y) };
      }
    }

    const step = 48;
    for (let ring = 1; ring <= 16; ring += 1) {
      for (let i = -ring; i <= ring; i += 1) {
        const pts = [
          { x: preferredX + i * step, y: preferredY - ring * step },
          { x: preferredX + i * step, y: preferredY + ring * step },
          { x: preferredX - ring * step, y: preferredY + i * step },
          { x: preferredX + ring * step, y: preferredY + i * step },
        ];
        for (let j = 0; j < pts.length; j += 1) {
          const p = pts[j];
          if (isSpotFree({ x: p.x, y: p.y, w: size.w, h: size.h })) {
            return { x: Math.round(p.x), y: Math.round(p.y) };
          }
        }
      }
    }
    return { x: Math.round(bounds.x + bounds.w + gap), y: Math.round(bounds.y) };
  }

  function dropPosition(clientX, clientY, kind) {
    const size = kindSize(kind);
    const ox = size.w / 2;
    const oy = 40;
    let x;
    let y;
    if (clientX != null && clientY != null) {
      const w = screenToWorld(clientX, clientY);
      x = Math.round(w.x - ox);
      y = Math.round(w.y - oy);
    } else {
      const r = canvas.getBoundingClientRect();
      const w = screenToWorld(r.left + r.width / 2, r.top + r.height / 2);
      x = Math.round(w.x - ox + (Math.random() * 40 - 20));
      y = Math.round(w.y - oy + (Math.random() * 40 - 20));
    }
    if (isAnnotation(kind)) return findClearSpot(kind, x, y);
    return { x, y };
  }

  function openPresetModal(kind, x, y) {
    pendingDrop = { kind, x, y };
    if (!modal || !presetList) {
      const n = addNode(kind, x, y);
      selectNode(n.id);
      if (n.kind === 'depense') revealDepenseItem();
      return;
    }
    const meta = KINDS[kind];
    modal.querySelector('[data-preset-kind]').textContent = meta.label;
    modal.querySelector('#preset-title').textContent = kind === 'groupe'
      ? 'Quel regroupement ?'
      : (kind === 'note' ? 'Quelle note ?' : 'Préconfigurer ce ' + meta.label.toLowerCase());
    modal.querySelector('[data-preset-intro]').textContent = kind === 'compte' || kind === 'livret'
      ? 'Choisissez un produit : le taux et le plafond seront remplis automatiquement. Vous pourrez tout ajuster ensuite.'
      : (kind === 'groupe'
        ? 'Le cadre se place derrière les blocs. Glissez-le pour les emmener, redimensionnez par le coin.'
        : (kind === 'note'
          ? 'La note n’entre pas dans les calculs : elle sert de rappel sur le plan.'
          : 'Choisissez un modèle, ou partez vierge.'));
    presetList.innerHTML = PRESETS[kind].map((group) => `
      <div>
        <div class="preset-group-label">${group.group}</div>
        <div class="preset-grid">
          ${group.items.map((item) => `
            <button type="button" class="preset-card${item.blank ? ' is-blank' : ''}" data-preset-pick="${item.id}">
              <strong>${escapeHtml(item.title)}</strong>
              <span>${escapeHtml(item.hint)}</span>
            </button>
          `).join('')}
        </div>
      </div>
    `).join('');
    modal.hidden = false;
    document.body.classList.add('is-locked');
  }

  function closePresetModal() {
    if (modal) modal.hidden = true;
    document.body.classList.remove('is-locked');
    pendingDrop = null;
    flushCoaches();
  }

  const LINK_COACH_KEY = 'repartio.linkCoachSeen';
  const SPLIT_COACH_KEY = 'repartio.splitCoachSeen';
  const ITEMS_COACH_KEY = 'repartio.itemsCoachSeen';
  const linkCoach = document.querySelector('[data-link-coach]');
  const splitCoach = document.querySelector('[data-split-coach]');
  const itemsCoach = document.querySelector('[data-items-coach]');
  let linkCoachPending = false;
  let linkCoachTimer = 0;
  let splitCoachPending = false;
  let splitCoachTimer = 0;
  let itemsCoachPending = false;
  let itemsCoachTimer = 0;

  function graphNodeCount() {
    return state.nodes.filter((n) => !isAnnotation(n)).length;
  }

  function fullscreenModalOpen() {
    return Array.from(document.querySelectorAll('.builder-modal')).some((el) => !el.hidden);
  }

  function overlayBlockingCoach() {
    const drawer = root.classList.contains('is-props-open') && window.matchMedia('(max-width: 980px)').matches;
    return fullscreenModalOpen() || drawer;
  }

  function linkCoachSeen() {
    try {
      return window.localStorage.getItem(LINK_COACH_KEY) === '1';
    } catch (e) {
      return false;
    }
  }

  function markLinkCoachSeen() {
    try {
      window.localStorage.setItem(LINK_COACH_KEY, '1');
    } catch (e) {}
  }

  function offerLinkCoach() {
    if (readonly || !linkCoach || linkCoachSeen()) return;
    if (graphNodeCount() !== 2) return;
    if (state.edges.length) return;
    if (overlayBlockingCoach() || (itemsCoach && !itemsCoach.hidden)) {
      linkCoachPending = true;
      return;
    }
    scheduleLinkCoach();
  }

  function scheduleLinkCoach() {
    linkCoachPending = false;
    window.clearTimeout(linkCoachTimer);
    linkCoachTimer = window.setTimeout(() => {
      if (overlayBlockingCoach() || (itemsCoach && !itemsCoach.hidden)) {
        linkCoachPending = true;
        return;
      }
      if (readonly || linkCoachSeen()) return;
      if (graphNodeCount() !== 2 || state.edges.length) return;
      showLinkCoach();
    }, 360);
  }

  function flushLinkCoach() {
    if (linkCoachPending) offerLinkCoach();
  }

  function showLinkCoach() {
    if (!linkCoach || !linkCoach.hidden) return;
    linkCoach.hidden = false;
    markLinkCoachSeen();
  }

  function dismissLinkCoach() {
    window.clearTimeout(linkCoachTimer);
    linkCoachPending = false;
    if (!linkCoach || linkCoach.hidden) return false;
    linkCoach.hidden = true;
    markLinkCoachSeen();
    return true;
  }

  function splitCoachSeen() {
    try {
      return window.localStorage.getItem(SPLIT_COACH_KEY) === '1';
    } catch (e) {
      return false;
    }
  }

  function markSplitCoachSeen() {
    try {
      window.localStorage.setItem(SPLIT_COACH_KEY, '1');
    } catch (e) {}
  }

  function sourceAlreadyOpen() {
    return !!(state.selected && state.edges.some((e) => e.from === state.selected));
  }

  function offerSplitCoach() {
    if (readonly || !splitCoach || splitCoachSeen()) return;
    if (!state.edges.length) return;
    if (sourceAlreadyOpen()) {
      markSplitCoachSeen();
      return;
    }
    if (overlayBlockingCoach()) {
      splitCoachPending = true;
      return;
    }
    scheduleSplitCoach();
  }

  function scheduleSplitCoach() {
    splitCoachPending = false;
    window.clearTimeout(splitCoachTimer);
    splitCoachTimer = window.setTimeout(() => {
      if (overlayBlockingCoach()) {
        splitCoachPending = true;
        return;
      }
      if (readonly || splitCoachSeen()) return;
      if (!state.edges.length) return;
      if (sourceAlreadyOpen()) {
        markSplitCoachSeen();
        return;
      }
      if (linkCoach && !linkCoach.hidden) return;
      showSplitCoach();
    }, 420);
  }

  function flushSplitCoach() {
    if (splitCoachPending) offerSplitCoach();
  }

  function itemsCoachSeen() {
    try {
      return window.localStorage.getItem(ITEMS_COACH_KEY) === '1';
    } catch (e) {
      return false;
    }
  }

  function markItemsCoachSeen() {
    try {
      window.localStorage.setItem(ITEMS_COACH_KEY, '1');
    } catch (e) {}
  }

  function otherCoachOpen() {
    return (linkCoach && !linkCoach.hidden) || (splitCoach && !splitCoach.hidden);
  }

  function offerItemsCoach() {
    if (readonly || !itemsCoach || itemsCoachSeen()) return;
    if (!state.nodes.some((n) => n.kind === 'depense')) return;
    if (overlayBlockingCoach() || otherCoachOpen()) {
      itemsCoachPending = true;
      return;
    }
    scheduleItemsCoach();
  }

  function scheduleItemsCoach() {
    itemsCoachPending = false;
    window.clearTimeout(itemsCoachTimer);
    itemsCoachTimer = window.setTimeout(() => {
      if (overlayBlockingCoach() || otherCoachOpen()) {
        itemsCoachPending = true;
        return;
      }
      if (readonly || itemsCoachSeen()) return;
      if (!state.nodes.some((n) => n.kind === 'depense')) return;
      showItemsCoach();
    }, 420);
  }

  function flushItemsCoach() {
    if (itemsCoachPending) offerItemsCoach();
  }

  function showItemsCoach() {
    if (!itemsCoach || !itemsCoach.hidden) return;
    itemsCoach.hidden = false;
    markItemsCoachSeen();
  }

  function dismissItemsCoach() {
    window.clearTimeout(itemsCoachTimer);
    itemsCoachPending = false;
    markItemsCoachSeen();
    const wasOpen = !!(itemsCoach && !itemsCoach.hidden);
    if (itemsCoach && !itemsCoach.hidden) itemsCoach.hidden = true;
    if (wasOpen) offerLinkCoach();
    return wasOpen;
  }

  function flushCoaches() {
    flushItemsCoach();
    if (!itemsCoachPending && (!itemsCoach || itemsCoach.hidden)) flushLinkCoach();
    flushSplitCoach();
  }

  function showSplitCoach() {
    if (!splitCoach || !splitCoach.hidden) return;
    dismissLinkCoach();
    splitCoach.hidden = false;
    markSplitCoachSeen();
  }

  function dismissSplitCoach() {
    window.clearTimeout(splitCoachTimer);
    splitCoachPending = false;
    markSplitCoachSeen();
    if (!splitCoach || splitCoach.hidden) return false;
    splitCoach.hidden = true;
    return true;
  }

  linkCoach?.addEventListener('click', (e) => {
    if (e.target.closest('[data-link-coach-dismiss]')) dismissLinkCoach();
    e.stopPropagation();
  });
  linkCoach?.addEventListener('mousedown', (e) => e.stopPropagation());
  linkCoach?.addEventListener('wheel', (e) => e.stopPropagation());
  splitCoach?.addEventListener('click', (e) => {
    if (e.target.closest('[data-split-coach-dismiss]')) dismissSplitCoach();
    e.stopPropagation();
  });
  splitCoach?.addEventListener('mousedown', (e) => e.stopPropagation());
  splitCoach?.addEventListener('wheel', (e) => e.stopPropagation());
  itemsCoach?.addEventListener('click', (e) => {
    if (e.target.closest('[data-items-coach-dismiss]')) dismissItemsCoach();
    e.stopPropagation();
  });
  itemsCoach?.addEventListener('mousedown', (e) => e.stopPropagation());
  itemsCoach?.addEventListener('wheel', (e) => e.stopPropagation());
  document.querySelectorAll('.builder-modal').forEach((el) => {
    new MutationObserver(() => {
      if (!overlayBlockingCoach()) flushCoaches();
    }).observe(el, { attributes: true, attributeFilter: ['hidden'] });
  });
  new MutationObserver(() => {
    if (!overlayBlockingCoach()) flushCoaches();
  }).observe(root, { attributes: true, attributeFilter: ['class'] });

  function applyPreset(presetId) {
    if (!pendingDrop) return;
    const { kind, x, y } = pendingDrop;
    let values = { title: KINDS[kind].label };
    PRESETS[kind].forEach((g) => {
      g.items.forEach((item) => { if (item.id === presetId) values = { ...item.values }; });
    });
    const finalKind = values.kind || kind;
    delete values.kind;
    const n = addNode(finalKind, x, y, values);
    closePresetModal();
    selectNode(n.id);
    if (n.kind === 'depense') revealDepenseItem();
  }

  function cancelLink() {
    state.connectFrom = null;
    state.connectSide = null;
    canvas.classList.remove('is-linking');
    layer.querySelectorAll('.port.is-armed, .port.is-target').forEach((p) => {
      p.classList.remove('is-armed', 'is-target');
    });
    const ghost = svg.querySelector('[data-ghost]');
    if (ghost) ghost.remove();
  }

  function startLink(id, side) {
    dismissLinkCoach();
    state.connectFrom = id;
    state.connectSide = side;
    canvas.classList.add('is-linking');
    layer.querySelectorAll('.port.is-armed, .port.is-target').forEach((p) => {
      p.classList.remove('is-armed', 'is-target');
    });
    const sel = side === 'in' ? `[data-port-in="${id}"]` : `[data-port-out="${id}"]`;
    layer.querySelector(sel)?.classList.add('is-armed');
  }

  function portUnderPoint(clientX, clientY) {
    const stack = document.elementsFromPoint(clientX, clientY);
    for (const el of stack) {
      const out = el.closest?.('[data-port-out]');
      if (out && layer.contains(out)) {
        return { id: out.getAttribute('data-port-out'), side: 'out', el: out };
      }
      const inn = el.closest?.('[data-port-in]');
      if (inn && layer.contains(inn)) {
        return { id: inn.getAttribute('data-port-in'), side: 'in', el: inn };
      }
    }
    const world = screenToWorld(clientX, clientY);
    let best = null;
    let bestDist = 28 / state.scale;
    state.nodes.forEach((n) => {
      const meta = KINDS[n.kind];
      if (!meta) return;
      if (meta.hasOut) {
        const p = portPoint(n, 'out');
        const d = Math.hypot(p.x - world.x, p.y - world.y);
        if (d < bestDist) {
          bestDist = d;
          const el = layer.querySelector(`[data-port-out="${n.id}"]`);
          if (el) best = { id: n.id, side: 'out', el };
        }
      }
      if (meta.hasIn) {
        const p = portPoint(n, 'in');
        const d = Math.hypot(p.x - world.x, p.y - world.y);
        if (d < bestDist) {
          bestDist = d;
          const el = layer.querySelector(`[data-port-in="${n.id}"]`);
          if (el) best = { id: n.id, side: 'in', el };
        }
      }
    });
    return best;
  }

  function compatibleTarget(port) {
    if (!port || !state.connectFrom || !state.connectSide) return null;
    if (port.id === state.connectFrom || port.side === state.connectSide) return null;
    return port;
  }

  function updateLinkPreview(clientX, clientY) {
    const source = nodeById(state.connectFrom);
    if (!source) return;
    const target = compatibleTarget(portUnderPoint(clientX, clientY));
    layer.querySelectorAll('.port.is-target').forEach((p) => p.classList.remove('is-target'));
    if (target) target.el.classList.add('is-target');
    const cursor = screenToWorld(clientX, clientY);
    const dest = target ? nodeById(target.id) : null;
    const from = state.connectSide === 'out'
      ? portPoint(source, 'out')
      : (dest ? portPoint(dest, 'out') : cursor);
    const to = state.connectSide === 'in'
      ? portPoint(source, 'in')
      : (dest ? portPoint(dest, 'in') : cursor);
    let g = svg.querySelector('[data-ghost]');
    if (!g) {
      g = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      g.setAttribute('data-ghost', '1');
      g.setAttribute('fill', 'none');
      g.setAttribute('stroke', 'var(--teal)');
      g.setAttribute('stroke-width', '1.8');
      g.setAttribute('stroke-dasharray', '5 5');
      svg.appendChild(g);
    }
    g.setAttribute('d', curve(from, to));
  }

  function finishLink(clientX, clientY) {
    const target = compatibleTarget(portUnderPoint(clientX, clientY));
    if (target) {
      const from = state.connectSide === 'out' ? state.connectFrom : target.id;
      const to = state.connectSide === 'out' ? target.id : state.connectFrom;
      addEdge(from, to);
      cancelLink();
      render();
      return;
    }
    cancelLink();
  }

  let paletteDrag = null;
  let drag = null;
  let pan = null;
  let linkJustEnded = false;
  let ignoreClick = false;

  function swallowNextClick() {
    ignoreClick = true;
    setTimeout(() => { ignoreClick = false; }, 0);
  }

  function pointerMoved(from, x, y, threshold = 4) {
    const dx = x - from.sx;
    const dy = y - from.sy;
    return (dx * dx) + (dy * dy) >= threshold * threshold;
  }

  root.querySelectorAll('[data-add]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      if (btn.dataset.didDrag === '1') {
        delete btn.dataset.didDrag;
        e.preventDefault();
        return;
      }
      const kind = btn.getAttribute('data-add');
      const pos = dropPosition(null, null, kind);
      openPresetModal(kind, pos.x, pos.y);
    });
    btn.addEventListener('pointerdown', (e) => {
      if (e.button !== 0) return;
      const kind = btn.getAttribute('data-add');
      paletteDrag = { kind, sx: e.clientX, sy: e.clientY, moved: false, ghost: null, btn };
    });
  });

  document.addEventListener('pointermove', (e) => {
    if (!paletteDrag) return;
    const dx = e.clientX - paletteDrag.sx;
    const dy = e.clientY - paletteDrag.sy;
    if (!paletteDrag.moved && Math.hypot(dx, dy) < 8) return;
    paletteDrag.moved = true;
    paletteDrag.btn.classList.add('is-dragging');
    if (!paletteDrag.ghost) {
      const meta = KINDS[paletteDrag.kind];
      const g = document.createElement('div');
      if (paletteDrag.kind === 'groupe') {
        g.className = 'node is-group builder-ghost';
        g.innerHTML = `<div class="group-label"><span class="group-title">${meta.label}</span></div>`;
      } else if (paletteDrag.kind === 'note') {
        const tint = TINTS.amber;
        g.className = 'node is-note builder-ghost';
        g.style.background = tint.fill;
        g.innerHTML = `<div class="node-inner"><div class="node-head"><span class="node-kind" style="color:${tint.ink}">Note</span></div><div class="node-title">Note</div></div>`;
      } else {
        g.className = 'node builder-ghost';
        g.innerHTML = `<div class="node-inner"><div class="node-bar" style="background:${meta.color}"></div><div class="node-head"><span class="node-kind" style="color:${meta.color}">${meta.label}</span></div><div class="node-title">${meta.label}</div></div>`;
      }
      document.body.appendChild(g);
      paletteDrag.ghost = g;
    }
    paletteDrag.ghost.style.left = (e.clientX - 40) + 'px';
    paletteDrag.ghost.style.top = (e.clientY - 20) + 'px';
  });

  document.addEventListener('pointerup', (e) => {
    if (!paletteDrag) return;
    const drag = paletteDrag;
    paletteDrag = null;
    drag.btn.classList.remove('is-dragging');
    if (drag.ghost) drag.ghost.remove();
    if (!drag.moved) return;
    drag.btn.dataset.didDrag = '1';
    setTimeout(() => { delete drag.btn.dataset.didDrag; }, 0);
    const over = document.elementFromPoint(e.clientX, e.clientY);
    if (over && canvas.contains(over)) {
      const pos = dropPosition(e.clientX, e.clientY, drag.kind);
      openPresetModal(drag.kind, pos.x, pos.y);
    }
  });

  modal?.addEventListener('click', (e) => {
    if (e.target.closest('[data-preset-dismiss]')) {
      closePresetModal();
      return;
    }
    const pick = e.target.closest('[data-preset-pick]');
    if (pick) applyPreset(pick.getAttribute('data-preset-pick'));
  });

  layer.addEventListener('mousedown', (e) => {
    if (readonly) return;
    const kill = e.target.closest('[data-del]');
    if (kill) {
      e.preventDefault();
      e.stopPropagation();
      removeNode(kill.getAttribute('data-del'));
      render();
      return;
    }
    const out = e.target.closest('[data-port-out]');
    const inn = e.target.closest('[data-port-in]');
    if (out || inn) {
      e.preventDefault();
      e.stopPropagation();
      startLink(
        out ? out.getAttribute('data-port-out') : inn.getAttribute('data-port-in'),
        out ? 'out' : 'in'
      );
      updateLinkPreview(e.clientX, e.clientY);
      return;
    }
    if (e.target.closest('button, input, select')) return;
    const resize = e.target.closest('[data-resize]');
    if (resize) {
      e.preventDefault();
      e.stopPropagation();
      const id = resize.getAttribute('data-resize');
      const node = nodeById(id);
      if (!node) return;
      selectNodeLive(id);
      drag = { type: 'resize', id, sx: e.clientX, sy: e.clientY, ow: node.w || 560, oh: node.h || 340, el: resize.closest('.node'), moved: false };
      return;
    }
    const handle = e.target.closest('[data-drag]');
    if (!handle) return;
    const id = handle.getAttribute('data-drag');
    const node = nodeById(id);
    if (!node) return;
    e.preventDefault();
    e.stopPropagation();
    window.getSelection()?.removeAllRanges();
    selectNodeLive(id);
    const riders = node.kind === 'groupe'
      ? nodesInside(node).map((n) => ({ id: n.id, ox: n.x, oy: n.y, el: layer.querySelector(`[data-node="${n.id}"]`) }))
      : [];
    drag = { type: 'node', id, sx: e.clientX, sy: e.clientY, ox: node.x, oy: node.y, el: handle.closest('.node'), riders, moved: false };
  });

  canvas.addEventListener('mousedown', (e) => {
    if (e.target.closest('.node, .edge-pill, .link-coach, button, input, select')) return;
    pan = { sx: e.clientX, sy: e.clientY, ox: state.tx, oy: state.ty };
    canvas.classList.add('is-grabbing');
  });

  document.addEventListener('selectstart', (e) => {
    if (drag || pan || state.connectFrom) e.preventDefault();
  });

  document.addEventListener('mousemove', (e) => {
    if (drag || pan || state.connectFrom) {
      e.preventDefault();
      window.getSelection()?.removeAllRanges();
    }
    if (drag && drag.type === 'resize') {
      if (!drag.moved && !pointerMoved(drag, e.clientX, e.clientY)) return;
      drag.moved = true;
      const node = nodeById(drag.id);
      if (!node) return;
      node.w = Math.max(280, Math.round(drag.ow + (e.clientX - drag.sx) / state.scale));
      node.h = Math.max(160, Math.round(drag.oh + (e.clientY - drag.sy) / state.scale));
      node._w = node.w;
      node._h = node.h;
      if (drag.el) {
        drag.el.style.width = node.w + 'px';
        drag.el.style.height = node.h + 'px';
      }
    } else if (drag && drag.type === 'node') {
      if (!drag.moved && !pointerMoved(drag, e.clientX, e.clientY)) return;
      drag.moved = true;
      const node = nodeById(drag.id);
      if (!node) return;
      const dx = (e.clientX - drag.sx) / state.scale;
      const dy = (e.clientY - drag.sy) / state.scale;
      node.x = Math.round(drag.ox + dx);
      node.y = Math.round(drag.oy + dy);
      if (drag.el) {
        drag.el.style.left = node.x + 'px';
        drag.el.style.top = node.y + 'px';
      }
      (drag.riders || []).forEach((r) => {
        const rider = nodeById(r.id);
        if (!rider) return;
        rider.x = Math.round(r.ox + dx);
        rider.y = Math.round(r.oy + dy);
        if (r.el) {
          r.el.style.left = rider.x + 'px';
          r.el.style.top = rider.y + 'px';
        }
      });
      lastCompute = lastCompute || compute();
      redrawWires();
    } else if (pan) {
      state.tx = pan.ox + (e.clientX - pan.sx);
      state.ty = pan.oy + (e.clientY - pan.sy);
      applyTransform();
    }
    if (state.connectFrom) updateLinkPreview(e.clientX, e.clientY);
  });

  document.addEventListener('mouseup', (e) => {
    if (drag && (drag.type === 'node' || drag.type === 'resize')) {
      if (drag.moved) {
        render({ props: false });
        swallowNextClick();
      }
    }
    drag = null;
    pan = null;
    canvas.classList.remove('is-grabbing');
    if (state.connectFrom) {
      finishLink(e.clientX, e.clientY);
      linkJustEnded = true;
      setTimeout(() => { linkJustEnded = false; }, 0);
    }
  });

  layer.addEventListener('click', (e) => {
    if (readonly) return;
    if (linkJustEnded || ignoreClick) {
      e.stopPropagation();
      return;
    }
    if (e.target.closest('[data-del], .port')) {
      e.stopPropagation();
      return;
    }
    const node = e.target.closest('[data-node]');
    if (node) {
      const id = node.getAttribute('data-node');
      const already = state.selected === id;
      selectNode(id);
      const n = nodeById(id);
      if (n && (n.kind === 'groupe' || n.kind === 'note') && (already || e.target.closest('.group-title, .group-label, .node-title'))) {
        const field = propsForm?.querySelector('[data-prop="title"]');
        field?.focus();
        field?.select();
      }
    }
  });

  labels?.addEventListener('click', (e) => {
    if (readonly) return;
    const pill = e.target.closest('[data-edge]');
    if (!pill) return;
    const edge = state.edges.find((x) => x.id === pill.getAttribute('data-edge'));
    if (!edge) return;
    state.selected = edge.from;
    state.openEdge = edge.id;
    root.classList.add('is-props-open');
    dismissSplitCoach();
    render();
    propsForm?.querySelector(`[data-edge-edit="${edge.id}"]`)?.scrollIntoView({ block: 'nearest' });
  });

  canvas.addEventListener('click', (e) => {
    if (linkJustEnded || ignoreClick) return;
    if (e.target.closest('.node, .edge-pill, .port, .link-coach, button')) return;
    if (state.connectFrom) { cancelLink(); return; }
    if (state.selected) selectNode(null);
  });

  canvas.addEventListener('wheel', (e) => {
    if (readonly && !e.ctrlKey && !e.metaKey) return;
    e.preventDefault();
    const f = e.deltaY < 0 ? 1.12 : 1 / 1.12;
    const k = Math.min(1.8, Math.max(0.28, state.scale * f));
    const r = canvas.getBoundingClientRect();
    const mx = e.clientX - r.left;
    const my = e.clientY - r.top;
    state.tx = mx - (mx - state.tx) * (k / state.scale);
    state.ty = my - (my - state.ty) * (k / state.scale);
    state.scale = k;
    applyTransform();
  }, { passive: false });

  propsForm?.addEventListener('input', (e) => {
    const n = nodeById(state.selected);
    if (!n) return;
    const prop = e.target.getAttribute('data-prop');
    if (prop === 'title') n.title = e.target.value;
    if (prop === 'note') n.note = e.target.value;
    if (prop === 'amount') {
      n.amount = Number(e.target.value) || 0;
      if (n.kind === 'depense') syncDepenseItems(n);
    }
    const itemTitle = e.target.getAttribute('data-item-title');
    const itemAmount = e.target.getAttribute('data-item-amount');
    if (itemTitle && n.kind === 'depense') {
      const item = (n.items || []).find((x) => x.id === itemTitle);
      if (item) item.title = e.target.value;
    }
    if (itemAmount && n.kind === 'depense') {
      const item = (n.items || []).find((x) => x.id === itemAmount);
      if (item) item.amount = Math.max(0, Number(e.target.value) || 0);
      syncDepenseItems(n);
    }
    if (prop === 'start') n.start = Number(e.target.value) || 0;
    if (prop === 'rate') { n.rate = Number(e.target.value) || 0; n.preset = 'custom'; }
    if (prop === 'cap') { n.cap = Number(e.target.value) || 0; n.preset = 'custom'; }
    if (prop === 'preset') {
      n.preset = e.target.value;
      const pack = LIVRET_PRESETS[n.preset];
      if (pack && n.preset !== 'custom') {
        if (!n.title || Object.values(LIVRET_PRESETS).some((p) => p.title === n.title)) n.title = pack.title;
        n.rate = pack.rate;
        n.cap = pack.cap;
        const rate = propsForm.querySelector('[data-prop="rate"]');
        const cap = propsForm.querySelector('[data-prop="cap"]');
        const title = propsForm.querySelector('[data-prop="title"]');
        if (rate) rate.value = n.rate;
        if (cap) cap.value = n.cap;
        if (title) title.value = n.title;
      }
    }
    const edgeMode = e.target.getAttribute('data-edge-mode');
    const edgeValue = e.target.getAttribute('data-edge-value');
    if (edgeMode) {
      const edge = state.edges.find((x) => x.id === edgeMode);
      if (edge) {
        edge.mode = e.target.value;
        if (edge.mode === 'pct' && !edge.value) edge.value = 25;
        if (edge.mode === 'fixe' && !edge.value) edge.value = 200;
      }
    }
    if (edgeValue) {
      const edge = state.edges.find((x) => x.id === edgeValue);
      if (edge) edge.value = Number(e.target.value) || 0;
    }
    lastCompute = compute();
    renderCanvas();
    renderSide();
    drawTimeChart();
    syncTimePlay();
    syncPayload();
    if (edgeMode) renderProps();
    else if (itemAmount && n.kind === 'depense') refreshDepenseReadout(n);
    else if (n.kind === 'livret' && (prop === 'rate' || prop === 'start' || prop === 'cap' || prop === 'preset')) {
      refreshLivretInterest(n);
    } else {
      refreshLectureStats(n);
    }
  });

  propsForm?.addEventListener('click', (e) => {
    const colorToggle = e.target.closest('[data-prop-color-toggle]');
    if (colorToggle) {
      const wrap = colorToggle.closest('.prop-color');
      const open = !wrap?.classList.contains('is-open');
      closeTintMenus(open ? wrap : null);
      if (wrap) {
        wrap.classList.toggle('is-open', open);
        colorToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        const menu = wrap.querySelector('.prop-color-menu');
        if (menu) menu.hidden = !open;
      }
      return;
    }
    const tintBtn = e.target.closest('[data-prop-tint]');
    if (tintBtn) {
      const n = nodeById(state.selected);
      if (n) {
        n.tint = tintBtn.getAttribute('data-prop-tint');
        render();
      }
      return;
    }
    const addItem = e.target.closest('[data-item-add]');
    if (addItem) {
      const n = nodeById(state.selected);
      if (n && n.kind === 'depense') {
        const item = addDepenseItem(n);
        render();
        if ((n.items || []).length >= 2) dismissItemsCoach();
        if (item) revealDepenseItem(item.id);
      }
      return;
    }
    const delItem = e.target.closest('[data-item-del]');
    if (delItem) {
      const n = nodeById(state.selected);
      if (n && n.kind === 'depense') {
        const id = delItem.getAttribute('data-item-del');
        n.items = (n.items || []).filter((item) => item.id !== id);
        syncDepenseItems(n);
        render();
      }
      return;
    }
    const delEdge = e.target.closest('[data-edge-del]');
    if (delEdge) {
      state.edges = state.edges.filter((ed) => ed.id !== delEdge.getAttribute('data-edge-del'));
      render();
      return;
    }
    if (e.target.closest('[data-del-selected]') && state.selected) {
      removeNode(state.selected);
      render();
    }
    if (!e.target.closest('.prop-color')) closeTintMenus();
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.prop-color')) closeTintMenus();
    if (!e.target.closest('[data-horizon-unit]')) closeHorizonUnitMenu();
  });

  root.querySelector('[data-horizon]')?.addEventListener('input', () => {
    applyHorizonFromInput();
    render({ props: false });
    renderProps();
  });
  horizonUnitToggle?.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    if (horizonUnitOpen()) closeHorizonUnitMenu();
    else openHorizonUnitMenu();
  });
  horizonUnitMenu?.addEventListener('click', (e) => {
    const opt = e.target.closest('[data-horizon-unit-opt]');
    if (!opt) return;
    e.preventDefault();
    setHorizonUnit(opt.getAttribute('data-horizon-unit-opt'));
  });

  root.querySelector('[data-zoom-in]')?.addEventListener('click', () => { state.scale = Math.min(1.8, state.scale * 1.15); applyTransform(); });
  root.querySelector('[data-zoom-out]')?.addEventListener('click', () => { state.scale = Math.max(0.28, state.scale / 1.15); applyTransform(); });
  root.querySelector('[data-fit]')?.addEventListener('click', fit);
  root.querySelector('[data-clear]')?.addEventListener('click', () => {
    if (!confirm('Vider le canvas ?')) return;
    state.nodes = [];
    state.edges = [];
    state.selected = null;
    cancelLink();
    render();
  });
  root.querySelector('[data-props-toggle]')?.addEventListener('click', (event) => {
    const open = !root.classList.contains('is-props-open');
    root.classList.toggle('is-props-open', open);
    event.currentTarget.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  form?.addEventListener('submit', (e) => {
    if (readonly) {
      e.preventDefault();
      return;
    }
    syncPayload();
    if (!isDirty()) {
      e.preventDefault();
      return;
    }
    saving = true;
  });

  function setupOpen() {
    return Boolean(setupModal && !setupModal.hidden);
  }

  function clearSetupParam() {
    try {
      const url = new URL(window.location.href);
      if (!url.searchParams.has('nouveau')) return;
      url.searchParams.delete('nouveau');
      const next = url.pathname + url.search + url.hash;
      history.replaceState({}, '', next);
    } catch (e) {}
  }

  function syncSetupPresets() {
    if (!setupModal || !setupHorizon) return;
    const current = parseInt(setupHorizon.value, 10);
    setupModal.querySelectorAll('[data-setup-preset]').forEach((btn) => {
      btn.classList.toggle('active', parseInt(btn.getAttribute('data-setup-preset'), 10) === current);
    });
  }

  function closeSetupModal() {
    if (!setupModal) return;
    setupModal.hidden = true;
    if (!scenarioModal || scenarioModal.hidden) document.body.classList.remove('is-locked');
    clearSetupParam();
  }

  function scenarioOpen() {
    return Boolean(scenarioModal && !scenarioModal.hidden);
  }

  function openScenarioModal() {
    if (readonly || !scenarioModal) return;
    closePresetModal();
    if (setupModal && !setupModal.hidden) closeSetupModal();
    scenarioModal.hidden = false;
    document.body.classList.add('is-locked');
  }

  function closeScenarioModal() {
    if (!scenarioModal) return;
    scenarioModal.hidden = true;
    document.body.classList.remove('is-locked');
  }

  function reportOpen() {
    return Boolean(reportModal && !reportModal.hidden);
  }

  function fillReport() {
    if (!reportModal || !reportBody) return;
    const C = lastCompute || compute();
    lastCompute = C;
    const month = currentMonth();
    const span = reportSpanLabel(month);
    const lines = reportSpendLines();
    const insights = reportInsights(lines, month);
    if (reportTitle) reportTitle.textContent = month <= 0 ? 'Rapport' : 'Rapport · ' + playLabel(month);
    const marks = reportMarks();
    const chips = marks.map((m) => (
      `<button type="button" class="chip${m === month ? ' active' : ''}" data-report-month="${m}">${escapeHtml(yearsLabel(m))}</button>`
    )).join('');
    const kpis = month > 0 ? `
      <div class="report-kpis">
        <div><span>Entrées</span><b class="mono">${euro(C.inn * month)}</b></div>
        <div><span>Dépenses</span><b class="mono">${euro(C.out * month)}</b></div>
        <div><span>Mis de côté</span><b class="mono">${euro(C.saved * month)}</b></div>
        <div><span>Patrimoine</span><b class="mono is-proj">${euro(playTotal(C))}</b></div>
      </div>
    ` : '';
    const insightHtml = insights.length
      ? `<div class="report-insights">${insights.map((row) => `<p class="report-insight"><strong>${escapeHtml(row.title)}</strong> : ${escapeHtml(row.text)}</p>`).join('')}</div>`
      : '';
    const lineHtml = lines.length && month > 0
      ? `<div class="report-section">
          <div class="eyebrow">Postes du mois, cumulés</div>
          <div class="report-lines">
            ${lines.map((row) => `<div class="report-line"><span>${escapeHtml(row.title)}</span><span class="is-month">${euro(row.monthly)}/mois</span><b>${euro(row.total)}</b></div>`).join('')}
          </div>
        </div>`
      : '';
    const emptyHtml = month <= 0
      ? '<p class="report-empty">Avancez sur la frise, ou choisissez une durée. Rien n’est encore accumulé.</p>'
      : (!lines.length
        ? '<p class="report-empty">Aucun poste de dépense pour l’instant. Posez un bloc Dépense, ou chargez un scénario.</p>'
        : '');
    const lead = month <= 0
      ? 'Le mois type, répété jusqu’à la durée choisie.'
      : 'Le mois type, répété pendant ' + span + '. Les montants suivent le curseur de la frise.';
    reportBody.innerHTML = `
      <p class="report-lead">${escapeHtml(lead)}</p>
      ${marks.length ? `<div class="chips report-chips">${chips}</div>` : ''}
      ${kpis}
      ${insightHtml}
      ${lineHtml}
      ${emptyHtml}
      ${month > 0 ? '<div class="report-actions"><button type="button" class="btn btn-ghost" data-report-print>Imprimer</button></div>' : ''}
    `;
  }

  function openReportModal() {
    if (!reportModal) return;
    if (currentMonth() <= 0 && state.horizon > 0) setPlayMonth(state.horizon, false);
    fillReport();
    reportModal.hidden = false;
    document.body.classList.add('is-locked');
  }

  function closeReportModal() {
    if (!reportModal) return;
    reportModal.hidden = true;
    if (!fullscreenModalOpen()) document.body.classList.remove('is-locked');
  }

  const KIND_LAYER = { revenu: 0, compte: 1, repartiteur: 2, livret: 3, depense: 3 };
  const KIND_WEIGHT = { revenu: 0, compte: 1, depense: 2, repartiteur: 3, livret: 4 };

  function nodeBox(n) {
    let w;
    let h;
    if (n.kind === 'groupe') {
      w = n.w || n._w || 560;
      h = n.h || n._h || 340;
    } else if (n.kind === 'note') {
      w = n.w || n._w || 200;
      h = n._h || 90;
    } else {
      w = n._w || 244;
      h = n._h || 118;
    }
    return { x: n.x, y: n.y, w, h };
  }

  function layoutCircuit() {
    const graph = state.nodes.filter((n) => !isAnnotation(n));
    if (!graph.length) return;

    const byId = {};
    const preds = {};
    const succs = {};
    graph.forEach((n) => {
      byId[n.id] = n;
      preds[n.id] = [];
      succs[n.id] = [];
    });
    state.edges.forEach((e) => {
      if (!byId[e.from] || !byId[e.to] || e.from === e.to) return;
      succs[e.from].push(e.to);
      preds[e.to].push(e.from);
    });

    const rank = {};
    const indeg = {};
    graph.forEach((n) => { indeg[n.id] = preds[n.id].length; });
    const q = graph.filter((n) => indeg[n.id] === 0).map((n) => n.id);
    q.forEach((id) => { rank[id] = 0; });
    const seen = {};
    while (q.length) {
      const id = q.shift();
      if (seen[id]) continue;
      seen[id] = 1;
      (succs[id] || []).forEach((to) => {
        rank[to] = Math.max(rank[to] || 0, (rank[id] || 0) + 1);
        indeg[to] -= 1;
        if (indeg[to] === 0) q.push(to);
      });
    }
    graph.forEach((n) => {
      if (rank[n.id] !== undefined) return;
      const known = preds[n.id].map((id) => rank[id]).filter((r) => r !== undefined);
      rank[n.id] = known.length ? Math.max(...known) + 1 : (KIND_LAYER[n.kind] || 0);
    });
    graph.forEach((n) => {
      if (preds[n.id].length || succs[n.id].length) return;
      rank[n.id] = KIND_LAYER[n.kind] ?? 0;
    });

    const compactRanks = () => {
      const used = [...new Set(graph.map((n) => rank[n.id]))].sort((a, b) => a - b);
      const remap = {};
      used.forEach((r, i) => { remap[r] = i; });
      graph.forEach((n) => { rank[n.id] = remap[rank[n.id]]; });
      return used.length;
    };
    compactRanks();
    const byRank = {};
    graph.forEach((n) => { (byRank[rank[n.id]] ||= []).push(n); });
    Object.keys(byRank).forEach((r) => {
      const list = byRank[r];
      if (list.length <= 4) return;
      const hasD = list.some((n) => n.kind === 'depense');
      const hasL = list.some((n) => n.kind === 'livret');
      if (!hasD || !hasL) return;
      list.filter((n) => n.kind === 'livret').forEach((n) => { rank[n.id] += 1; });
    });
    const layerCount = compactRanks();
    const maxRank = Math.max(0, layerCount - 1);

    const layers = [];
    for (let i = 0; i <= maxRank; i += 1) layers.push([]);
    graph.slice().sort((a, b) => {
      const dw = (KIND_WEIGHT[a.kind] || 0) - (KIND_WEIGHT[b.kind] || 0);
      if (dw) return dw;
      return (a.y - b.y) || String(a.title || '').localeCompare(String(b.title || ''), 'fr');
    }).forEach((n) => layers[rank[n.id]].push(n.id));

    const indexOf = () => {
      const idx = {};
      layers.forEach((ids) => ids.forEach((id, i) => { idx[id] = i; }));
      return idx;
    };
    const sortByBary = (ids, neigh) => {
      const idx = indexOf();
      return ids.slice().sort((a, b) => {
        const bar = (id) => {
          const ns = neigh[id] || [];
          if (!ns.length) return idx[id] ?? 0;
          return ns.reduce((s, nid) => s + (idx[nid] ?? 0), 0) / ns.length;
        };
        const d = bar(a) - bar(b);
        if (Math.abs(d) > 1e-6) return d;
        return (idx[a] ?? 0) - (idx[b] ?? 0);
      });
    };
    for (let iter = 0; iter < 4; iter += 1) {
      for (let i = 1; i < layers.length; i += 1) layers[i] = sortByBary(layers[i], preds);
    }

    const nodeW = 244;
    const gapX = 168;
    const gapY = 56;
    const originX = 48;
    const originY = 40;
    const heights = {};
    graph.forEach((n) => { heights[n.id] = nodeBox(n).h; });
    layers.forEach((ids, li) => {
      const x = originX + li * (nodeW + gapX);
      let y = originY;
      ids.forEach((id) => {
        const n = byId[id];
        n.x = Math.round(x);
        n.y = Math.round(y);
        y += heights[id] + gapY;
      });
      if (ids.length !== 1) return;
      const id = ids[0];
      const ps = preds[id];
      if (!ps.length) return;
      const avg = ps.reduce((s, nid) => s + byId[nid].y + heights[nid] / 2, 0) / ps.length;
      byId[id].y = Math.round(Math.max(originY, avg - heights[id] / 2));
    });

    let x2 = 0;
    let y2 = 0;
    graph.forEach((n) => {
      const b = nodeBox(n);
      x2 = Math.max(x2, n.x + b.w);
      y2 = Math.max(y2, n.y + b.h);
    });
    state.nodes.filter((n) => n.kind === 'note').forEach((n, i) => {
      n.x = originX;
      n.y = Math.round(y2 + 72 + i * 140);
    });
  }

  function applyScenario(key) {
    const pack = SCENARIOS[key];
    if (!pack || !pack.payload) return;
    if (state.nodes.length && !confirm('Remplacer le circuit actuel par « ' + pack.title + ' » ?')) return;
    state.nodes = (pack.payload.nodes || []).map(normalizeNode);
    state.edges = (pack.payload.edges || []).map(normalizeEdge);
    state.horizon = Number(pack.payload.horizon) || state.horizon;
    state.selected = null;
    state.openEdge = null;
    cancelLink();
    syncHorizonInput();
    const currentName = (nameInput?.value || '').trim();
    if (nameInput && (!currentName || /^nouveau circuit$/i.test(currentName))) {
      nameInput.value = pack.title;
      document.title = pack.title + ' — repartio.fr';
    }
    closeScenarioModal();
    render({ props: false });
    layoutCircuit();
    render();
    requestAnimationFrame(fit);
  }

  function applySetup() {
    const name = (setupName?.value || '').trim() || 'Nouveau circuit';
    const raw = parseInt(setupHorizon?.value, 10);
    const horizon = Number.isNaN(raw) ? 60 : Math.min(360, Math.max(1, raw));
    if (nameInput) nameInput.value = name;
    document.title = name + ' — repartio.fr';
    state.horizon = horizon;
    syncHorizonInput();
    syncPayload();
    render({ props: false });
    if (form) {
      const data = new FormData(form);
      data.delete('payload');
      data.set('horizon', String(horizon));
      data.set('name', name);
      fetch(form.action, {
        method: 'POST',
        body: data,
        redirect: 'manual',
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      }).catch(() => {});
    }
    closeSetupModal();
  }

  setupModal?.addEventListener('click', (e) => {
    if (e.target.closest('[data-setup-dismiss]')) closeSetupModal();
  });
  setupHorizon?.addEventListener('input', syncSetupPresets);
  setupModal?.querySelectorAll('[data-setup-preset]').forEach((btn) => {
    btn.addEventListener('click', () => {
      if (!setupHorizon) return;
      setupHorizon.value = btn.getAttribute('data-setup-preset') || '60';
      syncSetupPresets();
    });
  });
  setupForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    applySetup();
  });
  document.querySelectorAll('[data-scenario-open]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      openScenarioModal();
    });
  });
  scenarioModal?.addEventListener('click', (e) => {
    if (e.target.closest('[data-scenario-dismiss]')) {
      closeScenarioModal();
      return;
    }
    const pick = e.target.closest('[data-scenario-load]');
    if (pick) applyScenario(pick.getAttribute('data-scenario-load'));
  });
  document.querySelectorAll('[data-report-open]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      openReportModal();
    });
  });
  reportModal?.addEventListener('click', (e) => {
    if (e.target.closest('[data-report-dismiss]')) {
      closeReportModal();
      return;
    }
    const mark = e.target.closest('[data-report-month]');
    if (mark) {
      setPlayMonth(Number(mark.getAttribute('data-report-month')) || 0, true);
      return;
    }
    if (e.target.closest('[data-report-print]')) {
      document.body.classList.add('is-print-report');
      window.print();
      window.setTimeout(() => document.body.classList.remove('is-print-report'), 400);
    }
  });
  if (setupOpen() && !readonly) {
    document.body.classList.add('is-locked');
    requestAnimationFrame(() => {
      setupName?.focus();
      setupName?.select();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (horizonUnitOpen()) { closeHorizonUnitMenu(); return; }
      if (propsForm?.querySelector('.prop-color.is-open')) { closeTintMenus(); return; }
      if (setupOpen()) { closeSetupModal(); return; }
      if (scenarioOpen()) { closeScenarioModal(); return; }
      if (reportOpen()) { closeReportModal(); return; }
      if (modal && !modal.hidden) { closePresetModal(); return; }
      if (state.connectFrom) { cancelLink(); return; }
      if (dismissLinkCoach()) return;
      if (dismissSplitCoach()) return;
      if (dismissItemsCoach()) return;
      if (state.selected) selectNode(null);
      return;
    }
    if ((e.key === 'Delete' || e.key === 'Backspace') && !readonly && state.selected && !['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
      removeNode(state.selected);
      render();
    }
  });

  let tourFocus = [];

  function applyTourFocus() {
    const ids = new Set(tourFocus);
    const on = ids.size > 0;
    layer?.querySelectorAll('.node').forEach((el) => {
      el.classList.toggle('is-tour-on', on && ids.has(el.dataset.node));
      el.classList.toggle('is-tour-dim', on && !ids.has(el.dataset.node));
    });
    state.edges.forEach((e) => {
      const hit = !on || ids.has(e.from) || ids.has(e.to);
      flow.paths[e.id]?.classList.toggle('is-tour-dim', on && !hit);
      flow.paths[e.id]?.classList.toggle('is-tour-on', on && hit);
      flow.pills[e.id]?.classList.toggle('is-tour-dim', on && !hit);
    });
  }

  function animateCamera(k, tx, ty) {
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduced) {
      state.scale = k;
      state.tx = tx;
      state.ty = ty;
      applyTransform();
      return;
    }
    const s0 = state.scale;
    const x0 = state.tx;
    const y0 = state.ty;
    const t0 = performance.now();
    const dur = 520;
    const ease = (t) => (t < 0.5 ? 4 * t * t * t : 1 - (((-2 * t + 2) ** 3) / 2));
    const tick = (now) => {
      const t = Math.min(1, (now - t0) / dur);
      const e = ease(t);
      state.scale = s0 + (k - s0) * e;
      state.tx = x0 + (tx - x0) * e;
      state.ty = y0 + (ty - y0) * e;
      applyTransform();
      if (t < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  }

  function focusNodes(ids, pad = 72) {
    const nodes = state.nodes.filter((n) => ids.includes(n.id));
    if (!nodes.length) {
      fit();
      return;
    }
    let x1 = 1e9;
    let y1 = 1e9;
    let x2 = -1e9;
    let y2 = -1e9;
    nodes.forEach((n) => {
      x1 = Math.min(x1, n.x);
      y1 = Math.min(y1, n.y);
      x2 = Math.max(x2, n.x + (n._w || 244));
      y2 = Math.max(y2, n.y + (n._h || 110));
    });
    const r = canvas.getBoundingClientRect();
    const padB = Math.max(pad, 72);
    let k = Math.min(1.15, (r.width - pad * 2) / Math.max(1, x2 - x1), (r.height - pad - padB) / Math.max(1, y2 - y1));
    k = Math.max(0.35, Math.min(1.2, k));
    animateCamera(k, (r.width - (x2 - x1) * k) / 2 - x1 * k, pad + (r.height - pad - padB - (y2 - y1) * k) / 2 - y1 * k);
  }

  function fit() {
    if (!state.nodes.length) {
      state.scale = 0.85;
      state.tx = 24;
      state.ty = 24;
      applyTransform();
      return;
    }
    let x1 = 1e9, y1 = 1e9, x2 = -1e9, y2 = -1e9;
    state.nodes.forEach((n) => {
      x1 = Math.min(x1, n.x);
      y1 = Math.min(y1, n.y);
      x2 = Math.max(x2, n.x + (n._w || 244));
      y2 = Math.max(y2, n.y + (n._h || 110));
    });
    const r = canvas.getBoundingClientRect();
    const padX = 48;
    const padT = 48;
    const padB = 72;
    let k = Math.min(1.2, (r.width - padX * 2) / Math.max(1, x2 - x1), (r.height - padT - padB) / Math.max(1, y2 - y1));
    k = Math.max(0.3, Math.min(1, k));
    state.scale = k;
    state.tx = (r.width - (x2 - x1) * k) / 2 - x1 * k;
    state.ty = padT + (r.height - padT - padB - (y2 - y1) * k) / 2 - y1 * k;
    applyTransform();
  }

  if (nameInput) nameInput.addEventListener('input', syncPayload);
  window.addEventListener('beforeunload', (e) => {
    if (readonly || saving || !isDirty()) return;
    e.preventDefault();
    e.returnValue = '';
  });
  bindTimeControls();
  render();
  if (payloadBroken) {
    savedSnap = '';
    syncSaveButton();
  } else {
    markSaved();
  }
  requestAnimationFrame((t) => { lastTick = t; requestAnimationFrame(tick); });

  root.repartioTour = {
    highlight(ids) {
      tourFocus = Array.isArray(ids) ? ids : [];
      applyTourFocus();
    },
    focus(ids, pad) {
      focusNodes(Array.isArray(ids) ? ids : [], pad);
    },
    fit,
    setMonth(month) {
      setPlayMonth(month, true);
    },
  };
})();
