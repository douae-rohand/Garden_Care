<template>
  <div>
    <!-- Back Button -->
    <button
      @click="$emit('back')"
      class="flex items-center gap-2 mb-6 text-sm transition-colors hover:underline"
      style="color: #5B7C99"
    >
      ← Retour au tableau de bord
    </button>

    <!-- Header -->
    <h2 class="text-2xl font-semibold mb-6" style="color: #2F4F4F">
      Gestion des Clients
    </h2>

    <!-- Search and Filters -->
    <div class="flex gap-4 mb-6 flex-wrap items-center">
      <div class="flex-1 min-w-64 relative">
        <input
          type="text"
          placeholder="Rechercher par nom, prénom ou email..."
          v-model="searchTerm"
          class="w-full pl-4 pr-4 py-2.5 border rounded-lg focus:outline-none text-sm focus:ring-2 focus:ring-blue-200"
          style="border-color: #D1D5DB; background-color: #F9FAFB"
        />
      </div>
      
      <div class="flex gap-2">
        <button
          v-for="filter in statusFilters"
          :key="filter.value"
          @click="filterStatus = filter.value"
          class="px-4 py-2.5 rounded-lg text-sm font-medium transition-all whitespace-nowrap"
          :style="{ 
            backgroundColor: filterStatus === filter.value ? filter.activeColor : 'white',
            color: filterStatus === filter.value ? 'white' : '#6B7280',
            border: `1px solid ${filterStatus === filter.value ? filter.activeColor : '#D1D5DB'}`
          }"
        >
          {{ filter.label }} ({{ filter.count }})
        </button>
      </div>
    </div>

    <!-- Clients Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <div 
        v-for="client in filteredClients" 
        :key="client.id"
        class="bg-white rounded-2xl p-5 border transition-all"
        style="border-color: #E5E7EB; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05)"
      >
        <!-- Header with Photo and Badge -->
        <div class="flex items-start gap-4 mb-4">
          <!-- Profile Photo Placeholder -->
          <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 relative" 
               style="background-color: #E5E7EB">
            <User :size="28" style="color: #9CA3AF" />
            <!-- Online Status Indicator (if active) -->
            <div v-if="client.statut === 'actif'" 
                 class="absolute bottom-0 right-0 w-4 h-4 rounded-full border-2 border-white"
                 style="background-color: #5EAD5F">
            </div>
          </div>
          
          <div class="flex-1 min-w-0">
            <!-- Name -->
            <h3 class="text-base font-semibold mb-1" style="color: #1F2937">
              {{ client.prenom }} {{ client.nom }}
            </h3>
            <!-- Email -->
            <p class="text-xs mb-1" style="color: #6B7280">
              {{ client.email }}
            </p>
            <!-- Phone -->
            <p class="text-xs flex items-center gap-1" style="color: #6B7280">
              <Phone :size="12" />
              {{ client.telephone }}
            </p>
          </div>

          <!-- Status Badge -->
          <span 
            class="px-3 py-1 rounded-md text-xs font-medium text-white flex-shrink-0"
            :style="{ 
              backgroundColor: client.statut === 'actif' ? '#5EAD5F' : '#5B7C99'
            }"
          >
            {{ client.statut }}
          </span>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-3 mb-4 rounded-lg p-3" style="background-color: #F9FAFB">
          <div class="text-center">
            <p class="text-xs mb-1" style="color: #6B7280">Réservations</p>
            <p class="text-xl font-semibold" style="color: #1F2937">{{ client.reservations }}</p>
          </div>
          <div class="text-center">
            <p class="text-xs mb-1" style="color: #6B7280">Dépenses</p>
            <p class="text-lg font-semibold" style="color: #5EAD5F">{{ client.montantTotal }}DH</p>
          </div>
          <div class="text-center">
            <p class="text-xs mb-1" style="color: #6B7280">Avis</p>
            <p class="text-xl font-semibold" style="color: #1F2937">{{ client.avis || 0 }}</p>
          </div>
        </div>

        <!-- Action Button -->
        <button
          class="w-full py-2.5 rounded-lg text-sm font-medium transition-all text-white hover:opacity-90"
          :style="{ 
            backgroundColor: client.statut === 'actif' ? '#5EAD5F' : '#5B7C99'
          }"
          @click.stop="viewClientDetails(client)"
        >
          Voir les détails
        </button>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredClients.length === 0" class="text-center py-12">
      <p class="text-gray-500">Aucun client trouvé</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { User, Phone } from 'lucide-vue-next'

const props = defineProps({
  clients: {
    type: Array,
    required: true,
    default: () => []
  },
  loading: Boolean
})

const emit = defineEmits(['back', 'view-client', 'suspend-client'])

const searchTerm = ref('')
const filterStatus = ref('tous')

const statusFilters = computed(() => {
  const actifsCount = props.clients.filter(c => c.statut && c.statut === 'actif').length
  const suspendusCount = props.clients.filter(c => c.statut && c.statut === 'suspendu').length
  
  return [
    { 
      value: 'tous', 
      label: 'Tous', 
      count: props.clients.length, 
      activeColor: '#FBBF24' 
    },
    { 
      value: 'actif', 
      label: 'Actifs', 
      count: actifsCount, 
      activeColor: '#5EAD5F' 
    },
    { 
      value: 'suspendu', 
      label: 'Suspendus', 
      count: suspendusCount, 
      activeColor: '#5B7C99' 
    }
  ]
})

const filteredClients = computed(() => {
  return props.clients.filter(client => {
    const matchesSearch = (client.nom || '').toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                         (client.prenom || '').toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                         (client.email || '').toLowerCase().includes(searchTerm.value.toLowerCase())
    const matchesStatus = filterStatus.value === 'tous' || client.statut === filterStatus.value
    return matchesSearch && matchesStatus
  })
})

const viewClientDetails = (client) => {
  emit('view-client', client)
}
</script>
