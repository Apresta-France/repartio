<section class="dark-band band-2 donnees-hero">
  <div class="donnees-hero-copy">
    <span class="eyebrow" style="color:oklch(0.75 0.12 192);">Vos données</span>
    <h1>Aucune connexion bancaire. Aucun agrégateur.</h1>
    <p class="lede">repartio ne lit pas vos comptes. Vous saisissez les montants que vous voulez répartir&nbsp;: un salaire, un loyer, un versement mensuel. C’est une contrainte assumée — elle permet de modéliser des situations qui n’existent pas encore, et elle nous évite de détenir votre historique de transactions.</p>
    <div class="chips donnees-hero-meta">
      <span class="chip">À jour au 24 août 2026</span>
      <span class="chip">RGPD</span>
      <span class="chip">Union européenne</span>
    </div>
    <div class="cta-row">
      <a class="btn btn-orange" href="<?= e(url('/confidentialite')) ?>">Politique de confidentialité</a>
      <a class="btn btn-ghost donnees-hero-ghost" href="<?= e(url('/app/reglages')) ?>">Demander une suppression</a>
    </div>
  </div>
  <div class="donnees-hero-facts">
    <?php foreach ([
      ['Saisie manuelle', 'Aucun accès à vos comptes, aucun mandat DSP2, aucun agrégateur tiers.'],
      ['Hébergement', 'Serveurs en France, Union européenne, chiffrés au repos et en transit.'],
      ['Aucune revente', 'Vos circuits ne sont ni vendus, ni partagés, ni utilisés pour du ciblage.'],
      ['Export', 'JSON et CSV disponibles à tout moment, sans demande ni délai.'],
      ['Suppression', 'Un clic supprime le compte et les circuits, sans période de rétention.'],
    ] as $t): ?>
      <div class="trust-row">
        <span class="eyebrow k"><?= e($t[0]) ?></span>
        <span><?= e($t[1]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<nav class="donnees-jump" aria-label="Aller à une rubrique">
  <a class="chip" href="#inventaire">Inventaire</a>
  <a class="chip" href="#droits">Vos droits</a>
  <a class="chip" href="#hebergement">Hébergement</a>
  <a class="chip" href="#questions">Questions</a>
</nav>

<section class="page-split" style="border-bottom:1px solid var(--line);">
  <div class="donnees-never-copy">
    <span class="eyebrow">Principe</span>
    <h2>Ce que nous ne détenons pas</h2>
    <p class="lede">Pas de banque à autoriser, pas de mandat à révoquer, pas de bannière de cookies. Le service ne voit que ce que vous tapez.</p>
  </div>
  <div class="donnees-never-list">
    <?php foreach ([
      'Aucun IBAN, numéro de carte ou identifiant bancaire.',
      'Aucun historique de transactions, aucun relevé importé.',
      'Aucun mandat DSP2, aucun agrégateur de comptes.',
      'Aucun cookie publicitaire, aucun pixel de régie.',
      'Aucune revente, aucun courtier, aucun enrichissement externe.',
      'Aucun transfert des données hors de l’Union européenne.',
    ] as $item): ?>
      <div><span aria-hidden="true">—</span><span><?= e($item) ?></span></div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="inventaire" style="background:var(--paper);">
  <div class="section-head">
    <span class="eyebrow">Inventaire</span>
    <h2>Ce que nous stockons, exactement</h2>
  </div>
  <p class="lede donnees-lede">La liste complète des données présentes sur nos serveurs, la raison pour laquelle elles y sont, et la durée pendant laquelle elles y restent.</p>
  <div class="table donnees-table">
    <div class="table-row table-data table-head">
      <span>Donnée</span><span>Pourquoi</span><span>Conservation</span><span>Chiffrée</span>
    </div>
    <?php foreach ([
      ['Prénom et e-mail', 'Créer le compte, envoyer les liens de connexion et les factures.', 'Compte actif', 'Oui'],
      ['Mot de passe', 'Authentification — jamais stocké en clair.', 'Compte actif', 'Oui'],
      ['Structure des circuits', 'Les blocs, les fils et leurs montants : le cœur du service.', 'Compte actif', 'Oui'],
      ['Montants saisis', 'Calculer le mois type et la projection.', 'Compte actif', 'Oui'],
      ['Historique de versions', 'Restaurer un circuit à un état antérieur, plans payants.', '24 mois', 'Oui'],
      ['Journal de connexion', 'Détecter les accès anormaux et sécuriser le compte.', '12 mois', 'Oui'],
      ['Messages de contact', 'Répondre à votre demande.', 'Le temps du traitement', 'Oui'],
      ['Mesure d’audience', 'Compter les pages vues, sans cookie ni identifiant publicitaire.', '6 mois, agrégée', 'Agrégée'],
      ['Journaux d’e-mails', 'Tracer les envois transactionnels (connexion, facture).', '90 jours', 'Oui'],
      ['Données de facturation', 'Obligations comptables et fiscales. Aucune carte chez nous.', '10 ans', 'Oui'],
    ] as $r): ?>
      <div class="table-row table-data">
        <strong><?= e($r[0]) ?></strong>
        <span class="donnees-why"><?= e($r[1]) ?></span>
        <span class="mono" data-label="Conservation"><?= e($r[2]) ?></span>
        <span class="mono <?= $r[3] === 'Oui' ? 'donnees-yes' : 'donnees-soft' ?>" data-label="Chiffrée"><?= e($r[3]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="droits">
  <div class="section-head">
    <span class="eyebrow">Vos droits</span>
    <h2>Quatre boutons, pas quatre courriers</h2>
  </div>
  <p class="lede donnees-lede">La plupart des droits RGPD s’exercent directement dans l’application. Pour une demande formelle, <a href="<?= e(url('/contact')) ?>">écrivez-nous</a>.</p>
  <div class="split cols-4">
    <?php foreach ([
      ['D01', 'Accès', 'La totalité de vos données est visible dans l’application, sans passer par nous.', 'Réglages → Mon compte', '/app/profil'],
      ['D02', 'Portabilité', 'Export JSON et CSV immédiat, format documenté et réimportable.', 'Réglages → Exporter', '/app'],
      ['D03', 'Rectification', 'Tout est modifiable directement : montants, blocs, e-mail de connexion.', 'Dans le builder', '/app'],
      ['D04', 'Effacement', 'Suppression définitive du compte et des circuits, sans période de rétention.', 'Réglages → Supprimer', '/app/reglages'],
    ] as $r): ?>
      <a class="donnees-right" href="<?= e(url($r[4])) ?>">
        <span class="eyebrow"><?= e($r[0]) ?></span>
        <strong><?= e($r[1]) ?></strong>
        <p><?= e($r[2]) ?></p>
        <span class="mono"><?= e($r[3]) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="hebergement" style="background:var(--paper);">
  <div class="section-head">
    <span class="eyebrow">Sous-traitants</span>
    <h2>Hébergement et prestataires</h2>
  </div>
  <p class="lede donnees-lede">Les données restent dans l’Union européenne. Les sous-traitants sont limités au strict nécessaire, encadrés par l’article 28 du RGPD.</p>
  <div class="donnees-vendors">
    <?php foreach ([
      ['Hébergement', 'Infrastructure européenne', 'Base de données et application hébergées dans un centre de données situé en France.', 'Paris, FR'],
      ['Paiement', 'Prestataire PCI-DSS', 'Aucune donnée de carte ne transite par nos serveurs : nous ne voyons qu’un identifiant d’abonnement.', 'Union européenne'],
      ['E-mails', 'Envoi transactionnel', 'Liens de connexion et factures uniquement. Pas de liste marketing par défaut.', 'Union européenne'],
      ['Audience', 'ReInvent Analytics', 'Mesure d’usage sans cookie ni identifiant publicitaire, opérée par ReInvent.', 'Union européenne'],
    ] as $v): ?>
      <div class="card card-pad donnees-vendor">
        <span class="eyebrow"><?= e($v[0]) ?></span>
        <strong><?= e($v[1]) ?></strong>
        <p><?= e($v[2]) ?></p>
        <span class="mono"><?= e($v[3]) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="questions">
  <div class="section-head">
    <span class="eyebrow">Questions</span>
    <h2>Ce qu’on nous demande sur les données</h2>
  </div>
  <div class="card">
    <?php foreach ([
      ['Faut-il connecter ma banque ?', 'Non, et ce n’est pas prévu. repartio fonctionne uniquement avec les montants que vous saisissez : un salaire, un loyer, un versement mensuel.'],
      ['Où sont hébergées mes données ?', 'Dans un centre de données situé en France, au sein de l’Union européenne. Aucun transfert hors UE.'],
      ['Vendez-vous les données ?', 'Jamais. Pas de revente, pas de courtier, pas de ciblage publicitaire, pas de croisement entre utilisateurs.'],
      ['Comment supprimer définitivement mon compte ?', 'Depuis les réglages, en un clic. La suppression est immédiate et sans période de rétention. Exportez vos circuits avant.'],
      ['Y a-t-il une bannière de cookies ?', 'Non. Un cookie de session suffit à vous maintenir connecté, et la mesure d’audience ReInvent n’utilise ni cookie ni identifiant publicitaire.'],
    ] as $q): ?>
      <div class="faq-item">
        <button type="button" data-faq><span style="flex:1;"><?= e($q[0]) ?></span><span class="sign">+</span></button>
        <p><?= e($q[1]) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" style="padding-top:40px;">
  <div class="legal-note">
    <p>Une question, une demande d’accès formelle, ou une réclamation ? Écrivez à <a href="mailto:bonjour@repartio.fr">bonjour@repartio.fr</a> — nous répondons sous 72 heures ouvrées. Vous pouvez aussi saisir la CNIL après une demande préalable auprès de nous.</p>
    <div class="cta-row">
      <a class="btn btn-navy" href="<?= e(url('/contact')) ?>">Nous contacter</a>
      <a class="btn btn-ghost" href="<?= e(url('/confidentialite')) ?>">Lire la politique complète</a>
    </div>
  </div>
</section>
