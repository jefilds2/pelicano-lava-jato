<?php
$pageTitle = 'Lava Jato em Guanhães MG | Pelicano Lava-Jato';
$metaDescription = 'Lava jato em Guanhães MG com lavagem simples, lavagem detalhada, limpeza interna, higienização de bancos, polimento automotivo, enceramento e agendamento pelo WhatsApp.';
$bodyClass = 'bg-slate-950 text-white font-sans antialiased';
$useBaseCss = false;
$seoBusinessName = 'Pelicano Lava-Jato';
$seoStreetAddress = 'Av. Governador Milton Campos, 3540';
$seoCity = 'Guanhães';
$seoState = 'MG';
$seoPostalCode = '39740-000';
$whatsappDigits = slug_phone($company['whatsapp'] ?? '');
$phoneDigits = slug_phone($company['phone'] ?? ($company['whatsapp'] ?? ''));
$whatsappE164Digits = $whatsappDigits === '' ? '' : (str_starts_with($whatsappDigits, '55') ? $whatsappDigits : '55' . $whatsappDigits);
$phoneE164Digits = $phoneDigits === '' ? '' : (str_starts_with($phoneDigits, '55') ? $phoneDigits : '55' . $phoneDigits);
$whatsLink = $whatsappE164Digits !== ''
    ? 'https://wa.me/' . $whatsappE164Digits . '?text=' . urlencode('Olá! Vi o seu site e gostaria de agendar um horário no Pelicano Lava-Jato.')
    : '#';
$phoneLink = $phoneE164Digits !== '' ? 'tel:+' . $phoneE164Digits : '';
$heroVideo = $media['hero_video'];
$heroPoster = $media['hero_images'][0] ?? '';
$aboutImage = $media['hero_images'][1] ?? $heroPoster;
$galleryVideos = $media['real_videos'];
$siteRoot = rtrim((string) config()['app_url'], '/');
$appUrl = h($siteRoot);
$canonicalUrl = $siteRoot . '/';
$heroPosterFile = $heroPoster !== '' ? dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . ltrim(str_replace('/', DIRECTORY_SEPARATOR, urldecode($heroPoster)), DIRECTORY_SEPARATOR) : '';
$ogImage = ($heroPoster !== '' && is_file($heroPosterFile)) ? $siteRoot . $heroPoster : '';
$companyAddressDisplay = $seoStreetAddress . ', ' . $seoCity . ' - ' . $seoState . ', ' . $seoPostalCode;
$companyAddress = h($companyAddressDisplay);
$modelCss = h(asset('css/model.css'));
$logoPath = h(asset('img/logo.png'));
$ogMetaTags = $ogImage !== ''
    ? '<meta property="og:image" content="' . h($ogImage) . '">' . PHP_EOL
        . '<meta property="og:image:alt" content="Lava jato em Guanhães MG - Pelicano Lava-Jato">' . PHP_EOL
        . '<meta name="twitter:image" content="' . h($ogImage) . '">' . PHP_EOL
    : '';
