<?php

namespace App\Utils;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class XlsUtils
{
    const TEMP_FILES_PATH = '..' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR;

    /**
     * @param array $codes
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function writeFile(array $codes)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($codes as $index => $code) {
            $sheet->setCellValue('A' . ($index + 1), $code);
        }
        $writer = new Xls($spreadsheet);
        $path = self::TEMP_FILES_PATH . uniqid() . '.xls';
        $writer->save($path);
        return $path;
    }

}