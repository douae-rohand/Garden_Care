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
          <h1 class="text-3xl" :style="{ color: currentServiceColor }">
            Tous nos {{ currentServiceName }}
          </h1>
        </div>

        <!-- Search inputs -->
        <div class="flex gap-4 items-center">
          <div class="flex-1 relative">
            <MapPin class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" :size="20" />
            <input
              type="text"
              placeholder="Ville"
              v-model="citySearch"
              class="w-full pl-12 pr-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-gray-300"
            />
          </div>
          
          <div class="flex-1 relative">
            <select
              v-model="serviceTypeFilter"
              class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:outline-none focus:border-gray-300 appearance-none cursor-pointer"
            >
              <option value="all">Type de service</option>
              <option v-for="type in serviceTypes" :key="type" :value="type">{{ type }}</option>
            </select>
          </div>

          <button
            class="px-8 py-3 rounded-lg text-white flex items-center gap-2 hover:opacity-90 transition-all"
            :style="{ backgroundColor: currentServiceColor }"
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
                <SlidersHorizontal :size="20" :style="{ color: currentServiceColor }" />
                <h3 class="text-lg" :style="{ color: currentServiceColor }">Filtres</h3>
              </div>
              <button
                @click="resetFilters"
                class="text-sm hover:underline"
                :style="{ color: currentServiceColor }"
              >
                Réinitialiser
              </button>
            </div>

            <!-- Ville -->
            <div class="mb-6">
              <label class="block text-sm font-medium mb-2">Ville</label>
              <select
                v-model="selectedCity"
                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none cursor-pointer"
              >
                <option value="all">Toutes les villes</option>
                <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
              </select>
            </div>

            <!-- Type de service -->
            <div class="mb-6">
              <label class="block text-sm font-medium mb-3">Spécialités</label>
              <div class="space-y-2">
                <label v-for="type in serviceTypes" :key="type" class="flex items-center gap-2 cursor-pointer text-sm">
                  <input
                    type="checkbox"
                    :value="type"
                    v-model="selectedServiceTypes"
                    class="w-4 h-4 cursor-pointer"
                    :style="{ accentColor: currentServiceColor }"
                  />
                  <span class="text-gray-700">{{ type }}</span>
                </label>
              </div>
            </div>

            <!-- Tarif horaire -->
            <div class="mb-6">
              <label class="block text-sm font-medium mb-3">Tarif horaire</label>
              <div class="px-2">
                <input
                  type="range"
                  min="10"
                  max="50"
                  v-model="priceRangeMax"
                  class="w-full"
                  :style="{ accentColor: currentServiceColor }"
                />
                <div class="flex justify-between text-xs text-gray-600 mt-2">
                  <span class="px-2 py-1 rounded" :style="{ backgroundColor: currentServiceColor + '20', color: currentServiceColor }">
                    10DH/h
                  </span>
                  <span class="px-2 py-1 rounded" :style="{ backgroundColor: currentServiceColor + '20', color: currentServiceColor }">
                    {{ priceRangeMax }}DH/h
                  </span>
                </div>
              </div>
            </div>

            <!-- Note minimum -->
            <div class="mb-6">
              <label class="block text-sm font-medium mb-3">Note minimum</label>
              <div class="space-y-2">
                <label v-for="rating in ['5+', '4.5+', '4+', '3.5+']" :key="rating" class="flex items-center gap-2 cursor-pointer text-sm">
                  <input
                    type="radio"
                    name="rating"
                    :value="rating"
                    v-model="selectedRating"
                    class="w-4 h-4 cursor-pointer"
                    :style="{ accentColor: currentServiceColor }"
                  />
                  <Star :size="14" class="fill-yellow-400 text-yellow-400" />
                  <span class="text-gray-700">{{ rating }}</span>
                </label>
              </div>
            </div>

            <!-- Apply button -->
            <button
              class="w-full py-3 rounded-lg text-white transition-all hover:opacity-90"
              :style="{ backgroundColor: currentServiceColor }"
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
              <Filter :size="18" :style="{ color: currentServiceColor }" />
              <span class="px-4 py-2 rounded-lg text-sm" :style="{ backgroundColor: currentServiceColor, color: 'white' }">
                {{ filteredIntervenants.length }} intervenant{{ filteredIntervenants.length > 1 ? 's' : '' }} trouvé{{ filteredIntervenants.length > 1 ? 's' : '' }}
              </span>
            </div>

            <select
              v-model="sortBy"
              class="px-4 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none cursor-pointer"
            >
              <option value="pertinence">Trier par: Pertinence</option>
              <option value="rating">Note décroissante</option>
              <option value="missions">Missions décroissantes</option>
            </select>
          </div>

          <!-- Loading State -->
          <div v-if="loading" class="text-center py-16">
            <div class="text-4xl mb-4">⏳</div>
            <p class="text-gray-500">Chargement des intervenants...</p>
          </div>

          <!-- Intervenants Grid -->
          <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="intervenant in sortedIntervenants"
              :key="intervenant.id"
              class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all overflow-hidden cursor-pointer"
              @click="viewProfile(intervenant)"
            >
              <!-- Image with badges -->
              <div class="relative h-48 bg-gradient-to-br" :style="{ backgroundImage: `linear-gradient(135deg, ${currentServiceColor}40, ${currentServiceColor}80)` }">
                <div class="absolute inset-0 flex items-center justify-center">
                  <div 
                    class="w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-bold"
                    :style="{ backgroundColor: currentServiceColor }"
                  >
                    {{ intervenant.prenom?.charAt(0) }}{{ intervenant.nom?.charAt(0) }}
                  </div>
                </div>
                <div class="absolute top-3 left-3 flex gap-2">
                  <span class="px-3 py-1 rounded-full text-xs text-white flex items-center gap-1" :style="{ backgroundColor: currentServiceColor }">
                    <CheckCircle :size="12" />
                    Vérifié
                  </span>
                </div>
                <button 
                  class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full flex items-center justify-center hover:bg-gray-100"
                  @click.stop="toggleFavorite(intervenant.id)"
                >
                  <Heart :size="16" :class="favorites.includes(intervenant.id) ? 'fill-red-500 text-red-500' : 'text-gray-600'" />
                </button>
              </div>

              <!-- Content -->
              <div class="p-4">
                <h3 class="text-lg font-semibold mb-1">{{ intervenant.prenom }} {{ intervenant.nom }}</h3>
                <p class="text-xs text-gray-500 mb-3">{{ intervenant.missions }} missions complétées</p>

                <!-- Specialties -->
                <div class="flex flex-wrap gap-1.5 mb-3">
                  <span
                    v-for="(tache, index) in intervenant.taches?.slice(0, 2)"
                    :key="index"
                    class="text-xs px-2 py-1 rounded-md"
                    :style="{
                      backgroundColor: currentServiceColor + '20',
                      color: currentServiceColor
                    }"
                  >
                    {{ tache.nom }}
                  </span>
                </div>

                <!-- Icons -->
                <div class="flex items-center gap-3 mb-3 text-xs text-gray-600">
                  <div class="flex items-center gap-1">
                    <MapPin :size="14" :style="{ color: currentServiceColor }" />
                    <span>{{ intervenant.ville || 'Non spécifié' }}</span>
                  </div>
                </div>

                <!-- Rating and price -->
                <div class="flex items-center justify-between mb-4">
                  <div class="flex items-center gap-1">
                    <Star :size="16" class="fill-yellow-400 text-yellow-400" />
                    <span class="font-semibold">{{ intervenant.note || 0 }}</span>
                    <span class="text-xs text-gray-500">({{ intervenant.missions }} avis)</span>
                  </div>
                  <span class="text-lg font-semibold" :style="{ color: currentServiceColor }">
                    {{ intervenant.taches?.[0]?.tarif || 0 }}DH/h
                  </span>
                </div>

                <!-- Button -->
                <button
                  class="w-full py-2.5 rounded-lg text-white transition-all hover:opacity-90"
                  :style="{ backgroundColor: currentServiceColor }"
                  @click.stop="viewProfile(intervenant)"
                >
                  Voir le profil
                </button>
              </div>
            </div>
          </div>

          <!-- No results -->
          <div v-if="!loading && sortedIntervenants.length === 0" class="text-center py-16">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-2xl mb-2 text-gray-700">Aucun intervenant trouvé</h3>
            <p class="text-gray-500 mb-4">Essayez de modifier vos critères de recherche</p>
            <button
              @click="resetFilters"
              class="px-6 py-3 rounded-lg text-white"
              :style="{ backgroundColor: currentServiceColor }"
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
import { ref, computed, watch, onMounted } from 'vue'
import { 
  ArrowLeft, 
  Search, 
  Filter, 
  SlidersHorizontal, 
  Heart, 
  CheckCircle, 
  Star, 
  MapPin 
} from 'lucide-vue-next'
import adminService from '@/services/adminService'

