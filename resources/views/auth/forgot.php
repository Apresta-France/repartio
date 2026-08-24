<div style="min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:34px 32px 40px;gap:40px;">
  <a href="<?= e(url('/')) ?>" class="logo" style="align-self:flex-start;"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
  <div class="card card-pad" style="width:100%;max-width:468px;">
    <?php if (!empty($sent)): ?>
      <h1>Lien envoyé</h1>
      <p class="lede">Si un compte existe pour <strong><?= e($shownEmail ?: 'cette adresse') ?></strong>, un lien de réinitialisation vient d’y arriver. Il expire dans une heure.</p>
      <a class="btn btn-navy" href="<?= e(url('/connexion')) ?>">Retour à la connexion</a>
    <?php else: ?>
      <span class="eyebrow">Réinitialisation</span>
      <h1>Mot de passe oublié</h1>
      <p class="lede">Indiquez l’adresse de votre compte : nous vous envoyons un lien valable une heure.</p>
      <form method="post" action="<?= e(url('/mot-de-passe-oublie')) ?>" style="display:flex;flex-direction:column;gap:14px;width:100%;">
        <?= csrf_field() ?>
        <label class="field"><span>Adresse e-mail du compte</span><input type="email" name="email" required placeholder="vous@exemple.fr"></label>
        <button class="btn btn-orange" type="submit">Envoyer le lien</button>
      </form>
    <?php endif; ?>
  </div>
</div>
