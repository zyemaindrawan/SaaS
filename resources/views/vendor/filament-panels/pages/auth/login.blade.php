<x-filament-panels::page.simple>
    @assets
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
            .alert-success {
                background-color: #dcfce7;
                border-color: #bbf7d0;
                color: #166534;
            }
            .alert-error {
                background-color: #fee2e2;
                border-color: #fecaca;
                color: #991b1b;
            }

            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

            body {
                font-family: 'Poppins', sans-serif;
            }

            .filament-login-page {
                background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            }

            .filament-page-simple {
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border-radius: 1rem;
                overflow: hidden;
            }

            .filament-button {
                background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
                transition: all 0.3s ease;
                box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
            }

            .filament-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
            }

            .alert-success {
                background-color: #dcfce7;
                border-color: #bbf7d0;
                color: #166534;
            }

            .alert-error {
                background-color: #fee2e2;
                border-color: #fecaca;
                color: #991b1b;
            }

            header.fi-simple-header.flex.flex-col.items-center
            {
                display: none;
            }

            main.fi-simple-main {
                margin: 20px;
            }
            
        </style>
    @endassets

    <div class="text-center mb-6">
        <h1 class="my-4 text-3xl font-bold leading-tight text-gray-800">
            Login Admin
        </h1>
    </div>

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
</x-filament-panels::page.simple>