const props = defineProps({
  service: {
    type: String,
    default: 'tous',
    validator: (value) => ['tous', 'Jardinage', 'Ménage'].includes(value)
  }
})

const emit = defineEmits(['back', 'view-profile'])

// State
const loading = ref(false)
const intervenants = ref([])
const citySearch = ref('')
const serviceTypeFilter = ref('all')
const selectedCity = ref('all')
const selectedServiceTypes = ref([])
const priceRangeMax = ref(50)
const selectedRating = ref('all')
const sortBy = ref('pertinence')
const favorites = ref([])

// Service configuration
const serviceConfig = {
  'Jardinage': {
    name: 'Jardiniers',
    color: '#92B08B',
    types: ['Tonte de pelouse', 'Taille de haies', 'Plantation de fleurs', 'Élagage', 'Entretien potager']
  },
  'Ménage': {
    name: 'Intervenants Ménage',
    color: '#1A5FA3',
    types: ['Ménage régulier', 'Grand nettoyage', 'Ménage simple', 'Après travaux', 'Bureaux']
  },
  'tous': {
    name: 'Intervenants',
    color: '#5B7C99',
    types: []
  }
}

const currentServiceName = computed(() => serviceConfig[props.service]?.name || 'Intervenants')
const currentServiceColor = computed(() => serviceConfig[props.service]?.color || '#5B7C99')
const serviceTypes = computed(() => serviceConfig[props.service]?.types || [])

