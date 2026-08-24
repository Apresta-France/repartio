<header class="app-top"><h1>Mon profil</h1></header>
<section style="padding:28px;max-width:520px;">
  <form method="post" action="<?= e(url('/app/profil')) ?>" style="display:flex;flex-direction:column;gap:14px;">
    <?= csrf_field() ?>
    <label class="field"><span>Prénom</span><input name="first_name" required value="<?= e($user['first_name']) ?>"></label>
    <label class="field"><span>E-mail</span><input type="email" name="email" required value="<?= e($user['email']) ?>"></label>
    <button class="btn btn-orange" type="submit">Enregistrer</button>
  </form>
</section>
