<?php
$share = $share ?? null;
$sends = $sends ?? [];
$suggestedTitle = $suggestedTitle ?? ($share['title'] ?? $project['name']);
$returnTo = $returnTo ?? 'page';
$publicUrl = $share ? url('/p/' . $share['slug']) : '';
$enabled = $share && (int) $share['enabled'] === 1;
?>
<form method="post" action="<?= e(url(\App\Models\Project::path($project, 'partage'))) ?>" class="share-form" data-share-form>
  <?= csrf_field() ?>
  <input type="hidden" name="return_to" value="<?= e($returnTo) ?>">

  <label class="field">
    <span>Nom de l’aperçu public</span>
    <input name="title" required maxlength="180" value="<?= e($suggestedTitle) ?>" placeholder="Ex. Budget familial">
  </label>

  <?php if ($share): ?>
    <div class="share-url-row">
      <input type="text" readonly data-copy-value value="<?= e($publicUrl) ?>" class="share-url-input" aria-label="Lien public">
      <button type="button" class="btn btn-ghost" data-copy>Copier</button>
      <?php if ($enabled): ?>
        <a class="btn btn-ghost" href="<?= e($publicUrl) ?>" target="_blank" rel="noopener">Ouvrir</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <?php if ($share && !$enabled): ?>
    <p class="builder-hint">Ce lien est révoqué. Enregistrez à nouveau pour le réactiver, ou utilisez Réactiver.</p>
  <?php elseif ($share): ?>
    <p class="builder-hint">Toute personne qui a le lien voit la dernière version enregistrée, en lecture seule, sans compte.</p>
  <?php else: ?>
    <p class="builder-hint">Un identifiant unique sera généré à l’enregistrement. Toute personne qui a le lien verra la dernière version, en lecture seule, sans compte.</p>
  <?php endif; ?>

  <label class="field">
    <span>Envoyer vers une ou plusieurs adresses</span>
    <textarea name="emails" rows="3" placeholder="marie@exemple.fr, paul@exemple.fr"><?= e((string) old('emails', '')) ?></textarea>
  </label>
  <label class="field">
    <span>Message (optionnel)</span>
    <textarea name="note" rows="2" maxlength="500" placeholder="Voici notre ventilation du mois."><?= e((string) old('note', '')) ?></textarea>
  </label>

  <div class="share-actions">
    <button class="btn btn-orange" type="submit">Enregistrer le lien</button>
  </div>
</form>

<?php if ($share): ?>
  <div class="share-tools">
    <?php if ($enabled): ?>
      <form method="post" action="<?= e(url(\App\Models\Project::path($project, 'partage/revoquer'))) ?>" onsubmit="return confirm('Révoquer le lien public ? Les destinataires ne pourront plus l’ouvrir.');">
        <?= csrf_field() ?>
        <input type="hidden" name="return_to" value="<?= e($returnTo) ?>">
        <button class="btn btn-ghost" type="submit">Révoquer le lien</button>
      </form>
    <?php else: ?>
      <form method="post" action="<?= e(url(\App\Models\Project::path($project, 'partage/reactiver'))) ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="return_to" value="<?= e($returnTo) ?>">
        <button class="btn btn-navy" type="submit">Réactiver le lien</button>
      </form>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if ($sends): ?>
  <div class="share-sends">
    <div class="eyebrow">Déjà envoyé</div>
    <?php foreach ($sends as $send): ?>
      <div class="share-send">
        <span><?= e($send['email']) ?></span>
        <span class="mono"><?= e(time_ago($send['sent_at'])) ?><?= (int) $send['n'] > 1 ? ' · ' . (int) $send['n'] . ' fois' : '' ?></span>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
