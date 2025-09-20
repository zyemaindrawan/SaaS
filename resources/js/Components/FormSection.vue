<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div :class="`bg-gradient-to-r ${gradient} px-6 py-4`">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <component :is="getIcon(icon)" class="w-5 h-5 mr-2" />
                {{ title }}
            </h3>
        </div>
        
        <div class="p-6 space-y-6">
            <div v-for="field in fields" :key="field.key">
                
                <!-- Regular Input Fields -->
                <div v-if="field.type !== 'repeater'" :class="getFieldWidth(field)">
                    <label :for="field.key" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ field.label }}
                        <span v-if="field.required" class="text-red-500">*</span>
                    </label>
                    
                    <!-- Text Input -->
                    <input 
                        v-if="field.type === 'text'"
                        :id="field.key"
                        :name="field.key"
                        type="text"
                        :value="formData[field.key]"
                        :placeholder="field.placeholder"
                        :maxlength="field.max_length"
                        :class="getInputClass(field.key)"
                        @input="updateValue(field.key, $event.target.value)"
                    >
                    
                    <!-- Email Input -->
                    <input 
                        v-else-if="field.type === 'email'"
                        :id="field.key"
                        :name="field.key"
                        type="email"
                        :value="formData[field.key]"
                        :placeholder="field.placeholder"
                        :class="getInputClass(field.key)"
                        @input="updateValue(field.key, $event.target.value)"
                    >
                    
                    <!-- Tel Input -->
                    <input 
                        v-else-if="field.type === 'tel'"
                        :id="field.key"
                        :name="field.key"
                        type="tel"
                        :value="formData[field.key]"
                        :placeholder="field.placeholder"
                        :class="getInputClass(field.key)"
                        @input="updateValue(field.key, $event.target.value)"
                    >
                    
                    <!-- URL Input -->
                    <input 
                        v-else-if="field.type === 'url'"
                        :id="field.key"
                        :name="field.key"
                        type="url"
                        :value="formData[field.key]"
                        :placeholder="field.placeholder"
                        :class="getInputClass(field.key)"
                        @input="updateValue(field.key, $event.target.value)"
                    >
                    
                    <!-- Textarea -->
                    <textarea 
                        v-else-if="field.type === 'textarea'"
                        :id="field.key"
                        :name="field.key"
                        :value="formData[field.key]"
                        :placeholder="field.placeholder"
                        :maxlength="field.max_length"
                        rows="3"
                        :class="getInputClass(field.key)"
                        @input="updateValue(field.key, $event.target.value)"
                    />
                    
                    <!-- Color Input -->
                    <div v-else-if="field.type === 'color'" class="flex items-center space-x-3">
                        <input 
                            :id="field.key"
                            :name="field.key"
                            type="color"
                            :value="formData[field.key] || field.default"
                            class="h-12 w-20 border border-gray-300 rounded-lg cursor-pointer"
                            @input="updateValue(field.key, $event.target.value)"
                        >
                        <div class="flex-1">
                            <input 
                                type="text" 
                                :value="formData[field.key] || field.default"
                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                readonly
                            >
                        </div>
                    </div>
                    
                    <!-- Subdomain Input -->
                    <div v-else-if="field.type === 'subdomain'" class="flex">
                        <input 
                            :id="field.key"
                            :name="field.key"
                            type="text"
                            :value="formData[field.key]"
                            :placeholder="field.placeholder"
                            :class="`flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent ${errors[field.key] ? 'border-red-300' : ''}`"
                            pattern="[a-z0-9\-]+"
                            @input="updateValue(field.key, $event.target.value.toLowerCase().replace(/[^a-z0-9-]/g, ''))"
                        >
                        <span class="inline-flex items-center px-4 py-3 bg-gray-50 border border-l-0 border-gray-300 rounded-r-lg text-sm text-gray-500">
                            {{ field.suffix }}
                        </span>
                    </div>
                    
                    <!-- Select Input -->
                    <select 
                        v-else-if="field.type === 'select'"
                        :id="field.key"
                        :name="field.key"
                        :value="formData[field.key]"
                        :class="getInputClass(field.key)"
                        @change="updateValue(field.key, $event.target.value)"
                    >
                        <option value="">Select {{ field.label }}</option>
                        <option 
                            v-for="(label, value) in field.options" 
                            :key="value" 
                            :value="value"
                        >
                            {{ label }}
                        </option>
                    </select>
                    
                    <!-- Character Counter -->
                    <div v-if="field.max_length" class="mt-1 text-xs text-gray-500">
                        {{ (formData[field.key] || '').length }}/{{ field.max_length }} characters
                    </div>
                    
                    <!-- Help Text -->
                    <p v-if="field.help" class="mt-1 text-xs text-gray-500">
                        {{ field.help }}
                    </p>
                    
                    <!-- Error Message -->
                    <div v-if="errors[field.key]" class="mt-1 text-sm text-red-600">
                        {{ errors[field.key] }}
                    </div>
                </div>
                
                <!-- Repeater Fields -->
                <RepeaterField
                    v-else
                    :field="field"
                    :model-value="formData[field.key] || []"
                    :errors="errors"
                    @update:model-value="updateValue(field.key, $event)"
                />
                
            </div>
        </div>
    </div>
</template>

<script setup>
import RepeaterField from '@/Components/RepeaterField.vue'
import {
    BuildingOfficeIcon,
    MagnifyingGlassIcon,
    EnvelopeIcon,
    DocumentTextIcon,
    ShareIcon,
    PaintBrushIcon,
    CogIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
    title: String,
    icon: String,
    gradient: String,
    fields: Array,
    formData: Object,
    errors: Object
})

const emit = defineEmits(['update'])

const getIcon = (iconName) => {
    const icons = {
        BuildingOfficeIcon,
        MagnifyingGlassIcon,
        EnvelopeIcon,
        DocumentTextIcon,
        ShareIcon,
        PaintBrushIcon,
        CogIcon
    }
    return icons[iconName] || BuildingOfficeIcon
}

const getFieldWidth = (field) => {
    if (field.type === 'repeater') {
        return 'col-span-full'
    }
    return field.full_width ? 'col-span-full' : 'col-span-1'
}

const getInputClass = (fieldKey) => {
    return `w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ${props.errors[fieldKey] ? 'border-red-300 ring-red-200' : ''}`
}

const updateValue = (key, value) => {
    emit('update', { key, value })
}
</script>
