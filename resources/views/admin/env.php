<?php
$values = $values ?? [];
$groups = $groups ?? [];
$writable = $writable ?? false;
$hasKey = !empty($hasKey);
?>
<header class="app-top">
  <div>
    <h1>Environnement</h1>
    <span class="eyebrow">Fichier .env · <?= $writable ? 'inscriptible' : 'lecture seule' ?></span>
  </div>
</header>
<section class="admin-page">
  <?php if (!$writable): ?>
    <div class="card card-pad admin-danger">
      <strong>Le fichier .env n’est pas inscriptible</strong>
      <p class="lede">Corrigez les droits du fichier à la racine du projet avant d’enregistrer.</p>
    </div>
  <?php endif; ?>

  <form class="admin-env" method="post" action="<?= e(url('/admin/environnement')) ?>">
    <?= csrf_field() ?>
    <?php foreach ($groups as $title => $fields): ?>
      <div class="card card-pad">
        <h2><?= e($title) ?></h2>
        <div class="admin-env-fields">
          <?php foreach ($fields as $key => $field):
              $type = $field['type'] ?? 'text';
              $current = (string) ($values[$key] ?? '');
              ?>
            <?php if ($type === 'checkbox'): ?>
              <label class="check">
                <input type="checkbox" name="<?= e($key) ?>" value="1" <?= $current === '1' ? 'checked' : '' ?>>
                <span><?= e($field['label']) ?><?php if (!empty($field['hint'])): ?> <span class="field-hint"><?= e($field['hint']) ?></span><?php endif; ?></span>
              </label>
            <?php elseif ($type === 'select'): ?>
              <label class="field">
                <span><?= e($field['label']) ?></span>
                <select name="<?= e($key) ?>">
                  <?php foreach ($field['options'] ?? [] as $value => $label): ?>
                    <option value="<?= e((string) $value) ?>" <?= $current === (string) $value ? 'selected' : '' ?>><?= e($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </label>
            <?php elseif ($type === 'password'): ?>
              <label class="field">
                <span><?= e($field['label']) ?></span>
                <input type="password" name="<?= e($key) ?>" value="" autocomplete="new-password" placeholder="<?= $current !== '' ? 'Inchangé' : '' ?>">
                <?php if (!empty($field['hint'])): ?><span class="field-hint"><?= e($field['hint']) ?></span><?php endif; ?>
              </label>
            <?php else: ?>
              <label class="field">
                <span><?= e($field['label']) ?></span>
                <input type="<?= e($type) ?>" name="<?= e($key) ?>" value="<?= e($current) ?>">
                <?php if (!empty($field['hint'])): ?><span class="field-hint"><?= e($field['hint']) ?></span><?php endif; ?>
              </label>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="card card-pad">
      <h2>Clé d’application</h2>
      <p class="lede">APP_KEY est <?= $hasKey ? 'définie' : 'manquante' ?>. Elle n’est pas affichée. Une régénération invalide les cookies chiffrés éventuels ; les sessions PHP restent valides.</p>
      <div class="admin-actions">
        <button class="btn btn-orange" type="submit" <?= $writable ? '' : 'disabled' ?>>Enregistrer le .env</button>
      </div>
    </div>
  </form>

  <div class="admin-actions">
    <form method="post" action="<?= e(url('/admin/environnement/cle')) ?>">
      <?= csrf_field() ?>
      <button class="btn btn-ghost" type="submit" <?= $writable ? '' : 'disabled' ?>>Régénérer APP_KEY</button>
    </form>
    <form method="post" action="<?= e(url('/admin/environnement/test-mail')) ?>">
      <?= csrf_field() ?>
      <button class="btn btn-navy" type="submit">Envoyer un e-mail de test</button>
    </form>
  </div>
</section>
