<?php

declare(strict_types=1);

namespace App;

class Articles
{
    public static function featuredSlug(): string
    {
        return 'couple-12338';
    }

    public static function index(): array
    {
        return [
            [
                'slug' => 'journal-versions',
                'tag' => 'Produit',
                'read' => '4 min',
                'date' => '22 août 2026',
                't' => 'Journal des versions',
                'd' => 'Ce qui a changé dans le moteur, le canvas et les projections — mois par mois, sans rummage.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Nouveautés produit, par mois',
            ],
            [
                'slug' => 'taux-plafonds-2026',
                'tag' => 'Réglementaire',
                'read' => '5 min',
                'date' => '20 août 2026',
                't' => 'Taux et plafonds 2026',
                'd' => 'Livret A, LDDS, LEP, livrets jeunes : les barèmes utilisés par le moteur, et ce qu’ils changent quand on épargne chaque mois.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Livret A · LDDS · LEP · livrets jeunes',
            ],
            [
                'slug' => 'couple-12338',
                'tag' => 'Étude de cas',
                'read' => '12 min',
                'date' => '18 août 2026',
                't' => 'Un couple, 12 338 € par mois, zéro euro non affecté',
                'd' => 'Le circuit complet d’une famille de quatre : deux salaires, une auto-entreprise, un local loué, deux comptes joints et six livrets.',
                'interactive' => true,
                'featured' => true,
                'topics' => ['Couple', 'Auto-entreprise', 'Livrets'],
                'cta' => ['href' => '/circuit-rempli', 'label' => 'Voir le circuit commenté'],
                'figures' => [
                    ['k' => 'Blocs', 'v' => '23', 'tone' => 'ink'],
                    ['k' => 'Entrées / mois', 'v' => '12 338 €', 'tone' => 'ink'],
                    ['k' => 'Épargné / mois', 'v' => '5 234 €', 'tone' => 'teal'],
                    ['k' => 'Non affecté', 'v' => '0 €', 'tone' => 'teal'],
                    ['k' => 'À 60 mois', 'v' => '105 615 €', 'tone' => 'ink'],
                ],
                'leadRows' => [
                    ['k' => 'Entrées / mois', 'v' => '12 338 €'],
                    ['k' => 'Épargné / mois', 'v' => '5 234 €'],
                    ['k' => 'Patrimoine à 60 mois', 'v' => '105 615 €'],
                ],
            ],
            [
                'slug' => 'anatomie-repartiteur',
                'tag' => 'Méthode',
                'read' => '6 min',
                'date' => '16 août 2026',
                't' => 'Anatomie d’un répartiteur',
                'd' => 'Parts, pourcentages, débordement : comment découper un flux sans jamais laisser un euro sans destination.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Parts, pourcentages, débordement',
            ],
            [
                'slug' => 'budget-tableur',
                'tag' => 'Méthode',
                'read' => '6 min',
                'date' => '12 août 2026',
                't' => 'Pourquoi votre budget ne tient pas dans un tableur',
                'd' => 'Un tableur décrit des totaux ; un circuit décrit des chemins. La différence se voit au troisième compte joint.',
                'interactive' => true,
            ],
            [
                'slug' => 'tableur-vers-circuit',
                'tag' => 'Méthode',
                'read' => '5 min',
                'date' => '10 août 2026',
                't' => 'Passer d’un tableur à un circuit',
                'd' => 'Quatre étapes, vingt minutes, et plus aucune colonne masquée. Une migration guidée, ligne par ligne.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Migration en 20 minutes',
            ],
            [
                'slug' => 'ordre-livrets',
                'tag' => 'Réglementaire',
                'read' => '4 min',
                'date' => '4 août 2026',
                't' => 'Ordre de remplissage des livrets réglementés',
                'd' => 'LEP, LDDS, Livret A : dans quel ordre saturer quand on épargne 1 500 € par mois.',
                'interactive' => true,
            ],
            [
                'slug' => 'compte-joint-factures',
                'tag' => 'Méthode',
                'read' => '7 min',
                'date' => '28 juillet 2026',
                't' => 'Le compte joint « factures » change tout',
                'd' => 'Séparer les prélèvements du quotidien supprime la moitié des arbitrages mensuels.',
                'interactive' => true,
            ],
            [
                'slug' => 'urssaf-auto-entrepreneur',
                'tag' => 'Étude de cas',
                'read' => '9 min',
                'date' => '19 juillet 2026',
                't' => 'Auto-entrepreneur : provisionner l’URSSAF comme une dépense',
                'd' => 'Un bloc dépense dédié évite la mauvaise surprise trimestrielle, et rend le revenu net lisible.',
                'interactive' => true,
            ],
            [
                'slug' => 'plafond-atteint',
                'tag' => 'Réglementaire',
                'read' => '5 min',
                'date' => '9 juillet 2026',
                't' => 'Ce que « plafond atteint » veut vraiment dire',
                'd' => 'Un livret plein continue de produire des intérêts au-delà du plafond. Ce que ça change dans une projection.',
                'interactive' => true,
            ],
            [
                'slug' => 'pourcentages-ou-fixes',
                'tag' => 'Méthode',
                'read' => '8 min',
                'date' => '1 juillet 2026',
                't' => 'Répartir en pourcentages, ou en montants fixes ?',
                'd' => 'Les pourcentages encaissent les variations de revenu ; les montants fixes protègent les objectifs.',
                'interactive' => true,
            ],
            [
                'slug' => 'fil-tout-le-reste',
                'tag' => 'Produit',
                'read' => '3 min',
                'date' => '24 juin 2026',
                't' => 'Nouveau : le fil « tout le reste »',
                'd' => 'Un fil qui emporte le solde d’un bloc, pour ne plus recalculer après chaque augmentation.',
                'interactive' => true,
            ],
            [
                'slug' => 'famille-de-quatre',
                'tag' => 'Étude de cas',
                'read' => '11 min',
                'date' => '14 juin 2026',
                't' => 'Famille de quatre, deux livrets enfants, un objectif apport',
                'd' => 'Le circuit complet, avec les compromis assumés et ce que la projection à cinq ans a fait changer.',
                'interactive' => true,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir un modèle famille'],
            ],
            [
                'slug' => 'scenarios-compares',
                'tag' => 'Produit',
                'read' => '4 min',
                'date' => '2 juin 2026',
                't' => 'Les scénarios comparés, en pratique',
                'd' => 'Deux variantes d’un même circuit côte à côte, et la lecture de l’écart de patrimoine.',
                'interactive' => true,
            ],
        ];
    }

