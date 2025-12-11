<template>
  <div 
    v-if="isOpen && intervenant"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
    @click.self="$emit('close')"
  >
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b">
        <h2 class="text-xl font-semibold" style="color: #2F4F4F">Profil de l'Intervenant</h2>
        <button 
          @click="$emit('close')"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <X :size="24" />
        </button>
      </div>

      <!-- Profile Header -->
      <div class="p-6 border-b">
        <div class="flex items-start gap-4">
          <!-- Profile Photo -->
          <div class="relative">
            <div 
              class="w-16 h-16 rounded-full flex items-center justify-center text-2xl text-gray-400 border-4"
              :style="{ 
                backgroundColor: '#E5E7EB',
                borderColor: getServiceColor()
              }"
            >
              <User :size="32" />
            </div>
            <!-- Status Badge -->
            <div 
              class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-4 border-white flex items-center justify-center"
              :style="{ backgroundColor: intervenant.statut === 'actif' ? '#4CAF50' : '#94A3B8' }"
            >
              <Check v-if="intervenant.statut === 'actif'" :size="12" color="white" />
            </div>
          </div>

          <!-- Info -->
          <div class="flex-1">
            <h3 class="text-xl font-semibold mb-1" style="color: #2F4F4F">
              {{ intervenant.prenom }} {{ intervenant.nom }}
            </h3>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
              <MapPin :size="14" />
              {{ intervenant.adresse || '15 Rue du Jardin' }} • {{ getExperience() }}
            </div>
            <div class="flex items-center gap-2">
              <Star 
                v-for="i in 5" 
                :key="i"
                :size="18" 
                :fill="i <= Math.round(intervenant.note) ? '#FFC107' : 'none'"
                :stroke="i <= Math.round(intervenant.note) ? '#FFC107' : '#E5E7EB'"
              />
              <span class="text-lg font-semibold ml-2">{{ intervenant.note }}</span>
              <span class="text-gray-500">({{ intervenant.nbAvis }} avis)</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="flex border-b px-6">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          class="px-4 py-3 text-sm font-medium transition-colors relative"
          :style="{
            color: activeTab === tab.id ? '#2F4F4F' : '#9CA3AF',
            borderBottom: activeTab === tab.id ? '2px solid #2F4F4F' : '2px solid transparent'
          }"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- Content -->
      <div class="flex-1 overflow-y-auto p-6">
        <!-- À propos Tab -->
        <div v-if="activeTab === 'apropos'">
          <!-- Bio -->
          <div class="mb-6">
            <p class="text-sm text-gray-700 leading-relaxed">
              {{ intervenant.bio || `Bonjour ! Je m'appelle ${intervenant.prenom} et je suis professionnel(le) du ${getMainService()} depuis plusieurs années. Passionné(e) par mon métier, j'accorde une attention particulière aux détails pour vous offrir un intérieur impeccable. Mon expérience m'a permis de développer des méthodes efficaces et respectueuses de l'environnement.` }}
            </p>
          </div>

          <!-- Contact Info -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="flex items-start gap-3 p-4 rounded-lg" style="background-color: #F9FAFB">
              <Mail :size="18" style="color: #5B7C99" class="mt-1" />
              <div>
                <p class="text-xs text-gray-500 mb-1">Email</p>
                <p class="text-sm font-medium" style="color: #2F4F4F">{{ intervenant.email }}</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 rounded-lg" style="background-color: #F9FAFB">
              <Phone :size="18" style="color: #5B7C99" class="mt-1" />
              <div>
                <p class="text-xs text-gray-500 mb-1">Téléphone</p>
                <p class="text-sm font-medium" style="color: #2F4F4F">{{ intervenant.telephone }}</p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 rounded-lg" style="background-color: #F9FAFB">
              <Clock :size="18" style="color: #5B7C99" class="mt-1" />
              <div>
                <p class="text-xs text-gray-500 mb-1">Disponibilité</p>
                <p class="text-sm font-medium" style="color: #2F4F4F">{{ intervenant.disponibilite || 'Lun-Sam 8h-18h' }}</p>
              </div>
            </div>
          </div>

          <!-- Stats -->
          <div class="grid grid-cols-3 gap-4">
            <div class="text-center p-6 rounded-xl" style="background-color: rgba(143, 188, 143, 0.1)">
              <p class="text-sm text-gray-500 mb-2">Missions réalisées</p>
              <p class="text-3xl font-bold" style="color: #92B08B">{{ intervenant.missions }}</p>
            </div>
            <div class="text-center p-6 rounded-xl" style="background-color: rgba(91, 124, 153, 0.1)">
              <p class="text-sm text-gray-500 mb-2">Tâches proposées</p>
              <p class="text-3xl font-bold" style="color: #5B7C99">{{ intervenant.taches.length }}</p>
            </div>
            <div class="text-center p-6 rounded-xl" style="background-color: rgba(255, 193, 7, 0.1)">
              <p class="text-sm text-gray-500 mb-2">Note moyenne</p>
              <p class="text-3xl font-bold" style="color: #FFC107">{{ intervenant.note }}/5</p>
            </div>
          </div>
        </div>

        <!-- Tâches/Service Tab -->
        <div v-if="activeTab === 'taches'">
          <div 
            v-if="intervenant.allServices && intervenant.allServices.length > 0"
            v-for="service in intervenant.allServices"
            :key="service"
            class="mb-6"
          >
            <div class="mb-4 p-3 rounded-lg" style="background-color: #F0F9F4">
              <h4 class="font-semibold text-lg" style="color: #2F4F4F">Service : {{ service }}</h4>
              <p class="text-sm text-gray-600">Tâches proposées pour ce service</p>
            </div>

            <div class="space-y-4">
              <div 
                v-for="tache in getTachesForService(service)"
                :key="tache.id"
                class="border-2 rounded-xl p-4"
                :style="{ borderColor: service === 'Jardinage' ? '#92B08B' : '#5B7C99' }"
              >
                <h5 class="font-semibold mb-2" style="color: #2F4F4F">{{ tache.nom }}</h5>
                <p class="text-sm text-gray-600 mb-3">{{ tache.description || 'Description de la tâche' }}</p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <Clock :size="14" />
                    <span>{{ tache.duree || '2-3 heures' }} recommandées</span>
                  </div>
                  <div class="text-xl font-bold" :style="{ color: service === 'Jardinage' ? '#92B08B' : '#5B7C99' }">
                    {{ tache.tarif || '0' }}DH/h
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Fallback if no allServices -->
          <div v-if="!intervenant.allServices || intervenant.allServices.length === 0">
            <div class="space-y-4">
              <div 
                v-for="tache in intervenant.taches"
                :key="tache.id"
                class="border-2 rounded-xl p-4"
                :style="{ borderColor: getServiceColor() }"
              >
                <h5 class="font-semibold mb-2" style="color: #2F4F4F">{{ tache.nom }}</h5>
                <p class="text-sm text-gray-600 mb-3">{{ tache.description || 'Description de la tâche' }}</p>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <Clock :size="14" />
                    <span>{{ tache.duree || '2-3 heures' }} recommandées</span>
                  </div>
                  <div class="text-xl font-bold" :style="{ color: getServiceColor() }">
                    {{ tache.tarif || '0' }}DH/h
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Avis Tab -->
        <div v-if="activeTab === 'avis'">
          <!-- Rating Summary -->
          <div class="mb-6 p-6 rounded-xl" style="background-color: #F9FAFB">
            <div class="flex items-center gap-8">
              <div class="text-center">
                <p class="text-5xl font-bold mb-2" style="color: #2F4F4F">{{ intervenant.note }}</p>
                <p class="text-sm text-gray-500">{{ intervenant.nbAvis }} avis</p>
              </div>
              <div class="flex-1 space-y-2">
                <div v-for="i in 5" :key="i" class="flex items-center gap-3">
                  <span class="text-sm text-gray-600 w-8">{{ 6 - i }}★</span>
                  <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div 
                      class="h-full transition-all"
                      :style="{ 
                        width: getRatingPercentage(6 - i) + '%',
                        backgroundColor: '#5B7C99'
                      }"
                    ></div>
                  </div>
                  <span class="text-sm text-gray-500 w-12">{{ getRatingPercentage(6 - i) }}%</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Reviews List -->
          <div v-if="avisList.length > 0" class="space-y-4">
            <div 
              v-for="(avis, index) in avisList"
              :key="index"
              class="border-b pb-4"
            >
              <div class="flex items-start justify-between mb-2">
                <div>
                  <p class="font-semibold" style="color: #2F4F4F">{{ avis.clientNom }}</p>
                  <p class="text-xs text-gray-500">{{ formatDate(avis.date) }}</p>
                </div>
                <div class="flex">
                  <Star 
                    v-for="i in 5" 
                    :key="i"
                    :size="16" 
                    :fill="i <= avis.note ? '#FFC107' : 'none'"
                    :stroke="i <= avis.note ? '#FFC107' : '#E5E7EB'"
                  />
                </div>
              </div>
              <p v-if="avis.commentaire" class="text-sm text-gray-700">"{{ avis.commentaire }}"</p>
            </div>
          </div>
          <div v-else class="text-center py-8 text-gray-500">
            <p>Aucun avis pour le moment</p>
          </div>
        </div>

        <!-- Photos Tab -->
        <div v-if="activeTab === 'photos'">
          <div class="grid grid-cols-3 gap-4">
            <div 
              v-for="i in 6"
              :key="i"
              class="aspect-square rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center"
            >
              <ImageIcon :size="48" class="text-gray-300" />
            </div>
          </div>
        </div>

        <!-- Documents Tab -->
        <div v-if="activeTab === 'documents'">
          <div class="space-y-3">
            <div 
              v-for="doc in documents"
              :key="doc.id"
              class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl hover:border-gray-300 transition-colors"
            >
              <div class="flex items-center gap-3">
                <FileText :size="20" style="color: #92B08B" />
                <div>
                  <p class="font-medium text-sm" style="color: #2F4F4F">{{ doc.nom }}</p>
                  <p class="text-xs text-gray-500">Vérifié le {{ doc.date }}</p>
                </div>
              </div>
              <button 
                class="px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors"
                style="background-color: #5B7C99"
              >
                Télécharger
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="p-6 border-t flex justify-between items-center">
        <button
          @click="$emit('suspend', intervenant.id)"
          class="px-6 py-2.5 rounded-lg text-sm font-medium transition-colors border-2"
          :style="{
            borderColor: intervenant.statut === 'actif' ? '#EF4444' : '#4CAF50',
            color: intervenant.statut === 'actif' ? '#EF4444' : '#4CAF50'
          }"
        >
          {{ intervenant.statut === 'actif' ? 'Suspendre le compte' : 'Activer le compte' }}
        </button>
        <button
          @click="$emit('close')"
          class="px-6 py-2.5 rounded-lg text-sm font-medium text-white"
          style="background-color: #5B7C99"
        >
          Fermer
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { 
  X, User, Check, Star, MapPin, Mail, Phone, Clock, 
  FileText, ImageIcon 
} from 'lucide-vue-next'

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  intervenant: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'suspend'])

