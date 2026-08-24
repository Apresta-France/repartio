<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Access
{
    public const MAX_MEMBERS = 8;

    public const PERMISSIONS = [
        'lecture' => 'Lecture',
        'edition' => 'Édition',
        'gestion' => 'Gestion',
    ];

    public const LABELS = [
        'lecture' => 'Lecture',
        'edition' => 'Édition',
        'gestion' => 'Gestion',
        'proprietaire' => 'Propriétaire',
    ];

    public const HINTS = [
        'lecture' => 'Voir le circuit, sans le modifier.',
        'edition' => 'Modifier et enregistrer le circuit.',
        'gestion' => 'Partager, archiver et dupliquer.',
        'proprietaire' => 'Tous les droits, y compris supprimer et inviter.',
    ];

    private const RANKS = [
        'lecture' => 1,
        'edition' => 2,
        'gestion' => 3,
        'proprietaire' => 4,
    ];

    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM account_members WHERE id = ? LIMIT 1', [$id]);
    }

    public static function findForOwner(int $id, int $ownerId): ?array
    {
        return Database::fetch(
            'SELECT * FROM account_members WHERE id = ? AND owner_id = ? LIMIT 1',
            [$id, $ownerId]
        );
    }

    public static function findByOwnerEmail(int $ownerId, string $email): ?array
    {
        return Database::fetch(
            'SELECT * FROM account_members WHERE owner_id = ? AND email = ? LIMIT 1',
            [$ownerId, mb_strtolower($email)]
        );
    }

    public static function findByToken(string $raw, bool $validOnly = true): ?array
    {
        $sql = 'SELECT * FROM account_members WHERE token_hash = ?';
        if ($validOnly) {
            $sql .= ' AND (expires_at IS NULL OR expires_at > NOW())';
        }
        $sql .= ' LIMIT 1';
        return Database::fetch($sql, [hash('sha256', $raw)]);
    }

    public static function countForOwner(int $ownerId): int
    {
        $row = Database::fetch(
            'SELECT COUNT(*) AS n FROM account_members WHERE owner_id = ?',
            [$ownerId]
        );
        return (int) ($row['n'] ?? 0);
    }

    public static function membersForOwner(int $ownerId): array
    {
        $members = Database::fetchAll(
            'SELECT am.*, u.first_name AS member_name
             FROM account_members am
             LEFT JOIN users u ON u.id = am.member_id
             WHERE am.owner_id = ?
             ORDER BY am.created_at DESC',
            [$ownerId]
        );
        if ($members === []) {
            return [];
        }

        $ids = array_map(static fn (array $m): int => (int) $m['id'], $members);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $rows = Database::fetchAll(
            "SELECT amc.member_row_id, amc.project_id, amc.permission, p.name AS project_name, p.status AS project_status
             FROM account_member_circuits amc
             INNER JOIN projects p ON p.id = amc.project_id
             WHERE amc.member_row_id IN ({$placeholders})
             ORDER BY p.name",
            $ids
        );

        $byMember = [];
        foreach ($rows as $row) {
            $byMember[(int) $row['member_row_id']][] = $row;
        }
        foreach ($members as &$member) {
            $member['circuits'] = $byMember[(int) $member['id']] ?? [];
        }
        unset($member);

        return $members;
    }

    public static function permission(int $userId, int $projectId): ?string
    {
        $project = Project::findById($projectId);
        if (!$project) {
            return null;
        }
        if ((int) $project['user_id'] === $userId) {
            return 'proprietaire';
        }
        $row = Database::fetch(
            'SELECT amc.permission
             FROM account_member_circuits amc
             INNER JOIN account_members am ON am.id = amc.member_row_id
             WHERE amc.project_id = ? AND am.member_id = ? AND am.status = "active"
             LIMIT 1',
            [$projectId, $userId]
        );
        return $row ? (string) $row['permission'] : null;
    }

    public static function can(int $userId, int $projectId, string $need): bool
    {
        $perm = self::permission($userId, $projectId);
        if ($perm === null) {
            return false;
        }
        $rank = self::RANKS[$perm] ?? 0;
        $needRank = self::RANKS[$need] ?? 99;
        return $rank >= $needRank;
    }

    public static function allProjectsForUser(int $userId, ?string $status = null): array
    {
        $owned = Project::allForUser($userId, $status);
        foreach ($owned as &$project) {
            $project['access_role'] = 'proprietaire';
            $project['owner_name'] = null;
        }
        unset($project);

        $sql = 'SELECT p.*, amc.permission AS access_role, u.first_name AS owner_name
                FROM account_member_circuits amc
                INNER JOIN account_members am ON am.id = amc.member_row_id
                INNER JOIN projects p ON p.id = amc.project_id
                INNER JOIN users u ON u.id = am.owner_id
                WHERE am.member_id = ? AND am.status = "active"';
        $params = [$userId];
        if ($status) {
            $sql .= ' AND p.status = ?';
            $params[] = $status;
        }
        $shared = Database::fetchAll($sql, $params);

        $byId = [];
        foreach (array_merge($owned, $shared) as $project) {
            $id = (int) $project['id'];
            if (!isset($byId[$id]) || ($project['access_role'] ?? '') === 'proprietaire') {
                $byId[$id] = $project;
            }
        }

        $list = array_values($byId);
        usort($list, static function (array $a, array $b): int {
            $order = ['actif' => 0, 'scenario' => 1, 'archive' => 2];
            $sa = $order[$a['status'] ?? ''] ?? 9;
            $sb = $order[$b['status'] ?? ''] ?? 9;
            if ($sa !== $sb) {
                return $sa <=> $sb;
            }
            return strcmp((string) ($b['updated_at'] ?? ''), (string) ($a['updated_at'] ?? ''));
        });

        return $list;
    }

    public static function recentsForUser(int $userId, int $limit = 3): array
    {
        return Database::fetchAll(
            'SELECT id, name, status FROM (
                SELECT p.id, p.name, p.status, p.updated_at
                FROM projects p
                WHERE p.user_id = ? AND p.status != "archive"
                UNION
                SELECT p.id, p.name, p.status, p.updated_at
                FROM projects p
                INNER JOIN account_member_circuits amc ON amc.project_id = p.id
                INNER JOIN account_members am ON am.id = amc.member_row_id
                WHERE am.member_id = ? AND am.status = "active" AND p.status != "archive"
             ) AS circuits
             ORDER BY updated_at DESC
             LIMIT ' . (int) $limit,
            [$userId, $userId]
        );
    }

    /**
     * @return array<int, string> projectId => permission
     */
    public static function parseAssignments(array $post, int $ownerId): array
    {
        $ids = array_map('intval', (array) ($post['circuit_ids'] ?? []));
        $rights = (array) ($post['rights'] ?? []);
        $assignments = [];
        foreach ($ids as $id) {
            if ($id <= 0) {
                continue;
            }
            if (!Project::findForUser($id, $ownerId)) {
                continue;
            }
            $perm = (string) ($rights[$id] ?? 'lecture');
            if (!isset(self::PERMISSIONS[$perm])) {
                $perm = 'lecture';
            }
            $assignments[$id] = $perm;
        }
        return $assignments;
    }

    /**
     * @param array<int, string> $assignments
     * @return array{id:int,token:string}
     */
    public static function invite(int $ownerId, string $email, array $assignments): array
    {
        $token = bin2hex(random_bytes(16));
        Database::query(
            'INSERT INTO account_members (owner_id, email, token_hash, status, expires_at, created_at)
             VALUES (?, ?, ?, "pending", DATE_ADD(NOW(), INTERVAL 14 DAY), NOW())',
            [$ownerId, mb_strtolower($email), hash('sha256', $token)]
        );
        $id = (int) Database::lastId();
        self::syncCircuits($id, $ownerId, $assignments);
        return ['id' => $id, 'token' => $token];
    }

    /**
     * @param array<int, string> $assignments
     */
    public static function syncCircuits(int $memberRowId, int $ownerId, array $assignments): void
    {
        Database::query('DELETE FROM account_member_circuits WHERE member_row_id = ?', [$memberRowId]);
        foreach ($assignments as $projectId => $permission) {
            $projectId = (int) $projectId;
            if ($projectId <= 0 || !Project::findForUser($projectId, $ownerId)) {
                continue;
            }
            if (!isset(self::PERMISSIONS[$permission])) {
                $permission = 'lecture';
            }
            Database::query(
                'INSERT INTO account_member_circuits (member_row_id, project_id, permission) VALUES (?, ?, ?)',
                [$memberRowId, $projectId, $permission]
            );
        }
    }

    public static function refreshToken(int $id): string
    {
        $token = bin2hex(random_bytes(16));
        Database::query(
            'UPDATE account_members SET token_hash = ?, expires_at = DATE_ADD(NOW(), INTERVAL 14 DAY) WHERE id = ?',
            [hash('sha256', $token), $id]
        );
        return $token;
    }

    public static function accept(int $id, int $userId): void
    {
        Database::query(
            'UPDATE account_members SET member_id = ?, status = "active", accepted_at = NOW() WHERE id = ?',
            [$userId, $id]
        );
    }

    public static function revoke(int $id, int $ownerId): void
    {
        Database::query('DELETE FROM account_members WHERE id = ? AND owner_id = ?', [$id, $ownerId]);
    }

    public static function inviteWithCircuits(int $id): array
    {
        $member = self::find($id);
        if (!$member) {
            return [];
        }
        $member['circuits'] = Database::fetchAll(
            'SELECT amc.project_id, amc.permission, p.name AS project_name
             FROM account_member_circuits amc
             INNER JOIN projects p ON p.id = amc.project_id
             WHERE amc.member_row_id = ?
             ORDER BY p.name',
            [$id]
        );
        return $member;
    }
}
