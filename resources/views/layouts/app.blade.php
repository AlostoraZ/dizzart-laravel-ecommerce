<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dizzart | Egyptian Perfume House')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --dizzart-gold: #b8912f;
            --dizzart-dark: #1a1a1a;
            --dizzart-cream: #f8f5ef;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--dizzart-cream);
            color: var(--dizzart-dark);
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--dizzart-gold) !important;
        }
        .navbar {
            background-color: var(--dizzart-dark) !important;
        }
        .btn-dizzart {
            background-color: var(--dizzart-gold);
            border-color: var(--dizzart-gold);
            color: #fff;
        }
        .btn-dizzart:hover {
            background-color: #9a7a26;
            border-color: #9a7a26;
            color: #fff;
        }
        .text-dizzart-gold {
            color: var(--dizzart-gold);
        }
        .whatsapp-float {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background-color: #25D366;
            color: #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.25);
            z-index: 1050;
            text-decoration: none;
        }
        .whatsapp-float:hover {
            color: #fff;
            transform: scale(1.05);
        }
        .card-product {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            transition: transform 0.2s ease;
        }
        .card-product:hover {
            transform: translateY(-4px);
        }
        footer {
            background-color: var(--dizzart-dark);
            color: #ccc;
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('shop.index') }}">DIZZART</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <form class="d-flex mx-auto my-2 my-lg-0" style="max-width: 420px; width: 100%;" method="GET" action="{{ route('shop.index') }}">
                <input class="form-control me-2" type="search" name="search" placeholder="Search perfumes or notes..." value="{{ request('search') }}">
                <select name="sort" class="form-select me-2" style="max-width: 160px;" onchange="this.form.submit()">
                    <option value="">Sort by</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                </select>
                <button class="btn btn-dizzart" type="submit">Go</button>
            </form>

            <ul class="navbar-nav ms-lg-3 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link text-white position-relative" href="{{ route('cart.index') }}">
                        Cart
                        @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                        @if($cartCount > 0)
                            <span class="badge rounded-pill bg-warning text-dark ms-1">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('orders.mine') }}">My Orders</a>
                    </li>
                    @if(auth()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Admin</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm ms-lg-2" type="submit">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-dizzart btn-sm ms-lg-2" href="{{ route('register') }}">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<footer class="py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1 text-dizzart-gold" style="font-family: 'Playfair Display', serif; font-size: 1.2rem;">DIZZART</p>
        <p class="mb-0 small">&copy; {{ date('Y') }} Dizzart Perfumes, Cairo, Egypt. All prices in EGP.</p>
    </div>
</footer>

<a href="https://wa.me/Zzz_007" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16">
        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.51.646-.626.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.336-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
    </svg>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
