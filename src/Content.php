<?php

declare(strict_types=1);

namespace App;

class Content
{
    public static function posts(): array
    {
        return [
            ['slug' => 'budget-tableur', 'tag' => 'Méthode', 'read' => '6 min', 'date' => '12 août 2026', 't' => 'Pourquoi votre budget ne tient pas dans un tableur', 'd' => 'Un tableur décrit des totaux ; un circuit décrit des chemins. La différence se voit au troisième compte joint.'],
            ['slug' => 'ordre-livrets', 'tag' => 'Réglementaire', 'read' => '4 min', 'date' => '4 août 2026', 't' => 'Ordre de remplissage des livrets réglementés', 'd' => 'LEP, LDDS, Livret A : dans quel ordre saturer quand on épargne 1 500 € par mois.'],
            ['slug' => 'compte-joint-factures', 'tag' => 'Méthode', 'read' => '7 min', 'date' => '28 juillet 2026', 't' => 'Le compte joint « factures » change tout', 'd' => 'Séparer les prélèvements du quotidien supprime la moitié des arbitrages mensuels.'],
            ['slug' => 'urssaf-auto-entrepreneur', 'tag' => 'Étude de cas', 'read' => '9 min', 'date' => '19 juillet 2026', 't' => 'Auto-entrepreneur : provisionner l’URSSAF comme une dépense', 'd' => 'Un bloc dépense dédié évite la mauvaise surprise trimestrielle, et rend le revenu net lisible.'],
            ['slug' => 'plafond-atteint', 'tag' => 'Réglementaire', 'read' => '5 min', 'date' => '9 juillet 2026', 't' => 'Ce que « plafond atteint » veut vraiment dire', 'd' => 'Un livret plein continue de produire des intérêts au-delà du plafond. Ce que ça change dans une projection.'],
            ['slug' => 'pourcentages-ou-fixes', 'tag' => 'Méthode', 'read' => '8 min', 'date' => '1 juillet 2026', 't' => 'Répartir en pourcentages, ou en montants fixes ?', 'd' => 'Les pourcentages encaissent les variations de revenu ; les montants fixes protègent les objectifs.'],
            ['slug' => 'fil-tout-le-reste', 'tag' => 'Produit', 'read' => '3 min', 'date' => '24 juin 2026', 't' => 'Nouveau : le fil « tout le reste »', 'd' => 'Un fil qui emporte le solde d’un bloc, pour ne plus recalculer après chaque augmentation.'],
            ['slug' => 'famille-de-quatre', 'tag' => 'Étude de cas', 'read' => '11 min', 'date' => '14 juin 2026', 't' => 'Famille de quatre, deux livrets enfants, un objectif apport', 'd' => 'Le circuit complet, avec les compromis assumés et ce que la projection à cinq ans a fait changer.'],
            ['slug' => 'scenarios-compares', 'tag' => 'Produit', 'read' => '4 min', 'date' => '2 juin 2026', 't' => 'Les scénarios comparés, en pratique', 'd' => 'Deux variantes d’un même circuit côte à côte, et la lecture de l’écart de patrimoine.'],
            ['slug' => 'couple-12338', 'tag' => 'Étude de cas', 'read' => '12 min', 'date' => '18 août 2026', 't' => 'Un couple, 12 338 € par mois, zéro euro non affecté', 'd' => 'Le circuit complet d’une famille de quatre : deux salaires, une auto-entreprise, un local loué, deux comptes joints et six livrets.'],
        ];
    }

    public static function post(string $slug): ?array
    {
        foreach (self::posts() as $post) {
            if ($post['slug'] === $slug) {
                $post['body'] = [
                    $post['d'],
                    'repartio sert à rendre ces chemins visibles : chaque euro entre par un bloc, circule le long d’un fil, et arrive quelque part. Si un montant reste « non affecté », le mois n’est pas encore décrit.',
                    'Les chiffres cités dans cette note sont des exemples de simulation. Ils ne constituent pas un conseil en investissement.',
                ];
                return $post;
            }
        }
        return null;
    }

