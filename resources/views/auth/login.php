<div class="auth-grid">
  <div class="auth-form">
    <a href="<?= e(url('/')) ?>" class="logo"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
    <div class="auth-box">
      <div>
        <h1>Content de vous revoir</h1>
        <p class="lede">Vos circuits vous attendent, exactement là où vous les avez laissés.</p>
      </div>
      <form method="post" action="<?= e(url('/connexion')) ?>" style="display:flex;flex-direction:column;gap:14px;">
        <?= csrf_field() ?>
        <label class="field"><span>Adresse e-mail</span><input type="email" name="email" required value="<?= e((string) old('email')) ?>" placeholder="vous@exemple.fr"></label>
        <label class="field">
          <span style="display:flex;">Mot de passe <a href="<?= e(url('/mot-de-passe-oublie')) ?>" style="margin-left:auto;font-size:12px;">Oublié ?</a></span>
          <input type="password" name="password" required placeholder="••••••••••">
        </label>
        <label class="check"><input type="checkbox" name="remember" checked> Rester connecté 30 jours</label>
        <button class="btn btn-orange" type="submit">Se connecter</button>
      </form>
      <div class="auth-sep"><i></i><span>ou</span><i></i></div>
      <form method="post" action="<?= e(url('/connexion/lien')) ?>" style="display:flex;flex-direction:column;gap:10px;">
        <?= csrf_field() ?>
        <input type="email" name="email" required value="<?= e((string) old('email')) ?>" placeholder="vous@exemple.fr" style="padding:12px 14px;border-radius:10px;border:1px solid oklch(0.89 0.014 255);">
        <button class="btn btn-ghost" type="submit">Recevoir un lien de connexion par e-mail</button>
      </form>
      <span style="font-size:13.5px;color:var(--muted);">Pas encore de compte ? <a href="<?= e(url('/creer-un-compte')) ?>" style="font-weight:700;">Créer un compte gratuit</a></span>
    </div>
    <span style="font-size:12px;color:var(--faint);">repartio est un outil de simulation, pas un conseil en investissement.</span>
  </div>
  <div class="auth-side">
    <div class="dots"></div>
    <div style="position:relative;margin-top:auto;max-width:460px;">
      <span class="eyebrow" style="color:oklch(0.75 0.12 192);">Votre circuit, hier soir</span>
      <div class="kv" style="margin-top:16px;background:oklch(0.34 0.07 265);border-color:oklch(0.34 0.07 265);">
        <?php foreach ([['Entrées du mois','12 338 €'],['Épargné','5 234 €'],['Non affecté','0 €'],['Patrimoine à 60 mois','105 615 €']] as $r): ?>
          <div style="background:oklch(0.27 0.075 265);color:#fff;"><span><?= e($r[0]) ?></span><strong class="mono" style="margin-left:auto;"><?= e($r[1]) ?></strong></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
