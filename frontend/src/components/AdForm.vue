<template>
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Ad</h2>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Title <span class="text-red-500">*</span>
                </label>
                <input
                    id="title"
                    v-model="formData.title"
                    type="text"
                    required
                    maxlength="255"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter ad title"
                />
                <p v-if="errors.title" class="text-red-500 text-xs mt-1">{{ errors.title }}</p>
            </div>

            <div>
                <label for="text" class="block text-sm font-medium text-gray-700 mb-1">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="text"
                    v-model="formData.text"
                    required
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter ad description"
                ></textarea>
                <p v-if="errors.text" class="text-red-500 text-xs mt-1">{{ errors.text }}</p>
            </div>

            <!-- Changed from URL input to file upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                    Image <span class="text-red-500">*</span>
                </label>
                <input
                    id="image"
                    type="file"
                    accept="image/jpeg,image/jpg,image/png,image/webp"
                    required
                    @change="handleImageChange"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                />
                <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, JPEG, PNG, WEBP (max 5MB)</p>
                <p v-if="errors.image" class="text-red-500 text-xs mt-1">{{ errors.image }}</p>

                <!-- Added image preview -->
                <div v-if="imagePreview" class="mt-3">
                    <img
                        :src="imagePreview"
                        alt="Preview"
                        class="w-full h-48 object-cover rounded-lg"
                    />
                </div>
            </div>

            <div>
                <label for="target_url" class="block text-sm font-medium text-gray-700 mb-1">
                    Target URL <span class="text-red-500">*</span>
                </label>
                <input
                    id="target_url"
                    v-model="formData.target_url"
                    type="url"
                    required
                    maxlength="2048"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="https://example.com/landing-page"
                />
                <p v-if="errors.target_url" class="text-red-500 text-xs mt-1">{{ errors.target_url }}</p>
            </div>

            <div v-if="submitError" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ submitError }}
            </div>

            <div v-if="submitSuccess" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                Ad created successfully! Redirecting...
            </div>

            <div class="flex gap-3 pt-2">
                <button
                    type="submit"
                    :disabled="loading"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-medium py-2 px-4 rounded-lg transition-colors"
                >
                    {{ loading ? 'Creating...' : 'Create Ad' }}
                </button>

                <button
                    type="button"
                    @click="resetForm"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    Reset
                </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { adService } from '../services/adService'
import type { CreateAdPayload } from '../types/ad'

const router = useRouter()

const formData = reactive<Omit<CreateAdPayload, 'image'> & { image: File | null }>({
    title: '',
    text: '',
    image: null,
    target_url: ''
})

const errors = reactive<Record<string, string>>({})
const loading = ref<boolean>(false)
const submitError = ref<string | null>(null)
const submitSuccess = ref<boolean>(false)
const imagePreview = ref<string | null>(null)

const handleImageChange = (event: Event): void => {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0]

    if (file) {
        // Validate file
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']
        const maxSize = 5 * 1024 * 1024 // 5MB

        if (!validTypes.includes(file.type)) {
            errors.image = 'Invalid file type. Please upload JPG, JPEG, PNG, or WEBP.'
            formData.image = null
            imagePreview.value = null
            return
        }

        if (file.size > maxSize) {
            errors.image = 'File size exceeds 5MB limit.'
            formData.image = null
            imagePreview.value = null
            return
        }

        delete errors.image
        formData.image = file

        // Generate preview
        const reader = new FileReader()
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string
        }
        reader.readAsDataURL(file)
    }
}

const isValidUrl = (url: string): boolean => {
    try {
        new URL(url)
        return true
    } catch {
        return false
    }
}

const validateForm = (): boolean => {
    Object.keys(errors).forEach(key => delete errors[key])

    if (!formData.title.trim()) {
        errors.title = 'Title is required'
    }

    if (!formData.text.trim()) {
        errors.text = 'Description is required'
    }

    if (!formData.image) {
        errors.image = 'Image is required'
    }

    if (!isValidUrl(formData.target_url)) {
        errors.target_url = 'Please enter a valid URL'
    }

    return Object.keys(errors).length === 0
}

const handleSubmit = async (): Promise<void> => {
    if (!validateForm() || !formData.image) return

    loading.value = true
    submitError.value = null
    submitSuccess.value = false

    try {
        await adService.createAd(formData as CreateAdPayload)
        submitSuccess.value = true

        setTimeout(() => {
            router.push('/')
        }, 1500)
    } catch (error: any) {
        submitError.value = error.response?.data?.message || 'Failed to create ad. Please try again.'
    } finally {
        loading.value = false
    }
}

const resetForm = (): void => {
    Object.assign(formData, {
        title: '',
        text: '',
        image: null,
        target_url: ''
    })
    Object.keys(errors).forEach(key => delete errors[key])
    submitError.value = null
    submitSuccess.value = false
    imagePreview.value = null

    // Reset file input
    const fileInput = document.getElementById('image') as HTMLInputElement
    if (fileInput) fileInput.value = ''
}
</script>