    public static function faq(): array
    {
        return [
            ['Prise en main', 'Faut-il connecter ma banque ?', 'Non, et ce n’est pas prévu. repartio fonctionne uniquement avec les montants que vous saisissez.', 'Vos données', '/vos-donnees'],
            ['Prise en main', 'Combien de temps pour un premier circuit ?', 'Dix minutes pour un foyer simple, une demi-heure pour un circuit à vingt blocs. Le plus rapide est de partir d’un circuit type.', 'Circuits types', '/circuits-types'],
            ['Prise en main', 'Par quoi commencer : les revenus ou les dépenses ?', 'Par les revenus. Le compteur « non affecté » vous indique ensuite exactement combien il reste à câbler.', '', ''],
            ['Prise en main', 'Puis-je importer un tableur existant ?', 'Pas encore d’import automatique. Recopier les lignes d’un tableur dans des blocs prend une quinzaine de minutes.', '', ''],
            ['Prise en main', 'repartio fonctionne-t-il sur mobile ?', 'Le tableau de bord et la lecture des circuits, oui. La construction au canvas demande un écran large.', '', ''],
            ['Le moteur', 'Que signifie « non affecté » ?', 'C’est l’argent entré dans le circuit qui n’a pas encore de destination. Tant que ce compteur n’est pas à zéro, votre mois type est incomplet.', 'Le moteur', '/fonctionnement'],
            ['Le moteur', 'Comment sont gérés les plafonds réglementaires ?', 'Chaque livret porte son plafond et son taux. Quand il sature, repartio redirige le surplus vers la destination de débordement.', '', ''],
            ['Le moteur', 'Un fil peut-il porter un pourcentage ?', 'Oui : montant fixe, pourcentage, ou « tout le reste ». Les montants fixes sont servis d’abord.', '', ''],
            ['Le moteur', 'Les intérêts sont-ils composés ?', 'Ils sont capitalisés une fois par an sur le solde moyen, au taux que porte le livret.', '', ''],
            ['Le moteur', 'Peut-on modéliser un revenu irrégulier ?', 'Oui, en moyenne lissée. Pour de fortes variations, créez deux scénarios : un mois bas et un mois haut.', '', ''],
            ['Le moteur', 'L’inflation est-elle prise en compte ?', 'Non par défaut : nous n’imposons aucune hypothèse macroéconomique.', '', ''],
            ['Situations', 'Peut-on modéliser un couple avec des comptes séparés ?', 'C’est le cas le plus courant : deux colonnes de comptes personnels, un ou plusieurs comptes joints, et des répartiteurs distincts.', 'Voir le modèle', '/circuits-types'],
            ['Situations', 'Comment provisionner l’URSSAF en auto-entreprise ?', 'Avec un bloc dépense dédié, alimenté depuis le compte professionnel.', '', ''],
            ['Situations', 'Et les livrets des enfants ?', 'Un bloc livret par enfant, avec son solde de départ, son taux et son plafond.', '', ''],
            ['Situations', 'Peut-on suivre un objectif chiffré, comme un apport ?', 'Oui : vous posez la cible sur le bloc de destination, et repartio affiche le mois d’atteinte.', '', ''],
            ['Situations', 'Comment gérer un crédit immobilier ?', 'Comme une dépense mensuelle fixe. La modélisation du capital restant dû n’est pas encore dans le moteur.', '', ''],
            ['Compte & plans', 'Que contient la version gratuite ?', 'Trois circuits, les cinq types de blocs, les plafonds réglementaires, la projection jusqu’à 60 mois et les exports.', 'Comparer les plans', '/tarifs'],
            ['Compte & plans', 'Faut-il une carte pour créer un compte ?', 'Non. Aucun moyen de paiement n’est demandé sur le plan Libre.', '', ''],
            ['Compte & plans', 'Que devient mon circuit si j’arrête de payer ?', 'Il reste consultable et exportable. Vous repassez sous la limite de trois circuits modifiables.', '', ''],
            ['Compte & plans', 'Le plan Foyer, c’est deux abonnements ?', 'Non, un seul : deux accès nominatifs sur les mêmes circuits.', '', ''],
            ['Compte & plans', 'Puis-je changer de plan en cours de route ?', 'À tout moment, dans les deux sens.', '', ''],
            ['Données & sécurité', 'Où sont hébergées mes données ?', 'Dans un centre de données situé en France, au sein de l’Union européenne.', 'Confidentialité', '/confidentialite'],
            ['Données & sécurité', 'Vendez-vous les données ?', 'Jamais. Pas de revente, pas de courtier, pas de ciblage publicitaire.', '', ''],
            ['Données & sécurité', 'Comment supprimer définitivement mon compte ?', 'Depuis les réglages, en un clic. La suppression est immédiate et sans période de rétention.', '', ''],
            ['Données & sécurité', 'repartio est-il un conseil en investissement ?', 'Non. C’est un outil de simulation : il calcule ce que vous décrivez.', '', ''],
        ];
    }

