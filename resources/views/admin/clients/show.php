<?php
$client = $client ?? [];
$plans = $plans ?? [];
$projects = $projects ?? [];
$members = $members ?? [];
$activity = $activity ?? [];
$isSelf = (int) ($user['id'] ?? 0) === (int) ($client['id'] ?? 0);
?>
<header class="app-top">
  <div>
    <h1><?= e((string) $client['first_name']) ?></h1>
    <span class="eyebrow"><a href="<?= e(url('/admin/clients')) ?>">Clients</a> · <?= e((string) $client['email']) ?></span>
  </div>
  <div class="admin-actions">
    <?php if (($client['role'] ?? '') === 'admin'): ?><span class="chip">Administrateur</span><?php endif; ?>
    <?php if (!empty($client['email_verified_at'])): ?>
      <span class="chip">E-mail confirmé</span>
    <?php else: ?>
      <span class="chip chip-warn">E-mail non confirmé</span>
    <?php endif; ?>
  </div>
</header>
<section class="admin-page">
  <div class="admin-split">
    <form class="card card-pad admin-form" method="post" action="<?= e(url('/admin/clients/' . $client['id'])) ?>">
      <?= csrf_field() ?>
      <h2>Fiche</h2>
      <label class="field"><span>Prénom</span><input name="first_name" required value="<?= e((string) $client['first_name']) ?>"></label>
      <label class="field"><span>E-mail</span><input type="email" name="email" required value="<?= e((string) $client['email']) ?>"></label>
      <div class="fields-2">
        <label class="field">
          <span>Forfait</span>
          <select name="plan">
            <?php foreach ($plans as $plan): ?>
              <option value="<?= e($plan['slug']) ?>" <?= ($client['plan'] ?? '') === $plan['slug'] ? 'selected' : '' ?>><?= e($plan['label']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="field">
          <span>Rôle</span>
          <select name="role" <?= $isSelf ? 'disabled' : '' ?>>
            <option value="user" <?= ($client['role'] ?? '') !== 'admin' ? 'selected' : '' ?>>Client</option>
            <option value="admin" <?= ($client['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
          </select>
          <?php if ($isSelf): ?><input type="hidden" name="role" value="admin"><?php endif; ?>
        </label>
      </div>
      <p class="field-hint">Inscription <?= e($client['created_at'] ? date('d/m/Y H:i', strtotime((string) $client['created_at'])) : '—') ?> · dernière visite <?= e(time_ago($client['last_login_at'] ?? null)) ?></p>
      <button class="btn btn-orange" type="submit">Enregistrer</button>
    </form>

    <div class="admin-stack">
      <form class="card card-pad admin-form" method="post" action="<?= e(url('/admin/clients/' . $client['id'] . '/verifier')) ?>">
        <?= csrf_field() ?>
        <h2>Confirmation e-mail</h2>
        <?php if (!empty($client['email_verified_at'])): ?>
          <p class="lede">Confirmé le <?= e(date('d/m/Y H:i', strtotime((string) $client['email_verified_at']))) ?>.</p>
          <input type="hidden" name="unverify" value="1">
          <button class="btn btn-ghost" type="submit">Retirer la confirmation</button>
        <?php else: ?>
          <p class="lede">L’adresse n’a pas encore été confirmée.</p>
          <button class="btn btn-navy" type="submit">Marquer comme confirmée</button>
        <?php endif; ?>
      </form>

      <form class="card card-pad admin-form" method="post" action="<?= e(url('/admin/clients/' . $client['id'] . '/mot-de-passe')) ?>">
        <?= csrf_field() ?>
        <h2>Mot de passe</h2>
        <label class="field">
          <span>Nouveau mot de passe</span>
          <input type="password" name="password" required minlength="12" autocomplete="new-password">
        </label>
        <p class="field-hint">Les sessions et jetons « se souvenir de moi » seront révoqués.</p>
        <button class="btn btn-navy" type="submit">Changer le mot de passe</button>
      </form>
    </div>
  </div>

  <div>
    <div class="admin-section-head">
      <h2>Circuits (<?= count($projects) ?>)</h2>
    </div>
    <div class="table">
      <?php if (!$projects): ?>
        <div class="table-row">Aucun circuit.</div>
      <?php endif; ?>
      <?php foreach ($projects as $project): ?>
        <div class="table-row table-admin-circuits">
          <span><strong><?= e((string) $project['name']) ?></strong></span>
          <span class="chip"><?= e((string) $project['status']) ?></span>
          <span class="mono"><?= e(money($project['monthly_in'] ?? 0)) ?> / mois</span>
          <span class="mono"><?= e(time_ago($project['updated_at'] ?? null)) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if ($members): ?>
    <div>
      <div class="admin-section-head"><h2>Personnes invitées (<?= count($members) ?>)</h2></div>
      <div class="table">
        <?php foreach ($members as $member): ?>
          <div class="table-row table-admin-members">
            <span><?= e((string) ($member['member_name'] ?: $member['email'])) ?></span>
            <span class="mono"><?= e((string) $member['email']) ?></span>
            <span class="chip"><?= $member['status'] === 'active' ? 'Actif' : 'En attente' ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <div>
    <div class="admin-section-head"><h2>Activité</h2></div>
    <div class="table">
      <?php if (!$activity): ?>
        <div class="table-row">Aucun mouvement.</div>
      <?php endif; ?>
      <?php foreach ($activity as $a): ?>
        <div class="table-row table-admin-activity">
          <span class="mono"><?= e(time_ago($a['created_at'])) ?></span>
          <span><?= e((string) $a['message']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if (!$isSelf): ?>
    <form class="card card-pad admin-danger" method="post" action="<?= e(url('/admin/clients/' . $client['id'] . '/supprimer')) ?>">
      <?= csrf_field() ?>
      <h2>Supprimer le compte</h2>
      <p class="lede">Suppression immédiate du client, de ses circuits et de ses invitations.</p>
      <label class="field"><span>Saisissez « supprimer »</span><input name="confirm" required></label>
      <button class="btn btn-danger" type="submit">Supprimer définitivement</button>
    </form>
  <?php endif; ?>
</section>
