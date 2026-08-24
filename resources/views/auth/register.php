<div class="auth-grid">
  <div class="auth-form">
    <a href="<?= e(url('/')) ?>"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr" style="height:32px;"></a>
    <div class="auth-box">
      <div>
        <span class="eyebrow" style="color:var(--orange-ink);">Gratuit · sans carte</span>
        <h1>Créer votre compte</h1>
        <p class="lede">Trois circuits, tous les types de blocs, projection à 60 mois. Aucun moyen de paiement demandé.</p>
      </div>
      <form method="post" action="<?= e(url('/creer-un-compte')) ?>" style="display:flex;flex-direction:column;gap:14px;">
        <?= csrf_field() ?>
        <label class="field"><span>Prénom</span><input name="first_name" required value="<?= e((string) old('first_name')) ?>" placeholder="Julien"></label>
        <label class="field"><span>Adresse e-mail</span><input type="email" name="email" required value="<?= e((string) old('email')) ?>" placeholder="vous@exemple.fr"></label>
        <label class="field">
          <span>Mot de passe</span>
          <input type="password" name="password" required data-password placeholder="12 caractères minimum">
          <div class="pwd-bars"><i data-pwd-bar></i><i data-pwd-bar></i><i data-pwd-bar></i><i data-pwd-bar></i></div>
          <span class="mono" style="font-size:11px;color:var(--faint);" data-pwd-check data-label="12 caractères minimum">· 12 caractères minimum</span>
          <span class="mono" style="font-size:11px;color:var(--faint);" data-pwd-check data-label="une majuscule et une minuscule">· une majuscule et une minuscule</span>
          <span class="mono" style="font-size:11px;color:var(--faint);" data-pwd-check data-label="un chiffre ou un symbole">· un chiffre ou un symbole</span>
        </label>
        <label class="check"><input type="checkbox" name="terms" required><span>J’accepte les <a href="<?= e(url('/cgu')) ?>">conditions d’utilisation</a> et la <a href="<?= e(url('/confidentialite')) ?>">politique de confidentialité</a>.</span></label>
        <button class="btn btn-orange" type="submit">Créer mon compte</button>
      </form>
      <span style="font-size:13.5px;color:var(--muted);">Déjà un compte ? <a href="<?= e(url('/connexion')) ?>" style="font-weight:700;">Se connecter</a></span>
    </div>
  </div>
  <div class="auth-side">
    <div class="dots"></div>
    <div style="position:relative;margin:auto 0;max-width:470px;">
      <h2 style="color:#fff;font-size:27px;">Ce que vous aurez fait dans dix minutes</h2>
      <?php foreach ([['01','Vos revenus posés','Salaires, auto-entreprise, loyers perçus, allocations.'],['02','Vos comptes câblés','Comptes personnels, joints, répartiteurs d’épargne.'],['03','Votre première projection','Ce que vous aurez dans cinq ans, saturation des livrets.']] as $s): ?>
        <div style="display:flex;gap:14px;margin:14px 0;">
          <span class="mono" style="width:26px;height:26px;display:grid;place-items:center;border-radius:8px;background:oklch(0.32 0.07 265);color:oklch(0.78 0.11 192);"><?= e($s[0]) ?></span>
          <div><strong style="color:#fff;"><?= e($s[1]) ?></strong><div style="color:oklch(0.79 0.03 255);font-size:13.5px;"><?= e($s[2]) ?></div></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
