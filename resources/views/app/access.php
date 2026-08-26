<?php
$canInvite = $memberCount < $memberLimit && $circuits !== [];
$oldCircuitIds = array_map('strval', (array) old('circuit_ids', count($circuits) === 1 ? [(string) $circuits[0]['id']] : []));
$oldPermission = (string) old('permission', 'lecture');
if (!isset(\App\Models\Access::PERMISSIONS[$oldPermission])) {
    $oldPermission = 'lecture';
}
?>
<header class="app-top">
  <div>
    <h1>Accès &amp; droits</h1>
    <span class="eyebrow"><?= (int) $memberCount ?> / <?= (int) $memberLimit ?> personnes · vous restez propriétaire</span>
  </div>
</header>
<section class="access-page">
  <p class="lede access-intro">Invitez une personne, cochez les circuits auxquels elle a accès, puis choisissez son droit. Un circuit partagé occupe un emplacement sur son forfait : elle ne pourra accepter que s’il lui reste de la place.</p>

  <div class="card access-card access-invite">
    <div>
      <span class="eyebrow">Inviter</span>
      <h2>Ajouter une personne</h2>
      <p class="lede access-invite-copy">Elle reçoit un e-mail. Sans compte, elle en crée un avec la même adresse, puis accepte.</p>
    </div>

    <?php if ($memberLimit <= 0): ?>
      <p class="lede access-invite-copy">Le plan Libre ne permet pas d’inviter quelqu’un à gérer un circuit. Le plan Complet ouvre une invitation.</p>
      <a class="btn btn-orange" href="<?= e(url('/app/forfait?raison=invitations')) ?>">Changer de forfait</a>
    <?php elseif ($circuits === []): ?>
      <?php if (\App\Models\Project::atPlanLimit($user)): ?>
        <p class="lede access-invite-copy">Votre forfait n’a plus d’emplacement pour un circuit à vous. Changez de forfait, ou libérez un emplacement, avant d’inviter.</p>
        <a class="btn btn-orange" href="<?= e(url(\App\Models\Project::planChangePath('circuits'))) ?>">Changer de forfait</a>
      <?php else: ?>
        <p class="lede access-invite-copy">Créez d’abord un circuit pour pouvoir y donner accès.</p>
        <form method="post" action="<?= e(url('/app/circuits/nouveau')) ?>"><?= csrf_field() ?><button class="btn btn-orange" type="submit">Nouveau circuit</button></form>
      <?php endif; ?>
    <?php elseif ($memberCount >= $memberLimit): ?>
      <p class="lede access-invite-copy">Limite de <?= (int) $memberLimit ?> personne<?= (int) $memberLimit > 1 ? 's' : '' ?> atteinte. Changez de forfait pour en inviter davantage, ou retirez un accès.</p>
      <a class="btn btn-orange" href="<?= e(url('/app/forfait?raison=invitations')) ?>">Changer de forfait</a>
    <?php else: ?>
      <form method="post" action="<?= e(url('/app/acces')) ?>" class="access-form">
        <?= csrf_field() ?>
        <label class="field">
          <span>Adresse e-mail</span>
          <input type="email" name="email" required placeholder="marie@exemple.fr" value="<?= e((string) old('email')) ?>">
        </label>
        <fieldset class="access-step">
          <legend>Circuits</legend>
          <p class="field-hint">Cochez ceux qu’elle pourra ouvrir.</p>
          <div class="access-grid">
            <?php foreach ($circuits as $circuit): ?>
              <label class="access-row access-row-pick">
                <input type="checkbox" name="circuit_ids[]" value="<?= (int) $circuit['id'] ?>" <?= in_array((string) $circuit['id'], $oldCircuitIds, true) ? 'checked' : '' ?>>
                <span><?= e($circuit['name']) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </fieldset>
        <fieldset class="access-step">
          <legend>Droit sur ces circuits</legend>
          <div class="access-perm">
            <div class="access-perm-options">
              <?php foreach (\App\Models\Access::PERMISSIONS as $key => $label): ?>
                <label>
                  <input type="radio" name="permission" value="<?= e($key) ?>" <?= $key === $oldPermission ? 'checked' : '' ?>>
                  <?= e($label) ?>
                </label>
              <?php endforeach; ?>
            </div>
            <?php foreach (\App\Models\Access::PERMISSIONS as $key => $label): ?>
              <p class="access-perm-hint" data-perm="<?= e($key) ?>"><?= e(\App\Models\Access::HINTS[$key]) ?></p>
            <?php endforeach; ?>
          </div>
        </fieldset>
        <button class="btn btn-orange" type="submit" <?= $canInvite ? '' : 'disabled' ?>>Envoyer l’invitation</button>
      </form>
    <?php endif; ?>
  </div>

  <p class="access-people">Personnes qui ont accès</p>
  <div class="card access-card access-owner">
    <div class="access-person-head">
      <span class="access-avatar"><?= e(initials($user['first_name'])) ?></span>
      <div>
        <strong><?= e($user['first_name']) ?> <span class="access-you">vous</span></strong>
        <span class="mono access-email"><?= e($user['email']) ?></span>
      </div>
      <span class="chip access-role">Propriétaire</span>
    </div>
    <p class="field-hint" style="margin:0;">Tous les circuits, tous les droits — y compris inviter, facturer et supprimer le compte.</p>
  </div>

  <?php if ($members): ?>
    <div class="access-list">
      <?php foreach ($members as $member):
          $name = $member['member_name'] ?: $member['email'];
          $expired = \App\Models\Access::isPendingExpired($member);
          $assigned = [];
          foreach ($member['circuits'] as $c) {
              $assigned[(int) $c['project_id']] = $c['permission'];
          }
          ?>
        <article class="card access-card">
          <div class="access-person-head">
            <span class="access-avatar"><?= e(initials((string) $name)) ?></span>
            <div>
              <strong><?= e($member['member_name'] ?: explode('@', (string) $member['email'])[0]) ?></strong>
              <span class="mono access-email"><?= e($member['email']) ?></span>
            </div>
            <span class="access-status is-<?= $member['status'] === 'active' ? 'active' : ($expired ? 'expired' : 'pending') ?>"><?= $member['status'] === 'active' ? 'Actif' : ($expired ? 'Expiré' : 'En attente') ?></span>
          </div>

          <?php if ($member['circuits']): ?>
            <div class="access-chips">
              <?php foreach ($member['circuits'] as $c): ?>
                <span class="chip"><?= e($c['project_name']) ?> · <?= e(\App\Models\Access::PERMISSIONS[$c['permission']] ?? $c['permission']) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <form method="post" action="<?= e(url('/app/acces/' . $member['id'])) ?>" class="access-form">
            <?= csrf_field() ?>
            <div class="access-grid">
              <div class="access-grid-head">
                <span>Circuit</span>
                <span>Droit</span>
              </div>
              <?php foreach ($circuits as $circuit):
                  $checked = isset($assigned[(int) $circuit['id']]);
                  $perm = $assigned[(int) $circuit['id']] ?? 'lecture';
                  $boxId = 'access-c-' . (int) $member['id'] . '-' . (int) $circuit['id'];
                  ?>
                <div class="access-row" data-access-row>
                  <input id="<?= e($boxId) ?>" type="checkbox" name="circuit_ids[]" value="<?= (int) $circuit['id'] ?>" <?= $checked ? 'checked' : '' ?> data-access-circuit>
                  <label for="<?= e($boxId) ?>"><?= e($circuit['name']) ?></label>
                  <select name="rights[<?= (int) $circuit['id'] ?>]" <?= $checked ? '' : 'disabled' ?>>
                    <?php foreach (\App\Models\Access::PERMISSIONS as $key => $label): ?>
                      <option value="<?= e($key) ?>" <?= $perm === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="access-actions">
              <button class="btn btn-navy" type="submit">Enregistrer les droits</button>
              <?php if ($member['status'] === 'pending'): ?>
                <button class="btn btn-ghost" type="submit" form="resend-<?= (int) $member['id'] ?>">Renvoyer l’invitation</button>
              <?php endif; ?>
              <button class="btn btn-ghost access-revoke" type="submit" form="revoke-<?= (int) $member['id'] ?>">Retirer</button>
            </div>
          </form>
        </article>
      <?php endforeach; ?>
    </div>
    <?php foreach ($members as $member): ?>
      <?php if ($member['status'] === 'pending'): ?>
        <form id="resend-<?= (int) $member['id'] ?>" method="post" action="<?= e(url('/app/acces/' . $member['id'] . '/renvoyer')) ?>" hidden><?= csrf_field() ?></form>
      <?php endif; ?>
      <form id="revoke-<?= (int) $member['id'] ?>" method="post" action="<?= e(url('/app/acces/' . $member['id'] . '/retirer')) ?>" hidden data-confirm-delete data-confirm-title="Retirer <?= e($member['email']) ?> ?" data-confirm-text="Cette personne ne pourra plus ouvrir les circuits qui lui étaient attribués."><?= csrf_field() ?></form>
    <?php endforeach; ?>
  <?php endif; ?>
</section>

<div class="builder-modal" data-confirm-modal hidden>
  <div class="builder-modal-backdrop" data-confirm-dismiss></div>
  <div class="builder-modal-card confirm-modal-card" role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div class="builder-modal-head">
      <div>
        <div class="eyebrow">Retirer l’accès</div>
        <h2 id="confirm-title" data-confirm-title>Retirer cette personne ?</h2>
      </div>
      <button type="button" class="btn btn-ghost builder-modal-close" data-confirm-dismiss aria-label="Fermer">×</button>
    </div>
    <p class="builder-hint" data-confirm-text>Elle ne pourra plus ouvrir les circuits qui lui étaient attribués.</p>
    <div class="confirm-actions">
      <button type="button" class="btn btn-ghost" data-confirm-dismiss>Annuler</button>
      <button type="button" class="btn btn-danger" data-confirm-ok>Retirer</button>
    </div>
  </div>
</div>
