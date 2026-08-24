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
    <?php
    $C = [
        'revenu' => 'oklch(0.62 0.12 192)',
        'compte' => 'oklch(0.32 0.09 265)',
        'repartiteur' => 'oklch(0.68 0.18 38)',
        'livret' => 'oklch(0.48 0.11 240)',
        'depense' => 'oklch(0.55 0.16 25)',
    ];
    $nodes = [
        [20, 60, 'revenu', 'Revenu', 'Salaire Julien', [['Par mois', '1 500 €']], 400],
        [20, 200, 'revenu', 'Revenu', 'Auto-entreprise', [['Par mois', '5 000 €']], 1100],
        [20, 340, 'revenu', 'Revenu', 'Loyers du local', [['Par mois', '2 000 €']], 1800],
        [320, 120, 'compte', 'Compte', 'Compte courant', [['Reçoit', '8 500 €'], ['Reste', '0 €']], 2600],
        [320, 320, 'repartiteur', 'Répartiteur', 'Répartiteur épargne', [['Reçoit', '3 665 €'], ['Ventilé', '100 %']], 6200],
        [630, 40, 'depense', 'Dépense', 'Prélèvements', [['Reçoit', '3 254 €']], 4800],
        [630, 180, 'livret', 'Livret', 'Livret A', [['Reçoit', '1 466 €'], ['Dans 60 mois', '22 950 €']], 7600],
        [630, 350, 'livret', 'Livret', 'LDDS', [['Reçoit', '1 100 €'], ['Dans 60 mois', '12 000 €']], 8800],
        [630, 520, 'livret', 'Livret', 'LEP', [['Reçoit', '1 100 €'], ['Dans 60 mois', '10 000 €']], 10000],
    ];
    $wires = [
        ['M 252 111 C 308 111, 264 171, 320 171', $C['revenu'], 3400],
        ['M 252 251 C 308 251, 264 171, 320 171', $C['revenu'], 3800],
        ['M 252 391 C 308 391, 264 171, 320 171', $C['revenu'], 4200],
        ['M 552 171 C 608 171, 574 91, 630 91', $C['compte'], 5400],
        ['M 552 171 C 610 171, 270 366, 320 366', $C['compte'], 6800],
        ['M 552 371 C 608 371, 574 231, 630 231', $C['repartiteur'], 8200],
        ['M 552 371 C 608 371, 574 401, 630 401', $C['repartiteur'], 9400],
        ['M 552 371 C 608 371, 574 571, 630 571', $C['repartiteur'], 10600],
    ];
    $flows = [
        [386, 132, '1 500 €', 3600],
        [386, 202, '3 660 €', 4000],
        [386, 272, '2 000 €', 4400],
        [591, 122, '3 254 €', 5700],
        [591, 262, '3 665 €', 7100],
        [591, 292, '1 466 €', 8500],
        [591, 386, '1 100 €', 9700],
        [591, 462, '1 100 €', 10900],
    ];
    ?>
    <div class="auth-circuit" data-auth-circuit aria-hidden="true">
      <div class="auth-circuit-scene">
        <svg class="hero-wires auth-circuit-wires" width="920" height="720">
          <?php foreach ($wires as [$d, $stroke, $at]): ?>
            <path class="auth-circuit-wire" data-appear="<?= (int) $at ?>" pathLength="1" d="<?= e($d) ?>" fill="none" stroke="<?= e($stroke) ?>" stroke-width="1.7" stroke-linecap="round"></path>
          <?php endforeach; ?>
        </svg>
        <?php foreach ($nodes as [$x, $y, $kind, $label, $title, $rows, $at]): ?>
          <div class="hero-node auth-circuit-node" data-appear="<?= (int) $at ?>" style="left:<?= (int) $x ?>px;top:<?= (int) $y ?>px;color:<?= e($C[$kind]) ?>">
            <div class="bar" style="background:<?= e($C[$kind]) ?>"></div>
            <span class="kind" style="color:<?= e($C[$kind]) ?>"><?= e($label) ?></span>
            <div class="title"><?= e($title) ?></div>
            <?php foreach ($rows as $row): ?>
              <div class="row"><span><?= e($row[0]) ?></span><b><?= e($row[1]) ?></b></div>
            <?php endforeach; ?>
            <div style="height:11px;"></div>
            <i class="port port-in"></i>
            <i class="port port-out" style="background:<?= e($C[$kind]) ?>"></i>
          </div>
        <?php endforeach; ?>
        <?php foreach ($flows as [$x, $y, $label, $at]): ?>
          <div class="hero-flow auth-circuit-flow" data-appear="<?= (int) $at ?>" style="left:<?= (int) $x ?>px;top:<?= (int) $y ?>px;"><?= e($label) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="auth-side-copy">
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
