<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-4">
            {{ field.label }}
            <span v-if="field.required" class="text-red-500">*</span>
            <span class="text-gray-500 font-normal text-xs ml-2">
                ({{ items.length }}/{{ field.max_items }} items)
            </span>
        </label>
        
        <div class="space-y-4">
            <!-- Repeater Items -->
            <div 
                v-for="(item, index) in items" 
                :key="index"
                class="relative bg-gray-50 border border-gray-200 rounded-lg p-4"
            >
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-sm font-medium text-gray-700">
                        {{ field.label }} #{{ index + 1 }}
                    </h4>
                    
                    <button
                        v-if="items.length > (field.min_items || 0)"
                        type="button"
                        @click="removeItem(index)"
                        class="text-red-500 hover:text-red-700 p-1 rounded-md hover:bg-red-50 transition duration-200"
                    >
                        <TrashIcon class="w-4 h-4" />
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div 
                        v-for="subField in field.fields" 
                        :key="subField.key"
                        :class="subField.type === 'textarea' ? 'md:col-span-2' : 'col-span-1'"
                    >
                        <label :for="`${field.key}_${index}_${subField.key}`" class="block text-xs font-medium text-gray-600 mb-1">
                            {{ subField.label }}
                            <span v-if="subField.required" class="text-red-500">*</span>
                        </label>
                        
                        <!-- Text Input -->
                        <input
                            v-if="subField.type === 'text'"
                            :id="`${field.key}_${index}_${subField.key}`"
                            :name="`${field.key}.${index}.${subField.key}`"
                            type="text"
                            :value="item[subField.key] || ''"
                            :placeholder="subField.placeholder"
                            :class="getSubFieldClass(field.key, index, subField.key)"
                            @input="updateItem(index, subField.key, $event.target.value)"
                        >
                        
                        <!-- Textarea -->
                        <textarea
                            v-else-if="subField.type === 'textarea'"
                            :id="`${field.key}_${index}_${subField.key}`"
                            :name="`${field.key}.${index}.${subField.key}`"
                            :value="item[subField.key] || ''"
                            :placeholder="subField.placeholder"
                            rows="3"
                            :class="getSubFieldClass(field.key, index, subField.key)"
                            @input="updateItem(index, subField.key, $event.target.value)"
                        />
                        
                        <!-- URL Input -->
                        <input
                            v-else-if="subField.type === 'url'"
                            :id="`${field.key}_${index}_${subField.key}`"
                            :name="`${field.key}.${index}.${subField.key}`"
                            type="url"
                            :value="item[subField.key] || ''"
                            :placeholder="subField.placeholder"
                            :class="getSubFieldClass(field.key, index, subField.key)"
                            @input="updateItem(index, subField.key, $event.target.value)"
                        >
                        
                        <!-- Select -->
                        <select
                            v-else-if="subField.type === 'select'"
                            :id="`${field.key}_${index}_${subField.key}`"
                            :name="`${field.key}.${index}.${subField.key}`"
                            :value="item[subField.key] || ''"
                            :class="getSubFieldClass(field.key, index, subField.key)"
                            @change="updateItem(index, subField.key, $event.target.value)"
                        >
                            <option value="">Select {{ subField.label }}</option>
                            <option 
                                v-for="(label, value) in subField.options" 
                                :key="value" 
                                :value="value"
                            >
                                {{ label }}
                            </option>
                        </select>
                        
                        <!-- Error Message -->
                        <div v-if="getFieldError(field.key, index, subField.key)" class="mt-1 text-xs text-red-600">
                            {{ getFieldError(field.key, index, subField.key) }}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Add Item Button -->
            <button
                v-if="items.length < field.max_items"
                type="button"
                @click="addItem"
                class="w-full flex items-center justify-center py-3 px-4 border-2 border-dashed border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:border-blue-400 hover:text-blue-600 transition duration-200"
            >
                <PlusIcon class="w-5 h-5 mr-2" />
                Add {{ field.label.replace(/s$/, '') }}
            </button>
            
            <!-- Field Error -->
            <div v-if="errors[field.key]" class="text-sm text-red-600">
                {{ errors[field.key] }}
            </div>
            
            <!-- Help Text -->
            <p v-if="field.help" class="text-xs text-gray-500">
                {{ field.help }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    field: Object,
    modelValue: Array,
    errors: Object
})

const emit = defineEmits(['update:modelValue'])

const items = ref([...props.modelValue])

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
    items.value = [...newValue]
}, { deep: true })

// Emit changes
watch(items, (newItems) => {
    emit('update:modelValue', newItems)
}, { deep: true })

const addItem = () => {
    if (items.value.length < props.field.max_items) {
        const newItem = {}
        props.field.fields.forEach(subField => {
            newItem[subField.key] = ''
        })
        items.value.push(newItem)
    }
}

const removeItem = (index) => {
    if (items.value.length > (props.field.min_items || 0)) {
        items.value.splice(index, 1)
    }
}

const updateItem = (index, key, value) => {
    if (items.value[index]) {
        items.value[index][key] = value
    }
}

const getSubFieldClass = (fieldKey, index, subFieldKey) => {
    const hasError = getFieldError(fieldKey, index, subFieldKey)
    return `w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ${hasError ? 'border-red-300 ring-red-200' : ''}`
}

const getFieldError = (fieldKey, index, subFieldKey) => {
    const errorKey = `${fieldKey}.${index}.${subFieldKey}`
    return props.errors[errorKey]
}

// Initialize with minimum items
if (items.value.length === 0 && props.field.min_items > 0) {
    for (let i = 0; i < props.field.min_items; i++) {
        addItem()
    }
}
</script>
