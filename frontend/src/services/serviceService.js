import api from './api';

/**
 * Service pour gérer les services
 */
const serviceService = {
    /**
     * Récupérer tous les services
     */
    getAll() {
        return api.get('services');
    },

    /**
     * Récupérer un service par ID
     */
    getById(id) {
        return api.get(`services/${id}`);
    },

    /**
     * Récupérer les tâches d'un service
     */
    getTaches(serviceId) {
        return api.get(`services/${serviceId}/taches`);
    },

    /**
     * Créer un nouveau service
     */
    create(data) {
        return api.post('services', data);
    },

    /**
     * Mettre à jour un service
     */
    update(id, data) {
        return api.put(`services/${id}`, data);
    },

    /**
     * Supprimer un service
     */
    delete(id) {
        return api.delete(`services/${id}`);
    }
};

export default serviceService;
