<?php
$widget = $widget ?? '';
?>
<?php if ($widget === 'couple'): ?>
  <div class="lab" data-lab="couple">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Et si l’auto-entreprise baisse ?</strong>
      <p>Le salaire A, les loyers et les allocations restent fixes. Seul le CA de l’AE bouge. Regardez quelle enveloppe se réduit en premier.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Chiffre d’affaires AE</span><b data-out="ae">1 800 €</b></div>
      <input type="range" min="0" max="3000" step="50" value="1800" data-in="ae">
    </div>
    <div class="lab-flow" data-out="flow"></div>
    <div class="lab-kpis">
      <div><span>Net après URSSAF</span><strong data-out="net">1 420 €</strong></div>
      <div><span>Épargne A</span><strong data-out="save">460 €</strong></div>
      <div><span>Ce qui s’arrête</span><strong data-out="cut">Rien — circuit tenu</strong></div>
    </div>
    <p class="lab-foot" data-out="note">À 1 800 €, le compte A verse 1 260 € vers Factures, 1 560 € vers Quotidien, et tout le reste vers son épargne.</p>
  </div>

<?php elseif ($widget === 'tableur'): ?>
  <div class="lab" data-lab="tableur">
    <div class="lab-head">
      <span class="eyebrow">Démo</span>
      <strong>Ajoutez des comptes. Voyez ce qui casse.</strong>
      <p>À gauche, la feuille continue de totaliser. À droite, le circuit doit nommer chaque chemin.</p>
    </div>
    <div class="lab-steps" role="tablist">
      <button type="button" class="chip active" data-step="0">1 salaire</button>
      <button type="button" class="chip" data-step="1">+ auto-entreprise</button>
      <button type="button" class="chip" data-step="2">+ 2ᵉ joint</button>
      <button type="button" class="chip" data-step="3">+ livret enfant</button>
    </div>
    <div class="lab-split">
      <div>
        <span class="eyebrow">Tableur</span>
        <div class="lab-sheet" data-out="sheet"></div>
      </div>
      <div>
        <span class="eyebrow">Circuit</span>
        <div class="lab-nodes" data-out="nodes"></div>
      </div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'ordre'): ?>
  <div class="lab" data-lab="ordre">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Votre ordre de remplissage</strong>
      <p>LEP d’abord s’il est ouvert, puis LDDS, puis Livret A. Les soldes de départ sont ceux d’un foyer déjà en route.</p>
    </div>
    <label class="lab-check">
      <input type="checkbox" data-in="lep" checked>
      <span>Éligible au LEP</span>
    </label>
    <div class="lab-field">
      <div class="lab-field-top"><span>Épargne mensuelle</span><b data-out="save">1 500 €</b></div>
      <input type="range" min="200" max="4000" step="50" value="1500" data-in="save">
    </div>
    <div class="lab-stack" data-out="stack"></div>
    <div class="lab-kpis">
      <div><span>LEP plein dans</span><strong data-out="lep-m">—</strong></div>
      <div><span>LDDS plein dans</span><strong data-out="ldds-m">—</strong></div>
      <div><span>Livret A plein dans</span><strong data-out="la-m">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'joints'): ?>
  <div class="lab" data-lab="joints">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Deux enveloppes, ou une seule bagarre</strong>
      <p>Réglez le montant des prélèvements et l’enveloppe quotidienne. Le simulateur compare un joint unique et deux joints séparés.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Prélèvements du mois</span><b data-out="bills">2 400 €</b></div>
      <input type="range" min="800" max="4500" step="50" value="2400" data-in="bills">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Enveloppe quotidien</span><b data-out="daily">1 800 €</b></div>
      <input type="range" min="400" max="3500" step="50" value="1800" data-in="daily">
    </div>
    <div class="lab-split">
      <div class="lab-envelope">
        <span class="eyebrow">Un seul joint</span>
        <div class="lab-bar"><i data-out="mix-bills"></i><i data-out="mix-daily"></i></div>
        <p data-out="mix-note"></p>
      </div>
      <div class="lab-envelope">
        <span class="eyebrow">Deux joints</span>
        <div class="lab-bar is-split"><i data-out="sep-bills"></i></div>
        <div class="lab-bar is-split"><i data-out="sep-daily"></i></div>
        <p data-out="sep-note"></p>
      </div>
    </div>
  </div>

