<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Top Search Bar -->
    <div class="bg-white shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center gap-4 mb-4">
          <button
            @click="$emit('back')"
            class="flex items-center gap-2 text-gray-600 hover:text-white transition-all px-3 py-2 rounded-lg"
            @mouseenter="handleBackHover"
            @mouseleave="handleBackLeave"
          >
            <ArrowLeft :size="20" />
          </button>
          <h1 class="text-3xl" style="color: #2F4F4F">
            Tous les Clients
          </h1>
        </div>

        <!-- Search inputs -->
        <div class="flex gap-4 items-center">
          <div class="flex-1 relative">
            <Search class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" :size="20" />
            <input
              type="text"
              placeholder="Rechercher par nom, prénom ou email..."
              v-model="searchTerm"
              class="w-full pl-12 pr-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-gray-300"
            />
          </div>
          
          <div class="flex-1 relative">
            <MapPin class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" :size="20" />
            <input
              type="text"
              placeholder="Ville"
              v-model="citySearch"
              class="w-full pl-12 pr-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-gray-300"
            />
          </div>

          <button
            class="px-8 py-3 rounded-lg text-white flex items-center gap-2 hover:opacity-90 transition-all"
            style="background-color: #5B7C99"
            @click="applyFilters"
          >
            <Search :size="20" />
            Rechercher
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content with Sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex gap-8">
        <!-- Left Sidebar - Filters -->
        <div class="w-64 flex-shrink-0">
          <div class="bg-white rounded-xl shadow-md p-6 sticky top-4">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-2">
                <SlidersHorizontal :size="20" style="color: #5B7C99" />
                <h3 class="text-lg" style="color: #5B7C99">Filtres</h3>
              </div>
              <button
                @click="resetFilters"
                class="text-sm hover:underline"
                style="color: #5B7C99"
              >
                Réinitialiser
              </button>
            </div>

            <!-- Statut -->
            <div class="mb-6">
              <label class="block text-sm font-medium mb-3">Statut</label>
              <div class="space-y-2">
                <label v-for="status in statusOptions" :key="status.value" class="flex items-center gap-2 cursor-pointer text-sm">
                  <input
                    type="radio"
                    name="status"
                    :value="status.value"
                    v-model="selectedStatus"
                    class="w-4 h-4 cursor-pointer"
                    style="accent-color: #5B7C99"
                  />
                  <span class="text-gray-700">{{ status.label }}</span>
                </label>
              </div>
            </div>

            <!-- Réservations -->
            <div class="mb-6">
              <label class="block text-sm font-medium mb-3">Nombre de réservations</label>
              <div class="px-2">
                <input
                  type="range"
                  min="0"
                  max="50"
                  v-model="minReservations"
                  class="w-full"
                  style="accent-color: #5B7C99"
                />
                <div class="flex justify-between text-xs text-gray-600 mt-2">
                  <span class="px-2 py-1 rounded" style="background-color: #5B7C9920; color: #5B7C99">
                    {{ minReservations }}+
                  </span>
                  <span class="text-gray-500">réservations</span>
                </div>
              </div>
            </div>

            <!-- Montant dépensé -->
            <div class="mb-6">
              <label class="block text-sm font-medium mb-3">Montant dépensé minimum</label>
              <div class="px-2">
                <input
                  type="range"
                  min="0"
                  max="5000"
                  step="100"
                  v-model="minAmount"
                  class="w-full"
                  style="accent-color: #5B7C99"
                />
                <div class="flex justify-between text-xs text-gray-600 mt-2">
                  <span class="px-2 py-1 rounded" style="background-color: #5B7C9920; color: #5B7C99">
                    {{ minAmount }}DH+
                  </span>
                </div>
              </div>
            </div>

            <!-- Apply button -->
            <button
              class="w-full py-3 rounded-lg text-white transition-all hover:opacity-90"
              style="background-color: #5B7C99"
              @click="applyFilters"
            >
              Appliquer les filtres
            </button>
          </div>
        </div>

        <!-- Right Content - Results -->
        <div class="flex-1">
          <!-- Results header -->
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <Filter :size="18" style="color: #5B7C99" />
              <span class="px-4 py-2 rounded-lg text-sm text-white" style="background-color: #5B7C99">
                {{ filteredClients.length }} client{{ filteredClients.length > 1 ? 's' : '' }} trouvé{{ filteredClients.length > 1 ? 's' : '' }}
              </span>
            </div>

            <select
              v-model="sortBy"
              class="px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none cursor-pointer"
            >
              <option value="recent">Trier par: Plus récent</option>
              <option value="reservations">Réservations décroissantes</option>
              <option value="amount">Montant décroissant</option>
              <option value="name">Nom alphabétique</option>
            </select>
          </div>

          <!-- Loading State -->
          <div v-if="loading" class="text-center py-16">
            <div class="text-4xl mb-4">⏳</div>
            <p class="text-gray-500">Chargement des clients...</p>
          </div>

          <!-- Clients Grid -->
          <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="client in sortedClients"
              :key="client.id"
              class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all overflow-hidden cursor-pointer"
              @click="viewProfile(client)"
            >
              <!-- Header with avatar -->
              <div class="relative h-32 bg-gradient-to-br from-blue-50 to-blue-100">
                <div class="absolute inset-0 flex items-center justify-center">
                  <div 
                    class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold"
                    style="background-color: #5B7C99"
                  >
                    {{ client.prenom?.charAt(0) }}{{ client.nom?.charAt(0) }}
                  </div>
                </div>
                <div class="absolute top-3 right-3">
                  <span 
                    class="px-3 py-1 rounded-full text-xs text-white"
                    :style="{ backgroundColor: client.statut === 'actif' ? '#4CAF50' : '#FF6B6B' }"
                  >
                    {{ client.statut }}
                  </span>
                </div>
              </div>

              <!-- Content -->
              <div class="p-4">
                <h3 class="text-lg font-semibold mb-1" style="color: #2F4F4F">
                  {{ client.prenom }} {{ client.nom }}
                </h3>
                <p class="text-xs text-gray-500 mb-3">Client depuis {{ formatDate(client.dateInscription) }}</p>

                <!-- Contact Info -->
                <div class="space-y-2 mb-4 text-sm text-gray-600">
                  <div class="flex items-center gap-2">
                    <Mail :size="14" style="color: #5B7C99" />
                    <span class="truncate">{{ client.email }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <Phone :size="14" style="color: #5B7C99" />
                    <span>{{ client.telephone }}</span>
                  </div>
                  <div v-if="client.adresse" class="flex items-center gap-2">
                    <MapPin :size="14" style="color: #5B7C99" />
                    <span class="truncate">{{ client.ville || 'Non spécifié' }}</span>
                  </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-3 mb-4 bg-gray-50 p-3 rounded-lg">
                  <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Réservations</p>
                    <p class="text-xl font-bold" style="color: #2F4F4F">{{ client.reservations }}</p>
                  </div>
                  <div class="text-center">
                    <p class="text-xs text-gray-500 mb-1">Dépenses</p>
                    <p class="text-xl font-bold" style="color: #4CAF50">{{ client.montantTotal }}DH</p>
                  </div>
                </div>

                <!-- Last Service -->
                <div v-if="client.dernierService" class="mb-4 text-xs text-gray-500">
                  Dernier service: {{ formatDate(client.dernierService) }}
                </div>

                <!-- Button -->
                <button
                  class="w-full py-2.5 rounded-lg text-white transition-all hover:opacity-90"
                  style="background-color: #5B7C99"
                  @click.stop="viewProfile(client)"
                >
                  Voir le profil
                </button>
              </div>
            </div>
          </div>

          <!-- No results -->
          <div v-if="!loading && sortedClients.length === 0" class="text-center py-16">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-2xl mb-2 text-gray-700">Aucun client trouvé</h3>
            <p class="text-gray-500 mb-4">Essayez de modifier vos critères de recherche</p>
            <button
              @click="resetFilters"
              class="px-6 py-3 rounded-lg text-white"
              style="background-color: #5B7C99"
            >
              Réinitialiser les filtres
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  ArrowLeft, 
  Search, 
  Filter, 
  SlidersHorizontal, 
  Mail, 
  Phone, 
  MapPin 
} from 'lucide-vue-next'
import adminService from '@/services/adminService'

