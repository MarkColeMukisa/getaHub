<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Geta helps landlords manage tenants, calculate water bills automatically, and notify tenants by SMS and email — no spreadsheets required." />
  <title>Geta — Rental &amp; Water Bill Management for Landlords</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    [x-cloak] { display: none !important; }

    .reveal {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity 0.7s ease-out, transform 0.7s ease-out;
    }

    .reveal.is-visible {
      opacity: 1;
      transform: translateY(0);
    }

    .hero-glow {
      background: radial-gradient(circle at 30% 20%, color-mix(in srgb, var(--color-primary) 18%, transparent), transparent 60%),
                  radial-gradient(circle at 80% 0%, color-mix(in srgb, var(--color-accent) 18%, transparent), transparent 55%);
    }
  </style>
</head>

<body class="font-sans text-gray-800 antialiased" x-data="{ mobileMenu: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 12">

  <!-- Navigation -->
  <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-300" :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm' : 'bg-transparent'">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <a href="{{ route('welcome') }}" class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
          </div>
          <span class="text-xl font-extrabold tracking-tight text-secondary">Geta</span>
        </a>

        <div class="hidden lg:flex items-center gap-8 text-sm font-medium text-gray-600">
          <a href="#features" class="hover:text-secondary transition-colors">Features</a>
          <a href="#how-it-works" class="hover:text-secondary transition-colors">How it works</a>
          <a href="#pricing" class="hover:text-secondary transition-colors">Pricing</a>
          <a href="#faq" class="hover:text-secondary transition-colors">FAQ</a>
        </div>

        <div class="hidden lg:flex items-center gap-3">
          @auth
          <a href="{{ route('dashboard') }}" class="bg-primary text-white px-5 py-2.5 rounded-full font-semibold shadow-md hover:bg-primary/90 transition">
            Go to Dashboard
          </a>
          @else
          <a href="{{ route('login') }}" class="text-gray-700 px-4 py-2.5 font-semibold hover:text-secondary transition">
            Log in
          </a>
          @if (Route::has('register'))
          <a href="{{ route('register') }}" class="bg-primary text-white px-5 py-2.5 rounded-full font-semibold shadow-md hover:bg-primary/90 transition">
            Get Started Free
          </a>
          @endif
          @endauth
        </div>

        <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-secondary">
          <svg x-show="!mobileMenu" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg x-show="mobileMenu" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Mobile menu -->
      <div x-show="mobileMenu" x-cloak x-transition class="lg:hidden pb-6 border-t border-gray-100 bg-white/95 backdrop-blur-md -mx-4 px-4">
        <div class="flex flex-col gap-1 pt-4 text-sm font-medium text-gray-700">
          <a href="#features" @click="mobileMenu = false" class="py-2.5">Features</a>
          <a href="#how-it-works" @click="mobileMenu = false" class="py-2.5">How it works</a>
          <a href="#pricing" @click="mobileMenu = false" class="py-2.5">Pricing</a>
          <a href="#faq" @click="mobileMenu = false" class="py-2.5">FAQ</a>
          <div class="flex flex-col gap-2 pt-4">
            @auth
            <a href="{{ route('dashboard') }}" class="bg-primary text-white px-5 py-3 rounded-full font-semibold text-center">Go to Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="border border-gray-300 text-gray-800 px-5 py-3 rounded-full font-semibold text-center">Log in</a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="bg-primary text-white px-5 py-3 rounded-full font-semibold text-center">Get Started Free</a>
            @endif
            @endauth
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <header class="relative overflow-hidden pt-40 pb-24 hero-glow">
    <div class="absolute inset-0 bg-hero-pattern opacity-40 pointer-events-none"></div>
    <div class="absolute top-24 right-[8%] w-3 h-3 rounded-full bg-primary/40 animate-float"></div>
    <div class="absolute top-64 left-[12%] w-2 h-2 rounded-full bg-accent/50 animate-float" style="animation-delay: 1.5s"></div>
    <div class="absolute bottom-20 right-[20%] w-4 h-4 rounded-full bg-secondary/20 animate-float" style="animation-delay: 0.8s"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <div>
          <div class="inline-flex items-center gap-2 bg-primary/10 text-primary rounded-full px-4 py-1.5 text-sm font-semibold mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
            Built for landlords, not accountants
          </div>
          <h1 class="text-4xl md:text-6xl font-extrabold text-secondary leading-[1.1] mb-6">
            Stop managing tenants
            <span class="relative inline-block">
              <span class="bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">in spreadsheets</span>
              <span class="absolute -bottom-1 left-0 w-full h-3 bg-gradient-to-r from-primary/20 to-accent/20 rounded-full blur-sm"></span>
            </span>
          </h1>
          <p class="text-lg text-gray-600 leading-relaxed mb-8 max-w-xl">
            Geta keeps every tenant, meter reading, and water bill in one place — then calculates VAT,
            PAYE, and rubbish fees for you and notifies tenants automatically by SMS or email.
          </p>
          <div class="flex flex-col sm:flex-row gap-4">
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="bg-primary hover:bg-primary/90 text-white px-8 py-4 rounded-xl font-semibold shadow-lg shadow-primary/25 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
              Get Started Free
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
            @endif
            <a href="#how-it-works" class="bg-white border border-gray-200 text-secondary px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
              See how it works
            </a>
          </div>
          <p class="text-sm text-gray-500 mt-5 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Free to start · No credit card required
          </p>
        </div>

        <!-- Hero visual: stylised dashboard preview -->
        <div class="relative reveal" data-reveal>
          <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-6 relative">
            <div class="flex items-center justify-between mb-6">
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dashboard</p>
                <p class="text-lg font-bold text-secondary">This month at a glance</p>
              </div>
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse-slow"></span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
              <div class="bg-indigo-50 rounded-2xl p-4">
                <p class="text-xs font-medium text-indigo-500 uppercase tracking-wide">Tenants</p>
                <p class="text-2xl font-extrabold text-secondary mt-1">48</p>
              </div>
              <div class="bg-emerald-50 rounded-2xl p-4">
                <p class="text-xs font-medium text-emerald-600 uppercase tracking-wide">Bills this month</p>
                <p class="text-2xl font-extrabold text-secondary mt-1">46</p>
              </div>
              <div class="bg-sky-50 rounded-2xl p-4">
                <p class="text-xs font-medium text-sky-600 uppercase tracking-wide">Notified</p>
                <p class="text-2xl font-extrabold text-secondary mt-1">44</p>
              </div>
              <div class="bg-primary/10 rounded-2xl p-4">
                <p class="text-xs font-medium text-primary uppercase tracking-wide">Grand total</p>
                <p class="text-2xl font-extrabold text-secondary mt-1">UGX 4.1M</p>
              </div>
            </div>

            <div class="bg-secondary rounded-2xl p-4 text-white flex items-center justify-between">
              <div>
                <p class="text-xs text-white/60 uppercase tracking-wide">Room B2 · Jane A.</p>
                <p class="font-semibold">Bill notified via SMS</p>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.8L3 20l1.3-3.9A7.966 7.966 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
            </div>
          </div>

          <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 hidden sm:flex items-center gap-3 animate-float">
            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-xs text-gray-400">VAT, PAYE &amp; rubbish</p>
              <p class="text-sm font-bold text-secondary">Calculated automatically</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Feature grid -->
  <section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-16 reveal" data-reveal>
        <span class="inline-flex items-center bg-primary/10 text-primary rounded-full px-4 py-1.5 text-sm font-semibold">Everything you need</span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-secondary mt-3 mb-4">One place for tenants, bills, and notifications</h2>
        <p class="text-gray-600 text-lg">No more juggling notebooks, WhatsApp reminders, and manual VAT math.</p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
        $features = [
          [
            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            'title' => 'Tenant management',
            'body' => 'Add, search, and organise every tenant by name, contact, or room number with instant autocomplete.',
            'bg' => 'bg-indigo-50',
            'text' => 'text-indigo-500',
          ],
          [
            'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
            'title' => 'Automated bill calculator',
            'body' => 'Enter a meter reading and Geta works out units used, VAT, PAYE, and rubbish fees for you — every time.',
            'bg' => 'bg-primary/10',
            'text' => 'text-primary',
          ],
          [
            'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.8L3 20l1.3-3.9A7.966 7.966 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            'title' => 'SMS &amp; email notifications',
            'body' => 'Tenants get notified the moment a bill is ready, with delivery tracked right on your dashboard.',
            'bg' => 'bg-sky-50',
            'text' => 'text-sky-500',
          ],
          [
            'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            'title' => 'Live dashboard &amp; analytics',
            'body' => 'See tenant counts, bills generated, and notification success rate at a glance, refreshed automatically.',
            'bg' => 'bg-emerald-50',
            'text' => 'text-emerald-500',
          ],
          [
            'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
            'title' => 'Role-based access',
            'body' => 'Promote trusted staff to admin and keep sensitive tenant data locked down for everyone else.',
            'bg' => 'bg-rose-50',
            'text' => 'text-rose-500',
          ],
          [
            'icon' => 'M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z',
            'title' => 'CSV export',
            'body' => 'Export a filtered, sorted tenant list in one click whenever you need records outside Geta.',
            'bg' => 'bg-amber-50',
            'text' => 'text-amber-500',
          ],
        ];
        @endphp

        @foreach($features as $feature)
        <div class="group bg-white border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 reveal" data-reveal>
          <div class="w-14 h-14 rounded-2xl {{ $feature['bg'] }} flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 {{ $feature['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-secondary mb-2">{!! $feature['title'] !!}</h3>
          <p class="text-gray-600 leading-relaxed">{!! $feature['body'] !!}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- How it works -->
  <section id="how-it-works" class="py-24 bg-light">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-16 reveal" data-reveal>
        <span class="inline-flex items-center bg-primary/10 text-primary rounded-full px-4 py-1.5 text-sm font-semibold">How it works</span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-secondary mt-3 mb-4">Four steps from meter reading to paid bill</h2>
      </div>

      <div class="grid md:grid-cols-4 gap-8 relative">
        <div class="hidden md:block absolute top-8 left-[12.5%] right-[12.5%] h-0.5 bg-gradient-to-r from-primary/30 via-accent/30 to-primary/30"></div>

        @php
        $steps = [
          ['n' => '1', 'title' => 'Add your tenants', 'body' => 'Create a tenant profile with room number and contact details in seconds.'],
          ['n' => '2', 'title' => 'Log a meter reading', 'body' => 'Enter the current reading — the previous one is filled in for you automatically.'],
          ['n' => '3', 'title' => 'Bill is calculated', 'body' => 'Units, VAT, PAYE, and rubbish fees are computed instantly into a grand total.'],
          ['n' => '4', 'title' => 'Tenant is notified', 'body' => 'An SMS or email goes out immediately, with delivery status tracked for you.'],
        ];
        @endphp

        @foreach($steps as $step)
        <div class="relative text-center reveal" data-reveal>
          <div class="w-16 h-16 rounded-2xl bg-white shadow-lg border border-gray-100 flex items-center justify-center text-xl font-extrabold text-primary mb-5 relative z-10 mx-auto">
            {{ $step['n'] }}
          </div>
          <h3 class="text-lg font-bold text-secondary mb-2">{{ $step['title'] }}</h3>
          <p class="text-gray-600 leading-relaxed">{{ $step['body'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Social proof -->
  <section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="grid sm:grid-cols-3 gap-8 text-center mb-20 reveal" data-reveal>
        <div>
          <p class="text-4xl font-extrabold text-secondary">100%</p>
          <p class="text-gray-500 mt-1">Automated bill calculation</p>
        </div>
        <div>
          <p class="text-4xl font-extrabold text-secondary">0</p>
          <p class="text-gray-500 mt-1">Spreadsheets required</p>
        </div>
        <div>
          <p class="text-4xl font-extrabold text-secondary">24/7</p>
          <p class="text-gray-500 mt-1">Dashboard access</p>
        </div>
      </div>

      <div class="text-center max-w-2xl mx-auto mb-12 reveal" data-reveal>
        <span class="inline-flex items-center bg-primary/10 text-primary rounded-full px-4 py-1.5 text-sm font-semibold mb-4">Social proof</span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-secondary mb-4">What landlords are saying</h2>
        <p class="text-gray-500 text-sm">(Early feedback — updated as more landlords come on board)</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        @php
        $testimonials = [
          ['quote' => 'I used to spend an entire evening every month working out VAT and rubbish fees by hand. Now it takes minutes.', 'name' => 'Property Owner', 'context' => 'Kampala, 12 units'],
          ['quote' => 'Tenants get their bill by SMS the same day I take the reading. No more chasing people down.', 'name' => 'Landlord', 'context' => '8-unit rental'],
          ['quote' => 'Having one dashboard for tenants and billing instead of three notebooks is a huge relief.', 'name' => 'Property Manager', 'context' => 'Multi-property portfolio'],
        ];
        @endphp

        @foreach($testimonials as $t)
        <div class="bg-light rounded-2xl p-8 border border-gray-100 reveal" data-reveal>
          <div class="flex gap-1 mb-4 text-primary">
            @for ($i = 0; $i < 5; $i++)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 00-1.176 0l-3.367 2.446c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.063 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.958z" /></svg>
            @endfor
          </div>
          <p class="text-gray-700 leading-relaxed mb-6">&ldquo;{{ $t['quote'] }}&rdquo;</p>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-bold text-sm">
              {{ strtoupper(substr($t['name'], 0, 1)) }}
            </div>
            <div>
              <p class="font-semibold text-secondary text-sm">{{ $t['name'] }}</p>
              <p class="text-gray-500 text-xs">{{ $t['context'] }}</p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Pricing -->
  <section id="pricing" class="py-24 bg-light">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-16 reveal" data-reveal>
        <span class="inline-flex items-center bg-primary/10 text-primary rounded-full px-4 py-1.5 text-sm font-semibold">Pricing</span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-secondary mt-3 mb-4">Start free. Upgrade only if you need to.</h2>
        <p class="text-gray-600 text-lg">Geta is free to get started — no credit card, no trial countdown.</p>
      </div>

      <div class="max-w-md mx-auto bg-white rounded-3xl shadow-2xl border border-gray-100 p-10 relative overflow-hidden reveal" data-reveal>
        <div class="absolute -top-8 -right-8 w-32 h-32 bg-primary/10 rounded-full"></div>
        <div class="relative">
          <span class="inline-block bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-4">Free plan</span>
          <div class="flex items-baseline gap-1 mb-6">
            <span class="text-5xl font-extrabold text-secondary">UGX 0</span>
            <span class="text-gray-500">/ forever</span>
          </div>
          <ul class="space-y-3 mb-8">
            @foreach(['Unlimited tenants', 'Automated bill calculations', 'SMS & email notifications', 'Live dashboard & analytics', 'CSV export'] as $item)
            <li class="flex items-center gap-3 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ $item }}
            </li>
            @endforeach
          </ul>
          @if (Route::has('register'))
          <a href="{{ route('register') }}" class="block text-center bg-primary hover:bg-primary/90 text-white px-6 py-4 rounded-xl font-semibold shadow-lg shadow-primary/25 transition-all hover:-translate-y-0.5">
            Get Started Free
          </a>
          @endif
          <p class="text-center text-xs text-gray-400 mt-4">Paid plans for larger portfolios are on the way.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section id="faq" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-6 lg:px-8">
      <div class="text-center mb-16 reveal" data-reveal>
        <span class="inline-flex items-center bg-primary/10 text-primary rounded-full px-4 py-1.5 text-sm font-semibold">FAQ</span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-secondary mt-3">Common questions</h2>
      </div>

      <div x-data="{ open: 0 }" class="space-y-3 reveal" data-reveal>
        @php
        $faqs = [
          ['q' => 'Is Geta really free?', 'a' => 'Yes. The free plan covers unlimited tenants, automated bill calculations, notifications, and the dashboard, with no credit card required to sign up.'],
          ['q' => 'How does the bill calculation work?', 'a' => 'You enter a current meter reading, Geta pulls the previous reading automatically, works out units used, and adds VAT, PAYE, and a rubbish collection fee to produce a grand total.'],
          ['q' => 'How do tenants get notified?', 'a' => 'As soon as a bill is generated, Geta can send an SMS and/or email to the tenant, and you can see delivery status right on your dashboard.'],
          ['q' => 'Can I manage more than one property?', 'a' => 'Yes — tenants are organised by room number, so you can track multiple units or buildings from the same account.'],
          ['q' => 'Is my tenant data secure?', 'a' => 'Only accounts you promote to admin can manage tenant records, billing, and exports — everyone else has read-only access to the shared dashboard.'],
        ];
        @endphp

        @foreach($faqs as $i => $faq)
        <div class="border border-gray-100 rounded-2xl overflow-hidden">
          <button type="button" @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left bg-light/60 hover:bg-light transition-colors">
            <span class="font-semibold text-secondary">{{ $faq['q'] }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary shrink-0 transition-transform duration-300" :class="open === {{ $i }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="open === {{ $i }}" x-cloak x-transition class="px-6 py-5 text-gray-600 leading-relaxed">
            {{ $faq['a'] }}
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Final CTA -->
  <section class="py-20 bg-secondary relative overflow-hidden">
    <div class="absolute inset-0 bg-hero-pattern opacity-5"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center reveal" data-reveal>
      <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Ready to simplify how you manage tenants?</h2>
      <p class="text-white/70 text-lg mb-8 max-w-xl mx-auto">Join Geta today and let the bills calculate themselves.</p>
      @if (Route::has('register'))
      <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-8 py-4 rounded-xl font-semibold shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5">
        Get Started Free
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
        </svg>
      </a>
      @endif
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white/70 py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-primary to-accent flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
          </div>
          <span class="text-white font-bold">Geta</span>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-2 text-sm">
          <a href="#features" class="hover:text-white transition-colors">Features</a>
          <a href="#how-it-works" class="hover:text-white transition-colors">How it works</a>
          <a href="#pricing" class="hover:text-white transition-colors">Pricing</a>
          <a href="#faq" class="hover:text-white transition-colors">FAQ</a>
          @if (Route::has('login'))
          <a href="{{ route('login') }}" class="hover:text-white transition-colors">Log in</a>
          @endif
        </div>

        <p class="text-sm text-white/50">© {{ date('Y') }} Geta. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const revealEls = document.querySelectorAll('[data-reveal]');
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.15 });

      revealEls.forEach((el) => observer.observe(el));
    });
  </script>
</body>

</html>
