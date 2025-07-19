{{-- filepath: resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100 text-gray-800">
         @include('components.header')

    <main class="container mx-auto py-8 px-6">
        {{-- Products Pricing Section --}}
        <section class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Plans & Pricing</h2>
            <p class="text-gray-600 mb-6">Compare & explore our plans below. Find the perfect fit for your needs.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Basic Plan --}}
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-2">Basic</h3>
                    <p class="text-gray-600 mb-4">Perfect for personal use.</p>
                    <p class="text-2xl font-bold mb-4">$0/month</p>
                    <button class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Get Started</button>
                </div>
                {{-- Pro Plan --}}
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-2">Pro</h3>
                    <p class="text-gray-600 mb-4">Ideal for professionals.</p>
                    <p class="text-2xl font-bold mb-4">$9/month or $99/year</p>
                    <button class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Get Started</button>
                </div>
                {{-- Ultimate Plan --}}
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-2">Ultimate</h3>
                    <p class="text-gray-600 mb-4">Designed for organizations.</p>
                    <p class="text-2xl font-bold mb-4">$900/year/account</p>
                    <button class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Get Started</button>
                </div>
            </div>
        </section>

        {{-- About Section --}}
        <section class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">About Us</h2>
            <p class="text-gray-600">Learn more about our mission and values.</p>
            <a href="{{ url('/about') }}" class="text-blue-500 hover:underline">Read More</a>
        </section>

        {{-- Contact Us Section --}}
        <section class="text-center">
            <h2 class="text-3xl font-bold mb-4">Contact Us</h2>
            <p class="text-gray-600">Have questions? We're here to help.</p>
            <a href="{{ url('/contact') }}" class="text-blue-500 hover:underline">Get in Touch</a>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>