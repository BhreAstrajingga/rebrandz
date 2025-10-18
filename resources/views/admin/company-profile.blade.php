<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile</title>
    <link rel="shortcut icon" href="/assets/images/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <main>
        <div class="main-content">
            <article class="about active" data-page="about">
                <header>
                    <h2 class="h2 article-title">{{ $company->name ?? '-' }}</h2>
                </header>

                {{-- <section class="about-text">
					<p>Detail profil perusahaan berikut diambil dari data yang tersedia. Pastikan Anda menyimpan atau mengunduh berkas yang diperlukan.</p>
				</section> --}}

                <section class="service">
                    <h3 class="h3 service-title">Company Data</h3>
                    <ul class="contacts-list">
                        <li class="contact-item">
                            <div class="icon-box">
                                <ion-icon name="id-card-outline"></ion-icon>
                            </div>
                            <div class="contact-info">
                                <p class="contact-title">Company Name</p>
                                <p class="service-item-text">{{ $company->name ?? '-' }}</p>
                            </div>
                        </li>
                        <li class="contact-item">
                            <div class="icon-box">
                                <ion-icon name="location-outline"></ion-icon>
                            </div>
                            <div class="contact-info">
                                <p class="contact-title">Address</p>
                                <p class="service-item-text">{!! $companyAddress !!}</p>
                            </div>
                        </li>
                        <li class="contact-item">
                            <div class="icon-box">
                                <ion-icon name="phone-portrait-outline"></ion-icon>
                            </div>
                            <div class="contact-info">
                                <p class="contact-title">Phone</p>
                                <a href="tel:{{ $company->phone ?? '-' }}"
                                    class="service-item-text contact-link">{{ $company->phone ?? '-' }}</a>
                            </div>
                        </li>
                        <li class="contact-item">
                            <div class="icon-box">
                                <ion-icon name="mail-outline"></ion-icon>
                            </div>
                            <div class="contact-info">
                                <p class="contact-title">Email</p>
                                <a href="mailto:{{ $company->mail ?? '-' }}"
                                    class="service-item-text contact-link">{{ $company->mail ?? '-' }}</a>
                            </div>
                        </li>
                        @forelse ($customColumns as $item)
                            <li class="contact-item">
                                <div class="icon-box">
                                    <ion-icon name="{{ ($item['type'] === 'file') ? 'download-outline' : 'document-text-outline' }}"></ion-icon>
                                </div>
                                <div class="contact-info">
                                    <p class="contact-title">{{ $item['label'] }}</p>
                                    @if ($item['type'] === 'file' && $item['value'])
                                        <p class="service-item-text">
                                            <a class="contact-link"
                                                href="{{ route('profile.attachment.download', ['path' => $item['value'], 'alias' => $profile->company_alias]) }}"
                                                download rel="noopener">Unduh Lampiran</a>
                                        </p>
                                    @else
                                        <p class="service-item-text">{{ $item['value'] ?? '-' }}</p>
                                    @endif
                                </div>
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </section>
            </article>
        </div>
    </main>

    <script type="module" src="/assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                var params = new URLSearchParams(window.location.search);
                var alias = params.get('alias');
                var tempToken = params.get('tempToken');
                if (alias && tempToken) {
                    var url = "{{ route('profile.consume') }}" + "?alias=" + encodeURIComponent(alias) + "&tempToken=" + encodeURIComponent(tempToken);
                    fetch(url, { method: 'GET', credentials: 'same-origin' }).catch(function () {});
                }
            } catch (e) {
                // ignore
            }
        });
    </script>
</body>

</html>
