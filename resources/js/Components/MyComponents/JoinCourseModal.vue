<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    courseId: {
        type: Number,
        required: true,
    },
});

const form = ref({
    name: '',
    surname: '',
    phone: '',
    email: '',
    agree: false,
    course_id: props.courseId, // Используем переданный courseId
});

const user = usePage().props.user;
const hasApprovedApplication = ref(false);
const isAuthenticated = ref(!!user);
const errorMessage = ref('');

const checkUserApplications = async () => {
    if (!isAuthenticated.value) {
        return;
    }

    try {
        const response = await axios.get(`/applications/check?user_id=${user.id}`);
        hasApprovedApplication.value = response.data.hasApprovedApplication;
    } catch (error) {
        console.error('Error checking user applications:', error);
    }
};

const submitForm = async () => {
    try {
        const response = await axios.post('/applications', form.value);
        console.log('Form submitted:', response.data);
        // Очистить форму после успешной отправки
        form.value = {
            name: '',
            surname: '',
            phone: '',
            email: '',
            agree: false,
            course_id: props.courseId,
        };
        // Закрыть модальное окно
        const modal = new bootstrap.Modal(document.getElementById('joinCourseModal'));
        modal.hide();
    } catch (error) {
        console.error('Error submitting form:', error);
        if (error.response) {
            console.error('Server response:', error.response.data);
            errorMessage.value = error.response.data.message;
        }
    }
};

onMounted(() => {
    checkUserApplications();
});
</script>

<template>
    <div class="modal fade" id="joinCourseModal" tabindex="-1" aria-labelledby="joinCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="joinCourseModalLabel">Присоединиться к курсу</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitForm">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Имя</label>
                            <input type="text" class="form-control" id="name" v-model="form.name" required>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label fw-bold">Фамилия</label>
                            <input type="text" class="form-control" id="surname" v-model="form.surname" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Телефон</label>
                            <input type="tel" class="form-control" id="phone" v-model="form.phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" v-model="form.email" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agree" v-model="form.agree" required>
                            <label class="form-check-label" for="agree">Согласен получать сообщения от школы Createx</label>
                        </div>
                        <button type="submit" class="btn btn-custom-gradient w-100" :disabled="hasApprovedApplication">
                            Отправить сообщение
                        </button>
                        <p v-if="hasApprovedApplication" class="text-danger mt-3">У вас уже есть одобренная заявка на другой курс. Новая заявка будет отклонена.</p>
                        <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-content {
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.modal-header {
    padding: 20px 20px 0 20px;
}

.modal-body {
    padding: 20px;
}

.form-label {
    color: #333;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 10px;
}

.form-check-label {
    font-size: 14px;
    color: #666;
}

.btn-custom-gradient {
    background: linear-gradient(45deg, #FF3F3A, #F75E05);
    color: white;
    border: none;
    border-radius: 5px;
    padding: 12px;
    font-weight: bold;
}

.btn-custom-gradient:hover {
    background: linear-gradient(45deg, #F75E05, #FF3F3A);
    color: white;
}
</style>