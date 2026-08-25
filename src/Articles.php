<?php

declare(strict_types=1);

namespace App;

class Articles
{
    public static function featuredSlug(): string
    {
        return 'reussir-a-epargner';
    }

    public static function index(): array
    {
        return [
            [
                'slug' => 'reussir-a-epargner',
                'tag' => 'Méthode',
                'read' => '9 min',
                'date' => '25 août 2026',
                't' => 'Réussir à épargner : payer le livret avant le quotidien',
                'd' => 'L’épargne qui reste « s’il en reste » n’arrive jamais. Le geste qui tient : un fil fixe vers le livret, dès le salaire, avant les sorties.',
                'interactive' => true,
                'featured' => true,
                'guide' => true,
                'guideMeta' => 'Payez-vous d’abord',
                'topics' => ['Budget', 'Épargne'],
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir un modèle d’épargne'],
                'figures' => [
                    ['k' => 'Ordre', 'v' => 'Livret d’abord', 'tone' => 'teal'],
                    ['k' => 'Exemple', 'v' => '300 € / mois', 'tone' => 'ink'],
                    ['k' => '1 000 €', 'v' => '4 mois', 'tone' => 'teal'],
                ],
            ],
            [
                'slug' => 'comment-faire-un-budget',
                'tag' => 'Méthode',
                'read' => '10 min',
                'date' => '25 août 2026',
                't' => 'Comment faire un budget qui tient tout le mois',
                'd' => 'Nommer les entrées, séparer les fixes du quotidien, et n’arrêter que lorsque chaque euro a une destination.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Quatre passes, un compteur à zéro',
                'topics' => ['Budget'],
            ],
            [
                'slug' => 'cest-quoi-livret-a',
                'tag' => 'Réglementaire',
                'read' => '8 min',
                'date' => '25 août 2026',
                't' => 'C’est quoi un Livret A ?',
                'd' => '1,70 % net, 22 950 € de plafond, un par personne, disponible tout de suite. À quoi il sert dans un mois type — et ce qu’il ne fait pas.',
                'interactive' => true,
                'guide' => true,
                'guideMeta' => 'Taux · plafond · intérêts 2026',
                'topics' => ['Épargne'],
            ],
            [
                'slug' => 'cest-quoi-ldds',
                'tag' => 'Réglementaire',
                'read' => '6 min',
                'date' => '25 août 2026',
                't' => 'C’est quoi un LDDS ?',
                'd' => 'Même taux que le Livret A, plafond plus bas. Pourquoi le saturer souvent avant, et comment le câbler à côté.',
                'interactive' => false,
                'topics' => ['Épargne'],
            ],
            [
                'slug' => 'livret-jeune',
                'tag' => 'Réglementaire',
                'read' => '6 min',
                'date' => '25 août 2026',
                't' => 'Livret Jeune : le premier livret d’un ado',
                'd' => '12–25 ans, 1 600 € de plafond, au moins le taux du Livret A. Comment le remplir sans vider l’enveloppe permis.',
                'interactive' => false,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir le modèle Seize ans'],
                'topics' => ['Épargne'],
            ],
            [
                'slug' => 'payer-soi-meme-dabord',
                'tag' => 'Méthode',
                'read' => '7 min',
                'date' => '25 août 2026',
                't' => 'Se payer d’abord : 10 %, 20 %, ou un fixe ?',
                'd' => 'Le circuit « Premier salaire » verse 20 % à l’épargne avant le quotidien. Voyez ce qui reste vraiment, selon le pourcentage.',
                'interactive' => true,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir Premier salaire'],
                'topics' => ['Budget', 'Épargne'],
            ],
            [
                'slug' => 'epargner-petit-revenu',
                'tag' => 'Méthode',
                'read' => '7 min',
                'date' => '25 août 2026',
                't' => 'Épargner avec un petit revenu, sans se mentir',
                'd' => '50 € par mois, c’est 600 € en un an. Le montant compte moins que la régularité — et que le fil ne soit pas le dernier servi.',
                'interactive' => true,
                'topics' => ['Épargne', 'Budget'],
            ],
            [
                'slug' => 'charges-fixes-variables',
                'tag' => 'Méthode',
                'read' => '6 min',
                'date' => '25 août 2026',
                't' => 'Charges fixes, enveloppe quotidienne : deux natures',
                'd' => 'Le loyer se prélève. Les courses se plafonnent. Mélanger les deux dans une seule ligne, c’est l’arbitrage du 30 chaque mois.',
                'interactive' => false,
                'topics' => ['Budget'],
            ],
            [
                'slug' => 'enveloppe-projet',
                'tag' => 'Méthode',
                'read' => '7 min',
                'date' => '25 août 2026',
                't' => 'Permis, vacances, voyage : une enveloppe à date',
                'd' => 'Une cible, un nombre de mois, un versement. Le reste du surplus reste sur le Livret A — les deux objectifs ne se marchent pas dessus.',
                'interactive' => true,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir Projet à date'],
                'topics' => ['Épargne'],
            ],
            [
                'slug' => 'objectif-apport',
                'tag' => 'Étude de cas',
                'read' => '8 min',
                'date' => '25 août 2026',
                't' => 'Épargner pour un apport : la date avant le total',
                'd' => 'Deux salaires, les charges du foyer, puis LEP, Livret A, et tout le reste vers l’apport. La projection donne le mois, pas un vœu.',
                'interactive' => true,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir Objectif apport'],
                'topics' => ['Épargne', 'Foyer'],
            ],
            [
                'slug' => 'premier-salaire-budget',
                'tag' => 'Étude de cas',
                'read' => '8 min',
                'date' => '25 août 2026',
                't' => 'Premier salaire : 1 900 €, loyer, et 20 % avant le reste',
                'd' => 'Le circuit type d’un premier emploi. Ce qui tient, ce qui casse si le loyer monte, et pourquoi l’épargne part avant les sorties.',
                'interactive' => true,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir Premier salaire'],
                'topics' => ['Budget'],
                'topic' => 'Budget',
            ],
            [
                'slug' => 'budget-etudiant',
                'tag' => 'Étude de cas',
                'read' => '7 min',
                'date' => '25 août 2026',
                't' => 'Budget étudiant : bourse, job, colocation, le reste sur Livret A',
                'd' => 'Deux petites entrées, trois fixes, et un surplus souvent mince. Le circuit tient si le quotidien a un plafond — pas une liste ouverte.',
                'interactive' => false,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir le modèle Étudiant'],
                'topics' => ['Budget'],
                'topic' => 'Budget',
            ],
            [
                'slug' => 'colocation-partager',
                'tag' => 'Étude de cas',
                'read' => '6 min',
                'date' => '25 août 2026',
                't' => 'Colocation : qui paie quoi, sans tableur partagé',
                'd' => 'Loyer, charges, courses communes : chaque coloc verse sa part vers un compte « toit », le reste reste personnel.',
                'interactive' => false,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir le modèle Colocation'],
                'topics' => ['Budget'],
                'topic' => 'Budget',
            ],
            [
                'slug' => 'couple-qui-paie-quoi',
                'tag' => 'Étude de cas',
                'read' => '9 min',
                'date' => '25 août 2026',
                't' => 'Couple : 50/50, prorata, ou tout en commun ?',
                'd' => 'Salaires inégaux, factures communes. Trois câblages, trois lectures de « c’est juste ». Le simulateur compare les parts.',
                'interactive' => true,
                'cta' => ['href' => '/circuits-types', 'label' => 'Voir les modèles couple'],
                'topics' => ['Foyer', 'Budget'],
            ],
            [
                'slug' => 'credit-immobilier-budget',
                'tag' => 'Étude de cas',
                'read' => '8 min',
                'date' => '25 août 2026',
                't' => 'Crédit immobilier : le loyer a changé de nom',
                'd' => 'Mensualité, copro, taxe foncière, enveloppe travaux. Ce qui reste à vivre une fois le bien payé — et ce qu’il faut provisionner.',
                'interactive' => true,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir Propriétaire avec crédit'],
                'topics' => ['Foyer', 'Budget'],
            ],
            [
                'slug' => 'budget-conge-parental',
                'tag' => 'Étude de cas',
                'read' => '7 min',
                'date' => '25 août 2026',
                't' => 'Congé parental : un salaire en moins, les mêmes factures',
                'd' => 'Le circuit doit tenir sur le revenu qui reste. L’épargne se réduit ; les fixes, eux, ne négocient pas.',
                'interactive' => false,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir Congé parental'],
                'topics' => ['Foyer'],
            ],
            [
                'slug' => 'revenu-saisonnier',
                'tag' => 'Méthode',
                'read' => '8 min',
                'date' => '25 août 2026',
                't' => 'Revenu saisonnier : lisser pour tenir les mois bas',
                'd' => 'Six mois chargés, six mois calmes. Le mois type est une moyenne ; la réserve, elle, se calcule sur les creux.',
                'interactive' => true,
                'cta' => ['href' => '/circuits-types', 'label' => 'Ouvrir Revenu saisonnier'],
                'topics' => ['Budget', 'Épargne'],
            ],
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
            ['q' => 'Je veux épargner', 'a' => 'Payer le livret d’abord', 'slug' => 'reussir-a-epargner'],
            ['q' => 'C’est quoi un Livret A', 'a' => 'Taux, plafond, à quoi il sert', 'slug' => 'cest-quoi-livret-a'],
            ['q' => 'Je suis auto-entrepreneur', 'a' => 'Provisionner l’URSSAF 2026', 'slug' => 'urssaf-auto-entrepreneur'],
            ['q' => 'Je veux un foyer réel', 'a' => '6 280 €, zéro euro non affecté', 'slug' => 'couple-12338'],
        ];
    }

