<div class="install">
  <a href="<?= e(url('/')) ?>"><img src="<?= e(asset('img/logo.png')) ?>" alt="repartio.fr" style="height:36px;margin-bottom:24px;"></a>
  <div class="install-card">
    <span class="eyebrow">Première installation</span>
    <h1 style="margin:0;font-size:28px;">Installer repartio</h1>
    <p class="lede">Ce formulaire n’apparaît qu’une fois. Il crée la base, lance les migrations et votre compte administrateur.</p>
    <?php if ($msg = flash('error')): ?><div class="flash flash-error" style="margin:0;"><?= e($msg) ?></div><?php endif; ?>
    <div class="kv">
      <?php foreach ($checks as $c): ?>
        <div><span><?= e($c[0]) ?></span><strong class="<?= $c[1] ? 'check-ok' : 'check-ko' ?>"><?= $c[1] ? 'OK' : 'Manquant' ?></strong></div>
      <?php endforeach; ?>
    </div>
    <form method="post" action="<?= e(url('/install')) ?>" style="display:flex;flex-direction:column;gap:14px;">
      <?= csrf_field() ?>
      <h2 style="font-size:18px;margin:8px 0 0;">Site</h2>
      <label class="field"><span>URL publique</span><input name="app_url" required value="<?= e($defaults['app_url']) ?>"></label>
      <h2 style="font-size:18px;margin:8px 0 0;">Base de données</h2>
      <div style="display:grid;grid-template-columns:1fr 100px;gap:10px;">
        <label class="field"><span>Hôte</span><input name="db_host" required value="<?= e($defaults['db_host']) ?>"></label>
        <label class="field"><span>Port</span><input name="db_port" required value="<?= e((string) $defaults['db_port']) ?>"></label>
      </div>
      <label class="field"><span>Nom de la base</span><input name="db_name" required value="<?= e($defaults['db_name']) ?>"></label>
      <label class="field"><span>Utilisateur</span><input name="db_user" required value="<?= e($defaults['db_user']) ?>"></label>
      <label class="field"><span>Mot de passe</span><input type="password" name="db_pass" value="<?= e($defaults['db_pass']) ?>"></label>
      <h2 style="font-size:18px;margin:8px 0 0;">E-mail</h2>
      <label class="field"><span>Moteur</span>
        <select name="mail_driver">
          <option value="file" <?= $defaults['mail_driver'] === 'file' ? 'selected' : '' ?>>Fichier (local)</option>
          <option value="smtp">SMTP</option>
          <option value="mail">mail() PHP</option>
        </select>
      </label>
      <label class="field"><span>Hôte SMTP</span><input name="mail_host" value="<?= e($defaults['mail_host']) ?>"></label>
      <label class="field"><span>Port SMTP</span><input name="mail_port" value="<?= e((string) $defaults['mail_port']) ?>"></label>
      <label class="field"><span>Utilisateur SMTP</span><input name="mail_user"></label>
      <label class="field"><span>Mot de passe SMTP</span><input type="password" name="mail_pass"></label>
      <input type="hidden" name="mail_encryption" value="tls">
      <label class="field"><span>Expéditeur</span><input name="mail_from" value="<?= e($defaults['mail_from']) ?>"></label>
      <h2 style="font-size:18px;margin:8px 0 0;">Compte administrateur</h2>
      <label class="field"><span>Prénom</span><input name="first_name" required placeholder="Julien"></label>
      <label class="field"><span>E-mail</span><input type="email" name="email" required placeholder="vous@repartio.fr"></label>
      <label class="field"><span>Mot de passe (12 caractères min.)</span><input type="password" name="password" required minlength="12"></label>
      <button class="btn btn-orange" type="submit">Installer la plateforme</button>
    </form>
  </div>
</div>
