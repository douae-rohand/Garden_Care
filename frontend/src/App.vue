<!-- App.vue - Fichier principal avec Admin Dashboard -->
<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Page d'administration -->
    <AdminDashboard 
      v-if="currentPage === 'admin'"
      @logout="handleAdminLogout"
    />

    <!-- Page d'accueil -->
    <div v-else-if="currentPage === 'home'">
      <Header 
        @login-click="showLoginModal = true" 
        @signup-click="showSignupModal = true" 
      />
      <HeroSection @search="handleSearch" />
      <StatsSection />
      <ServicesSection @service-click="handleServiceClick" />
      <TestimonialsSection />
      <Footer />
    </div>

    <!-- Page de détail du service -->
    <ServiceDetailPage
      v-else-if="currentPage === 'service-detail'"
      :service="selectedService"
      @back="handleBack"
      @view-all-intervenants="handleViewAllIntervenants"
      @view-profile="handleViewProfile"
      @task-click="handleTaskClick"
    />

    <!-- Page de tous les intervenants -->
    <AllIntervenantsPage
      v-else-if="currentPage === 'all-intervenants'"
      :service="selectedService"
      @back="handleBackFromAllIntervenants"
      @view-profile="handleViewProfile"
    />

    <!-- Modals -->
    <LoginModal 
      :is-open="showLoginModal" 
      @close="showLoginModal = false"
      @signup-click="handleSwitchToSignup"
      @admin-login="handleAdminLogin"
    />
    
    <SignupModal 
      :is-open="showSignupModal" 
      @close="showSignupModal = false"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Header from './components/Header.vue'
import HeroSection from './components/HeroSection.vue'
import StatsSection from './components/StatsSection.vue'
import ServicesSection from './components/ServicesSection.vue'
import TestimonialsSection from './components/TestimonialsSection.vue'
import Footer from './components/Footer.vue'
import ServiceDetailPage from './components/ServiceDetailPage.vue'
import AllIntervenantsPage from './components/AllIntervenantsPage.vue'
import LoginModal from './components/LoginModal.vue'
import SignupModal from './components/SignupModal.vue'
import AdminDashboard from './components/Admin/AdminDashboard.vue'
import authService from './services/authService'

// État pour gérer la navigation entre les pages
// Valeurs possibles: 'home', 'service-detail', 'all-intervenants', 'admin'
const currentPage = ref('home')
const selectedService = ref(null)
const currentUser = ref(null)

// État pour les modals
const showLoginModal = ref(false)
const showSignupModal = ref(false)

// Vérifier si l'utilisateur est connecté au chargement
onMounted(async () => {
  const token = authService.getToken()
  if (token) {
    try {
      const response = await authService.getCurrentUser()
      currentUser.value = response.data.user
      
      // Si c'est un admin, rediriger vers le dashboard
      if (currentUser.value.admin) {
        currentPage.value = 'admin'
      }
    } catch (error) {
      console.error('Erreur lors de la récupération de l\'utilisateur:', error)
      authService.setAuthToken(null)
    }
  }
  
  // Vérifier l'URL pour accès direct admin
  if (window.location.hash === '#admin' || window.location.pathname.includes('admin')) {
    // Si pas connecté, montrer le modal de login
    if (!currentUser.value?.admin) {
      showLoginModal.value = true
    }
  }
})

const handleSearch = () => {
  console.log('Search clicked')
  // TODO: Implémenter la recherche
}

// Navigation depuis l'accueil vers la page de détail du service
const handleServiceClick = (serviceId) => {
  console.log('Service clicked:', serviceId)
  selectedService.value = serviceId
  currentPage.value = 'service-detail'
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Retour de la page de détail du service vers l'accueil
const handleBack = () => {
  selectedService.value = null
  currentPage.value = 'home'
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Navigation de la page de détail vers la page de tous les intervenants
const handleViewAllIntervenants = () => {
  console.log('View all intervenants for:', selectedService.value)
  currentPage.value = 'all-intervenants'
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Retour de la page de tous les intervenants vers la page de détail du service
const handleBackFromAllIntervenants = () => {
  currentPage.value = 'service-detail'
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Navigation vers le profil d'un intervenant (depuis n'importe quelle page)
const handleViewProfile = (intervenantId) => {
  console.log('View profile:', intervenantId, 'for service:', selectedService.value)
  // TODO: Implémenter la page de profil de l'intervenant
  // currentPage.value = 'intervenant-profile'
}

// Navigation vers la page de réservation d'une tâche
const handleTaskClick = (taskName) => {
  console.log('Task clicked:', taskName, 'for service:', selectedService.value)
  // TODO: Implémenter la page de réservation
  // currentPage.value = 'booking'
}

// Fonction pour basculer de login à signup
const handleSwitchToSignup = () => {
  showLoginModal.value = false
  showSignupModal.value = true
}

// Fonction pour gérer la connexion admin
const handleAdminLogin = (user) => {
  currentUser.value = user
  showLoginModal.value = false
  currentPage.value = 'admin'
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Fonction pour gérer la déconnexion admin
const handleAdminLogout = async () => {
  try {
    await authService.logout()
  } catch (error) {
    console.error('Erreur lors de la déconnexion:', error)
  }
  authService.setAuthToken(null)
  currentUser.value = null
  currentPage.value = 'home'
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<style>
/* Styles globaux si nécessaire */
</style>
