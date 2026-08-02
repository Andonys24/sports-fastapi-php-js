<?php
function s(?string $html): string
{
    return htmlspecialchars($html ?? '', ENT_QUOTES, 'UTF-8');
}

function isAdmin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true || (int)$_SESSION['role'] !== ROLE_ADMIN) {
        header('Location: /login');
        exit;
    }
}

function filterByDate(array $items, string $dateKey, string $targetDate): array
{
    if (empty($targetDate)) {
        return $items;
    }

    return array_values(array_filter($items, function ($item) use ($dateKey, $targetDate) {
        return isset($item[$dateKey]) && $item[$dateKey] === $targetDate;
    }));
}
