<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Send Feedback') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Your feedback helps us improve our services. Please share your thoughts about the application.') }}
        </p>
    </header>

    <div class="relative w-full overflow-hidden pt-[56.25%] ">
        @php
            // Ambil nama user yang sedang login
            $userName = auth()->user()->name;
            
            $nameEntryId = env('ENTRY_KEY'); // Ganti dengan entry ID yang benar
            
            // Base URL Google Form
            $baseUrl = 'https://docs.google.com/forms/d/e/1FAIpQLSfnicTlX0_1bzA58ZlmX9MtFD1QSZvrs5a5evJRezdLIQtbfw/viewform';
            
            // Buat URL dengan pre-fill parameter
            $prefilledUrl = $baseUrl . '?embedded=true&usp=pp_url&' . $nameEntryId . '=' . urlencode($userName);
        @endphp
        
        <iframe 
            class="absolute top-0 left-0 bottom-0 right-0 w-full h-full"
            src="{{ $prefilledUrl }}" 
            frameborder="0" 
            marginheight="0" 
            marginwidth="0"
        >
            Memuat…
        </iframe>
    </div>
</section>