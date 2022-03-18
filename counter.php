<?php

declare(strict_types=1);

/**
 * @param string $dir              Исходная директория.
 * @param string $searchedFileName Наименование искомого файла.
 *
 * @return float
 *
 * @throws Exception
 */
function countSum(string $dir, string $searchedFileName = 'count'): float
{
    $sum = 0.0;
    if (!is_dir($dir)) {
        return $sum;
    }

    foreach (scandir($dir) as $file) {
        if (in_array($file, ['.', '..'])) {
            continue;
        }

        if (is_dir( $dir . '/' . $file)) {
            $sum += countSum($dir . '/' . $file);

            continue;
        }

        if ($file !== $searchedFileName) {
            continue;
        }

        $fp = fopen($dir . '/' . $file, 'r');
        if ($fp) {
            while (($buffer = fgets($fp)) !== false) {
                $out = [];
                preg_match_all('/(?<match>\d+\.?(\d+)?)/', $buffer, $out);

                $sum += array_sum($out['match']);
            }

            if (!feof($fp)) {
                throw new Exception("Ошибка: fgets() неожиданно потерпел неудачу\n");
            }
        }

        fclose($fp);
    }

    return $sum;
}

echo countSum('./directory');