    public static function templates(): array
    {
        $node = static function (string $id, string $kind, string $title, float $x, float $y, float $amount = 0, array $extra = []): array {
            return array_merge(compact('id', 'kind', 'title', 'x', 'y', 'amount'), $extra);
        };
        $C = [
            'revenu' => 'oklch(0.62 0.12 192)',
            'compte' => 'oklch(0.32 0.09 265)',
            'repartiteur' => 'oklch(0.68 0.18 38)',
            'livret' => 'oklch(0.48 0.11 240)',
            'depense' => 'oklch(0.55 0.16 25)',
        ];

        $items = [
            'couple' => [
                'title' => 'Couple, comptes séparés',
                'category' => 'Couple',
                'hint' => 'Deux salaires, un joint pour les factures, un joint pour le quotidien, épargne par personne.',
                'thumb' => [
                    'wires' => ['M40 30 C80 30 80 68 120 68', 'M40 104 C80 104 80 72 120 72', 'M164 70 C200 70 200 34 240 34', 'M164 74 C200 74 200 104 240 104'],
                    'dots' => [[8, 22, 32, $C['revenu']], [8, 96, 32, $C['revenu']], [120, 60, 44, $C['compte']], [240, 26, 52, $C['depense']], [240, 96, 52, $C['livret']]],
                ],
                'payload' => [
                    'horizon' => 60,
                    'nodes' => [
                        $node('r1', 'revenu', 'Salaire A', 40, 40, 2400),
                        $node('r2', 'revenu', 'Salaire B', 40, 280, 2800),
                        $node('ca', 'compte', 'Compte A', 360, 40),
                        $node('cb', 'compte', 'Compte B', 360, 200),
                        $node('cf', 'compte', 'Joint Factures', 360, 360),
                        $node('cq', 'compte', 'Joint Quotidien', 360, 520),
                        $node('p1', 'repartiteur', 'Épargne', 360, 680),
                        $node('d1', 'depense', 'Prélèvements', 720, 360),
                        $node('d2', 'depense', 'Dépenses courantes', 720, 520),
                        $node('l1', 'livret', 'Livret A', 720, 40, 0, ['rate' => 1.7, 'cap' => 22950, 'start' => 0, 'preset' => 'livret-a']),
                        $node('l2', 'livret', 'LDDS', 720, 200, 0, ['rate' => 1.7, 'cap' => 12000, 'start' => 0, 'preset' => 'ldds']),
                    ],
                    'edges' => [
                        ['from' => 'r1', 'to' => 'ca', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'r2', 'to' => 'cb', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'ca', 'to' => 'cf', 'mode' => 'fixe', 'value' => 900],
                        ['from' => 'ca', 'to' => 'cq', 'mode' => 'fixe', 'value' => 600],
                        ['from' => 'ca', 'to' => 'p1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'cb', 'to' => 'cf', 'mode' => 'fixe', 'value' => 1100],
                        ['from' => 'cb', 'to' => 'cq', 'mode' => 'fixe', 'value' => 800],
                        ['from' => 'cb', 'to' => 'p1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'cf', 'to' => 'd1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'cq', 'to' => 'd2', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'p1', 'to' => 'l1', 'mode' => 'fixe', 'value' => 400],
                        ['from' => 'p1', 'to' => 'l2', 'mode' => 'reste', 'value' => 0],
                    ],
                ],
            ],
            'auto-entrepreneur' => [
                'title' => 'Auto-entrepreneur',
                'category' => 'Indépendant',
                'hint' => 'Chiffre d’affaires, provision URSSAF, rémunération vers le compte perso, épargne de précaution.',
                'thumb' => [
                    'wires' => ['M40 68 C80 68 80 30 120 30', 'M40 72 C80 72 80 104 120 104', 'M164 32 C200 32 210 68 240 68'],
                    'dots' => [[8, 60, 32, $C['revenu']], [120, 22, 44, $C['compte']], [120, 96, 44, $C['depense']], [240, 60, 52, $C['livret']]],
                ],
                'payload' => [
                    'horizon' => 60,
                    'nodes' => [
                        $node('r1', 'revenu', 'Chiffre d’affaires', 40, 200, 3500),
                        $node('cpro', 'compte', 'Compte professionnel', 360, 80),
                        $node('cperso', 'compte', 'Compte perso', 360, 360),
                        $node('p1', 'repartiteur', 'Épargne', 360, 560),
                        $node('dur', 'depense', 'Cotisations URSSAF', 720, 40),
                        $node('dchg', 'depense', 'Charges pro', 720, 200),
                        $node('dcour', 'depense', 'Dépenses courantes', 720, 360),
                        $node('l1', 'livret', 'Livret A', 720, 520, 0, ['rate' => 1.7, 'cap' => 22950, 'start' => 0, 'preset' => 'livret-a']),
                        $node('l2', 'livret', 'LDDS', 720, 680, 0, ['rate' => 1.7, 'cap' => 12000, 'start' => 0, 'preset' => 'ldds']),
                    ],
                    'edges' => [
                        ['from' => 'r1', 'to' => 'cpro', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'cpro', 'to' => 'dur', 'mode' => 'fixe', 'value' => 770],
                        ['from' => 'cpro', 'to' => 'dchg', 'mode' => 'fixe', 'value' => 200],
                        ['from' => 'cpro', 'to' => 'cperso', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'cperso', 'to' => 'dcour', 'mode' => 'fixe', 'value' => 1600],
                        ['from' => 'cperso', 'to' => 'p1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'p1', 'to' => 'l1', 'mode' => 'fixe', 'value' => 500],
                        ['from' => 'p1', 'to' => 'l2', 'mode' => 'reste', 'value' => 0],
                    ],
                ],
            ],
            'precaution' => [
                'title' => 'Épargne de précaution',
                'category' => 'Épargne',
                'hint' => 'Un seul revenu, une règle de trois mois de charges, saturation du LEP puis du Livret A.',
                'thumb' => [
                    'wires' => ['M40 68 C80 68 80 42 120 42', 'M164 44 C200 44 200 24 240 24', 'M164 48 C200 48 200 96 240 96'],
                    'dots' => [[8, 60, 32, $C['revenu']], [120, 34, 44, $C['repartiteur']], [240, 16, 52, $C['livret']], [240, 88, 52, $C['livret']]],
                ],
                'payload' => [
                    'horizon' => 60,
                    'nodes' => [
                        $node('r1', 'revenu', 'Salaire', 40, 200, 2200),
                        $node('c1', 'compte', 'Compte courant', 360, 200),
                        $node('d1', 'depense', 'Charges du mois', 720, 80),
                        $node('p1', 'repartiteur', 'Précaution', 720, 280),
                        $node('lep', 'livret', 'LEP', 1040, 200, 0, ['rate' => 2.5, 'cap' => 10000, 'start' => 0, 'preset' => 'lep']),
                        $node('la', 'livret', 'Livret A', 1040, 400, 0, ['rate' => 1.7, 'cap' => 22950, 'start' => 0, 'preset' => 'livret-a']),
                    ],
                    'edges' => [
                        ['from' => 'r1', 'to' => 'c1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'c1', 'to' => 'd1', 'mode' => 'fixe', 'value' => 1800],
                        ['from' => 'c1', 'to' => 'p1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'p1', 'to' => 'lep', 'mode' => 'fixe', 'value' => 300],
                        ['from' => 'p1', 'to' => 'la', 'mode' => 'reste', 'value' => 0],
                    ],
                ],
            ],
            'apport' => [
                'title' => 'Objectif apport',
                'category' => 'Épargne',
                'hint' => 'Tout ce qui n’est pas dépensé va vers l’apport ; repartio affiche la date d’atteinte du montant cible.',
                'thumb' => [
                    'wires' => ['M40 42 C80 42 80 68 120 68', 'M40 104 C80 104 80 72 120 72', 'M164 70 C210 70 210 42 240 42'],
                    'dots' => [[8, 34, 32, $C['revenu']], [8, 96, 32, $C['revenu']], [120, 60, 44, $C['repartiteur']], [240, 34, 52, $C['livret']]],
                ],
                'payload' => [
                    'horizon' => 60,
                    'nodes' => [
                        $node('r1', 'revenu', 'Salaire A', 40, 40, 2200),
                        $node('r2', 'revenu', 'Salaire B', 40, 280, 2400),
                        $node('c1', 'compte', 'Compte joint', 360, 160),
                        $node('d1', 'depense', 'Loyer', 720, 40),
                        $node('d2', 'depense', 'Quotidien', 720, 200),
                        $node('d3', 'depense', 'Impôts', 720, 360),
                        $node('p1', 'repartiteur', 'Tout le reste', 720, 520),
                        $node('lep', 'livret', 'LEP', 1040, 360, 0, ['rate' => 2.5, 'cap' => 10000, 'start' => 0, 'preset' => 'lep']),
                        $node('la', 'livret', 'Livret A', 1040, 520, 0, ['rate' => 1.7, 'cap' => 22950, 'start' => 0, 'preset' => 'livret-a']),
                        $node('lap', 'livret', 'Livret apport', 1040, 680, 0, ['rate' => 1.7, 'cap' => 0, 'start' => 0, 'preset' => 'custom', 'note' => 'Cible d’apport : ajustez le versement et lisez la projection.']),
                    ],
                    'edges' => [
                        ['from' => 'r1', 'to' => 'c1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'r2', 'to' => 'c1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'c1', 'to' => 'd1', 'mode' => 'fixe', 'value' => 1200],
                        ['from' => 'c1', 'to' => 'd2', 'mode' => 'fixe', 'value' => 1600],
                        ['from' => 'c1', 'to' => 'd3', 'mode' => 'fixe', 'value' => 400],
                        ['from' => 'c1', 'to' => 'p1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'p1', 'to' => 'lep', 'mode' => 'fixe', 'value' => 200],
                        ['from' => 'p1', 'to' => 'la', 'mode' => 'fixe', 'value' => 300],
                        ['from' => 'p1', 'to' => 'lap', 'mode' => 'reste', 'value' => 0],
                    ],
                ],
            ],
            'famille' => [
                'title' => 'Famille avec enfants',
                'category' => 'Famille',
                'hint' => 'Allocations, livrets des enfants, dépenses courantes, deux répartiteurs.',
                'thumb' => [
                    'wires' => ['M40 28 C80 28 80 64 120 64', 'M40 100 C80 100 80 68 120 68', 'M164 66 C200 66 200 28 240 28', 'M164 70 C200 70 200 100 240 100'],
                    'dots' => [[8, 20, 32, $C['revenu']], [8, 92, 32, $C['revenu']], [120, 56, 44, $C['repartiteur']], [240, 20, 52, $C['livret']], [240, 92, 52, $C['depense']]],
                ],
                'payload' => [
                    'horizon' => 60,
                    'nodes' => [
                        $node('r1', 'revenu', 'Salaire A', 40, 40, 2800),
                        $node('r2', 'revenu', 'Salaire B', 40, 200, 2200),
                        $node('r3', 'revenu', 'Allocations', 40, 360, 380),
                        $node('cf', 'compte', 'Joint Factures', 360, 40),
                        $node('cq', 'compte', 'Joint Quotidien', 360, 200),
                        $node('p1', 'repartiteur', 'Épargne foyer', 360, 360),
                        $node('p2', 'repartiteur', 'Livrets enfants', 360, 520),
                        $node('d1', 'depense', 'Prélèvements', 720, 40),
                        $node('d2', 'depense', 'Dépenses courantes', 720, 200),
                        $node('l1', 'livret', 'Livret A', 720, 360, 0, ['rate' => 1.7, 'cap' => 22950, 'start' => 0, 'preset' => 'livret-a']),
                        $node('l2', 'livret', 'LDDS', 720, 520, 0, ['rate' => 1.7, 'cap' => 12000, 'start' => 0, 'preset' => 'ldds']),
                        $node('le1', 'livret', 'Livret enfant 1', 720, 680, 0, ['rate' => 1.7, 'cap' => 1600, 'start' => 0, 'preset' => 'jeune']),
                        $node('le2', 'livret', 'Livret enfant 2', 720, 840, 0, ['rate' => 1.7, 'cap' => 1600, 'start' => 0, 'preset' => 'jeune']),
                    ],
                    'edges' => [
                        ['from' => 'r1', 'to' => 'cf', 'mode' => 'fixe', 'value' => 1400],
                        ['from' => 'r1', 'to' => 'cq', 'mode' => 'fixe', 'value' => 800],
                        ['from' => 'r1', 'to' => 'p1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'r2', 'to' => 'cf', 'mode' => 'fixe', 'value' => 1000],
                        ['from' => 'r2', 'to' => 'cq', 'mode' => 'fixe', 'value' => 800],
                        ['from' => 'r2', 'to' => 'p1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'r3', 'to' => 'p2', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'cf', 'to' => 'd1', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'cq', 'to' => 'd2', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'p1', 'to' => 'l1', 'mode' => 'fixe', 'value' => 400],
                        ['from' => 'p1', 'to' => 'l2', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'p2', 'to' => 'le1', 'mode' => 'fixe', 'value' => 190],
                        ['from' => 'p2', 'to' => 'le2', 'mode' => 'reste', 'value' => 0],
                    ],
                ],
            ],
            'locatif' => [
                'title' => 'Locatif + salaire',
                'category' => 'Indépendant',
                'hint' => 'Loyers perçus, charges du local, fiscalité provisionnée, surplus vers épargne longue.',
                'thumb' => [
                    'wires' => ['M40 68 C80 68 80 36 120 36', 'M164 38 C200 38 200 22 240 22', 'M164 42 C200 42 200 96 240 96'],
                    'dots' => [[8, 60, 32, $C['revenu']], [120, 28, 44, $C['compte']], [240, 14, 52, $C['depense']], [240, 88, 52, $C['livret']]],
                ],
                'payload' => [
                    'horizon' => 60,
                    'nodes' => [
                        $node('r1', 'revenu', 'Salaire', 40, 40, 2600),
                        $node('r2', 'revenu', 'Loyers', 40, 280, 1400),
                        $node('cperso', 'compte', 'Compte perso', 360, 40),
                        $node('cloc', 'compte', 'Compte locatif', 360, 280),
                        $node('dcour', 'depense', 'Dépenses courantes', 720, 40),
                        $node('dchg', 'depense', 'Charges du local', 720, 200),
                        $node('dfisc', 'depense', 'Fiscalité locative', 720, 360),
                        $node('l1', 'livret', 'Livret A', 720, 520, 0, ['rate' => 1.7, 'cap' => 22950, 'start' => 0, 'preset' => 'livret-a']),
                    ],
                    'edges' => [
                        ['from' => 'r1', 'to' => 'cperso', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'r2', 'to' => 'cloc', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'cloc', 'to' => 'dchg', 'mode' => 'fixe', 'value' => 350],
                        ['from' => 'cloc', 'to' => 'dfisc', 'mode' => 'fixe', 'value' => 280],
                        ['from' => 'cloc', 'to' => 'cperso', 'mode' => 'reste', 'value' => 0],
                        ['from' => 'cperso', 'to' => 'dcour', 'mode' => 'fixe', 'value' => 1800],
                        ['from' => 'cperso', 'to' => 'l1', 'mode' => 'reste', 'value' => 0],
                    ],
                ],
            ],
        ];

        foreach ($items as $key => &$item) {
            $item['payload'] = self::layoutPayload($item['payload']);
            $item['key'] = $key;
            $item['blocks'] = count($item['payload']['nodes'] ?? []);
        }
        unset($item);

        return $items;
    }

    public static function templatePayload(string $key): ?array
    {
        return self::templates()[$key]['payload'] ?? null;
    }

    private static function layoutPayload(array $payload): array
    {
        $nodes = $payload['nodes'] ?? [];
        $edges = $payload['edges'] ?? [];
        if ($nodes === []) {
            return $payload;
        }

        $kindLayer = ['revenu' => 0, 'compte' => 1, 'repartiteur' => 2, 'livret' => 3, 'depense' => 3];
        $kindWeight = ['revenu' => 0, 'compte' => 1, 'depense' => 2, 'repartiteur' => 3, 'livret' => 4];
        $heightOf = static function (array $n): float {
            return match ($n['kind'] ?? '') {
                'livret' => 176.0,
                'depense' => 152.0,
                'repartiteur' => 148.0,
                default => 120.0,
            };
        };

        $graph = [];
        foreach ($nodes as $n) {
            $kind = $n['kind'] ?? '';
            if ($kind === 'groupe' || $kind === 'note') {
                continue;
            }
            $graph[$n['id']] = $n;
        }
        if ($graph === []) {
            return $payload;
        }

        $preds = [];
        $succs = [];
        foreach ($graph as $id => $_) {
            $preds[$id] = [];
            $succs[$id] = [];
        }
        foreach ($edges as $e) {
            $from = (string) ($e['from'] ?? '');
            $to = (string) ($e['to'] ?? '');
            if (!isset($graph[$from], $graph[$to]) || $from === $to) {
                continue;
            }
            $succs[$from][] = $to;
            $preds[$to][] = $from;
        }

        $rank = [];
        $indeg = [];
        foreach ($graph as $id => $_) {
            $indeg[$id] = count($preds[$id]);
        }
        $q = [];
        foreach ($graph as $id => $_) {
            if ($indeg[$id] === 0) {
                $q[] = $id;
                $rank[$id] = 0;
            }
        }
        $seen = [];
        while ($q !== []) {
            $id = array_shift($q);
            if (isset($seen[$id])) {
                continue;
            }
            $seen[$id] = true;
            $base = $rank[$id] ?? 0;
            foreach ($succs[$id] as $to) {
                $rank[$to] = max($rank[$to] ?? 0, $base + 1);
                $indeg[$to]--;
                if ($indeg[$to] === 0) {
                    $q[] = $to;
                }
            }
        }
        foreach ($graph as $id => $n) {
            if (isset($rank[$id])) {
                continue;
            }
            $known = [];
            foreach ($preds[$id] as $p) {
                if (isset($rank[$p])) {
                    $known[] = $rank[$p];
                }
            }
            $rank[$id] = $known !== [] ? max($known) + 1 : ($kindLayer[$n['kind'] ?? ''] ?? 0);
        }
        foreach ($graph as $id => $n) {
            if ($preds[$id] === [] && $succs[$id] === []) {
                $rank[$id] = $kindLayer[$n['kind'] ?? ''] ?? 0;
            }
        }

        $compactRanks = static function () use (&$rank, $graph): int {
            $used = array_values(array_unique(array_values($rank)));
            sort($used);
            $remap = array_flip($used);
            foreach ($rank as $id => $r) {
                $rank[$id] = $remap[$r];
            }

            return count($used);
        };
        $compactRanks();
        $byRank = [];
        foreach ($graph as $id => $n) {
            $byRank[$rank[$id]][] = $n;
        }
        foreach ($byRank as $list) {
            if (count($list) <= 4) {
                continue;
            }
            $hasD = false;
            $hasL = false;
            foreach ($list as $n) {
                if (($n['kind'] ?? '') === 'depense') {
                    $hasD = true;
                }
                if (($n['kind'] ?? '') === 'livret') {
                    $hasL = true;
                }
            }
            if (!$hasD || !$hasL) {
                continue;
            }
            foreach ($list as $n) {
                if (($n['kind'] ?? '') === 'livret') {
                    $rank[$n['id']]++;
                }
            }
        }
        $layerCount = $compactRanks();
        $maxRank = max(0, $layerCount - 1);

        $layers = array_fill(0, $maxRank + 1, []);
        $seed = array_values($graph);
        usort($seed, static function (array $a, array $b) use ($kindWeight): int {
            $dw = ($kindWeight[$a['kind'] ?? ''] ?? 0) <=> ($kindWeight[$b['kind'] ?? ''] ?? 0);
            if ($dw !== 0) {
                return $dw;
            }
            $dy = ($a['y'] ?? 0) <=> ($b['y'] ?? 0);

            return $dy !== 0 ? $dy : strnatcasecmp((string) ($a['title'] ?? ''), (string) ($b['title'] ?? ''));
        });
        foreach ($seed as $n) {
            $layers[$rank[$n['id']]][] = $n['id'];
        }

        $indexOf = static function (array $layers): array {
            $idx = [];
            foreach ($layers as $ids) {
                foreach ($ids as $i => $id) {
                    $idx[$id] = $i;
                }
            }

            return $idx;
        };
        $sortByBary = static function (array $ids, array $neigh, array $layers) use ($indexOf): array {
            $idx = $indexOf($layers);
            usort($ids, static function (string $a, string $b) use ($neigh, $idx): int {
                $bar = static function (string $id) use ($neigh, $idx): float {
                    $ns = $neigh[$id] ?? [];
                    if ($ns === []) {
                        return (float) ($idx[$id] ?? 0);
                    }
                    $s = 0.0;
                    foreach ($ns as $n) {
                        $s += $idx[$n] ?? 0;
                    }

                    return $s / count($ns);
                };
                $d = $bar($a) <=> $bar($b);

                return $d !== 0 ? $d : (($idx[$a] ?? 0) <=> ($idx[$b] ?? 0));
            });

            return $ids;
        };

        for ($iter = 0; $iter < 4; $iter++) {
            for ($i = 1, $n = count($layers); $i < $n; $i++) {
                $layers[$i] = $sortByBary($layers[$i], $preds, $layers);
            }
        }

        $nodeW = 244.0;
        $gapX = 168.0;
        $gapY = 56.0;
        $originX = 48.0;
        $originY = 40.0;
        $heights = [];
        foreach ($graph as $id => $n) {
            $heights[$id] = $heightOf($n);
        }
        $pos = [];
        foreach ($layers as $li => $ids) {
            $x = $originX + $li * ($nodeW + $gapX);
            $y = $originY;
            foreach ($ids as $id) {
                $pos[$id] = ['x' => (int) round($x), 'y' => (int) round($y)];
                $y += $heights[$id] + $gapY;
            }
            if (count($ids) !== 1) {
                continue;
            }
            $id = $ids[0];
            $ps = $preds[$id];
            if ($ps === []) {
                continue;
            }
            $sum = 0.0;
            foreach ($ps as $pid) {
                $sum += ($pos[$pid]['y'] ?? $originY) + $heights[$pid] / 2;
            }
            $pos[$id]['y'] = (int) round(max($originY, $sum / count($ps) - $heights[$id] / 2));
        }

        foreach ($payload['nodes'] as &$n) {
            $id = $n['id'] ?? '';
            if (isset($pos[$id])) {
                $n['x'] = $pos[$id]['x'];
                $n['y'] = $pos[$id]['y'];
            }
        }
        unset($n);

        return $payload;
    }

    public static function mentions(): array
    {
        return [
            'eyebrow' => 'Mentions légales',
            'title' => 'Mentions légales',
            'lede' => 'Informations légales relatives à l’éditeur du site repartio.fr, conformément à la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l’économie numérique.',
            'meta' => ['Version 1.2', 'À jour au 1er août 2026', 'Droit français'],
            'sections' => [
                ['h' => 'Éditeur du site', 'ps' => ['Le site repartio.fr et le service qu’il héberge sont édités par la société ci-dessous.'], 'rows' => [
                    ['k' => 'Dénomination', 'v' => 'Repartio SAS (à compléter)'],
                    ['k' => 'Forme juridique', 'v' => 'Société par actions simplifiée'],
                    ['k' => 'Contact', 'v' => 'bonjour@repartio.fr'],
                ]],
                ['h' => 'Hébergement', 'ps' => ['Le site et les données du service sont hébergés au sein de l’Union européenne.']],
                ['h' => 'Nature du service', 'ps' => ['repartio est un outil de simulation. Il ne fournit ni conseil en investissement, ni service de paiement.']],
                ['h' => 'Propriété intellectuelle', 'ps' => ['Les circuits créés par un utilisateur restent la propriété de cet utilisateur.']],
                ['h' => 'Droit applicable', 'ps' => ['Les présentes mentions sont soumises au droit français.']],
            ],
        ];
    }

    public static function cgu(): array
    {
        return [
            'eyebrow' => 'CGU & CGV',
            'title' => 'Conditions générales',
            'lede' => 'Les règles d’utilisation de repartio.fr, du plan Libre au plan Foyer.',
            'meta' => ['Version 1.0', 'À jour au 1er août 2026'],
            'sections' => [
                ['h' => 'Objet', 'ps' => ['Les présentes conditions régissent l’accès au service de simulation de circuits de revenus proposé sur repartio.fr.']],
                ['h' => 'Compte', 'ps' => ['La création d’un compte est gratuite. Vous êtes responsable de la confidentialité de vos identifiants.']],
                ['h' => 'Plans', 'ps' => ['Le plan Libre permet trois circuits. Les plans Complet et Foyer lèvent cette limite et ajoutent des fonctions de comparaison.']],
                ['h' => 'Résiliation', 'ps' => ['Vous pouvez supprimer votre compte à tout moment. La suppression est immédiate et sans rétention.']],
                ['h' => 'Responsabilité', 'ps' => ['repartio est un outil de simulation, pas un conseil en investissement. Les résultats dépendent des montants que vous saisissez.']],
            ],
        ];
    }

    public static function privacy(): array
    {
        return [
            'eyebrow' => 'Confidentialité',
            'title' => 'Politique de confidentialité',
            'lede' => 'Ce que nous collectons, pourquoi, et comment exercer vos droits.',
            'meta' => ['RGPD', 'À jour au 1er août 2026'],
            'sections' => [
                ['h' => 'Responsable', 'ps' => ['Le responsable de traitement est l’éditeur de repartio.fr. Contact : bonjour@repartio.fr.']],
                ['h' => 'Données collectées', 'ps' => ['Identité (prénom, e-mail), identifiants techniques, circuits que vous créez, messages de contact. Aucune donnée bancaire n’est collectée.']],
                ['h' => 'Finalités', 'ps' => ['Fournir le service, sécuriser les comptes, répondre aux messages, envoyer les e-mails transactionnels.']],
                ['h' => 'Durée', 'ps' => ['Les données de compte sont conservées jusqu’à suppression. Les messages de contact sont conservés le temps du traitement.']],
                ['h' => 'Vos droits', 'ps' => ['Accès, rectification, export, suppression : depuis votre espace, ou par e-mail à bonjour@repartio.fr.']],
            ],
        ];
    }
}
