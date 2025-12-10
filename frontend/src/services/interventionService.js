import api from './api';

/**
 * Service pour gérer les interventions
 */
const interventionService = {
  /**
   * Récupérer toutes les interventions
   */
  getAll() {
    return api.get('interventions');
  },

  /**
   * Récupérer une intervention par ID
   */
  getById(id) {
    return api.get(`interventions/${id}`);
  },

  /**
   * Créer une nouvelle intervention
   */
  create(data) {
    return api.post('interventions', data);
  },

  /**
   * Mettre à jour une intervention
   */
  update(id, data) {
    return api.put(`interventions/${id}`, data);
  },

  /**
   * Supprimer une intervention
   */
  delete(id) {
    return api.delete(`interventions/${id}`);
  },

  /**
   * Récupérer les interventions à venir
   */
  getUpcoming() {
    return api.get('interventions?filter=upcoming');
  },

  /**
   * Récupérer les interventions terminées
   */
  getCompleted() {
    return api.get('interventions?filter=completed');
  },

  /**
   * Ajouter une photo à une intervention
   */
  addPhoto(interventionId, formData) {
    return api.post(`interventions/${interventionId}/photos`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  }
};

export default interventionService;
