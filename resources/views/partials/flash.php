<?php
$flashError = flash('error');
$flashSuccess = flash('success');
if (!$flashError && !$flashSuccess) {
    return;
}
?>
<div class="flash-stack" aria-live="polite">
  <?php if ($flashError): ?>
    <div class="flash flash-error" role="alert" data-flash>
      <p><?= e($flashError) ?></p>
      <button type="button" class="flash-close" data-flash-close aria-label="Fermer">
        <span aria-hidden="true">×</span>
      </button>
    </div>
  <?php endif; ?>
  <?php if ($flashSuccess): ?>
    <div class="flash flash-success" role="status" data-flash>
      <p><?= e($flashSuccess) ?></p>
      <button type="button" class="flash-close" data-flash-close aria-label="Fermer">
        <span aria-hidden="true">×</span>
      </button>
    </div>
  <?php endif; ?>
</div>
