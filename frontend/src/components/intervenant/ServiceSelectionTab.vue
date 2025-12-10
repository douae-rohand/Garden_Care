<template>
  <div class="container">
    <!-- Success Message -->
    <div v-if="showSuccessMessage" class="success-message">
      <div class="success-icon">
        <Check :size="20" />
      </div>
      <div>
        <p class="erfolg-title">Votre demande a été envoyée avec succès !</p>
        <p class="success-subtitle">Elle sera examinée par notre équipe dans les plus brefs délais.</p>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card stat-green">
        <p class="stat-label">Services Actifs</p>
        <p class="stat-value">{{ activeServicesCount }}</p>
      </div>
      <div class="stat-card stat-blue">
        <p class="stat-label">Sous-services Actifs</p>
        <p class="stat-value">{{ activeTasksCount }}</p>
      </div>
    </div>

    <!-- Service Selection -->
    <div class="card">
      <h2>Choisissez vos services</h2>
      <div class="services-grid">
        <div
          v-for="service in services"
          :key="service.id"
          class="service-card"
          :class="{ 'service-active': serviceStates[service.id] }"
          :style="serviceStates[service.id] ? {
            borderColor: service.color,
            backgroundColor: `${service.color}10`
          } : {}"
        >
          <div class="service-header">
            <span class="service-name">{{ service.name }}</span>
            <button
              @click="toggleService(service.id, service.name)"
              class="toggle-switch"
              :style="{ backgroundColor: serviceStates[service.id] ? service.color : '#E0E0E0' }"
            >
              <span
                class="toggle-slider"
                :class="{ 'toggle-slider-active': serviceStates[service.id] }"
              ></span>
            </button>
          </div>
          <p v-if="serviceStates[service.id]" class="service-status">Service activé</p>
        </div>
      </div>
    </div>

    <!-- Task Selection for Active Services -->
    <div v-for="(isActive, serviceId) in serviceStates" :key="serviceId">
      <div v-if="isActive" class="card">
        <h2 :style="{ color: getServiceColor(serviceId) }">
          Sous-services pour {{ getServiceName(serviceId) }}
        </h2>
        <div class="tasks-list">
          <div
            v-for="task in getTasksByService(serviceId)"
            :key="task.id"
            class="task-card"
            :class="{ 'task-active': taskStates[task.id] }"
            :style="taskStates[task.id] ? {
              borderColor: getServiceColor(serviceId),
              backgroundColor: `${getServiceColor(serviceId)}08`
            } : {}"
          >
            <div class="task-header">
              <h3>{{ task.name }}</h3>
              <button
                @click="toggleTask(task.id)"
                class="toggle-switch toggle-switch-sm"
                :style="{ backgroundColor: taskStates[task.id] ? getServiceColor(serviceId) : '#E0E0E0' }"
              >
                <span
                  class="toggle-slider toggle-slider-sm"
                  :class="{ 'toggle-slider-active': taskStates[task.id] }"
                ></span>
              </button>
            </div>

            <!-- Task Configuration (shown when active) -->
            <div v-if="taskStates[task.id]" class="task-config">
              <div class="form-group">
                <label>Votre tarif horaire <span class="required">*</span></label>
                <div class="input-with-suffix">
                  <input
                    v-model="taskPrices[task.id]"
                    type="number"
                    min="1"
                    step="0.5"
                    :placeholder="String(task.baseRate)"
                  />
                  <span class="input-suffix">DH/h</span>
                </div>
              </div>

              <div class="form-group">
                <label>Description de votre présentation</label>
                <textarea
                  v-model="taskDescriptions[task.id]"
                  rows="3"
                  placeholder="Décrivez votre expérience et ce que vous proposez pour ce sous-service..."
                ></textarea>
              </div>

              <div class="form-group">
                <label>Matériaux nécessaires</label>
                <div class="materials-grid">
                  <label
                    v-for="material in getMaterialsByService(serviceId)"
                    :key="material"
                    class="checkbox-label"
                  >
                    <input
                      type="checkbox"
                      :checked="taskMaterials[task.id]?.includes(material)"
                      @change="toggleMaterial(task.id, material)"
                      :style="{ accentColor: getServiceColor(serviceId) }"
                    />
                    <span>{{ material }}</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="activeServicesCount === 0" class="card">
      <div class="empty-state">
        <div class="empty-icon">
          <FileText :size="28" />
        </div>
        <p>Aucun service activé</p>
        <p class="text-sm">Activez vos services pour commencer à recevoir des demandes</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Check, FileText } from 'lucide-vue-next'

