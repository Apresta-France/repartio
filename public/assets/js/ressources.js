(() => {
  const euro = (n) => `${new Intl.NumberFormat('fr-FR').format(Math.round(n))} €`;
  const monthsLabel = (n) => {
    if (!Number.isFinite(n)) return '—';
    if (n <= 0) return 'déjà';
    if (n > 240) return '+ de 20 ans';
    const m = Math.ceil(n);
    return `${m} mois`;
  };
  const clamp = (n, a, b) => Math.min(b, Math.max(a, n));
  const num = (el) => (el ? Number(el.value) : 0);

  const api = (root) => {
    const out = (key) => root.querySelector(`[data-out="${key}"]`);
    const inn = (key) => root.querySelector(`[data-in="${key}"]`);
    const set = (key, value, warn = false) => {
      const el = out(key);
      if (!el) return;
      el.textContent = value;
      el.classList.toggle('is-warn', warn);
    };
    const html = (key, value) => {
      const el = out(key);
      if (el) el.innerHTML = value;
    };
    const onChange = (fn) => {
      root.querySelectorAll('input, select, button.chip, button[data-step], button[data-rate], button[data-livret], button[data-log]').forEach((el) => {
        el.addEventListener('input', fn);
        el.addEventListener('change', fn);
        if (el.tagName === 'BUTTON') el.addEventListener('click', fn);
      });
      fn();
    };
    return { out, inn, set, html, onChange };
  };

  const LIVRETS = {
    la: { name: 'Livret A', rate: 1.7, cap: 22950 },
    ldds: { name: 'LDDS', rate: 1.7, cap: 12000 },
    lep: { name: 'LEP', rate: 2.5, cap: 10000 },
    jeune: { name: 'Livret jeune', rate: 1.7, cap: 1600 },
    cel: { name: 'CEL', rate: 1.25, cap: 15300 },
    pel: { name: 'PEL 2026', rate: 2.0, cap: 61200 },
  };

  const REGIMES = {
    vente: { label: 'Vente', social: 12.3, cfp: 0.1, vl: 1.0, abatt: 71, tva: 85000, tvaMaj: 93500, micro: 203100 },
    bic: { label: 'Services BIC', social: 21.2, cfp: 0.2, vl: 1.7, abatt: 50, tva: 37500, tvaMaj: 41250, micro: 83600 },
    bnc: { label: 'BNC', social: 25.6, cfp: 0.2, vl: 2.2, abatt: 34, tva: 37500, tvaMaj: 41250, micro: 83600 },
    cipav: { label: 'CIPAV', social: 23.2, cfp: 0.2, vl: 2.2, abatt: 34, tva: 37500, tvaMaj: 41250, micro: 83600 },
  };

  const pct = (n) => `${n.toFixed(1).replace('.', ',')} %`;
  const pct2 = (n) => `${n.toFixed(2).replace('.', ',')} %`;

  const fillMonths = (start, cap, monthly) => {
    const room = cap - start;
    if (room <= 0) return 0;
    if (monthly <= 0) return Infinity;
    return room / monthly;
  };

  const projectBooks = (monthly, months, books) => {
    const bals = books.map((b) => b.start);
    let cash = 0;
    for (let m = 1; m <= months; m += 1) {
      let pay = monthly;
      books.forEach((b, i) => {
        const room = Math.max(0, b.cap - bals[i]);
        const add = Math.min(room, pay);
        bals[i] += add;
        pay -= add;
      });
      cash += pay;
      if (m % 12 === 0) {
        books.forEach((b, i) => {
          bals[i] = Math.min(b.cap, bals[i] * (1 + b.rate / 100));
        });
      }
    }
    return bals.reduce((sum, n) => sum + n, 0) + cash;
  };

  const couple = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const ae = num(inn('ae'));
      const provision = 380;
      const factures = 1260;
      const quotidien = 1560;
      const autres = 1860;
      const net = ae - provision;
      const persoA = net + autres;
      const save = persoA - factures - quotidien;
      set('ae', euro(ae));
      set('net', euro(Math.max(0, net)));
      set('save', euro(Math.max(0, save)), save < 150);
      let cut = 'Rien — circuit tenu';
      if (ae < provision) cut = 'Le compte pro ne couvre plus l’URSSAF';
      else if (save <= 0) cut = 'Le répartiteur A s’arrête';
      else if (save < 150) cut = 'Épargne A presque à sec';
      else if (save < 300) cut = 'Épargne A réduite';
      set('cut', cut, save < 300 || ae < provision);
      if (root.querySelector('[data-out="flow"]')) {
        const fact = clamp((factures / Math.max(persoA, 1)) * 100, 0, 100);
        const quot = clamp((quotidien / Math.max(persoA, 1)) * 100, 0, 100);
        const rest = clamp(100 - fact - quot, 0, 100);
        html('flow', [
          ['Factures', fact, euro(Math.min(factures, Math.max(0, persoA)))],
          ['Quotidien', quot, euro(Math.min(quotidien, Math.max(0, persoA - factures)))],
          ['Épargne A', rest, euro(Math.max(0, save))],
        ].map(([label, pct, val]) => `
          <div class="lab-flow-row">
            <span>${label}</span>
            <i><em style="width:${pct}%"></em></i>
            <b class="mono">${val}</b>
          </div>
        `).join(''));
      }
      set('note', ae >= 1800
        ? 'À 1 800 €, le compte A verse 1 260 € vers Factures, 1 560 € vers Quotidien, et tout le reste vers son épargne.'
        : `À ${euro(ae)} de CA, l’URSSAF reste à 380 € (fixe du circuit). C’est l’épargne personnelle qui encaisse l’écart en premier.`);
    });
  };

  const tableur = (root) => {
    const { html, set } = api(root);
    const steps = [
      {
        sheet: [['Salaire', '2 400 €'], ['Loyer', '− 890 €'], ['Courses', '− 420 €'], ['Reste visible', '1 090 €']],
        nodes: [['Revenu', 'Salaire 2 400 €'], ['Compte', 'Courant'], ['Dépense', 'Loyer + courses'], ['Livret', 'Le reste']],
        note: 'Un salaire, un compte : tableur et circuit racontent encore la même chose.',
      },
      {
        sheet: [['Salaire', '2 400 €'], ['AE (CA)', '3 200 €'], ['Virements internes', '− ?'], ['Reste', 'illisible']],
        nodes: [['Revenu', 'Salaire'], ['Revenu', 'Auto-entreprise'], ['Compte', 'Perso + pro'], ['Dépense', 'URSSAF à câbler']],
        note: 'Le tableur additionne deux entrées. Il ne dit plus quel compte encaisse l’AE, ni ce qui part à l’URSSAF.',
        bad: true,
      },
      {
        sheet: [['Joint unique', '4 100 €'], ['Prélèvements', 'mélangés'], ['Quotidien', 'mélangé'], ['Qui a trop pris ?', 'inconnu']],
        nodes: [['Compte', 'Joint Factures'], ['Compte', 'Joint Quotidien'], ['Fil', 'Fixes exacts'], ['Fil', 'Enveloppe quotidienne']],
        note: 'Deux joints, deux natures. Le tableur n’a plus qu’une colonne « banque » : l’arbitrage revient tous les 30 du mois.',
        bad: true,
      },
      {
        sheet: [['Livret enfant', '55 €'], ['Colonne masquée', '#REF!'], ['Solde joint', 'faux ?'], ['Chemin', 'perdu']],
        nodes: [['Compte', 'Compte B'], ['Livret', 'Jeune A · 55 €'], ['Livret', 'Jeune B · 55 €'], ['Répartiteur', 'Le reste']],
        note: 'Au quatrième compte, la feuille cache des formules. Le circuit nomme encore chaque euro.',
        bad: true,
      },
    ];
    const paint = (index) => {
      const step = steps[index];
      root.querySelectorAll('[data-step]').forEach((btn) => btn.classList.toggle('active', Number(btn.dataset.step) === index));
      html('sheet', step.sheet.map(([k, v]) => {
        const bad = Boolean(step.bad && (v.includes('?') || v.includes('#') || v === 'illisible' || v === 'inconnu' || v === 'perdu' || v === 'mélangés' || v === 'mélangé' || v === 'faux ?'));
        return `<div><span>${k}</span><b class="mono${bad ? ' is-bad' : ''}">${v}</b></div>`;
      }).join(''));
      html('nodes', step.nodes.map(([k, v]) => `<div><span class="eyebrow" style="color:var(--teal-ink)">${k}</span><span>${v}</span></div>`).join(''));
      set('note', step.note);
    };
    root.querySelectorAll('[data-step]').forEach((btn) => {
      btn.addEventListener('click', () => paint(Number(btn.dataset.step)));
    });
    paint(0);
  };

  const ordre = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const monthly = num(inn('save'));
      const lepOn = inn('lep')?.checked ?? true;
      set('save', euro(monthly));
      const books = [];
      if (lepOn) books.push({ id: 'lep', name: 'LEP', cap: 10000, start: 1800, rate: 2.5, key: 'lep-m' });
      books.push({ id: 'ldds', name: 'LDDS', cap: 12000, start: 2000, rate: 1.7, key: 'ldds-m' });
      books.push({ id: 'la', name: 'Livret A', cap: 22950, start: 4000, rate: 1.7, key: 'la-m' });

      const filled = Object.fromEntries(books.map((b) => [b.id, null]));
      const bals = Object.fromEntries(books.map((b) => [b.id, b.start]));
      for (let m = 1; m <= 240; m += 1) {
        let pay = monthly;
        books.forEach((b) => {
          if (bals[b.id] >= b.cap) return;
          const add = Math.min(b.cap - bals[b.id], pay);
          bals[b.id] += add;
          pay -= add;
          if (bals[b.id] >= b.cap && filled[b.id] === null) filled[b.id] = m;
        });
        if (pay === monthly) break;
      }

      html('stack', books.map((b) => {
        const pct = clamp((bals[b.id] / b.cap) * 100, 0, 100);
        return `<div class="lab-stack-row">
          <span>${b.name}<b>${euro(Math.min(bals[b.id], b.cap))} / ${euro(b.cap)}</b></span>
          <i><em style="width:${pct}%;background:${b.id === 'lep' ? 'var(--teal)' : 'var(--blue)'}"></em></i>
        </div>`;
      }).join(''));

      set('lep-m', lepOn ? monthsLabel(filled.lep ?? Infinity) : 'non ouvert');
      set('ldds-m', monthsLabel(filled.ldds ?? Infinity));
      set('la-m', monthsLabel(filled.la ?? Infinity));
      const last = filled.la;
      set('note', last
        ? `Avec ${euro(monthly)} / mois, le dernier livret de la file sature au mois ${last}. Pensez au fil de débordement dès aujourd’hui.`
        : `À ${euro(monthly)} / mois, la file ne sature pas en 20 ans. Le débordement peut attendre, l’ordre reste le même.`);
    });
  };

  const joints = (root) => {
    const { inn, set, out, onChange } = api(root);
    const width = (el, pct, color) => {
      if (!el) return;
      el.style.width = `${clamp(pct, 0, 100)}%`;
      if (color) el.style.background = color;
    };
    onChange(() => {
      const bills = num(inn('bills'));
      const daily = num(inn('daily'));
      const total = bills + daily;
      set('bills', euro(bills));
      set('daily', euro(daily));
      width(out('mix-bills'), (bills / total) * 100, 'var(--red)');
      width(out('mix-daily'), (daily / total) * 100, 'var(--orange)');
      width(out('sep-bills'), 100, 'var(--red)');
      width(out('sep-daily'), 100, 'var(--orange)');
      set('mix-note', `Un seul pot de ${euro(total)} : chaque weekend grignote les factures, chaque facture surprise grignote le quotidien. Un arbitrage par mois, au minimum.`);
      set('sep-note', `Factures = ${euro(bills)} pile. Quotidien = ${euro(daily)} d’enveloppe. Si les deux tiennent, le mois est réussi — sans tableur.`);
    });
  };

  const urssaf = (root) => {
    const { inn, set, out, html, onChange } = api(root);
    let regime = 'bic';
    let acre = 0;
    root.querySelectorAll('[data-regime]').forEach((btn) => {
      btn.addEventListener('click', () => {
        regime = btn.dataset.regime;
        root.querySelectorAll('[data-regime]').forEach((el) => el.classList.toggle('active', el === btn));
      });
    });
    root.querySelectorAll('[data-acre]').forEach((btn) => {
      btn.addEventListener('click', () => {
        acre = Number(btn.dataset.acre);
        root.querySelectorAll('[data-acre]').forEach((el) => el.classList.toggle('active', el === btn));
      });
    });
    onChange(() => {
      const spec = REGIMES[regime];
      const ca = num(inn('ca'));
      const social = spec.social * (acre ? (1 - acre / 100) : 1);
      const useCfp = inn('cfp')?.checked;
      const artisan = inn('artisan')?.checked;
      const cfp = useCfp ? (artisan ? 0.3 : spec.cfp) : 0;
      const vl = inn('vl')?.checked ? spec.vl : 0;
      const rate = social + cfp + vl;
      const tax = ca * (rate / 100);
      const net = ca - tax;
      const annual = ca * 12;
      set('ca', euro(ca));
      set('rate', pct2(rate));
      set('month', euro(tax));
      set('net', euro(net));
      const bar = out('tax-bar');
      if (bar) bar.style.width = `${clamp(rate, 0, 100)}%`;
      html('break', `
        <div class="lab-col-title">Décomposition</div>
        <div><span>Cotisations sociales</span><b class="mono">${pct2(social)}</b></div>
        <div><span>CFP</span><b class="mono">${useCfp ? pct2(cfp) : 'off'}</b></div>
        <div><span>Versement libératoire</span><b class="mono">${vl ? pct(vl) : 'off'}</b></div>
        <div><span>Facture / trimestre</span><b class="mono">${euro(tax * 3)}</b></div>
      `);
      const tvaLeft = spec.tva - annual;
      const microLeft = spec.micro - annual;
      html('year', `
        <div class="lab-col-title">Sur 12 mois</div>
        <div><span>CA annuel</span><b class="mono">${euro(annual)}</b></div>
        <div><span>Franchise TVA ${euro(spec.tva)}</span><b class="mono${tvaLeft < 0 ? ' is-bad' : ''}">${tvaLeft >= 0 ? euro(tvaLeft) + ' restants' : 'dépassée'}</b></div>
        <div><span>Plafond micro ${euro(spec.micro)}</span><b class="mono${microLeft < 0 ? ' is-bad' : ''}">${microLeft >= 0 ? euro(microLeft) + ' restants' : 'dépassé'}</b></div>
      `);
      set('note', acre
        ? `ACRE ${acre} % : le taux social passe à ${pct2(social)}. La CFP et le libératoire ne sont pas réduits. Servez ce total depuis le compte pro, puis « tout le reste » vers le perso.`
        : 'Hors ACRE. Servez ce total depuis le compte pro, puis un fil « tout le reste » vers le perso — jamais un pourcentage du CA brut.');
    });
  };

  const plafond = (root) => {
    const { inn, set, html, onChange } = api(root);
    let key = 'la';
    const applyCap = () => {
      const cap = LIVRETS[key].cap;
      const start = inn('start');
      if (start) {
        start.max = String(cap);
        if (Number(start.value) > cap) start.value = String(cap);
      }
    };
    root.querySelectorAll('[data-livret]').forEach((btn) => {
      btn.addEventListener('click', () => {
        key = btn.dataset.livret;
        root.querySelectorAll('[data-livret]').forEach((el) => el.classList.toggle('active', el === btn));
        applyCap();
      });
    });
    onChange(() => {
      applyCap();
      const book = LIVRETS[key];
      const start = clamp(num(inn('start')), 0, book.cap);
      const pay = num(inn('pay'));
      set('start', euro(start));
      set('pay', euro(pay));
      const when = fillMonths(start, book.cap, pay);
      set('when', monthsLabel(when));
      set('overflow', when === 0 ? euro(pay) : (when < Infinity ? `${euro(pay)} / mois après` : 'aucun à 20 ans'));
      const bars = [];
      let bal = start;
      let overflow = 0;
      for (let m = 1; m <= 60; m += 1) {
        bal += pay;
        if (bal > book.cap) {
          overflow += bal - book.cap;
          bal = book.cap;
        }
        if (m % 12 === 0) bal = Math.min(book.cap, bal * (1 + book.rate / 100));
        const pct = clamp((bal / book.cap) * 100, 2, 100);
        const klass = bal >= book.cap ? 'is-full' : '';
        bars.push(`<i class="${klass}" style="height:${pct}%"></i>`);
      }
      html('chart', bars.join(''));
      const interest = book.cap * (book.rate / 100) * Math.max(0, (60 - Math.ceil(when)) / 12);
      set('interest', when < 60 ? euro(interest) : euro(0));
    });
  };

  const mix = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const shock = inn('shock')?.checked;
      const income = num(inn('income')) * (shock ? 0.8 : 1);
      const fixed = num(inn('fixed'));
      const save = num(inn('save'));
      set('income', euro(num(inn('income'))) + (shock ? ' → ' + euro(income) : ''));
      set('fixed', euro(fixed));
      set('save', euro(save));
      const leftFix = income - fixed - save;
      const pctSave = income > fixed ? (income - fixed) * (save / Math.max(num(inn('income')) - num(inn('fixed')), 1)) : 0;
      const leftPct = income - fixed - pctSave;
      const col = (title, rows, verdict, bad) => `
        <div class="lab-col-title">${title}</div>
        ${rows.map(([k, v, warn]) => `<div><span>${k}</span><b class="mono${warn ? ' is-bad' : ''}">${v}</b></div>`).join('')}
        <div><span>Lecture</span><b class="mono${bad ? ' is-bad' : ''}">${verdict}</b></div>
      `;
      html('col-fix', col('Tout en fixe', [
        ['Revenu', euro(income)],
        ['Factures', euro(fixed)],
        ['Épargne fixe', euro(save), leftFix < 0],
        ['Reste', euro(leftFix), leftFix < 0],
      ], leftFix < 0 ? `Il manque ${euro(-leftFix)}` : 'Le mois tient', leftFix < 0));
      html('col-pct', col('Épargne en % du reste', [
        ['Revenu', euro(income)],
        ['Factures', euro(fixed)],
        ['Épargne variable', euro(pctSave)],
        ['Reste quotidien', euro(Math.max(0, leftPct))],
      ], leftPct < 0 ? 'Factures > revenu' : 'L’épargne fond, le mois tient', leftPct < 0));
    });
  };

  const reste = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const raise = num(inn('raise'));
      set('raise', euro(raise));
      const salary = 2800 + raise;
      const leftOver = salary - 2000;
      html('left', `
        <div class="lab-col-title">Trois fils fixes</div>
        <div><span>Salaire</span><b class="mono">${euro(salary)}</b></div>
        <div><span>→ Factures</span><b class="mono">900 €</b></div>
        <div><span>→ Quotidien</span><b class="mono">700 €</b></div>
        <div><span>→ Épargne</span><b class="mono">400 €</b></div>
        <div><span>Non affecté</span><b class="mono is-bad">${euro(leftOver)}</b></div>
      `);
      html('right', `
        <div class="lab-col-title">Un fil « tout le reste »</div>
        <div><span>Salaire</span><b class="mono">${euro(salary)}</b></div>
        <div><span>→ Factures</span><b class="mono">900 €</b></div>
        <div><span>→ Quotidien</span><b class="mono">700 €</b></div>
        <div><span>→ Épargne (reste)</span><b class="mono">${euro(salary - 1600)}</b></div>
        <div><span>Non affecté</span><b class="mono">${euro(0)}</b></div>
      `);
    });
  };

  const famille = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const pot = num(inn('pot'));
      const kids = num(inn('kids'));
      const buffer = num(inn('buffer'));
      const target = num(inn('target'));
      const kidsTotal = kids * 2;
      const apport = Math.max(0, pot - kidsTotal - buffer);
      set('pot', euro(pot));
      set('kids', euro(kids));
      set('buffer', euro(buffer));
      set('target', euro(target));
      set('apport', euro(apport), apport === 0);
      set('when', apport > 0 ? monthsLabel(target / apport) : 'jamais');
      set('kids-when', kids > 0 ? monthsLabel((1600 - 400) / kids) : '—');
      const parts = [
        ['Enfants × 2', kidsTotal, 'var(--navy)'],
        ['Précaution', buffer, 'var(--teal)'],
        ['Apport', apport, 'var(--orange)'],
      ];
      html('stack', parts.map(([name, val, color]) => {
        const pct = pot > 0 ? clamp((val / pot) * 100, 0, 100) : 0;
        return `<div class="lab-stack-row"><span>${name}<b>${euro(val)}</b></span><i><em style="width:${pct}%;background:${color}"></em></i></div>`;
      }).join(''));
    });
  };

  const scenarios = (root) => {
    const { inn, set, out, onChange } = api(root);
    onChange(() => {
      const pay = num(inn('pay'));
      const months = num(inn('months'));
      set('pay', euro(pay));
      set('months', `${months} mois`);
      const a = projectBooks(pay, months, [
        { start: 0, cap: 10000, rate: 2.5 },
        { start: 0, cap: 22950, rate: 1.7 },
      ]);
      const b = projectBooks(pay, months, [
        { start: 0, cap: 22950, rate: 1.7 },
      ]);
      set('a-tot', euro(a));
      set('b-tot', euro(b));
      const max = Math.max(a, b, 1);
      const aBar = out('a-bar');
      const bBar = out('b-bar');
      if (aBar) aBar.style.width = `${(a / max) * 100}%`;
      if (bBar) bBar.style.width = `${(b / max) * 100}%`;
      const delta = a - b;
      set('note', delta > 0
        ? `À ${months} mois, passer par le LEP d’abord laisse ${euro(delta)} de plus. L’écart se referme une fois les deux plafonds saturés : le surplus devient du cash à 0 %.`
        : `À ${months} mois, les deux variantes sont équivalentes : les plafonds ont déjà tout absorbé.`);
    });
  };

  const taux = (root) => {
    const { inn, set, html, onChange } = api(root);
    let key = 'la';
    const paint = () => {
      const book = LIVRETS[key];
      const startEl = inn('start');
      if (startEl) {
        startEl.max = String(book.cap);
        if (Number(startEl.value) > book.cap) startEl.value = String(book.cap);
      }
      const start = clamp(num(startEl), 0, book.cap);
      const pay = num(inn('pay'));
      set('start', euro(start));
      set('pay', euro(pay));
      set('room', euro(Math.max(0, book.cap - start)));
      set('when', monthsLabel(fillMonths(start, book.cap, pay)));
      set('yield', euro(book.cap * (book.rate / 100)));
    };
    const cards = () => {
      html('cards', Object.entries(LIVRETS).map(([id, book]) => `
        <button type="button" class="lab-livret${id === key ? ' is-on' : ''}" data-pick="${id}">
          <strong>${book.name}</strong>
          <span>${book.rate.toFixed(2).replace('.', ',')} % · ${euro(book.cap)}</span>
        </button>
      `).join(''));
      root.querySelectorAll('[data-pick]').forEach((btn) => {
        btn.addEventListener('click', () => {
          key = btn.dataset.pick;
          cards();
          paint();
        });
      });
    };
    cards();
    onChange(paint);
  };

  const repart = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const input = num(inn('input'));
      const p1 = num(inn('p1'));
      const p2 = num(inn('p2'));
      const p3 = num(inn('p3'));
      const sum = p1 + p2 + p3;
      set('input', euro(input));
      set('p1', `${p1} %`);
      set('p2', `${p2} %`);
      set('p3', `${p3} %`);
      set('sum', `${sum} %`, sum !== 100);
      set('a1', euro(input * p1 / 100));
      set('a3', euro(input * p3 / 100));
      html('bar', [
        [p1, 'var(--teal)'],
        [p2, 'var(--blue)'],
        [p3, 'var(--orange)'],
      ].map(([pct, color]) => `<i style="width:${Math.max(0, pct)}%;background:${color}"></i>`).join(''));
      if (sum === 100) set('note', 'Le répartiteur est rond : tout ce qui entre ressort. Câblez encore un débordement pour le jour où un livret saturera.');
      else if (sum < 100) set('note', `Il manque ${100 - sum} %. Ce n’est pas une réserve : c’est de l’argent non affecté.`);
      else set('note', `Les parts promettent ${sum - 100} % de trop. Un fil ne sera pas servi en entier.`);
    });
  };

  const migrate = (root) => {
    const { html, set } = api(root);
    const steps = [
      { t: 'Poser les revenus', d: 'Une ligne du tableur = un bloc. Moyenne lissée, pas le mois exceptionnel.', v: 4200 },
      { t: 'Créer les comptes', d: 'Perso, joint, pro. Une banque n’est pas une catégorie : c’est un nœud.', v: 0 },
      { t: 'Câbler les dépenses', d: 'Factures en fixe, quotidien en enveloppe. Pas trente lignes de tickets.', v: 2800 },
      { t: 'Le reste vers l’épargne', d: 'Un fil « tout le reste ». Le compteur doit tomber à zéro.', v: 1400 },
    ];
    const paint = () => {
      const checks = [...root.querySelectorAll('[data-pass]')].map((el) => el.checked);
      const done = checks.filter(Boolean).length;
      const assigned = steps.reduce((sum, step, i) => sum + (checks[i] ? step.v : 0), 0);
      set('left', euro(4200 - assigned), assigned < 4200);
      set('done', `${done} / 4`);
      set('time', done === 4 ? 'terminé' : `${Math.max(5, 20 - done * 5)} min`);
      html('steps', steps.map((step, i) => `
        <label class="lab-step${checks[i] ? ' is-on' : ''}">
          <input type="checkbox" data-pass ${checks[i] ? 'checked' : ''}>
          <span><strong>${i + 1}. ${step.t}</strong><span>${step.d}</span></span>
        </label>
      `).join(''));
      root.querySelectorAll('[data-pass]').forEach((el) => el.addEventListener('change', paint));
    };
    html('steps', steps.map((step, i) => `
      <label class="lab-step">
        <input type="checkbox" data-pass>
        <span><strong>${i + 1}. ${step.t}</strong><span>${step.d}</span></span>
      </label>
    `).join(''));
    paint();
  };

  const chipGroup = (root, attr, initial, onPick) => {
    let value = initial;
    root.querySelectorAll(`[${attr}]`).forEach((btn) => {
      btn.addEventListener('click', () => {
        value = btn.getAttribute(attr);
        root.querySelectorAll(`[${attr}]`).forEach((el) => el.classList.toggle('active', el === btn));
        onPick(value);
      });
    });
    return () => value;
  };

  const plafonds = (root) => {
    const { inn, set, html, onChange } = api(root);
    let act = 'services';
    const current = chipGroup(root, 'data-act', 'services', (v) => { act = v; });
    onChange(() => {
      act = current();
      const spec = act === 'vente' ? REGIMES.vente : REGIMES.bic;
      const month = num(inn('month'));
      const year = month * 12;
      set('month', euro(month));
      set('year', euro(year));
      const tvaOk = year <= spec.tva;
      const tvaMaj = year <= spec.tvaMaj;
      const microOk = year <= spec.micro;
      set('tva', tvaOk ? 'franchise' : (tvaMaj ? 'seuil majoré' : 'redevable'), !tvaOk);
      set('micro', microOk ? 'sous le plafond' : 'dépassé', !microOk);
      const bars = [
        ['Franchise TVA', year, spec.tva, 'var(--orange)'],
        ['Seuil TVA majoré', year, spec.tvaMaj, 'var(--red)'],
        ['Plafond micro', year, spec.micro, 'var(--teal)'],
      ];
      html('stack', bars.map(([name, val, cap, color]) => {
        const pctBar = clamp((val / cap) * 100, 0, 100);
        return `<div class="lab-stack-row"><span>${name}<b>${euro(val)} / ${euro(cap)}</b></span><i><em style="width:${pctBar}%;background:${color}"></em></i></div>`;
      }).join(''));
      if (!microOk) set('note', `À ${euro(month)} / mois, le plafond micro est dépassé. Deux années de suite, le régime réel s’applique au 1er janvier suivant.`);
      else if (!tvaOk) set('note', `Encore en micro (${euro(spec.micro - year)} de marge), mais au-dessus de la franchise TVA. Les factures devront porter la TVA — le net câblable n’est plus le brut encaissé.`);
      else set('note', `Sous les deux seuils. Il reste ${euro(spec.tva - year)} avant la franchise TVA, ${euro(spec.micro - year)} avant le plafond micro.`);
    });
  };

  const liberatoire = (root) => {
    const { inn, set, html, onChange } = api(root);
    let regime = 'bic';
    let tmi = 11;
    chipGroup(root, 'data-regime', 'bic', (v) => { regime = v; });
    chipGroup(root, 'data-tmi', '11', (v) => { tmi = Number(v); });
    onChange(() => {
      const spec = REGIMES[regime];
      const ca = num(inn('ca'));
      set('ca', euro(ca));
      const social = spec.social + spec.cfp;
      const withVl = ca * ((social + spec.vl) / 100);
      const withoutSocial = ca * (social / 100);
      const taxable = ca * (1 - spec.abatt / 100);
      const ir = taxable * (tmi / 100);
      const withoutTotal = withoutSocial + ir;
      const cheaper = withVl <= withoutTotal;
      html('with', `
        <div class="lab-col-title">Avec libératoire</div>
        <div><span>Urssaf + CFP</span><b class="mono">${euro(ca * social / 100)}</b></div>
        <div><span>IR libératoire ${pct(spec.vl)}</span><b class="mono">${euro(ca * spec.vl / 100)}</b></div>
        <div><span>Prélevé / mois</span><b class="mono">${euro(withVl)}</b></div>
        <div><span>Net câblable</span><b class="mono">${euro(ca - withVl)}</b></div>
      `);
      html('without', `
        <div class="lab-col-title">Sans · IR à part</div>
        <div><span>Urssaf + CFP</span><b class="mono">${euro(withoutSocial)}</b></div>
        <div><span>IR estimé (TMI ${tmi} %)</span><b class="mono">${euro(ir)}</b></div>
        <div><span>Total / mois</span><b class="mono">${euro(withoutTotal)}</b></div>
        <div><span>Net après IR</span><b class="mono">${euro(ca - withoutTotal)}</b></div>
      `);
      if (tmi === 0) set('note', 'À TMI 0 %, le libératoire fait payer un impôt que le foyer ne doit pas. Gardez le taux social seul, et ne posez pas de provision IR.');
      else set('note', cheaper
        ? `À TMI ${tmi} %, le libératoire coûte ${euro(withoutTotal - withVl)} de moins par mois. Un seul bloc suffit : social + CFP + ${pct(spec.vl)}.`
        : `À TMI ${tmi} %, le barème classique coûte ${euro(withVl - withoutTotal)} de moins. Deux blocs : Urssaf, puis une provision IR de ${euro(ir)}.`);
    });
  };

  const irregulier = (root) => {
    const { inn, set, html, onChange } = api(root);
    let pattern = 'flat';
    chipGroup(root, 'data-pattern', 'flat', (v) => { pattern = v; });
    onChange(() => {
      const peak = num(inn('peak'));
      set('peak', euro(peak));
      const months = [];
      for (let i = 0; i < 12; i += 1) {
        if (pattern === 'flat') months.push(peak);
        else if (pattern === 'season') months.push(i >= 3 && i <= 8 ? peak : 0);
        else months.push(i % 2 === 0 ? peak : Math.round(peak * 0.25));
      }
      const total = months.reduce((s, n) => s + n, 0);
      const avg = total / 12;
      const prov = avg * 0.212;
      set('total', euro(total));
      set('avg', euro(avg));
      set('prov', euro(prov));
      const max = Math.max(...months, 1);
      html('year', months.map((v, i) => {
        const labels = ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'];
        const h = clamp((v / max) * 100, v > 0 ? 8 : 2, 100);
        return `<i style="height:${h}%" title="${labels[i]} : ${euro(v)}"></i>`;
      }).join(''));
      if (pattern === 'flat') set('note', `Douze mois à ${euro(peak)}. La provision à 21,2 % (BIC) est ${euro(prov)} chaque mois — le trimestre fait ${euro(prov * 3)}.`);
      else set('note', `CA annuel ${euro(total)}, moyenne ${euro(avg)}. Si vous provisionnez seulement les mois chargés, il faudra sortir ${euro(prov * 3)} d’un coup au trimestre. Le compte pro doit déjà porter ce matelas.`);
    });
  };

  const lep = (root) => {
    const { inn, set, onChange } = api(root);
    let parts = 1;
    chipGroup(root, 'data-parts', '1', (v) => { parts = Number(v); });
    onChange(() => {
      const rfr = num(inn('rfr'));
      set('rfr', euro(rfr));
      const cap = Math.round(23028 + (parts - 1) * 12298);
      set('cap', euro(cap));
      const ok = rfr <= cap;
      set('ok', ok ? 'Éligible' : 'Hors plafond', !ok);
      const room = cap - rfr;
      set('note', ok
        ? `Marge de ${euro(room)}. Saturez d’abord le LEP (2,50 % · 10 000 €), puis le LDDS, puis le Livret A.`
        : `Dépassement de ${euro(-room)}. Un seul avis au-dessus : le LEP déjà ouvert peut rester un an. Deux années de suite : clôture.`);
    });
  };

  const matelas = (root) => {
    const { inn, set, out, onChange } = api(root);
    onChange(() => {
      const bills = num(inn('bills'));
      const horizon = num(inn('horizon'));
      const have = num(inn('have'));
      const pay = num(inn('pay'));
      const target = bills * horizon;
      const gap = Math.max(0, target - have);
      set('bills', euro(bills));
      set('horizon', `${horizon} mois`);
      set('have', euro(have));
      set('pay', euro(pay));
      set('target', euro(target));
      set('gap', euro(gap), gap > 0);
      set('when', gap === 0 ? 'déjà' : monthsLabel(gap / pay));
      const fill = out('fill');
      if (fill) fill.style.width = `${clamp((have / Math.max(target, 1)) * 100, 0, 100)}%`;
      set('note', gap === 0
        ? 'La cible est atteinte. Le fil fixe vers ce livret peut basculer vers l’objectif suivant — apport, projet, ou « tout le reste ».'
        : `À ${euro(pay)} / mois, la cible de ${horizon} mois de charges est tenue dans ${monthsLabel(gap / pay)}. Tant que ce livret n’est pas là, l’épargne-projet attend.`);
    });
  };

  const mixte = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const salary = num(inn('salary'));
      const ae = num(inn('ae'));
      const bills = num(inn('bills'));
      const urssafTax = ae * 0.212;
      const netAe = ae - urssafTax;
      const total = salary + netAe;
      const save = total - bills;
      const withoutAe = salary - bills;
      set('salary', euro(salary));
      set('ae', euro(ae));
      set('bills', euro(bills));
      set('net', euro(Math.max(0, netAe)));
      set('save', euro(Math.max(0, save)), save < 0);
      set('cut', withoutAe >= 0 ? 'Factures tenues' : `Il manque ${euro(-withoutAe)}`, withoutAe < 0);
      const billPct = clamp((bills / Math.max(total, 1)) * 100, 0, 100);
      const savePct = clamp(100 - billPct, 0, 100);
      html('flow', [
        ['Factures', billPct, euro(Math.min(bills, Math.max(0, total)))],
        ['Épargne / reste', savePct, euro(Math.max(0, save))],
      ].map(([label, p, val]) => `
        <div class="lab-flow-row">
          <span>${label}</span>
          <i><em style="width:${p}%"></em></i>
          <b class="mono">${val}</b>
        </div>
      `).join(''));
      set('note', withoutAe >= 0
        ? 'Le salaire couvre les factures. L’AE peut tout verser à l’épargne après l’Urssaf — y compris tomber à zéro un mois, sans faire sauter le loyer.'
        : `Le salaire ne tient pas les factures. L’AE doit verser au moins ${euro(-withoutAe)} chaque mois, et le compte pro a besoin d’un matelas pour les mois à 0 €.`);
    });
  };

  const flowHtml = (rows) => rows.map(([label, p, val]) => `
        <div class="lab-flow-row">
          <span>${label}</span>
          <i><em style="width:${p}%"></em></i>
          <b class="mono">${val}</b>
        </div>
      `).join('');

  const epargne = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const income = num(inn('income'));
      const bills = num(inn('bills'));
      const save = num(inn('save'));
      const daily = income - bills - save;
      set('income', euro(income));
      set('bills', euro(bills));
      set('save', euro(save));
      set('daily', euro(Math.max(0, daily)), daily < 0);
      set('pct', income > 0 ? `${Math.round((save / income) * 100)} %` : '—');
      set('when', save > 0 ? monthsLabel(1000 / save) : 'jamais');
      const base = Math.max(income, 1);
      html('flow', flowHtml([
        ['Fixes', clamp((bills / base) * 100, 0, 100), euro(bills)],
        ['Livret', clamp((save / base) * 100, 0, 100), euro(save)],
        ['Quotidien', clamp((Math.max(0, daily) / base) * 100, 0, 100), euro(Math.max(0, daily))],
      ]));
      if (daily < 0) set('note', `Le mois casse de ${euro(-daily)}. Baissez le livret ou les fixes : un versement qui met le courant à découvert ne tiendra pas.`);
      else if (save === 0) set('note', 'Sans versement, le quotidien prend tout le surplus. C’est précisément l’épargne « s’il en reste ».');
      else set('note', `${euro(save)} partent vers le livret. Il reste ${euro(daily)} pour le quotidien. 1 000 € sont là en ${monthsLabel(1000 / save)}.`);
    });
  };

  const budget = (root) => {
    const { inn, set, onChange } = api(root);
    onChange(() => {
      const income = num(inn('income'));
      const bills = num(inn('bills'));
      const daily = num(inn('daily'));
      const save = num(inn('save'));
      const used = bills + daily + save;
      const left = income - used;
      set('income', euro(income));
      set('bills', euro(bills));
      set('daily', euro(daily));
      set('save', euro(save));
      set('used', euro(used));
      set('left', euro(left), left !== 0);
      if (left < 0) {
        set('state', 'Déficit', true);
        set('note', `Il manque ${euro(-left)}. Ce n’est plus une répartition : un fixe doit baisser, ou une entrée monter.`);
      } else if (left === 0) {
        set('state', 'À zéro');
        set('note', 'Chaque euro a une destination. C’est un mois type complet — le compteur non affecté est à zéro.');
      } else {
        set('state', 'Reste');
        set('note', `${euro(left)} n’ont pas encore de destination. Posez-les sur le livret, ou dites que c’est du quotidien.`);
      }
    });
  };

  const livreta = (root) => {
    const { inn, set, out, onChange } = api(root);
    const cap = 22950;
    const rate = 1.7;
    onChange(() => {
      const start = clamp(num(inn('start')), 0, cap);
      const pay = num(inn('pay'));
      const room = Math.max(0, cap - start);
      const when = fillMonths(start, cap, pay);
      set('start', euro(start));
      set('pay', euro(pay));
      set('room', euro(room));
      set('when', monthsLabel(when));
      set('interest', euro(start * (rate / 100)));
      const fill = out('fill');
      if (fill) fill.style.width = `${clamp((start / cap) * 100, 0, 100)}%`;
      if (room <= 0) set('note', 'Le plafond de versement est atteint. Les intérêts peuvent encore faire monter le solde ; le virement mensuel doit partir ailleurs.');
      else if (pay <= 0) set('note', `Il reste ${euro(room)} avant 22 950 €. Sans versement, seuls les intérêts (~${euro(start * rate / 100)} / an sur le stock actuel) bougent.`);
      else set('note', `À ${euro(pay)} / mois, le livret sature en ${monthsLabel(when)}. Après cette date, câblez un débordement — LDDS s’il a de la place, ou un bac suivant.`);
    });
  };

  const payfirst = (root) => {
    const { inn, set, html, onChange } = api(root);
    let pctSave = 20;
    chipGroup(root, 'data-pct', '20', (v) => { pctSave = Number(v); });
    onChange(() => {
      const income = num(inn('income'));
      const rent = num(inn('rent'));
      const save = income * (pctSave / 100);
      const daily = income - rent - save;
      set('income', euro(income));
      set('rent', euro(rent));
      set('save', euro(save));
      set('daily', euro(Math.max(0, daily)), daily < 0);
      const base = Math.max(income, 1);
      html('flow', flowHtml([
        ['Loyer / fixes', clamp((rent / base) * 100, 0, 100), euro(rent)],
        [`Livret ${pctSave} %`, clamp((save / base) * 100, 0, 100), euro(save)],
        ['Quotidien', clamp((Math.max(0, daily) / base) * 100, 0, 100), euro(Math.max(0, daily))],
      ]));
      if (daily < 0) {
        set('state', 'Ça casse', true);
        set('note', `À ${pctSave} %, le livret et le loyer dépassent le salaire de ${euro(-daily)}. Baissez le pourcentage, ou le mois ne tient pas.`);
      } else {
        set('state', 'Tenue');
        set('note', `${euro(save)} vers le livret, ${euro(daily)} pour le quotidien. C’est le câblage « se payer d’abord » : le 20 % du modèle Premier salaire se règle ici.`);
      }
    });
  };

  const petit = (root) => {
    const { inn, set, onChange } = api(root);
    onChange(() => {
      const pay = num(inn('pay'));
      const years = num(inn('years'));
      const months = years * 12;
      const useRate = inn('rate')?.checked ?? true;
      const paid = pay * months;
      let end = paid;
      if (useRate) {
        const r = 0.017 / 12;
        end = r === 0 ? paid : pay * ((Math.pow(1 + r, months) - 1) / r);
      }
      set('pay', euro(pay));
      const span = years > 1 ? years + ' ans' : 'un an';
      set('years', years > 1 ? years + ' ans' : '1 an');
      set('paid', euro(paid));
      set('end', euro(end));
      set('gain', euro(Math.max(0, end - paid)));
      set('note', useRate
        ? euro(pay) + ' pendant ' + span + ' : ' + euro(paid) + ' versés, environ ' + euro(end) + ' si le taux du Livret A reste à 1,70 %. Les taux sont révisés deux fois par an.'
        : euro(pay) + ' pendant ' + span + ' : ' + euro(paid) + ' de côté, sans compter les intérêts. La régularité compte plus que le taux.');
    });
  };

  const projet = (root) => {
    const { inn, set, onChange } = api(root);
    onChange(() => {
      const target = num(inn('target'));
      const have = num(inn('have'));
      const months = num(inn('months'));
      const gap = Math.max(0, target - have);
      const pay = months > 0 ? gap / months : 0;
      set('target', euro(target));
      set('have', euro(have));
      set('months', months > 1 ? `${months} mois` : '1 mois');
      set('gap', euro(gap));
      set('pay', euro(pay));
      set('ok', pay <= 400 ? 'oui' : 'non', pay > 400);
      if (gap <= 0) set('note', 'La cible est déjà là. Le fixe peut s’arrêter, ou basculer vers le Livret A.');
      else if (pay > 400) set('note', `Il faudrait ${euro(pay)} / mois. Au-dessus des 400 € du modèle « Projet à date » : reculez l’échéance, ou baissez la cible.`);
      else set('note', `${euro(pay)} / mois pendant ${months} mois. Le reste du surplus peut rester sur le Livret A, sans mélanger les deux bacs.`);
    });
  };

  const apport = (root) => {
    const { inn, set, out, onChange } = api(root);
    onChange(() => {
      const target = num(inn('target'));
      const have = num(inn('have'));
      const pay = num(inn('pay'));
      const gap = Math.max(0, target - have);
      const when = pay > 0 ? gap / pay : Infinity;
      set('target', euro(target));
      set('have', euro(have));
      set('pay', euro(pay));
      set('gap', euro(gap));
      set('when', monthsLabel(when));
      set('total', pay > 0 && Number.isFinite(when) ? euro(pay * Math.ceil(when)) : '—');
      const fill = out('fill');
      if (fill) fill.style.width = `${clamp((have / Math.max(target, 1)) * 100, 0, 100)}%`;
      if (gap <= 0) set('note', 'La cible est atteinte. Gardez le matelas de précaution à part : on n’achète pas avec les trois mois de charges.');
      else if (pay <= 0) set('note', `Il manque ${euro(gap)}. Sans versement, la date n’existe pas.`);
      else set('note', `À ${euro(pay)} / mois, l’apport est là en ${monthsLabel(when)}. En chemin, saturer LEP puis LDDS avant de tout verser dans ce bac.`);
    });
  };

  const premier = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const income = num(inn('income'));
      const rent = num(inn('rent'));
      const save = income * 0.2;
      const daily = income - rent - save;
      set('income', euro(income));
      set('rent', euro(rent));
      set('save', euro(save));
      set('daily', euro(Math.max(0, daily)), daily < 0);
      const base = Math.max(income, 1);
      html('flow', flowHtml([
        ['Loyer', clamp((rent / base) * 100, 0, 100), euro(rent)],
        ['Épargne 20 %', clamp((save / base) * 100, 0, 100), euro(save)],
        ['Quotidien', clamp((Math.max(0, daily) / base) * 100, 0, 100), euro(Math.max(0, daily))],
      ]));
      if (daily < 0) {
        set('state', 'Trop cher', true);
        set('note', `Loyer + 20 % dépassent le salaire de ${euro(-daily)}. Ce n’est plus le modèle Premier salaire : le logement mange l’épargne, puis le mois.`);
      } else if (daily < 500) {
        set('state', 'Juste', true);
        set('note', `Il reste ${euro(daily)} pour le quotidien. L’épargne tient encore (elle est servie avant). Le quotidien, lui, est déjà serré.`);
      } else {
        set('state', 'Tenue');
        set('note', `Comme le modèle : ${euro(save)} vers LEP puis Livret A, ${euro(daily)} pour le quotidien. Montez le loyer pour voir ce qui encaisse le choc.`);
      }
    });
  };

  const prorata = (root) => {
    const { inn, set, html, onChange } = api(root);
    onChange(() => {
      const a = num(inn('a'));
      const b = num(inn('b'));
      const shared = num(inn('shared'));
      const total = a + b;
      set('a', euro(a));
      set('b', euro(b));
      set('shared', euro(shared));
      const halfA = shared / 2;
      const halfB = shared / 2;
      const proA = total > 0 ? shared * (a / total) : 0;
      const proB = total > 0 ? shared * (b / total) : 0;
      const row = (title, left, right, leftShare, rightShare) => `
        <div class="lab-col-title">${title}</div>
        <div><span>A verse</span><b class="mono">${euro(left)} <span style="color:var(--faint);font-weight:500">${Math.round(leftShare)} %</span></b></div>
        <div><span>B verse</span><b class="mono">${euro(right)} <span style="color:var(--faint);font-weight:500">${Math.round(rightShare)} %</span></b></div>
        <div><span>Reste A</span><b class="mono${a - left < 0 ? ' is-bad' : ''}">${euro(a - left)}</b></div>
        <div><span>Reste B</span><b class="mono${b - right < 0 ? ' is-bad' : ''}">${euro(b - right)}</b></div>
      `;
      html('half', row('50 / 50', halfA, halfB, a ? (halfA / a) * 100 : 0, b ? (halfB / b) * 100 : 0));
      html('pro', row('Prorata', proA, proB, a ? (proA / a) * 100 : 0, b ? (proB / b) * 100 : 0));
      if (a - halfA < 0 || b - halfB < 0) set('note', 'À 50/50, l’un des deux comptes passe sous zéro. Le prorata évite ça : chacun verse la même part de son salaire.');
      else set('note', `À 50/50, A pose ${Math.round((halfA / Math.max(a, 1)) * 100)} % de son salaire, B ${Math.round((halfB / Math.max(b, 1)) * 100)} %. Au prorata, les deux restent à ${Math.round((shared / Math.max(total, 1)) * 100)} %.`);
    });
  };

  const credit = (root) => {
    const { inn, set, onChange } = api(root);
    onChange(() => {
      const income = num(inn('income'));
      const loan = num(inn('loan'));
      const house = num(inn('house'));
      const daily = num(inn('daily'));
      const roof = loan + house;
      const left = income - roof - daily;
      const works = 300;
      set('income', euro(income));
      set('loan', euro(loan));
      set('house', euro(house));
      set('daily', euro(daily));
      set('roof', euro(roof));
      set('left', euro(left), left < 0);
      set('works', left >= works ? 'tenu' : (left < 0 ? 'impossible' : 'trop juste'), left < works);
      if (left < 0) set('note', `Il manque ${euro(-left)} une fois le toit et le quotidien servis. Le crédit a remplacé le loyer par un fixe trop lourd pour ce foyer.`);
      else if (left < works) set('note', `Il reste ${euro(left)}. L’enveloppe travaux à 300 € du modèle ne tient pas : baissez le quotidien, ou acceptez un bac travaux plus petit.`);
      else set('note', `${euro(left)} après le toit et le quotidien. 300 € peuvent aller aux travaux, ${euro(left - works)} au Livret A — c’est le câblage du modèle Propriétaire.`);
    });
  };

  const saison = (root) => {
    const { inn, set, onChange } = api(root);
    onChange(() => {
      const highn = num(inn('highn'));
      const high = num(inn('high'));
      const low = num(inn('low'));
      const bills = num(inn('bills'));
      const lown = 12 - highn;
      const avg = ((high * highn) + (low * lown)) / 12;
      const gap = Math.max(0, bills - low);
      const reserve = gap * lown;
      set('highn', highn > 1 ? `${highn} mois` : '1 mois');
      set('high', euro(high));
      set('low', euro(low));
      set('bills', euro(bills));
      set('avg', euro(avg));
      set('gap', euro(gap));
      set('reserve', euro(reserve), reserve === 0);
      if (avg < bills) set('note', `La moyenne annuelle (${euro(avg)}) ne couvre pas les charges. Lisser ne suffit pas : le foyer est structurellement trop juste.`);
      else if (gap === 0) set('note', 'Même un mois creux couvre les charges. La réserve sert alors d’imprévu, pas de pont saisonnier.');
      else set('note', `Un creux manque de ${euro(gap)}. Sur ${lown} mois bas, il faut ${euro(reserve)} déjà là — nourris par les mois chargés, pas « s’il reste en août ».`);
    });
  };

  const labs = { couple, tableur, ordre, joints, urssaf, plafond, mix, reste, famille, scenarios, taux, repart, migrate, plafonds, liberatoire, irregulier, lep, matelas, mixte, epargne, budget, livreta, payfirst, petit, projet, apport, premier, prorata, credit, saison };
  document.querySelectorAll('[data-lab]').forEach((root) => {
    const fn = labs[root.getAttribute('data-lab')];
    if (fn) fn(root);
  });

  const toc = [...document.querySelectorAll('.article-toc a')];
  const heads = toc.map((a) => document.querySelector(a.getAttribute('href'))).filter(Boolean);
  if (toc.length && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      const visible = entries.filter((e) => e.isIntersecting).pop();
      if (!visible) return;
      toc.forEach((a) => a.classList.toggle('is-on', a.getAttribute('href') === `#${visible.target.id}`));
    }, { rootMargin: '-20% 0px -70% 0px', threshold: 0 });
    heads.forEach((h) => io.observe(h));
  }
})();
