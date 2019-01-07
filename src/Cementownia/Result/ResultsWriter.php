<?php
/**
 * Created by PhpStorm.
 * User: beton
 * Date: 07.01.19
 * Time: 21:14
 */

namespace Cementownia\Result;

use Cementownia\Exception;

class ResultsWriter
{
    public function write(array $results, string $filePath)
    {
        $str = json_encode($results, JSON_PRETTY_PRINT);
        if ($str === false) {
            throw new Exception("Failed to serialize data into a json");
        }

        $numBytes = file_put_contents($filePath, $str);
        if ($numBytes === false) {
            throw new Exception("Failed to write the results to file");
        }
    }
}
