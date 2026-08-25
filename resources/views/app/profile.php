<header class="app-top">
  <div>
    <h1>Mon profil</h1>
    <span class="eyebrow"><?= e($user['email']) ?></span>
  </div>
</header>
<section class="app-page">
  <div class="app-page-grid">
    <form class="card card-pad admin-form" method="post" action="<?= e(url('/app/profil')) ?>">
      <?= csrf_field() ?>
      <div>
        <span class="eyebrow">Identité</span>
        <h2>Vos informations</h2>
        <p class="lede">Ces données apparaissent sur votre compte et dans les invitations que vous envoyez.</p>
      </div>
      <div class="fields-2">
        <label class="field"><span>Prénom</span><input name="first_name" required value="<?= e($user['first_name']) ?>"></label>
        <label class="field"><span>E-mail</span><input type="email" name="email" required value="<?= e($user['email']) ?>"></label>
      </div>
      <button class="btn btn-orange" type="submit">Enregistrer</button>
    </form>
    <div class="card card-pad">
      <span class="eyebrow">Compte</span>
      <h2><?= e(\App\Models\Plan::label($user)) ?></h2>
      <p class="lede"><?= e(\App\Models\Plan::blurb($user)) ?></p>
      <p class="field-hint">Inscription <?= e(!empty($user['created_at']) ? date('d/m/Y', strtotime((string) $user['created_at'])) : '—') ?> · dernière visite <?= e(time_ago($user['last_login_at'] ?? null)) ?></p>
      <a class="btn btn-ghost" href="<?= e(url('/app/forfait')) ?>">Gérer le forfait</a>
    </div>
  </div>
</section>
