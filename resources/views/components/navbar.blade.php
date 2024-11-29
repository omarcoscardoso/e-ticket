<nav class="bg-gray-800 text-white top-0 left-0 right-0 z-100 flex flex-col items-center justify-center min-w-screen scale-110">
    <div class="container mx-auto px-10 py-2 flex justify-between items-center">
        <h1 class="text-2xl font-bold">e-Ticket</h1>
        <div class="flex items-center"> 
            <p class="text-sm">
                {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
</nav>