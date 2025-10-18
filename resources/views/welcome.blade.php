<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deddy Dwi Raharjo - Personal Portfolio</title>
    <link rel="shortcut icon" href="./assets/images/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <main>
        <aside class="sidebar" data-sidebar>

            <div class="sidebar-info">

                <figure class="avatar-box">
                    <img src="./assets/images/my-avatar.png" alt="Deddy Dwi Raharjo" width="80">
                </figure>

                <div class="info-content">
                    <h1 class="name" title="Deddy Dwi Raharjo">Deddy Dwi Raharjo</h1>

                    <p class="title">Web developer</p>
                </div>

                <button class="info_more-btn" data-sidebar-btn>
                    <span>Show Contacts</span>

                    <ion-icon name="chevron-down"></ion-icon>
                </button>

            </div>

            <div class="sidebar-info_more">

                <div class="separator"></div>

                <ul class="contacts-list">

                    <li class="contact-item">

                        <div class="icon-box">
                            <ion-icon name="mail-outline"></ion-icon>
                        </div>

                        <div class="contact-info">
                            <p class="contact-title">Email</p>

                            <a href="mailto:sultanjawer@gmail.com" class="contact-link">sultanjawer@gmail.com</a>
                        </div>

                    </li>

                    <li class="contact-item">

                        <div class="icon-box">
                            <ion-icon name="phone-portrait-outline"></ion-icon>
                        </div>

                        <div class="contact-info">
                            <p class="contact-title">Phone</p>

                            <a href="tel:+628180880507" class="contact-link">+62 81 8088 0507</a>
                        </div>

                    </li>

                    <li class="contact-item">

                        <div class="icon-box">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </div>

                        <div class="contact-info">
                            <p class="contact-title">Birthday</p>

                            <time datetime="1982-06-23">April 23, 1976</time>
                        </div>

                    </li>

                    <li class="contact-item">

                        <div class="icon-box">
                            <ion-icon name="location-outline"></ion-icon>
                        </div>

                        <div class="contact-info">
                            <p class="contact-title">Location</p>

                            <address>Bogor, West Java, Indonesia</address>
                        </div>

                    </li>

                </ul>

                <div class="separator"></div>

                <ul class="social-list">

                    <li class="social-item">
                        <a href="#" class="social-link">
                            <ion-icon name="logo-facebook"></ion-icon>
                        </a>
                    </li>

                    <li class="social-item">
                        <a href="#" class="social-link">
                            <ion-icon name="logo-twitter"></ion-icon>
                        </a>
                    </li>

                    <li class="social-item">
                        <a href="#" class="social-link">
                            <ion-icon name="logo-instagram"></ion-icon>
                        </a>
                    </li>

                </ul>

            </div>
        </aside>

        <div class="main-content">
            <nav class="navbar">
                <ul class="navbar-list">
                    <li class="navbar-item">
                        <button class="navbar-link  active" data-nav-link>About</button>
                    </li>

                    <li class="navbar-item">
                        <button class="navbar-link" data-nav-link>Resume</button>
                    </li>

                    <li class="navbar-item">
                        <button class="navbar-link" data-nav-link>Portfolio</button>
                    </li>

                    <li class="navbar-item">
                        <button class="navbar-link" data-nav-link>Blog</button>
                    </li>

                    <li class="navbar-item">
                        <button class="navbar-link" data-nav-link>Contact</button>
                    </li>
                </ul>
            </nav>
            <article class="about  active" data-page="about">
                <header>
                    <h2 class="h2 article-title">About me</h2>
                </header>

                <section class="about-text">
                    <p>I am a full-stack developer with a strong focus on building web-based and spatial systems for the
                        public and social sectors. With over 15 years of experience developing applications using
                        Laravel, I have recently expanded my expertise to mobile development with Flutter and backend
                        systems using Node.js and the Frappe Framework.</p>

                    <p>I specialize in system analysis, data architecture design, and the implementation of spatial
                        features such as GeoJSON processing, interactive mapping, and location verification. I am used
                        to working across teams with flexible roles — from junior developer and backend engineer to
                        system analyst — in national-scale government projects.</p>

                    <p>I believe that a good system is not only functional, but also relevant, efficient, and
                        user-friendly in real-world field conditions. With a strong commitment to continuous learning
                        and upholding solid technical standards, I am open to contributing to high-impact, meaningful
                        projects.</p>
                </section>


                <section class="service">
                    <h3 class="h3 service-title">What I'm Doing</h3>

                    <ul class="service-list">

                        <li class="service-item">
                            <div class="service-icon-box">
                                <img src="./assets/images/icon-design.svg" alt="design icon" width="40">
                            </div>
                            <div class="service-content-box">
                                <h4 class="h4 service-item-title">Web Design</h4>
                                <p class="service-item-text">
                                    Crafting responsive and accessible web interfaces with attention to usability and
                                    modern design trends.
                                </p>
                            </div>
                        </li>

                        <li class="service-item">
                            <div class="service-icon-box">
                                <img src="./assets/images/icon-dev.svg" alt="web development icon" width="40">
                            </div>
                            <div class="service-content-box">
                                <h4 class="h4 service-item-title">Web Development</h4>
                                <p class="service-item-text">
                                    Building scalable and maintainable web applications using Laravel, Node.js, and
                                    modern frameworks.
                                </p>
                            </div>
                        </li>

                        <li class="service-item">
                            <div class="service-icon-box">
                                <img src="./assets/images/icon-app.svg" alt="mobile app icon" width="40">
                            </div>
                            <div class="service-content-box">
                                <h4 class="h4 service-item-title">Mobile Apps</h4>
                                <p class="service-item-text">
                                    Developing cross-platform mobile applications with Flutter, optimized for
                                    performance and user experience.
                                </p>
                            </div>
                        </li>

                        <li class="service-item">
                            <div class="service-icon-box">
                                <img src="./assets/images/icon-photo.svg" alt="map icon" width="40">
                            </div>
                            <div class="service-content-box">
                                <h4 class="h4 service-item-title">Spatial Systems</h4>
                                <p class="service-item-text">
                                    Implementing map-based features using GeoJSON, spatial validation, and
                                    location-aware technologies.
                                </p>
                            </div>
                        </li>

                        <li class="service-item">
                            <div class="service-icon-box">
                                <img src="./assets/images/icon-dev.svg" alt="architecture icon" width="40">
                            </div>
                            <div class="service-content-box">
                                <h4 class="h4 service-item-title">System Architecture</h4>
                                <p class="service-item-text">
                                    Designing robust backend systems and data structures for high-impact public service
                                    platforms.
                                </p>
                            </div>
                        </li>

                        <li class="service-item">
                            <div class="service-icon-box">
                                <img src="./assets/images/icon-photo.svg" alt="travel icon" width="40">
                            </div>
                            <div class="service-content-box">
                                <h4 class="h4 service-item-title">Travelling</h4>
                                <p class="service-item-text">
                                    Exploring new places to recharge, gain fresh perspectives, and find inspiration
                                    beyond the screen.
                                </p>
                            </div>
                        </li>

                    </ul>
                </section>


                <section class="testimonials" hidden>

                    <h3 class="h3 testimonials-title">Testimonials</h3>

                    <ul class="testimonials-list has-scrollbar">

                        <li class="testimonials-item">
                            <div class="content-card" data-testimonials-item>

                                <figure class="testimonials-avatar-box">
                                    <img src="./assets/images/avatar-1.png" alt="Daniel lewis" width="60"
                                        data-testimonials-avatar>
                                </figure>

                                <h4 class="h4 testimonials-item-title" data-testimonials-title>Daniel lewis</h4>

                                <div class="testimonials-text" data-testimonials-text>
                                    <p>
                                        Richard was hired to create a corporate identity. We were very pleased with the
                                        work done. She has a
                                        lot of experience
                                        and is very concerned about the needs of client. Lorem ipsum dolor sit amet,
                                        ullamcous cididt
                                        consectetur adipiscing
                                        elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                                    </p>
                                </div>

                            </div>
                        </li>

                        <li class="testimonials-item">
                            <div class="content-card" data-testimonials-item>

                                <figure class="testimonials-avatar-box">
                                    <img src="./assets/images/avatar-2.png" alt="Jessica miller" width="60"
                                        data-testimonials-avatar>
                                </figure>

                                <h4 class="h4 testimonials-item-title" data-testimonials-title>Jessica miller</h4>

                                <div class="testimonials-text" data-testimonials-text>
                                    <p>
                                        Richard was hired to create a corporate identity. We were very pleased with the
                                        work done. She has a
                                        lot of experience
                                        and is very concerned about the needs of client. Lorem ipsum dolor sit amet,
                                        ullamcous cididt
                                        consectetur adipiscing
                                        elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                                    </p>
                                </div>

                            </div>
                        </li>

                        <li class="testimonials-item">
                            <div class="content-card" data-testimonials-item>

                                <figure class="testimonials-avatar-box">
                                    <img src="./assets/images/avatar-3.png" alt="Emily evans" width="60"
                                        data-testimonials-avatar>
                                </figure>

                                <h4 class="h4 testimonials-item-title" data-testimonials-title>Emily evans</h4>

                                <div class="testimonials-text" data-testimonials-text>
                                    <p>
                                        Richard was hired to create a corporate identity. We were very pleased with the
                                        work done. She has a
                                        lot of experience
                                        and is very concerned about the needs of client. Lorem ipsum dolor sit amet,
                                        ullamcous cididt
                                        consectetur adipiscing
                                        elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                                    </p>
                                </div>

                            </div>
                        </li>

                        <li class="testimonials-item">
                            <div class="content-card" data-testimonials-item>

                                <figure class="testimonials-avatar-box">
                                    <img src="./assets/images/avatar-4.png" alt="Henry william" width="60"
                                        data-testimonials-avatar>
                                </figure>

                                <h4 class="h4 testimonials-item-title" data-testimonials-title>Henry william</h4>

                                <div class="testimonials-text" data-testimonials-text>
                                    <p>
                                        Richard was hired to create a corporate identity. We were very pleased with the
                                        work done. She has a
                                        lot of experience
                                        and is very concerned about the needs of client. Lorem ipsum dolor sit amet,
                                        ullamcous cididt
                                        consectetur adipiscing
                                        elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                                    </p>
                                </div>

                            </div>
                        </li>

                    </ul>

                </section>

                <div class="modal-container" data-modal-container hidden>
                    <div class="overlay" data-overlay></div>
                    <section class="testimonials-modal">

                        <button class="modal-close-btn" data-modal-close-btn>
                            <ion-icon name="close-outline"></ion-icon>
                        </button>

                        <div class="modal-img-wrapper">
                            <figure class="modal-avatar-box">
                                <img src="./assets/images/avatar-1.png" alt="Daniel lewis" width="80"
                                    data-modal-img>
                            </figure>

                            <img src="./assets/images/icon-quote.svg" alt="quote icon">
                        </div>

                        <div class="modal-content">

                            <h4 class="h3 modal-title" data-modal-title>Daniel lewis</h4>

                            <time datetime="2021-06-14">14 June, 2021</time>

                            <div data-modal-text>
                                <p>
                                    Richard was hired to create a corporate identity. We were very pleased with the work
                                    done. She has a
                                    lot of experience
                                    and is very concerned about the needs of client. Lorem ipsum dolor sit amet,
                                    ullamcous cididt
                                    consectetur adipiscing
                                    elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                                </p>
                            </div>

                        </div>

                    </section>
                </div>

                <section class="clients">
                    <h3 class="h3 clients-title">Clients</h3>
                    <ul class="clients-list has-scrollbar ">
                        <li class="clients-item">
                            <a href="">
                                <img src="./assets/images/clients/adorama.png" alt="adorama logo">
                            </a>
                        </li>

                        <li class="clients-item">
                            <a href="https://majalahagraria.today">
                                <img src="./assets/images/clients/argraria.png" alt="argraria logo">
                            </a>
                        </li>
                        <li class="clients-item">
                            <a href="">
                                <img src="./assets/images/clients/katalis.png" alt="katalis logo">
                            </a>
                        </li>

                        <li class="clients-item">
                            <a href="">
                                <img src="./assets/images/clients/prospera1.webp" alt="pertanian logo">
                            </a>
                        </li>

                        <li class="clients-item">
                            <a href="">
                                <img src="./assets/images/clients/pertanian.png" alt="prospera1 logo">
                            </a>
                        </li>

                        <li class="clients-item">
                            <a href="">
                                <img src="./assets/images/clients/wahana.png" alt="wahana logo">
                            </a>
                        </li>

                    </ul>
                </section>

            </article>
            <article class="resume" data-page="resume">
                <header>
                    <h2 class="h2 article-title">Resume</h2>
                </header>
                <section class="timeline">
                    <div class="title-wrapper">
                        <div class="icon-box">
                            <ion-icon name="book-outline"></ion-icon>
                        </div>

                        <h3 class="h3">Education</h3>
                    </div>
                    <ol class="timeline-list">

                        <li class="timeline-item">

                            <h4 class="h4 timeline-item-title">Institute Teknologi Nasional, Malang</h4>

                            <span>1997 — 2001</span>

                            <p class="timeline-text">
                                Mengambil jurusan Teknik Arsitektur fakultas Teknik Sipil.
                            </p>

                        </li>

                        <li class="timeline-item">

                            <h4 class="h4 timeline-item-title">Sekolah Menengah Atas Negeri 13, Bandung</h4>

                            <span>2006 — 2007</span>

                            <p class="timeline-text">
                                Ratione voluptatem sequi nesciunt, facere quisquams facere menda ossimus, omnis voluptas
                                assumenda est
                                omnis..
                            </p>

                        </li>

                    </ol>
                </section>
                <section class="timeline">
                    <div class="title-wrapper">
                        <div class="icon-box">
                            <ion-icon name="book-outline"></ion-icon>
                        </div>

                        <h3 class="h3">Experience</h3>
                    </div>
                    <ol class="timeline-list">

                        <li class="timeline-item">

                            <h4 class="h4 timeline-item-title">Creative director</h4>

                            <span>2015 — Present</span>

                            <p class="timeline-text">
                                Nemo enim ipsam voluptatem blanditiis praesentium voluptum delenit atque corrupti, quos
                                dolores et qvuas
                                molestias
                                exceptur.
                            </p>

                        </li>

                        <li class="timeline-item">

                            <h4 class="h4 timeline-item-title">Art director</h4>

                            <span>2013 — 2015</span>

                            <p class="timeline-text">
                                Nemo enims ipsam voluptatem, blanditiis praesentium voluptum delenit atque corrupti,
                                quos dolores et
                                quas molestias
                                exceptur.
                            </p>

                        </li>

                        <li class="timeline-item">

                            <h4 class="h4 timeline-item-title">Web designer</h4>

                            <span>2010 — 2013</span>

                            <p class="timeline-text">
                                Nemo enims ipsam voluptatem, blanditiis praesentium voluptum delenit atque corrupti,
                                quos dolores et
                                quas molestias
                                exceptur.
                            </p>

                        </li>

                    </ol>
                </section>
                <section class="skill">
                    <h3 class="h3 skills-title">My skills</h3>
                    <ul class="skills-list content-card">

                        <li class="skills-item">

                            <div class="title-wrapper">
                                <h5 class="h5">PHP</h5>
                                <data value="90">90%</data>
                            </div>

                            <div class="skill-progress-bg">
                                <div class="skill-progress-fill" style="width: 90%;"></div>
                            </div>

                        </li>

                        <li class="skills-item">

                            <div class="title-wrapper">
                                <h5 class="h5">Javascript</h5>
                                <data value="70">70%</data>
                            </div>

                            <div class="skill-progress-bg">
                                <div class="skill-progress-fill" style="width: 70%;"></div>
                            </div>

                        </li>

                        <li class="skills-item">

                            <div class="title-wrapper">
                                <h5 class="h5">SQL (MySql/MariaDB)</h5>
                                <data value="85">85%</data>
                            </div>

                            <div class="skill-progress-bg">
                                <div class="skill-progress-fill" style="width: 85%;"></div>
                            </div>

                        </li>

                        <li class="skills-item">

                            <div class="title-wrapper">
                                <h5 class="h5">Flutter (Dart)</h5>
                                <data value="65">65%</data>
                            </div>

                            <div class="skill-progress-bg">
                                <div class="skill-progress-fill" style="width: 65%;"></div>
                            </div>

                        </li>

                    </ul>
                </section>
            </article>
            <article class="portfolio" data-page="portfolio">

                <header>
                    <h2 class="h2 article-title">Portfolio</h2>
                </header>

                <section class="projects">

                    <ul class="filter-list">

                        <li class="filter-item">
                            <button class="active" data-filter-btn>All</button>
                        </li>

                        <li class="filter-item">
                            <button data-filter-btn>Web design</button>
                        </li>

                        <li class="filter-item">
                            <button data-filter-btn>Applications</button>
                        </li>

                        <li class="filter-item">
                            <button data-filter-btn>Web development</button>
                        </li>

                    </ul>

                    <div class="filter-select-box">

                        <button class="filter-select" data-select>

                            <div class="select-value" data-selecct-value>Select category</div>

                            <div class="select-icon">
                                <ion-icon name="chevron-down"></ion-icon>
                            </div>

                        </button>

                        <ul class="select-list">

                            <li class="select-item">
                                <button data-select-item>All</button>
                            </li>

                            <li class="select-item">
                                <button data-select-item>Web design</button>
                            </li>

                            <li class="select-item">
                                <button data-select-item>Applications</button>
                            </li>

                            <li class="select-item">
                                <button data-select-item>Web development</button>
                            </li>

                        </ul>

                    </div>

                    <ul class="project-list">

                        <li class="project-item  active" data-filter-item data-category="web development">
                            <a href="">

                                <figure class="project-img">
                                    <div class="project-item-icon-box">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>

                                    <img src="./assets/images/project-1.jpg" alt="finance" loading="lazy">
                                </figure>

                                <h3 class="project-title">Finance</h3>

                                <p class="project-category">Web development</p>

                            </a>
                        </li>

                        <li class="project-item  active" data-filter-item data-category="web development">
                            <a href="">

                                <figure class="project-img">
                                    <div class="project-item-icon-box">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>

                                    <img src="./assets/images/project-2.png" alt="orizon" loading="lazy">
                                </figure>

                                <h3 class="project-title">Orizon</h3>

                                <p class="project-category">Web development</p>

                            </a>
                        </li>

                        <li class="project-item  active" data-filter-item data-category="web design">
                            <a href="">

                                <figure class="project-img">
                                    <div class="project-item-icon-box">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>

                                    <img src="./assets/images/project-3.jpg" alt="fundo" loading="lazy">
                                </figure>

                                <h3 class="project-title">Fundo</h3>

                                <p class="project-category">Web design</p>

                            </a>
                        </li>

                        <li class="project-item  active" data-filter-item data-category="applications">
                            <a href="">

                                <figure class="project-img">
                                    <div class="project-item-icon-box">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>

                                    <img src="./assets/images/project-4.png" alt="brawlhalla" loading="lazy">
                                </figure>

                                <h3 class="project-title">Brawlhalla</h3>

                                <p class="project-category">Applications</p>

                            </a>
                        </li>

                        <li class="project-item  active" data-filter-item data-category="web design">
                            <a href="">

                                <figure class="project-img">
                                    <div class="project-item-icon-box">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>

                                    <img src="./assets/images/project-5.png" alt="dsm." loading="lazy">
                                </figure>

                                <h3 class="project-title">DSM.</h3>

                                <p class="project-category">Web design</p>

                            </a>
                        </li>

                        <li class="project-item  active" data-filter-item data-category="web design">
                            <a href="">

                                <figure class="project-img">
                                    <div class="project-item-icon-box">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>

                                    <img src="./assets/images/project-6.png" alt="metaspark" loading="lazy">
                                </figure>

                                <h3 class="project-title">MetaSpark</h3>

                                <p class="project-category">Web design</p>

                            </a>
                        </li>

                        <li class="project-item  active" data-filter-item data-category="web development">
                            <a href="">

                                <figure class="project-img">
                                    <div class="project-item-icon-box">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>

                                    <img src="./assets/images/project-7.png" alt="summary" loading="lazy">
                                </figure>

                                <h3 class="project-title">Summary</h3>

                                <p class="project-category">Web development</p>

                            </a>
                        </li>

                        <li class="project-item  active" data-filter-item data-category="applications">
                            <a href="">

                                <figure class="project-img">
                                    <div class="project-item-icon-box">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>

                                    <img src="./assets/images/project-8.jpg" alt="task manager" loading="lazy">
                                </figure>

                                <h3 class="project-title">Task Manager</h3>

                                <p class="project-category">Applications</p>

                            </a>
                        </li>

                        <li class="project-item  active" data-filter-item data-category="web development">
                            <a href="">

                                <figure class="project-img">
                                    <div class="project-item-icon-box">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </div>

                                    <img src="./assets/images/project-9.png" alt="arrival" loading="lazy">
                                </figure>

                                <h3 class="project-title">Arrival</h3>

                                <p class="project-category">Web development</p>

                            </a>
                        </li>

                    </ul>

                </section>

            </article>
            <article class="blog" data-page="blog">

                <header>
                    <h2 class="h2 article-title">Blog</h2>
                </header>

                <section class="blog-posts">

                    <ul class="blog-posts-list">
                        @forelse ($posts as $post)
                            <li class="blog-post-item">
                                <a href="{{ route('blog.show', $post->slug) }}">

                                    <figure class="blog-banner-box">
                                        <img src="{{ $post->cover_image_path ? (str_starts_with($post->cover_image_path, 'http') ? $post->cover_image_path : asset('storage/' . ltrim($post->cover_image_path, '/'))) : './assets/images/blog-1.jpg' }}"
                                            alt="{{ $post->title }}" loading="lazy">
                                    </figure>

                                    <div class="blog-content">

                                        <div class="blog-meta">
                                            <p class="blog-category">Article</p>

                                            <span class="dot"></span>

                                            <time
                                                datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('M d, Y') }}</time>
                                        </div>

                                        <h3 class="h3 blog-item-title">{{ $post->title }}</h3>

                                        <p class="blog-text">{{ $post->excerpt }}</p>

                                    </div>

                                </a>
                            </li>
                        @empty
                            <li class="blog-post-item">
                                <div class="blog-content">
                                    <p class="blog-text">Something incredible is coming..</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>

                    @if (!empty($hasMoreBlogPosts))
                        <div style="margin-top: 8px; display: flex; justify-content: flex-end;">
                            <a href="{{ route('blog.index') }}" class="contact-link navbar-link active">More Articles...</a>
                        </div>
                    @endif
                </section>

            </article>
            <article class="contact" data-page="contact">

                <header>
                    <h2 class="h2 article-title">Contact</h2>
                </header>

                <section class="mapbox" data-mapbox>
                    <figure>
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d199666.5651251294!2d-121.58334177520186!3d38.56165006739519!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x809ac672b28397f9%3A0x921f6aaa74197fdb!2sSacramento%2C%20CA%2C%20USA!5e0!3m2!1sen!2sbd!4v1647608789441!5m2!1sen!2sbd"
                            width="400" height="300" loading="lazy"></iframe>
                    </figure>
                </section>

                <section class="contact-form">

                    <h3 class="h3 form-title">Contact Form</h3>

                    <form action="#" class="form" data-form>

                        <div class="input-wrapper">
                            <input type="text" name="fullname" class="form-input" placeholder="Full name"
                                required data-form-input>

                            <input type="email" name="email" class="form-input" placeholder="Email address"
                                required data-form-input>
                        </div>

                        <textarea name="message" class="form-input" placeholder="Your Message" required data-form-input></textarea>

                        <button class="form-btn" type="submit" disabled data-form-btn>
                            <ion-icon name="paper-plane"></ion-icon>
                            <span>Send Message</span>
                        </button>

                    </form>

                </section>

            </article>
        </div>

    </main>
    <script src="./assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
