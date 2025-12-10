<script setup>
import { ref, onMounted } from 'vue'
import interventionService from '@/services/interventionService'

const interventions = ref([])
const loading = ref(false)
const error = ref(null)

// Récupérer toutes les interventions au chargement
onMounted(async () => {
  await fetchInterventions()
})

// Fonction pour récupérer les interventions
const fetchInterventions = async () => {
  loading.value = true
  error.value = null
  
  try {
    const response = await interventionService.getAll()
    interventions.value = response.data
  } catch (err) {
    error.value = 'Erreur lors du chargement des interventions'
    console.error(err)
  } finally {
    loading.value = false
  }
}

// Fonction pour créer une intervention
const createIntervention = async (data) => {
  try {
    const response = await interventionService.create(data)
    interventions.value.push(response.data)
    alert('Intervention créée avec succès!')
  } catch (err) {
    error.value = 'Erreur lors de la création'
    console.error(err)
  }
}

// Fonction pour supprimer une intervention
const deleteIntervention = async (id) => {
  if (!confirm('Êtes-vous sûr de vouloir supprimer cette intervention?')) {
    return
  }
  
  try {
    await interventionService.delete(id)
    interventions.value = interventions.value.filter(i => i.id !== id)
    alert('Intervention supprimée avec succès!')
  } catch (err) {
    error.value = 'Erreur lors de la suppression'
    console.error(err)
  }
}
</script>

<template>
  <div class="interventions-container">
    <h1>Liste des Interventions</h1>
    
    <!-- Message d'erreur -->
    <div v-if="error" class="error">{{ error }}</div>
    
    <!-- Indicateur de chargement -->
    <div v-if="loading" class="loading">Chargement...</div>
    
    <!-- Liste des interventions -->
    <div v-else class="interventions-list">
      <div 
        v-for="intervention in interventions" 
        :key="intervention.id"
        class="intervention-card"
      >
        <h3>{{ intervention.tache?.nomTache || 'Tâche non définie' }}</h3>
        <p><strong>Client:</strong> {{ intervention.client?.nom }}</p>
        <p><strong>Intervenant:</strong> {{ intervention.intervenant?.nom }}</p>
        <p><strong>Date:</strong> {{ intervention.dateIntervention }}</p>
        <p><strong>Statut:</strong> {{ intervention.status }}</p>
        <p><strong>Adresse:</strong> {{ intervention.address }}, {{ intervention.ville }}</p>
        
        <button @click="deleteIntervention(intervention.id)" class="btn-delete">
          Supprimer
        </button>
      </div>
      
      <div v-if="interventions.length === 0" class="no-data">
        Aucune intervention trouvée
      </div>
    </div>
  </div>
</template>

<style scoped>
.interventions-container {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

h1 {
  color: #2c3e50;
  margin-bottom: 20px;
}

.error {
  background-color: #fee;
  color: #c33;
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 20px;
}

.loading {
  text-align: center;
  padding: 40px;
  color: #666;
}

.interventions-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.intervention-card {
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: box-shadow 0.3s;
}

.intervention-card:hover {
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.intervention-card h3 {
  margin-top: 0;
  color: #3498db;
}

.intervention-card p {
  margin: 8px 0;
  color: #555;
}

.btn-delete {
  background-color: #e74c3c;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 10px;
}

.btn-delete:hover {
  background-color: #c0392b;
}

.no-data {
  text-align: center;
  padding: 40px;
  color: #999;
  grid-column: 1 / -1;
}
</style>
