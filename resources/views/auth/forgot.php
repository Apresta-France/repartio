<div style="min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:34px 32px 40px;gap:40px;">
  <a href="<?= e(url('/')) ?>" style="align-self:flex-start;"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr" style="height:32px;"></a>
  <div class="card" style="width:100%;max-width:468px;padding:30px 32px;">
    <?php if (!empty($sent)): ?>
      <h1 style="font-size:25px;">Lien envoyé</h1>
      <p class="lede">Si un compte existe pour <strong><?= e($shownEmail ?: 'cette adresse') ?></strong>, un lien de réinitialisation vient d’y arriver. Il expire dans une heure.</p>
      <a class="btn btn-navy" href="<?= e(url('/connexion')) ?>">Retour à la connexion</a>
    <?php else: ?>
      <span class="eyebrow">Réinitialisation</span>
      <h1 style="font-size:27px;margin:8px 0;">Mot de passe oublié</h1>
      <p class="lede">Indiquez l’adresse de votre compte : nous vous envoyons un lien valable une heure.</p>
      <form method="post" action="<?= e(url('/mot-de-passe-oublie')) ?>" style="display:flex;flex-direction:column;gap:14px;margin-top:16px;">
        <?= csrf_field() ?>
        <label class="field"><span>Adresse e-mail du compte</span><input type="email" name="email" required placeholder="vous@exemple.fr"></label>
        <button class="btn btn-orange" type="submit">Envoyer le lien</button>
      </form>
    <?php endif; ?>
  </div>
</div>
