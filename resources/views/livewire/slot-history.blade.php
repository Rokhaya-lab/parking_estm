<div>
    <!-- Barre de navigation -->
    <nav class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <h1 class="text-xl font-semibold text-gray-900">🚗 Parking ESTM</h1>
                    <div class="hidden md:flex space-x-4">
                        <a href="/dashboard" class="nav-link nav-link-inactive">Tableau de bord</a>
                        <a href="/history" class="nav-link nav-link-active">Historique</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
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
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-4">Historique des événements</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="table-header">Place</th>
                                    <th class="table-header">État précédent</th>
                                    <th class="table-header">Nouvel état</th>
                                    <th class="table-header">Source</th>
                                    <th class="table-header">Date/Heure</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($events as $event)
                                    <tr>
                                        <td class="table-cell font-medium text-gray-900">
                                            {{ $event->parkingSlot->slot_code }}
                                        </td>
                                        <td class="table-cell">
                                            <span class="status-badge {{ $event->status_before === 'occupied' ? 'status-badge-occupied' : 'status-badge-free' }}">
                                                {{ $event->status_before === 'occupied' ? 'Occupé' : 'Libre' }}
                                            </span>
                                        </td>
                                        <td class="table-cell">
                                            <span class="status-badge {{ $event->status_after === 'occupied' ? 'status-badge-occupied' : 'status-badge-free' }}">
                                                {{ $event->status_after === 'occupied' ? 'Occupé' : 'Libre' }}
                                            </span>
                                        </td>
                                        <td class="table-cell">
                                            {{ $event->source }}
                                        </td>
                                        <td class="table-cell">
                                            {{ $event->detected_at->format('d/m/Y H:i:s') }}
                                            <div class="text-xs text-gray-400">{{ $event->detected_at->diffForHumans() }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Aucun événement enregistré
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