const cities = ref(['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Tanger', 'Agadir', 'Meknès', 'Oujda'])

// Load intervenants
const loadIntervenants = async () => {
  try {
    loading.value = true
    const params = {}
    if (props.service !== 'tous') {
      params.service = props.service
    }
    const response = await adminService.getIntervenants(params)
    intervenants.value = response.data || []
  } catch (error) {
    console.error('Erreur chargement intervenants:', error)
    intervenants.value = []
  } finally {
    loading.value = false
  }
}

// Filtered intervenants
const filteredIntervenants = computed(() => {
  return intervenants.value.filter((intervenant) => {
    // City filter
    const matchesCity = selectedCity.value === 'all' || intervenant.ville === selectedCity.value
    
    // Service type filter (spécialités/tâches)
    const matchesServiceType = selectedServiceTypes.value.length === 0 || 
      intervenant.taches?.some(tache => selectedServiceTypes.value.includes(tache.nom))
    
    // Price filter
    const minTarif = intervenant.taches?.[0]?.tarif || 0
    const matchesPrice = minTarif <= priceRangeMax.value
    
    // Rating filter
    let matchesRating = true
    if (selectedRating.value === '5+') matchesRating = intervenant.note === 5.0
    else if (selectedRating.value === '4.5+') matchesRating = intervenant.note >= 4.5
    else if (selectedRating.value === '4+') matchesRating = intervenant.note >= 4.0
    else if (selectedRating.value === '3.5+') matchesRating = intervenant.note >= 3.5

    return matchesCity && matchesServiceType && matchesPrice && matchesRating
  })
})

// Sorted intervenants
const sortedIntervenants = computed(() => {
  const sorted = [...filteredIntervenants.value]
  if (sortBy.value === 'rating') {
    return sorted.sort((a, b) => (b.note || 0) - (a.note || 0))
  } else if (sortBy.value === 'missions') {
    return sorted.sort((a, b) => (b.missions || 0) - (a.missions || 0))
  }
  return sorted // pertinence (order as-is)
})

// Methods
const resetFilters = () => {
  selectedCity.value = 'all'
  selectedServiceTypes.value = []
  priceRangeMax.value = 50
  selectedRating.value = 'all'
}

const applyFilters = () => {
  // Filters are reactive, so this is mostly for the button action feedback
  console.log('Filters applied')
}

const viewProfile = (intervenant) => {
  emit('view-profile', intervenant)
}

const toggleFavorite = (id) => {
  const index = favorites.value.indexOf(id)
  if (index > -1) {
    favorites.value.splice(index, 1)
  } else {
    favorites.value.push(id)
  }
}

const handleBackHover = (e) => {
  e.currentTarget.style.backgroundColor = currentServiceColor.value
}

const handleBackLeave = (e) => {
  e.currentTarget.style.backgroundColor = 'transparent'
}

// Watch for service changes
watch(() => props.service, () => {
  loadIntervenants()
  resetFilters()
}, { immediate: true })

onMounted(() => {
  loadIntervenants()
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
