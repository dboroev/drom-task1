<?php

declare(strict_types=1);

/**
 * @param string $dir              Исходная директория.
 * @param string $searchedFileName Наименование искомого файла.
 *
 * @return Generator
 *
 * @throws Exception
 */
function getStrings(string $dir, string $searchedFileName = 'count'): Generator
{
    if (!is_dir($dir)) {
        return;
    }

    foreach (scandir($dir) as $file) {
        if (in_array($file, ['.', '..'])) {
            continue;
        }

        if (is_dir( $dir . '/' . $file)) {
            foreach (getStrings($dir . '/' . $file) as $item) {
                yield $item;
            }
        }

        if ($file !== $searchedFileName) {
            continue;
        }

        $fp = fopen($dir . '/' . $file, 'r');
        if ($fp) {
            while (($buffer = fgets($fp)) !== false) {
                yield $buffer;
            }

            if (!feof($fp)) {
                throw new Exception("Ошибка: fgets() неожиданно потерпел неудачу\n");
            }
        }

        fclose($fp);
    }
}

$sum = 0;
foreach (getStrings('./directory') as $string) {
    $out = [];
    preg_match_all('/(?<match>\d+\.?(\d+)?)/', $string, $out);

    $sum += array_sum($out['match']);
}

echo $sum;

