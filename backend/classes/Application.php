<?php

declare(strict_types=1);

class Application
{
    public static function create(int $userId, array $data): int
    {
        $db = Database::getConnection();

        $stmt = $db->prepare(
            'INSERT INTO applications
            (user_id, address, contact_phone,
             desired_datetime, service_name,
             payment_method_id, status_id)
            VALUES
            (:user_id, :address, :contact_phone,
             :desired_datetime, :service_name,
             :payment_method_id, 1)'
        );

        $stmt->execute([
            'user_id' => $userId,
            'address' => $data['address'],
            'contact_phone' => $data['contact_phone'],
            'desired_datetime' => $data['desired_datetime'],
            'service_name' => $data['service_name'],
            'payment_method_id' => $data['payment_method_id']
        ]);

        return (int) $db->lastInsertId();
    }

    public static function forUser(int $userId): array
    {
        $db = Database::getConnection();

        $stmt = $db->prepare(
            'SELECT
                a.*,
                s.name AS status_name,
                p.name AS payment_method_name
             FROM applications AS a
             JOIN statuses AS s ON s.id = a.status_id
             JOIN payment_methods AS p
                ON p.id = a.payment_method_id
             WHERE a.user_id = :user_id
             ORDER BY a.created_at DESC, a.id DESC'
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function allForAdmin(): array
    {
        $db = Database::getConnection();

        $stmt = $db->query(
            'SELECT
                a.*,
                u.login,
                p.last_name,
                p.first_name,
                p.middle_name,
                s.name AS status_name,
                pm.name AS payment_method_name
             FROM applications AS a
             JOIN users AS u ON u.id = a.user_id
             LEFT JOIN user_profiles AS p ON p.user_id = u.id
             JOIN statuses AS s ON s.id = a.status_id
             JOIN payment_methods AS pm
                ON pm.id = a.payment_method_id
             ORDER BY a.created_at DESC, a.id DESC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
public static function changeStatus(
    int $applicationId,
    int $newStatusId,
    ?string $cancelReason = null
): bool {
    if ($applicationId <= 0) {
        return false;
    }

    $allowedTransitions = [
        2 => 1,
        3 => 2,
        4 => 2
    ];

    if (!isset($allowedTransitions[$newStatusId])) {
        return false;
    }

    $oldStatusId = $allowedTransitions[$newStatusId];

    if ($newStatusId === 4) {
        $cancelReason = trim((string)$cancelReason);

        if ($cancelReason === '') {
            return false;
        }
    } else {
        $cancelReason = null;
    }

    $db = Database::getConnection();

    $stmt = $db->prepare(
        'UPDATE applications
         SET status_id = :new_status,
             cancel_reason = :cancel_reason
         WHERE id = :application_id
           AND status_id = :old_status'
    );

    $stmt->execute([
        'new_status' => $newStatusId,
        'cancel_reason' => $cancelReason,
        'application_id' => $applicationId,
        'old_status' => $oldStatusId
    ]);

    return $stmt->rowCount() === 1;
}

}