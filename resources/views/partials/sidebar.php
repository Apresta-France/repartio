<?php
$nav = $nav ?? '';
$limit = \App\Models\Project::planLimit($user);
$used = $activeCount ?? 0;
$pct = $limit > 0 ? min(100, (int) round($used / $limit * 100)) : 0;
$nextPlan = \App\Models\Plan::nextLabel($user);
$limitHint = $used >= $limit
    ? ($nextPlan ? 'Limite atteinte. Passez en ' . $nextPlan . '.' : 'Limite de circuits atteinte.')
    : 'Circuits actifs sur votre plan.';
$main = [
    ['dashboard', 'Tableau de bord', '/app', 'var(--teal)'],
    ['projets', 'Mes circuits', '/app/circuits', 'var(--blue)'],
    ['acces', 'Accès & droits', '/app/acces', 'var(--orange)'],
];
$bottom = [
    ['forfait', 'Forfait & facturation', '/app/forfait', 'var(--orange)'],
    ['profil', 'Mon profil', '/app/profil', 'var(--navy)'],
    ['reglages', 'Réglages', '/app/reglages', 'var(--navy)'],
];
if (\App\Core\Auth::isAdmin($user)) {
    array_unshift($bottom, ['admin', 'Administration', '/admin', 'var(--orange)']);
}
?>
<aside class="sidebar" id="app-sidebar">
  <a href="<?= e(url('/app')) ?>" class="logo"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
  <form method="post" action="<?= e(url('/app/circuits/nouveau')) ?>"><?= csrf_field() ?><button class="btn btn-orange" type="submit">+ Nouveau circuit</button></form>
  <nav>
    <?php foreach ($main as [$id, $label, $href, $dot]): ?>
      <a href="<?= e(url($href)) ?>" class="<?= $nav === $id ? 'active' : '' ?>">
        <span class="dot" style="background:<?= e($dot) ?>"></span><?= e($label) ?>
        <?php if ($id === 'projets'): ?><span class="mono" style="margin-left:auto;font-size:10.5px;color:var(--faint);"><?= (int) $used ?></span><?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>
  <div>
    <span class="eyebrow" style="padding:4px 12px 8px;display:block;">Circuits récents</span>
    <?php foreach (($recents ?? []) as $r): ?>
      <a href="<?= e(url(\App\Models\Project::path($r))) ?>" style="display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:9px;font-size:13px;color:oklch(0.42 0.05 265);">
        <span class="dot" style="border-radius:50%;background:var(--teal)"></span>
        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($r['name']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
  <nav style="margin-top:auto;padding-top:14px;border-top:1px solid var(--line-soft);">
    <?php foreach ($bottom as [$id, $label, $href, $dot]): ?>
      <a href="<?= e(url($href)) ?>" class="<?= $nav === $id ? 'active' : '' ?>">
        <span class="dot" style="background:<?= e($dot) ?>"></span><?= e($label) ?>
      </a>
    <?php endforeach; ?>
  </nav>
  <a href="<?= e(url('/app/forfait')) ?>" style="display:flex;flex-direction:column;gap:7px;padding:13px;border-radius:11px;background:oklch(0.975 0.005 250);border:1px solid var(--line);color:var(--ink);">
    <div style="display:flex;align-items:baseline;gap:8px;">
      <span class="eyebrow">Plan <?= e(\App\Models\Plan::label($user)) ?></span>
      <span class="mono" style="margin-left:auto;font-size:11px;"><?= (int) $used ?> / <?= (int) $limit ?></span>
    </div>
    <div class="progress"><i style="width:<?= (int) $pct ?>%"></i></div>
    <span style="font-size:12px;color:var(--muted);"><?= e($limitHint) ?></span>
  </a>
  <a href="<?= e(url('/app/profil')) ?>" style="display:flex;align-items:center;gap:10px;padding:8px 6px 0;color:var(--ink);">
    <span style="width:30px;height:30px;border-radius:9px;background:var(--navy);color:#fff;display:grid;place-items:center;font-size:12px;font-weight:700;"><?= e(initials($user['first_name'])) ?></span>
    <span style="display:flex;flex-direction:column;overflow:hidden;">
      <span style="font-size:13px;font-weight:600;"><?= e($user['first_name']) ?></span>
      <span class="mono" style="font-size:10.5px;color:var(--faint);overflow:hidden;text-overflow:ellipsis;"><?= e($user['email']) ?></span>
    </span>
  </a>
</aside>
