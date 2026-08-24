(() => {
  const root = document.querySelector('[data-builder]');
  if (!root) return;

  const KINDS = {
    revenu: { label: 'Revenu', color: 'oklch(0.48 0.10 152)', hasIn: false, hasOut: true },
    compte: { label: 'Compte', color: 'oklch(0.48 0.10 248)', hasIn: true, hasOut: true },
    repartiteur: { label: 'Répartiteur', color: 'oklch(0.48 0.12 300)', hasIn: true, hasOut: true },
    livret: { label: 'Livret', color: 'oklch(0.55 0.11 62)', hasIn: true, hasOut: true },
    depense: { label: 'Dépense', color: 'oklch(0.52 0.14 32)', hasIn: true, hasOut: false },
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
  };

  const LIVRET_PRESETS = {
    'livret-a': { title: 'Livret A', rate: 1.7, cap: 22950 },
    ldds: { title: 'LDDS', rate: 1.7, cap: 12000 },
    lep: { title: 'LEP', rate: 2.5, cap: 10000 },
    jeune: { title: 'Livret Jeune', rate: 1.7, cap: 1600 },
    custom: { title: 'Livret', rate: 0, cap: 0 },
  };

  const readonly = root.hasAttribute('data-readonly');
  const initial = JSON.parse(root.getAttribute('data-payload') || '{}');
  const state = {
    nodes: (initial.nodes || []).map(normalizeNode),
    edges: (initial.edges || []).map(normalizeEdge),
    horizon: Number(initial.horizon) || 60,
    scale: 0.85,
    tx: 24,
    ty: 24,
    selected: null,
    openEdge: null,
    connectFrom: null,
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
  let lastCompute = null;
  let pendingDrop = null;

  function uid(prefix) {
    return prefix + Math.random().toString(36).slice(2, 8);
  }

  function normalizeNode(n) {
    return {
      id: n.id || uid('n'),
      kind: KINDS[n.kind] ? n.kind : 'compte',
      title: n.title || KINDS[n.kind]?.label || 'Bloc',
      x: Number(n.x) || 80,
      y: Number(n.y) || 80,
      amount: Number(n.amount) || 0,
      start: Number(n.start) || 0,
      rate: Number(n.rate) || 0,
      cap: Number(n.cap) || 0,
      preset: n.preset || '',
    };
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
    const byId = {};
    const outs = {};
    const indeg = {};
    state.nodes.forEach((n) => { byId[n.id] = n; outs[n.id] = []; indeg[n.id] = 0; });
    state.edges.forEach((e) => {
      if (!byId[e.from] || !byId[e.to]) return;
      outs[e.from].push(e);
      indeg[e.to] += 1;
    });

    const q = state.nodes.filter((n) => indeg[n.id] === 0).map((n) => n.id);
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
    const cycle = order.length !== state.nodes.length;
    state.nodes.forEach((n) => { if (!seen[n.id]) order.push(n.id); });

    const inflow = {};
    const kept = {};
    const over = {};
    state.nodes.forEach((n) => { inflow[n.id] = 0; });
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
    let proj = 0;
    state.nodes.forEach((n) => {
      if (n.kind !== 'livret') return;
      let b = Math.max(0, n.start);
      const cap = n.cap > 0 ? n.cap : Infinity;
      const add = kept[n.id] || 0;
      let when = null;
      if (b >= cap - 0.5) when = 0;
      for (let m = 1; m <= state.horizon; m += 1) {
        b += b * (Math.max(0, n.rate) / 100) / 12;
        b = Math.min(cap, b + add);
        if (when === null && b >= cap - 0.5) when = m;
      }
      fin[n.id] = b;
      full[n.id] = when;
      proj += b;
      if (when !== null) sat.push({ name: n.title, m: when });
    });

    let inn = 0;
    let out = 0;
    let saved = 0;
    let leftover = 0;
    state.nodes.forEach((n) => {
      const outAmt = (outs[n.id] || []).reduce((s, e) => s + e._amt, 0);
      if (n.kind === 'revenu') inn += Math.max(0, n.amount);
      else if (n.kind === 'depense') out += (kept[n.id] || 0) + outAmt;
      else if (n.kind === 'livret') saved += kept[n.id] || 0;
      else leftover += kept[n.id] || 0;
    });

    return { byId, outs, inflow, kept, over, cycle, inn, out, saved, leftover, proj, fin, full, sat };
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

  function nodeStats(n, C) {
    const inflow = C.inflow[n.id] || 0;
    const kept = C.kept[n.id] || 0;
    const out = (C.outs[n.id] || []).reduce((s, e) => s + e._amt, 0);
    const rows = [];
    if (n.kind === 'revenu') {
      rows.push(['Par mois', euro(n.amount)]);
      rows.push(['Sort', euro(out)]);
    } else {
      rows.push(['Reçoit', euro(inflow)]);
    }
    if (n.kind === 'compte') rows.push(['Reste dessus', euro(kept)]);
    if (n.kind === 'livret') {
      rows.push(['Taux', (n.rate || 0).toString().replace('.', ',') + ' %']);
      rows.push(['Dans ' + state.horizon + ' mois', euro(C.fin[n.id] || 0)]);
    }
    if (n.kind === 'depense') rows.push(['Sur ' + state.horizon + ' mois', euro(inflow * state.horizon)]);
    let chip = '';
    if (n.kind === 'repartiteur') {
      chip = kept > 0.5 ? { text: euro(kept) + ' non ventilés', bad: true } : { text: 'tout est ventilé', bad: false };
    }
    if (n.kind === 'livret' && C.full[n.id] !== null && C.full[n.id] !== undefined) {
      chip = { text: C.full[n.id] === 0 ? 'déjà plein' : 'plein en ' + C.full[n.id] + ' mois', bad: false };
    }
    if (C.over[n.id]) chip = { text: 'sorties > entrées', bad: true };
    return { rows, chip };
  }

  function renderCanvas() {
    const C = lastCompute || compute();
    lastCompute = C;
    layer.querySelectorAll('.node').forEach((el) => el.remove());
    svg.innerHTML = '';
    if (labels) labels.innerHTML = '';

    state.nodes.forEach((n) => {
      const meta = KINDS[n.kind];
      const stats = nodeStats(n, C);
      const el = document.createElement('div');
      el.className = 'node' + (state.selected === n.id ? ' is-selected' : '');
      el.dataset.node = n.id;
      el.style.left = n.x + 'px';
      el.style.top = n.y + 'px';
      el.innerHTML = `
        <div class="node-inner">
          <div class="node-bar" style="background:${meta.color}"></div>
          <div class="node-head"${readonly ? '' : ` data-drag="${n.id}"`}>
            <span class="node-kind" style="color:${meta.color}">${meta.label}</span>
            ${readonly ? '' : `<button type="button" class="node-kill" data-del="${n.id}" title="Supprimer">×</button>`}
          </div>
          <div class="node-title">${escapeHtml(n.title)}</div>
          <div class="node-body">
            ${stats.rows.map(([k, v]) => `<div class="node-stat"><span>${k}</span><b>${v}</b></div>`).join('')}
            ${stats.chip ? `<div class="node-chip${stats.chip.bad ? ' is-bad' : ''}">${stats.chip.text}</div>` : ''}
          </div>
        </div>
        ${readonly ? '' : (meta.hasIn ? `<div class="port port-in" data-port-in="${n.id}" style="border:2px solid ${meta.color}" title="Entrée"></div>` : '')}
        ${readonly ? '' : (meta.hasOut ? `<div class="port port-out" data-port-out="${n.id}" style="background:${meta.color};border:2px solid #fff;box-shadow:0 0 0 1px ${meta.color}" title="Sortie"></div>` : '')}
      `;
      layer.appendChild(el);
      n._w = el.offsetWidth;
      n._h = el.offsetHeight;
    });

    if (state.connectFrom) {
      const armed = layer.querySelector(`[data-port-out="${state.connectFrom}"]`);
      if (armed) armed.classList.add('is-armed');
    }

    state.edges.forEach((e) => {
      const a = C.byId[e.from];
      const b = C.byId[e.to];
      if (!a || !b) return;
      const p1 = portPoint(a, 'out');
      const p2 = portPoint(b, 'in');
      const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.setAttribute('d', curve(p1, p2));
      path.setAttribute('fill', 'none');
      path.setAttribute('stroke', e._amt > 0.5 ? (KINDS[a.kind]?.color || '#999') : 'oklch(0.82 0.02 255)');
      path.setAttribute('stroke-width', e._amt > 0.5 ? '2' : '1.5');
      path.setAttribute('stroke-linecap', 'round');
      svg.appendChild(path);

      if (!labels) return;
      const mid = { x: (p1.x + p2.x) / 2, y: (p1.y + p2.y) / 2 };
      const pill = document.createElement('button');
      pill.type = 'button';
      const tag = e.mode === 'pct' ? e.value + ' %' : (e.mode === 'fixe' ? 'fixe' : 'reste');
      pill.className = 'edge-pill' + (e._amt > 0.5 ? '' : ' is-zero') + (state.openEdge === e.id ? ' is-open' : '');
      if (readonly) pill.disabled = true;
      pill.dataset.edge = e.id;
      pill.style.left = mid.x + 'px';
      pill.style.top = mid.y + 'px';
      pill.innerHTML = `${euro(e._amt)}<i>${tag}</i>`;
      labels.appendChild(pill);
    });
  }

  function renderSide() {
    const C = lastCompute || compute();
    lastCompute = C;
    const set = (sel, val) => { const el = root.querySelector(sel); if (el) el.textContent = val; };
    set('[data-stat="in"]', euro(C.inn));
    set('[data-stat="out"]', euro(C.out));
    set('[data-stat="saved"]', euro(C.saved));
    set('[data-stat="unassigned"]', euro(C.leftover));
    set('[data-stat="proj"]', euro(C.proj));
    const years = state.horizon >= 24 ? 'Dans ' + Math.round(state.horizon / 12) + ' ans' : 'Dans ' + state.horizon + ' mois';
    set('[data-horizon-label]', years);
    const hint = root.querySelector('[data-stat="proj-hint"]');
    if (hint) {
      hint.textContent = C.sat.length
        ? 'Saturés d’ici là : ' + C.sat.map((s) => s.name + ' (' + (s.m === 0 ? 'déjà' : s.m + ' m') + ')').join(', ') + '.'
        : (C.proj > 0 ? 'Aucun livret n’atteint son plafond sur la période.' : '');
    }
    const warns = [];
    if (C.cycle) warns.push('Le circuit contient une boucle. Retirez un lien pour la casser.');
    state.nodes.forEach((n) => {
      if (C.over[n.id]) warns.push('« ' + n.title + ' » distribue plus qu’il ne reçoit.');
      if (n.kind === 'repartiteur' && (C.kept[n.id] || 0) > 0.5) warns.push('« ' + n.title + ' » garde ' + euro(C.kept[n.id]) + ' sans destination.');
    });
    const box = root.querySelector('[data-warns]');
    if (box) box.innerHTML = warns.slice(0, 4).map((t) => '<div class="builder-warn">' + escapeHtml(t) + '</div>').join('');
    const empty = root.querySelector('[data-empty]');
    if (empty) empty.hidden = state.nodes.length > 0;
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
    const stats = nodeStats(n, C);
    const outs = (C.outs[n.id] || []);
    if (propsEmpty) propsEmpty.hidden = true;
    if (!propsForm) return;
    propsForm.hidden = false;
    propsForm.innerHTML = `
      <div class="prop-block">
        <div class="prop-head">
          <span class="dot" style="background:${meta.color}"></span>
          <div>
            <div class="eyebrow">${meta.label}</div>
            <div class="prop-kind">${escapeHtml(n.title)}</div>
          </div>
        </div>
        <label class="prop-field">
          <span>Nom du bloc</span>
          <input data-prop="title" value="${escapeAttr(n.title)}">
        </label>
        ${n.kind === 'revenu' ? `
          <label class="prop-field">
            <span>Montant par mois</span>
            <input data-prop="amount" type="number" min="0" step="1" value="${n.amount}">
          </label>` : ''}
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
            ${stats.rows.map(([k, v]) => `<div class="prop-stat"><span>${k}</span><b>${v}</b></div>`).join('')}
          </div>
        </div>
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
              }).join('') : '<p class="builder-hint">Aucun lien. Cliquez le point droit, puis l’entrée d’un autre bloc.</p>'}
            </div>
          </div>` : ''}
        <button type="button" class="btn btn-ghost" data-del-selected>Supprimer le bloc</button>
      </div>
    `;
  }

  function render(opts = {}) {
    lastCompute = compute();
    renderCanvas();
    renderSide();
    applyTransform();
    syncPayload();
    if (opts.props !== false) renderProps();
  }

  function syncPayload() {
    if (!payloadInput) return;
    payloadInput.value = JSON.stringify({
      horizon: state.horizon,
      nodes: state.nodes.map(({ _w, _h, ...n }) => n),
      edges: state.edges.map(({ _amt, ...e }) => e),
    });
  }

  function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  }
  function escapeAttr(s) {
    return escapeHtml(s);
  }

  function selectNode(id) {
    state.selected = id;
    state.openEdge = null;
    if (id) root.classList.add('is-props-open');
    else root.classList.remove('is-props-open');
    const toggle = root.querySelector('[data-props-toggle]');
    if (toggle) toggle.setAttribute('aria-expanded', id ? 'true' : 'false');
    render();
  }

  function addNode(kind, x, y, extra = {}) {
    const n = normalizeNode({
      id: uid('n'),
      kind,
      title: extra.title || KINDS[kind].label,
      x, y,
      ...extra,
    });
    state.nodes.push(n);
    return n;
  }

  function removeNode(id) {
    state.nodes = state.nodes.filter((n) => n.id !== id);
    state.edges = state.edges.filter((e) => e.from !== id && e.to !== id);
    if (state.selected === id) state.selected = null;
    if (state.connectFrom === id) cancelLink();
  }

  function addEdge(from, to) {
    if (from === to) return;
    if (!nodeById(from) || !nodeById(to)) return;
    if (state.edges.some((e) => e.from === from && e.to === to)) return;
    const already = state.edges.some((e) => e.from === from);
    state.edges.push({
      id: uid('e'),
      from,
      to,
      mode: already ? 'pct' : 'reste',
      value: already ? 25 : 0,
    });
  }

  function dropPosition(clientX, clientY) {
    if (clientX != null && clientY != null) {
      const w = screenToWorld(clientX, clientY);
      return { x: Math.round(w.x - 122), y: Math.round(w.y - 40) };
    }
    const r = canvas.getBoundingClientRect();
    const w = screenToWorld(r.left + r.width / 2, r.top + r.height / 2);
    return { x: Math.round(w.x - 122 + (Math.random() * 40 - 20)), y: Math.round(w.y - 40 + (Math.random() * 40 - 20)) };
  }

  function openPresetModal(kind, x, y) {
    pendingDrop = { kind, x, y };
    if (!modal || !presetList) {
      const n = addNode(kind, x, y);
      selectNode(n.id);
      return;
    }
    const meta = KINDS[kind];
    modal.querySelector('[data-preset-kind]').textContent = meta.label;
    modal.querySelector('#preset-title').textContent = 'Préconfigurer ce ' + meta.label.toLowerCase();
    modal.querySelector('[data-preset-intro]').textContent = kind === 'compte' || kind === 'livret'
      ? 'Choisissez un produit : le taux et le plafond seront remplis automatiquement. Vous pourrez tout ajuster ensuite.'
      : 'Choisissez un modèle, ou partez vierge.';
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
  }

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
  }

  function cancelLink() {
    state.connectFrom = null;
    canvas.classList.remove('is-linking');
    layer.querySelectorAll('.port.is-armed').forEach((p) => p.classList.remove('is-armed'));
    const ghost = svg.querySelector('[data-ghost]');
    if (ghost) ghost.remove();
  }

  let paletteDrag = null;
  let drag = null;
  let pan = null;

  root.querySelectorAll('[data-add]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      if (btn.dataset.didDrag === '1') {
        delete btn.dataset.didDrag;
        e.preventDefault();
        return;
      }
      const kind = btn.getAttribute('data-add');
      const pos = dropPosition();
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
      const g = document.createElement('div');
      g.className = 'node builder-ghost';
      g.innerHTML = `<div class="node-inner"><div class="node-bar" style="background:${KINDS[paletteDrag.kind].color}"></div><div class="node-head"><span class="node-kind" style="color:${KINDS[paletteDrag.kind].color}">${KINDS[paletteDrag.kind].label}</span></div><div class="node-title">${KINDS[paletteDrag.kind].label}</div></div>`;
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
      const pos = dropPosition(e.clientX, e.clientY);
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
    const handle = e.target.closest('[data-drag]');
    if (!handle) return;
    const id = handle.getAttribute('data-drag');
    const node = nodeById(id);
    if (!node) return;
    e.preventDefault();
    e.stopPropagation();
    drag = { type: 'node', id, sx: e.clientX, sy: e.clientY, ox: node.x, oy: node.y, el: handle.closest('.node') };
  });

  canvas.addEventListener('mousedown', (e) => {
    if (e.target.closest('.node, .edge-pill, button, input, select')) return;
    pan = { sx: e.clientX, sy: e.clientY, ox: state.tx, oy: state.ty };
    canvas.classList.add('is-grabbing');
  });

  document.addEventListener('mousemove', (e) => {
    if (drag && drag.type === 'node') {
      const node = nodeById(drag.id);
      if (!node) return;
      node.x = Math.round(drag.ox + (e.clientX - drag.sx) / state.scale);
      node.y = Math.round(drag.oy + (e.clientY - drag.sy) / state.scale);
      if (drag.el) {
        drag.el.style.left = node.x + 'px';
        drag.el.style.top = node.y + 'px';
      }
      lastCompute = lastCompute || compute();
      svg.innerHTML = '';
      if (labels) labels.innerHTML = '';
      state.edges.forEach((ed) => {
        const a = nodeById(ed.from);
        const b = nodeById(ed.to);
        if (!a || !b) return;
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        path.setAttribute('d', curve(portPoint(a, 'out'), portPoint(b, 'in')));
        path.setAttribute('fill', 'none');
        path.setAttribute('stroke', KINDS[a.kind]?.color || '#999');
        path.setAttribute('stroke-width', '1.6');
        svg.appendChild(path);
      });
    } else if (pan) {
      state.tx = pan.ox + (e.clientX - pan.sx);
      state.ty = pan.oy + (e.clientY - pan.sy);
      applyTransform();
    }
    if (state.connectFrom) {
      const a = nodeById(state.connectFrom);
      if (!a) return;
      const w = screenToWorld(e.clientX, e.clientY);
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
      g.setAttribute('d', curve(portPoint(a, 'out'), w));
    }
  });

  document.addEventListener('mouseup', () => {
    if (drag && drag.type === 'node') render({ props: false });
    drag = null;
    pan = null;
    canvas.classList.remove('is-grabbing');
  });

  layer.addEventListener('click', (e) => {
    if (readonly) return;
    const del = e.target.closest('[data-del]');
    if (del) {
      e.stopPropagation();
      removeNode(del.getAttribute('data-del'));
      render();
      return;
    }
    const out = e.target.closest('[data-port-out]');
    if (out) {
      e.stopPropagation();
      state.connectFrom = out.getAttribute('data-port-out');
      canvas.classList.add('is-linking');
      layer.querySelectorAll('.port.is-armed').forEach((p) => p.classList.remove('is-armed'));
      out.classList.add('is-armed');
      return;
    }
    const inn = e.target.closest('[data-port-in]');
    if (inn && state.connectFrom) {
      e.stopPropagation();
      addEdge(state.connectFrom, inn.getAttribute('data-port-in'));
      cancelLink();
      render();
      return;
    }
    const node = e.target.closest('[data-node]');
    if (node) {
      selectNode(node.getAttribute('data-node'));
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
    render();
    propsForm?.querySelector(`[data-edge-edit="${edge.id}"]`)?.scrollIntoView({ block: 'nearest' });
  });

  canvas.addEventListener('click', (e) => {
    if (e.target.closest('.node, .edge-pill, .port, button')) return;
    if (state.connectFrom) { cancelLink(); return; }
    if (state.selected) selectNode(null);
  });

  canvas.addEventListener('wheel', (e) => {
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
    if (prop === 'amount') n.amount = Number(e.target.value) || 0;
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
    syncPayload();
    if (edgeMode) renderProps();
  });

  propsForm?.addEventListener('click', (e) => {
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
  });

  root.querySelector('[data-horizon]')?.addEventListener('input', (e) => {
    const v = parseInt(e.target.value, 10);
    state.horizon = Number.isNaN(v) ? 60 : Math.min(360, Math.max(1, v));
    render({ props: false });
    renderProps();
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

  form?.addEventListener('submit', () => syncPayload());

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (modal && !modal.hidden) { closePresetModal(); return; }
      if (state.connectFrom) { cancelLink(); return; }
      if (state.selected) selectNode(null);
      return;
    }
    if ((e.key === 'Delete' || e.key === 'Backspace') && !readonly && state.selected && !['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
      removeNode(state.selected);
      render();
    }
  });

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
    const pad = 48;
    let k = Math.min(1.2, (r.width - pad * 2) / Math.max(1, x2 - x1), (r.height - pad * 2) / Math.max(1, y2 - y1));
    k = Math.max(0.3, Math.min(1, k));
    state.scale = k;
    state.tx = (r.width - (x2 - x1) * k) / 2 - x1 * k;
    state.ty = (r.height - (y2 - y1) * k) / 2 - y1 * k;
    applyTransform();
  }

  if (nameInput) nameInput.addEventListener('input', syncPayload);
  render();
})();
