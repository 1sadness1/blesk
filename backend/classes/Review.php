<?php
declare(strict_types=1);

class Review
{
    public static function create(
        int $userId,
        int $applicationId,
        string $reviewText,
        int $rating
    ): bool {
        $reviewText = trim($reviewText);

        if (
            $userId <= 0 ||
            $applicationId <= 0 ||
            $reviewText === '' ||
            $rating < 1 ||
            $rating > 5
        ) {
            return false;
        }

        $db = Database::getConnection();

        $stmt = $db->prepare(
            'INSERT INTO reviews (
                application_id,
                review_text,
                rating
            )
            SELECT
                a.id,
                :review_text,
                :rating
            FROM applications AS a
            WHERE a.id = :application_id
              AND a.user_id = :user_id
              AND a.status_id = 3
              AND NOT EXISTS (
                  SELECT 1
                  FROM reviews AS r
                  WHERE r.application_id = a.id
              )'
        );

        $stmt->execute([
            'review_text' => $reviewText,
            'rating' => $rating,
            'application_id' => $applicationId,
            'user_id' => $userId
        ]);

        return $stmt->rowCount() === 1;
    }
    
    public static function forUser(int $userId): array
    {
        $db = Database::getConnection();

        $stmt = $db->prepare(
            'SELECT
                r.application_id,
                r.review_text,
                r.rating
             FROM reviews AS r
             INNER JOIN applications AS a
                ON a.id = r.application_id
             WHERE a.user_id = :user_id'
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        $reviews = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $review) {
            $reviews[(int)$review['application_id']] = $review;
        }

        return $reviews;
    }
}
