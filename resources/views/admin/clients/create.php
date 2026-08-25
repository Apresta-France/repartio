<?php $plans = $plans ?? []; ?>
<header class="app-top">
  <div>
    <h1>Nouveau client</h1>
    <span class="eyebrow"><a href="<?= e(url('/admin/clients')) ?>">Clients</a> · création</span>
  </div>
</header>
<section class="admin-page admin-page-narrow">
  <form class="card card-pad admin-form" method="post" action="<?= e(url('/admin/clients')) ?>">
    <?= csrf_field() ?>
    <label class="field"><span>Prénom</span><input name="first_name" required value="<?= e((string) old('first_name')) ?>"></label>
    <label class="field"><span>E-mail</span><input type="email" name="email" required value="<?= e((string) old('email')) ?>"></label>
    <label class="field">
      <span>Mot de passe</span>
      <input type="password" name="password" required minlength="12" autocomplete="new-password">
      <span class="field-hint">12 caractères, majuscule, minuscule, chiffre ou symbole.</span>
    </label>
    <div class="fields-2">
      <label class="field">
        <span>Forfait</span>
        <select name="plan">
          <?php foreach ($plans as $plan): ?>
            <option value="<?= e($plan['slug']) ?>" <?= (string) old('plan', 'libre') === $plan['slug'] ? 'selected' : '' ?>><?= e($plan['label']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label class="field">
        <span>Rôle</span>
        <select name="role">
          <option value="user" <?= (string) old('role', 'user') === 'user' ? 'selected' : '' ?>>Client</option>
          <option value="admin" <?= (string) old('role') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        </select>
      </label>
    </div>
    <label class="check"><input type="checkbox" name="verified" value="1" <?= old('verified') ? 'checked' : '' ?>><span>Marquer l’e-mail comme confirmé</span></label>
    <div class="admin-actions">
      <button class="btn btn-orange" type="submit">Créer le compte</button>
      <a class="btn btn-ghost" href="<?= e(url('/admin/clients')) ?>">Annuler</a>
    </div>
  </form>
</section>
