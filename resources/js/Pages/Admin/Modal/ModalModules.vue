<template>
    <div class="modal-overlay" tabindex="-1" role="dialog" @click.self="cancel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ isCreating ? 'Создать новый модуль' : 'Редактировать модуль' }}</h5>
                    <button type="button" class="close" @click="cancel">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitForm">
                        <div class="form-group">
                            <label for="title">Название</label>
                            <input type="text" class="form-control" id="title" v-model="form.title" :disabled="!isEditing && !isCreating">
                            <div v-if="errors.title" class="text-danger">{{ errors.title[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <textarea class="form-control" id="description" v-model="form.description" :disabled="!isEditing && !isCreating"></textarea>
                            <div v-if="errors.description" class="text-danger">{{ errors.description[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="course_id">Курс</label>
                            <select class="form-control" id="course_id" v-model="form.course_id" :disabled="!isEditing && !isCreating">
                                <option v-for="course in courses" :key="course.id" :value="course.id">{{ course.title }}</option>
                            </select>
                            <div v-if="errors.course_id" class="text-danger">{{ errors.course_id[0] }}</div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" v-if="isEditing || isCreating">{{ isCreating ? 'Создать' : 'Сохранить' }}</button>
                            <button type="button" class="btn btn-secondary" @click="cancel" v-if="isEditing || isCreating">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    module: {
        type: Object,
        required: true,
    },
    isEditing: {
        type: Boolean,
        required: true,
    },
    isCreating: {
        type: Boolean,
        default: false,
    },
    courses: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['update', 'create', 'cancel', 'error']);

const form = ref({
    title: props.module.title,
    description: props.module.description,
    course_id: props.module.course_id,
});

const errors = ref({});

watch(() => props.module, (newModule) => {
    form.value = {
        title: newModule.title,
        description: newModule.description,
        course_id: newModule.course_id,
    };
});

const submitForm = () => {
    errors.value = {};
    console.log('Отправка формы с данными:', form.value); // Отладочное сообщение
    if (props.isCreating) {
        emit('create', form.value);
    } else {
        emit('update', { ...form.value, id: props.module.id });
    }
};

const cancel = () => {
    errors.value = {};
    emit('cancel');
};

// Обработка ошибок
const handleError = (error) => {
    if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors;
    } else {
        errors.value = { general: ['Произошла ошибка. Пожалуйста, попробуйте позже.'] };
    }
};

// Прослушивание события ошибки
emit('error', handleError);
</script>

<style scoped>
.modal-overlay {
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-dialog {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 400px;
    max-width: 90%;
}

.modal-content {
    padding: 20px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
}

.close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
    margin-left: 10px;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.modal {
    display: block;
    background: rgba(0, 0, 0, 0.5);
}
</style>