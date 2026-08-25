<header class="app-top"><h1>Réglages</h1></header>
<section class="app-page">
  <div class="app-page-grid">
    <div class="card card-pad">
      <span class="eyebrow">Session</span>
      <h2>Cet appareil</h2>
      <p class="lede">Déconnexion de cet appareil uniquement. Les autres sessions restent ouvertes.</p>
      <form method="post" action="<?= e(url('/deconnexion')) ?>"><?= csrf_field() ?><button class="btn btn-ghost" type="submit">Se déconnecter</button></form>
    </div>
    <div class="card card-pad admin-danger">
      <span class="eyebrow">Danger</span>
      <h2>Supprimer le compte</h2>
      <p class="lede">Suppression immédiate, sans rétention. Exportez vos circuits avant.</p>
      <form method="post" action="<?= e(url('/app/reglages/supprimer')) ?>" class="admin-form">
        <?= csrf_field() ?>
        <label class="field"><span>Saisissez « supprimer »</span><input name="confirm" required></label>
        <button class="btn btn-danger" type="submit">Supprimer définitivement</button>
      </form>
    </div>
  </div>
</section>
