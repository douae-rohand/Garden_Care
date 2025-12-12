<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="bg-blue-600 text-white p-6 rounded-t-lg flex items-center justify-between">
        <div class="flex items-center gap-4">
          <img
            :src="intervenant.image"
            :alt="intervenant.name"
            class="w-16 h-16 rounded-full object-cover border-2 border-white"
          />
          <div>
            <h2 class="text-2xl font-bold">Choisir une tâche</h2>
            <p class="text-blue-100">pour {{ intervenant.name }}</p>
          </div>
        </div>
        <button
          @click="$emit('close')"
          class="text-white hover:text-gray-200 transition-colors"
        >
          <X :size="24" />
        </button>
      </div>

      <!-- Progress Steps -->
      <div class="p-6 border-b">
        <div class="flex items-center justify-center gap-4">
          <div class="flex items-center">
            <div
              class="w-10 h-10 rounded-full flex items-center justify-center text-white"
              :class="currentStep >= 1 ? 'bg-green-500' : 'bg-gray-300'"
            >
              <Check v-if="currentStep > 1" :size="20" />
              <Calendar v-else :size="20" />
            </div>
            <span class="ml-2 font-medium" :class="currentStep >= 1 ? 'text-green-600' : 'text-gray-400'">
              Date
            </span>
          </div>
          <div class="w-12 h-1" :class="currentStep >= 2 ? 'bg-green-500' : 'bg-gray-300'"></div>
          <div class="flex items-center">
            <div
              class="w-10 h-10 rounded-full flex items-center justify-center text-white"
              :class="currentStep >= 2 ? 'bg-green-500' : currentStep === 2 ? 'bg-blue-500' : 'bg-gray-300'"
            >
              <Check v-if="currentStep > 2" :size="20" />
              <Clock v-else :size="20" />
            </div>
            <span class="ml-2 font-medium" :class="currentStep >= 2 ? 'text-green-600' : currentStep === 2 ? 'text-blue-600' : 'text-gray-400'">
              Horaire
            </span>
          </div>
          <div class="w-12 h-1" :class="currentStep >= 3 ? 'bg-green-500' : 'bg-gray-300'"></div>
          <div class="flex items-center">
            <div
              class="w-10 h-10 rounded-full flex items-center justify-center text-white"
              :class="currentStep >= 3 ? 'bg-blue-500' : 'bg-gray-300'"
            >
              <FileText :size="20" />
            </div>
            <span class="ml-2 font-medium" :class="currentStep >= 3 ? 'text-blue-600' : 'text-gray-400'">
              Détails
            </span>
          </div>
        </div>
      </div>

      <!-- Intervenant Info Bar -->
      <div class="bg-green-50 p-4 flex items-center gap-4 border-b">
        <img
          :src="intervenant.image"
          :alt="intervenant.name"
          class="w-12 h-12 rounded-full object-cover border-2"
          style="border-color: #92b08b"
        />
        <div class="flex-1">
          <h3 class="font-bold text-lg" style="color: #2f4f4f">{{ intervenant.name }}</h3>
          <div class="flex items-center gap-4 mt-1">
            <div class="flex items-center gap-1">
              <Star :size="16" fill="#FEE347" color="#FEE347" />
              <span class="font-semibold">{{ intervenant.averageRating || 'N/A' }}</span>
            </div>
            <span class="px-3 py-1 rounded-full text-sm text-white" style="background-color: #1a5fa3">
              {{ intervenant.hourlyRate ? `${intervenant.hourlyRate} DH/h` : 'N/A' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Step Content -->
      <div class="p-6">
        <!-- Step 1: Choose Service -->
        <div v-if="currentStep === 1" class="space-y-6">
          <h3 class="text-xl font-bold mb-4" style="color: #2f4f4f">Étape 1 : Choisissez le service</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="service in services"
              :key="service.id"
              @click="selectService(service)"
              class="p-4 border-2 rounded-lg cursor-pointer transition-all hover:shadow-md"
              :class="selectedService?.id === service.id ? 'border-green-500 bg-green-50' : 'border-gray-200'"
            >
              <h4 class="font-bold text-lg mb-2" :class="selectedService?.id === service.id ? 'text-green-600' : 'text-gray-700'">
                {{ service.nom_service }}
              </h4>
              <p class="text-sm text-gray-600">
                {{ service.taches_count || 0 }} tâches disponibles
              </p>
            </div>
          </div>
        </div>

        <!-- Step 2: Choose Task -->
        <div v-if="currentStep === 2" class="space-y-6">
          <h3 class="text-xl font-bold mb-4" style="color: #2f4f4f">Étape 2 : Choisissez la tâche</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button
              v-for="task in tasks"
              :key="task.id"
              @click="selectTask(task)"
              class="p-4 border-2 rounded-lg transition-all hover:shadow-md text-left"
              :class="selectedTask?.id === task.id ? 'border-green-500 bg-green-50' : 'border-gray-200'"
            >
              <span class="font-medium" :class="selectedTask?.id === task.id ? 'text-green-600' : 'text-gray-700'">
                {{ task.nom_tache }}
              </span>
            </button>
          </div>
        </div>

        <!-- Step 3: Select Date -->
        <div v-if="currentStep === 3" class="space-y-6">
          <h3 class="text-xl font-bold mb-4" style="color: #2f4f4f">Sélectionnez une date</h3>
          <div class="bg-white rounded-lg p-6 border-2 border-gray-200">
            <div class="flex items-center gap-2 mb-4">
              <Calendar :size="20" style="color: #1a5fa3" />
              <span class="font-medium">Sélectionnez une date</span>
            </div>
            <input
              v-model="bookingData.date"
              type="date"
              :min="minDate"
              class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              @change="loadTimeSlots"
            />
          </div>
        </div>

        <!-- Step 4: Select Time Slot -->
        <div v-if="currentStep === 4" class="space-y-6">
          <h3 class="text-xl font-bold mb-4" style="color: #2f4f4f">
            Créneaux disponibles pour le {{ formatDate(bookingData.date) }}
          </h3>
          <p class="text-gray-600 mb-4">{{ availableSlots.length }} créneaux disponibles</p>
          <div class="grid grid-cols-3 md:grid-cols-4 gap-4">
            <button
              v-for="slot in timeSlots"
              :key="slot.time"
              @click="selectTimeSlot(slot)"
              :disabled="!slot.available"
              class="p-4 border-2 rounded-lg transition-all text-center relative"
              :class="
                slot.available
                  ? selectedTimeSlot === slot.time
                    ? 'border-blue-500 bg-blue-50'
                    : 'border-gray-200 hover:border-green-500 hover:bg-green-50'
                  : 'border-red-200 bg-red-50 opacity-50 cursor-not-allowed'
              "
            >
              <span class="font-semibold">{{ slot.time }}</span>
              <X v-if="!slot.available" :size="16" class="absolute top-2 right-2 text-red-500" />
              <Check v-if="slot.available && selectedTimeSlot === slot.time" :size="16" class="absolute top-2 right-2 text-blue-500" />
              <span v-if="!slot.available" class="block text-xs text-red-600 mt-1">Indisponible</span>
            </button>
          </div>
          <div v-if="selectedTimeSlot" class="bg-green-50 border-2 border-green-300 rounded-lg p-4">
            <div class="flex items-center gap-2">
              <Check :size="20" class="text-green-600" />
              <span class="font-semibold">Créneau sélectionné: {{ selectedTimeSlot }} - Durée estimée:</span>
            </div>
            <p class="text-sm text-gray-600 mt-1">
              L'intervenant confirmera la durée exacte après analyse de votre demande
            </p>
          </div>
        </div>

        <!-- Step 5: Details -->
        <div v-if="currentStep === 5" class="space-y-6">
          <!-- Urgency Level -->
          <div class="bg-white rounded-lg p-6 border-2 border-gray-200">
            <h4 class="text-lg font-bold mb-4 flex items-center gap-2" style="color: #2f4f4f">
              <AlertCircle :size="20" class="text-orange-500" />
              Niveau d'urgence
            </h4>
            <div class="flex gap-4">
              <button
                v-for="urgency in urgencyLevels"
                :key="urgency.value"
                @click="bookingData.urgency = urgency.value"
                class="flex-1 p-4 border-2 rounded-lg transition-all"
                :class="
                  bookingData.urgency === urgency.value
                    ? urgency.class
                    : 'border-gray-200 bg-white'
                "
              >
                <component :is="urgency.icon" :size="20" class="mb-2" :class="urgency.iconClass" />
                <span class="font-medium">{{ urgency.label }}</span>
              </button>
            </div>
          </div>

          <!-- Address -->
          <div class="bg-white rounded-lg p-6 border-2 border-gray-200">
            <h4 class="text-lg font-bold mb-4 flex items-center gap-2" style="color: #2f4f4f">
              <MapPin :size="20" style="color: #92b08b" />
              Adresse du service
            </h4>
            <div class="space-y-4">
              <div>
                <input
                  v-model="bookingData.address"
                  type="text"
                  placeholder="Numéro, rue, immeuble..."
                  class="w-full px-4 py-2 border-2 rounded-lg focus:ring-2 focus:ring-green-500"
                  style="border-color: #92b08b"
                />
              </div>
              <div>
                <label class="block mb-2 font-medium">Quartier</label>
                <select
                  v-model="bookingData.quartier"
                  class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                >
                  <option value="">Sélectionnez un quartier</option>
                  <option value="Tetouan Centre">Tetouan Centre</option>
                  <option value="Martil">Martil</option>
                  <option value="Fnideq">Fnideq</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Specific Information -->
          <div class="bg-white rounded-lg p-6 border-2 border-gray-200">
            <h4 class="text-lg font-bold mb-4" style="color: #2f4f4f">Informations spécifiques</h4>
            <div>
              <label class="block mb-2 font-medium">Surface approximative (m²) *</label>
              <input
                v-model.number="bookingData.surface"
                type="number"
                placeholder="Ex: 50"
                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
              />
            </div>
          </div>

          <!-- Materials -->
          <div class="bg-white rounded-lg p-6 border-2 border-gray-200">
            <h4 class="text-lg font-bold mb-4 flex items-center gap-2" style="color: #2f4f4f">
              <Package :size="20" style="color: #7c3aed" />
              Matériel disponible
            </h4>
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mb-4">
              <div class="flex items-start gap-2">
                <Info :size="20" class="text-blue-600 mt-0.5" />
                <div class="text-sm">
                  <p class="font-medium mb-1">Cochez le matériel que vous possédez déjà</p>
                  <p>Le matériel non coché sera fourni par l'intervenant avec un coût supplémentaire</p>
                </div>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <label
                v-for="material in materials"
                :key="material.id"
                class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50"
              >
                <input
                  v-model="bookingData.materials"
                  type="checkbox"
                  :value="material.id"
                  class="w-5 h-5"
                />
                <span class="flex-1">{{ material.nom_materiel }}</span>
                <span v-if="material.cost" class="px-2 py-1 rounded-full text-xs bg-orange-100 text-orange-700">
                  +{{ material.cost }} DH
                </span>
              </label>
            </div>
            <div class="mt-4 flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <span>Matériel que vous fournissez:</span>
              <span class="flex items-center gap-2">
                <Check :size="16" class="text-green-600" />
                <span class="font-semibold">{{ bookingData.materials.length }}/{{ materials.length }}</span>
              </span>
            </div>
            <div class="mt-2 flex items-center justify-between p-3 bg-orange-50 rounded-lg">
              <span>Coût matériel à fournir:</span>
              <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 font-semibold">
                +{{ materialsCost }} DH
              </span>
            </div>
          </div>

          <!-- Description -->
          <div class="bg-white rounded-lg p-6 border-2 border-gray-200">
            <h4 class="text-lg font-bold mb-4 flex items-center gap-2" style="color: #2f4f4f">
              <FileText :size="20" class="text-yellow-500" />
              Description détaillée *
            </h4>
            <textarea
              v-model="bookingData.description"
              rows="4"
              placeholder="Décrivez en détail votre besoin, l'état actuel, les spécificités à prendre en compte..."
              class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
            ></textarea>
            <div class="mt-2 flex items-start gap-2 text-sm text-gray-600">
              <Info :size="16" class="mt-0.5" />
              <span>Plus vous donnez de détails, mieux l'intervenant pourra estimer le temps nécessaire.</span>
            </div>
          </div>

          <!-- Photos -->
          <div class="bg-white rounded-lg p-6 border-2 border-gray-200">
            <h4 class="text-lg font-bold mb-4 flex items-center gap-2" style="color: #2f4f4f">
              <Camera :size="20" class="text-blue-500" />
              Photos du site (optionnel)
            </h4>
            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-4">
              <div class="flex items-start gap-2">
                <Info :size="20" class="text-green-600 mt-0.5" />
                <p class="text-sm">
                  Ajoutez des photos pour aider l'intervenant à mieux comprendre votre besoin
                  Ex: état actuel du jardin, zones à nettoyer, problèmes spécifiques...
                </p>
              </div>
            </div>
            <div
              @click="triggerFileInput"
              @drop.prevent="handleFileDrop"
              @dragover.prevent
              class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center cursor-pointer hover:border-green-500 transition-colors"
            >
              <input
                ref="fileInput"
                type="file"
                multiple
                accept="image/*"
                @change="handleFileSelect"
                class="hidden"
              />
              <Upload :size="48" class="mx-auto mb-4 text-gray-400" />
              <p class="text-gray-600">
                Cliquez pour ajouter des photos PNG, JPG jusqu'à 10MB (max 5 photos)
              </p>
            </div>
            <div v-if="bookingData.photos.length > 0" class="mt-4 grid grid-cols-5 gap-2">
              <div
                v-for="(photo, index) in bookingData.photos"
                :key="index"
                class="relative"
              >
                <img :src="photo.preview" alt="Preview" class="w-full h-24 object-cover rounded-lg" />
                <button
                  @click="removePhoto(index)"
                  class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1"
                >
                  <X :size="12" />
                </button>
              </div>
            </div>
          </div>

          <!-- Cost Estimation -->
          <div class="bg-blue-50 rounded-lg p-6 border-2 border-blue-200">
            <h4 class="text-lg font-bold mb-4 flex items-center gap-2" style="color: #2f4f4f">
              <DollarSign :size="20" class="text-orange-500" />
              Estimation du coût
            </h4>
            <div class="space-y-3">
              <div class="flex justify-between items-center">
                <span>Tarif horaire:</span>
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">
                  {{ intervenant.hourlyRate || 'N/A' }} DH/h
                </span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-600">Durée et coût final:</span>
                <span class="text-gray-600">À confirmer par l'intervenant</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-orange-600">Coût matériel:</span>
                <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 font-semibold">
                  +{{ materialsCost }} DH
                </span>
              </div>
            </div>
            <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4">
              <div class="flex items-start gap-2">
                <AlertCircle :size="20" class="text-yellow-600 mt-0.5" />
                <p class="text-sm text-gray-700">
                  La durée estimée et le coût final de la main d'œuvre seront confirmés par l'intervenant après analyse de votre demande.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer Buttons -->
      <div class="p-6 border-t bg-gray-50 flex justify-between">
        <button
          v-if="currentStep > 1"
          @click="previousStep"
          class="px-6 py-3 rounded-lg bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition-all flex items-center gap-2"
        >
          <ArrowLeft :size="20" />
          Retour
        </button>
        <div v-else></div>
        <button
          @click="nextStep"
          :disabled="!canProceed"
          class="px-6 py-3 rounded-lg text-white font-semibold transition-all flex items-center gap-2"
          :class="canProceed ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-400 cursor-not-allowed'"
        >
          {{ currentStep === 5 ? 'Envoyer la demande' : 'Continuer →' }}
          <ArrowRight v-if="currentStep < 5" :size="20" />
          <Check v-else :size="20" />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {
  X, Calendar, Clock, FileText, Check, Star, MapPin, AlertCircle,
  Package, Info, Camera, Upload, DollarSign, ArrowLeft, ArrowRight
} from 'lucide-vue-next';
import bookingService from '@/services/bookingService';
import api from '@/services/api';

export default {
  name: 'BookingModal',
  components: {
    X, Calendar, Clock, FileText, Check, Star, MapPin, AlertCircle,
    Package, Info, Camera, Upload, DollarSign, ArrowLeft, ArrowRight
  },
  props: {
    intervenant: {
      type: Object,
      required: true
    },
    clientId: {
      type: Number,
      required: true
    }
  },
  emits: ['close', 'success'],
  data() {
    return {
      currentStep: 1,
      services: [],
      tasks: [],
      selectedService: null,
      selectedTask: null,
      bookingData: {
        date: '',
        time: '',
        urgency: 'normal',
        address: '',
        quartier: '',
        surface: null,
        materials: [],
        description: '',
        photos: []
      },
      timeSlots: [],
      availableSlots: [],
      selectedTimeSlot: null,
      materials: [],
      urgencyLevels: [
        {
          value: 'normal',
          label: 'Normal',
          icon: 'Clock',
          class: 'border-green-500 bg-green-50',
          iconClass: 'text-green-600'
        },
        {
          value: 'urgent',
          label: 'Urgent (24-48h)',
          icon: 'AlertCircle',
          class: 'border-orange-500 bg-orange-50',
          iconClass: 'text-orange-600'
        },
        {
          value: 'emergency',
          label: 'Urgence (Aujourd\'hui)',
          icon: 'AlertCircle',
          class: 'border-red-500 bg-red-50',
          iconClass: 'text-red-600'
        }
      ],
      loading: false
    };
  },
  computed: {
    minDate() {
      const today = new Date();
      return today.toISOString().split('T')[0];
    },
    canProceed() {
      switch (this.currentStep) {
        case 1:
          return this.selectedService !== null;
        case 2:
          return this.selectedTask !== null;
        case 3:
          return this.bookingData.date !== '';
        case 4:
          return this.selectedTimeSlot !== null;
        case 5:
          return this.bookingData.address !== '' && 
                 this.bookingData.quartier !== '' && 
                 this.bookingData.surface !== null && 
                 this.bookingData.description !== '';
        default:
          return false;
      }
    },
    materialsCost() {
      const selectedMaterialIds = this.bookingData.materials;
      return this.materials
        .filter(m => !selectedMaterialIds.includes(m.id))
        .reduce((sum, m) => sum + (m.cost || 0), 0);
    }
  },
  mounted() {
    this.loadServices();
    this.loadMaterials();
  },
  methods: {
    async loadServices() {
      try {
        const response = await bookingService.getIntervenantServices(this.intervenant.id);
        this.services = response.data.data || response.data || [];
        console.log('Loaded services:', this.services); // Debug
      } catch (error) {
        console.error('Error loading services:', error);
        // Fallback: show default services if API fails
        this.services = [
          { id: 1, nom_service: 'Jardinage', taches_count: 3 },
          { id: 2, nom_service: 'Ménage', taches_count: 2 }
        ];
      }
    },
    async selectService(service) {
      this.selectedService = service;
      try {
        const response = await bookingService.getServiceTaches(service.id, this.intervenant.id);
        this.tasks = response.data.data || response.data || [];
        this.currentStep = 2;
      } catch (error) {
        console.error('Error loading tasks:', error);
      }
    },
    selectTask(task) {
      this.selectedTask = task;
    },
    async loadTimeSlots() {
      if (!this.bookingData.date) return;
      
      try {
        const response = await bookingService.getIntervenantDisponibilites(
          this.intervenant.id,
          this.bookingData.date
        );
        // Generate time slots from 08:00 to 18:00
        const allSlots = [];
        for (let hour = 8; hour <= 18; hour++) {
          const time = `${hour.toString().padStart(2, '0')}:00`;
          const isAvailable = this.checkSlotAvailability(time, response.data);
          allSlots.push({ time, available: isAvailable });
        }
        this.timeSlots = allSlots;
        this.availableSlots = allSlots.filter(s => s.available);
      } catch (error) {
        console.error('Error loading time slots:', error);
        // Default slots if API fails
        this.generateDefaultSlots();
      }
    },
    checkSlotAvailability(time, disponibilites) {
      // Check if time slot is available based on disponibilites
      if (!disponibilites || disponibilites.length === 0) return true;
      // Implement logic based on your disponibilite structure
      return true; // Placeholder
    },
    generateDefaultSlots() {
      const slots = [];
      for (let hour = 8; hour <= 18; hour++) {
        slots.push({
          time: `${hour.toString().padStart(2, '0')}:00`,
          available: Math.random() > 0.3 // Random for demo
        });
      }
      this.timeSlots = slots;
      this.availableSlots = slots.filter(s => s.available);
    },
    selectTimeSlot(slot) {
      if (slot.available) {
        this.selectedTimeSlot = slot.time;
        this.bookingData.time = slot.time;
      }
    },
    async loadMaterials() {
      try {
        // Load materials for the selected task
        const response = await api.get(`taches/${this.selectedTask?.id}/materiels`);
        this.materials = response.data.data || response.data || [];
      } catch (error) {
        console.error('Error loading materials:', error);
        // Default materials for demo
        this.materials = [
          { id: 1, nom_materiel: 'Taille-haie électrique ou thermique', cost: 0 },
          { id: 2, nom_materiel: 'Échelle', cost: 20 },
          { id: 3, nom_materiel: 'Bâche de protection', cost: 0 },
          { id: 4, nom_materiel: 'Gants de protection', cost: 5 },
          { id: 5, nom_materiel: 'Lunettes de protection', cost: 5 }
        ];
      }
    },
    triggerFileInput() {
      this.$refs.fileInput?.click();
    },
    handleFileSelect(event) {
      const files = Array.from(event.target.files).slice(0, 5);
      files.forEach(file => {
        if (file.size <= 10 * 1024 * 1024) { // 10MB
          const reader = new FileReader();
          reader.onload = (e) => {
            this.bookingData.photos.push({
              file,
              preview: e.target.result
            });
          };
          reader.readAsDataURL(file);
        }
      });
    },
    handleFileDrop(event) {
      const files = Array.from(event.dataTransfer.files).slice(0, 5);
      files.forEach(file => {
        if (file.type.startsWith('image/') && file.size <= 10 * 1024 * 1024) {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.bookingData.photos.push({
              file,
              preview: e.target.result
            });
          };
          reader.readAsDataURL(file);
        }
      });
    },
    removePhoto(index) {
      this.bookingData.photos.splice(index, 1);
    },
    nextStep() {
      if (!this.canProceed) return;
      
      if (this.currentStep === 2 && this.selectedTask) {
        this.loadMaterials();
      }
      
      if (this.currentStep === 3) {
        this.loadTimeSlots();
        this.currentStep = 4;
        return;
      }
      
      if (this.currentStep === 5) {
        this.submitBooking();
        return;
      }
      
      this.currentStep++;
    },
    previousStep() {
      if (this.currentStep > 1) {
        this.currentStep--;
      }
    },
    async submitBooking() {
      this.loading = true;
      try {
        const formData = new FormData();
        formData.append('clientId', this.clientId);
        formData.append('intervenantId', this.intervenant.id);
        formData.append('tacheId', this.selectedTask.id);
        formData.append('dateIntervention', this.bookingData.date);
        formData.append('address', this.bookingData.address);
        formData.append('ville', this.bookingData.quartier);
        formData.append('status', 'en_attente');
        formData.append('description', this.bookingData.description || '');
        
        // Add surface if provided
        if (this.bookingData.surface) {
          formData.append('surface', this.bookingData.surface);
        }
        
        // Add photos
        this.bookingData.photos.forEach((photo) => {
          formData.append('photos[]', photo.file);
        });

        console.log('Submitting booking with data:', {
          clientId: this.clientId,
          intervenantId: this.intervenant.id,
          tacheId: this.selectedTask.id,
          date: this.bookingData.date,
          address: this.bookingData.address,
          ville: this.bookingData.quartier
        });

        const response = await bookingService.createIntervention(formData);
        console.log('Booking response:', response);
        
        this.$emit('success');
        alert('Demande de service envoyée avec succès !');
      } catch (error) {
        console.error('Error submitting booking:', error);
        console.error('Error response:', error.response);
        const errorMessage = error.response?.data?.message || 
                           error.response?.data?.error || 
                           error.message || 
                           'Erreur lors de l\'envoi de la demande. Veuillez réessayer.';
        alert(errorMessage);
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    }
  },
  watch: {
    selectedTask() {
      if (this.selectedTask) {
        this.loadMaterials();
      }
    }
  }
};
</script>

<style scoped>
/* Additional styles if needed */
</style>

