<?php

declare(strict_types=1);

namespace App;

class Content
{
    public static function posts(): array
    {
        return Articles::index();
    }

    public static function post(string $slug): ?array
    {
        return Articles::find($slug);
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
            ['Situations', 'Peut-on modéliser un couple avec des comptes séparés ?', 'C’est le cas le plus courant : deux colonnes de comptes personnels, un ou plusieurs comptes joints, et des répartiteurs distincts.', 'Voir un circuit rempli', '/circuit-rempli'],
            ['Situations', 'Comment provisionner l’URSSAF en auto-entreprise ?', 'Avec un bloc dépense dédié, alimenté depuis le compte professionnel.', '', ''],
            ['Situations', 'Et les livrets des enfants ?', 'Un bloc livret par enfant, avec son solde de départ, son taux et son plafond.', '', ''],
            ['Situations', 'Peut-on câbler le premier argent d’un ado ?', 'Oui. Le scénario « Seize ans » part d’un argent de poche et d’un job, verse une part à l’épargne avant les sorties, et vise un Livret Jeune plus une enveloppe (permis, voyage).', 'Circuits types', '/circuits-types'],
            ['Situations', 'Peut-on suivre un objectif chiffré, comme un apport ?', 'Oui : vous posez la cible sur le bloc de destination, et repartio affiche le mois d’atteinte.', '', ''],
            ['Situations', 'Comment gérer un crédit immobilier ?', 'Comme une dépense mensuelle fixe. La modélisation du capital restant dû n’est pas encore dans le moteur.', '', ''],
            ['Compte & plans', 'Que contient la version gratuite ?', 'Un circuit, les cinq types de blocs, la projection jusqu’à 24 mois et le partage public.', 'Comparer les plans', '/tarifs'],
            ['Compte & plans', 'Faut-il une carte pour créer un compte ?', 'Non. Aucun moyen de paiement n’est demandé sur le plan Libre.', '', ''],
            ['Compte & plans', 'Que devient mon circuit si j’arrête de payer ?', 'Il reste consultable, partageable et exportable. Vous repassez sous la limite d’un circuit modifiable et d’une projection à 24 mois.', '', ''],
            ['Compte & plans', 'Le plan Foyer, c’est plusieurs abonnements ?', 'Non, un seul : jusqu’à dix personnes invitées à gérer les mêmes circuits.', '', ''],
            ['Compte & plans', 'Puis-je changer de plan en cours de route ?', 'À tout moment, dans les deux sens.', '', ''],
            ['Données & sécurité', 'Où sont hébergées mes données ?', 'Chez Infomaniak, dans un centre de données situé en Suisse, en Europe.', 'Confidentialité', '/confidentialite'],
            ['Données & sécurité', 'Vendez-vous les données ?', 'Jamais. Pas de revente, pas de courtier, pas de ciblage publicitaire.', '', ''],
            ['Données & sécurité', 'Comment supprimer définitivement mon compte ?', 'Depuis les réglages, en un clic. La suppression est immédiate et sans période de rétention.', '', ''],
            ['Données & sécurité', 'repartio est-il un conseil en investissement ?', 'Non. C’est un outil de simulation : il calcule ce que vous décrivez.', '', ''],
        ];
    }

    public static function templates(): array
    {
        $items = [];
        foreach (self::hydrateAll() as $key => $item) {
            if (($item['catalog'] ?? true) === false) {
                continue;
            }
            $items[$key] = $item;
        }

        return $items;
    }

    /** @return array<string, mixed>|null */
    public static function template(string $key): ?array
    {
        return self::hydrateAll()[$key] ?? null;
    }

    /** @return array<string, array<string, mixed>> */
    private static function hydrateAll(): array
    {
        $items = Scenarios::all();
        foreach ($items as $key => &$item) {
            $item['payload'] = self::layoutPayload($item['payload']);
            $item['key'] = $key;
            $item['blocks'] = count($item['payload']['nodes'] ?? []);
        }
        unset($item);

        return $items;
    }

    /** @return list<string> */
    public static function featuredKeys(): array
    {
        return ['couple', 'auto-entrepreneur', 'precaution'];
    }

    public static function featuredTemplates(): array
    {
        $all = self::templates();
        $out = [];
        foreach (self::featuredKeys() as $key) {
            if (isset($all[$key])) {
                $out[$key] = $all[$key];
            }
        }

        return $out;
    }

    public static function templatePayload(string $key): ?array
    {
        $pack = self::template($key);

        return is_array($pack) ? ($pack['payload'] ?? null) : null;
    }

    public static function showcaseKey(): string
    {
        return 'couple-complet';
    }

    /** @return array<string, mixed>|null */
    public static function showcase(): ?array
    {
        $key = self::showcaseKey();
        $raw = Scenarios::all()[$key] ?? null;
        if (!$raw) {
            return null;
        }

        $payload = $raw['payload'];
        $nodes = $payload['nodes'] ?? [];

        return [
            'key' => $key,
            'title' => $raw['title'],
            'category' => $raw['category'],
            'hint' => $raw['hint'],
            'payload' => $payload,
            'blocks' => count($nodes),
            'stats' => \App\Models\Project::summarize($payload),
        ];
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
            $kind = $n['kind'] ?? '';
            if ($kind === 'depense') {
                $lines = 0;
                foreach ($n['items'] ?? [] as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $title = trim((string) ($item['title'] ?? ''));
                    $amount = (float) ($item['amount'] ?? 0);
                    if ($title !== '' || $amount > 0) {
                        $lines++;
                    }
                }

                return 152.0 + $lines * 26.0;
            }

            return match ($kind) {
                'livret' => 176.0,
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
            'lede' => 'Informations légales relatives à l’éditeur du site repartio.fr et du service de répartition de revenus qui y est accessible, conformément à la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l’économie numérique.',
            'meta' => ['Version 2.0', 'À jour au 25 août 2026', 'Droit français'],
            'sections' => [
                [
                    'h' => 'Éditeur du site',
                    'ps' => [
                        'Le site repartio.fr et le service qu’il héberge sont un produit édité et exploité par la société REINVENT, qui opère sous la marque commerciale ReInvent. Le site est propulsé par [ReInvent](https://reinvent.fr).',
                    ],
                    'rows' => [
                        ['k' => 'Dénomination', 'v' => 'REINVENT'],
                        ['k' => 'Marque commerciale', 'v' => 'ReInvent'],
                        ['k' => 'Forme juridique', 'v' => 'Société par actions simplifiée (SAS)'],
                        ['k' => 'Capital social', 'v' => '100 €'],
                        ['k' => 'Siège social', 'v' => '486 rue Sadi Carnot, 59184 Sainghin-en-Weppes, France'],
                        ['k' => 'RCS', 'v' => '107 095 671 R.C.S. Lille Métropole'],
                        ['k' => 'SIREN', 'v' => '107 095 671'],
                        ['k' => 'SIRET (siège)', 'v' => '107 095 671 00015'],
                        ['k' => 'TVA intracommunautaire', 'v' => 'FR36107095671'],
                        ['k' => 'Code APE', 'v' => '62.01Z — Programmation informatique'],
                        ['k' => 'Site de l’éditeur', 'v' => '[reinvent.fr](https://reinvent.fr)'],
                        ['k' => 'Registre public', 'v' => '[Fiche Pappers de REINVENT](https://www.pappers.fr/entreprise/reinvent-107095671)'],
                        ['k' => 'Contact produit', 'v' => 'bonjour@repartio.fr'],
                    ],
                ],
                [
                    'h' => 'Direction de la publication',
                    'ps' => [
                        'Le président de REINVENT est la société TERCIUM, SARL au capital de 5 000 €, dont le siège social est situé 486 rue Sadi Carnot, 59184 Sainghin-en-Weppes, immatriculée au RCS de Lille Métropole sous le numéro 829 770 064, représentée par Monsieur Julien Larzillière.',
                        'Toute demande relative au contenu éditorial du site peut être adressée à bonjour@repartio.fr.',
                    ],
                    'rows' => [
                        ['k' => 'Directeur de publication', 'v' => 'Julien Larzillière'],
                        ['k' => 'Président', 'v' => 'TERCIUM, représentée par Julien Larzillière'],
                    ],
                ],
                [
                    'h' => 'Hébergement et exploitation technique',
                    'ps' => [
                        'Le site et les données du service sont exploités par REINVENT et hébergés en Suisse, en Europe, par Infomaniak Network SA. L’hébergeur n’intervient ni dans la production ni dans la modération des contenus publiés.',
                    ],
                    'rows' => [
                        ['k' => 'Exploitant technique', 'v' => 'REINVENT — [reinvent.fr](https://reinvent.fr)'],
                        ['k' => 'Hébergeur', 'v' => 'Infomaniak Network SA'],
                        ['k' => 'Adresse de l’hébergeur', 'v' => 'Rue Eugène-Marziano 25, 1227 Les Acacias (GE), Suisse'],
                        ['k' => 'Localisation', 'v' => 'Centres de données situés en Suisse'],
                    ],
                ],
                [
                    'h' => 'Nature du service',
                    'ps' => [
                        'repartio est un outil de simulation. Il permet de décrire des flux d’argent déclarés par l’utilisateur et d’en projeter les conséquences dans le temps selon des règles explicites.',
                    ],
                    'list' => [
                        'repartio ne dispose d’aucun accès aux comptes bancaires de ses utilisateurs et n’exerce aucune activité d’agrégation de comptes.',
                        'repartio ne fournit ni conseil en investissement, ni recommandation personnalisée, ni service de paiement au sens du code monétaire et financier.',
                        'Les résultats affichés dépendent intégralement des montants et des règles saisis par l’utilisateur.',
                    ],
                ],
                [
                    'h' => 'Propriété intellectuelle',
                    'ps' => [
                        'L’ensemble des éléments composant le site — structure, textes, interfaces, identité visuelle, logo, code — est protégé au titre du droit d’auteur et du droit des marques. Toute reproduction ou représentation, totale ou partielle, sans autorisation écrite préalable de REINVENT est interdite.',
                        'Les circuits créés par un utilisateur restent la propriété de cet utilisateur. REINVENT n’acquiert aucun droit d’exploitation sur ces contenus, hormis les opérations techniques strictement nécessaires à la fourniture du service.',
                    ],
                ],
                [
                    'h' => 'Liens hypertextes',
                    'ps' => [
                        'Le site peut contenir des liens vers des ressources externes, notamment réglementaires ou vers le site de l’éditeur [reinvent.fr](https://reinvent.fr). REINVENT n’exerce aucun contrôle sur les ressources tierces et décline toute responsabilité quant à leur contenu ou leur disponibilité.',
                    ],
                ],
                [
                    'h' => 'Signalement d’un contenu',
                    'ps' => [
                        'Tout contenu manifestement illicite constaté sur le site peut être signalé par e-mail à bonjour@repartio.fr, en précisant l’URL concernée et le motif du signalement. Un accusé de réception est adressé sous 72 heures ouvrées.',
                    ],
                ],
                [
                    'h' => 'Droit applicable',
                    'ps' => [
                        'Les présentes mentions légales sont soumises au droit français. En cas de litige, et à défaut de résolution amiable, les tribunaux français sont seuls compétents.',
                    ],
                ],
            ],
        ];
    }

    public static function cgu(): array
    {
        return self::conditions('cgu');
    }

    public static function cgv(): array
    {
        return self::conditions('cgv');
    }

    public static function conditions(string $which = 'cgu'): array
    {
        $cgu = [
            'eyebrow' => 'CGU · Conditions générales d’utilisation',
            'title' => 'Conditions générales d’utilisation',
            'lede' => 'Les règles d’usage du service repartio : ce que vous pouvez en faire, ce que nous nous engageons à fournir, et ce que nous ne garantissons pas. Elles s’appliquent à tout utilisateur, gratuit ou abonné.',
            'meta' => ['Version 2.0', 'En vigueur au 24 août 2026', 'Applicable à tous les plans'],
            'sections' => [
                [
                    'h' => 'Objet et acceptation',
                    'ps' => [
                        'Les présentes conditions régissent l’accès au service repartio, édité et propulsé par [ReInvent](https://reinvent.fr) (REINVENT, SAS, SIREN 107 095 671), et son utilisation. La création d’un compte vaut acceptation sans réserve de ces conditions dans leur version en vigueur au jour de l’inscription.',
                        'Toute modification substantielle est notifiée par e-mail au moins trente jours avant son entrée en vigueur. La poursuite de l’utilisation du service après cette date vaut acceptation de la nouvelle version.',
                    ],
                ],
                [
                    'h' => 'Éditeur',
                    'ps' => [
                        'repartio est un produit de REINVENT. Les mentions d’identification de la société figurent dans les [mentions légales](/mentions-legales) et sur la [fiche Pappers de REINVENT](https://www.pappers.fr/entreprise/reinvent-107095671).',
                    ],
                ],
                [
                    'h' => 'Description du service',
                    'ps' => [
                        'repartio est un simulateur de flux financiers personnels. L’utilisateur décrit des blocs (revenus, comptes, répartiteurs, livrets, dépenses) et les relie par des fils portant des montants ou des pourcentages. Le service en déduit un mois type et une projection sur l’horizon choisi.',
                    ],
                    'list' => [
                        'Le service fonctionne exclusivement à partir des montants saisis par l’utilisateur.',
                        'Aucune connexion à un établissement bancaire n’est réalisée ni proposée.',
                        'Les taux et plafonds réglementaires préremplis sont fournis à titre indicatif et peuvent être modifiés par l’utilisateur.',
                    ],
                ],
                [
                    'h' => 'Compte utilisateur',
                    'ps' => [
                        'La création d’un compte requiert une adresse e-mail valide et un mot de passe de douze caractères minimum. L’utilisateur est responsable de la confidentialité de ses identifiants et de toute activité réalisée depuis son compte.',
                    ],
                    'list' => [
                        'Un compte est strictement personnel ; le plan Complet autorise une invitation, le plan Foyer jusqu’à dix personnes invitées à gérer les circuits.',
                        'Tout usage frauduleux constaté doit être signalé sans délai à bonjour@repartio.fr.',
                        'L’éditeur peut suspendre un compte en cas d’atteinte à la sécurité ou à l’intégrité du service.',
                    ],
                ],
                [
                    'h' => 'Usages interdits',
                    'list' => [
                        'Tenter d’accéder aux données d’un autre utilisateur, ou contourner les limitations du plan souscrit.',
                        'Extraire massivement le contenu du service par des moyens automatisés non autorisés.',
                        'Utiliser le service pour fournir un conseil financier rémunéré à des tiers sans accord écrit préalable.',
                        'Perturber le fonctionnement de l’infrastructure, notamment par des requêtes volumétriques anormales.',
                    ],
                ],
                [
                    'h' => 'Disponibilité et évolutions',
                    'ps' => [
                        'Le service est fourni en l’état, accessible en continu hors interruptions de maintenance annoncées et hors cas de force majeure. Aucun niveau de disponibilité contractuel n’est garanti sur les plans gratuits.',
                        'L’éditeur peut faire évoluer les fonctionnalités, y compris en retirer, sous réserve d’en informer les abonnés payants au moins trente jours à l’avance lorsque la modification réduit une fonctionnalité facturée.',
                    ],
                ],
                [
                    'h' => 'Absence de conseil financier',
                    'ps' => [
                        'repartio n’est ni conseiller en investissements financiers, ni intermédiaire en opérations de banque, ni prestataire de services de paiement. Les projections affichées sont des calculs déterministes appliqués aux données saisies, sans appréciation de leur pertinence.',
                    ],
                    'list' => [
                        'Les résultats ne constituent ni une recommandation, ni une garantie de performance.',
                        'L’utilisateur reste seul décideur de ses arbitrages financiers.',
                        'Les taux réglementaires peuvent évoluer indépendamment du service.',
                    ],
                ],
                [
                    'h' => 'Responsabilité',
                    'ps' => [
                        'La responsabilité de l’éditeur ne peut être engagée pour les décisions prises par l’utilisateur sur la base des simulations, ni pour les préjudices indirects tels que perte de chance ou manque à gagner. En tout état de cause, la responsabilité totale est limitée aux sommes effectivement versées par l’utilisateur au titre des douze mois précédant le fait générateur.',
                    ],
                ],
                [
                    'h' => 'Résiliation',
                    'ps' => [
                        'L’utilisateur peut supprimer son compte à tout moment depuis les réglages, sans motif ni préavis. La suppression entraîne l’effacement des circuits associés sans période de rétention ; il est recommandé de procéder à un export préalable.',
                    ],
                ],
                [
                    'h' => 'Droit applicable et litiges',
                    'ps' => [
                        'Les présentes conditions sont soumises au droit français. En cas de différend, les parties s’efforcent de trouver une solution amiable. À défaut, le litige relève des tribunaux français compétents ; le consommateur peut également saisir gratuitement un médiateur de la consommation.',
                    ],
                ],
            ],
        ];

        $cgv = [
            'eyebrow' => 'CGV · Conditions générales de vente',
            'title' => 'Conditions générales de vente',
            'lede' => 'Les règles applicables aux abonnements payants repartio : prix, paiement, durée, renouvellement, droit de rétractation et remboursement. Elles complètent les conditions générales d’utilisation.',
            'meta' => ['Version 2.1', 'En vigueur au 25 août 2026', 'Prix HT — TVA en sus'],
            'sections' => [
                [
                    'h' => 'Champ d’application',
                    'ps' => [
                        'Les présentes conditions s’appliquent à toute souscription d’un abonnement payant au service repartio, édité et propulsé par [ReInvent](https://reinvent.fr) (REINVENT, SAS, SIREN 107 095 671), par un consommateur ou un professionnel. Le plan gratuit n’entre pas dans leur champ, à l’exception des dispositions relatives aux données de facturation.',
                    ],
                ],
                [
                    'h' => 'Vendeur',
                    'ps' => [
                        'Les abonnements sont commercialisés par REINVENT, dont les coordonnées figurent dans les [mentions légales](/mentions-legales) et sur la [fiche Pappers de REINVENT](https://www.pappers.fr/entreprise/reinvent-107095671).',
                    ],
                ],
                [
                    'h' => 'Offres et prix',
                    'ps' => [
                        'Les prix sont indiqués en euros hors taxes. La TVA en vigueur s’applique selon le statut de l’utilisateur. Le prix applicable est celui affiché au moment de la souscription ; une évolution tarifaire ne s’applique qu’au renouvellement suivant, après information par e-mail trente jours à l’avance.',
                    ],
                    'rows' => [
                        ['k' => 'Plan Libre', 'v' => '0 € — 1 circuit, projection jusqu’à 24 mois, partage public'],
                        ['k' => 'Plan Complet', 'v' => '3,90 € HT par mois ou 39 € HT par an — 3 circuits, 60 mois, 1 invitation'],
                        ['k' => 'Plan Foyer', 'v' => '8,90 € HT par mois ou 89 € HT par an — 50 circuits, 50 ans, 10 invitations'],
                        ['k' => 'Engagement', 'v' => 'Aucun — résiliable à tout moment'],
                    ],
                ],
                [
                    'h' => 'Souscription et paiement',
                    'ps' => [
                        'La souscription est réalisée en ligne. Le paiement est effectué par carte bancaire ou prélèvement SEPA via un prestataire certifié PCI-DSS ; aucune donnée de carte ne transite ni n’est conservée sur les serveurs de l’éditeur.',
                    ],
                    'list' => [
                        'Le premier paiement est prélevé au jour de la souscription.',
                        'Les paiements suivants sont prélevés à la date anniversaire, mensuelle ou annuelle.',
                        'Une facture nominative est mise à disposition dans l’espace « Forfait & facturation » après chaque paiement.',
                    ],
                ],
                [
                    'h' => 'Durée et renouvellement',
                    'ps' => [
                        'L’abonnement est conclu pour une durée d’un mois ou d’un an selon le cycle choisi, renouvelable tacitement. Le passage d’un cycle annuel à un cycle mensuel prend effet à la fin de la période déjà réglée.',
                    ],
                ],
                [
                    'h' => 'Droit de rétractation',
                    'ps' => [
                        'Le consommateur dispose d’un délai de quatorze jours à compter de la souscription pour exercer son droit de rétractation, sans motif ni pénalité, par simple demande à bonjour@repartio.fr ou depuis les réglages du compte.',
                        'Le service étant immédiatement accessible, l’utilisateur reconnaît que l’exécution commence dès la souscription ; le remboursement est alors calculé au prorata de la période non consommée.',
                    ],
                ],
                [
                    'h' => 'Résiliation et effets',
                    'ps' => [
                        'La résiliation est effective à la fin de la période en cours ; aucun prélèvement supplémentaire n’intervient. Les fonctionnalités payantes sont désactivées à cette date.',
                    ],
                    'list' => [
                        'Les circuits au-delà de la limite du plan gratuit deviennent consultables, partageables et exportables, mais non modifiables.',
                        'L’historique des versions reste accessible pendant trente jours après la fin de l’abonnement.',
                        'Aucune donnée n’est supprimée du fait de la seule résiliation.',
                    ],
                ],
                [
                    'h' => 'Défaut de paiement',
                    'ps' => [
                        'En cas de rejet de prélèvement, une nouvelle tentative est effectuée à trois et sept jours. Passé un délai de quinze jours, l’abonnement est suspendu et le compte repasse automatiquement au plan gratuit, sans frais additionnels.',
                    ],
                ],
                [
                    'h' => 'Garanties et réclamations',
                    'ps' => [
                        'Toute réclamation relative à une facturation doit être adressée dans un délai de soixante jours à bonjour@repartio.fr. L’éditeur s’engage à répondre sous cinq jours ouvrés et, en cas d’erreur constatée, à régulariser sous dix jours ouvrés.',
                    ],
                ],
                [
                    'h' => 'Médiation de la consommation',
                    'ps' => [
                        'Conformément au code de la consommation, le client consommateur peut recourir gratuitement à un médiateur de la consommation en vue de la résolution amiable d’un litige, après réclamation écrite préalable auprès de l’éditeur. Les coordonnées du médiateur sont communiquées sur demande.',
                    ],
                ],
            ],
        ];

        $doc = $which === 'cgv' ? $cgv : $cgu;
        $doc['tabs'] = [
            ['key' => 'cgu', 'label' => 'Conditions d’utilisation', 'href' => '/cgu'],
            ['key' => 'cgv', 'label' => 'Conditions de vente', 'href' => '/cgv'],
        ];
        $doc['activeTab'] = $which === 'cgv' ? 'cgv' : 'cgu';

        return $doc;
    }

    public static function privacy(): array
    {
        return [
            'eyebrow' => 'Politique de confidentialité',
            'title' => 'Politique de confidentialité',
            'lede' => 'Comment repartio, édité et propulsé par ReInvent, traite les données personnelles de ses utilisateurs, pour quelles finalités, pendant combien de temps, et comment exercer vos droits. Rédigée en application du règlement (UE) 2016/679 (RGPD).',
            'meta' => ['Version 2.0', 'À jour au 25 août 2026', 'Responsable : REINVENT'],
            'sections' => [
                [
                    'h' => 'Responsable du traitement',
                    'ps' => [
                        'Le responsable du traitement est REINVENT, éditeur du service repartio, dont les coordonnées figurent dans les [mentions légales](/mentions-legales). Toute demande relative aux données personnelles peut être adressée à bonjour@repartio.fr.',
                    ],
                    'rows' => [
                        ['k' => 'Responsable', 'v' => 'REINVENT (ReInvent) — SAS, SIREN 107 095 671'],
                        ['k' => 'Siège', 'v' => '486 rue Sadi Carnot, 59184 Sainghin-en-Weppes'],
                        ['k' => 'Contact données', 'v' => 'bonjour@repartio.fr'],
                        ['k' => 'Site de l’éditeur', 'v' => '[reinvent.fr](https://reinvent.fr)'],
                        ['k' => 'Registre public', 'v' => '[Fiche Pappers](https://www.pappers.fr/entreprise/reinvent-107095671)'],
                        ['k' => 'Délégué à la protection', 'v' => 'Non désigné — traitement ne l’exigeant pas'],
                    ],
                ],
                [
                    'h' => 'Principe directeur : pas d’accès bancaire',
                    'ps' => [
                        'repartio ne se connecte à aucun établissement bancaire et n’utilise aucun agrégateur de comptes. Les montants présents dans le service sont exclusivement ceux saisis par l’utilisateur, ce qui exclut par construction la détention d’un historique de transactions.',
                    ],
                    'list' => [
                        'Aucun mandat DSP2 n’est demandé ni conservé.',
                        'Aucun identifiant bancaire (IBAN, numéro de carte) n’est stocké par l’éditeur.',
                        'Les données de paiement sont traitées exclusivement par un prestataire certifié PCI-DSS.',
                    ],
                ],
                [
                    'h' => 'Données collectées et finalités',
                    'ps' => [
                        'Seules les données nécessaires au fonctionnement du service sont collectées. Chaque catégorie répond à une finalité déterminée et à une base légale identifiée.',
                    ],
                    'rows' => [
                        ['k' => 'Adresse e-mail', 'v' => 'Identification du compte, envoi des liens de connexion et des factures — exécution du contrat'],
                        ['k' => 'Prénom', 'v' => 'Personnalisation de l’interface — exécution du contrat'],
                        ['k' => 'Structure des circuits', 'v' => 'Fourniture du service de simulation — exécution du contrat'],
                        ['k' => 'Montants saisis', 'v' => 'Calcul du mois type et de la projection — exécution du contrat'],
                        ['k' => 'Historique de versions', 'v' => 'Restauration d’un état antérieur, plans payants — exécution du contrat'],
                        ['k' => 'Journal de connexion', 'v' => 'Sécurité du compte et détection d’accès anormaux — intérêt légitime'],
                        ['k' => 'Mesure d’usage anonymisée', 'v' => 'Statistiques d’audience ReInvent, sans cookie ni identifiant publicitaire — intérêt légitime (exemption CNIL)'],
                        ['k' => 'Messages de contact', 'v' => 'Réponse à votre demande — intérêt légitime / mesures précontractuelles'],
                        ['k' => 'Données de facturation', 'v' => 'Obligations comptables et fiscales — obligation légale'],
                    ],
                ],
                [
                    'h' => 'Mesure d’audience anonymisée',
                    'ps' => [
                        'Le site utilise le tracker de [ReInvent](https://reinvent.fr) (stat.reinvent.fr), exclusivement pour mesurer l’audience et quelques actions de conversion (inscription, recherche, souscription). Ce dispositif est conçu sans cookie, sans identifiant publicitaire et sans stockage côté navigateur.',
                        'Il relève de l’exemption de consentement prévue pour la mesure d’audience nécessaire au fonctionnement du service, conformément aux lignes directrices de la CNIL. Aucune bannière de consentement n’est donc affichée pour ce seul traceur.',
                    ],
                    'list' => [
                        'Pas de cookie déposé pour la mesure d’audience.',
                        'Pas de recoupement avec des données publicitaires ou des courtiers.',
                        'Pas de transfert des statistiques à des régies publicitaires.',
                    ],
                ],
                [
                    'h' => 'Durées de conservation',
                    'rows' => [
                        ['k' => 'Compte et circuits', 'v' => 'Toute la durée de vie du compte, puis suppression immédiate'],
                        ['k' => 'Historique de versions', 'v' => '24 mois glissants'],
                        ['k' => 'Journal de connexion', 'v' => '12 mois'],
                        ['k' => 'Mesure d’usage', 'v' => '6 mois, sous forme agrégée'],
                        ['k' => 'Messages de contact', 'v' => 'Le temps du traitement, puis archivage limité'],
                        ['k' => 'Factures', 'v' => '10 ans, au titre des obligations comptables'],
                    ],
                ],
                [
                    'h' => 'Absence de profilage et de revente',
                    'ps' => [
                        'Aucune décision automatisée produisant des effets juridiques n’est prise à l’égard des utilisateurs. Les données ne sont ni vendues, ni louées, ni transmises à des courtiers en données, ni utilisées pour du ciblage publicitaire.',
                    ],
                    'list' => [
                        'Pas de traceur publicitaire ni de pixel tiers à des fins publicitaires.',
                        'Pas de croisement des données de circuits entre utilisateurs.',
                        'Pas d’enrichissement auprès de sources externes.',
                    ],
                ],
                [
                    'h' => 'Sous-traitants et localisation',
                    'ps' => [
                        'Les données sont hébergées en Suisse, en Europe, chez Infomaniak. Les sous-traitants intervenant dans la fourniture du service sont limités au strict nécessaire et encadrés par des clauses conformes à l’article 28 du RGPD.',
                    ],
                    'rows' => [
                        ['k' => 'Édition et infogérance', 'v' => 'REINVENT — [reinvent.fr](https://reinvent.fr)'],
                        ['k' => 'Hébergement', 'v' => 'Infomaniak Network SA, centres de données en Suisse'],
                        ['k' => 'Mesure d’audience', 'v' => 'ReInvent Analytics (stat.reinvent.fr), Union européenne'],
                        ['k' => 'Paiement', 'v' => 'Prestataire certifié PCI-DSS, Union européenne'],
                        ['k' => 'E-mails transactionnels', 'v' => 'Prestataire d’envoi, Union européenne'],
                        ['k' => 'Transferts hors UE', 'v' => 'Hébergement en Suisse, pays bénéficiant d’une décision d’adéquation de la Commission européenne'],
                    ],
                ],
                [
                    'h' => 'Sécurité',
                    'list' => [
                        'Chiffrement des données en transit (TLS) et au repos.',
                        'Mots de passe stockés sous forme de condensats à dérivation lente, jamais en clair.',
                        'Accès aux bases de production restreint, journalisé et soumis à double authentification.',
                        'Sauvegardes chiffrées quotidiennes, restauration testée trimestriellement.',
                    ],
                ],
                [
                    'h' => 'Vos droits',
                    'ps' => [
                        'Vous disposez des droits d’accès, de rectification, d’effacement, de limitation, d’opposition et de portabilité. La plupart s’exercent directement dans l’application, sans intervention de notre part.',
                    ],
                    'rows' => [
                        ['k' => 'Accès', 'v' => 'Toutes vos données sont visibles dans l’interface, à tout moment'],
                        ['k' => 'Portabilité', 'v' => 'Export JSON et CSV immédiat depuis les réglages'],
                        ['k' => 'Rectification', 'v' => 'Modification directe des montants, blocs et informations de compte'],
                        ['k' => 'Effacement', 'v' => 'Suppression du compte et des circuits, sans période de rétention'],
                        ['k' => 'Réclamation', 'v' => 'Saisine possible de la CNIL après demande préalable auprès de nous'],
                    ],
                ],
                [
                    'h' => 'Cookies et traceurs',
                    'ps' => [
                        'Le site n’utilise qu’un cookie de session strictement nécessaire au maintien de la connexion, et une mesure d’audience anonymisée opérée par ReInvent, sans identifiant individuel durable. Aucun consentement publicitaire n’est requis, faute de traceur publicitaire.',
                    ],
                ],
                [
                    'h' => 'Modifications de la politique',
                    'ps' => [
                        'Toute modification substantielle de la présente politique est notifiée par e-mail au moins trente jours avant son entrée en vigueur. Les versions antérieures sont conservées et communicables sur demande.',
                    ],
                ],
            ],
        ];
    }
}
