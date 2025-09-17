<template>
  <div class="whatsapp-preview-container">
    <!-- Live Preview -->
    <div class="preview-box">
      <h4>Preview WhatsApp Button</h4>
      <div class="preview-frame" :class="position">
        <div 
          class="whatsapp-button-preview"
          :style="{ backgroundColor: color }"
        >
          <i class="fab fa-whatsapp"></i>
          <span class="tooltip-preview">{{ greetingText }}</span>
        </div>
      </div>
    </div>
    
    <!-- Configuration Form -->
    <div class="config-form">
      <div class="form-group">
        <label>Enable WhatsApp Chat</label>
        <input 
          type="checkbox" 
          v-model="enabled"
          @change="updateConfig"
        >
      </div>
      
      <template v-if="enabled">
        <div class="form-group">
          <label>WhatsApp Number</label>
          <input 
            type="text" 
            v-model="phoneNumber"
            placeholder="628123456789"
            @input="updateConfig"
          >
        </div>
        
        <div class="form-group">
          <label>Default Message</label>
          <textarea 
            v-model="message"
            placeholder="Halo {company_name}, saya tertarik..."
            @input="updateConfig"
          ></textarea>
        </div>
        
        <div class="form-group">
          <label>Position</label>
          <select v-model="position" @change="updateConfig">
            <option value="bottom-right">Bottom Right</option>
            <option value="bottom-left">Bottom Left</option>
          </select>
        </div>
        
        <div class="form-group">
          <label>Button Color</label>
          <input 
            type="color" 
            v-model="color"
            @input="updateConfig"
          >
        </div>
        
        <div class="form-group">
          <label>Greeting Text</label>
          <input 
            type="text" 
            v-model="greetingText"
            placeholder="Chat dengan kami!"
            @input="updateConfig"
          >
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: Object,
  companyName: String
})

const emit = defineEmits(['update:modelValue'])

// Reactive data
const enabled = ref(props.modelValue?.whatsapp_enabled || false)
const phoneNumber = ref(props.modelValue?.whatsapp_number || '')
const message = ref(props.modelValue?.whatsapp_message || 'Halo {company_name}, saya tertarik dengan layanan Anda')
const position = ref(props.modelValue?.whatsapp_position || 'bottom-right')
const color = ref(props.modelValue?.whatsapp_color || '#25D366')
const greetingText = ref(props.modelValue?.whatsapp_greeting_text || 'Butuh bantuan? Chat dengan kami!')

// Update parent component
const updateConfig = () => {
  emit('update:modelValue', {
    ...props.modelValue,
    whatsapp_enabled: enabled.value,
    whatsapp_number: phoneNumber.value,
    whatsapp_message: message.value,
    whatsapp_position: position.value,
    whatsapp_color: color.value,
    whatsapp_greeting_text: greetingText.value
  })
}
</script>

<style scoped>
.whatsapp-preview-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin: 1rem 0;
}

.preview-box {
  background: #f8f9fa;
  padding: 1rem;
  border-radius: 8px;
  position: relative;
  height: 200px;
}

.preview-frame {
  position: relative;
  width: 100%;
  height: 150px;
  border: 2px dashed #ddd;
  border-radius: 8px;
}

.whatsapp-button-preview {
  position: absolute;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 24px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  bottom: 10px;
}

.preview-frame.bottom-right .whatsapp-button-preview {
  right: 10px;
}

.preview-frame.bottom-left .whatsapp-button-preview {
  left: 10px;
}

.config-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  font-weight: 600;
  color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
  font-size: 14px;
}

.form-group textarea {
  min-height: 80px;
  resize: vertical;
}
</style>
