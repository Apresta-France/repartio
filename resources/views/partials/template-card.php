<?php
$key = (string) ($key ?? $t['key'] ?? '');
$t = $t ?? [];
$cta = $cta ?? 'Ouvrir dans le builder';
$category = (string) ($t['category'] ?? '');
?>
<form method="post" action="<?= e(url('/app/circuits')) ?>" class="card card-action"<?= $category !== '' ? ' data-filter-item="' . e($category) . '" data-filter-group="templates"' : '' ?>>
  <?= csrf_field() ?>
  <input type="hidden" name="name" value="<?= e((string) ($t['title'] ?? '')) ?>">
  <input type="hidden" name="template" value="<?= e($key) ?>">
  <button type="submit" class="card-action-hit">
    <?php
    $wires = $t['thumb']['wires'] ?? [];
    $dots = $t['thumb']['dots'] ?? [];
    require BASE_PATH . '/resources/views/partials/circuit-thumb.php';
    ?>
    <div class="card-action-body">
      <div class="card-action-title">
        <strong><?= e((string) ($t['title'] ?? '')) ?></strong>
        <span class="mono"><?= (int) ($t['blocks'] ?? 0) ?> blocs</span>
      </div>
      <p><?= e((string) ($t['hint'] ?? '')) ?></p>
      <?php if ($cta !== ''): ?>
        <span class="btn btn-ghost"><?= e($cta) ?></span>
      <?php endif; ?>
    </div>
  </button>
</form>
