<section class="dark-band band-2">
  <div style="padding:70px 44px 70px 32px;">
    <span class="eyebrow" style="color:oklch(0.75 0.12 192);">Vos données</span>
    <h1 style="color:#fff;font-size:46px;max-width:22ch;margin:14px 0;">Aucune connexion bancaire. Aucun agrégateur.</h1>
    <p>repartio ne lit pas vos comptes. Vous saisissez les montants que vous voulez répartir : un salaire, un loyer, un versement mensuel.</p>
    <div class="cta-row" style="margin-top:16px;">
      <a class="btn btn-orange" href="<?= e(url('/confidentialite')) ?>">Politique de confidentialité</a>
      <a class="btn btn-ghost" href="<?= e(url('/app/reglages')) ?>" style="color:#fff !important;border-color:oklch(0.42 0.07 265);background:transparent;">Demander une suppression</a>
    </div>
  </div>
  <div style="padding:54px 32px;background:var(--navy-soft);">
    <?php foreach ([
      ['Saisie manuelle', 'Aucun accès à vos comptes, aucun mandat DSP2.'],
      ['Hébergement', 'France, Union européenne, chiffré au repos et en transit.'],
      ['Export', 'JSON et CSV à tout moment, sans demande.'],
      ['Suppression', 'Un clic, sans période de rétention.'],
      ['Publicité', 'Aucun traceur publicitaire, aucune revente.'],
    ] as $t): ?>
      <div class="trust-row" style="border-bottom-color:oklch(0.28 0.07 265);">
        <span class="eyebrow k" style="width:128px;color:oklch(0.72 0.11 192);"><?= e($t[0]) ?></span>
        <span><?= e($t[1]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<section class="section" style="background:var(--paper);">
  <div class="section-head"><span class="eyebrow">Inventaire</span><h2>Ce que nous stockons, exactement</h2></div>
  <div class="table">
    <div class="table-row table-data" style="background:oklch(0.975 0.005 250);font-family:var(--mono);font-size:10.5px;letter-spacing:.13em;text-transform:uppercase;color:var(--faint);">
      <span>Donnée</span><span>Pourquoi</span><span>Conservation</span><span>Chiffrée</span>
    </div>
    <?php foreach ([
      ['Prénom et e-mail', 'Créer le compte et vous écrire', 'Jusqu’à suppression', 'Oui'],
      ['Mot de passe', 'Authentification', 'Hashé, jamais en clair', 'Oui'],
      ['Circuits', 'Fournir le service', 'Jusqu’à suppression', 'Oui'],
      ['Messages de contact', 'Répondre à votre demande', 'Le temps du traitement', 'Oui'],
      ['Journaux d’e-mails', 'Tracer les envois transactionnels', '90 jours', 'Oui'],
    ] as $r): ?>
      <div class="table-row table-data">
        <strong><?= e($r[0]) ?></strong><span style="color:var(--muted);"><?= e($r[1]) ?></span>
        <span class="mono"><?= e($r[2]) ?></span><span class="mono" style="color:var(--teal-ink);"><?= e($r[3]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<section class="section">
  <div class="section-head"><span class="eyebrow">Vos droits</span><h2>Quatre boutons, pas quatre courriers</h2></div>
  <div class="split cols-4">
    <?php foreach ([
      ['01', 'Accéder', 'Télécharger vos circuits depuis l’espace.', 'Mes circuits'],
      ['02', 'Corriger', 'Modifier prénom et e-mail dans le profil.', 'Mon profil'],
      ['03', 'Exporter', 'JSON et CSV, sans demander à personne.', 'Le builder'],
      ['04', 'Supprimer', 'Un clic dans les réglages, effet immédiat.', 'Réglages'],
    ] as $r): ?>
      <div style="padding:24px;">
        <span class="eyebrow"><?= e($r[0]) ?></span>
        <strong style="display:block;margin:8px 0;"><?= e($r[1]) ?></strong>
        <p style="margin:0;font-size:13.5px;color:var(--muted);"><?= e($r[2]) ?></p>
        <span class="mono" style="display:block;margin-top:10px;color:var(--orange-ink);font-size:11.5px;"><?= e($r[3]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>
