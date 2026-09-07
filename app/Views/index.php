<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>DAARU ASY-SYIFA SMP/SMA MUHAMMADIYAH PAKENJENG</title>
  <!-- Sisipkan kode favicon dinamis di sini -->
  <?php if (isset($sett_apps) && !empty($sett_apps->logo_sekolah)) : ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo/' . $sett_apps->logo_sekolah) ?>">
  <?php else : ?>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo/default.png') ?>">
  <?php endif; ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
      overflow-x: hidden;
    }

    h2 {
      text-align: center;
      margin: 30px 0 15px;
      color: #003366;
      font-size: clamp(1.5rem, 4vw, 2rem);
    }

    /* ====== HEADER ====== */
    header {
      /* Ubah 'nama_file_background.jpg' sesuai dengan nama file gambar yang Kang Rian miliki */
      background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), url('<?= base_url('assets/img/web/background-web.png'); ?>') no-repeat center/cover;
      color: white;
      padding: 60px 15px;
      text-align: center;
    }

    header img.logo {
      width: 90px;
      height: auto;
      margin-bottom: 12px;
    }

    header h1 {
      font-size: clamp(1.4rem, 5vw, 2.5rem);
      margin-bottom: 10px;
      line-height: 1.3;
    }

    header p {
      font-size: clamp(0.9rem, 2.5vw, 1.15rem);
      max-width: 700px;
      margin: 0 auto;
      opacity: 0.9;
    }

    /* ====== NAVBAR RESPONSIVE ====== */
    nav {
      background: #003366;
      padding: 10px 5px;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 1000;
      white-space: nowrap;
      overflow-x: auto;
      scrollbar-width: none;
      /* Firefox */
    }

    nav::-webkit-scrollbar {
      display: none;
      /* Chrome, Safari, Opera */
    }

    nav a {
      color: white;
      margin: 0 12px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.95rem;
      display: inline-block;
      transition: color 0.2s;
    }

    nav a:hover {
      color: #ffd700;
    }

    /* ====== SECTION & GRID ====== */
    section {
      padding: 40px 15px;
      max-width: 1100px;
      margin: auto;
    }

    section p {
      font-size: 0.95rem;
      text-align: justify;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 15px;
      margin-top: 20px;
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-3px);
    }

    .card h3 {
      font-size: 1.15rem;
      color: #003366;
      margin-bottom: 8px;
    }

    .card p {
      font-size: 0.9rem;
      color: #555;
      text-align: left;
    }

    /* ====== GALERI SLIDER ====== */
    .gallery {
      padding: 40px 15px;
      background: #f1f3f5;
      text-align: center;
    }

    .slider-container {
      position: relative;
      max-width: 100%;
      width: 800px;
      margin: auto;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
      height: auto;
      aspect-ratio: 16 / 9;
      object-fit: cover;
      display: block;
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
      padding: 10px 14px;
      cursor: pointer;
      border-radius: 50%;
      font-size: 16px;
      z-index: 10;
    }

    .prev {
      left: 10px;
    }

    .next {
      right: 10px;
    }

    /* ====== KONTAK & MAPS ====== */
    #kontak p {
      text-align: center;
      margin-bottom: 5px;
      font-size: 0.9rem;
    }

    #kontak p:first-of-type {
      margin-top: 10px;
    }

    .map-responsive {
      position: relative;
      overflow: hidden;
      padding-bottom: 56.25%;
      /* Rasio 16:9 */
      height: 0;
      margin-top: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .map-responsive iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 0;
    }

    /* ====== FOOTER ====== */
    footer {
      background: #003366;
      color: white;
      text-align: center;
      padding: 20px 15px;
      margin-top: 30px;
      font-size: 0.85rem;
    }

    /* ====== MEDIA QUERIES KHUSUS MOBILE ====== */
    @media (max-width: 768px) {
      header {
        padding: 40px 15px;
      }

      nav {
        text-align: left;
        padding: 8px 12px;
      }

      nav a {
        margin: 0 10px;
        font-size: 0.9rem;
      }

      section {
        padding: 30px 15px;
      }
    }
  </style>
</head>

<body>

  <!-- ====== HEADER ====== -->
  <header>
    <img src="<?= base_url('assets/img/logo/File-250131-41bb13b44a.png'); ?>" alt="Logo Sekolah" class="logo">
    <h1>DAARU ASY-SYIFA SMP/SMA MUHAMMADIYAH PAKENJENG</h1>
    <p>Mencetak Generasi Berakhlak Mulia, Cerdas, dan Berkemajuan</p>
  </header>

  <!-- ====== NAVBAR (Scrollable di Mobile) ====== -->
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
    <p>DAARU ASY-SYIFA SMP/SMA MUHAMMADIYAH PAKENJENG berdiri untuk memberikan pendidikan berkualitas dengan landasan nilai-nilai Islam dan semangat kemajuan. Sejak awal berdiri, sekolah ini telah berkomitmen mencetak lulusan yang unggul dalam akademik maupun akhlak.</p>
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
        <div class="slide"><img src="<?= base_url('assets/img/web/FB_IMG_1788706313421.jpg'); ?>" alt="Foto Lingkungan 1"></div>
        <div class="slide"><img src="<?= base_url('assets/img/web/FB_IMG_1788706315549.jpg'); ?>" alt="Foto Lingkungan 2"></div>
        <div class="slide"><img src="<?= base_url('assets/img/web/FB_IMG_1788706321016.jpg'); ?>" alt="Foto Guru 1"></div>
        <div class="slide"><img src="<?= base_url('assets/img/web/FB_IMG_1788706323802.jpg'); ?>" alt="Foto Guru 2"></div>
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
    <p><strong>Alamat:</strong> Kp. Pasirlangu Desa Pasirlangu, Pakenjeng, Garut, Jawa Barat</p>
    <p><strong>WhatsApp:</strong> <a href="https://wa.me/6289506021190" target="_blank">+62 895-0602-1190</a></p>
    <p><strong>Email:</strong> <a href="mailto:ppmahaddaaruasysyifapakenjeng@gmail.com">ppmahaddaaruasysyifapakenjeng@gmail.com</a></p>

    <div class="map-responsive">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19038.710286115445!2d107.6333703!3d-7.4546706!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6620489584a00b%3A0x4cc4109046cf9ee7!2sSMA%20Muhammadiyah%20Pakenjeng!5e1!3m2!1sen!2sid!4v1788707827452!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </div>
  </section>

  <!-- ====== FOOTER ====== -->
  <footer>
    <p>&copy; 2026 DAARU ASY-SYIFA SMP/SMA MUHAMMADIYAH PAKENJENG. All rights reserved.</p>
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