<template>
  <div>
    <!-- Back Button -->
    <button
      @click="$emit('back')"
      class="flex items-center gap-2 text-sm transition-colors hover:underline"
      style="color: #5B7C99"
    >
      ← Retour au tableau de bord
    </button>
  </div>
</template>

<script setup>
defineEmits(['back'])
</script>
