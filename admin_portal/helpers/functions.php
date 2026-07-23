<?php
function s(?string $html): string
{
    return htmlspecialchars($html ?? '', ENT_QUOTES, 'UTF-8');
}
