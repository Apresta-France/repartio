<header class="app-top">
  <div>
    <h1>Mes circuits</h1>
    <span class="eyebrow"><?= (int) $activeCount ?> actifs · plan <?= e(ucfirst($user['plan'])) ?> <?= (int) $activeCount ?>/<?= (int) $limit ?></span>
  </div>
  <a class="btn btn-orange" href="<?= e(url('/app/circuits/nouveau')) ?>" style="margin-left:auto;">Nouveau circuit</a>
</header>
<section class="cards-fill" style="padding:24px 28px 34px;">
  <?php foreach ($projects as $p): ?>
    <div class="card" style="display:flex;flex-direction:column;">
      <a href="<?= e(url('/app/circuits/' . $p['id'])) ?>" style="height:120px;background:var(--grid);border-bottom:1px solid var(--line);display:block;"></a>
      <div style="padding:16px 18px 18px;display:flex;flex-direction:column;gap:11px;flex:1;">
        <div style="display:flex;align-items:baseline;gap:10px;">
          <a href="<?= e(url('/app/circuits/' . $p['id'])) ?>" style="font-weight:700;color:inherit;"><?= e($p['name']) ?></a>
          <span class="mono" style="margin-left:auto;font-size:10.5px;color:var(--faint);"><?= e($p['status']) ?></span>
        </div>
        <div class="kv">
          <div><span class="k">Entrées / mois</span><span><?= e(money($p['monthly_in'])) ?></span></div>
          <div><span class="k">Non affecté</span><span><?= e(money($p['unassigned'])) ?></span></div>
          <div><span class="k">Dans <?= (int) $p['horizon'] ?> mois</span><span><?= e(money($p['projection'])) ?></span></div>
        </div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:auto;">
          <a class="btn btn-ghost" href="<?= e(url('/app/circuits/' . $p['id'] . '/partage')) ?>" style="padding:5px 10px;font-size:12px;">Partager</a>
          <form method="post" action="<?= e(url('/app/circuits/' . $p['id'] . '/dupliquer')) ?>"><?= csrf_field() ?><button class="btn btn-ghost" style="padding:5px 10px;font-size:12px;">Dupliquer</button></form>
          <form method="post" action="<?= e(url('/app/circuits/' . $p['id'] . '/archiver')) ?>"><?= csrf_field() ?><button class="btn btn-ghost" style="padding:5px 10px;font-size:12px;"><?= $p['status'] === 'archive' ? 'Réactiver' : 'Archiver' ?></button></form>
          <form method="post" action="<?= e(url('/app/circuits/' . $p['id'] . '/supprimer')) ?>" onsubmit="return confirm('Supprimer ce circuit ?');"><?= csrf_field() ?><button class="btn btn-ghost" style="padding:5px 10px;font-size:12px;">Supprimer</button></form>
        </div>
        <span class="mono" style="font-size:10.5px;color:var(--faint);"><?= e(time_ago($p['updated_at'])) ?></span>
      </div>
    </div>
  <?php endforeach; ?>
  <a href="<?= e(url('/app/circuits/nouveau')) ?>" class="card" style="min-height:300px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:9px;border-style:dashed;color:var(--muted);">
    <span style="width:34px;height:34px;border-radius:11px;background:oklch(0.96 0.03 38);color:var(--orange-ink);display:grid;place-items:center;font-size:18px;">+</span>
    <strong>Nouveau circuit</strong>
  </a>
</section>
<section class="card" style="margin:0 28px 40px;padding:20px 24px;display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
  <div>
    <strong>Besoin d’un quatrième circuit ?</strong>
    <div style="font-size:13.5px;color:var(--muted);">Le plan Complet lève la limite et débloque les scénarios comparés.</div>
  </div>
  <a class="btn btn-navy" href="<?= e(url('/app/forfait')) ?>" style="margin-left:auto;">Voir les forfaits</a>
</section>
