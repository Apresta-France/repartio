<?php
$plan = $plan ?? [];
$create = !empty($create);
$usersOnPlan = (int) ($usersOnPlan ?? 0);
?>
<header class="app-top">
  <div>
    <h1><?= $create ? 'Nouveau forfait' : e((string) $plan['label']) ?></h1>
    <span class="eyebrow"><a href="<?= e(url('/admin/forfaits')) ?>">Forfaits</a><?= $create ? '' : ' · ' . (int) $usersOnPlan . ' client' . ($usersOnPlan > 1 ? 's' : '') ?></span>
  </div>
</header>
<section class="admin-page admin-page-narrow">
  <form class="card card-pad admin-form" method="post" action="<?= e(url($create ? '/admin/forfaits' : '/admin/forfaits/' . $plan['slug'])) ?>">
    <?= csrf_field() ?>
    <div class="fields-2">
      <label class="field">
        <span>Nom</span>
        <input name="label" required value="<?= e((string) old('label', $plan['label'] ?? '')) ?>">
      </label>
      <label class="field">
        <span>Slug</span>
        <input name="slug" <?= $create ? '' : 'disabled' ?> value="<?= e((string) old('slug', $plan['slug'] ?? '')) ?>" placeholder="ex. studio">
        <?php if (!$create): ?><span class="field-hint">Le slug ne peut plus changer : il est stocké sur chaque client.</span><?php endif; ?>
      </label>
    </div>
    <label class="field">
      <span>Accroche</span>
      <textarea name="blurb" rows="3"><?= e((string) old('blurb', $plan['blurb'] ?? '')) ?></textarea>
    </label>
    <div class="fields-2">
      <label class="field"><span>Circuits</span><input type="number" name="circuits" min="1" required value="<?= e((string) old('circuits', $plan['circuits'] ?? 1)) ?>"></label>
      <label class="field"><span>Horizon (mois)</span><input type="number" name="horizon" min="1" max="600" required value="<?= e((string) old('horizon', $plan['horizon'] ?? 24)) ?>"></label>
    </div>
    <div class="fields-2">
      <label class="field"><span>Personnes invitées</span><input type="number" name="members" min="0" required value="<?= e((string) old('members', $plan['members'] ?? 0)) ?>"></label>
      <label class="field"><span>Ordre d’affichage</span><input type="number" name="sort_order" value="<?= e((string) old('sort_order', $plan['sort_order'] ?? 0)) ?>"></label>
    </div>
    <div class="fields-2">
      <label class="field"><span>Prix mensuel HT</span><input name="price_monthly_ht" inputmode="decimal" value="<?= e((string) old('price_monthly_ht', $plan['price_monthly_ht'] ?? 0)) ?>"></label>
      <label class="field"><span>Prix annuel HT</span><input name="price_yearly_ht" inputmode="decimal" value="<?= e((string) old('price_yearly_ht', $plan['price_yearly_ht'] ?? 0)) ?>"></label>
    </div>
    <div class="fields-2">
      <label class="field"><span>Libellé du bouton</span><input name="cta_label" value="<?= e((string) old('cta_label', $plan['cta_label'] ?? '')) ?>"></label>
      <label class="field"><span>Lien du bouton</span><input name="cta_url" value="<?= e((string) old('cta_url', $plan['cta_url'] ?? '/creer-un-compte')) ?>"></label>
    </div>
    <label class="check">
      <input type="checkbox" name="featured" value="1" <?= !empty($plan['featured']) || old('featured') ? 'checked' : '' ?>>
      <span>Mettre en avant sur la page tarifs (un seul à la fois)</span>
    </label>
    <div class="admin-actions">
      <button class="btn btn-orange" type="submit"><?= $create ? 'Créer' : 'Enregistrer' ?></button>
      <a class="btn btn-ghost" href="<?= e(url('/admin/forfaits')) ?>">Retour</a>
    </div>
  </form>

  <?php if (!$create): ?>
    <form class="card card-pad admin-danger" method="post" action="<?= e(url('/admin/forfaits/' . $plan['slug'] . '/supprimer')) ?>">
      <?= csrf_field() ?>
      <h2>Supprimer ce forfait</h2>
      <p class="lede"><?= $usersOnPlan > 0
          ? 'Impossible tant que ' . $usersOnPlan . ' client' . ($usersOnPlan > 1 ? 's sont' : ' est') . ' encore rattaché' . ($usersOnPlan > 1 ? 's' : '') . '.'
          : 'Le forfait disparaîtra de /tarifs et de l’espace client.' ?></p>
      <button class="btn btn-danger" type="submit" <?= $usersOnPlan > 0 ? 'disabled' : '' ?>>Supprimer</button>
    </form>
  <?php endif; ?>
</section>
