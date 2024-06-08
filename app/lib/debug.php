<?php declare(strict_types=1);

function formatDump($var): string
{
    ob_start();
    var_dump($var);
    $output = ob_get_clean();

    // Highlight PHP syntax
    $output = htmlspecialchars($output, ENT_QUOTES, 'UTF-8');

    // Apply custom styles for strings, numbers, integers, keywords, and array keys
    $output = preg_replace('/"(.*?)"/', '<span class="string">"$1"</span>', $output);
    $output = preg_replace('/\b(int|float|bool|array)\b/', '<span class="keyword">$1</span>', $output);
    $output = preg_replace('/=>\s+([0-9]+)/', '=> <span class="number">$1</span>', $output);
    $output = preg_replace('/\b([0-9]+)\b(?![^<>]*<\/span>)/', '<span class="int">$1</span>', $output);
    $output = preg_replace('/\["(.*?)"\]=>/', '<span class="array-key">"$1"</span> =>', $output);

    // Apply the same style for the string content inside var_dump output
    $output = preg_replace('/(string\(\d+\)\s*")([^"]*)(")/', '$1<span class="string">$2</span>$3', $output);

    $output = "<pre>{$output}</pre>";

    return $output;
}

function dump($var): void
{
    $backtrace = debug_backtrace();
    $caller = $backtrace[0];

    echo "<div class='dump-output'>";
    echo "<p><strong>Dump called in:</strong> " . $caller['file'] . " on line " . $caller['line'] . "</p>";
    echo formatDump($var);
    echo "</div>";
}

#[NoReturn] function dd($var): void
{
    dump($var);
    die();
}
