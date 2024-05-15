<?php

declare(strict_types=1);

namespace App\Services;

use PhpCfdi\CsfScraper\Exceptions\PdfReader\PdfToTextConvertException;
use Symfony\Component\Process\Process;

/**
 * Extract the contents of a pdf file using pdftotext (apt-get install poppler-utils)
 */
final class PdfToText
{
    private string $pdftotext;

    public function __construct(string $pathPdfToText = '')
    {
        if ('' === $pathPdfToText) {
            $pathPdfToText = 'C:\ProgramData\chocolatey\lib\poppler\tools\Library\bin\pdftotext.exe';
        }
        $this->pdftotext = $pathPdfToText;
    }

    /**
     * @return string file contents
     * @throws PdfToTextConvertException when call to pdftotext fail
     */
    public function extract(string $path): string
    {
        $command = $this->buildCommand($path);
        $process = new Process($command);
        // print_r($command);
        $exitCode = $process->run();
        if (0 !== $exitCode) {
            throw new PdfToTextConvertException(
                "Running pdftotext exit with error (exit code $exitCode)",
                $command,
                $exitCode,
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }
        return $process->getOutput();
    }

    /**
     * @return string[]
     */
    public function buildCommand(string $pdfFile): array
    {
        return [$this->pdftotext, '-eol', 'unix', '-raw', '-q', $pdfFile, '-'];
    }
}
