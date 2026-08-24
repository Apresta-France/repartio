<header class="app-top"><h1>Réglages</h1></header>
<section style="padding:28px;max-width:560px;display:flex;flex-direction:column;gap:24px;">
  <div class="card" style="padding:20px;">
    <strong>Session</strong>
    <p class="lede">Déconnexion de cet appareil.</p>
    <form method="post" action="<?= e(url('/deconnexion')) ?>"><?= csrf_field() ?><button class="btn btn-ghost" type="submit">Se déconnecter</button></form>
  </div>
  <div class="card" style="padding:20px;border-color:oklch(0.86 0.06 25);">
    <strong>Supprimer le compte</strong>
    <p class="lede">Suppression immédiate, sans rétention. Exportez vos circuits avant.</p>
    <form method="post" action="<?= e(url('/app/reglages/supprimer')) ?>" style="display:flex;flex-direction:column;gap:10px;">
      <?= csrf_field() ?>
      <label class="field"><span>Saisissez « supprimer »</span><input name="confirm" required></label>
      <button class="btn" type="submit" style="background:var(--red);color:#fff;">Supprimer définitivement</button>
    </form>
  </div>
</section>