    /** @return list<array{id: string, kicker: string, title: string, lead: string, slugs: list<string>}> */
    public static function sections(): array
    {
        return [
            [
                'id' => 'budget',
                'kicker' => '01 · Budget',
                'title' => 'Poser un mois qui tient',
                'lead' => 'Nommer les entrées, séparer les fixes du quotidien, et se payer avant de dépenser le reste.',
                'slugs' => ['reussir-a-epargner', 'comment-faire-un-budget', 'payer-soi-meme-dabord', 'charges-fixes-variables'],
            ],
            [
                'id' => 'livrets',
                'kicker' => '02 · Livrets',
                'title' => 'Comprendre les livrets',
                'lead' => 'Livret A, LDDS, LEP, Livret Jeune : à quoi chacun sert, dans quel ordre les remplir.',
                'slugs' => ['cest-quoi-livret-a', 'cest-quoi-ldds', 'livret-jeune', 'taux-plafonds-2026', 'ordre-livrets', 'eligibilite-lep'],
            ],
            [
                'id' => 'objectifs',
                'kicker' => '03 · Objectifs',
                'title' => 'Une date, une enveloppe',
                'lead' => 'Matelas, projet à date, apport : des cibles que la projection peut dater.',
                'slugs' => ['matelas-trois-mois', 'enveloppe-projet', 'objectif-apport', 'epargner-petit-revenu'],
            ],
            [
                'id' => 'situations',
                'kicker' => '04 · Situations',
                'title' => 'Des mois types déjà câblés',
                'lead' => 'Premier salaire, étudiant, couple, crédit, saisonnier : partir d’un circuit plutôt que d’une feuille vide.',
                'slugs' => ['premier-salaire-budget', 'budget-etudiant', 'colocation-partager', 'couple-qui-paie-quoi', 'credit-immobilier-budget', 'budget-conge-parental', 'revenu-saisonnier'],
            ],
            [
                'id' => 'commencer',
                'kicker' => '05 · Méthode',
                'title' => 'Quitter le tableur',
                'lead' => 'Traduire une feuille en chemins, puis comprendre ce qu’un répartiteur force à écrire.',
                'slugs' => ['tableur-vers-circuit', 'budget-tableur', 'anatomie-repartiteur'],
            ],
            [
                'id' => 'baremes',
                'kicker' => '06 · Barèmes',
                'title' => 'Les chiffres du moteur',
                'lead' => 'Taux, plafonds, ordre de remplissage : ce que le circuit porte, sans conseil de placement.',
                'slugs' => ['plafond-atteint', 'pourcentages-ou-fixes', 'fil-tout-le-reste'],
            ],
            [
                'id' => 'activite',
                'kicker' => '07 · Activité',
                'title' => 'Auto-entreprise, sans surprise',
                'lead' => 'Provisionner les cotisations, lire les plafonds, choisir le libératoire, lisser un CA irrégulier.',
                'slugs' => ['urssaf-auto-entrepreneur', 'plafonds-micro-tva-2026', 'versement-liberatoire', 'ca-irregulier'],
            ],
            [
                'id' => 'cas',
                'kicker' => '08 · Cas réels',
                'title' => 'Des circuits commentés',
                'lead' => 'Lire un foyer déjà câblé, puis baisser un revenu pour voir ce qui s’arrête.',
                'slugs' => ['couple-12338', 'famille-de-quatre', 'salaire-et-autoentreprise'],
            ],
            [
                'id' => 'mecanique',
                'kicker' => '09 · Foyer',
                'title' => 'Les gestes qui tiennent',
                'lead' => 'Joints, fixes, pourcentages : les décisions qui suppriment l’arbitrage du 30.',
                'slugs' => ['compte-joint-factures', 'scenarios-compares'],
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
            'reussir-a-epargner' => self::epargner(),
            'comment-faire-un-budget' => self::budgetMois(),
            'cest-quoi-livret-a' => self::livretA(),
            'cest-quoi-ldds' => self::ldds(),
            'livret-jeune' => self::livretJeune(),
            'payer-soi-meme-dabord' => self::payfirst(),
            'epargner-petit-revenu' => self::petitRevenu(),
            'charges-fixes-variables' => self::chargesNatures(),
            'enveloppe-projet' => self::enveloppe(),
            'objectif-apport' => self::apport(),
            'premier-salaire-budget' => self::premierSalaire(),
            'budget-etudiant' => self::etudiant(),
            'colocation-partager' => self::colo(),
            'couple-qui-paie-quoi' => self::prorata(),
            'credit-immobilier-budget' => self::creditImmo(),
            'budget-conge-parental' => self::conge(),
            'revenu-saisonnier' => self::saison(),
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

    private static function epargner(): array
    {
        return [
            ['type' => 'h', 'text' => 'L’épargne « s’il en reste » n’arrive jamais'],
            ['type' => 'p', 'text' => 'La plupart des mois se terminent avec l’intention d’épargner, et un compte courant qui a tout pris. Ce n’est pas un manque de volonté : c’est l’ordre des gestes. Tant que le livret est servi en dernier, il reçoit ce qui reste — souvent zéro, parfois 40 € qu’on rapatrie le 3 du mois suivant.'],
            ['type' => 'p', 'text' => 'Le geste qui change la lecture : le jour du salaire, un virement fixe part vers le livret, avant les courses, avant les sorties. Le quotidien s’ajuste sur ce qui reste. C’est exactement le câblage du circuit « Premier salaire » : 20 % vers l’épargne, puis tout le reste vers le quotidien.'],
            ['type' => 'widget', 'id' => 'epargne'],
            ['type' => 'h', 'text' => 'Un fixe, pas un vœu'],
            ['type' => 'list', 'items' => [
                'Choisissez un montant que le mois bas tient encore — pas le mois où tout va bien.',
                'Posez-le en premier, après le loyer et les prélèvements, avant l’enveloppe quotidienne.',
                'Si le fixe casse le mois, baissez-le. Un petit versement qui part tous les mois bat un gros versement « dès que possible ».',
            ]],
            ['type' => 'h', 'text' => 'Où le poser'],
            ['type' => 'p', 'text' => 'Sur un livret disponible tout de suite — Livret A ou LDDS, LEP s’il est ouvert. Ce n’est pas encore de l’épargne longue. C’est le premier bac : précaution, projet, ou simplement « de l’argent qui n’est plus sur le courant ». Une fois le bac nommé, la projection dit en combien de mois il se remplit.'],
            ['type' => 'quote', 'text' => 'On a arrêté d’attendre la fin du mois. Le livret est servi le 1er. Le quotidien a ce qui reste. Point.', 'by' => 'Lecture d’un circuit à 20 % d’abord'],
            ['type' => 'h', 'text' => 'Ce qu’on ne fait pas ici'],
            ['type' => 'p', 'text' => 'Pas de conseil de placement, pas de « meilleur livret selon votre profil ». Le circuit décrit un chemin : salaire → charges → livret. Le taux et le plafond, eux, sont dans le guide du Livret A et dans les barèmes 2026.'],
            ['type' => 'callout', 'title' => 'Trois mois de charges d’abord', 'text' => 'Avant un apport ou un voyage, un matelas de trois mois de charges — pas de revenus — évite de casser le livret au premier imprévu. Le guide dédié calcule la cible et la date.'],
        ];
    }

    private static function budgetMois(): array
    {
        return [
            ['type' => 'h', 'text' => 'Un budget, ce n’est pas une liste de catégories'],
            ['type' => 'p', 'text' => 'Beaucoup de feuilles commencent par « loyer, courses, essence, restos, loisirs, divers ». C’est une photographie de ce qui est déjà parti. Un budget qui tient décrit d’abord d’où l’argent entre, par quel compte il transite, et quelle enveloppe a le droit de le prendre.'],
            ['type' => 'p', 'text' => 'Quatre passes suffisent. À la dernière, chaque euro du mois type a une destination. S’il en reste un « non affecté », le mois n’est pas encore décrit — ce n’est pas un oubli cosmétique.'],
            ['type' => 'widget', 'id' => 'budget'],
            ['type' => 'h', 'text' => 'Les quatre passes'],
            ['type' => 'list', 'items' => [
                'Les entrées : salaires, aides, CA moyen — jamais le meilleur mois, jamais un bonus « peut-être ».',
                'Les fixes : tout ce qui se prélève ou se doit au centime. Loyer, énergie, assurances, crédit, forfaits.',
                'Le quotidien : une enveloppe, pas trente lignes. Courses, transports, sorties, « on verra ».',
                'L’épargne et le reste : un fil vers le livret, puis éventuellement un projet. S’il reste encore de l’argent, le compteur le dit.',
            ]],
            ['type' => 'h', 'text' => 'Le piège du « divers »'],
            ['type' => 'p', 'text' => 'Dès que « divers » dépasse 10 % des charges, c’est qu’on n’a pas nommé. Soit c’est du quotidien — alors ça rentre dans l’enveloppe — soit c’est un fixe oublié (mutuelle, impôt lissé, abonnement). Un circuit n’accepte pas une ligne orpheline : il force à choisir.'],
            ['type' => 'quote', 'text' => 'On savait que 2 200 € entraient. On ne savait plus si le loyer était déjà payé ou s’il fallait encore y penser.', 'by' => 'Note de terrain, premier budget'],
            ['type' => 'h', 'text' => 'Quand le mois ne tient pas'],
            ['type' => 'p', 'text' => 'Si les fixes dépassent les entrées, ce n’est plus un budget : c’est un découvert annoncé. On ne « répartit » pas un déficit. On baisse un fixe, on augmente une entrée, ou on dit clairement que l’épargne est à zéro jusqu’à ce que ça passe. Le simulateur ci-dessus le montre tout de suite.'],
            ['type' => 'callout', 'title' => 'Garder le tableur pour l’historique', 'text' => 'Le circuit décrit le mois type. Les relevés, eux, archivent le mois réel. Les deux outils ne se remplacent pas.'],
        ];
    }

    private static function livretA(): array
    {
        return [
            ['type' => 'h', 'text' => 'Un livret, pas un compte courant habillé'],
            ['type' => 'p', 'text' => 'Le Livret A est un produit d’épargne réglementé : l’État en fixe le taux et le plafond. Au 1er août 2026, il sert 1,70 % net d’impôt et de prélèvements sociaux, jusqu’à 22 950 € de versements. Un seul par personne. L’argent reste disponible : un retrait n’attend pas une échéance.'],
            ['type' => 'p', 'text' => 'Ce n’est pas un compte pour payer le loyer. C’est un bac : on y pose ce qu’on ne veut plus voir sur le courant, on le reprend si besoin. Les intérêts courent sur le stock, même quand on n’ajoute plus rien — et même au-delà du plafond, une fois celui-ci atteint par capitalisation.'],
            ['type' => 'table', 'head' => 'Règle', 'headRight' => 'Livret A · 2026', 'rows' => [
                ['k' => 'Taux (depuis le 1er août)', 'v' => '1,70 % net', 'c' => 'teal'],
                ['k' => 'Plafond de versement', 'v' => '22 950 €', 'c' => 'blue'],
                ['k' => 'Nombre par personne', 'v' => '1', 'c' => 'navy'],
                ['k' => 'Disponibilité', 'v' => 'À tout moment', 'c' => 'blue'],
                ['k' => 'Fiscalité des intérêts', 'v' => 'Exonérés', 'c' => 'teal'],
            ]],
            ['type' => 'widget', 'id' => 'livreta'],
            ['type' => 'h', 'text' => 'À quoi il sert dans un mois type'],
            ['type' => 'list', 'items' => [
                'Premier bac de précaution, souvent après le LEP s’il est ouvert — ou tout de suite s’il ne l’est pas.',
                'Débordement quand le LDDS est plein : même taux, plus de place.',
                'Enveloppe visible pour un projet, à condition de ne pas la mélanger avec le matelas d’urgence.',
            ]],
            ['type' => 'h', 'text' => 'Ce qu’il ne fait pas'],
            ['type' => 'p', 'text' => 'Il ne bat pas un LEP à 2,50 %. Il ne remplace pas un apport logé ailleurs une fois le plafond touché. Il ne « travaille » pas davantage si on y laisse dormir 80 € : le taux s’applique au stock, un petit stock produit peu. D’où l’intérêt d’un versement régulier, même modeste.'],
            ['type' => 'callout', 'title' => 'Prochaine révision', 'text' => 'Les taux des livrets réglementés sont revus en principe au 1er février et au 1er août. Le moteur garde les taux saisis sur un circuit déjà créé ; recharger un préréglage applique le barème neuf.'],
            ['type' => 'links', 'title' => 'Sources officielles', 'items' => [
                ['label' => 'Service-Public — Taux Livret A et LEP au 1er août 2026', 'href' => 'https://www.service-public.gouv.fr/particuliers/actualites/A18000'],
                ['label' => 'Service-Public — Livret A (F2365)', 'href' => 'https://www.service-public.gouv.fr/particuliers/vosdroits/F2365'],
            ]],
        ];
    }

    private static function ldds(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le voisin du Livret A'],
            ['type' => 'p', 'text' => 'Le LDDS — livret de développement durable et solidaire — sert le même taux que le Livret A : 1,70 % net au 1er août 2026. Son plafond de versement est plus bas : 12 000 €. Un par personne, disponible à tout moment, intérêts exonérés. Sur le papier, c’est un second Livret A plus petit.'],
            ['type' => 'p', 'text' => 'Dans un circuit, cette taille plus petite est précisément ce qui le rend utile. Saturer d’abord le LDDS libère plus tôt un fil de débordement vers le Livret A. Si on fait l’inverse, le Livret A s’approche de 22 950 € pendant que le LDDS reste à moitié vide — et le surplus n’a nulle part où aller le jour où le A est plein.'],
            ['type' => 'table', 'head' => 'Produit', 'headMid' => 'Taux', 'headRight' => 'Plafond', 'rows' => [
                ['k' => 'LDDS', 'mid' => '1,70 %', 'v' => '12 000 €', 'c' => 'blue'],
                ['k' => 'Livret A', 'mid' => '1,70 %', 'v' => '22 950 €', 'c' => 'blue'],
                ['k' => 'LEP (si éligible)', 'mid' => '2,50 %', 'v' => '10 000 €', 'c' => 'teal'],
            ]],
            ['type' => 'h', 'text' => 'Qui peut l’ouvrir'],
            ['type' => 'p', 'text' => 'Toute personne majeure domiciliée en France, en principe un seul. Pas de condition de revenu — contrairement au LEP. Un mineur ne l’ouvre pas : pour un ado, c’est le Livret Jeune ou le Livret A.'],
            ['type' => 'h', 'text' => 'Comment le câbler'],
            ['type' => 'list', 'items' => [
                'Après le LEP s’il est ouvert, avant le Livret A : même taux, plafond atteint plus vite.',
                'Un fil fixe tant qu’il n’est pas saturé, puis un débordement automatique vers le Livret A.',
                'Ne pas y loger le matelas d’urgence et l’apport en même temps : deux bacs, deux dates, deux lectures.',
            ]],
            ['type' => 'callout', 'title' => 'Ordre mécanique, pas un conseil', 'text' => 'LEP → LDDS → Livret A, c’est l’arithmétique des barèmes que le moteur porte. Le guide « ordre de remplissage » le simule avec vos versements.'],
        ];
    }

    private static function livretJeune(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le premier livret qui n’est pas celui des parents'],
            ['type' => 'p', 'text' => 'Le Livret Jeune s’ouvre entre 12 et 25 ans. Plafond de versement : 1 600 €. Le taux est fixé par chaque banque, sans pouvoir descendre sous celui du Livret A — 1,70 % au 1er août 2026. Les intérêts sont exonérés. Un seul par personne. À 25 ans, il se clôture ou se transforme.'],
            ['type' => 'p', 'text' => 'Dans le circuit « Seize ans », l’argent de poche et le job du week-end servent d’abord les abonnements, puis une part part vers le Livret Jeune, le reste vers une enveloppe permis. Le livret n’est pas là pour « faire comme les adultes » : il sépare ce qu’on ne touche pas de ce qu’on a le droit de dépenser.'],
            ['type' => 'table', 'head' => 'Règle', 'headRight' => 'Livret Jeune', 'rows' => [
                ['k' => 'Âge', 'v' => '12 à 25 ans', 'c' => 'navy'],
                ['k' => 'Plafond', 'v' => '1 600 €', 'c' => 'blue'],
                ['k' => 'Taux plancher', 'v' => '≥ Livret A (1,70 %)', 'c' => 'teal'],
                ['k' => 'Cumul possible', 'v' => 'Avec un Livret A', 'c' => 'blue'],
            ]],
            ['type' => 'h', 'text' => '1 600 €, ça va vite'],
            ['type' => 'p', 'text' => 'À 50 € par mois, le plafond est touché en 32 mois si on part de zéro — moins de deux ans à 80 €. Passé cette date, le versement doit basculer : enveloppe permis, Livret A, ou simplement le quotidien. Sans débordement, ces 50 € redeviennent « non affectés » dans la projection.'],
            ['type' => 'h', 'text' => 'Ce qu’on câble pour un ado'],
            ['type' => 'list', 'items' => [
                'Un compte ado qui reçoit poche + job, pas le compte des parents.',
                'Les abonnements en fixe (téléphone, transports) — servis en premier.',
                'Un fixe vers le Livret Jeune, un fixe ou un reste vers l’enveloppe projet (permis, voyage).',
                'Le quotidien — sorties — prend ce qui reste, pas l’inverse.',
            ]],
            ['type' => 'callout', 'title' => 'Le taux réel de votre banque', 'text' => 'Le moteur utilise 1,70 % comme plancher. Si votre banque sert davantage, saisissez le taux sur le bloc. Ça change peu le délai de saturation — le plafond est bas — ça change le stock à 24 mois.'],
        ];
    }

    private static function payfirst(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le pourcentage avant les envies'],
            ['type' => 'p', 'text' => '« Se payer d’abord » veut dire : le jour où l’argent arrive, une part part vers le livret, et le quotidien s’écrit avec ce qui reste. Ce n’est pas 50-30-20 gravé dans le marbre. C’est un ordre. Le circuit « Premier salaire » pose 20 % : sur 1 900 €, 380 € vers l’épargne, le loyer à 650 €, et 870 € pour le reste du mois.'],
            ['type' => 'p', 'text' => 'À 10 %, le livret se remplit deux fois plus lentement, mais le quotidien respire. À 30 %, l’épargne accélère — et le mois casse dès que le loyer ou une assurance bouge. Le bon pourcentage est celui que votre mois bas tient encore.'],
            ['type' => 'widget', 'id' => 'payfirst'],
            ['type' => 'h', 'text' => 'Pourcentage ou montant fixe'],
            ['type' => 'p', 'text' => 'Le pourcentage suit une augmentation : le livret grossit sans que vous y touchiez. Il suit aussi une baisse : l’épargne fond le mois où on aurait le plus besoin d’un fixe. Un montant fixe protège le livret, et laisse le quotidien encaisser l’écart — à condition que les fixes totaux ne dépassent pas l’entrée.'],
            ['type' => 'list', 'items' => [
                'Premier emploi, salaire stable : un pourcentage est simple à tenir.',
                'Loyer lourd ou charges déjà hautes : un petit fixe vaut mieux qu’un 20 % qui met le courant à découvert.',
                'Revenu variable : le pourcentage se calcule sur la moyenne, pas sur le dernier mois chargé.',
            ]],
            ['type' => 'quote', 'text' => 'On a baissé de 20 à 12 %. Le livret a continué de bouger. Le quotidien a arrêté de grignoter le 28.', 'by' => 'Lecture d’un circuit premier salaire'],
        ];
    }

