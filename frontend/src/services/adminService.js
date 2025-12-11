import api from './api';

/**
 * Service pour l'administration
 */
const adminService = {
    // ============== DASHBOARD ==============
    /**
     * Récupérer les statistiques du dashboard
     */
    getStats() {
        return api.get('admin/stats');
    },

    // ============== CLIENTS ==============
    /**
     * Récupérer tous les clients
     */
    getClients(params = {}) {
        return api.get('admin/clients', { params });
    },

    /**
     * Récupérer un client par ID
     */
    getClient(id) {
        return api.get(`clients/${id}`);
    },

    /**
     * Récupérer les détails complets d'un client (avec feedbacks)
     */
    getClientDetails(id) {
        return api.get(`admin/clients/${id}`);
    },

    /**
     * Mettre à jour un client
     */
    updateClient(id, data) {
        return api.put(`clients/${id}`, data);
    },

    /**
     * Suspendre/Activer un client
     */
    toggleClientStatus(id) {
        return api.post(`admin/clients/${id}/toggle-status`);
    },

    /**
     * Récupérer les interventions d'un client
     */
    getClientInterventions(id) {
        return api.get(`clients/${id}/interventions`);
    },

    // ============== INTERVENANTS ==============
    /**
     * Récupérer tous les intervenants
     */
    getIntervenants(params = {}) {
        return api.get('admin/intervenants', { params });
    },

    /**
     * Récupérer un intervenant par ID
     */
    getIntervenant(id) {
        return api.get(`intervenants/${id}`);
    },

    /**
     * Mettre à jour un intervenant
     */
    updateIntervenant(id, data) {
        return api.put(`intervenants/${id}`, data);
    },

    /**
     * Suspendre/Activer un intervenant
     */
    toggleIntervenantStatus(id) {
        return api.post(`admin/intervenants/${id}/toggle-status`);
    },

    /**
     * Récupérer les tâches d'un intervenant
     */
    getIntervenantTaches(id) {
        return api.get(`intervenants/${id}/taches`);
    },

    // ============== DEMANDES ==============
    /**
     * Récupérer les demandes d'inscription
     */
    getDemandes(params = {}) {
        return api.get('admin/demandes', { params });
    },

    /**
     * Approuver une demande
     */
    approveDemande(id) {
        return api.post(`admin/demandes/${id}/approve`);
    },

    /**
     * Rejeter une demande
     */
    rejectDemande(id, reason = '') {
        return api.post(`admin/demandes/${id}/reject`, { reason });
    },

    // ============== SERVICES ==============
    /**
     * Récupérer tous les services
     */
    getServices() {
        return api.get('admin/services');
    },

    /**
     * Créer un nouveau service
     */
    createService(data) {
        return api.post('services', data);
    },

    /**
     * Mettre à jour un service
     */
    updateService(id, data) {
        return api.put(`services/${id}`, data);
    },

    /**
     * Activer/Désactiver un service
     */
    toggleServiceStatus(id) {
        return api.post(`services/${id}/toggle-status`);
    },

    /**
     * Récupérer les tâches d'un service
     */
    getServiceTaches(id) {
        return api.get(`services/${id}/taches`);
    },

    // ============== TÂCHES ==============
    /**
     * Récupérer toutes les tâches
     */
    getTaches(params = {}) {
        return api.get('taches', { params });
    },

    /**
     * Créer une nouvelle tâche
     */
    createTache(data) {
        return api.post('taches', data);
    },

    /**
     * Mettre à jour une tâche
     */
    updateTache(id, data) {
        return api.put(`taches/${id}`, data);
    },

    /**
     * Supprimer une tâche
     */
    deleteTache(id) {
        return api.delete(`taches/${id}`);
    },

    // ============== INTERVENTIONS ==============
    /**
     * Récupérer toutes les interventions
     */
    getInterventions(params = {}) {
        return api.get('interventions', { params });
    },

    /**
     * Récupérer une intervention par ID
     */
    getIntervention(id) {
        return api.get(`interventions/${id}`);
    },

    // ============== RÉCLAMATIONS ==============
    /**
     * Récupérer les réclamations
     */
    getReclamations(params = {}) {
        return api.get('admin/reclamations', { params });
    },

    /**
     * Traiter une réclamation
     */
    handleReclamation(id, action, message = '') {
        return api.post(`admin/reclamations/${id}/handle`, { action, message });
    },

    // ============== HISTORIQUE ==============
    /**
     * Récupérer l'historique des interventions
     */
    getHistorique(params = {}) {
        return api.get('admin/historique', { params });
    },

    // ============== ÉVALUATIONS ==============
    /**
     * Récupérer les évaluations
     */
    getEvaluations(params = {}) {
        return api.get('admin/evaluations', { params });
    }
};

export default adminService;