const showSuccessMessage = ref(false)

const services = [
  { id: 'menage', name: 'Ménage', color: '#1A5FA3' },
  { id: 'jardinage', name: 'Jardinage', color: '#92B08B' }
]

const tasksByService = {
  menage: [
    { id: 'menage-1', name: 'Ménage résidentiel & régulier', baseRate: 20 },
    { id: 'menage-2', name: 'Nettoyage en profondeur (Deep Cleaning)', baseRate: 24 },
    { id: 'menage-3', name: 'Nettoyage spécial : déménagement & post-travaux', baseRate: 25 },
    { id: 'menage-4', name: 'Lavage vitres & surfaces spécialisées', baseRate: 22 },
    { id: 'menage-5', name: 'Nettoyage mobilier & textiles', baseRate: 23 },
    { id: 'menage-6', name: 'Nettoyage professionnel (bureaux & commerces)', baseRate: 21 }
  ],
  jardinage: [
    { id: 'jardinage-1', name: 'Tonte de pelouse', baseRate: 25 },
    { id: 'jardinage-2', name: 'Taille de haies', baseRate: 28 },
    { id: 'jardinage-3', name: 'Plantation de fleurs', baseRate: 26 },
    { id: 'jardinage-4', name: 'Élagage d\'arbres', baseRate: 30 },
    { id: 'jardinage-5', name: 'Désherbage', baseRate: 24 },
    { id: 'jardinage-6', name: 'Entretien de potager', baseRate: 27 }
  ]
}

const materialsByService: Record<string, string[]> = {
  menage: [
    'Aspirateur',
    'Balai et serpillière',
    'Produits de nettoyage',
    'Chiffons et éponges',
    'Seau',
    'Nettoyeur vapeur'
  ],
  jardinage: [
    'Tondeuse',
    'Taille-haie',
    'Râteau',
    'Pelle et bêche',
    'Arrosoir',
    'Sécateur'
  ]
}

const serviceStates = ref<Record<string, boolean>>({
  menage: false,
  jardinage: false
})

const taskStates = ref<Record<string, boolean>>({})
const taskPrices = ref<Record<string, string>>({})
const taskDescriptions = ref<Record<string, string>>({})
const taskMaterials = ref<Record<string, string[]>>({})

const activeServicesCount = computed(() => {
  return Object.values(serviceStates.value).filter(Boolean).length
})

const activeTasksCount = computed(() => {
  return Object.values(taskStates.value).filter(Boolean).length
})

const toggleService = (serviceId: string, serviceName: string) => {
  serviceStates.value[serviceId] = !serviceStates.value[serviceId]
  
  if (serviceStates.value[serviceId]) {
    showSuccessMessage.value = true
    setTimeout(() => {
      showSuccessMessage.value = false
    }, 4000)
  }
}

const toggleTask = (taskId: string) => {
  taskStates.value[taskId] = !taskStates.value[taskId]
  
  if (!taskStates.value[taskId]) {
    delete taskPrices.value[taskId]
    delete taskDescriptions.value[taskId]
    delete taskMaterials.value[taskId]
  }
}

const toggleMaterial = (taskId: string, material: string) => {
  if (!taskMaterials.value[taskId]) {
    taskMaterials.value[taskId] = []
  }
  
  const index = taskMaterials.value[taskId].indexOf(material)
  if (index > -1) {
    taskMaterials.value[taskId].splice(index, 1)
  } else {
    taskMaterials.value[taskId].push(material)
  }
}

const getServiceColor = (serviceId: string) => {
  return services.find(s => s.id === serviceId)?.color || '#92B08B'
}

const getServiceName = (serviceId: string) => {
  return services.find(s => s.id === serviceId)?.name || ''
}

const getTasksByService = (serviceId: string) => {
  return tasksByService[serviceId as keyof typeof tasksByService] || []
}

const getMaterialsByService = (serviceId: string) => {
  return materialsByService[serviceId] || []
}
</script>

<style scoped>
.container {
  max-width: 80rem;
}

.success-message {
  display: flex;
  align-items: center;
  gap: var(--spacing-3);
  padding: var(--spacing-4);
  background: var(--color-white);
  box-shadow: var(--shadow-md);
  border-left: 4px solid var(--color-primary-green);
  border-radius: var(--radius-lg);
  margin-bottom: var(--spacing-4);
}