    public static function guides(): array
    {
        $out = [];
        foreach (self::index() as $post) {
            if (!empty($post['guide'])) {
                $out[] = $post;
            }
        }

        return $out;
    }

    public static function find(string $slug): ?array
    {
        foreach (self::index() as $post) {
            if ($post['slug'] !== $slug) {
                continue;
            }
            $post['blocks'] = self::blocks($slug);
            $post['toc'] = self::toc($post['blocks']);
            $post['related'] = self::related($slug);
            $post['disclaimer'] = 'Les chiffres de cette note sont des exemples de simulation. Ils ne constituent pas un conseil en investissement. Les taux et plafonds repris sont ceux du moteur repartio.';

            return $post;
        }

        return null;
    }

    /** @return list<array<string, mixed>> */
    public static function related(string $slug, int $limit = 3): array
    {
        $current = null;
        foreach (self::index() as $post) {
            if ($post['slug'] === $slug) {
                $current = $post;
                break;
            }
        }
        $picked = [];
        foreach (self::index() as $post) {
            if ($post['slug'] === $slug) {
                continue;
            }
            if ($current && $post['tag'] === $current['tag']) {
                $picked[] = $post;
            }
        }
        foreach (self::index() as $post) {
            if ($post['slug'] === $slug) {
                continue;
            }
            $exists = false;
            foreach ($picked as $item) {
                if ($item['slug'] === $post['slug']) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $picked[] = $post;
            }
        }

        return array_slice($picked, 0, $limit);
    }

    /** @param list<array<string, mixed>> $blocks */
    private static function toc(array $blocks): array
    {
        $toc = [];
        $n = 0;
        foreach ($blocks as $block) {
            if (($block['type'] ?? '') !== 'h') {
                continue;
            }
            $n++;
            $toc[] = [
                'n' => str_pad((string) $n, 2, '0', STR_PAD_LEFT),
                'label' => $block['text'],
                'id' => $block['id'] ?? slugify($block['text']),
            ];
        }

        return $toc;
    }

