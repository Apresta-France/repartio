(() => {
  const root = document.querySelector('[data-builder]');
  if (!root) return;

  const colors = {
    revenu: 'oklch(0.48 0.10 152)',
    compte: 'oklch(0.48 0.10 248)',
    repartiteur: 'oklch(0.48 0.12 300)',
    livret: 'oklch(0.55 0.11 62)',
    depense: 'oklch(0.52 0.14 32)',
  };
  const labels = { revenu: 'Revenu', compte: 'Compte', repartiteur: 'Répartiteur', livret: 'Livret', depense: 'Dépense' };

  const initial = JSON.parse(root.getAttribute('data-payload') || '{}');
  const state = {
    nodes: initial.nodes || [],
    edges: initial.edges || [],
    horizon: initial.horizon || 60,
    scale: 0.85,
    tx: 24,
    ty: 24,
    connectFrom: null,
  };

  const canvas = root.querySelector('[data-canvas]');
  const layer = root.querySelector('[data-layer]');
  const svg = root.querySelector('[data-edges]');
  const nameInput = root.querySelector('[data-name]');
  const payloadInput = root.querySelector('[data-payload-input]');
  const form = root.querySelector('[data-save-form]');

  function uid() {
    return 'n' + Math.random().toString(36).slice(2, 8);
  }

  function totals() {
    let inn = 0, out = 0, saved = 0;
    state.nodes.forEach((n) => {
      const a = Number(n.amount) || 0;
      if (n.kind === 'revenu') inn += a;
      else if (n.kind === 'depense') out += a;
      else if (n.kind === 'livret' || n.kind === 'repartiteur') saved += a;
    });
    return { inn, out, saved, unassigned: Math.max(0, inn - out - saved), proj: saved * state.horizon };
  }

  function euro(n) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(n)) + ' €';
  }

  function applyTransform() {
    layer.style.transform = `translate(${state.tx}px, ${state.ty}px) scale(${state.scale})`;
    const zoom = root.querySelector('[data-zoom]');
    if (zoom) zoom.textContent = Math.round(state.scale * 100) + '%';
  }

  function render() {
    layer.querySelectorAll('.node').forEach((el) => el.remove());
    svg.innerHTML = '';
    state.nodes.forEach((n) => {
      const el = document.createElement('div');
      el.className = 'node';
      el.style.left = n.x + 'px';
      el.style.top = n.y + 'px';
      el.innerHTML = `
        <div class="node-bar" style="background:${colors[n.kind]}"></div>
        <div class="node-head" data-drag="${n.id}">
          <span class="mono" style="font-size:9.5px;letter-spacing:.13em;text-transform:uppercase;color:${colors[n.kind]}">${labels[n.kind]}</span>
          <button type="button" data-del="${n.id}" style="margin-left:auto;border:0;background:none;cursor:pointer;color:#bbb">×</button>
        </div>
        <input data-title="${n.id}" value="${n.title.replace(/"/g, '&quot;')}" style="width:100%;border:0;padding:0 12px 8px;font-weight:600;font-size:14.5px;background:transparent">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 12px 12px;border-top:1px solid oklch(0.95 0.004 95)">
          <span style="font-size:12px;color:#777">Par mois</span>
          <input data-amount="${n.id}" value="${n.amount}" type="number" style="width:90px;font-family:var(--mono);font-size:12px;padding:3px 8px;border:1px solid #eee;border-radius:6px">
        </div>
        <div class="port port-in" data-port-in="${n.id}" style="border:2px solid ${colors[n.kind]}"></div>
        <div class="port port-out" data-port-out="${n.id}" style="background:${colors[n.kind]};border:2px solid #fff"></div>
      `;
      layer.appendChild(el);
    });

    state.edges.forEach((e) => {
      const a = state.nodes.find((n) => n.id === e.from);
      const b = state.nodes.find((n) => n.id === e.to);
      if (!a || !b) return;
      const ax = a.x + 244, ay = a.y + 51, bx = b.x, by = b.y + 51;
      const dx = Math.max(50, Math.abs(bx - ax) * 0.42);
      const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.setAttribute('d', `M ${ax} ${ay} C ${ax + dx} ${ay}, ${bx - dx} ${by}, ${bx} ${by}`);
      path.setAttribute('fill', 'none');
      path.setAttribute('stroke', colors[a.kind] || '#999');
      path.setAttribute('stroke-width', '1.6');
      svg.appendChild(path);
    });

    const t = totals();
    const set = (sel, val) => { const el = root.querySelector(sel); if (el) el.textContent = val; };
    set('[data-stat="in"]', euro(t.inn));
    set('[data-stat="out"]', euro(t.out));
    set('[data-stat="saved"]', euro(t.saved));
    set('[data-stat="unassigned"]', euro(t.unassigned));
    set('[data-stat="proj"]', euro(t.proj));
    applyTransform();
    syncPayload();
  }

  function syncPayload() {
    if (payloadInput) {
      payloadInput.value = JSON.stringify({
        horizon: state.horizon,
        nodes: state.nodes,
        edges: state.edges,
      });
    }
  }

  root.querySelectorAll('[data-add]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const kind = btn.getAttribute('data-add');
      state.nodes.push({
        id: uid(),
        kind,
        title: labels[kind],
        x: 80 + state.nodes.length * 20,
        y: 80 + state.nodes.length * 24,
        amount: 0,
      });
      render();
    });
  });

  let drag = null;
  let pan = null;
  layer.addEventListener('mousedown', (e) => {
    const handle = e.target.closest('[data-drag]');
    if (!handle) return;
    const id = handle.getAttribute('data-drag');
    const node = state.nodes.find((n) => n.id === id);
    if (!node) return;
    e.preventDefault();
    e.stopPropagation();
    drag = { id, sx: e.clientX, sy: e.clientY, ox: node.x, oy: node.y };
  });

  canvas.addEventListener('mousedown', (e) => {
    if (e.target.closest('.node')) return;
    pan = { sx: e.clientX, sy: e.clientY, ox: state.tx, oy: state.ty };
  });

  document.addEventListener('mousemove', (e) => {
    if (drag) {
      const k = state.scale;
      const node = state.nodes.find((n) => n.id === drag.id);
      node.x = drag.ox + (e.clientX - drag.sx) / k;
      node.y = drag.oy + (e.clientY - drag.sy) / k;
      render();
    } else if (pan) {
      state.tx = pan.ox + (e.clientX - pan.sx);
      state.ty = pan.oy + (e.clientY - pan.sy);
      applyTransform();
    }
  });
  document.addEventListener('mouseup', () => { drag = null; pan = null; });

  layer.addEventListener('click', (e) => {
    const del = e.target.closest('[data-del]');
    if (del) {
      const id = del.getAttribute('data-del');
      state.nodes = state.nodes.filter((n) => n.id !== id);
      state.edges = state.edges.filter((ed) => ed.from !== id && ed.to !== id);
      render();
      return;
    }
    const out = e.target.closest('[data-port-out]');
    if (out) { state.connectFrom = out.getAttribute('data-port-out'); return; }
    const inn = e.target.closest('[data-port-in]');
    if (inn && state.connectFrom) {
      const to = inn.getAttribute('data-port-in');
      if (to !== state.connectFrom) {
        state.edges.push({ from: state.connectFrom, to, amount: 0 });
      }
      state.connectFrom = null;
      render();
    }
  });

  layer.addEventListener('input', (e) => {
    const title = e.target.getAttribute('data-title');
    const amount = e.target.getAttribute('data-amount');
    if (title) {
      const n = state.nodes.find((x) => x.id === title);
      if (n) n.title = e.target.value;
    }
    if (amount) {
      const n = state.nodes.find((x) => x.id === amount);
      if (n) n.amount = Number(e.target.value) || 0;
      render();
    }
    syncPayload();
  });

  root.querySelector('[data-zoom-in]')?.addEventListener('click', () => { state.scale = Math.min(1.6, state.scale * 1.15); applyTransform(); });
  root.querySelector('[data-zoom-out]')?.addEventListener('click', () => { state.scale = Math.max(0.25, state.scale / 1.15); applyTransform(); });
  root.querySelector('[data-fit]')?.addEventListener('click', () => { state.scale = 0.85; state.tx = 24; state.ty = 24; applyTransform(); });
  root.querySelector('[data-clear]')?.addEventListener('click', () => {
    if (confirm('Vider le canvas ?')) { state.nodes = []; state.edges = []; render(); }
  });

  form?.addEventListener('submit', () => syncPayload());
  render();
})();
