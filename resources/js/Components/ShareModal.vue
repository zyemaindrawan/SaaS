<template>
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Share Preview</h3>
      
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Preview Link (expires in 24 hours)
          </label>
          <div class="flex">
            <input
              ref="shareInput"
              :value="shareUrl"
              readonly
              class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 text-sm"
            >
            <button
              @click="copyToClipboard"
              class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-r-md hover:bg-indigo-700"
            >
              {{ copied ? 'Copied!' : 'Copy' }}
            </button>
          </div>
        </div>
        
        <div class="flex justify-end space-x-3">
          <button
            @click="$emit('close')"
            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  websiteContent: Object
})

const copied = ref(false)
const shareInput = ref(null)

const shareUrl = computed(() => {
  // Generate signed URL that expires in 24 hours
  return route('site.public', {
    websiteContent: props.websiteContent.id,
    expires: Math.floor(Date.now() / 1000) + 86400 // 24 hours
  })
})

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(shareUrl.value)
    copied.value = true
    setTimeout(() => {
      copied.value = false
    }, 2000)
  } catch (err) {
    // Fallback for older browsers
    shareInput.value.select()
    document.execCommand('copy')
    copied.value = true
    setTimeout(() => {
      copied.value = false
    }, 2000)
  }
}
</script>