$localBusinessDescription = 'Lava jato em Guanhães MG com lavagem simples, lavagem detalhada, limpeza interna, higienização de bancos, polimento automotivo, enceramento e atendimento por WhatsApp.';
$seoIntro = 'Pelicano Lava-Jato: lavagem automotiva, estética automotiva e polimento em Guanhães - MG.';
$serviceNames = array_map(static fn(array $service): string => (string) ($service['name'] ?? ''), $services);
$serviceNames = array_values(array_filter($serviceNames));
$serviceCatalog = array_map(
    static fn(array $service): array => [
        '@type' => 'Service',
        'name' => (string) $service['name'],
        'description' => (string) ($service['description'] ?? ''),
        'provider' => [
            '@type' => 'LocalBusiness',
            'name' => 'Pelicano Lava-Jato',
        ],
        'areaServed' => [
            '@type' => 'City',
            'name' => 'Guanhães',
        ],
    ],
    $services
);
$faqItems = [
    [
        'question' => 'Onde encontrar lava jato em Guanhães?',
        'answer' => 'O Pelicano Lava-Jato fica em Guanhães - MG, na Av. Governador Milton Campos, 3540, com atendimento e agendamento pelo WhatsApp.',
    ],
    [
        'question' => 'O Pelicano Lava-Jato faz lavagem completa?',
        'answer' => 'Sim. O Pelicano oferece lavagem simples, lavagem detalhada, limpeza interna e outros serviços de estética automotiva em Guanhães.',
    ],
    [
        'question' => 'Tem polimento automotivo em Guanhães?',
        'answer' => 'Sim. O Pelicano Lava-Jato realiza polimento automotivo em Guanhães, além de enceramento técnico e cuidados estéticos para o veículo.',
    ],
    [
        'question' => 'O Pelicano faz higienização interna?',
        'answer' => 'Sim. O serviço inclui opções como limpeza interna, higienização de bancos automotivos e outros cuidados para o interior do carro.',
    ],
    [
        'question' => 'Como agendar pelo WhatsApp?',
        'answer' => 'Basta clicar nos botões de WhatsApp da página e enviar sua mensagem para combinar horário e serviço desejado.',
    ],
    [
        'question' => 'Onde fica o Pelicano Lava-Jato?',
        'answer' => 'O Pelicano Lava-Jato está localizado na Av. Governador Milton Campos, 3540, em Guanhães - MG.',
    ],
];
$serviceVisuals = [
    ['icon' => 'car', 'wrapper' => 'bg-brand-500/10', 'iconColor' => 'text-brand-400', 'description' => 'Lavagem externa com produtos de qualidade para manter seu carro sempre limpo.'],
    ['icon' => 'sparkles', 'wrapper' => 'bg-brand-500/10', 'iconColor' => 'text-brand-400', 'description' => 'Lavagem completa com atenção a cada detalhe do seu veículo.'],
    ['icon' => 'home', 'wrapper' => 'bg-brand-500/10', 'iconColor' => 'text-brand-400', 'description' => 'Limpeza completa do interior do veículo, deixando tudo impecável.'],
    ['icon' => 'sun', 'wrapper' => 'bg-brand-500/10', 'iconColor' => 'text-brand-400', 'description' => 'Lavagem externa detalhada com proteção e brilho para sua lataria.'],
    ['icon' => 'armchair', 'wrapper' => 'bg-brand-500/10', 'iconColor' => 'text-brand-400', 'description' => 'Remoção de manchas e bactérias, deixando os bancos como novos.'],
    ['icon' => 'square', 'wrapper' => 'bg-brand-500/10', 'iconColor' => 'text-brand-400', 'description' => 'Limpeza profunda do teto do veículo, removendo marcas e odores.'],
    ['icon' => 'wrench', 'wrapper' => 'bg-brand-500/10', 'iconColor' => 'text-brand-400', 'description' => 'Limpeza segura do compartimento do motor, preservando componentes.'],
    ['icon' => 'shield', 'wrapper' => 'bg-brand-500/10', 'iconColor' => 'text-brand-400', 'description' => 'Limpeza completa do chassi e subsolo, prevenindo corrosão.'],
    ['icon' => 'star', 'wrapper' => 'bg-amber-500/10', 'iconColor' => 'text-amber-400', 'description' => 'Proteção e brilho duradouros para a pintura do seu veículo.'],
    ['icon' => 'circle-dot', 'wrapper' => 'bg-purple-500/10', 'iconColor' => 'text-purple-400', 'description' => 'Remoção de riscos e restauração do brilho original da pintura.'],
    ['icon' => 'lightbulb', 'wrapper' => 'bg-yellow-500/10', 'iconColor' => 'text-yellow-400', 'description' => 'Restauração da transparência dos faróis para melhor visibilidade.'],
    ['icon' => 'diamond', 'wrapper' => 'bg-rose-500/10', 'iconColor' => 'text-rose-400', 'description' => 'Hidratação profunda para manter o couro macio e protegido.'],
    ['icon' => 'layers-3', 'wrapper' => 'bg-cyan-500/10', 'iconColor' => 'text-cyan-400', 'description' => 'Lavagem profunda do carpete automotivo para remover sujeira, odores e resíduos acumulados.'],
    ['icon' => 'eraser', 'wrapper' => 'bg-orange-500/10', 'iconColor' => 'text-orange-400', 'description' => 'Remoção cuidadosa de adesivos e resíduos de cola sem agredir a pintura do veículo.'],
];
$extraHead = <<<HTML
<meta name="robots" content="index, follow, max-image-preview:large">
<meta name="author" content="Pelicano Lava-Jato JF">
<meta name="geo.region" content="BR-MG">
<meta name="geo.placename" content="Guanhães">
<meta name="theme-color" content="#0c2340">
<link rel="canonical" href="{$canonicalUrl}">
<meta property="og:title" content="Lava Jato em Guanhães MG | Pelicano Lava-Jato">
<meta property="og:description" content="Lava jato em Guanhães MG com lavagem simples, lavagem detalhada, limpeza interna, higienização de bancos, polimento automotivo, enceramento e agendamento pelo WhatsApp.">
<meta property="og:type" content="website">
<meta property="og:url" content="{$canonicalUrl}">
<meta property="og:locale" content="pt_BR">
<meta property="og:site_name" content="Pelicano Lava-Jato">
{$ogMetaTags}<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Lava Jato em Guanhães MG | Pelicano Lava-Jato">
<meta name="twitter:description" content="Lava jato em Guanhães MG com lavagem simples, lavagem detalhada, limpeza interna, higienização de bancos, polimento automotivo, enceramento e agendamento pelo WhatsApp.">
<meta property="business:contact_data:street_address" content="{$seoStreetAddress}">
<meta property="business:contact_data:locality" content="{$seoCity}">
<meta property="business:contact_data:region" content="{$seoState}">
<meta property="business:contact_data:postal_code" content="{$seoPostalCode}">
<meta property="business:contact_data:country_name" content="Brasil">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: { sans: ["Inter", "system-ui", "sans-serif"] },
      colors: {
        brand: {
          50: "#f0f9ff", 100: "#e0f2fe", 200: "#bae6fd", 300: "#7dd3fc",
          400: "#38bdf8", 500: "#0ea5e9", 600: "#0284c7", 700: "#0369a1",
          800: "#075985", 900: "#0c2340", 950: "#081529"
        }
      }
    }
  }
}
</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link rel="stylesheet" href="{$modelCss}">
<script type="application/ld+json">
HTML
 . json_encode([
    '@context' => 'https://schema.org',
    '@type' => ['LocalBusiness', 'AutoWash'],
    'name' => $seoBusinessName,
    'alternateName' => $company['name'] ?? 'Pelicano Lava-Jato JF',
    'description' => $localBusinessDescription,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $seoStreetAddress,
        'addressLocality' => $seoCity,
        'addressRegion' => $seoState,
        'postalCode' => $seoPostalCode,
        'addressCountry' => 'BR',
    ],
    'telephone' => $phoneE164Digits !== '' ? '+' . $phoneE164Digits : null,
    'image' => $ogImage,
    'url' => $canonicalUrl,
    'hasMap' => !empty($company['google_maps_url']) ? (string) $company['google_maps_url'] : null,
    'areaServed' => [
        ['@type' => 'City', 'name' => 'Guanhães'],
        ['@type' => 'AdministrativeArea', 'name' => 'Guanhães e região'],
    ],
    'hasOfferCatalog' => [
        '@type' => 'OfferCatalog',
        'name' => 'Serviços automotivos em Guanhães',
        'itemListElement' => $serviceCatalog,
    ],
    'openingHours' => !empty($company['opening_hours']) ? [(string) $company['opening_hours']] : null,
    'priceRange' => '$$',
    'contactPoint' => [
        '@type' => 'ContactPoint',
        'contactType' => 'customer service',
        'telephone' => $phoneE164Digits !== '' ? '+' . $phoneE164Digits : null,
        'url' => $whatsappE164Digits !== '' ? 'https://wa.me/' . $whatsappE164Digits : null,
        'areaServed' => 'BR-MG',
        'availableLanguage' => ['pt-BR'],
    ],
    'sameAs' => array_values(array_filter([
        $company['google_maps_url'] ?? '',
    ])),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
. '</script>
<script type="application/ld+json">'
. json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(
        static fn(array $item): array => [
            '@type' => 'Question',
            'name' => $item['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['answer'],
            ],
        ],
        $faqItems
    ),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
$extraScripts = '
<script>
document.addEventListener("DOMContentLoaded", function () {
  if (window.lucide) window.lucide.createIcons();
  var year = document.getElementById("current-year");
  if (year) year.textContent = new Date().getFullYear();
});
</script>';
?>

<nav id="navbar" class="navbar fixed top-0 left-0 right-0 z-50 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <a href="#hero" class="flex items-center gap-2 group" aria-label="Pelicano Lava-Jato em Guanhães MG - voltar ao início">
                <img src="<?= $logoPath ?>" alt="Pelicano Lava-Jato em Guanhães MG" class="h-12 w-auto max-w-[120px] object-contain transition-transform duration-300 group-hover:scale-105">
                <div class="hidden sm:block">
                    <span class="text-lg font-bold text-white">Pelicano</span>
                    <span class="text-lg font-bold gradient-text"> Lava-Jato</span>
                </div>
            </a>

            <div class="hidden lg:flex items-center gap-8">
                <a href="#servicos" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Serviços</a>
                <a href="#sobre" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Sobre</a>
                <a href="#galeria" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Galeria</a>
                <a href="#localizacao" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Localização</a>
                <a href="#faq" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">FAQ</a>
                <a href="<?= h($whatsLink) ?>" target="_blank" rel="noreferrer" aria-label="Agendar lavagem automotiva pelo WhatsApp em Guanhães"
                    class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition-all hover:scale-105 shadow-lg shadow-green-500/25">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    WhatsApp
                </a>
            </div>

            <button id="menu-toggle" class="lg:hidden p-2 text-white hover:text-brand-400 transition-colors" type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>
    </div>
</nav>

<div id="menu-overlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden"></div>

<div id="mobile-menu" class="fixed top-0 right-0 bottom-0 w-72 bg-slate-900 z-50 transform translate-x-full transition-transform duration-300 lg:hidden" data-menu>
    <div class="p-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-2">
                <img src="<?= $logoPath ?>" alt="Pelicano Lava-Jato em Guanhães MG" class="h-10 w-auto max-w-[110px] object-contain">
                <span class="text-xl font-bold gradient-text">Pelicano</span>
            </div>
            <button id="menu-close" class="p-2 text-gray-400 hover:text-white" type="button">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div class="space-y-4">
            <a href="#servicos" class="block text-lg font-medium text-gray-300 hover:text-white py-2 transition-colors">Serviços</a>
            <a href="#sobre" class="block text-lg font-medium text-gray-300 hover:text-white py-2 transition-colors">Sobre</a>
            <a href="#galeria" class="block text-lg font-medium text-gray-300 hover:text-white py-2 transition-colors">Galeria</a>
            <a href="#localizacao" class="block text-lg font-medium text-gray-300 hover:text-white py-2 transition-colors">Localização</a>
            <a href="#faq" class="block text-lg font-medium text-gray-300 hover:text-white py-2 transition-colors">FAQ</a>
            <div class="pt-4 border-t border-slate-700">
                <a href="<?= h($whatsLink) ?>" target="_blank" rel="noreferrer" aria-label="Agendar pelo WhatsApp com o Pelicano Lava-Jato em Guanhães"
                    class="flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-full transition-all w-full">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<section id="hero" class="relative min-h-screen flex items-center overflow-hidden">
    <div class="absolute inset-0">
        <video class="w-full h-full object-cover" autoplay muted loop playsinline poster="<?= h($heroPoster) ?>">
            <source src="<?= h($heroVideo) ?>" type="video/mp4">
        </video>
        <div class="hero-overlay absolute inset-0"></div>
    </div>

    <div class="absolute top-20 right-10 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-brand-500/5 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 lg:py-0">
        <div class="max-w-2xl">
            <div class="animate-fade-in-up inline-flex items-center gap-2 bg-brand-500/10 border border-brand-500/20 rounded-full px-4 py-2 mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-sm font-medium text-brand-300">Estética Automotiva Completa</span>
            </div>

            <h1 class="animate-fade-in-up text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-tight mb-6" style="animation-delay: 0.1s">
                Pelicano<br>
                <span class="gradient-text">Lava-Jato JF</span>
                <span class="mt-3 block text-base font-semibold tracking-normal text-sky-200 sm:text-lg">Lava jato em Guanhães MG</span>
            </h1>

            <p class="animate-fade-in-up text-lg sm:text-xl text-gray-300 mb-4 leading-relaxed" style="animation-delay: 0.2s">
                💎 Seu carro limpo, protegido e com aparência de novo! Estética automotiva completa em <strong class="text-white">Guanhães - MG</strong>.
            </p>
            <p class="animate-fade-in-up text-sm text-sky-200/90 mb-8 max-w-2xl" style="animation-delay: 0.25s">
                <?= h($seoIntro) ?>
            </p>

            <div class="animate-fade-in-up flex flex-col sm:flex-row gap-4" style="animation-delay: 0.3s">
                <a href="<?= h($whatsLink) ?>" target="_blank" rel="noreferrer" aria-label="Agende lavagem automotiva pelo WhatsApp com o Pelicano Lava-Jato em Guanhães"
                    class="btn-shine inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-4 rounded-full text-lg transition-all hover:scale-105 shadow-xl shadow-green-500/30">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    Agende pelo WhatsApp
                </a>
                <a href="<?= h($company['google_maps_url'] ?? '#') ?>" target="_blank" rel="noreferrer" aria-label="Abrir rota para o Pelicano Lava-Jato em Guanhães MG"
                    class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold px-8 py-4 rounded-full text-lg transition-all hover:scale-105 backdrop-blur-sm">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                    Como Chegar
                </a>
            </div>

            <div class="animate-fade-in-up mt-12 grid grid-cols-3 gap-6 max-w-md" style="animation-delay: 0.4s">
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl font-bold gradient-text"><?= count($services) ?>+</div>
                    <div class="text-xs sm:text-sm text-gray-400 mt-1">Serviços</div>
                </div>
                <div class="text-center border-x border-white/10">
                    <div class="text-2xl sm:text-3xl font-bold gradient-text">100%</div>
                    <div class="text-xs sm:text-sm text-gray-400 mt-1">Qualidade</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl font-bold gradient-text">⚡</div>
                    <div class="text-xs sm:text-sm text-gray-400 mt-1">Agilidade</div>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <i data-lucide="chevron-down" class="w-6 h-6 text-gray-400"></i>
    </div>
</section>

<section id="servicos" class="relative py-20 lg:py-28 bg-slate-950">
    <div class="absolute top-0 left-0 right-0 section-divider"></div>
    <div class="absolute top-40 right-0 w-80 h-80 bg-brand-500/5 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 scroll-animate">
            <div class="inline-flex items-center gap-2 bg-brand-500/10 border border-brand-500/20 rounded-full px-4 py-2 mb-4">
                <i data-lucide="sparkles" class="w-4 h-4 text-brand-400"></i>
                <span class="text-sm font-medium text-brand-300">Nossos Serviços</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">
                Estética Automotiva <span class="gradient-text">Completa</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Oferecemos serviços de lava-jato e estética automotiva em Guanhães para deixar seu carro impecável, por dentro e por fora.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($services as $index => $service):
                $visual = $serviceVisuals[$index] ?? ['icon' => 'sparkles', 'wrapper' => 'bg-brand-500/10', 'iconColor' => 'text-brand-400', 'description' => $service['description']];
            ?>
                <div class="service-card bg-slate-900/80 border border-slate-800 rounded-2xl p-6 scroll-animate">
                    <div class="w-12 h-12 <?= h($visual['wrapper']) ?> rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="<?= h($visual['icon']) ?>" class="w-6 h-6 <?= h($visual['iconColor']) ?>"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2"><?= h($service['name']) ?></h3>
                    <p class="text-sm text-gray-400"><?= h($visual['description'] ?: $service['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="sobre" class="relative py-20 lg:py-28 bg-slate-900">
    <div class="absolute top-0 left-0 right-0 section-divider"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-brand-500/5 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div class="scroll-animate-left">
                <div class="relative">
                    <img src="<?= h($aboutImage) ?>"
                        alt="Lava jato em Guanhães MG - Pelicano Lava-Jato"
                        class="w-full rounded-2xl shadow-2xl border border-slate-700/50 object-cover min-h-[420px]" loading="lazy">
                    <div class="absolute -bottom-4 -right-4 bg-white/70 backdrop-blur-sm px-5 py-4 rounded-3xl shadow-xl hidden md:block">
                        <img src="<?= $logoPath ?>" alt="Pelicano Lava-Jato em Guanhães MG" class="h-16 w-auto max-w-[140px] object-contain">
                    </div>
                </div>
            </div>

            <div class="scroll-animate-right">
                <div class="inline-flex items-center gap-2 bg-brand-500/10 border border-brand-500/20 rounded-full px-4 py-2 mb-4">
                    <i data-lucide="award" class="w-4 h-4 text-brand-400"></i>
                    <span class="text-sm font-medium text-brand-300">Por que nos escolher</span>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
                    Qualidade que <span class="gradient-text">você vê</span>
                </h2>
                <p class="text-gray-400 text-lg mb-8">
                    <?= h($company['description'] ?: 'Estética automotiva completa em Guanhães. Seu carro limpo, protegido e com aparência de novo!') ?>
                </p>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white mb-1">Produtos de Qualidade</h4>
                            <p class="text-sm text-gray-400">Utilizamos produtos e acabamento focados em limpeza, proteção e apresentação final do veículo.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white mb-1">Equipe Especializada</h4>
                            <p class="text-sm text-gray-400">Cada serviço foi organizado para comunicar mais valor e mais confiança já na primeira visita ao site.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white mb-1">Atendimento Personalizado</h4>
                            <p class="text-sm text-gray-400">Agende pelo WhatsApp e receba atendimento direto, rápido e pensado para o uso no celular.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="galeria" class="relative py-20 lg:py-28 bg-slate-950 overflow-hidden">
    <div class="absolute top-0 left-0 right-0 section-divider"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 scroll-animate">
            <div class="inline-flex items-center gap-2 bg-brand-500/10 border border-brand-500/20 rounded-full px-4 py-2 mb-4">
                <i data-lucide="clapperboard" class="w-4 h-4 text-brand-400"></i>
                <span class="text-sm font-medium text-brand-300">Vídeos Reais</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">O lava-jato em <span class="gradient-text">movimento</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($galleryVideos as $video): ?>
                <video controls preload="metadata" class="rounded-2xl border border-slate-800 bg-slate-900 shadow-2xl scroll-animate" aria-label="Vídeo de serviços do Pelicano Lava-Jato JF em Guanhães">
                    <source src="<?= h($video) ?>" type="video/mp4">
                </video>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="relative py-20 lg:py-28 bg-slate-950 overflow-hidden">
    <div class="absolute top-0 left-0 right-0 section-divider"></div>
    <div class="absolute inset-0">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-brand-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-animate">
        <div class="text-6xl mb-6">🚗✨</div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
            Pronto para deixar seu carro <span class="gradient-text">impecável</span>?
        </h2>
        <p class="text-lg sm:text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
            📲 Agende seu horário agora pelo WhatsApp e tenha seu carro limpo, protegido e com aparência de novo!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= h($whatsLink) ?>" target="_blank" rel="noreferrer" aria-label="Agendar lavagem de carro em Guanhães pelo WhatsApp"
                    class="btn-shine inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-10 py-4 rounded-full text-lg transition-all hover:scale-105 shadow-xl shadow-green-500/30">
                <i data-lucide="message-circle" class="w-5 h-5"></i>
                Agendar pelo WhatsApp
            </a>
                <a href="<?= h($company['google_maps_url'] ?? '#') ?>" target="_blank" rel="noreferrer" aria-label="Abrir localização do Pelicano Lava-Jato no Google Maps"
                    class="inline-flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-bold px-10 py-4 rounded-full text-lg transition-all hover:scale-105 shadow-xl shadow-brand-500/30">
                <i data-lucide="navigation" class="w-5 h-5"></i>
                Visite Nosso Lava-Jato
            </a>
        </div>
    </div>
</section>

<section id="localizacao" class="relative py-20 lg:py-28 bg-slate-900">
    <div class="absolute top-0 left-0 right-0 section-divider"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 scroll-animate">
            <div class="inline-flex items-center gap-2 bg-brand-500/10 border border-brand-500/20 rounded-full px-4 py-2 mb-4">
                <i data-lucide="map-pin" class="w-4 h-4 text-brand-400"></i>
                <span class="text-sm font-medium text-brand-300">Localização</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">
                Venha nos <span class="gradient-text">visitar</span>
            </h2>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <div class="scroll-animate-left rounded-2xl overflow-hidden border border-slate-700/50 shadow-2xl">
                <?php $mapAddress = urlencode($company['address'] ?? 'Guanhães - MG'); ?>
                <iframe
                    src="https://maps.google.com/maps?q=<?= h($mapAddress) ?>&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" title="Mapa do Pelicano Lava-Jato em Guanhães MG"
                    class="w-full h-[300px] sm:h-[450px]">
                </iframe>
            </div>

            <div class="scroll-animate-right flex flex-col justify-center">
                <div class="space-y-6">
                    <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-brand-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i data-lucide="map-pin" class="w-6 h-6 text-brand-400"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white mb-1">Endereço</h4>
                                <p class="text-gray-400"><?= $companyAddress ?></p>
                                <a href="<?= h($company['google_maps_url'] ?? '#') ?>" target="_blank" rel="noreferrer" aria-label="Abrir rota para o Pelicano Lava-Jato em Guanhães MG no Google Maps"
                                    class="inline-flex items-center gap-1 text-brand-400 text-sm mt-2 hover:text-brand-300 transition-colors">
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                    Abrir no Google Maps
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i data-lucide="message-circle" class="w-6 h-6 text-green-400"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white mb-1">WhatsApp</h4>
                                <p class="text-gray-400"><?= h($company['whatsapp'] ?? '') ?></p>
                                <a href="<?= h($whatsLink) ?>" target="_blank" rel="noreferrer" aria-label="Enviar mensagem no WhatsApp para o Pelicano Lava-Jato"
                                    class="inline-flex items-center gap-1 text-green-400 text-sm mt-2 hover:text-green-300 transition-colors">
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                    Enviar mensagem
                                </a>
                                <?php if ($phoneLink !== ''): ?>
                                    <a href="<?= h($phoneLink) ?>" aria-label="Ligar para o Pelicano Lava-Jato em Guanhães"
                                        class="inline-flex items-center gap-1 text-sky-400 text-sm mt-2 hover:text-sky-300 transition-colors">
                                        <i data-lucide="phone" class="w-3 h-3"></i>
                                        Ligar agora
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i data-lucide="clock" class="w-6 h-6 text-amber-400"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white mb-1">Horário de Funcionamento</h4>
                                <p class="text-gray-400"><?= h($company['opening_hours'] ?? 'Atendimento sob agendamento') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="relative py-20 lg:py-24 bg-slate-950">
    <div class="absolute top-0 left-0 right-0 section-divider"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <details class="faq-shell scroll-animate rounded-3xl border border-slate-800 bg-slate-900/55 p-6 sm:p-8">
            <summary class="faq-shell-summary flex cursor-pointer list-none items-center justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 bg-brand-500/10 border border-brand-500/20 rounded-full px-4 py-2 mb-4">
                        <i data-lucide="help-circle" class="w-4 h-4 text-brand-400"></i>
                        <span class="text-sm font-medium text-brand-300">FAQ</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold mb-3">Dúvidas sobre o <span class="gradient-text">lava jato em Guanhães</span></h2>
                    <p class="text-gray-400 text-lg max-w-3xl">
                        Respostas rápidas para quem procura lavagem de carro, estética automotiva e agendamento pelo WhatsApp em Guanhães - MG.
                    </p>
                </div>
                <div class="faq-shell-trigger">
                    <span>Ver perguntas</span>
                    <i data-lucide="chevron-down" class="faq-shell-icon h-5 w-5 flex-shrink-0 text-brand-400"></i>
                </div>
            </summary>

            <div class="mt-8 space-y-4 faq-shell-content">
                <?php foreach ($faqItems as $faq): ?>
                    <details class="faq-item rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left font-semibold text-white">
                            <span><?= h($faq['question']) ?></span>
                            <i data-lucide="chevron-down" class="faq-icon h-5 w-5 flex-shrink-0 text-brand-400"></i>
                        </summary>
                        <p class="mt-4 text-sm leading-7 text-gray-400"><?= h($faq['answer']) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </details>
    </div>
</section>

<footer class="bg-slate-950 border-t border-slate-800/50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <img src="<?= $logoPath ?>" alt="Pelicano Lava-Jato em Guanhães MG" class="h-12 w-auto max-w-[120px] object-contain">
                    <div>
                        <span class="text-lg font-bold text-white">Pelicano </span>
                        <span class="text-lg font-bold text-sky-500">Lava-</span>
                        <span class="text-lg font-bold text-sky-300">Jato</span>
                    </div>
                </div>
                <p class="text-sm text-gray-500"><?= h($company['description'] ?: 'Estética automotiva completa em Guanhães. Seu carro limpo, protegido e com aparência de novo!') ?></p>
                <p class="mt-3 text-sm text-sky-300/90">Pelicano Lava-Jato: lavagem automotiva, estética automotiva e polimento em Guanhães - MG.</p>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Links Rápidos</h4>
                <div class="space-y-2">
                    <a href="#hero" class="block text-sm text-gray-400 hover:text-white transition-colors">Início</a>
                    <a href="#servicos" class="block text-sm text-gray-400 hover:text-white transition-colors">Serviços</a>
                    <a href="#sobre" class="block text-sm text-gray-400 hover:text-white transition-colors">Sobre</a>
                    <a href="#localizacao" class="block text-sm text-gray-400 hover:text-white transition-colors">Localização</a>
                    <a href="#faq" class="block text-sm text-gray-400 hover:text-white transition-colors">FAQ</a>
                </div>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Serviços</h4>
                <div class="space-y-2">
                    <span class="block text-sm text-gray-400">Lavagem Detalhada</span>
                    <span class="block text-sm text-gray-400">Higienização de Bancos</span>
                    <span class="block text-sm text-gray-400">Polimento Automotivo</span>
                    <span class="block text-sm text-gray-400">Enceramento Técnico</span>
                </div>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Contato</h4>
                <div class="space-y-2">
                    <a href="<?= h($company['google_maps_url'] ?? '#') ?>" target="_blank" rel="noreferrer" aria-label="Abrir endereço do Pelicano Lava-Jato em Guanhães no Google Maps" class="block text-sm text-gray-400 hover:text-white transition-colors">
                        <?= $companyAddress ?>
                    </a>
                    <?php if ($phoneLink !== ''): ?>
                        <a href="<?= h($phoneLink) ?>" class="block text-sm text-sky-400 hover:text-sky-300 transition-colors" aria-label="Ligar para o Pelicano Lava-Jato">
                            <?= h($company['phone'] ?? $company['whatsapp'] ?? '') ?>
                        </a>
                    <?php endif; ?>
                    <a href="<?= h($whatsLink) ?>" class="block text-sm text-green-400 hover:text-green-300 transition-colors" target="_blank" rel="noreferrer" aria-label="Falar com o Pelicano Lava-Jato pelo WhatsApp">
                        <?= h($company['whatsapp'] ?? '') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800/50 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-center sm:text-left">
                <p class="text-sm text-gray-500">
                    &copy; <span id="current-year"></span> Pelicano Lava-Jato JF. Todos os direitos reservados.
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    Desenvolvido por
                    <a href="https://www.linkedin.com/in/jefferson-miranda-dfs/" target="_blank" rel="noreferrer" class="text-sky-400 hover:text-sky-300 transition-colors font-semibold">Jefferson Miranda</a>
                    |
                    <a href="https://wa.me/5533987494050" target="_blank" rel="noreferrer" class="text-green-400 hover:text-green-300 transition-colors font-semibold">+55 33 9 8749-4050</a>
                </p>
            </div>
            <a href="/admin/login" class="text-xs text-gray-600 hover:text-gray-400 transition-colors">Acesso Admin</a>
        </div>
    </div>
</footer>

<a id="whatsapp-float" href="<?= h($whatsLink) ?>" target="_blank" rel="noreferrer" class="whatsapp-float no-print" aria-label="Agendar lava jato em Guanhães pelo WhatsApp">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="white">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
    </svg>
</a>
