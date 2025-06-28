<script setup lang="ts">
import { ref, toRefs } from 'vue';
import axios from 'axios';
import { UploadProgress } from '@/components/ui/uploads/upload-progress';
import { Trash, Upload } from 'lucide-vue-next';
import type { CancelTokenSource } from 'axios';

const props = defineProps({
  meta: {
    type: Object,
    required: true,
    default: () => ({
      url: '',
      multiple: false,
      maxSize: 5 * 1024 * 1024, // 5MB default
      allowedTypes: [], // e.g. ['image/png', 'image/jpeg']
      fieldName: 'file',
      headers: {},
    })
  }
});
const { meta } = toRefs(props);

const emit = defineEmits(['uploaded', 'error', 'removed']);

const uploading = ref(false);
const uploadProgress = ref(0);
const uploadError = ref<string|null>(null);
const selectedFiles = ref<File[]>([]);
const validatedFiles = ref<File[]>([]);
const dragActive = ref(false);
const uploadStatus = ref<Record<string, string>>({});
let cancelTokenSource: CancelTokenSource | null = null;

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        handleFiles(Array.from(target.files));
    }
};

const handleFiles = (files: File[]) => {
    // Validate files
    const valid: File[] = [];
    for (const file of files) {
        if (meta.value.allowedTypes.length && !meta.value.allowedTypes.includes(file.type)) {
            uploadError.value = `File type not allowed: ${file.name}`;
            continue;
        }
        if (meta.value.maxSize && file.size > meta.value.maxSize) {
            uploadError.value = `File too large: ${file.name}`;
            continue;
        }
        valid.push(file);
    }
    if (meta.value.multiple) {
        selectedFiles.value = [...selectedFiles.value, ...files];
        validatedFiles.value = [...validatedFiles.value, ...valid];
    } else {
        selectedFiles.value = files;
        validatedFiles.value = valid;
    }
    uploadError.value = valid.length === 0 ? uploadError.value : null;
};

const onDrop = (event: DragEvent) => {
    event.preventDefault();
    dragActive.value = false;
    if (event.dataTransfer && event.dataTransfer.files.length > 0) {
        handleFiles(Array.from(event.dataTransfer.files));
    }
};

const onDragOver = (event: DragEvent) => {
    event.preventDefault();
    dragActive.value = true;
};

const onDragLeave = (event: DragEvent) => {
    event.preventDefault();
    dragActive.value = false;
};

const removeFile = (file: File) => {
    selectedFiles.value = selectedFiles.value.filter(f => f !== file);
    validatedFiles.value = validatedFiles.value.filter(f => f !== file);
    emit('removed', file);
};

const uploadFiles = async () => {
    if (!validatedFiles.value.length) return;
    uploadProgress.value = 0;
    uploading.value = true;
    uploadError.value = null;
    // Set all files to progress status
    validatedFiles.value.forEach(file => {
        uploadStatus.value[file.name] = 'progress';
    });
    cancelTokenSource = axios.CancelToken.source();
    try {
        if (meta.value.multiple) {
            const uploaded: any[] = [];
            for (const file of validatedFiles.value) {
                const formData = new FormData();
                formData.append(meta.value.fieldName || 'file', file);
                await axios.post(meta.value.url, formData, {
                    headers: { 'Content-Type': 'multipart/form-data', ...meta.value.headers },
                    onUploadProgress: (progressEvent) => {
                        if (progressEvent.total) {
                            uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        }
                    },
                    cancelToken: cancelTokenSource.token,
                });
                uploadStatus.value[file.name] = 'success';
                uploaded.push(file);
            }
            emit('uploaded', uploaded);
        } else {
            const file = validatedFiles.value[0];
            const formData = new FormData();
            formData.append(meta.value.fieldName || 'file', file);
            const response = await axios.post(meta.value.url, formData, {
                headers: { 'Content-Type': 'multipart/form-data', ...meta.value.headers },
                onUploadProgress: (progressEvent) => {
                    if (progressEvent.total) {
                        uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                },
                cancelToken: cancelTokenSource.token,
            });
            uploadStatus.value[file.name] = 'success';
            emit('uploaded', response.data);
        }
    } catch (err: any) {
        if (axios.isCancel(err)) {
            uploadError.value = 'Upload cancelled';
        } else {
            uploadError.value = err?.message || 'Upload failed';
        }
        // Set all files to error status
        validatedFiles.value.forEach(file => {
            uploadStatus.value[file.name] = 'error';
        });
        emit('error', uploadError.value);
    } finally {
        uploading.value = false;
        cancelTokenSource = null;
    }
};

const cancelUpload = () => {
    if (cancelTokenSource) {
        cancelTokenSource.cancel('Upload cancelled by user');
    }
};
</script>

<template>
    <div class="flex gap-4">
        <div
            class="flex items-center justify-center w-full"
            :class="{ 'bg-blue-50 border-blue-400': dragActive }"
            @drop="onDrop"
            @dragover="onDragOver"
            @dragleave="onDragLeave"
        >
            <label for="dropzone-file"
                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to
                            upload</span> or drag and drop</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                </div>
                <input id="dropzone-file" :multiple="meta.multiple" type="file" class="hidden" @change="onFileChange" />
            </label>
        </div>
        <div v-if="validatedFiles.length || uploadError" class="w-full">
            <div v-if="validatedFiles.length" class="mt-4 space-y-2 ">
                <div v-for="file in validatedFiles" :key="file.name" class="relative">
                    <UploadProgress
                        :progress="uploadProgress"
                        :filename="file.name"
                        :size="file.size"
                        :status="uploadStatus[file.name] || 'progress'"
                    >
                        <template #actions>
                            <button v-if="!uploading" type="button" class="py-1 rounded text-slate-600 cursor-pointer" @click="uploadFiles" :disabled="uploading">
                                <Upload />
                            </button>
                            <button v-if="uploading" type="button" class="py-1 rounded bg-red-600 text-white" @click="cancelUpload">
                                Cancel
                            </button>
                            <button v-if="!uploading" type="button" class="py-1 rounded text-slate-600 cursor-pointer" @click="removeFile(file)">
                                <Trash />
                            </button>
                        </template>
                    </UploadProgress>
                </div>
            </div>
            <div v-if="uploadError" class="text-red-500 text-sm mt-2">{{ uploadError }}</div>
        </div>
    </div>
</template>
