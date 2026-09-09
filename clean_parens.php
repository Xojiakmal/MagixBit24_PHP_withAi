<?php

$dir = __DIR__ . '/resources/views';

function processFile($filePath) {
    $content = file_get_contents($filePath);
    $original = $content;

    // Pattern 1: {{ __('Text') }} (Text in parentheses)
    // Example: {{ __('Ro\'yxat') }} (List)
    $content = preg_replace('/(\{\{\s*__\([^\)]+\)\s*\}\})\s*\([^)]+\)/', '$1', $content);

    // Pattern 2: __('Text (Text inside)') -> __('Text')
    // We only want to remove (Something) that is preceded by a space and is inside the string.
    // Example: __('Summasi va Valyuta (Amount and currency)') -> __('Summasi va Valyuta')
    // Example: __('Mijoz (Client)') -> __('Mijoz')
    // We must be careful not to break the `__('...')` syntax.
    
    // We'll use a regex that matches `__('... (something)...')` and `__("... (something)...")`
    $content = preg_replace_callback('/(__\([\'"])(.*?)([\'"]\))/', function($matches) {
        $text = $matches[2];
        // Remove (something)
        // Match a space followed by parentheses containing text
        $cleanedText = preg_replace('/\s*\([^)]+\)/', '', $text);
        return $matches[1] . $cleanedText . $matches[3];
    }, $content);
    
    // Also handle {!! __('... (something) ...') !!}
    // The previous regex catches __(...) regardless of surrounding tags, so it covers {!! !!} too.

    if ($content !== $original) {
        file_put_contents($filePath, $content);
        echo "Updated: $filePath\n";
    }
}

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        processFile($file->getPathname());
    }
}

// Now update lang files
$langs = ['en.json', 'uz.json'];
foreach ($langs as $langFile) {
    $path = __DIR__ . '/lang/' . $langFile;
    if (file_exists($path)) {
        $json = json_decode(file_get_contents($path), true);
        $newJson = [];
        foreach ($json as $key => $value) {
            $newKey = preg_replace('/\s*\([^)]+\)/', '', $key);
            $newValue = preg_replace('/\s*\([^)]+\)/', '', $value);
            $newJson[$newKey] = $newValue;
        }
        file_put_contents($path, json_encode($newJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "Updated $langFile\n";
    }
}

echo "Done.\n";
