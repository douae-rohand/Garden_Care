<template>
  <!-- Modal Overlay -->
  <div 
    v-if="isOpen" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    @click.self="$emit('close')"
  >
    <!-- Modal Content -->
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b" style="border-color: #E5E7EB">
        <h2 class="text-xl font-semibold" style="color: #1F2937">
          Détails du Client
        </h2>
        <button 
          @click="$emit('close')"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <X :size="24" />
        </button>
      </div>

      <!-- Content -->
      <div class="p-6">
        <!-- Client Header -->
        <div class="flex items-start gap-4 mb-6">
          <!-- Profile Photo with Initials -->
          <div class="w-16 h-16 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xl font-semibold"
               :style="{ backgroundColor: '#5EAD5F' }">
            {{ getInitials(client.prenom, client.nom) }}
          </div>

          <div class="flex-1">
            <!-- Name and Status -->
            <div class="flex items-center gap-3 mb-2">
              <h3 class="text-lg font-semibold" style="color: #1F2937">
                {{ client.prenom }} {{ client.nom }}
              </h3>
              <span 
                class="px-3 py-1 rounded-md text-xs font-medium text-white"
                :style="{ backgroundColor: client.statut === 'actif' ? '#5EAD5F' : '#5B7C99' }"
              >
                {{ client.statut }}
              </span>
            </div>

            <!-- Contact Info -->
            <div class="space-y-1 text-sm" style="color: #6B7280">
              <div class="flex items-center gap-2">
                <Mail :size="14" />
                <span>{{ client.email }}</span>
              </div>
              <div class="flex items-center gap-2">
                <Phone :size="14" />
                <span>{{ client.telephone }}</span>
              </div>
              <div v-if="client.adresse" class="flex items-center gap-2">
                <MapPin :size="14" />
                <span>{{ client.adresse }}</span>
              </div>
              <div class="flex items-center gap-2">
                <Calendar :size="14" />
                <span>Inscrit le {{ formatDateFull(client.dateInscription) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-3 mb-6">
          <!-- Réservations -->
          <div class="text-center p-4 rounded-lg" style="background-color: #EFF6FF">
            <p class="text-xs mb-2" style="color: #6B7280">Réservations</p>
            <p class="text-2xl font-semibold" style="color: #1F2937">
              {{ client.reservations }}
            </p>
          </div>

          <!-- Dépenses totales -->
          <div class="text-center p-4 rounded-lg" style="background-color: #F0FDF4">
            <p class="text-xs mb-2" style="color: #6B7280">Dépenses totales</p>
            <p class="text-xl font-semibold" style="color: #5EAD5F">
              {{ client.montantTotal }}DH
            </p>
          </div>

          <!-- Dernier service -->
          <div class="text-center p-4 rounded-lg" style="background-color: #FEF9E7">
            <p class="text-xs mb-2" style="color: #6B7280">Dernier service</p>
            <p class="text-sm font-semibold" style="color: #1F2937">
              {{ formatDateShort(client.dernierService) }}
            </p>
          </div>
        </div>

        <!-- Feedbacks Section -->
        <div v-if="client.feedbacks && client.feedbacks.length > 0" class="mb-6">
          <h4 class="text-sm font-semibold mb-3" style="color: #1F2937">
            Feedbacks du client ({{ client.feedbacks.length }})
          </h4>

          <div class="space-y-3">
            <div 
              v-for="feedback in client.feedbacks" 
              :key="feedback.id"
              class="p-4 rounded-lg border"
              style="border-color: #E5E7EB; background-color: #FAFAFA"
            >
              <!-- Feedback Header -->
              <div class="flex items-start justify-between mb-2">
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-1">
                    <span class="text-sm font-medium" style="color: #1F2937">
                      Pour {{ feedback.intervenantNom }}
                    </span>
                    <span 
                      class="px-2 py-0.5 rounded text-xs font-medium text-white"
                      :style="{ backgroundColor: feedback.service === 'Jardinage' ? '#5EAD5F' : '#5B7C99' }"
                    >
                      {{ feedback.service }}
                    </span>
                  </div>
                  <p class="text-xs" style="color: #6B7280">
                    {{ formatDateFull(feedback.date) }}
                  </p>
                </div>

                <!-- Star Rating -->
                <div class="flex items-center gap-0.5">
                  <Star 
                    v-for="i in 5" 
                    :key="i"
                    :size="14"
                    :fill="i <= feedback.note ? '#FFC107' : 'none'"
                    :stroke="i <= feedback.note ? '#FFC107' : '#D1D5DB'"
                  />
                </div>
              </div>

              <!-- Comment -->
              <p class="text-sm italic" style="color: #4B5563">
                "{{ feedback.commentaire }}"
              </p>
            </div>
          </div>
        </div>

        <!-- No Feedbacks -->
        <div v-else class="mb-6 p-4 rounded-lg text-center" style="background-color: #F9FAFB">
          <p class="text-sm" style="color: #6B7280">Aucun feedback pour le moment</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
          <button
            v-if="client.statut === 'actif'"
            @click="$emit('suspend', client)"
            class="flex-1 py-3 rounded-lg text-sm font-medium text-white transition-all hover:opacity-90 flex items-center justify-center gap-2"
            style="background-color: #EF4444"
          >
            <AlertCircle :size="18" />
            Suspendre le compte
          </button>
          
          <button
            v-else
            @click="$emit('activate', client)"
            class="flex-1 py-3 rounded-lg text-sm font-medium text-white transition-all hover:opacity-90 flex items-center justify-center gap-2"
            style="background-color: #5EAD5F"
          >
            <CheckCircle :size="18" />
            Activer le compte
          </button>

          <button
            @click="$emit('close')"
            class="flex-1 py-3 rounded-lg text-sm font-medium text-white transition-all hover:opacity-90"
            style="background-color: #5B7C99"
          >
            Fermer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { X, Mail, Phone, MapPin, Calendar, Star, AlertCircle, CheckCircle } from 'lucide-vue-next'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  client: {
    type: Object,
    required: true,
    default: () => ({})
  }
})

defineEmits(['close', 'suspend', 'activate'])

const getInitials = (prenom, nom) => {
  const p = prenom ? prenom.charAt(0).toUpperCase() : ''
  const n = nom ? nom.charAt(0).toUpperCase() : ''
  return p + n
}

const formatDateFull = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const formatDateShort = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', { 
    year: 'numeric', 
    month: '2-digit', 
    day: '2-digit' 
  })
}
</script>
