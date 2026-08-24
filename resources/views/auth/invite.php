<?php
$ownerName = $owner['first_name'] ?? 'Quelqu’un';
$circuits = $invite['circuits'] ?? [];
?>
<div class="auth-grid">
  <div class="auth-form">
    <a href="<?= e(url('/')) ?>" class="logo"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
    <div class="auth-box">
      <div>
        <span class="eyebrow" style="color:var(--orange-ink);">Invitation</span>
        <h1><?= e($ownerName) ?> vous invite</h1>
        <p class="lede">Accès aux circuits de <?= e($ownerName) ?> — uniquement ceux listés, avec le droit indiqué.</p>
      </div>
      <?php if ($circuits): ?>
        <div class="invite-circuits">
          <?php foreach ($circuits as $circuit): ?>
            <div class="invite-circuit">
              <strong><?= e($circuit['project_name']) ?></strong>
              <span class="chip"><?= e(\App\Models\Access::LABELS[$circuit['permission']] ?? $circuit['permission']) ?></span>
            </div>
            <p class="field-hint" style="margin:0 0 10px;"><?= e(\App\Models\Access::HINTS[$circuit['permission']] ?? '') ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($user && !empty($emailMatch)): ?>
        <form method="post" action="<?= e(url('/invitation/' . $token)) ?>" style="display:flex;flex-direction:column;gap:14px;">
          <?= csrf_field() ?>
          <button class="btn btn-orange" type="submit">Accepter l’accès</button>
        </form>
      <?php elseif ($user): ?>
        <p class="lede">Cette invitation est destinée à <strong><?= e($invite['email']) ?></strong>. Vous êtes connecté avec <?= e($user['email']) ?>.</p>
        <form method="post" action="<?= e(url('/deconnexion')) ?>"><?= csrf_field() ?><button class="btn btn-ghost" type="submit">Changer de compte</button></form>
      <?php else: ?>
        <p class="lede">Connectez-vous ou créez un compte avec <strong><?= e($invite['email']) ?></strong>, puis revenez sur cette page.</p>
        <div class="cta-row">
          <a class="btn btn-orange" href="<?= e(url('/connexion')) ?>">Se connecter</a>
          <a class="btn btn-ghost" href="<?= e(url('/creer-un-compte')) ?>">Créer un compte</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="auth-side">
    <div class="dots"></div>
    <div style="position:relative;margin-top:auto;max-width:460px;">
      <span class="eyebrow" style="color:oklch(0.75 0.12 192);">Lecture, édition ou gestion</span>
      <div class="kv" style="margin-top:16px;background:oklch(0.34 0.07 265);border-color:oklch(0.34 0.07 265);">
        <?php foreach ([
          ['Lecture', 'Voir le circuit, sans le modifier'],
          ['Édition', 'Changer les blocs et enregistrer'],
          ['Gestion', 'Partager, archiver, dupliquer'],
        ] as $r): ?>
          <div style="background:oklch(0.27 0.075 265);color:#fff;flex-direction:column;gap:4px;align-items:flex-start;">
            <span><?= e($r[0]) ?></span>
            <strong style="font-size:14px;font-weight:600;"><?= e($r[1]) ?></strong>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