    private static function petitRevenu(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le montant n’est pas le sujet'],
            ['type' => 'p', 'text' => 'À 1 400 € par mois, « épargner 20 % » est souvent une phrase d’un autre budget. 50 €, c’est 600 € en douze mois. 80 €, c’est 960 €. Ce n’est pas un matelas de trois mois. C’est déjà un bac qui n’est plus sur le courant — une réparation, un aller-retour, un mois de loyer en moins à trouver en urgence.'],
            ['type' => 'p', 'text' => 'Le piège, avec un petit revenu, est d’attendre d’avoir « assez » pour commencer. Assez n’arrive pas. Le fil utile est petit, fixe, servi après le loyer, avant les sorties. S’il casse le mois, on le baisse à 20 €. On ne l’annule pas « jusqu’à plus tard ».'],
            ['type' => 'widget', 'id' => 'petit'],
            ['type' => 'h', 'text' => 'Ce qui aide vraiment'],
            ['type' => 'list', 'items' => [
                'Un seul livret, visible, pas trois enveloppes à 12 €.',
                'Le virement le jour du salaire ou de la bourse — pas le 28 « s’il reste ».',
                'Couper une ligne de quotidien plutôt que de viser un pourcentage de magazine.',
                'Si un LEP est ouvert, ces 50 € y vont d’abord : le taux le plus haut du moteur, un plafond qu’on n’atteindra pas tout de suite.',
            ]],
            ['type' => 'quote', 'text' => 'On a posé 40 €. Au bout d’un an on a arrêté de dire qu’on ne pouvait rien mettre de côté.', 'by' => 'Note de terrain, petit revenu'],
            ['type' => 'callout', 'title' => 'Aides et irrégulier', 'text' => 'Bourse, APL, job : additionnez une moyenne, pas le meilleur mois. Le circuit étudiant fait exactement ça — deux entrées, trois fixes, le reste sur Livret A.'],
        ];
    }

    private static function chargesNatures(): array
    {
        return [
            ['type' => 'h', 'text' => 'Deux questions, pas vingt catégories'],
            ['type' => 'p', 'text' => 'Une charge fixe se doit au centime, ou presque : loyer, crédit, assurance, forfait, impôt lissé. Une enveloppe quotidienne est un plafond : courses, essence, restos, « on verra ». La première se câble en fixe. La seconde aussi — mais c’est un maximum, pas une liste à épuiser.'],
            ['type' => 'p', 'text' => 'Quand les deux circulent dans la même ligne « dépenses », chaque fin de mois redevient un procès : est-ce le weekend ou la mutuelle qui a trop pris ? Séparer les natures ne change pas le total. Ça change le nombre de décisions.'],
            ['type' => 'h', 'text' => 'Comment trancher une ligne douteuse'],
            ['type' => 'list', 'items' => [
                'Ça sort tout seul, le même jour, le même montant ? C’est un fixe.',
                'Ça varie, et on peut le baisser sans rompre un contrat ? C’est du quotidien.',
                'C’est rare mais prévisible (assurance annuelle, taxe foncière) ? On le lisse en fixe mensuel — on ne l’attend pas dans « divers ».',
                'On ne sait pas ? Pendant un mois, on le met dans le quotidien. S’il revient identique, on le sort en fixe.',
            ]],
            ['type' => 'h', 'text' => 'Pourquoi le circuit insiste'],
            ['type' => 'p', 'text' => 'Un répartiteur sert les fixes d’abord. Si vous avez mis les courses en fixe trop haut et l’épargne en pourcentage, le livret s’arrête dès que le mois est juste. L’inverse tient mieux : fixes vrais, quotidien en enveloppe, épargne en fixe ou en reste. C’est le même raisonnement que les deux comptes joints — factures d’un côté, quotidien de l’autre.'],
            ['type' => 'quote', 'text' => 'On a arrêté de flicquer les tickets de caisse. Le quotidien a une enveloppe. Les prélèvements ont la leur.', 'by' => 'Lecture d’un circuit à deux natures'],
        ];
    }

    private static function enveloppe(): array
    {
        return [
            ['type' => 'h', 'text' => 'Une date transforme un vœu en versement'],
            ['type' => 'p', 'text' => '« On mettra de côté pour les vacances » ne dit ni combien, ni quand. Une enveloppe projet, si : un montant cible, un nombre de mois, un versement. Le circuit « Projet à date » pose 400 € vers l’enveloppe, le reste sur Livret A. Les deux objectifs ne se marchent pas dessus.'],
            ['type' => 'p', 'text' => 'Le calcul est une division. Cible moins déjà posé, divisé par les mois restants. Si le versement casse le mois, on recule la date ou on baisse la cible — on ne « verra plus tard ». Plus tard, le billet est déjà acheté trop cher, ou le permis a encore attendu.'],
            ['type' => 'widget', 'id' => 'projet'],
            ['type' => 'h', 'text' => 'Ne pas mélanger avec le matelas'],
            ['type' => 'p', 'text' => 'Le livret de précaution sert si la voiture lâche. L’enveloppe permis, elle, doit être encore là en juin. Même support réglementé, deux blocs dans le circuit. Sinon le premier imprévu mange le voyage, et on recommence à zéro les deux compteurs.'],
            ['type' => 'list', 'items' => [
                'Un bloc « projet » avec une note : la date et la cible.',
                'Un fixe mensuel calé sur cette division — servi après les charges, avant le « reste » du Livret A.',
                'Quand la date est tenue, le fixe s’arrête ou bascule : on ne continue pas par habitude à nourrir un bac plein.',
            ]],
            ['type' => 'callout', 'title' => 'Permis, voyage, mariage', 'text' => 'Le modèle ne change pas. Seuls le titre du bloc et le versement changent. Ouvrez « Projet à date », renommez, lisez la projection.'],
        ];
    }

    private static function apport(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le total ne dit rien sans le mois'],
            ['type' => 'p', 'text' => '« Il nous faut 40 000 € d’apport » est une cible. La question utile est : à 800 € par mois après les charges, en combien de mois, et que se passe-t-il quand le LEP sature en chemin ? Le circuit « Objectif apport » pose deux salaires, les charges du foyer, 200 € vers le LEP, 300 € vers le Livret A, et tout le reste vers un bac apport.'],
            ['type' => 'p', 'text' => 'L’ordre n’est pas un détail. Tant que le LEP n’est pas plein, une part du surplus y va — meilleur taux du moteur. Ensuite le flux bascule. Sans débordement câblé, la projection affiche un trou le mois où le livret est saturé, alors que le mois type, lui, reste à zéro.'],
            ['type' => 'widget', 'id' => 'apport'],
            ['type' => 'h', 'text' => 'Ce que cinq ans déplacent'],
            ['type' => 'p', 'text' => 'Monter les livrets enfants, garder un quotidien plus large, ou accélérer l’apport : chaque euro posé ailleurs recule la date. Dans le circuit famille, passer de 30 à 55 € par enfant reculait l’apport de quatre mois. Voir les deux dates sur la même projection clôt le débat plus vite qu’un tableur à scénarios cachés.'],
            ['type' => 'list', 'items' => [
                'Garder un matelas de précaution à part : on n’achète pas avec les trois mois de charges.',
                'Saturer LEP puis LDDS avant de tout verser sur un bac non réglementé.',
                'Dupliquer le circuit pour tester « +200 € / mois » : un seul fil change, l’horizon reste le même.',
            ]],
            ['type' => 'callout', 'title' => 'Pas un conseil immobilier', 'text' => 'repartio date un bac. Il ne dit pas si vous devez acheter, ni quel apport un prêteur exigera. Les 40 000 € du simulateur sont un exemple.'],
        ];
    }

    private static function premierSalaire(): array
    {
        return [
            ['type' => 'h', 'text' => '1 900 €, et déjà trop de destinations'],
            ['type' => 'p', 'text' => 'Le circuit type « Premier salaire » est volontairement simple : un salaire, un compte, un loyer à 650 €, un quotidien, un répartiteur d’épargne. 20 % du compte part vers l’épargne avant le reste. Sur 1 900 €, ça fait 380 € vers les livrets — 250 € vers le LEP s’il est ouvert, le reste vers le Livret A — et 870 € pour courses, transports, sorties, divers.'],
            ['type' => 'table', 'head' => 'Bloc', 'headRight' => 'Par mois', 'rows' => [
                ['k' => 'Salaire', 'v' => '1 900 €', 'c' => 'teal'],
                ['k' => 'Loyer + charges', 'v' => '650 €', 'c' => 'orange'],
                ['k' => 'Épargne (20 %)', 'v' => '380 €', 'c' => 'blue'],
                ['k' => 'Quotidien (le reste)', 'v' => '870 €', 'c' => 'navy'],
            ]],
            ['type' => 'widget', 'id' => 'premier'],
            ['type' => 'h', 'text' => 'Ce qui casse en premier'],
            ['type' => 'p', 'text' => 'Montez le loyer à 800 € dans le simulateur : le quotidien fond, l’épargne tient encore — parce qu’elle est servie avant. C’est le contraire du réflexe « je paierai le livret s’il reste ». Ici, c’est le quotidien qui encaisse le choc. Si le loyer mange aussi les 20 %, le circuit passe sous zéro : ce n’est plus une répartition, c’est un mois trop cher pour ce salaire.'],
            ['type' => 'list', 'items' => [
                'Garder le 20 % tant que le quotidien reste vivable — sinon baisser à 10 % plutôt que tout couper.',
                'LEP d’abord si éligible : à 250 € / mois, 10 000 € se saturent en quarante mois en partant de zéro.',
                'Le quotidien est une enveloppe unique. Pas six catégories à flicquer le premier mois d’emploi.',
            ]],
            ['type' => 'quote', 'text' => 'Le premier salaire donne l’impression de pouvoir tout faire. Le circuit, lui, montre ce qui reste après le loyer.', 'by' => 'Lecture du modèle Premier salaire'],
        ];
    }

    private static function etudiant(): array
    {
        return [
            ['type' => 'h', 'text' => 'Deux petites rentrées, pas un salaire'],
            ['type' => 'p', 'text' => 'Le circuit « Étudiant » additionne une bourse / APL à 550 € et un job à 450 €. Total : 1 000 €. Le loyer de colocation prend 420 €, le quotidien 280 €, les transports 50 €. Il reste 250 € — tout le reste — vers le Livret A. Ce n’est pas confortable. C’est lisible.'],
            ['type' => 'table', 'head' => 'Poste', 'headRight' => 'Mois type', 'rows' => [
                ['k' => 'Bourse / APL', 'v' => '550 €', 'c' => 'teal'],
                ['k' => 'Job étudiant', 'v' => '450 €', 'c' => 'teal'],
                ['k' => 'Loyer colocation', 'v' => '420 €', 'c' => 'orange'],
                ['k' => 'Courses et resto', 'v' => '280 €', 'c' => 'navy'],
                ['k' => 'Transports', 'v' => '50 €', 'c' => 'navy'],
                ['k' => 'Livret A (reste)', 'v' => '250 €', 'c' => 'blue'],
            ]],
            ['type' => 'h', 'text' => 'Ce qui tient, ce qui ne tient plus'],
            ['type' => 'p', 'text' => 'Si le job tombe à zéro un mois, il reste 550 € pour 750 € de fixes + quotidien. Le circuit est en déficit. Deux lectures possibles : baisser le quotidien ce mois-là, ou avoir déjà 200 € sur le Livret A pour encaisser le creux. D’où l’intérêt de verser le surplus les mois travaillés, même 80 €, plutôt que de « se payer un mois normal ».'],
            ['type' => 'list', 'items' => [
                'Le loyer est un fixe. On ne l’arrondit pas « avec les sorties ».',
                'Courses et restos partagent une enveloppe : si le resto prend trop, ce sont les courses qui trinquent, pas le livret.',
                'Le job est une moyenne. Un mois à 0 € doit déjà être prévu dans le bac, pas découvert le 5.',
            ]],
            ['type' => 'callout', 'title' => 'Alternant, mêmes gestes, autres montants', 'text' => 'Le modèle Alternant part d’un salaire à 1 100 € plus 180 € d’aides. Le câblage est le même : loyer, quotidien, formation, reste sur Livret A. Seuls les chiffres changent.'],
        ];
    }

    private static function colo(): array
    {
        return [
            ['type' => 'h', 'text' => 'Un toit commun, des comptes personnels'],
            ['type' => 'p', 'text' => 'La colocation casse les budgets dès qu’on mélange loyer, courses de la semaine et bières du vendredi sur le même compte. Le câblage qui tient : un compte (ou un livret) « toit » qui ne reçoit que les parts de loyer et de charges, et chaque coloc garde son quotidien de son côté.'],
            ['type' => 'p', 'text' => 'Dans le modèle Colocation, chacun verse sa part vers le toit. Le surplus personnel va au Livret A, pas dans la cagnotte commune. Le 30 du mois, on ne se demande plus qui a trop pris sur le pack d’eau : le toit est déjà soldé, le quotidien est une enveloppe privée.'],
            ['type' => 'h', 'text' => 'La règle des trois lignes'],
            ['type' => 'list', 'items' => [
                'Toit : loyer + charges + internet, au centime, divisé par le nombre de colocs — ou au prorata des chambres si c’est le deal.',
                'Courses communes : une petite enveloppe, ou chacun ses courses. Dès que ça dépasse le papier toilette, mieux vaut séparer.',
                'Le reste : personnel. Sorties, transports, épargne. Pas de tableur partagé à douze colonnes.',
            ]],
            ['type' => 'quote', 'text' => 'On a arrêté l’appli de dépenses partagées. Le toit a un virement. Le frigo, chacun voit.', 'by' => 'Lecture d’un circuit colocation'],
            ['type' => 'callout', 'title' => 'Caution et départ', 'text' => 'La caution n’est pas une épargne. C’est un dépôt bloqué. Ne la comptez pas dans le Livret A du circuit : le jour du départ, elle revient — ou pas — hors du mois type.'],
        ];
    }

    private static function prorata(): array
    {
        return [
            ['type' => 'h', 'text' => '« C’est juste » n’a pas une seule arithmétique'],
            ['type' => 'p', 'text' => 'Deux salaires, 2 000 € et 3 000 €. Des factures à 2 000 €, un quotidien à 1 400 €. À 50/50, chacun verse 1 700 € vers le commun : l’un pose 85 % de son salaire, l’autre 57 %. Au prorata 40/60, A verse 1 360 €, B verse 2 040 €. Chacun garde la même proportion. Tout en commun : les deux salaires tombent sur un joint, plus de parts à négocier — plus d’épargne personnelle visible non plus.'],
            ['type' => 'p', 'text' => 'repartio ne choisit pas la justice du foyer. Il rend les trois câblages lisibles. Le modèle « Couple au prorata » fait exactement le 40/60 : chacun alimente Factures et Quotidien à sa part, puis verse le reste sur son propre Livret A.'],
            ['type' => 'widget', 'id' => 'prorata'],
            ['type' => 'h', 'text' => 'Ce que chaque câblage protège'],
            ['type' => 'list', 'items' => [
                '50/50 : simple, dur dès que les salaires s’écartent. Le plus petit revenu n’a plus d’épargne.',
                'Prorata : chacun garde un reste comparable en pourcentage. Il faut recalculer si un salaire bouge.',
                'Tout en commun : zéro arbitrage de parts. L’épargne devient celle du foyer — à câbler après les charges, pas « s’il en reste sur mon compte ».',
            ]],
            ['type' => 'h', 'text' => 'Deux joints restent utiles'],
            ['type' => 'p', 'text' => 'Même au prorata, séparer Factures et Quotidien évite la bagarre du 30. Les parts alimentent deux enveloppes, pas une. Le guide du compte joint « factures » détaille le geste ; ici, on ne tranche que la clé de répartition.'],
            ['type' => 'quote', 'text' => 'On n’a pas choisi le prorata par idéologie. On l’a choisi parce que le 50/50 vidait un des deux livrets.', 'by' => 'Lecture d’un circuit couple 40/60'],
        ];
    }

    private static function creditImmo(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le loyer a changé de nom, pas de nature'],
            ['type' => 'p', 'text' => 'Une mensualité de crédit est un fixe, comme un loyer — souvent plus haut, et accompagné de charges que le locataire ne voyait pas : copropriété, taxe foncière, énergie du bien, et tôt ou tard des travaux. Le circuit « Propriétaire avec crédit » pose 1 180 € de mensualité, 480 € de charges du bien, 1 600 € de quotidien, 420 € d’assurances et impôts, puis 300 € vers une enveloppe travaux et le reste sur Livret A.'],
            ['type' => 'p', 'text' => 'Le risque n’est pas « on est propriétaires, on peut relâcher l’épargne ». C’est d’avoir remplacé un loyer par un fixe plus lourd, sans bac pour la chaudière. La projection le montre : sans l’enveloppe travaux, tout le surplus va au Livret A, et le premier ravalement le vide d’un coup.'],
            ['type' => 'widget', 'id' => 'credit'],
            ['type' => 'h', 'text' => 'Lisser ce qui n’est pas mensuel'],
            ['type' => 'list', 'items' => [
                'Taxe foncière : divisez l’avis par douze, posez un fixe. Pas une ligne « divers » en octobre.',
                'Copropriété : le courant + les provisions. Une régularisation, c’est un pic — mieux vaut un petit surplus déjà là.',
                'Travaux : un bac dédié, même 200 € par mois. Ce n’est pas de l’épargne-projet plaisir, c’est le bien qui vieillit.',
            ]],
            ['type' => 'callout', 'title' => 'Reste à vivre', 'text' => 'Le simulateur soustrait crédit + charges du bien + quotidien. Ce qui sort n’est pas un taux d’endettement bancaire. C’est ce qui reste à câbler — livret ou enveloppe — une fois le toit payé.'],
        ];
    }

    private static function conge(): array
    {
        return [
            ['type' => 'h', 'text' => 'Un salaire en moins, les mêmes prélèvements'],
            ['type' => 'p', 'text' => 'Le congé parental ne négocie pas le loyer. Le circuit type part d’un salaire qui reste, d’allocations, parfois d’une indemnité, et des mêmes fixes qu’avant. L’épargne est ce qui s’arrête en premier — et c’est souvent le bon choix, à condition que le matelas existe déjà.'],
            ['type' => 'p', 'text' => 'La question à poser avant le premier mois, pas pendant : le revenu qui reste couvre-t-il Factures + un quotidien réduit ? Si non, le foyer pioche dans le livret, et il faut le dire dans le circuit : le fil d’épargne s’inverse, ou simplement tombe à zéro, et le stock baisse dans la projection.'],
            ['type' => 'h', 'text' => 'Ce qu’on recâble, ce qu’on laisse'],
            ['type' => 'list', 'items' => [
                'Les fixes du toit : inchangés, sauf si vous changez de logement. On ne « lisse » pas un loyer qu’on paie encore.',
                'Le quotidien : une enveloppe plus basse, assumée, plutôt que trente micro-coupes illisibles.',
                'L’épargne personnelle : en pause, ou un tout petit fixe pour ne pas perdre le geste.',
                'Le livret enfant, s’il existe : c’est souvent le dernier qu’on coupe — voyez la date d’apport ou de projet si vous le gardez.',
            ]],
            ['type' => 'quote', 'text' => 'On a dupliqué le circuit avant le congé. Le mois bas était déjà là, sur le papier. On n’a pas découvert le trou en novembre.', 'by' => 'Lecture d’un circuit congé parental'],
            ['type' => 'callout', 'title' => 'Dupliquer, ne pas écraser', 'text' => 'Gardez le circuit « avant » et un circuit « pendant ». Même horizon. Un seul écart à lire : ce qui s’arrête, et en combien de mois le matelas suffit.'],
        ];
    }

    private static function saison(): array
    {
        return [
            ['type' => 'h', 'text' => 'Le mois type d’un saisonnier est une moyenne'],
            ['type' => 'p', 'text' => 'Six mois à 4 200 €, six mois à 1 400 € : le foyer ne vit pas « un mois à 4 200 ». Il vit 2 800 €, si — et seulement si — les mois chargés ont nourri une réserve. Le circuit « Revenu saisonnier » pose cette moyenne, les charges, 400 € vers la réserve, le reste sur LDDS.'],
            ['type' => 'p', 'text' => 'La réserve n’est pas de l’épargne-projet. C’est ce qu’on reprend les mois bas pour que le loyer ne saute pas. La tailler trop juste, c’est revivre le yoyo. La tailler trop large, c’est sous-alimenter le reste. Le simulateur calcule l’écart à couvrir, mois creux par mois creux.'],
            ['type' => 'widget', 'id' => 'saison'],
            ['type' => 'h', 'text' => 'Trois règles pour que ça tienne'],
            ['type' => 'list', 'items' => [
                'Le revenu du circuit est la moyenne annuelle, y compris les zéros — pas « un mois de saison ».',
                'Les charges du foyer sont en fixe, calées sur ce qu’on paie vraiment en creux, pas sur le standing d’août.',
                'La réserve vise au moins (charges − revenu creux) × nombre de mois bas, déjà là avant le premier creux.',
            ]],
            ['type' => 'quote', 'text' => 'On a arrêté de se verser tout en juillet. Le compte garde la moyenne. L’hiver ne se voit plus sur le découvert.', 'by' => 'Note de terrain, revenu saisonnier'],
            ['type' => 'callout', 'title' => 'Auto-entreprise saisonnière', 'text' => 'Même logique, plus l’Urssaf. La provision se calcule sur la moyenne, et sort aussi les mois à 0 €, depuis le matelas pro. Le guide « CA en dents de scie » détaille le geste.'],
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