<?php elseif ($widget === 'urssaf'): ?>
  <div class="lab" data-lab="urssaf">
    <div class="lab-head">
      <span class="eyebrow">Simulateur · barème 2026</span>
      <strong>La provision mensuelle, pas la facture de mars</strong>
      <p>Régime, CFP, ACRE, versement libératoire. Le circuit pose ce total comme une dépense chaque mois.</p>
    </div>
    <div class="chips lab-regimes">
      <button type="button" class="chip active" data-regime="bic">Services BIC · 21,2 %</button>
      <button type="button" class="chip" data-regime="bnc">BNC · 25,6 %</button>
      <button type="button" class="chip" data-regime="cipav">CIPAV · 23,2 %</button>
      <button type="button" class="chip" data-regime="vente">Vente · 12,3 %</button>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>CA mensuel moyen</span><b data-out="ca">5 000 €</b></div>
      <input type="range" min="500" max="12000" step="100" value="5000" data-in="ca">
    </div>
    <div class="chips">
      <button type="button" class="chip active" data-acre="0">Sans ACRE</button>
      <button type="button" class="chip" data-acre="50">ACRE 50 % <span class="lab-chip-hint">avant juil. 2026</span></button>
      <button type="button" class="chip" data-acre="25">ACRE 25 % <span class="lab-chip-hint">depuis juil. 2026</span></button>
    </div>
    <div class="lab-opts">
      <label class="lab-check"><input type="checkbox" data-in="cfp" checked><span>Inclure la CFP</span></label>
      <label class="lab-check"><input type="checkbox" data-in="artisan"><span>Artisan (CFP 0,3 %)</span></label>
      <label class="lab-check"><input type="checkbox" data-in="vl"><span>Versement libératoire</span></label>
    </div>
    <div class="lab-bar is-tall"><i data-out="tax-bar"></i><em></em></div>
    <div class="lab-kpis">
      <div><span>Taux global</span><strong data-out="rate">—</strong></div>
      <div><span>Provision / mois</span><strong data-out="month">—</strong></div>
      <div><span>Net câblable</span><strong class="is-teal" data-out="net">—</strong></div>
    </div>
    <div class="lab-split">
      <div class="lab-col" data-out="break"></div>
      <div class="lab-col" data-out="year"></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'plafond'): ?>
  <div class="lab" data-lab="plafond">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Le livret se remplit — puis il déborde</strong>
      <p>Choisissez le livret, son solde actuel et le versement. La courbe montre le stock, le plafond, et le surplus qui doit partir ailleurs.</p>
    </div>
    <div class="chips">
      <button type="button" class="chip active" data-livret="la">Livret A</button>
      <button type="button" class="chip" data-livret="ldds">LDDS</button>
      <button type="button" class="chip" data-livret="lep">LEP</button>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Solde actuel</span><b data-out="start">4 000 €</b></div>
      <input type="range" min="0" max="22950" step="50" value="4000" data-in="start">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Versement mensuel</span><b data-out="pay">400 €</b></div>
      <input type="range" min="50" max="2000" step="25" value="400" data-in="pay">
    </div>
    <div class="lab-chart" data-out="chart" role="img" aria-label="Projection du solde sur 60 mois"></div>
    <div class="lab-kpis">
      <div><span>Plafond atteint</span><strong data-out="when">—</strong></div>
      <div><span>Surplus ensuite</span><strong data-out="overflow">—</strong></div>
      <div><span>Intérêts encore, à 60 mois</span><strong data-out="interest">—</strong></div>
    </div>
  </div>

