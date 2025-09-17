<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{title} - {site.name}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8F9FA;
        }
        .sidebar {
            background-color: #212529;
            color: #E9ECEF;
        }
        .sidebar a {
            color: #ADB5BD;
        }
        .sidebar a:hover, .sidebar a.active {
            color: #FFFFFF;
            background-color: #343A40;
        }
        .card {
            background-color: #FFFFFF;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
    </style>
</head>
<body class="flex h-screen">
<aside class="w-64 sidebar flex flex-col p-4">
<div class="text-xl font-bold mb-8">
<span class="text-blue-400">Your</span>Brand
        </div>
<nav class="flex-grow">
<h2 class="text-xs uppercase text-gray-500 mb-2">Menu</h2>
<ul>
<li class="mb-2">
<a class="flex items-center p-2 rounded-lg" href="#">
<span class="material-icons">home</span>
<span class="ml-3">Beranda</span>
</a>
</li>
<li class="mb-2">
<a class="flex items-center justify-between p-2 rounded-lg" href="#">
<div class="flex items-center">
<span class="material-icons">layers</span>
<span class="ml-3">Layanan</span>
</div>
<span class="material-icons">expand_more</span>
</a>
</li>
<li class="mb-2">
<a class="flex items-center justify-between p-2 rounded-lg" href="#">
<div class="flex items-center">
<span class="material-icons">dns</span>
<span class="ml-3">Domain</span>
</div>
<span class="material-icons">expand_more</span>
</a>
</li>
<li class="mb-2">
<a class="flex items-center justify-between p-2 rounded-lg active" href="#">
<div class="flex items-center">
<span class="material-icons">receipt</span>
<span class="ml-3">Finance/Billing</span>
</div>
<span class="material-icons">expand_less</span>
</a>
<ul class="ml-6 mt-1 space-y-1">
<li><a class="block p-2 rounded-lg text-white font-semibold" href="#">Tagihan Saya</a></li>
<li><a class="block p-2 rounded-lg" href="#">Permintaan Tagihan Manual</a></li>
<li><a class="block p-2 rounded-lg" href="#">Permintaan Faktur Pajak</a></li>
<li><a class="block p-2 rounded-lg" href="#">Tambah Deposit</a></li>
<li><a class="block p-2 rounded-lg" href="#">Pengembalian Dana</a></li>
</ul>
</li>
<li class="mb-2">
<a class="flex items-center justify-between p-2 rounded-lg" href="#">
<div class="flex items-center">
<span class="material-icons">support_agent</span>
<span class="ml-3">Support</span>
</div>
<span class="material-icons">expand_more</span>
</a>
</li>
<li class="mb-2">
<a class="flex items-center p-2 rounded-lg" href="#">
<span class="material-icons">group</span>
<span class="ml-3">Affiliate</span>
</a>
</li>
</ul>
</nav>
<div class="mt-auto">
<div class="flex items-center justify-between p-2 bg-gray-700 rounded-lg">
<span class="material-icons">dark_mode</span>
<span class="ml-2">Dark Mode</span>
<label class="relative inline-flex items-center cursor-pointer ml-auto" for="dark-mode-toggle">
<input class="sr-only peer" id="dark-mode-toggle" type="checkbox"/>
<div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
</label>
</div>
</div>
</aside>
<div class="flex-1 flex flex-col">
<header class="bg-white shadow-sm p-4 flex justify-between items-center">
<div class="flex items-center">
<h1 class="text-2xl font-bold">My Invoices</h1>
</div>
<div class="flex items-center space-x-4">
<button class="text-gray-500">
<span class="material-icons">shopping_cart</span>
</button>
<button class="text-gray-500">
<span class="material-icons">notifications</span>
</button>
<div class="flex items-center space-x-2">
<img alt="User avatar" class="w-8 h-8 rounded-full" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBVrbjpv587YaaPALngIkKrLnVUIuXH6gRqImu6JvJEay6XfWNygdF99GQOur9y88MP7HPRjv18f_oNONG02m9SBzXgM0tzpAhDrDUfp2RmbaYQnznbbEYf-D7XkNoZZXfAfcHB-pB-OS50_5eTItMvzRtdJu8PW_WZjZ4dIUWZrucX5KpokP71l7KBgl2VgMmUOMC_3-_wNfM0vKiKsyKUuH8Fc3nAxscEuNM_WQJ6GxBxYwRBB7g588QDzJgK1-IZtRlNMm8pPYNK"/>
<span class="text-gray-700">John Doe</span>
<span class="material-icons text-gray-500">expand_more</span>
</div>
</div>
</header>
<main class="flex-1 p-6">
<div class="text-sm text-gray-500 mb-6">
                DASHBOARD / MY INVOICES
            </div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
<div class="card p-6 flex justify-between items-center">
<div>
<p class="text-sm text-gray-500">PAID</p>
<p class="text-3xl font-bold">0</p>
</div>
<div class="p-3 bg-green-100 rounded-full">
<span class="material-icons text-green-500">check</span>
</div>
</div>
<div class="card p-6 flex justify-between items-center">
<div>
<p class="text-sm text-gray-500">UNPAID</p>
<p class="text-3xl font-bold">0</p>
</div>
<div class="p-3 bg-red-100 rounded-full">
<span class="material-icons text-red-500">error_outline</span>
</div>
</div>
<div class="card p-6 flex justify-between items-center">
<div>
<p class="text-sm text-gray-500">CANCELED</p>
<p class="text-3xl font-bold">0</p>
</div>
<div class="p-3 bg-blue-100 rounded-full">
<span class="material-icons text-blue-500">cancel</span>
</div>
</div>
<div class="card p-6 flex justify-between items-center">
<div>
<p class="text-sm text-gray-500">REFUNDED</p>
<p class="text-3xl font-bold">0</p>
</div>
<div class="p-3 bg-yellow-100 rounded-full">
<span class="material-icons text-yellow-500">inventory_2</span>
</div>
</div>
</div>
<div class="card p-6">
<h2 class="text-lg font-semibold mb-4">My Invoice List</h2>
<div class="flex justify-between items-center mb-4">
<div class="flex items-center space-x-2">
<span>Show</span>
<select class="border rounded px-2 py-1">
<option>10</option>
<option>25</option>
<option>50</option>
</select>
<span>entries</span>
</div>
<div class="flex items-center space-x-2">
<span>Search:</span>
<input class="border rounded px-2 py-1" type="text"/>
</div>
</div>
<table class="w-full text-left">
<thead class="border-b-2 border-orange-400">
<tr>
<th class="py-2">No. <span class="material-icons text-sm align-middle">unfold_more</span></th>
<th class="py-2">Invoice <span class="material-icons text-sm align-middle">unfold_more</span></th>
<th class="py-2">Invoice Date <span class="material-icons text-sm align-middle">unfold_more</span></th>
<th class="py-2">Due Date <span class="material-icons text-sm align-middle">unfold_more</span></th>
<th class="py-2">Total <span class="material-icons text-sm align-middle">unfold_more</span></th>
<th class="py-2">Status <span class="material-icons text-sm align-middle">unfold_more</span></th>
<th class="py-2">Action</th>
</tr>
</thead>
<tbody>
<tr>
<td class="text-center py-10 text-gray-500" colspan="7">No data available in table</td>
</tr>
</tbody>
</table>
<div class="flex justify-between items-center mt-4 text-sm text-gray-600">
<div>Showing 0 to 0 of 0 entries</div>
<div class="flex items-center space-x-2">
<button class="p-1 rounded border disabled:opacity-50" disabled="">
<span class="material-icons">chevron_left</span>
</button>
<button class="p-1 rounded border disabled:opacity-50" disabled="">
<span class="material-icons">chevron_right</span>
</button>
</div>
</div>
</div>
</main>
<footer class="text-center p-4 text-sm text-gray-500">Copyright 2025 © All Rights Reserved.</footer>
</div>

</body>
</html>