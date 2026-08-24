<div style="min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:34px 32px;">
  <a href="<?= e(url('/')) ?>" class="logo" style="align-self:flex-start;"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr"></a>
  <div class="card card-pad" style="width:100%;max-width:468px;margin:auto;">
    <h1>Nouveau mot de passe</h1>
    <form method="post" action="<?= e(url('/reinitialiser-mot-de-passe/' . $token)) ?>" style="display:flex;flex-direction:column;gap:14px;width:100%;">
      <?= csrf_field() ?>
      <label class="field"><span>Mot de passe</span><input type="password" name="password" required minlength="12" data-password></label>
      <button class="btn btn-orange" type="submit">Enregistrer</button>
    </form>
  </div>
</div>
