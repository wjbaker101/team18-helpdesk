<?php

function getSecureText($text, $connection, $stripTags)
{
    if ($stripTags) $after = strip_tags($text);
    $after = htmlspecialchars($stripTags ? $after : $text);
    $after = mysqli_real_escape_string($connection, $after);
    
    return $after;
}

function getAllowedHTML($text)
{
    $after = html_entity_decode($text);
    
    return $after;
}

function dashify($text)
{
    $after = str_replace(" ", "-", $text);
    
    $after = preg_replace('/[^\w-]+/', '', $after);
    
    $after = trim($after, "-");
    
    $after = strtolower($after);
    
    return $after;
}

?>