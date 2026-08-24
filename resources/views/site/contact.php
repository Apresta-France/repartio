<section class="section" style="padding-bottom:44px;">
  <span class="eyebrow eyebrow-live">Contact</span>
  <h1 class="page-title" style="font-size:46px;max-width:24ch;margin:12px 0;">Une vraie personne vous répond</h1>
  <p class="lede">Pas de robot de premier niveau. Vous écrivez, quelqu’un de l’équipe lit et répond — généralement le jour même, au plus tard sous 72 heures ouvrées.</p>
</section>
<section class="page-split" style="border-bottom:1px solid var(--line);">
  <div style="padding:36px 44px 44px 32px;">
    <?php if (!empty($sent)): ?>
      <div class="card" style="padding:28px;">
        <h2>Message envoyé</h2>
        <p class="lede">Nous avons bien reçu votre message. Un accusé vient d’arriver dans votre boîte.</p>
        <a class="btn btn-navy" href="<?= e(url('/')) ?>">Retour à l’accueil</a>
      </div>
    <?php else: ?>
      <form method="post" action="<?= e(url('/contact')) ?>" style="display:flex;flex-direction:column;gap:16px;max-width:560px;">
        <?= csrf_field() ?>
        <h2 style="margin:0;">Écrire à l’équipe</h2>
        <div class="chips">
          <?php foreach (['compte' => 'Compte', 'circuit' => 'Un circuit', 'facture' => 'Facturation', 'presse' => 'Presse', 'autre' => 'Autre'] as $k => $l): ?>
            <label class="chip" style="cursor:pointer;"><input type="radio" name="topic" value="<?= e($k) ?>" <?= old('topic', 'autre') === $k ? 'checked' : '' ?>> <?= e($l) ?></label>
          <?php endforeach; ?>
        </div>
        <div class="fields-2">
          <label class="field"><span>Prénom</span><input name="first_name" required value="<?= e((string) old('first_name')) ?>" placeholder="Julien"></label>
          <label class="field"><span>E-mail</span><input type="email" name="email" required value="<?= e((string) old('email')) ?>" placeholder="vous@exemple.fr"></label>
        </div>
        <label class="field"><span>Votre message</span><textarea name="message" rows="7" required placeholder="Décrivez votre situation. N’envoyez jamais de relevé bancaire."><?= e((string) old('message')) ?></textarea></label>
        <label class="check"><input type="checkbox" name="privacy" required><span>J’ai lu la <a href="<?= e(url('/confidentialite')) ?>">politique de confidentialité</a> et j’accepte que mon message soit conservé le temps du traitement.</span></label>
        <button class="btn btn-orange" type="submit">Envoyer le message</button>
      </form>
    <?php endif; ?>
  </div>
  <div style="padding:36px 32px;">
    <div class="kv">
      <div><span class="k">E-mail</span><span>bonjour@repartio.fr</span></div>
      <div><span class="k">Délai</span><span>72 h ouvrées maximum</span></div>
      <div><span class="k">Pièces</span><span>Aucune pièce jointe nécessaire</span></div>
    </div>
  </div>
</section>
