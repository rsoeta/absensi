<?php
// Pastikan path ini sesuai dengan lokasi repo di Rumahweb
$repo_dir = '/home/stuw8183/public_html/repositories/daaru_syifa';

// Mengeksekusi perintah git pull via terminal server
$output = shell_exec("cd {$repo_dir} && git pull origin main 2>&1");

// Menampilkan hasil tarikan
echo "<pre>Hasil Sinkronisasi GitHub:\n";
echo htmlspecialchars($output);
echo "</pre>";
