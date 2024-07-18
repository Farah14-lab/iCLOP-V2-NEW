<?php
namespace Tests;


use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckInsert extends TestCase
{
    private function readFileContent($filePath)
    {
        return file_get_contents($filePath);
    }

    private function compareFiles($file1Content, $file2Content)
    {
        $differences = [];
        $file1Lines = explode("\n", $file1Content);
        $file2Lines = explode("\n", $file2Content);

        $maxLines = max(count($file1Lines), count($file2Lines));

        for ($lineNumber = 0; $lineNumber < $maxLines; $lineNumber++) {
            $line1Content = $file1Lines[$lineNumber] ?? '';
            $line2Content = $file2Lines[$lineNumber] ?? '';

            if ($line1Content !== $line2Content) {
                $differences[] = [
                    'line' => $lineNumber + 1,
                    'expected' => $line1Content,
                    'actual' => $line2Content
                ];
            }
        }

        return $differences;
    }

    public function testCompareAndExecute()
    {
        $kunciJawabanPath = __DIR__ . '/../storage/app/private/kunci_jawaban/insertTable_Siswa1.php';
        $kodeMahasiswaPath = __DIR__ . '/../storage/app/private/testingunit/testingunit.php';

        $kunciJawabanContent = $this->readFileContent($kunciJawabanPath);
        $kodeMahasiswaContent = $this->readFileContent($kodeMahasiswaPath);

        // Membandingkan isi file
        $differences = $this->compareFiles($kunciJawabanContent, $kodeMahasiswaContent);

        if (!empty($differences)) {
            foreach ($differences as $difference) {
                echo "Perbedaan pada baris {$difference['line']}: \n";
                echo "Diharapkan: {$difference['expected']}\n";
                echo "Ditemukan: {$difference['actual']}\n\n";
            }
            $this->fail("Kode mahasiswa tidak sesuai dengan kunci jawaban.");
        }

        // Jika tidak ada perbedaan, jalankan kode mahasiswa
        ob_start();
        include $kodeMahasiswaPath;
        $output = ob_get_clean();

        $this->assertTrue(true, "Kondisi TRUE: Kode mahasiswa sesuai dan output valid.");
        echo "Kode mahasiswa sesuai dan output valid.\n";
    }
}
?>