    /** @return list<array<string, mixed>> */
    private static function blocks(string $slug): array
    {
        return match ($slug) {
            'couple-12338' => self::couple(),
            'budget-tableur' => self::tableur(),
            'ordre-livrets' => self::ordre(),
            'compte-joint-factures' => self::joints(),
            'urssaf-auto-entrepreneur' => self::urssaf(),
            'plafond-atteint' => self::plafond(),
            'pourcentages-ou-fixes' => self::mix(),
            'fil-tout-le-reste' => self::reste(),
            'famille-de-quatre' => self::famille(),
            'scenarios-compares' => self::scenarios(),
            'taux-plafonds-2026' => self::taux(),
            'anatomie-repartiteur' => self::repart(),
            'tableur-vers-circuit' => self::migrate(),
            'journal-versions' => self::journal(),
            default => [],
        };
    }

    private static function couple(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le point de départ : un tableur qui ne tenait plus'],
            ['type' => 'p', 'text' => 'Julien et Malorie ont quatre sources de revenus, deux enfants, un local commercial loué et six livrets réglementés. Leur tableur fonctionnait très bien tant qu’il n’y avait qu’un salaire et un compte joint. Il a cessé de fonctionner le jour où l’auto-entreprise de Julien est devenue la première source du foyer.'],
            ['type' => 'p', 'text' => 'Le problème n’était pas le calcul — un tableur calcule parfaitement — mais la lecture. Personne ne savait plus répondre à la question « si l’auto-entreprise baisse de mille euros, qu’est-ce qui s’arrête en premier ? ». La réponse existait, quelque part, dans une colonne masquée.'],
            ['type' => 'h', 'text' => 'Les entrées, telles qu’elles ont été posées'],
            ['type' => 'p', 'text' => 'Cinq blocs de revenus, dont deux variables. Les montants retenus sont des moyennes lissées sur les douze derniers mois, arrondies à l’euro inférieur — un choix conservateur assumé.'],
            ['type' => 'table', 'head' => 'Bloc de revenu', 'rows' => [
                ['k' => 'Auto-entreprise (Julien)', 'v' => '5 000 €', 'c' => 'teal'],
                ['k' => 'Salaire (Malorie)', 'v' => '3 073 €', 'c' => 'teal'],
                ['k' => 'Loyers du local', 'v' => '2 000 €', 'c' => 'teal'],
                ['k' => 'Salaire (Julien)', 'v' => '1 500 €', 'c' => 'teal'],
                ['k' => 'Allocations', 'v' => '765 €', 'c' => 'teal'],
            ]],
            ['type' => 'widget', 'id' => 'couple'],
            ['type' => 'h', 'text' => 'La première surprise : la provision URSSAF'],
            ['type' => 'p', 'text' => 'Dans le tableur, les cotisations étaient une ligne trimestrielle, donc invisible deux mois sur trois. En posant un bloc dépense alimenté chaque mois depuis le compte professionnel, le revenu réellement disponible a perdu 1 340 € — et gagné en honnêteté.'],
            ['type' => 'quote', 'text' => 'On croyait gagner sept mille euros avec l’auto-entreprise. On en gagne cinq mille six cent soixante, et c’est très bien — au moins c’est vrai.', 'by' => 'Julien, sur le circuit terminé'],
            ['type' => 'h', 'text' => 'Deux comptes joints, pas un'],
            ['type' => 'p', 'text' => 'La décision structurante du circuit n’est pas financière, elle est organisationnelle : séparer les prélèvements du quotidien. Le joint « Factures » ne reçoit que ce que les prélèvements consomment, au centime. Le joint « Quotidien » reçoit une enveloppe fixe.'],
            ['type' => 'list', 'items' => [
                'Joint Factures : 3 254 € par mois, entièrement consommé par les prélèvements.',
                'Joint Quotidien : 3 128 € par mois, dont 400 € de dépenses libres non justifiées.',
                'Aucun arbitrage mensuel n’est nécessaire tant que les deux enveloppes tiennent.',
            ]],
            ['type' => 'h', 'text' => 'L’épargne : deux répartiteurs, six livrets'],
            ['type' => 'p', 'text' => 'Chacun conserve son propre répartiteur, qui découpe ce qu’il reçoit entre ses trois livrets. C’est ce qui permet de dire « mon épargne » sans avoir à négocier chaque virement.'],
            ['type' => 'table', 'head' => 'Destination d’épargne', 'rows' => [
                ['k' => 'Répartiteur Julien', 'v' => '3 665 €', 'c' => 'orange'],
                ['k' => 'Répartiteur Malorie', 'v' => '1 459 €', 'c' => 'orange'],
                ['k' => 'Livrets des enfants', 'v' => '110 €', 'c' => 'blue'],
                ['k' => 'Total épargné / mois', 'v' => '5 234 €', 'c' => 'teal'],
            ]],
            ['type' => 'h', 'text' => 'Ce que la projection a changé'],
            ['type' => 'p', 'text' => 'À soixante mois, le patrimoine atteint 105 615 €. Mais l’information utile n’est pas ce total : c’est la liste des dates de saturation. Trois livrets sont déjà pleins, un le sera dans dix mois, un autre dans seize.'],
            ['type' => 'p', 'text' => 'Aucun débordement n’était câblé. Concrètement, à partir du dixième mois, 1 100 € par mois n’auraient plus eu de destination. Le compteur « non affecté » restait à zéro dans le mois type, mais la projection révélait le blocage à venir.'],
            ['type' => 'list', 'items' => [
                'Ajout d’un fil de débordement du LDDS vers le Livret A.',
                'Ajout d’un second débordement du Livret A vers un bloc « épargne longue » à définir.',
                'Décision reportée sur le support de cette épargne longue — hors du périmètre de repartio.',
            ]],
            ['type' => 'h', 'text' => 'Ce qu’on retient'],
            ['type' => 'p', 'text' => 'Un circuit ne rend pas les décisions plus faciles, il rend leurs conséquences visibles. Ici, la question de départ — « qu’est-ce qui s’arrête si l’auto-entreprise baisse ? » — a désormais une réponse d’une ligne : le répartiteur de Julien, avant toute autre chose.'],
        ];
    }

    private static function tableur(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le tableur décrit un stock, pas un chemin'],
            ['type' => 'p', 'text' => 'Une feuille de budget additionne des catégories. C’est utile pour savoir si le mois « tient ». Ça l’est beaucoup moins pour savoir d’où vient un euro, par quel compte il transite, et ce qui se passe s’il n’arrive plus.'],
            ['type' => 'p', 'text' => 'Tant qu’il n’y a qu’un salaire et un compte, les deux représentations coïncident. La rupture arrive au troisième compte — souvent le deuxième joint, ou le compte pro d’une auto-entreprise. À ce moment, le tableur continue de totaliser. Il arrête de raconter.'],
            ['type' => 'widget', 'id' => 'tableur'],
            ['type' => 'h', 'text' => 'Trois signes que la feuille a saturé'],
            ['type' => 'list', 'items' => [
                'Une colonne « virements internes » qu’on soustrait pour ne pas double-compter — et qu’on oublie un mois sur deux.',
                'Des formules qui pointent vers une feuille « Julien » et une feuille « Malorie », sans jamais dire qui alimente le joint.',
                'L’impossibilité de répondre, en une phrase, à « si ce revenu baisse, quelle enveloppe se réduit ? ».',
            ]],
            ['type' => 'h', 'text' => 'Ce qu’un circuit force à écrire'],
            ['type' => 'p', 'text' => 'Un circuit n’accepte pas un total orphelin. Chaque montant entre par un bloc, circule le long d’un fil, et arrive quelque part. S’il reste de l’argent « non affecté », le mois n’est pas encore décrit — ce n’est pas un oubli cosmétique, c’est un trou dans le récit.'],
            ['type' => 'quote', 'text' => 'Le tableur savait que 12 338 € entraient. Il ne savait plus où ils se séparaient.', 'by' => 'Lecture d’un circuit à 23 blocs'],
            ['type' => 'h', 'text' => 'Quand garder le tableur'],
            ['type' => 'p', 'text' => 'Pour l’historique bancaire, les justifications, le suivi au centime d’un mois réel. Le circuit, lui, décrit le mois type. Les deux outils ne se remplacent pas : l’un archive, l’autre projette.'],
        ];
    }

    private static function ordre(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le taux décide, le plafond chronomètre'],
            ['type' => 'p', 'text' => 'Sur les livrets réglementés du moteur, le LEP rapporte 2,50 %, le Livret A et le LDDS 1,70 %. L’ordre de remplissage le plus mécanique est donc : saturer le LEP, puis le LDDS (plafond plus bas), puis le Livret A. Ce n’est pas un conseil de placement : c’est l’arithmétique des barèmes que le circuit porte.'],
            ['type' => 'table', 'head' => 'Livret', 'rows' => [
                ['k' => 'LEP — si éligible', 'v' => '2,50 % · 10 000 €', 'c' => 'teal'],
                ['k' => 'LDDS', 'v' => '1,70 % · 12 000 €', 'c' => 'blue'],
                ['k' => 'Livret A', 'v' => '1,70 % · 22 950 €', 'c' => 'blue'],
                ['k' => 'Livret jeune', 'v' => '1,70 % · 1 600 €', 'c' => 'navy'],
            ]],
            ['type' => 'widget', 'id' => 'ordre'],
            ['type' => 'h', 'text' => 'Pourquoi le LDDS avant le Livret A'],
            ['type' => 'p', 'text' => 'Même taux, plafond plus petit. En saturant d’abord le LDDS, on libère plus tôt un fil de débordement vers le Livret A. Si on fait l’inverse, le LDDS reste à moitié vide pendant des mois alors que le Livret A s’approche déjà de 22 950 € — et le surplus n’a nulle part où aller.'],
            ['type' => 'h', 'text' => 'Le piège du LEP oublié'],
            ['type' => 'p', 'text' => 'Beaucoup de foyers éligibles laissent le LEP à 1 800 € « pour voir » et versent le gros sur le Livret A. Sur 1 500 € d’épargne mensuelle, saturer d’abord les 8 200 € restants du LEP prend moins de six mois. Passé ce délai, chaque euro suivant peut basculer vers le LDDS sans rien recalculer — à condition d’avoir câblé le débordement.'],
            ['type' => 'callout', 'title' => 'Éligibilité', 'text' => 'Le LEP n’est pas ouvert à tous les foyers. Décochez-le dans le simulateur si vous n’y avez pas droit : l’ordre devient LDDS puis Livret A, sans changer le reste du raisonnement.'],
        ];
    }

    private static function joints(): array
    {
        return [
            ['type' => 'h', 'text' => 'Un seul joint mélange deux natures de dépense'],
            ['type' => 'p', 'text' => 'Les prélèvements sont prévisibles au centime : loyer, énergie, assurances, impôts. Le quotidien est un plafond, pas une liste : courses, essence, restos, « on verra ». Quand les deux circulent sur le même compte, chaque fin de mois devient un arbitrage — est-ce le weekend ou la mutuelle qui a trop pris ?'],
            ['type' => 'p', 'text' => 'Séparer les deux comptes ne change pas le total dépensé. Ça change le nombre de décisions. Le joint Factures se vide tout seul. Le joint Quotidien a une enveloppe : si elle tient, le mois est réussi, sans tableur.'],
            ['type' => 'widget', 'id' => 'joints'],
            ['type' => 'h', 'text' => 'La règle des deux enveloppes'],
            ['type' => 'list', 'items' => [
                'Factures = somme exacte des prélèvements du mois type. Ni plus, ni matelas « au cas où ».',
                'Quotidien = enveloppe fixe, y compris une ligne de dépenses libres assumée.',
                'Si une facture augmente, on ajuste le fil vers Factures — on ne pique pas dans le quotidien à la main.',
            ]],
            ['type' => 'quote', 'text' => 'On a arrêté de se demander qui avait trop dépensé. Le quotidien a une enveloppe. Les factures ont la leur. Fin de discussion.', 'by' => 'Lecture d’un circuit à deux joints'],
            ['type' => 'h', 'text' => 'Qui alimente quoi'],
            ['type' => 'p', 'text' => 'Dans un couple, chaque compte personnel verse un fixe vers Factures et un fixe vers Quotidien. Le reste part vers l’épargne personnelle. Personne n’a à « équilibrer » en fin de mois : l’équilibre est dans les fils.'],
        ];
    }

    private static function urssaf(): array
    {
        return [
            ['type' => 'h', 'text' => 'Une charge trimestrielle n’existe pas dans un mois type'],
            ['type' => 'p', 'text' => 'L’auto-entrepreneur encaisse un chiffre d’affaires, puis reçoit une facture URSSAF tous les trois mois. Dans un tableur mensuel, cette facture apparaît comme un accident. Dans un circuit, elle doit être une dépense mensuelle — sinon le revenu « disponible » est une illusion deux mois sur trois.'],
            ['type' => 'p', 'text' => 'Le geste est simple : un bloc dépense « Cotisations », alimenté chaque mois depuis le compte professionnel, au taux du régime. Ce qui sort ensuite vers le compte personnel est le net réel, pas le brut encaissé.'],
            ['type' => 'widget', 'id' => 'urssaf'],
            ['type' => 'h', 'text' => 'Ce que ça change à la lecture'],
            ['type' => 'p', 'text' => 'Sur 5 000 € de CA services, une provision à 21,2 % retire 1 060 €. Le foyer ne « gagne » plus 5 000 € : il en gagne 3 940 avant impôt sur le revenu. C’est moins flatteur, et c’est le seul chiffre qu’on peut câbler vers un joint ou un livret sans se mentir.'],
            ['type' => 'list', 'items' => [
                'Le fil vers l’URSSAF est un fixe, recalculé si le CA moyen change.',
                'Le reste du compte pro part vers le compte personnel — « tout le reste », jamais un pourcentage du CA brut.',
                'Les mois bas : la provision baisse avec le CA si vous l’exprimez en pourcentage du bloc revenu. En fixe, elle peut trop prélever.',
            ]],
            ['type' => 'callout', 'title' => 'Barème indicatif', 'text' => 'Les taux proposés ici sont des ordres de grandeur du régime micro-social (hors CFP, hors versement libératoire). Vérifiez le vôtre sur votre dernier avis URSSAF avant de figer un fil.'],
        ];
    }

    private static function plafond(): array
    {
        return [
            ['type' => 'h', 'text' => 'Plein ne veut pas dire stérile'],
            ['type' => 'p', 'text' => 'Un Livret A à 22 950 € n’accepte plus de versement. Il continue de produire des intérêts sur ce stock. Beaucoup de projections « arrêtent » le livret une fois le plafond touché, comme s’il disparaissait. C’est faux : le flux s’arrête, le stock travaille encore.'],
            ['type' => 'p', 'text' => 'Ce qui s’arrête vraiment, c’est la destination du versement mensuel. Sans fil de débordement, ces euros redeviennent « non affectés » — pas le mois type, le mois n+k, celui où le plafond est atteint.'],
            ['type' => 'widget', 'id' => 'plafond'],
            ['type' => 'h', 'text' => 'Ce que le moteur doit afficher'],
            ['type' => 'list', 'items' => [
                'La date de saturation, en mois, à partir du solde et du versement.',
                'Le surplus mensuel après cette date, et sa destination câblée.',
                'Les intérêts du livret plein, qui continuent d’entrer dans le patrimoine projeté.',
            ]],
            ['type' => 'quote', 'text' => 'Le compteur à zéro du mois type peut cacher un trou dans dix mois. Seule la projection le voit.', 'by' => 'Note de terrain, juillet 2026'],
        ];
    }

    private static function mix(): array
    {
        return [
            ['type' => 'h', 'text' => 'Deux mécaniques, deux peurs'],
            ['type' => 'p', 'text' => 'Le montant fixe protège une destination : 400 € vers le LEP, quoi qu’il arrive. Le pourcentage protège une proportion : 20 % de ce qui reste, même si le mois est maigre. L’un casse quand le revenu baisse trop. L’autre laisse les objectifs sous-alimentés dès que le mois est bas.'],
            ['type' => 'p', 'text' => 'La combinaison utile, dans presque tous les circuits que nous voyons : les prélèvements en fixe, l’épargne de précaution en fixe tant qu’elle n’est pas saturée, et le surplus en pourcentage — ou mieux, en « tout le reste ».'],
            ['type' => 'widget', 'id' => 'mix'],
            ['type' => 'h', 'text' => 'Comment lire un mois bas'],
            ['type' => 'p', 'text' => 'Baissez le revenu dans le simulateur. Si les fixes dépassent l’entrée, le circuit est en déficit : ce n’est plus une répartition, c’est un découvert. Si les pourcentages seuls restent, l’épargne fond mais le mois reste cohérent. Le bon mélange est celui qui ne passe jamais sous zéro sur votre mois bas réel — pas sur le mois moyen.'],
            ['type' => 'list', 'items' => [
                'Fixe d’abord : factures, loyer, provision URSSAF, virement enfants.',
                'Pourcentage ensuite : seulement s’il reste un flux après les fixes.',
                'Jamais un pourcentage sur un revenu que vous n’êtes pas sûr d’encaisser.',
            ]],
        ];
    }

    private static function reste(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le recalcul manuel est une dette'],
            ['type' => 'p', 'text' => 'Vous augmentez un salaire de 180 €. Dans un circuit à fils fixes, ces 180 € deviennent « non affectés » jusqu’à ce que vous les répartissiez à la main. C’est précisément le geste qu’on oublie en janvier, et qu’on retrouve en juin dans un matelas de compte courant.'],
            ['type' => 'p', 'text' => 'Le fil « tout le reste » emporte le solde d’un bloc après les fixes. Une augmentation, une baisse, un mois atypique : la destination finale absorbe l’écart. Le compteur reste à zéro sans que vous touchiez aux autres fils.'],
            ['type' => 'widget', 'id' => 'reste'],
            ['type' => 'h', 'text' => 'Où le poser'],
            ['type' => 'list', 'items' => [
                'En sortie de compte personnel, vers le répartiteur d’épargne — le cas le plus fréquent.',
                'En sortie de compte pro, après la provision URSSAF, vers le compte perso.',
                'En sortie de livret saturé, vers le livret suivant (c’est un débordement, même mécanique).',
            ]],
            ['type' => 'callout', 'title' => 'Un seul « reste » par bloc', 'text' => 'Deux fils « tout le reste » sur le même bloc n’ont pas de sens : le premier prend tout. Le second reste à zéro. Un reste, puis des fixes — jamais l’inverse dans l’autre sens.'],
        ];
    }

    private static function famille(): array
    {
        return [
            ['type' => 'h', 'text' => 'Trois objectifs, un seul surplus'],
            ['type' => 'p', 'text' => 'Après les factures et le quotidien, il reste une enveloppe. Dans cette famille, elle doit nourrir deux livrets jeunes, une épargne de précaution, et un apport immobilier. Le conflit n’est pas moral, il est arithmétique : chaque euro posé sur un livret enfant retarde l’apport.'],
            ['type' => 'p', 'text' => 'Le circuit ne tranche pas à votre place. Il rend le délai visible. C’est souvent suffisant pour arrêter de « voir plus tard » un versement de 50 € qui, à cinq ans, déplace l’apport de plusieurs mois.'],
            ['type' => 'widget', 'id' => 'famille'],
            ['type' => 'h', 'text' => 'Les compromis qu’ils ont gardés'],
            ['type' => 'list', 'items' => [
                '55 € par enfant, chaque mois, plafond 1 600 € — saturé en moins de deux ans si on part de 400 €.',
                'Précaution plafonnée à trois mois de charges, ensuite tout le flux bascule vers l’apport.',
                'L’apport n’a pas de taux dans le moteur : c’est un bac à remplir, pas un livret réglementé.',
            ]],
            ['type' => 'h', 'text' => 'Ce que cinq ans ont déplacé'],
            ['type' => 'p', 'text' => 'En montant les livrets enfants de 30 à 55 €, l’apport reculait de quatre mois. Ils ont gardé les 55 € : l’objectif « plus tard » des enfants avait une date, l’apport aussi. Voir les deux dates sur la même projection a clos le débat plus vite qu’un tableur à scénarios cachés.'],
        ];
    }

    private static function scenarios(): array
    {
        return [
            ['type' => 'h', 'text' => 'Un écart n’existe que si le reste est identique'],
            ['type' => 'p', 'text' => 'Comparer « plus d’épargne » à « plus de quotidien » mélange deux décisions. Un scénario utile ne change qu’un fil. Ici : 400 € mensuels, soit vers le LEP d’abord, soit vers le Livret A. Même entrée, mêmes dépenses, même horizon. Seul l’ordre change.'],
            ['type' => 'p', 'text' => 'La lecture n’est pas « quel placement est le meilleur ». C’est « combien de mois gagne-t-on à saturer le taux le plus haut, et à partir de quand les deux courbes se rejoignent une fois les plafonds touchés ».'],
            ['type' => 'widget', 'id' => 'scenarios'],
            ['type' => 'h', 'text' => 'Comment comparer sans se mentir'],
            ['type' => 'list', 'items' => [
                'Dupliquer le circuit, ne modifier qu’un fil, lancer la même horizon.',
                'Lire l’écart à 12, 36 et 60 mois — pas seulement le total final.',
                'Noter la date où les plafonds rendent les deux variantes identiques : après ça, l’ordre ne joue plus.',
            ]],
        ];
    }

    private static function taux(): array
    {
        return [
            ['type' => 'h', 'text' => 'Les barèmes que porte le moteur'],
            ['type' => 'p', 'text' => 'repartio n’invente pas de taux. Chaque livret réglementé arrive avec son plafond et son taux, identiques à ceux que vous posez à la main si vous partez d’un livret vierge. Cette fiche sert à les lire ensemble, et à voir en combien de mois un versement les sature.'],
            ['type' => 'table', 'head' => 'Produit', 'rows' => [
                ['k' => 'Livret A', 'v' => '1,70 % · 22 950 €', 'c' => 'blue'],
                ['k' => 'LDDS', 'v' => '1,70 % · 12 000 €', 'c' => 'blue'],
                ['k' => 'LEP', 'v' => '2,50 % · 10 000 €', 'c' => 'teal'],
                ['k' => 'Livret jeune', 'v' => '1,70 % · 1 600 €', 'c' => 'navy'],
            ]],
            ['type' => 'widget', 'id' => 'taux'],
            ['type' => 'h', 'text' => 'Ce que le plafond ne dit pas'],
            ['type' => 'p', 'text' => 'Le plafond est un stock maximal, pas un flux. Un livret plein continue de capitaliser. Un livret jeune saturé en 18 mois n’est pas « fini » : ses intérêts restent dans la projection, et le versement mensuel doit basculer ailleurs — sinon il redevient non affecté.'],
            ['type' => 'callout', 'title' => 'Mise à jour', 'text' => 'Si un barème officiel change, le moteur est mis à jour et cette fiche suit. Les circuits déjà créés gardent les taux saisis, sauf si vous rechargez le préréglage du livret.'],
        ];
    }

    private static function repart(): array
    {
        return [
            ['type' => 'h', 'text' => 'Un répartiteur ne garde rien'],
            ['type' => 'p', 'text' => 'Contrairement au compte, le répartiteur n’a pas de matelas. Tout ce qu’il reçoit ressort, découpé en parts. S’il affiche 92 %, il manque 8 % : ce n’est pas une réserve, c’est un trou. S’il affiche 110 %, un fil trop-promet.'],
            ['type' => 'p', 'text' => 'Trois modes de sortie : fixe (servi en premier), pourcentage du reçu, tout le reste. L’ordre de service n’est pas cosmétique. Les fixes partent, puis les pourcentages, puis le reste. Si les fixes dépassent l’entrée, les pourcentages n’ont plus rien à se partager.'],
            ['type' => 'widget', 'id' => 'repart'],
            ['type' => 'h', 'text' => 'Le débordement n’est pas une part'],
            ['type' => 'p', 'text' => 'Quand un livret saturé renvoie son surplus vers le répartiteur suivant — ou vers un autre livret — ce n’est pas une quatrième part. C’est un événement dans le temps. Le mois type peut être à 100 % et malgré tout produire un débordement au mois 14. Il faut le câbler aujourd’hui, pas le jour où le plafond clignote.'],
            ['type' => 'list', 'items' => [
                'Un répartiteur par personne, plutôt qu’un seul pour le foyer : chacun lit « son » épargne.',
                'Les parts en pourcentages quand l’entrée varie ; les fixes pour les plafonds à saturer vite.',
                'Toujours un fil « reste » ou un débordement, sinon le 100 % d’aujourd’hui devient un trou demain.',
            ]],
        ];
    }

    private static function migrate(): array
    {
        return [
            ['type' => 'h', 'text' => 'Ne pas recopier la feuille, la traduire'],
            ['type' => 'p', 'text' => 'Importer un tableur ligne à ligne produit un circuit illisible : trente dépenses, zéro chemin. La migration utile tient en quatre passes. Cochez-les dans l’ordre ; à la dernière, le compteur non affecté doit tomber à zéro.'],
            ['type' => 'widget', 'id' => 'migrate'],
            ['type' => 'h', 'text' => 'Ce qu’on laisse dans le tableur'],
            ['type' => 'p', 'text' => 'L’historique des mois réels, les justificatifs, le suivi d’un découvert ponctuel. Le circuit reprend le mois type, pas les 17 lignes de « courses — carrefour — 42,30 € ». Si une catégorie n’a pas de destination stable, c’est une ligne du quotidien, pas un bloc.'],
            ['type' => 'quote', 'text' => 'Vingt minutes pour un foyer simple. Une demi-heure dès qu’il y a un compte pro. Plus long que ça, c’est qu’on recopie au lieu de câbler.', 'by' => 'FAQ, prise en main'],
        ];
    }

    private static function journal(): array
    {
        return [
            ['type' => 'h', 'text' => 'Ce qui entre dans ce journal'],
            ['type' => 'p', 'text' => 'Les changements qui modifient un circuit déjà posé, une projection, ou la façon de câbler. Pas les correctifs de rendu. Filtrez par famille, puis ouvrez une entrée pour le détail.'],
            ['type' => 'widget', 'id' => 'changelog'],
            ['type' => 'h', 'text' => 'Ce qui ne change pas'],
            ['type' => 'p', 'text' => 'Pas de connexion bancaire, pas de conseil en placement, pas de revente de données. Quand un barème réglementaire bouge, vos circuits gardent les taux saisis ; le préréglage neuf, lui, suit le barème.'],
        ];
    }
}