const emit = defineEmits(['back', 'view-profile'])

// State
const loading = ref(false)
const clients = ref([])
const searchTerm = ref('')
const citySearch = ref('')
const selectedStatus = ref('all')
const minReservations = ref(0)
const minAmount = ref(0)
const sortBy = ref('recent')

const statusOptions = [
  { value: 'all', label: 'Tous les statuts' },
  { value: 'actif', label: 'Actifs' },
  { value: 'suspendu', label: 'Suspendus' }
]

// Load clients
const loadClients = async () => {
  try {
    loading.value = true
    const response = await adminService.getClients()
    clients.value = response.data || []
  } catch (error) {
    console.error('Erreur chargement clients:', error)
    clients.value = []
  } finally {
    loading.value = false
  }
}

// Filtered clients
const filteredClients = computed(() => {
  return clients.value.filter((client) => {
    // Search filter
    const searchLower = searchTerm.value.toLowerCase()
    const matchesSearch = !searchTerm.value || 
      client.nom?.toLowerCase().includes(searchLower) ||
      client.prenom?.toLowerCase().includes(searchLower) ||
      client.email?.toLowerCase().includes(searchLower)
    
    // City filter
    const matchesCity = !citySearch.value || 
      client.ville?.toLowerCase().includes(citySearch.value.toLowerCase()) ||
      client.adresse?.toLowerCase().includes(citySearch.value.toLowerCase())
    
    // Status filter
    const matchesStatus = selectedStatus.value === 'all' || client.statut === selectedStatus.value
    
    // Reservations filter
    const matchesReservations = (client.reservations || 0) >= minReservations.value
    
    // Amount filter
    const matchesAmount = (client.montantTotal || 0) >= minAmount.value

    return matchesSearch && matchesCity && matchesStatus && matchesReservations && matchesAmount
  })
})