<?php elseif ($widget === 'mix'): ?>
  <div class="lab" data-lab="mix">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Mois moyen, puis mois bas</strong>
      <p>Les factures restent en fixe. L’épargne est soit un fixe, soit un pourcentage du reste. Basculez le mois bas pour voir ce qui casse.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Revenu du mois</span><b data-out="income">3 400 €</b></div>
      <input type="range" min="1600" max="7000" step="50" value="3400" data-in="income">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Factures fixes</span><b data-out="fixed">2 100 €</b></div>
      <input type="range" min="800" max="4000" step="50" value="2100" data-in="fixed">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Épargne (fixe ou %)</span><b data-out="save">400 €</b></div>
      <input type="range" min="50" max="1200" step="25" value="400" data-in="save">
    </div>
    <label class="lab-check">
      <input type="checkbox" data-in="shock">
      <span>Mois bas : revenu −20 %</span>
    </label>
    <div class="lab-split">
      <div class="lab-col" data-out="col-fix"></div>
      <div class="lab-col" data-out="col-pct"></div>
    </div>
  </div>

<?php elseif ($widget === 'reste'): ?>
  <div class="lab" data-lab="reste">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Une augmentation, deux câblages</strong>
      <p>Le salaire monte. À gauche, trois fils fixes : le surplus reste orphelin. À droite, un fil « tout le reste » l’emporte vers l’épargne.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Augmentation mensuelle</span><b data-out="raise">180 €</b></div>
      <input type="range" min="0" max="800" step="10" value="180" data-in="raise">
    </div>
    <div class="lab-split">
      <div class="lab-col" data-out="left"></div>
      <div class="lab-col" data-out="right"></div>
    </div>
  </div>

<?php elseif ($widget === 'famille'): ?>
  <div class="lab" data-lab="famille">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Découpez le surplus du foyer</strong>
      <p>Après factures et quotidien, il reste une enveloppe. Répartissez-la entre les enfants, la précaution et l’apport — et lisez la date d’atteinte.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Surplus mensuel</span><b data-out="pot">900 €</b></div>
      <input type="range" min="200" max="2500" step="25" value="900" data-in="pot">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Par enfant (× 2)</span><b data-out="kids">55 €</b></div>
      <input type="range" min="0" max="200" step="5" value="55" data-in="kids">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Précaution / mois</span><b data-out="buffer">250 €</b></div>
      <input type="range" min="0" max="800" step="25" value="250" data-in="buffer">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Objectif apport</span><b data-out="target">40 000 €</b></div>
      <input type="range" min="10000" max="80000" step="1000" value="40000" data-in="target">
    </div>
    <div class="lab-stack" data-out="stack"></div>
    <div class="lab-kpis">
      <div><span>Vers l’apport</span><strong data-out="apport">—</strong></div>
      <div><span>Apport atteint</span><strong data-out="when">—</strong></div>
      <div><span>Livrets enfants pleins</span><strong data-out="kids-when">—</strong></div>
    </div>
  </div>

<?php elseif ($widget === 'scenarios'): ?>
  <div class="lab" data-lab="scenarios">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>400 €, deux ordres, un horizon</strong>
      <p>Même versement. Variante A : LEP puis Livret A. Variante B : Livret A seulement. L’écart naît du taux, puis se referme aux plafonds.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Versement mensuel</span><b data-out="pay">400 €</b></div>
      <input type="range" min="100" max="1200" step="25" value="400" data-in="pay">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Horizon</span><b data-out="months">60 mois</b></div>
      <input type="range" min="12" max="120" step="6" value="60" data-in="months">
    </div>
    <div class="lab-compare">
      <div>
        <span class="eyebrow">A · LEP d’abord</span>
        <div class="lab-meter"><i data-out="a-bar"></i></div>
        <strong class="mono" data-out="a-tot">—</strong>
      </div>
      <div>
        <span class="eyebrow">B · Livret A seul</span>
        <div class="lab-meter"><i data-out="b-bar"></i></div>
        <strong class="mono" data-out="b-tot">—</strong>
      </div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'taux'): ?>
  <div class="lab" data-lab="taux">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>En combien de mois ce livret sature-t-il ?</strong>
      <p>Cliquez un barème, réglez le versement et le solde. Le moteur utilise exactement ces plafonds et ces taux.</p>
    </div>
    <div class="lab-livrets" data-out="cards"></div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Solde actuel</span><b data-out="start">0 €</b></div>
      <input type="range" min="0" max="22950" step="50" value="0" data-in="start">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Versement mensuel</span><b data-out="pay">300 €</b></div>
      <input type="range" min="20" max="2000" step="20" value="300" data-in="pay">
    </div>
    <div class="lab-kpis">
      <div><span>Capacité restante</span><strong data-out="room">—</strong></div>
      <div><span>Saturation</span><strong data-out="when">—</strong></div>
      <div><span>Intérêts / an au plafond</span><strong data-out="yield">—</strong></div>
    </div>
  </div>

