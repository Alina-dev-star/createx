<template>
    <div class="modal-overlay" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ isCreating ? 'Создать преподавателя' : (isEditing ? 'Редактировать преподавателя' : 'Просмотр преподавателя') }}</h5>
                    <button type="button" class="close" @click="cancelEdit">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="isCreating ? createTeacher() : (isEditing ? updateTeacher() : null)">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input type="text" class="form-control" id="name" v-model="selectedTeacher.name" :disabled="!isEditing && !isCreating">
                        </div>
                        <div class="form-group">
                            <label for="surname">Фамилия</label>
                            <input type="text" class="form-control" id="surname" v-model="selectedTeacher.surname" :disabled="!isEditing && !isCreating">
                        </div>
                        <div class="form-group">
                            <label for="patronymic">Отчество</label>
                            <input type="text" class="form-control" id="patronymic" v-model="selectedTeacher.patronymic" :disabled="!isEditing && !isCreating">
                        </div>
                        <div class="form-group">
                            <label for="birth_date">Дата рождения</label>
                            <input type="text" class="form-control" id="birth_date" v-model="selectedTeacher.birth_date" :disabled="!isEditing && !isCreating">
                        </div>
                        <div class="form-group">
                            <label for="phone">Телефон</label>
                            <input type="text" class="form-control" id="phone" v-model="selectedTeacher.phone" :disabled="!isEditing && !isCreating">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" v-model="selectedTeacher.email" :disabled="!isEditing && !isCreating">
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <div class="input-group">
                                <input :type="showPassword ? 'text' : 'password'" class="form-control" id="password" v-model="selectedTeacher.password" :disabled="!isEditing && !isCreating">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" @click="togglePasswordVisibility">
                                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="course_id">Курс</label>
                            <select class="form-control" id="course_id" v-model="selectedTeacher.course_id" :disabled="!isEditing && !isCreating">
                                <option value="">Выберите курс</option>
                                <option v-for="course in courses" :key="course.id" :value="course.id">{{ course.title }}</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" v-if="isEditing || isCreating">{{ isCreating ? 'Создать' : 'Сохранить' }}</button>
                            <button type="button" class="btn btn-secondary" @click="cancelEdit" v-if="isEditing || isCreating">Отмена</button>
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
    teacher: {
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

const emit = defineEmits(['update', 'cancel', 'create']);

const selectedTeacher = ref({ ...props.teacher, password: props.teacher.password || '', course_id: props.teacher.course_id || null });
const showPassword = ref(false);

watch(() => props.teacher, (newTeacher) => {
    selectedTeacher.value = { ...newTeacher, password: newTeacher.password || '', course_id: newTeacher.course_id || null };
});

const updateTeacher = () => {
    emit('update', selectedTeacher.value);
};

const createTeacher = () => {
    emit('create', selectedTeacher.value);
};

const cancelEdit = () => {
    emit('cancel');
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
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
</style>