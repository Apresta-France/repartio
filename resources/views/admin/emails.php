<?php $logs = $logs ?? []; ?>
<header class="app-top">
  <div>
    <h1>E-mails envoyés</h1>
    <span class="eyebrow">Journal · <?= count($logs) ?> dernière<?= count($logs) > 1 ? 's' : '' ?> entrée<?= count($logs) > 1 ? 's' : '' ?></span>
  </div>
</header>
<section class="admin-page">
  <div class="table">
    <div class="table-row table-admin-mails table-head">
      <span>Quand</span><span>Destinataire</span><span>Sujet</span><span>Modèle</span>
    </div>
    <?php if (!$logs): ?>
      <div class="table-row">Aucun envoi journalisé.</div>
    <?php endif; ?>
    <?php foreach ($logs as $log): ?>
      <div class="table-row table-admin-mails">
        <span class="mono"><?= e($log['created_at'] ? date('d/m/Y H:i', strtotime((string) $log['created_at'])) : '—') ?></span>
        <span class="mono"><?= e((string) $log['recipient']) ?></span>
        <span><?= e((string) $log['subject']) ?></span>
        <span class="chip"><?= e((string) $log['template']) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>
