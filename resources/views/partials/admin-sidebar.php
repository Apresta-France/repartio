<?php
$nav = $nav ?? '';
$main = [
    ['dashboard', 'Tableau de bord', '/admin', 'var(--teal)'],
    ['clients', 'Clients', '/admin/clients', 'var(--blue)'],
    ['forfaits', 'Forfaits', '/admin/forfaits', 'var(--orange)'],
    ['messages', 'Messages', '/admin/messages', 'var(--navy)'],
    ['emails', 'E-mails', '/admin/emails', 'var(--navy)'],
];
$bottom = [
    ['environnement', 'Environnement', '/admin/environnement', 'var(--navy)'],
];
?>
<aside class="sidebar" id="app-sidebar">
  <a href="<?= e(url('/admin')) ?>" class="logo"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
  <span class="chip admin-badge">Administration</span>
  <nav>
    <?php foreach ($main as [$id, $label, $href, $dot]): ?>
      <a href="<?= e(url($href)) ?>" class="<?= $nav === $id ? 'active' : '' ?>">
        <span class="dot" style="background:<?= e($dot) ?>"></span><?= e($label) ?>
      </a>
    <?php endforeach; ?>
  </nav>
  <nav style="margin-top:auto;padding-top:14px;border-top:1px solid var(--line-soft);">
    <?php foreach ($bottom as [$id, $label, $href, $dot]): ?>
      <a href="<?= e(url($href)) ?>" class="<?= $nav === $id ? 'active' : '' ?>">
        <span class="dot" style="background:<?= e($dot) ?>"></span><?= e($label) ?>
      </a>
    <?php endforeach; ?>
    <a href="<?= e(url('/app')) ?>">
      <span class="dot" style="background:var(--teal)"></span>Retour à l’app
    </a>
  </nav>
  <a href="<?= e(url('/app/profil')) ?>" style="display:flex;align-items:center;gap:10px;padding:8px 6px 0;color:var(--ink);">
    <span style="width:30px;height:30px;border-radius:9px;background:var(--navy);color:#fff;display:grid;place-items:center;font-size:12px;font-weight:700;"><?= e(initials($user['first_name'])) ?></span>
    <span style="display:flex;flex-direction:column;overflow:hidden;">
      <span style="font-size:13px;font-weight:600;"><?= e($user['first_name']) ?></span>
      <span class="mono" style="font-size:10.5px;color:var(--faint);overflow:hidden;text-overflow:ellipsis;"><?= e($user['email']) ?></span>
    </span>
  </a>
</aside>
