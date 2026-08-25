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
                'slug' => 'urssaf-auto-entrepreneur',
                'tag' => 'Activité',
                'read' => '14 min',
                'date' => '25 août 2026',
                't' => 'Auto-entrepreneur : provisionner l’URSSAF comme une dépense',
                'd' => 'Barème micro-social 2026, CFP, ACRE, versement libératoire : poser le vrai net chaque mois, pas la facture de mars.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Taux 2026 · CFP · ACRE · libératoire',
                'topics' => ['Activité', 'Budget'],
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir un modèle auto-entrepreneur'],
                'figures' => [
                    ['k' => 'BNC régime général', 'v' => '25,6 %', 'tone' => 'ink'],
                    ['k' => 'Services BIC', 'v' => '21,2 %', 'tone' => 'ink'],
                    ['k' => 'Vente', 'v' => '12,3 %', 'tone' => 'ink'],
                    ['k' => 'CIPAV', 'v' => '23,2 %', 'tone' => 'ink'],
                    ['k' => 'Plafond services', 'v' => '83 600 €', 'tone' => 'teal'],
                ],
            ],
            [
                'slug' => 'plafonds-micro-tva-2026',
                'tag' => 'Activité',
                'read' => '8 min',
                'date' => '25 août 2026',
                't' => 'Deux plafonds, deux bascules : micro et TVA',
                'd' => '83 600 € et 37 500 € ne sont pas la même limite. Le circuit doit voir la TVA arriver avant la sortie du régime.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Micro 2026–2028 · franchise TVA',
            ],
            [
                'slug' => 'versement-liberatoire',
                'tag' => 'Activité',
                'read' => '8 min',
                'date' => '25 août 2026',
                't' => 'Versement libératoire : payer l’impôt chaque mois, ou plus tard ?',
                'd' => 'Un pourcentage de plus sur le CA, ou un impôt annuel sur le revenu abattu. Le simulateur compare les deux câblages.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => '1 % · 1,7 % · 2,2 % · RFR 29 315 €',
            ],
            [
                'slug' => 'ca-irregulier',
                'tag' => 'Activité',
                'read' => '7 min',
                'date' => '25 août 2026',
                't' => 'CA en dents de scie : lisser pour ne pas vivre le trimestre',
                'd' => 'Six mois à zéro, trois mois chargés : la provision doit suivre la moyenne, pas la dernière facture.',
                'interactive' => true,
            ],
            [
                'slug' => 'eligibilite-lep',
                'tag' => 'Réglementaire',
                'read' => '6 min',
                'date' => '25 août 2026',
                't' => 'Êtes-vous encore dans les clous du LEP ?',
                'd' => '23 028 € de RFR pour une part, 35 326 € pour deux. Le taux à 2,50 % ne sert que si le livret peut rester ouvert.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Plafonds RFR 2026 · métropole',
            ],
            [
                'slug' => 'matelas-trois-mois',
                'tag' => 'Méthode',
                'read' => '6 min',
                'date' => '25 août 2026',
                't' => 'Le matelas de trois mois, en euros et en mois',
                'd' => 'Trois mois de charges, pas trois mois de revenus. Le circuit pose la cible, puis un fil jusqu’à saturation.',
                'interactive' => true,
            ],
            [
                'slug' => 'salaire-et-autoentreprise',
                'tag' => 'Étude de cas',
                'read' => '9 min',
                'date' => '25 août 2026',
                't' => 'Un salaire et une auto-entreprise : qui encaisse le choc ?',
                'd' => 'Le fixe tient les factures. Le variable nourrit l’épargne — jusqu’au mois où il ne le fait plus.',
                'interactive' => true,
            ],
            [
                'slug' => 'journal-versions',
                'tag' => 'Produit',
                'read' => '4 min',
                'date' => '25 août 2026',
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
                'date' => '25 août 2026',
                't' => 'Taux et plafonds 2026',
                'd' => 'Livret A, LDDS, LEP, CEL, PEL : les barèmes au 1er août 2026, et en combien de mois un versement les sature.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Livret A · LDDS · LEP · livrets jeunes',
            ],
            [
                'slug' => 'couple-12338',
                'tag' => 'Étude de cas',
                'read' => '12 min',
                'date' => '18 août 2026',
                't' => 'Un couple, 6 280 € par mois, zéro euro non affecté',
                'd' => 'Le circuit complet d’une famille de quatre : deux salaires, une auto-entreprise, un local loué, deux comptes joints et six livrets.',
                'interactive' => true,
                'featured' => true,
                'topics' => ['Couple', 'Auto-entreprise', 'Livrets'],
                'cta' => ['href' => '/circuit-rempli', 'label' => 'Voir le circuit commenté'],
                'figures' => [
                    ['k' => 'Blocs', 'v' => '23', 'tone' => 'ink'],
                    ['k' => 'Entrées / mois', 'v' => '6 280 €', 'tone' => 'ink'],
                    ['k' => 'Épargné / mois', 'v' => '860 €', 'tone' => 'teal'],
                    ['k' => 'Non affecté', 'v' => '0 €', 'tone' => 'teal'],
                    ['k' => 'À 60 mois', 'v' => '55 786 €', 'tone' => 'ink'],
                ],
                'leadRows' => [
                    ['k' => 'Entrées / mois', 'v' => '6 280 €'],
                    ['k' => 'Épargné / mois', 'v' => '860 €'],
                    ['k' => 'Patrimoine à 60 mois', 'v' => '55 786 €'],
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

    /** @return list<array<string, mixed>> */
    public static function recent(int $limit = 3): array
    {
        $out = [];
        foreach (self::index() as $post) {
            $post['topic'] = self::topicOf($post);
            $out[] = $post;
            if (count($out) >= $limit) {
                break;
            }
        }

        return $out;
    }

    /** @return list<string> */
    public static function topics(): array
    {
        return ['Budget', 'Épargne', 'Foyer', 'Activité', 'Produit'];
    }

    public static function topicOf(array $post): string
    {
        if (!empty($post['topic']) && is_string($post['topic'])) {
            return $post['topic'];
        }

        return match ($post['tag'] ?? '') {
            'Méthode' => 'Budget',
            'Réglementaire' => 'Épargne',
            'Étude de cas' => 'Foyer',
            'Activité' => 'Activité',
            default => 'Produit',
        };
    }

    /** @return list<array{q: string, a: string, slug: string}> */
    public static function doors(): array
    {
        return [
            ['q' => 'J’ai un tableur', 'a' => 'Le migrer en vingt minutes', 'slug' => 'tableur-vers-circuit'],
            ['q' => 'Je veux les barèmes', 'a' => 'Taux et plafonds 2026', 'slug' => 'taux-plafonds-2026'],
            ['q' => 'Je suis auto-entrepreneur', 'a' => 'Provisionner l’URSSAF 2026', 'slug' => 'urssaf-auto-entrepreneur'],
            ['q' => 'Je veux un foyer réel', 'a' => '6 280 €, zéro euro non affecté', 'slug' => 'couple-12338'],
        ];
    }

    /** @return list<array{id: string, kicker: string, title: string, lead: string, slugs: list<string>}> */
    public static function sections(): array
    {
        return [
            [
                'id' => 'commencer',
                'kicker' => '01 · Commencer',
                'title' => 'Quitter le tableur',
                'lead' => 'Traduire une feuille en chemins, puis comprendre ce qu’un répartiteur force à écrire.',
                'slugs' => ['tableur-vers-circuit', 'budget-tableur', 'anatomie-repartiteur'],
            ],
            [
                'id' => 'baremes',
                'kicker' => '02 · Barèmes',
                'title' => 'Les chiffres du moteur',
                'lead' => 'Taux, plafonds, ordre de remplissage : ce que le circuit porte, sans conseil de placement.',
                'slugs' => ['taux-plafonds-2026', 'ordre-livrets', 'plafond-atteint', 'eligibilite-lep'],
            ],
            [
                'id' => 'activite',
                'kicker' => '03 · Activité',
                'title' => 'Auto-entreprise, sans surprise',
                'lead' => 'Provisionner les cotisations, lire les plafonds, choisir le libératoire, lisser un CA irrégulier.',
                'slugs' => ['urssaf-auto-entrepreneur', 'plafonds-micro-tva-2026', 'versement-liberatoire', 'ca-irregulier'],
            ],
            [
                'id' => 'cas',
                'kicker' => '04 · Cas réels',
                'title' => 'Des circuits commentés',
                'lead' => 'Lire un foyer déjà câblé, puis baisser un revenu pour voir ce qui s’arrête.',
                'slugs' => ['couple-12338', 'famille-de-quatre', 'salaire-et-autoentreprise'],
            ],
            [
                'id' => 'mecanique',
                'kicker' => '05 · Mécanique',
                'title' => 'Les gestes qui tiennent',
                'lead' => 'Joints, fixes, pourcentages, débordement : les décisions qui suppriment l’arbitrage du 30.',
                'slugs' => ['compte-joint-factures', 'pourcentages-ou-fixes', 'fil-tout-le-reste', 'matelas-trois-mois'],
            ],
            [
                'id' => 'produit',
                'kicker' => '06 · Produit',
                'title' => 'Ce qui a changé',
                'lead' => 'Le journal du moteur, du canvas et des projections.',
                'slugs' => ['journal-versions', 'scenarios-compares'],
            ],
        ];
    }

    public static function find(string $slug): ?array
    {
        foreach (self::index() as $post) {
            if ($post['slug'] !== $slug) {
                continue;
            }
            $post['topic'] = self::topicOf($post);
            $post['blocks'] = self::blocks($slug);
            $post['toc'] = self::toc($post['blocks']);
            $post['related'] = self::related($slug);
            $post['disclaimer'] = 'Les chiffres de cette note sont des exemples de simulation. Ils ne constituent pas un conseil fiscal, social ou en investissement. Les barèmes repris sont ceux publiés au 25 août 2026 (Urssaf, ministère de l’Économie, Service-Public). Vérifiez votre dernier avis avant de figer un fil.';

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
            if ($current && self::topicOf($post) === self::topicOf($current)) {
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
            'plafonds-micro-tva-2026' => self::plafondsMicro(),
            'versement-liberatoire' => self::liberatoire(),
            'ca-irregulier' => self::irregulier(),
            'eligibilite-lep' => self::lepElig(),
            'matelas-trois-mois' => self::matelas(),
            'salaire-et-autoentreprise' => self::mixte(),
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
            ['type' => 'p', 'text' => 'Ce foyer a cinq sources de revenus, deux enfants, un local commercial loué et six livrets réglementés. Le tableur fonctionnait très bien tant qu’il n’y avait qu’un salaire et un compte joint. Il a cessé de fonctionner le jour où l’auto-entreprise est devenue la première source du foyer.'],
            ['type' => 'p', 'text' => 'Le problème n’était pas le calcul — un tableur calcule parfaitement — mais la lecture. Personne ne savait plus répondre à la question « si l’auto-entreprise baisse de mille euros, qu’est-ce qui s’arrête en premier ? ». La réponse existait, quelque part, dans une colonne masquée.'],
            ['type' => 'h', 'text' => 'Les entrées, telles qu’elles ont été posées'],
            ['type' => 'p', 'text' => 'Cinq blocs de revenus, dont deux variables. Les montants retenus sont des moyennes lissées sur les douze derniers mois, arrondies à l’euro inférieur — un choix conservateur assumé.'],
            ['type' => 'table', 'head' => 'Bloc de revenu', 'rows' => [
                ['k' => 'Auto-entreprise (A)', 'v' => '1 800 €', 'c' => 'teal'],
                ['k' => 'Salaire (B)', 'v' => '2 240 €', 'c' => 'teal'],
                ['k' => 'Loyers du local', 'v' => '540 €', 'c' => 'teal'],
                ['k' => 'Salaire (A)', 'v' => '1 320 €', 'c' => 'teal'],
                ['k' => 'Allocations', 'v' => '380 €', 'c' => 'teal'],
            ]],
            ['type' => 'widget', 'id' => 'couple'],
            ['type' => 'h', 'text' => 'La première surprise : la provision URSSAF'],
            ['type' => 'p', 'text' => 'Dans le tableur, les cotisations étaient une ligne trimestrielle, donc invisible deux mois sur trois. En posant un bloc dépense alimenté chaque mois depuis le compte professionnel, le revenu réellement disponible a perdu 380 € — et gagné en honnêteté.'],
            ['type' => 'quote', 'text' => 'On croyait disposer de tout le chiffre d’affaires. On en garde mille quatre cent vingt, et c’est très bien — au moins c’est vrai.', 'by' => 'Lecture du circuit, après provision URSSAF'],
            ['type' => 'h', 'text' => 'Deux comptes joints, pas un'],
            ['type' => 'p', 'text' => 'La décision structurante du circuit n’est pas financière, elle est organisationnelle : séparer les prélèvements du quotidien. Le joint « Factures » ne reçoit que ce que les prélèvements consomment, au centime. Le joint « Quotidien » reçoit une enveloppe fixe.'],
            ['type' => 'list', 'items' => [
                'Joint Factures : 2 280 € par mois, entièrement consommé par les prélèvements.',
                'Joint Quotidien : 2 760 € par mois, dont 280 € de dépenses libres non justifiées.',
                'Aucun arbitrage mensuel n’est nécessaire tant que les deux enveloppes tiennent.',
            ]],
            ['type' => 'h', 'text' => 'L’épargne : deux répartiteurs, six livrets'],
            ['type' => 'p', 'text' => 'Chacun conserve son propre répartiteur, qui découpe ce qu’il reçoit entre ses trois livrets. C’est ce qui permet de dire « mon épargne » sans avoir à négocier chaque virement.'],
            ['type' => 'table', 'head' => 'Destination d’épargne', 'rows' => [
                ['k' => 'Répartiteur A', 'v' => '460 €', 'c' => 'orange'],
                ['k' => 'Répartiteur B', 'v' => '320 €', 'c' => 'orange'],
                ['k' => 'Livrets des enfants', 'v' => '80 €', 'c' => 'blue'],
                ['k' => 'Total épargné / mois', 'v' => '860 €', 'c' => 'teal'],
            ]],
            ['type' => 'h', 'text' => 'Ce que la projection a changé'],
            ['type' => 'p', 'text' => 'À soixante mois, le patrimoine atteint 55 786 €. Mais l’information utile n’est pas ce total : c’est la liste des dates de saturation. Les LEP se remplissent en premier ; le Livret A, lui, a encore de la place.'],
            ['type' => 'p', 'text' => 'Aucun débordement n’était câblé. Concrètement, quand le LEP A sature, 200 € par mois n’ont plus de destination. Le compteur « non affecté » restait à zéro dans le mois type, mais la projection révélait le blocage à venir.'],
            ['type' => 'list', 'items' => [
                'Ajout d’un fil de débordement du LDDS vers le Livret A.',
                'Ajout d’un second débordement du Livret A vers un bloc « épargne longue » à définir.',
                'Décision reportée sur le support de cette épargne longue — hors du périmètre de repartio.',
            ]],
            ['type' => 'h', 'text' => 'Ce qu’on retient'],
            ['type' => 'p', 'text' => 'Un circuit ne rend pas les décisions plus faciles, il rend leurs conséquences visibles. Ici, la question de départ — « qu’est-ce qui s’arrête si l’auto-entreprise baisse ? » — a désormais une réponse d’une ligne : le répartiteur A, avant toute autre chose.'],
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
                'Des formules qui pointent vers une feuille « A » et une feuille « B », sans jamais dire qui alimente le joint.',
                'L’impossibilité de répondre, en une phrase, à « si ce revenu baisse, quelle enveloppe se réduit ? ».',
            ]],
            ['type' => 'h', 'text' => 'Ce qu’un circuit force à écrire'],
            ['type' => 'p', 'text' => 'Un circuit n’accepte pas un total orphelin. Chaque montant entre par un bloc, circule le long d’un fil, et arrive quelque part. S’il reste de l’argent « non affecté », le mois n’est pas encore décrit — ce n’est pas un oubli cosmétique, c’est un trou dans le récit.'],
            ['type' => 'quote', 'text' => 'Le tableur savait que 6 280 € entraient. Il ne savait plus où ils se séparaient.', 'by' => 'Lecture d’un circuit à 23 blocs'],
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
                ['k' => 'Livret jeune', 'v' => '≥ 1,70 % · 1 600 €', 'c' => 'navy'],
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
            ['type' => 'p', 'text' => 'L’auto-entrepreneur encaisse un chiffre d’affaires, puis paie l’Urssaf chaque mois ou chaque trimestre. Dans un tableur, la ligne n’apparaît que le jour du prélèvement. Deux mois sur trois, le « disponible » est donc une illusion : l’argent est déjà dû, il n’est juste pas encore parti.'],
            ['type' => 'p', 'text' => 'Le geste du circuit est simple. Un bloc dépense « Cotisations », alimenté chaque mois depuis le compte professionnel, au taux de votre régime. Ce qui sort ensuite vers le compte personnel est le net réellement câblable — pas le brut encaissé.'],
            ['type' => 'h', 'text' => 'Le barème micro-social au 1er janvier 2026'],
            ['type' => 'p', 'text' => 'Les taux s’appliquent au chiffre d’affaires hors taxes encaissé, sans déduire aucune charge. Seul le BNC du régime général a bougé cette année : il passe de 24,6 % à 25,6 %. Les autres familles restent stables. C’est ce point, souvent oublié dans les simulateurs restés à 2025, qui fausse le net d’un consultant ou d’un développeur.'],
            ['type' => 'table', 'head' => 'Famille d’activité', 'headMid' => '2025', 'headRight' => '2026', 'rows' => [
                ['k' => 'Vente de marchandises (BIC)', 'mid' => '12,3 %', 'v' => '12,3 %', 'c' => 'blue'],
                ['k' => 'Services commerciaux et artisanaux (BIC)', 'mid' => '21,2 %', 'v' => '21,2 %', 'c' => 'teal'],
                ['k' => 'Autres prestations (BNC, régime général)', 'mid' => '24,6 %', 'v' => '25,6 %', 'c' => 'orange'],
                ['k' => 'Libéraux réglementés (CIPAV)', 'mid' => '23,2 %', 'v' => '23,2 %', 'c' => 'navy'],
                ['k' => 'Meublé de tourisme classé', 'mid' => '6,0 %', 'v' => '6,0 %', 'c' => 'blue'],
            ]],
            ['type' => 'p', 'text' => 'Ces taux couvrent maladie, retraite, invalidité-décès, allocations familiales et CSG-CRDS. Ils ne couvrent pas la contribution à la formation professionnelle, ni le versement libératoire de l’impôt sur le revenu si vous l’avez choisi.'],
            ['type' => 'widget', 'id' => 'urssaf'],
            ['type' => 'h', 'text' => 'CFP : trois décimales qui s’ajoutent'],
            ['type' => 'p', 'text' => 'La contribution à la formation professionnelle est due quel que soit le secteur. Elle s’ajoute au taux social, et l’Urssaf la prélève en même temps. Oublier la CFP, c’est sous-provisionner de 0,1 à 0,3 point — peu sur un mois, visible sur un trimestre.'],
            ['type' => 'table', 'head' => 'Secteur', 'headRight' => 'CFP 2026', 'rows' => [
                ['k' => 'Commerce / vente', 'v' => '0,10 %', 'c' => 'blue'],
                ['k' => 'Prestation de services et professions libérales', 'v' => '0,20 %', 'c' => 'teal'],
                ['k' => 'Artisanat', 'v' => '0,30 %', 'c' => 'orange'],
            ]],
            ['type' => 'h', 'text' => 'ACRE : 50 % jusqu’au 30 juin, 25 % ensuite'],
            ['type' => 'p', 'text' => 'L’exonération de début d’activité réduit le seul taux social, pas la CFP ni le libératoire. Pour une création avant le 1er juillet 2026, l’Urssaf applique encore 50 % de réduction. À partir du 1er juillet, la réduction passe à 25 % — soit un taux social égal à 75 % du barème normal. L’exonération court jusqu’à la fin du troisième trimestre civil qui suit celui de la création.'],
            ['type' => 'table', 'head' => 'Régime', 'headMid' => 'ACRE 50 %', 'headRight' => 'ACRE 25 %', 'rows' => [
                ['k' => 'Vente · 12,3 %', 'mid' => '6,2 %', 'v' => '9,2 %', 'c' => 'blue'],
                ['k' => 'Services BIC · 21,2 %', 'mid' => '10,6 %', 'v' => '15,9 %', 'c' => 'teal'],
                ['k' => 'BNC régime général · 25,6 %', 'mid' => '12,8 %', 'v' => '19,2 %', 'c' => 'orange'],
                ['k' => 'CIPAV · 23,2 %', 'mid' => '11,6 %', 'v' => '17,4 %', 'c' => 'navy'],
            ]],
            ['type' => 'h', 'text' => 'Pourcentage du CA, pas un fixe figé'],
            ['type' => 'p', 'text' => 'Dans le circuit du couple à 6 280 €, la provision URSSAF est un fixe de 380 € — soit 21,2 % de 1 800 € de CA services. Ça tient tant que le CA moyen reste à 1 800 €. Si le mois tombe à 900 € et que le fixe reste, le compte pro se vide trop. Si le CA monte à 3 000 €, on sous-provisionne.'],
            ['type' => 'list', 'items' => [
                'Le plus honnête : un pourcentage du bloc revenu AE, recalculé chaque fois que la moyenne bouge.',
                'Un fixe n’est acceptable que si vous lissez le CA sur douze mois et que vous le revoyez à chaque trimestre.',
                'Après la provision, un seul fil « tout le reste » vers le compte personnel. Jamais un pourcentage du brut.',
            ]],
            ['type' => 'quote', 'text' => 'On croyait disposer de tout le chiffre d’affaires. On en garde le net, et c’est très bien — au moins c’est vrai.', 'by' => 'Lecture d’un circuit, après provision URSSAF'],
            ['type' => 'h', 'text' => 'Ce que 5 000 € de CA veulent vraiment dire'],
            ['type' => 'p', 'text' => 'Services BIC à 21,2 %, hors CFP et hors libératoire : 1 060 € partent, 3 940 € restent. En BNC régime général à 25,6 %, la même facture retire 1 280 €. L’écart est de 220 € par mois, 2 640 € par an — uniquement parce que le simulateur était resté à 24,6 %.'],
            ['type' => 'callout', 'title' => 'Deux autres plafonds à surveiller', 'text' => 'Rester en micro jusqu’à 83 600 € de CA services (203 100 € en vente) n’empêche pas de basculer à la TVA dès 37 500 € (85 000 € en vente). Ces deux limites ne bougent pas ensemble. Le guide dédié les superpose sur le même curseur.'],
            ['type' => 'links', 'title' => 'Sources officielles', 'items' => [
                ['label' => 'Urssaf — L’essentiel du statut auto-entrepreneur', 'href' => 'https://www.autoentrepreneur.urssaf.fr/portail/accueil/sinformer-sur-le-statut/lessentiel-du-statut.html'],
                ['label' => 'economie.gouv.fr — Montant des cotisations sociales des micro-entreprises', 'href' => 'https://www.economie.gouv.fr/entreprises/gerer-sa-micro-entreprise/micro-entreprises-quel-est-le-montant-de-vos-cotisations-sociales'],
            ]],
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
            ['type' => 'h', 'text' => 'Les barèmes au 1er août 2026'],
            ['type' => 'p', 'text' => 'repartio n’invente pas de taux. Chaque livret réglementé arrive avec son plafond et son taux, identiques à ceux que vous posez à la main si vous partez d’un livret vierge. Les taux ci-dessous courent jusqu’au 31 janvier 2027. Le Livret A et le LDDS sont passés de 1,50 % (1er février – 31 juillet) à 1,70 % le 1er août. Le LEP est resté à 2,50 % — la formule aurait dû le ramener à 2,20 %, un « coup de pouce » l’a maintenu.'],
            ['type' => 'table', 'head' => 'Produit', 'headRight' => 'Taux · plafond', 'rows' => [
                ['k' => 'Livret A', 'v' => '1,70 % · 22 950 €', 'c' => 'blue'],
                ['k' => 'LDDS', 'v' => '1,70 % · 12 000 €', 'c' => 'blue'],
                ['k' => 'LEP (si éligible)', 'v' => '2,50 % · 10 000 €', 'c' => 'teal'],
                ['k' => 'Livret jeune', 'v' => '≥ 1,70 % · 1 600 €', 'c' => 'navy'],
                ['k' => 'CEL', 'v' => '1,25 % · 15 300 €', 'c' => 'blue'],
                ['k' => 'PEL ouvert depuis le 1er janv. 2026', 'v' => '2,00 % · 61 200 €', 'c' => 'orange'],
            ]],
            ['type' => 'widget', 'id' => 'taux'],
            ['type' => 'h', 'text' => 'Ce que le plafond ne dit pas'],
            ['type' => 'p', 'text' => 'Le plafond est un stock maximal de versements, pas un flux. Un livret plein continue de capitaliser : les intérêts peuvent porter le solde au-delà. Un livret jeune saturé en 18 mois n’est pas « fini » : ses intérêts restent dans la projection, et le versement mensuel doit basculer ailleurs — sinon il redevient non affecté.'],
            ['type' => 'p', 'text' => 'Le livret jeune n’a pas un taux unique : chaque banque le fixe, sans pouvoir descendre sous le Livret A. Le moteur utilise 1,70 % comme plancher. Si votre banque sert davantage, saisissez le taux réel sur le bloc.'],
            ['type' => 'callout', 'title' => 'Mise à jour', 'text' => 'Les préréglages du canvas suivent ce barème. Les circuits déjà créés gardent les taux saisis ; recharger un préréglage applique le barème neuf. Prochaine révision réglementaire : 1er février 2027.'],
            ['type' => 'links', 'title' => 'Sources officielles', 'items' => [
                ['label' => 'Service-Public — Taux Livret A et LEP au 1er août 2026', 'href' => 'https://www.service-public.gouv.fr/particuliers/actualites/A18000'],
                ['label' => 'Service-Public — Plafonds de revenus du LEP 2026', 'href' => 'https://www.service-public.gouv.fr/particuliers/actualites/A18261'],
            ]],
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

    private static function plafondsMicro(): array
    {
        return [
            ['type' => 'h', 'text' => 'Deux compteurs, deux conséquences'],
            ['type' => 'p', 'text' => 'Le régime micro et la franchise en base de TVA ne partagent pas les mêmes seuils. On peut rester micro-entrepreneur et déjà facturer de la TVA. On peut aussi, plus rarement, s’approcher du plafond micro tout en étant encore franchise. Mélanger les deux chiffres dans une seule ligne de tableur est la première erreur.'],
            ['type' => 'table', 'head' => 'Seuil 2026', 'headMid' => 'Vente / hébergement', 'headRight' => 'Services / libéral', 'rows' => [
                ['k' => 'Plafond micro (2026–2028)', 'mid' => '203 100 €', 'v' => '83 600 €', 'c' => 'teal'],
                ['k' => 'Franchise TVA — seuil de base', 'mid' => '85 000 €', 'v' => '37 500 €', 'c' => 'orange'],
                ['k' => 'Franchise TVA — seuil majoré', 'mid' => '93 500 €', 'v' => '41 250 €', 'c' => 'orange'],
            ]],
            ['type' => 'p', 'text' => 'Les plafonds micro ont été revalorisés au 1er janvier 2026 (ils étaient à 188 700 € et 77 700 €). Les seuils de TVA, eux, n’ont pas bougé. Un prestataire à 4 000 € par mois (48 000 € l’an) est encore largement sous le plafond micro, et déjà au-dessus de la franchise TVA.'],
            ['type' => 'widget', 'id' => 'plafonds'],
            ['type' => 'h', 'text' => 'Ce que le circuit doit prévoir'],
            ['type' => 'list', 'items' => [
                'Un dépassement de franchise : dès le mois du seuil majoré, les factures portent de la TVA. Le CA « net câblable » n’est plus le brut encaissé.',
                'Un dépassement micro une année : vous restez micro l’année suivante. Deux années de suite : sortie au 1er janvier suivant.',
                'La première année, les plafonds sont proratisés au nombre de jours d’activité.',
            ]],
            ['type' => 'callout', 'title' => 'Activité mixte', 'text' => 'Vente + services : le CA global ne doit pas dépasser 203 100 €, dont 83 600 € au plus pour la part services. Les deux compteurs tournent en parallèle.'],
            ['type' => 'links', 'title' => 'Sources officielles', 'items' => [
                ['label' => 'Service-Public Entreprendre — Nouveaux seuils de la micro-entreprise', 'href' => 'https://entreprendre.service-public.gouv.fr/actualites/A18813'],
                ['label' => 'Urssaf — L’essentiel du statut', 'href' => 'https://www.autoentrepreneur.urssaf.fr/portail/accueil/sinformer-sur-le-statut/lessentiel-du-statut.html'],
            ]],
        ];
    }

    private static function liberatoire(): array
    {
        return [
            ['type' => 'h', 'text' => 'Deux façons de payer l’impôt, un seul net mensuel'],
            ['type' => 'p', 'text' => 'Sans versement libératoire, l’Urssaf ne prélève que les cotisations sociales (et la CFP). L’impôt sur le revenu arrive plus tard, sur le CA abattu : 71 % d’abattement en vente, 50 % en services BIC, 34 % en BNC. Avec le libératoire, un pourcentage s’ajoute chaque mois au prélèvement Urssaf, et l’impôt de l’activité est soldé.'],
            ['type' => 'table', 'head' => 'Activité', 'headMid' => 'Taux VL', 'headRight' => 'Abattement micro', 'rows' => [
                ['k' => 'Vente de marchandises', 'mid' => '1,0 %', 'v' => '71 %', 'c' => 'blue'],
                ['k' => 'Services BIC', 'mid' => '1,7 %', 'v' => '50 %', 'c' => 'teal'],
                ['k' => 'BNC et CIPAV', 'mid' => '2,2 %', 'v' => '34 %', 'c' => 'orange'],
            ]],
            ['type' => 'p', 'text' => 'L’option n’est ouverte que si le revenu fiscal de référence du foyer, année N-2, ne dépasse pas la limite supérieure de la deuxième tranche de l’IR. Pour une option en 2026, c’est le RFR 2024 : 29 315 € par part de quotient familial.'],
            ['type' => 'widget', 'id' => 'liberatoire'],
            ['type' => 'h', 'text' => 'Comment le câbler'],
            ['type' => 'list', 'items' => [
                'Avec libératoire : un seul bloc « Cotisations + IR », au taux social + CFP + VL. Le compte pro verse ensuite tout le reste au perso.',
                'Sans libératoire : le bloc Urssaf reste au taux social + CFP. Un second bloc « Provision IR » reçoit un pourcentage du CA, calé sur votre TMI × (1 − abattement).',
                'Si le TMI du foyer est à 0 %, le libératoire fait payer un impôt que vous ne deviez pas. Le simulateur le montre tout de suite.',
            ]],
            ['type' => 'callout', 'title' => 'Ce que le simulateur ne décide pas', 'text' => 'Le TMI réel dépend de tous les revenus du foyer, pas seulement du CA. Ici, on compare deux provisions mensuelles à TMI constant. Ce n’est pas une liasse fiscale.'],
            ['type' => 'links', 'title' => 'Sources officielles', 'items' => [
                ['label' => 'Urssaf — Versement libératoire de l’impôt sur le revenu', 'href' => 'https://www.autoentrepreneur.urssaf.fr/portail/accueil/sinformer-sur-le-statut/lessentiel-du-statut.html'],
            ]],
        ];
    }

    private static function irregulier(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le dernier mois n’est pas le mois type'],
            ['type' => 'p', 'text' => 'Un graphiste encaisse 0 € en août, 7 200 € en novembre. S’il provisionne 21,2 % du mois en cours, novembre se vide, août n’a rien mis de côté, et la facture trimestrielle tombe au plus mauvais moment. Le circuit, lui, ne connaît qu’une moyenne — c’est précisément ce qu’il faut lui donner.'],
            ['type' => 'p', 'text' => 'La moyenne utile se calcule sur les douze derniers mois encaissés, y compris les zéros. Pas sur « un mois normal ». Un mois normal n’existe pas quand la moitié de l’année est à zéro.'],
            ['type' => 'widget', 'id' => 'irregulier'],
            ['type' => 'h', 'text' => 'Trois câblages qui tiennent'],
            ['type' => 'list', 'items' => [
                'Le revenu AE est la moyenne annuelle, pas le dernier encaissement.',
                'La provision Urssaf est un pourcentage de cette moyenne — elle sort même les mois à 0 €, depuis un matelas posé sur le compte pro.',
                'Le matelas pro vaut au moins une échéance trimestrielle + un mois de charges perso alimentées par l’AE.',
            ]],
            ['type' => 'quote', 'text' => 'On a arrêté de « se verser tout » les mois chargés. Le compte pro garde la moyenne. Le perso ne voit plus le yoyo.', 'by' => 'Note de terrain, auto-entreprise saisonnière'],
            ['type' => 'callout', 'title' => 'Deux scénarios plutôt qu’une moyenne trop lisse', 'text' => 'Si l’écart entre mois bas et mois haut dépasse vraiment le confort, dupliquez le circuit : un mois bas, un mois haut. La moyenne reste le mois type ; les deux variantes disent ce qui s’arrête.'],
        ];
    }

    private static function lepElig(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le meilleur taux du moteur a une porte'],
            ['type' => 'p', 'text' => 'À 2,50 % net, le LEP reste le livret réglementé le mieux rémunéré. Il n’est ouvert que si le revenu fiscal de référence du foyer reste sous un plafond, révisé chaque année. En 2026, en métropole : 23 028 € pour une part, 35 326 € pour deux, 47 624 € pour trois. La banque lit le RFR 2024 (avis 2025) ou le RFR 2025 dès qu’il est disponible.'],
            ['type' => 'table', 'head' => 'Parts fiscales', 'headRight' => 'RFR max. 2026', 'rows' => [
                ['k' => '1 part — personne seule', 'v' => '23 028 €', 'c' => 'teal'],
                ['k' => '1,5 part', 'v' => '29 177 €', 'c' => 'teal'],
                ['k' => '2 parts — couple', 'v' => '35 326 €', 'c' => 'blue'],
                ['k' => '2,5 parts', 'v' => '41 475 €', 'c' => 'blue'],
                ['k' => '3 parts — couple, 2 enfants', 'v' => '47 624 €', 'c' => 'navy'],
                ['k' => 'Demi-part supplémentaire', 'v' => '+ 6 149 €', 'c' => 'navy'],
            ]],
            ['type' => 'widget', 'id' => 'lep'],
            ['type' => 'h', 'text' => 'Ce que ça change dans le circuit'],
            ['type' => 'list', 'items' => [
                'Éligible : le LEP se sature en premier, puis le LDDS, puis le Livret A. C’est l’ordre du guide des livrets.',
                'Plus éligible : décochez le LEP. L’ordre devient LDDS puis Livret A. Le stock déjà posé peut rester un an si un seul RFR dépasse ; deux années de suite, le livret se clôture.',
                'Le plafond de versement reste 10 000 €. Les intérêts peuvent le dépasser ; les versements, non.',
            ]],
            ['type' => 'links', 'title' => 'Sources officielles', 'items' => [
                ['label' => 'Service-Public — Plafonds de revenus du LEP 2026', 'href' => 'https://www.service-public.gouv.fr/particuliers/actualites/A18261'],
                ['label' => 'Service-Public — Fiche LEP (F2367)', 'href' => 'https://www.service-public.gouv.fr/particuliers/vosdroits/F2367'],
            ]],
        ];
    }

    private static function matelas(): array
    {
        return [
            ['type' => 'h', 'text' => 'Trois mois de quoi, exactement ?'],
            ['type' => 'p', 'text' => '« Trois mois de réserve » ne veut rien dire tant qu’on n’a pas nommé le dénominateur. Trois mois de revenus gonflent la cible dès qu’un bonus arrive. Trois mois de charges — loyer, énergie, assurances, courses, crédits — donnent un chiffre que le foyer peut encore payer si tout s’arrête.'],
            ['type' => 'p', 'text' => 'Dans un circuit, ce matelas est un livret (souvent le Livret A ou le LDDS) avec une cible. Tant que le solde est sous la cible, un fil fixe l’alimente. Une fois la cible touchée, le fil bascule vers l’objectif suivant — apport, projet, ou simplement « tout le reste ».'],
            ['type' => 'widget', 'id' => 'matelas'],
            ['type' => 'h', 'text' => 'Où le poser'],
            ['type' => 'list', 'items' => [
                'Sur un livret disponible tout de suite, pas sur un support à horizon long.',
                'Alimenté après les factures, avant l’épargne-projet. Le matelas a priorité tant qu’il n’est pas plein.',
                'Pour une auto-entreprise, un second matelas plus petit reste sur le compte pro : une échéance Urssaf, pas trois mois de foyer.',
            ]],
            ['type' => 'callout', 'title' => 'Quatre à six mois si le revenu est variable', 'text' => 'Un salaire unique tient souvent à trois mois. Dès qu’une part du foyer dépend d’un CA, allongez. Le simulateur le montre : la même charge, un mois de plus, déplace la date de plusieurs trimestres si le versement est petit.'],
        ];
    }

    private static function mixte(): array
    {
        return [
            ['type' => 'h', 'text' => 'Deux natures de revenu, un seul mois à tenir'],
            ['type' => 'p', 'text' => 'Le salaire arrive le 28, presque toujours le même. L’auto-entreprise arrive quand le client paie. Si les deux nourrissent les mêmes factures, un mois sans CA fait sauter le loyer. Le câblage qui tient : le salaire sert les fixes, l’AE sert l’épargne et, seulement s’il reste, le quotidien.'],
            ['type' => 'p', 'text' => 'C’est le contraire de ce que beaucoup de tableurs font par habitude : tout additionner, puis tout répartir. L’addition est juste. La répartition, elle, mélange un flux certain et un flux optionnel.'],
            ['type' => 'widget', 'id' => 'mixte'],
            ['type' => 'h', 'text' => 'La règle des deux colonnes'],
            ['type' => 'list', 'items' => [
                'Colonne salaire : factures, loyer, minimum vital. Si ce seul flux les couvre, le mois bas est déjà sauvé.',
                'Colonne AE : provision Urssaf d’abord, puis épargne, puis complément du quotidien.',
                'Si le salaire ne couvre pas les factures, l’AE doit verser un fixe — et le mois sans CA a besoin d’un matelas déjà là.',
            ]],
            ['type' => 'quote', 'text' => 'Tant que le salaire tenait le loyer, on a arrêté de regarder le compte pro tous les soirs.', 'by' => 'Lecture d’un circuit salaire + AE'],
        ];
    }
}