.success-icon {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  background-color: var(--color-light-green);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-primary-green);
}

.success-title {
  font-size: 0.875rem;
  color: var(--color-dark);
  margin: 0;
}

.success-subtitle {
  font-size: 0.75rem;
  color: var(--color-gray-500);
  margin: 0;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--spacing-4);
  margin-bottom: var(--spacing-6);
}

.stat-card {
  background: var(--color-white);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  padding: var(--spacing-4);
  border-left: 4px solid;
}

.stat-green {
  border-left-color: var(--color-primary-green);
}

.stat-blue {
  border-left-color: var(--color-blue);
}

.stat-label {
  font-size: 0.75rem;
  color: var(--color-gray-600);
  margin: 0 0 var(--spacing-1) 0;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
}

.stat-green .stat-value {
  color: var(--color-primary-green);
}

.stat-blue .stat-value {
  color: var(--color-blue);
}

.card {
  background: var(--color-white);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
  padding: var(--spacing-6);
  margin-bottom: var(--spacing-6);
}

.card h2 {
  color: var(--color-dark);
  margin: 0 0 var(--spacing-4) 0;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--spacing-4);
}

.service-card {
  padding: var(--spacing-4);
  border: 2px solid var(--color-gray-200);
  border-radius: var(--radius-lg);
  transition: all 0.3s;
}

.service-active {
  /* Dynamic styles applied inline */
}

.service-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.service-name {
  font-size: 1.125rem;
  color: var(--color-dark);
}

.service-status {
  font-size: 0.75rem;
  color: var(--color-gray-600);
  margin: var(--spacing-2) 0 0 0;
}

.toggle-switch {
  position: relative;
  width: 3.5rem;
  height: 1.75rem;
  border-radius: 9999px;
  transition: background-color 0.3s;
}

.toggle-switch-sm {
  width: 3rem;
  height: 1.5rem;
}

.toggle-slider {
  position: absolute;
  width: 1.25rem;
  height: 1.25rem;
  background: var(--color-white);
  border-radius: 50%;
  top: 0.25rem;
  left: 0.25rem;
  box-shadow: var(--shadow-sm);
  transition: transform 0.3s;
}

.toggle-slider-sm {
  width: 1rem;
  height: 1rem;
}

.toggle-slider-active {
  transform: translateX(1.75rem);
}

.toggle-slider-sm.toggle-slider-active {
  transform: translateX(1.5rem);
}

.tasks-list {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-3);
}

.task-card {
  padding: var(--spacing-4);
  border: 2px solid var(--color-gray-200);
  border-radius: var(--radius-lg);
  transition: all 0.3s;
}

.task-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: var(--spacing-4);
}

.task-header h3 {
  font-size: 0.875rem;
  color: var(--color-dark);
  margin: 0;
  flex: 1;
}

.task-config {
  margin-top: var(--spacing-4);
  padding-top: var(--spacing-4);
  border-top: 1px solid var(--color-gray-200);
}

.form-group {
  margin-bottom: var(--spacing-4);
}

.form-group:last-child {
  margin-bottom: 0;
}

.form-group label {
  display: block;
  font-size: 0.75rem;
  color: var(--color-gray-600);
  margin-bottom: var(--spacing-2);
}

.required {
  color: #E8793F;
}

.input-with-suffix {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
}

.input-with-suffix input {
  flex: 1;
}

.input-suffix {
  font-size: 0.875rem;
  color: var(--color-gray-600);
}

.materials-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: var(--spacing-2);
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
  padding: var(--spacing-2);
  border-radius: var(--radius-md);
  cursor: pointer;
  transition: background-color 0.2s;
  font-size: 0.875rem;
}

.checkbox-label:hover {
  background-color: var(--color-gray-50);
}

.checkbox-label input {
  width: 1rem;
  height: 1rem;
  cursor: pointer;
}

.empty-state {
  text-align: center;
  padding: var(--spacing-12) var(--spacing-6);
  color: var(--color-gray-500);
}

.empty-icon {
  width: 4rem;
  height: 4rem;
  border-radius: 50%;
  background-color: var(--color-light-yellow);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto var(--spacing-4);
  color: var(--color-primary-green);
}

@media (max-width: 768px) {
  .stats-grid,
  .services-grid,
  .materials-grid {
    grid-template-columns: 1fr;
  }
}
</style>
