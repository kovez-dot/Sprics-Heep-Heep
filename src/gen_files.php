<?php
$baseDir = __DIR__ . '/files';
$baseUrl = "http://game.samp-mobile.com/files";

function scanFiles($dir, $baseDir, $baseUrl) {
    $files = [];
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $fullPath = $dir . '/' . $item;
        if (is_dir($fullPath)) {
            $files = array_merge($files, scanFiles($fullPath, $baseDir, $baseUrl));
        } else {
            $relativePath = str_replace('\\', '/', substr($fullPath, strlen($baseDir) + 1));
            $files[] = [
                "name" => $item,
                "size" => filesize($fullPath),
                "path" => addslashes($relativePath),
                "url"  => addslashes($baseUrl . "/" . $relativePath)
            ];
        }
    }
    return $files;
}

$fileList = scanFiles($baseDir, $baseDir, $baseUrl);

// Converte para JSON **com barras escapadas** e **uma linha só**
$json = json_encode(["files" => $fileList], JSON_UNESCAPED_SLASHES);
$json = str_replace('/', '\/', $json);

// Salva no arquivo files.php
file_put_contents(__DIR__ . '/files.php', $json);

echo "✅ files.php gerado exatamente no modelo que você mostrou!\n";
