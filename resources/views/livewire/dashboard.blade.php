<div>
    <!-- Barre de navigation -->
    <nav class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <h1 class="text-xl font-semibold text-gray-900">🚗 Parking ESTM</h1>
                    <div class="hidden md:flex space-x-4">
                        <a href="/dashboard" class="nav-link nav-link-active">Tableau de bord</a>
                        <a href="/history" class="nav-link nav-link-inactive">Historique</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">Bonjour, {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn-danger">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Statistiques d'occupation -->
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="stat-card bg-parking-primary">
                    <div class="p-5 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-lg font-medium">🅿️ Total Places</div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <div class="text-3xl font-bold">{{ $totalSlots }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-parking-free">
                    <div class="p-5 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-lg font-medium">✅ Places Libres</div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <div class="text-3xl font-bold {{ $availableSlots == 0 ? 'animate-pulse-slow' : '' }}">{{ $availableSlots }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-parking-occupied">
                    <div class="p-5 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-lg font-medium">🚗 Places Occupées</div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <div class="text-3xl font-bold">{{ $occupiedSlots }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-purple-500">
                    <div class="p-5 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-lg font-medium">📊 Taux d'occupation</div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <div class="text-3xl font-bold {{ $occupancyRate >= 90 ? 'animate-bounce-subtle text-yellow-300' : '' }}">{{ $occupancyRate }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- État des places de parking -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold text-gray-900">État des places de parking</h2>
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="animate-pulse w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            Mise à jour automatique toutes les 5 secondes
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6">
                        @foreach ($parkingSlots as $slot)
                            <div class="parking-slot {{ $slot->status === 'occupied' ? 'parking-slot-occupied' : 'parking-slot-free' }}">
                                <div class="text-lg font-bold text-center">{{ $slot->slot_code }}</div>
                                <div class="text-center text-sm">
                                    {{ $slot->status === 'occupied' ? '🚗 Occupé' : '✅ Libre' }}
                                </div>
                                @if($slot->last_detection)
                                    <div class="mt-2 text-xs text-center opacity-90">
                                        {{ $slot->last_detection->diffForHumans() }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Légende -->
                    <div class="mt-6 flex items-center justify-center space-x-6 text-sm">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-parking-free rounded mr-2"></div>
                            <span class="text-gray-700">Place libre</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-parking-occupied rounded mr-2"></div>
                            <span class="text-gray-700">Place occupée</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
