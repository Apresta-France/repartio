<?php
$result = $result ?? ['rows' => [], 'total' => 0, 'page' => 1, 'pages' => 1];
$plans = $plans ?? [];
$q = $q ?? '';
$planFilter = $planFilter ?? '';
$roleFilter = $roleFilter ?? '';
$query = static function (array $extra = []) use ($q, $planFilter, $roleFilter): string {
    $params = array_filter([
        'q' => $extra['q'] ?? $q,
        'plan' => $extra['plan'] ?? $planFilter,
        'role' => $extra['role'] ?? $roleFilter,
        'page' => $extra['page'] ?? null,
    ], static fn ($v) => $v !== null && $v !== '');
    return $params ? '?' . http_build_query($params) : '';
};
?>
<header class="app-top">
  <div>
    <h1>Clients</h1>
    <span class="eyebrow"><?= (int) $result['total'] ?> compte<?= (int) $result['total'] > 1 ? 's' : '' ?></span>
  </div>
  <a class="btn btn-orange" href="<?= e(url('/admin/clients/nouveau')) ?>">Nouveau client</a>
</header>
<section class="admin-page">
  <form class="admin-filters" method="get" action="<?= e(url('/admin/clients')) ?>">
    <label class="field"><span>Recherche</span><input type="search" name="q" value="<?= e($q) ?>" placeholder="Prénom ou e-mail"></label>
    <label class="field">
      <span>Forfait</span>
      <select name="plan">
        <option value="">Tous</option>
        <?php foreach ($plans as $plan): ?>
          <option value="<?= e($plan['slug']) ?>" <?= $planFilter === $plan['slug'] ? 'selected' : '' ?>><?= e($plan['label']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label class="field">
      <span>Rôle</span>
      <select name="role">
        <option value="">Tous</option>
        <option value="user" <?= $roleFilter === 'user' ? 'selected' : '' ?>>Client</option>
        <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Administrateur</option>
      </select>
    </label>
    <button class="btn btn-navy" type="submit">Filtrer</button>
  </form>

  <div class="table">
    <div class="table-row table-admin-clients table-head">
      <span>Client</span><span>Forfait</span><span>Circuits</span><span>Dernière visite</span><span>Inscription</span>
    </div>
    <?php if (!$result['rows']): ?>
      <div class="table-row">Aucun client ne correspond.</div>
    <?php endif; ?>
    <?php foreach ($result['rows'] as $row): ?>
      <a class="table-row table-admin-clients" href="<?= e(url('/admin/clients/' . $row['id'])) ?>">
        <span class="admin-person">
          <span class="admin-avatar"><?= e(initials((string) $row['first_name'])) ?></span>
          <span>
            <strong><?= e($row['first_name']) ?></strong>
            <span class="mono admin-quiet"><?= e($row['email']) ?></span>
            <?php if (($row['role'] ?? '') === 'admin'): ?><span class="chip">Admin</span><?php endif; ?>
            <?php if (empty($row['email_verified_at'])): ?><span class="chip chip-warn">Non confirmé</span><?php endif; ?>
          </span>
        </span>
        <span><?= e(\App\Models\Plan::label($row)) ?></span>
        <span class="mono"><?= (int) ($row['circuits_count'] ?? 0) ?></span>
        <span class="mono"><?= e(time_ago($row['last_login_at'] ?? null)) ?></span>
        <span class="mono"><?= e($row['created_at'] ? date('d/m/Y', strtotime((string) $row['created_at'])) : '—') ?></span>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ((int) $result['pages'] > 1): ?>
    <div class="admin-pager">
      <?php if ((int) $result['page'] > 1): ?>
        <a class="btn btn-ghost" href="<?= e(url('/admin/clients') . $query(['page' => $result['page'] - 1])) ?>">Précédent</a>
      <?php endif; ?>
      <span class="mono">Page <?= (int) $result['page'] ?> / <?= (int) $result['pages'] ?></span>
      <?php if ((int) $result['page'] < (int) $result['pages']): ?>
        <a class="btn btn-ghost" href="<?= e(url('/admin/clients') . $query(['page' => $result['page'] + 1])) ?>">Suivant</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</section>