<?php elseif ($widget === 'repart'): ?>
  <div class="lab" data-lab="repart">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Trois parts, un total qui doit faire 100 %</strong>
      <p>Le répartiteur reçoit un flux, le découpe, et ne garde rien. Si la somme n’est pas ronde, le moteur le signale.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Entrée du répartiteur</span><b data-out="input">1 800 €</b></div>
      <input type="range" min="200" max="6000" step="50" value="1800" data-in="input">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>LEP</span><b data-out="p1">40 %</b></div>
      <input type="range" min="0" max="100" step="1" value="40" data-in="p1">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>LDDS</span><b data-out="p2">30 %</b></div>
      <input type="range" min="0" max="100" step="1" value="30" data-in="p2">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Livret A (reste)</span><b data-out="p3">30 %</b></div>
      <input type="range" min="0" max="100" step="1" value="30" data-in="p3">
    </div>
    <div class="lab-bar is-tall" data-out="bar"></div>
    <div class="lab-kpis">
      <div><span>Somme des parts</span><strong data-out="sum">100 %</strong></div>
      <div><span>LEP reçoit</span><strong data-out="a1">—</strong></div>
      <div><span>Livret A reçoit</span><strong data-out="a3">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'migrate'): ?>
  <div class="lab" data-lab="migrate">
    <div class="lab-head">
      <span class="eyebrow">Atelier</span>
      <strong>Quatre passes, une feuille à traduire</strong>
      <p>Cochez chaque passe. Le compteur descend : c’est le « non affecté » d’une feuille type à 4 200 € d’entrées.</p>
    </div>
    <div class="lab-kpis">
      <div><span>Non affecté</span><strong data-out="left">4 200 €</strong></div>
      <div><span>Passes faites</span><strong data-out="done">0 / 4</strong></div>
      <div><span>Temps estimé</span><strong data-out="time">20 min</strong></div>
    </div>
    <div class="lab-checks" data-out="steps"></div>
  </div>

