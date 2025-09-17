<template>
  <AppLayout>
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center space-x-4">
            <Link href="/dashboard" class="text-gray-500 hover:text-gray-700">
              <ArrowLeftIcon class="w-5 h-5" />
            </Link>
            <div>
              <h1 class="text-lg font-semibold text-gray-900">
                {{ websiteContent.website_name }}
              </h1>
              <p class="text-sm text-gray-500">Preview Mode</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-3">
            <!-- Responsive Toggle -->
            <div class="flex items-center space-x-1 bg-gray-100 rounded-lg p-1">
              <button
                v-for="device in devices"
                :key="device.name"
                @click="currentDevice = device"
                :class="[
                  'px-3 py-1 rounded-md text-sm font-medium transition-colors',
                  currentDevice.name === device.name
                    ? 'bg-white text-gray-900 shadow-sm'
                    : 'text-gray-500 hover:text-gray-900'
                ]"
              >
                <component :is="device.icon" class="w-4 h-4" />
              </button>
            </div>
            
            <!-- Actions -->
            <button
              @click="refreshPreview"
              :disabled="isRefreshing"
              class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
              <ArrowPathIcon :class="['w-4 h-4 mr-2', { 'animate-spin': isRefreshing }]" />
              Refresh
            </button>
            
            <Link
              v-if="canEdit"
              :href="editUrl"
              class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
            >
              <PencilIcon class="w-4 h-4 mr-2" />
              Edit
            </Link>
            
            <button
              @click="sharePreview"
              class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
              <ShareIcon class="w-4 h-4 mr-2" />
              Share
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Preview Container -->
    <div class="flex-1 bg-gray-100 p-4">
      <div class="max-w-7xl mx-auto">
        <!-- Device Frame -->
        <div class="bg-gray-800 rounded-lg p-4 shadow-2xl">
          <div class="bg-white rounded-md overflow-hidden shadow-lg" :style="deviceStyle">
            <!-- Loading Overlay -->
            <div
              v-if="isLoading"
              class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10"
            >
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            </div>
            
            <!-- Preview iframe -->
            <iframe
              ref="previewFrame"
              :src="previewUrl + '?t=' + refreshTimestamp"
              class="w-full h-full border-0"
              :style="{ height: currentDevice.height + 'px' }"
              @load="handleFrameLoad"
            ></iframe>
          </div>
        </div>
        
        <!-- Preview Info -->
        <div class="mt-4 bg-white rounded-lg p-4 shadow">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Template</label>
              <p class="mt-1 text-sm text-gray-900">{{ template.name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <span :class="statusClasses">{{ websiteContent.status }}</span>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Last Updated</label>
              <p class="mt-1 text-sm text-gray-900">{{ formattedDate }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Share Modal -->
    <ShareModal
      v-if="showShareModal"
      :website-content="websiteContent"
      @close="showShareModal = false"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ShareModal from '@/Components/ShareModal.vue'
import {
  ArrowLeftIcon,
  ArrowPathIcon,
  PencilIcon,
  ShareIcon,
  ComputerDesktopIcon,
  DeviceTabletIcon,
  DevicePhoneMobileIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  websiteContent: Object,
  template: Object,
  config: Object,
  processedContent: Object,
  previewUrl: String,
  editUrl: String,
  canEdit: Boolean,
  slug: String
})

// Reactive data
const previewFrame = ref(null)
const isLoading = ref(true)
const isRefreshing = ref(false)
const refreshTimestamp = ref(Date.now())
const showShareModal = ref(false)
const currentDevice = ref({ name: 'desktop', width: '100%', height: 800, icon: ComputerDesktopIcon })

// Device options
const devices = [
  { name: 'desktop', width: '100%', height: 800, icon: ComputerDesktopIcon },
  { name: 'tablet', width: '768px', height: 1024, icon: DeviceTabletIcon },
  { name: 'mobile', width: '375px', height: 667, icon: DevicePhoneMobileIcon }
]

// Computed
const deviceStyle = computed(() => ({
  width: currentDevice.value.width,
  height: currentDevice.value.height + 'px',
  margin: '0 auto',
  position: 'relative'
}))

const statusClasses = computed(() => {
  const baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
  const statusColors = {
    draft: 'bg-gray-100 text-gray-800',
    previewed: 'bg-blue-100 text-blue-800',
    paid: 'bg-green-100 text-green-800',
    processing: 'bg-yellow-100 text-yellow-800',
    active: 'bg-green-100 text-green-800',
    suspended: 'bg-red-100 text-red-800'
  }
  
  return [baseClasses, statusColors[props.websiteContent.status] || statusColors.draft]
})

const formattedDate = computed(() => {
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(new Date(props.websiteContent.updated_at))
})

// Methods
const handleFrameLoad = () => {
  isLoading.value = false
  isRefreshing.value = false
}

const refreshPreview = () => {
  isLoading.value = true
  isRefreshing.value = true
  refreshTimestamp.value = Date.now()
}

const sharePreview = () => {
  showShareModal.value = true
}

// Auto-refresh every 30 seconds if in edit mode
let autoRefreshInterval = null

onMounted(() => {
  // Debug: Log the props to see what we're getting
  console.log('Preview component mounted:', {
    websiteContent: props.websiteContent,
    previewUrl: props.previewUrl,
    slug: props.slug
  });
  
  // Set up auto-refresh for real-time updates
  if (props.canEdit) {
    autoRefreshInterval = setInterval(() => {
      // Check for updates without full refresh
      // If we have a slug prop, always use slug-based URLs
      const dataUrl = props.slug 
        ? `/preview/${props.slug}/data`
        : `/preview/${props.websiteContent.id}/data`
      
      console.log('Auto-refresh data URL:', dataUrl);
      
      fetch(dataUrl)
        .then(response => response.json())
        .then(data => {
          if (data.lastUpdated !== props.websiteContent.updated_at) {
            refreshPreview()
          }
        })
        .catch(console.error)
    }, 30000) // 30 seconds
  }
})

onUnmounted(() => {
  if (autoRefreshInterval) {
    clearInterval(autoRefreshInterval)
  }
})
</script>
