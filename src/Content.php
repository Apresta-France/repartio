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

    public static function templatePayload(string $key): ?array
    {
        $node = static function (string $id, string $kind, string $title, float $x, float $y, float $amount, array $extra = []): array {
            return array_merge(compact('id', 'kind', 'title', 'x', 'y', 'amount'), $extra);
        };
        $templates = [
            'couple' => [
                'horizon' => 60,
                'nodes' => [
                    $node('r1', 'revenu', 'Salaire A', 40, 60, 2200),
                    $node('r2', 'revenu', 'Salaire B', 40, 240, 2800),
                    $node('c1', 'compte', 'Joint Factures', 360, 60, 0),
                    $node('c2', 'compte', 'Joint Quotidien', 360, 240, 0),
                    $node('p1', 'repartiteur', 'Épargne', 360, 420, 0),
                    $node('d1', 'depense', 'Prélèvements', 680, 60, 0),
                    $node('l1', 'livret', 'Livret A', 680, 240, 0, ['rate' => 1.7, 'cap' => 22950, 'start' => 0, 'preset' => 'livret-a']),
                    $node('l2', 'livret', 'LDDS', 680, 400, 0, ['rate' => 1.7, 'cap' => 12000, 'start' => 0, 'preset' => 'ldds']),
                ],
                'edges' => [
                    ['from' => 'r1', 'to' => 'c1', 'mode' => 'fixe', 'value' => 1800],
                    ['from' => 'r1', 'to' => 'c2', 'mode' => 'fixe', 'value' => 400],
                    ['from' => 'r2', 'to' => 'c2', 'mode' => 'fixe', 'value' => 1200],
                    ['from' => 'r2', 'to' => 'p1', 'mode' => 'reste', 'value' => 0],
                    ['from' => 'c1', 'to' => 'd1', 'mode' => 'reste', 'value' => 0],
                    ['from' => 'p1', 'to' => 'l1', 'mode' => 'fixe', 'value' => 800],
                    ['from' => 'p1', 'to' => 'l2', 'mode' => 'reste', 'value' => 0],
                ],
            ],
        ];
        return $templates[$key] ?? null;
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