<?php elseif ($widget === 'plafonds'): ?>
  <div class="lab" data-lab="plafonds">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Où se situe votre CA annuel ?</strong>
      <p>Deux barres, deux bascules. La TVA arrive bien avant la sortie du régime micro.</p>
    </div>
    <div class="chips">
      <button type="button" class="chip active" data-act="services">Services / libéral</button>
      <button type="button" class="chip" data-act="vente">Vente / hébergement</button>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>CA mensuel moyen</span><b data-out="month">3 500 €</b></div>
      <input type="range" min="500" max="18000" step="100" value="3500" data-in="month">
    </div>
    <div class="lab-stack" data-out="stack"></div>
    <div class="lab-kpis">
      <div><span>CA annuel</span><strong data-out="year">—</strong></div>
      <div><span>Franchise TVA</span><strong data-out="tva">—</strong></div>
      <div><span>Régime micro</span><strong data-out="micro">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'liberatoire'): ?>
  <div class="lab" data-lab="liberatoire">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Libératoire chaque mois, ou IR plus tard ?</strong>
      <p>Même CA, deux provisions. Le TMI est celui de la dernière tranche du foyer — une hypothèse, pas votre avis d’impôt.</p>
    </div>
    <div class="chips">
      <button type="button" class="chip active" data-regime="bic">Services BIC</button>
      <button type="button" class="chip" data-regime="bnc">BNC</button>
      <button type="button" class="chip" data-regime="vente">Vente</button>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>CA mensuel</span><b data-out="ca">3 000 €</b></div>
      <input type="range" min="500" max="8000" step="100" value="3000" data-in="ca">
    </div>
    <div class="chips">
      <button type="button" class="chip" data-tmi="0">TMI 0 %</button>
      <button type="button" class="chip active" data-tmi="11">TMI 11 %</button>
      <button type="button" class="chip" data-tmi="30">TMI 30 %</button>
    </div>
    <div class="lab-split">
      <div class="lab-col" data-out="with"></div>
      <div class="lab-col" data-out="without"></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'irregulier'): ?>
  <div class="lab" data-lab="irregulier">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Douze mois, une moyenne, une provision</strong>
      <p>Choisissez le rythme. Le circuit, lui, ne voit que la moyenne — c’est celle qu’il faut lui donner.</p>
    </div>
    <div class="chips">
      <button type="button" class="chip active" data-pattern="flat">Régulier</button>
      <button type="button" class="chip" data-pattern="season">Saisonnier · 6 mois</button>
      <button type="button" class="chip" data-pattern="saw">Dents de scie</button>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>CA d’un mois chargé</span><b data-out="peak">4 000 €</b></div>
      <input type="range" min="800" max="10000" step="100" value="4000" data-in="peak">
    </div>
    <div class="lab-year" data-out="year" role="img" aria-label="Chiffre d’affaires sur 12 mois"></div>
    <div class="lab-kpis">
      <div><span>CA annuel</span><strong data-out="total">—</strong></div>
      <div><span>Moyenne / mois</span><strong data-out="avg">—</strong></div>
      <div><span>Provision / mois</span><strong data-out="prov">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'lep'): ?>
  <div class="lab" data-lab="lep">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Votre RFR, le plafond 2026</strong>
      <p>Métropole. La banque lit le RFR 2024 ou 2025, selon la date de la demande.</p>
    </div>
    <div class="chips">
      <button type="button" class="chip active" data-parts="1">1 part</button>
      <button type="button" class="chip" data-parts="1.5">1,5</button>
      <button type="button" class="chip" data-parts="2">2 parts</button>
      <button type="button" class="chip" data-parts="2.5">2,5</button>
      <button type="button" class="chip" data-parts="3">3 parts</button>
      <button type="button" class="chip" data-parts="4">4 parts</button>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Revenu fiscal de référence</span><b data-out="rfr">22 000 €</b></div>
      <input type="range" min="8000" max="90000" step="250" value="22000" data-in="rfr">
    </div>
    <div class="lab-kpis lab-kpis-2">
      <div><span>Plafond 2026</span><strong data-out="cap">—</strong></div>
      <div><span>Éligibilité</span><strong data-out="ok">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'matelas'): ?>
  <div class="lab" data-lab="matelas">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Combien de mois tenez-vous ?</strong>
      <p>La cible est un multiple des charges, pas des revenus. Le fil s’arrête une fois le livret saturé à cette cible.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Charges mensuelles</span><b data-out="bills">2 400 €</b></div>
      <input type="range" min="800" max="6000" step="50" value="2400" data-in="bills">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Mois de réserve visés</span><b data-out="horizon">3 mois</b></div>
      <input type="range" min="1" max="8" step="1" value="3" data-in="horizon">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Déjà de côté</span><b data-out="have">1 200 €</b></div>
      <input type="range" min="0" max="30000" step="100" value="1200" data-in="have">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Versement mensuel</span><b data-out="pay">250 €</b></div>
      <input type="range" min="50" max="1500" step="25" value="250" data-in="pay">
    </div>
    <div class="lab-bar is-tall"><i data-out="fill"></i><em></em></div>
    <div class="lab-kpis">
      <div><span>Cible</span><strong data-out="target">—</strong></div>
      <div><span>Il manque</span><strong data-out="gap">—</strong></div>
      <div><span>Cible atteinte</span><strong data-out="when">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'mixte'): ?>
  <div class="lab" data-lab="mixte">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Le salaire tient-il les factures ?</strong>
      <p>Baissez l’auto-entreprise. Si les fixes tiennent encore, le mois bas est déjà sauvé.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Salaire net</span><b data-out="salary">2 200 €</b></div>
      <input type="range" min="800" max="4500" step="50" value="2200" data-in="salary">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>CA auto-entreprise</span><b data-out="ae">1 800 €</b></div>
      <input type="range" min="0" max="6000" step="50" value="1800" data-in="ae">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Factures du foyer</span><b data-out="bills">1 900 €</b></div>
      <input type="range" min="600" max="4000" step="50" value="1900" data-in="bills">
    </div>
    <div class="lab-flow" data-out="flow"></div>
    <div class="lab-kpis">
      <div><span>Net AE après URSSAF</span><strong data-out="net">—</strong></div>
      <div><span>Épargne possible</span><strong data-out="save">—</strong></div>
      <div><span>Mois sans AE</span><strong data-out="cut">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'epargne'): ?>
  <div class="lab" data-lab="epargne">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Ce qui reste, si le livret part en premier</strong>
      <p>Le virement épargne est servi après les fixes, avant le quotidien. Baissez-le si le mois casse.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Revenus du mois</span><b data-out="income">2 200 €</b></div>
      <input type="range" min="800" max="6000" step="50" value="2200" data-in="income">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Charges fixes</span><b data-out="bills">1 200 €</b></div>
      <input type="range" min="200" max="4000" step="50" value="1200" data-in="bills">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Versement livret</span><b data-out="save">300 €</b></div>
      <input type="range" min="0" max="1500" step="25" value="300" data-in="save">
    </div>
    <div class="lab-flow" data-out="flow"></div>
    <div class="lab-kpis">
      <div><span>Quotidien restant</span><strong data-out="daily">—</strong></div>
      <div><span>Part épargnée</span><strong data-out="pct">—</strong></div>
      <div><span>1 000 € atteints</span><strong data-out="when">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'budget'): ?>
  <div class="lab" data-lab="budget">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Le mois tient-il, une fois tout nommé ?</strong>
      <p>Entrées, fixes, quotidien, épargne. Le compteur dit s’il reste un euro — ou s’il en manque.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Entrées</span><b data-out="income">2 400 €</b></div>
      <input type="range" min="700" max="8000" step="50" value="2400" data-in="income">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Fixes (loyer, prélèvements)</span><b data-out="bills">1 100 €</b></div>
      <input type="range" min="200" max="4500" step="50" value="1100" data-in="bills">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Enveloppe quotidienne</span><b data-out="daily">900 €</b></div>
      <input type="range" min="150" max="3500" step="50" value="900" data-in="daily">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Épargne posée</span><b data-out="save">250 €</b></div>
      <input type="range" min="0" max="2000" step="25" value="250" data-in="save">
    </div>
    <div class="lab-kpis">
      <div><span>Affecté</span><strong data-out="used">—</strong></div>
      <div><span>Non affecté</span><strong data-out="left">—</strong></div>
      <div><span>État du mois</span><strong data-out="state">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'livreta'): ?>
  <div class="lab" data-lab="livreta">
    <div class="lab-head">
      <span class="eyebrow">Simulateur · 1,70 % · 22 950 €</span>
      <strong>En combien de mois le Livret A sature-t-il ?</strong>
      <p>Les intérêts continuent après le plafond. Le versement, lui, doit alors partir ailleurs.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Déjà sur le livret</span><b data-out="start">2 000 €</b></div>
      <input type="range" min="0" max="22950" step="50" value="2000" data-in="start">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Versement mensuel</span><b data-out="pay">250 €</b></div>
      <input type="range" min="20" max="2000" step="10" value="250" data-in="pay">
    </div>
    <div class="lab-bar is-tall"><i data-out="fill"></i><em></em></div>
    <div class="lab-kpis">
      <div><span>Place restante</span><strong data-out="room">—</strong></div>
      <div><span>Plafond atteint</span><strong data-out="when">—</strong></div>
      <div><span>Intérêts / an (stock actuel)</span><strong data-out="interest">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'payfirst'): ?>
  <div class="lab" data-lab="payfirst">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>10 %, 20 % ou 30 % — que reste-t-il pour vivre ?</strong>
      <p>Le pourcentage part vers le livret avant le quotidien. Le loyer, lui, est déjà servi.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Salaire net</span><b data-out="income">1 900 €</b></div>
      <input type="range" min="1100" max="4000" step="50" value="1900" data-in="income">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Loyer et fixes</span><b data-out="rent">650 €</b></div>
      <input type="range" min="300" max="1800" step="25" value="650" data-in="rent">
    </div>
    <div class="chips">
      <button type="button" class="chip" data-pct="10">10 %</button>
      <button type="button" class="chip active" data-pct="20">20 %</button>
      <button type="button" class="chip" data-pct="30">30 %</button>
    </div>
    <div class="lab-flow" data-out="flow"></div>
    <div class="lab-kpis">
      <div><span>Vers le livret</span><strong data-out="save">—</strong></div>
      <div><span>Quotidien</span><strong data-out="daily">—</strong></div>
      <div><span>Le mois</span><strong data-out="state">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'petit'): ?>
  <div class="lab" data-lab="petit">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Un petit fixe, sur la durée</strong>
      <p>Sans promesse de rendement : juste le stock accumulé, à 1,70 % si vous cochez les intérêts du Livret A.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Versement mensuel</span><b data-out="pay">50 €</b></div>
      <input type="range" min="10" max="250" step="5" value="50" data-in="pay">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Durée</span><b data-out="years">3 ans</b></div>
      <input type="range" min="1" max="8" step="1" value="3" data-in="years">
    </div>
    <label class="lab-check">
      <input type="checkbox" data-in="rate" checked>
      <span>Compter 1,70 % (Livret A)</span>
    </label>
    <div class="lab-kpis">
      <div><span>Versé</span><strong data-out="paid">—</strong></div>
      <div><span>À l’arrivée</span><strong data-out="end">—</strong></div>
      <div><span>Dont intérêts</span><strong data-out="gain">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'projet'): ?>
  <div class="lab" data-lab="projet">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Quelle mensualité pour tenir la date ?</strong>
      <p>Cible moins déjà posé, divisé par les mois restants. Si ça casse le mois, reculez la date.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Cible</span><b data-out="target">2 400 €</b></div>
      <input type="range" min="400" max="15000" step="100" value="2400" data-in="target">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Déjà de côté</span><b data-out="have">400 €</b></div>
      <input type="range" min="0" max="8000" step="50" value="400" data-in="have">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Mois restants</span><b data-out="months">12 mois</b></div>
      <input type="range" min="2" max="36" step="1" value="12" data-in="months">
    </div>
    <div class="lab-kpis">
      <div><span>Il manque</span><strong data-out="gap">—</strong></div>
      <div><span>Versement / mois</span><strong data-out="pay">—</strong></div>
      <div><span>Tenable à 400 €</span><strong data-out="ok">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'apport'): ?>
  <div class="lab" data-lab="apport">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>À quelle date l’apport est-il là ?</strong>
      <p>Exemple : 40 000 €. Le versement est ce qui reste après charges. Ce n’est pas un avis bancaire.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Cible d’apport</span><b data-out="target">40 000 €</b></div>
      <input type="range" min="8000" max="80000" step="1000" value="40000" data-in="target">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Déjà de côté</span><b data-out="have">6 000 €</b></div>
      <input type="range" min="0" max="40000" step="500" value="6000" data-in="have">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Versement mensuel</span><b data-out="pay">800 €</b></div>
      <input type="range" min="100" max="2500" step="50" value="800" data-in="pay">
    </div>
    <div class="lab-bar is-tall"><i data-out="fill"></i><em></em></div>
    <div class="lab-kpis">
      <div><span>Il manque</span><strong data-out="gap">—</strong></div>
      <div><span>Date</span><strong data-out="when">—</strong></div>
      <div><span>Versé d’ici là</span><strong data-out="total">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'premier'): ?>
  <div class="lab" data-lab="premier">
    <div class="lab-head">
      <span class="eyebrow">Simulateur · Premier salaire</span>
      <strong>Et si le loyer monte ?</strong>
      <p>20 % partent vers l’épargne avant le quotidien. C’est le quotidien qui encaisse l’écart — jusqu’à ce que le mois casse.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Salaire</span><b data-out="income">1 900 €</b></div>
      <input type="range" min="1400" max="2800" step="50" value="1900" data-in="income">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Loyer + charges</span><b data-out="rent">650 €</b></div>
      <input type="range" min="400" max="1200" step="25" value="650" data-in="rent">
    </div>
    <div class="lab-flow" data-out="flow"></div>
    <div class="lab-kpis">
      <div><span>Épargne 20 %</span><strong data-out="save">—</strong></div>
      <div><span>Quotidien</span><strong data-out="daily">—</strong></div>
      <div><span>Le mois</span><strong data-out="state">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'prorata'): ?>
  <div class="lab" data-lab="prorata">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>50/50 ou au prorata des salaires</strong>
      <p>Mêmes factures communes. Seule la clé de répartition change. Regardez ce qui reste à chacun.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Salaire A</span><b data-out="a">2 000 €</b></div>
      <input type="range" min="1200" max="5000" step="50" value="2000" data-in="a">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Salaire B</span><b data-out="b">3 000 €</b></div>
      <input type="range" min="1200" max="6000" step="50" value="3000" data-in="b">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Charges communes</span><b data-out="shared">3 400 €</b></div>
      <input type="range" min="800" max="5500" step="50" value="3400" data-in="shared">
    </div>
    <div class="lab-split">
      <div class="lab-col" data-out="half"></div>
      <div class="lab-col" data-out="pro"></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'credit'): ?>
  <div class="lab" data-lab="credit">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Que reste-t-il une fois le crédit servi ?</strong>
      <p>Mensualité + charges du bien + quotidien. Le surplus, s’il existe, peut nourrir travaux et Livret A.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Revenus du foyer</span><b data-out="income">5 000 €</b></div>
      <input type="range" min="2500" max="9000" step="50" value="5000" data-in="income">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Mensualité de crédit</span><b data-out="loan">1 180 €</b></div>
      <input type="range" min="400" max="2800" step="20" value="1180" data-in="loan">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Charges du bien</span><b data-out="house">480 €</b></div>
      <input type="range" min="100" max="1200" step="20" value="480" data-in="house">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Quotidien</span><b data-out="daily">1 600 €</b></div>
      <input type="range" min="600" max="3500" step="50" value="1600" data-in="daily">
    </div>
    <div class="lab-kpis">
      <div><span>Toit (crédit + charges)</span><strong data-out="roof">—</strong></div>
      <div><span>Reste à câbler</span><strong data-out="left">—</strong></div>
      <div><span>Dont 300 € travaux</span><strong data-out="works">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>

