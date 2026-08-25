<?php
$messages = $messages ?? [];
$topics = [
    'compte' => 'Compte',
    'circuit' => 'Circuit',
    'facture' => 'Facture',
    'presse' => 'Presse',
    'autre' => 'Autre',
];
?>
<header class="app-top">
  <div>
    <h1>Messages</h1>
    <span class="eyebrow"><?= count($messages) ?> entrée<?= count($messages) > 1 ? 's' : '' ?> · formulaire contact</span>
  </div>
</header>
<section class="admin-page">
  <?php if (!$messages): ?>
    <div class="card card-pad"><p class="lede">Aucun message pour le moment.</p></div>
  <?php endif; ?>
  <div class="admin-stack">
    <?php foreach ($messages as $message): ?>
      <article class="card card-pad admin-message">
        <div class="admin-section-head">
          <div>
            <strong><?= e((string) $message['first_name']) ?></strong>
            <span class="mono admin-quiet"><?= e((string) $message['email']) ?></span>
          </div>
          <div class="admin-actions">
            <span class="chip"><?= e($topics[$message['topic']] ?? (string) $message['topic']) ?></span>
            <span class="mono"><?= e(time_ago($message['created_at'] ?? null)) ?></span>
          </div>
        </div>
        <p><?= nl2br(e((string) $message['body'])) ?></p>
        <form method="post" action="<?= e(url('/admin/messages/' . $message['id'] . '/supprimer')) ?>">
          <?= csrf_field() ?>
          <button class="btn btn-ghost" type="submit">Supprimer</button>
        </form>
      </article>
    <?php endforeach; ?>
  </div>
</section>
