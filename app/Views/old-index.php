<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ma'had Daaru Asy Syifa Muhammadiyah Pakenjeng</title>
  <style>
    /* ====== RESET & GLOBAL ====== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: #333;
      background: #f8f9fa;
    }

    h2 {
      text-align: center;
      margin: 40px 0 20px;
      color: #003366;
    }

    /* ====== HEADER ====== */
    header {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://via.placeholder.com/1600x600?text=Foto+Sekolah') no-repeat center/cover;
      color: white;
      padding: 100px 20px;
      text-align: center;
      position: relative;
    }

    header img.logo {
      width: 120px;
      height: auto;
      margin-bottom: 15px;
    }

    header h1 {
      font-size: 48px;
      margin-bottom: 10px;
    }

    header p {
      font-size: 20px;
    }

    /* ====== NAVBAR ====== */
    nav {
      background: #003366;
      padding: 10px;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    nav a {
      color: white;
      margin: 0 15px;
      text-decoration: none;
      font-weight: bold;
    }

    nav a:hover {
      color: #ffd700;
    }

    /* ====== SECTION ====== */
    section {
      padding: 60px 20px;
      max-width: 1100px;
      margin: auto;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    /* ====== GALERI SLIDER ====== */
    .gallery {
      padding: 60px 20px;
      background: #f9f9f9;
      text-align: center;
    }

    .gallery h2 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    .slider-container {
      position: relative;
      max-width: 800px;
      margin: auto;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .slider {
      display: flex;
      transition: transform 0.5s ease-in-out;
    }

    .slide {
      min-width: 100%;
      box-sizing: border-box;
    }

    .slide img {
      width: 100%;
      border-radius: 10px;
    }

    .prev,
    .next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 51, 102, 0.7);
      color: #fff;
      border: none;
      padding: 12px;
      cursor: pointer;
      border-radius: 50%;
      font-size: 18px;
    }

    .prev {
      left: 15px;
    }

    .next {
      right: 15px;
    }

    .prev:hover,
    .next:hover {
      background: rgba(0, 51, 102, 1);
    }

    /* ====== FOOTER ====== */
    footer {
      background: #003366;
      color: white;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }
  </style>
</head>

<body>

  <!-- ====== HEADER ====== -->
  <header>
    <img src="<?= base_url('assets\assets\img\daaru_syifa.png'); ?>" alt="Logo Sekolah" class="logo">
    <h1>Ma'had Daaru Asy Syifa Muhammadiyah Pakenjeng</h1>
    <p>Mencetak Generasi Berakhlak Mulia, Cerdas, dan Berkemajuan</p>
  </header>

  <!-- ====== NAVBAR ====== -->
  <nav>
    <a href="#sejarah">Sejarah</a>
    <a href="#fasilitas">Fasilitas</a>
    <a href="#program">Program</a>
    <a href="#ekstrakurikuler">Ekstrakurikuler</a>
    <a href="#gallery">Galeri</a>
    <a href="#berita">Berita</a>
    <a href="#kontak">Kontak</a>
    <a href="<?= base_url('auth'); ?>">Login</a>
  </nav>

  <!-- ====== SEJARAH ====== -->
  <section id="sejarah">
    <h2>Sejarah</h2>
    <p>Ma'had Daaru Asy Syifa Muhammadiyah Pakenjeng berdiri untuk memberikan pendidikan berkualitas dengan landasan nilai-nilai Islam dan semangat kemajuan. Sejak awal berdiri, sekolah ini telah berkomitmen mencetak lulusan yang unggul dalam akademik maupun akhlak.</p>
  </section>

  <!-- ====== FASILITAS ====== -->
  <section id="fasilitas">
    <h2>Fasilitas</h2>
    <div class="card-grid">
      <div class="card">
        <h3>Laboratorium Komputer</h3>
        <p>Dilengkapi komputer modern untuk menunjang pembelajaran.</p>
      </div>
      <div class="card">
        <h3>Perpustakaan</h3>
        <p>Koleksi buku yang lengkap dan nyaman untuk belajar.</p>
      </div>
      <div class="card">
        <h3>Lapangan Olahraga</h3>
        <p>Sarana olahraga untuk mendukung kesehatan siswa.</p>
      </div>
    </div>
  </section>

  <!-- ====== PROGRAM ====== -->
  <section id="program">
    <h2>Program Unggulan</h2>
    <div class="card-grid">
      <div class="card">
        <h3>Program Tahfidz</h3>
        <p>Mendorong siswa untuk menghafal Al-Qur’an.</p>
      </div>
      <div class="card">
        <h3>Program Literasi</h3>
        <p>Melatih kemampuan menulis, membaca, dan berpikir kritis.</p>
      </div>
      <div class="card">
        <h3>Program Sains</h3>
        <p>Mengembangkan kreativitas dan inovasi dalam bidang sains.</p>
      </div>
    </div>
  </section>

  <!-- ====== EKSTRAKURIKULER ====== -->
  <section id="ekstrakurikuler">
    <h2>Ekstrakurikuler</h2>
    <div class="card-grid">
      <div class="card">
        <h3>Pramuka</h3>
        <p>Melatih kedisiplinan dan kemandirian siswa.</p>
      </div>
      <div class="card">
        <h3>Futsal</h3>
        <p>Mengembangkan bakat olahraga dan sportivitas.</p>
      </div>
      <div class="card">
        <h3>English Club</h3>
        <p>Meningkatkan kemampuan bahasa Inggris siswa.</p>
      </div>
    </div>
  </section>

  <!-- ====== GALERI (SLIDER) ====== -->
  <section id="gallery" class="gallery">
    <h2>Galeri Sekolah</h2>
    <div class="slider-container">
      <div class="slider">
        <div class="slide"><img src="https://via.placeholder.com/800x400?text=Foto+Lingkungan+1" alt="Foto Lingkungan 1"></div>
        <div class="slide"><img src="https://via.placeholder.com/800x400?text=Foto+Lingkungan+2" alt="Foto Lingkungan 2"></div>
        <div class="slide"><img src="https://via.placeholder.com/800x400?text=Foto+Guru+1" alt="Foto Guru 1"></div>
        <div class="slide"><img src="https://via.placeholder.com/800x400?text=Foto+Guru+2" alt="Foto Guru 2"></div>
      </div>
      <button class="prev">&#10094;</button>
      <button class="next">&#10095;</button>
    </div>
  </section>

  <!-- ====== BERITA ====== -->
  <section id="berita">
    <h2>Berita Terbaru</h2>
    <div class="card-grid">
      <div class="card">
        <h3>Prestasi Olimpiade</h3>
        <p>Siswa meraih medali emas dalam Olimpiade Sains tingkat kabupaten.</p>
      </div>
      <div class="card">
        <h3>Pengajian Akbar</h3>
        <p>Kegiatan rutin untuk memperkuat iman dan ukhuwah Islamiyah.</p>
      </div>
      <div class="card">
        <h3>Peresmian Gedung Baru</h3>
        <p>Gedung laboratorium baru diresmikan untuk menunjang pembelajaran.</p>
      </div>
    </div>
  </section>

  <!-- ====== KONTAK ====== -->
  <section id="kontak">
    <h2>Kontak</h2>
    <p>Alamat: Jl. Pendidikan No.1, Pakenjeng, Garut, Jawa Barat</p>
    <p>Telepon: (0262) 123456 | Email: info@smapakenjeng.sch.id</p>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!..." width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
  </section>

  <!-- ====== FOOTER ====== -->
  <footer>
    <p>&copy; 2025 Ma'had Daaru Asy Syifa Muhammadiyah Pakenjeng. All rights reserved.</p>
  </footer>

  <!-- ====== SLIDER SCRIPT ====== -->
  <script>
    let currentIndex = 0;
    const slides = document.querySelectorAll(".slide");
    const slider = document.querySelector(".slider");

    function showSlide(index) {
      if (index >= slides.length) currentIndex = 0;
      else if (index < 0) currentIndex = slides.length - 1;
      else currentIndex = index;

      slider.style.transform = `translateX(${-currentIndex * 100}%)`;
    }

    document.querySelector(".next").addEventListener("click", () => {
      showSlide(currentIndex + 1);
    });
    document.querySelector(".prev").addEventListener("click", () => {
      showSlide(currentIndex - 1);
    });

    // Auto slide
    setInterval(() => {
      showSlide(currentIndex + 1);
    }, 4000);
  </script>

</body>

</html>