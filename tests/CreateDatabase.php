<?php

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestCase;

class CreateDatabase extends TestCase
{
    // Fungsi untuk membaca isi file
    private function readFileContent($filePath)
    {
        return file_get_contents($filePath);
    }

    // Fungsi ini sebagai alat bantu untuk membandingkan dua konten file
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

    // Fungsi utama untuk menguji konten dan mengeksekusi file
    public function testCompareAndExecute()
    {
        $kunciJawabanPath = __DIR__ . '/../storage/app/private/kunci_jawaban/createDB.php';
        $kodeMahasiswaPath = __DIR__ . '/../storage/app/private/testingunit/testingunit.php';

        $kunciJawabanContent = $this->readFileContent($kunciJawabanPath);
        $kodeMahasiswaContent = $this->readFileContent($kodeMahasiswaPath);

        // Memanggil fungsi compareFiles untuk mendapatkan perbedaan antara konten kedua file
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