// Sorted clients
const sortedClients = computed(() => {
  const sorted = [...filteredClients.value]
  
  if (sortBy.value === 'reservations') {
    return sorted.sort((a, b) => (b.reservations || 0) - (a.reservations || 0))
  } else if (sortBy.value === 'amount') {
    return sorted.sort((a, b) => (b.montantTotal || 0) - (a.montantTotal || 0))
  } else if (sortBy.value === 'name') {
    return sorted.sort((a, b) => {
      const nameA = `${a.prenom} ${a.nom}`.toLowerCase()
      const nameB = `${b.prenom} ${b.nom}`.toLowerCase()
      return nameA.localeCompare(nameB)
    })
  } else {
    // recent - sort by dateInscription descending
    return sorted.sort((a, b) => {
      const dateA = new Date(a.dateInscription || 0)
      const dateB = new Date(b.dateInscription || 0)
      return dateB - dateA
    })
  }
})

// Methods
const resetFilters = () => {
  searchTerm.value = ''
  citySearch.value = ''
  selectedStatus.value = 'all'
  minReservations.value = 0
  minAmount.value = 0
}

const applyFilters = () => {
  // Filters are reactive, so this is mostly for the button action feedback
  console.log('Filters applied')
}

const viewProfile = (client) => {
  emit('view-profile', client)
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', { 
    day: 'numeric',
    month: 'short', 
    year: 'numeric' 
  })
}

const handleBackHover = (e) => {
  e.currentTarget.style.backgroundColor = '#5B7C99'
}

const handleBackLeave = (e) => {
  e.currentTarget.style.backgroundColor = 'transparent'
}

onMounted(() => {
  loadClients()
})
</script>

<style scoped>
/* Custom scrollbar for filters */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