<?php elseif ($widget === 'saison'): ?>
  <div class="lab" data-lab="saison">
    <div class="lab-head">
      <span class="eyebrow">Simulateur</span>
      <strong>Quelle réserve pour tenir les mois bas ?</strong>
      <p>La moyenne paie le mois type. La réserve, elle, couvre l’écart des creux.</p>
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Mois chargés / an</span><b data-out="highn">6 mois</b></div>
      <input type="range" min="3" max="10" step="1" value="6" data-in="highn">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Revenu d’un mois chargé</span><b data-out="high">4 200 €</b></div>
      <input type="range" min="1500" max="8000" step="50" value="4200" data-in="high">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Revenu d’un mois creux</span><b data-out="low">1 400 €</b></div>
      <input type="range" min="0" max="4000" step="50" value="1400" data-in="low">
    </div>
    <div class="lab-field">
      <div class="lab-field-top"><span>Charges du foyer</span><b data-out="bills">2 300 €</b></div>
      <input type="range" min="800" max="5000" step="50" value="2300" data-in="bills">
    </div>
    <div class="lab-kpis">
      <div><span>Moyenne / mois</span><strong data-out="avg">—</strong></div>
      <div><span>Écart d’un creux</span><strong data-out="gap">—</strong></div>
      <div><span>Réserve à viser</span><strong data-out="reserve">—</strong></div>
    </div>
    <p class="lab-foot" data-out="note"></p>
  </div>
<?php endif; ?>
