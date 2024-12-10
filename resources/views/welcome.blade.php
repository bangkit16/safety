<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>SafeGuard</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <!-- Favicons -->
    <link href="{{ asset('/img/logo.png') }}" rel="icon" />
    <link href="{{ asset('impact/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ asset('impact/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('impact/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('impact/assets/vendor/aos/aos.css') }}" rel="stylesheet" />
    <link href="{{ asset('impact/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('impact/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet" />

    <!-- Main CSS File -->
    <link href="{{ asset('impact/assets/css/main.css') }}" rel="stylesheet" />

    <!-- =======================================================
  * Template Name: Impact
  * Template URL: https://bootstrapmade.com/impact-bootstrap-business-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
    <header id="header" class="header fixed-top ">
        <!-- <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">contact@example.com</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>+1 5589 55488 55</span></i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div>End Top Bar -->

        <div class="branding d-flex align-items-cente">
            <div class="container position-relative d-flex align-items-center justify-content-between">
                <a href="index.html" class="logo d-flex align-items-center">
                    <!-- Uncomment the line below if you also wish to use an image logo -->
                    <img src="{{ asset('img/logo.png') }}" alt="">
                    <h1 class="sitename">SafeGuard</h1>
                    <span class="color-white">.</span>
                </a>

                <nav id="navmenu" class="navmenu">
                    <ul class="gy-2">
                        <li>
                            <a href="#hero" class="active">Home<br /></a>
                        </li>
                        <li><a href="#about">Tentang</a></li>
                        <li><a href="#services">Layanan</a></li>
                        <li><a href="#why">Mengapa Kami?</a></li>
                        <li><a href="#contact">Kontak </a></li>
                        @auth
                            <li><a class="bg-primary px-2 py-1 rounded" href="{{ route('home') }}">Dashboard</a></li>
                        @else
                            <li class=" "><a class="bg-primary px-2 py-1 rounded"
                                    href="https://web.whatsapp.com/send?phone=62895360171810&text=Assalamualaikum  "
                                    target="_blank">Berlangganan </a></li>
                            {{-- <li class="bg-danger  rounded"><a class="btn btn-get-started"
                                    href="https://wa.me/6282232801273?text=Assalamualaikum" target="_blank">Berlangganan </a></li> --}}
                            <li class=""><a class="bg-primary px-2 py-1 rounded w-auto"
                                    href="{{ route('login') }}">Login
                                </a></li>
                        @endauth
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">
        <!-- Hero Section -->
        <section id="hero" class="hero section accent-background" {{-- style="background-image: url({{ asset("img/bg-banner.jpg")}}); background-attachment: fixed; background-size: cover" --}}>
            <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
                {{-- <div class="row gy-5 justify-content-between">
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center ">
                        <h2><span>PENGECEKAN DAN PELAPORAN K3 EFEKTIF, CEPAT, & TANPA RIBET</span></h2>
                        <ul>
                            <li>
                                <span>PENGECEKAN APAR</span>
                                <i class="bi bi-check-circle-fill"></i>
                            </li>
                            <li>
                                <span>PENGECEKAN P3K</span>
                                <i class="bi bi-check-circle-fill"></i>
                            </li>
                            <li>
                                <span>PELAPORAN SAFETY PATROL</span>
                                <i class="bi bi-check-circle-fill"></i>
                            </li>
                        </ul>
                        <div class="d-flex">
                            <a href="#about" class="btn-get-started">DAFTAR SEKARANG</a>
                        </div>
                    </div>
                    <div class="col-lg-5 order-1 order-lg-2">
                    </div>
                    <div class="col-lg-12 order-2 order-lg-1 d-flex flex-column justify-content-center ">
                    </div>
                </div> --}}
                <img src="{{ asset('img/SAFEGUARD.png') }}"  class="img-fluid -mt-1" alt="" />
            </div>

            <div class="icon-boxes position-relative" data-aos="fade-up" data-aos-delay="200">
                <div class=" position-relative">
                    <div class="container text-center mt-5">
                        <div class="row mx-auto my-auto justify-content-center">
                            <div id="recipeCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item active ">
                                        <div class=" col-md-4 px-1">
                                            <div class="icon-box ">
                                                <div class="icon"><i class="bi bi-easel"></i></div>
                                                <h4 class="title"><a href="" class="stretched-link">Energi
                                                        dan Migas</a>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item ">
                                        <div class=" col-md-4 px-1">
                                            <div class="icon-box">
                                                <div class="icon"><i class="bi bi-gem"></i></div>
                                                <h4 class="title"><a href=""
                                                        class="stretched-link">Manufaktur</a></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class=" col-md-4 px-1">
                                            <div class="icon-box">
                                                <div class="icon"><i class="bi bi-geo-alt"></i></div>
                                                <h4 class="title"><a href=""
                                                        class="stretched-link">Konstruksi</a></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class=" col-md-4 px-1">
                                            <div class="icon-box">
                                                <div class="icon"><i class="bi bi-command"></i></div>
                                                <h4 class="title"><a href=""
                                                        class="stretched-link">Transportasi dan Logistik</a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="carousel-control-prev bg-transparent w-aut" href="#recipeCarousel"
                                    role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </a>
                                <a class="carousel-control-next bg-transparent w-aut" href="#recipeCarousel"
                                    role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </a>
                            </div>
                        </div>
                    </div>



                    <script>
                        let items = document.querySelectorAll('.carousel .carousel-item')

                        items.forEach((el) => {
                            const minPerSlide = 4
                            let next = el.nextElementSibling
                            for (var i = 1; i < minPerSlide; i++) {
                                if (!next) {
                                    // wrap carousel by using first child
                                    next = items[0]
                                }
                                let cloneChild = next.cloneNode(true)
                                el.appendChild(cloneChild.children[0])
                                next = next.nextElementSibling
                            }
                        })
                    </script>


                </div>
            </div>
        </section>
        <!-- /Hero Section -->

        <!-- About Section -->
        <section id="about" class="about section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Tentang Kami<br /></h2>
                <p>Mitra kerjasama Anda yang terpecaya untuk masa depan Digital HSSE</p>
            </div>
            <!-- End Section Title -->

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <h3>Digitalisasi HSSE untuk Keselamatan yang Lebih Cerdas dan Efisien</h3>
                        <img src="https://assets.promediateknologi.id/crop/0x0:0x0/750x500/webp/photo/p1/43/2023/09/13/pic-lahan-bekas-tambang-2514565947.jpg"
                            class="img-fluid rounded-4 mb-4" alt="" />
                        <p>
                            Kami adalah perusahaan penyedia solusi digitalisasi yang berfokus pada sektor Health,
                            Safety, Security, and
                            Environment (HSSE). Dengan pengalaman dan teknologi terkini, kami hadir untuk membantu
                            organisasi meningkatkan
                            efisiensi dan efektivitas dalam operasional keselamatan kerja melalui inovasi digital. Kami
                            berkomitmen untuk
                            menciptakan lingkungan kerja yang aman, produktif, dan ramah lingkungan melalui teknologi
                            canggih yang mendukung
                            transparansi dan kepatuhan terhadap standar keselamatan global.
                        </p>
                        <!-- <p>Ut fugiat ut sunt quia veniam. Voluptate perferendis perspiciatis quod nisi et. Placeat debitis quia recusandae odit et consequatur voluptatem. Dignissimos pariatur consectetur fugiat voluptas ea.</p> -->
                        <!-- <p>Temporibus nihil enim deserunt sed ea. Provident sit expedita aut cupiditate nihil vitae quo officia vel. Blanditiis eligendi possimus et in cum. Quidem eos ut sint rem veniam qui. Ut ut repellendus nobis tempore doloribus debitis explicabo similique sit. Accusantium sed ut omnis beatae neque deleniti repellendus.</p> -->
                    </div>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
                        <div class="content ps-0 ps-lg-5">
                            <h3>Vision</h3>
                            <p>
                                Membangun ekosistem kerja yang lebih aman dan efisien dengan mengintegrasikan teknologi
                                digital ke dalam
                                manajemen keselamatan dan kesehatan kerja.
                            </p>
                            <h3>Mission</h3>
                            <ul>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Menyediakan solusi digital yang inovatif untuk kebutuhan HSSE.</span>
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Mendukung perusahaan dalam mencapai kepatuhan terhadap regulasi keselamatan
                                        kerja.</span>
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Meningkatkan produktivitas melalui sistem pemantauan yang transparan dan
                                        akurat.</span>
                                </li>
                            </ul>
                            <!-- <p>
                Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident
              </p> -->

                            <div class="position-relative mt-4">
                                <img src="https://www.landmarkacademyhub.co.uk/wp-content/uploads/2020/11/coal-1626368__480-1000x500.jpg"
                                    class="img-fluid rounded-4" alt="" />
                                <!-- <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /About Section -->

        <!-- Services Section -->
        <section id="services" class="services section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Layanan Kami</h2>
                <p>Layanan yang menunjkan dedikasi kami pada inovasi dan kualitas</p>
            </div>
            <!-- End Section Title -->

            <div class="container">
                <div class="row gy-4 justify-content-center">
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <i class="bi bi-activity"></i>
                            </div>
                            <h3>Safety Patrol</h3>
                            <p>
                                Digitalisasi safety patrol dengan pelacakan real-time, dokumentasi inspeksi, dan
                                peringatan dini potensi bahaya
                            </p>
                            <a href="service-details.html" class="readmore stretched-link">Read more <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <i class="bi bi-broadcast"></i>
                            </div>
                            <h3>Pengecekan Kotak P3K</h3>
                            <p>
                                Digitalisasi sistem inventori otomatis dan notifikasi cloud untuk memastikan kotak P3K
                                selalu lengkap dan siap
                                digunakan
                            </p>
                            <a href="service-details.html" class="readmore stretched-link">Read more <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item position-relative">
                            <div class="icon">
                                <i class="bi bi-easel"></i>
                            </div>
                            <h3>Pengecekan APAR</h3>
                            <p>
                                Digitalisasi terpadu untuk mencatat status APAR, notifikasi otomatis penggantian, serta
                                monitoring inventori
                                melalui dashboard
                            </p>
                            <a href="service-details.html" class="readmore stretched-link">Read more <i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                    <!-- End Service Item -->
                </div>
            </div>
        </section>
        <!-- /Services Section -->

        <!-- Services Section -->
        <section id="why" class="services section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Kenapa harus kami?
                </h2>
                <p>Membangun masa depan digitalisasi HSSE bersama kami</p>
            </div>
            <!-- End Section Title -->

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="why-item-red position-relative">
                            <div class="icon">
                                <i class="bi bi-activity"></i>
                            </div>
                            <h3>Teknologi Terdepan</h3>
                            <p>
                                Solusi modern untuk keselamatan dan efisiensi</p>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="why-item-white position-relative">
                            <div class="icon">
                                <i class="bi bi-broadcast"></i>
                            </div>
                            <h3>Efisiensi Operasional</h3>
                            <p>
                                Proses kerja optimal, hemat waktu dan biaya
                            </p>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="why-item-red position-relative">
                            <div class="icon">
                                <i class="bi bi-easel"></i>
                            </div>
                            <h3>Kepatuhan Regulasi</h3>
                            <p>
                                Memastikan kepatuhan standar keselamatan
                            </p>
                        </div>
                    </div>
                    <!-- End Service Item -->
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="why-item-white position-relative">
                            <div class="icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <h3>Dukungan 24/7</h3>
                            <p>
                                Bantuan nonstop untuk kelancaran operasional
                            </p>
                        </div>
                    </div>
                    <!-- End Service Item -->
                </div>
            </div>
        </section>
        <!-- /Services Section -->



        <!-- Contact Section -->
        <section id="contact" class="contact section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Kontak Kami</h2>
                <p>Tertarik bekerja sama dengan kami? Mari isi form dan mulai diskusi mewujudkan tujuanmu </p>
            </div>
            <!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gx-lg-0 gy-4">
                    <div class="col-lg-4">
                        <div class="info-container d-flex flex-column align-items-center justify-content-center">
                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                                <i class="bi bi-geo-alt flex-shrink-0"></i>
                                <div>
                                    <h3>Address</h3>
                                    <p>A108 Adam Street, New York, NY 535022</p>
                                </div>
                            </div>
                            <!-- End Info Item -->

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                                <i class="bi bi-telephone flex-shrink-0"></i>
                                <div>
                                    <h3>Call Us</h3>
                                    <p>+1 5589 55488 55</p>
                                </div>
                            </div>
                            <!-- End Info Item -->

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                                <i class="bi bi-envelope flex-shrink-0"></i>
                                <div>
                                    <h3>Email Us</h3>
                                    <p>info@example.com</p>
                                </div>
                            </div>
                            <!-- End Info Item -->

                            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                                <i class="bi bi-clock flex-shrink-0"></i>
                                <div>
                                    <h3>Open Hours:</h3>
                                    <p>Mon-Sat: 11AM - 23PM</p>
                                </div>
                            </div>
                            <!-- End Info Item -->
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade"
                            data-aos-delay="100">
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Your Name" required="" />
                                </div>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Your Email" required="" />
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject"
                                        required="" />
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="8" placeholder="Message" required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>

                                    <button type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Contact Form -->
                </div>
            </div>
        </section>
        <!-- /Contact Section -->
    </main>

    <footer id="footer" class="footer accent-background">
        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-5 col-md-12 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">SafeGuard</span>
                    </a>
                    <p>
                        Digitalisasi HSSE untuk Keselamatan yang Lebih Cerdas dan Efisien
                    </p>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Link</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                        <li><a href="#services">Layanan</a></li>
                        <li><a href="#why">Mengapa Kami</a></li>
                        <li><a href="#contact">Kontak</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="#">Safety Patrol</a></li>
                        <li><a href="#">Pengecekan Kotak P3K</a></li>
                        <li><a href="#">Pengecekan APAR</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                    <h4>Contact Us</h4>
                    <p>A108 Adam Street</p>
                    <p>New York, NY 535022</p>
                    <p>United States</p>
                    <p class="mt-4"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
                    <p><strong>Email:</strong> <span>info@example.com</span></p>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Impact</strong> <span>All Rights Reserved</span>
            </p>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you've purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by
                <a href="https://themewagon.com">ThemeWagon</a>
            </div>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('impact/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('impact/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('impact/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('impact/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('impact/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('impact/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('impact/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('impact/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('impact/assets/js/main.js') }}"></script>
</body>

</html>