const activeTab = ref('apropos')

const tabs = [
  { id: 'apropos', label: 'À propos' },
  { id: 'taches', label: 'Tâches/Service' },
  { id: 'avis', label: 'Avis' },
  { id: 'photos', label: 'Photos' },
  { id: 'documents', label: 'Documents' }
]

const documents = [
  { id: 1, nom: "Carte d'identité", date: '2024-01-05' },
  { id: 2, nom: 'Certificat professionnel', date: '2024-01-05' },
  { id: 3, nom: "Attestation d'assurance", date: '2025-12-31' }
]

// Récupérer les avis depuis les props (viennent maintenant de la BD)
const avisList = computed(() => {
  if (!props.intervenant || !props.intervenant.avis) return []
  return props.intervenant.avis
})

const getServiceColor = () => {
  if (!props.intervenant) return '#5B7C99'
  
  let serviceToCheck = props.intervenant.service
  if (props.intervenant.allServices && props.intervenant.allServices.length > 0) {
    serviceToCheck = props.intervenant.allServices[0]
  }
  
  return serviceToCheck === 'Jardinage' ? '#92B08B' : '#5B7C99'
}

const getMainService = () => {
  if (!props.intervenant) return 'service'
  
  if (props.intervenant.allServices && props.intervenant.allServices.length > 0) {
    return props.intervenant.allServices[0].toLowerCase()
  }
  
  return props.intervenant.service?.toLowerCase() || 'service'
}

const getExperience = () => {
  if (!props.intervenant || !props.intervenant.dateInscription) return '145 ans d\'expérience'
  
  const years = new Date().getFullYear() - new Date(props.intervenant.dateInscription).getFullYear()
  return `${years > 0 ? years : '1'} ans d'expérience`
}

const getTachesForService = (service) => {
  if (!props.intervenant || !props.intervenant.taches) return []
  
  // Filtrer les tâches par service si nécessaire
  // Pour l'instant, on retourne toutes les tâches
  return props.intervenant.taches
}

const getRatingPercentage = (star) => {
  if (!props.intervenant || props.intervenant.nbAvis === 0) return 0
  
  // Utiliser les vraies données de répartition depuis la BD
  if (props.intervenant.repartitionNotes && props.intervenant.repartitionNotes[star] !== undefined) {
    return props.intervenant.repartitionNotes[star]
  }
  
  return 0
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' })
}
</script>

<style scoped>
/* Smooth animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.fixed > div {
  animation: fadeIn 0.2s ease-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e0;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a0aec0;
}
</style>
