<script setup>
import { ref } from 'vue';

const props = defineProps({
    student: {
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
});

const emit = defineEmits(['update', 'cancel', 'create']);

const selectedStudent = ref({ ...props.student, agree: false, password: props.student.password || '', showPassword: false });
const showPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const updateStudent = () => {
    emit('update', selectedStudent.value);
};

const createStudent = () => {
    emit('create', selectedStudent.value);
};

const cancelEdit = () => {
    emit('cancel');
};
</script>

<template>
    <div class="modal-overlay" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ isCreating ? 'Создание нового студента' : 'Редактирование студента' }}</h5>
                    <button type="button" class="close" @click="cancelEdit">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="isCreating ? createStudent() : updateStudent()">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input v-model="selectedStudent.name" :readonly="!isEditing" type="text" class="form-control" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="surname">Фамилия</label>
                            <input v-model="selectedStudent.surname" :readonly="!isEditing" type="text" class="form-control" id="surname" required>
                        </div>
                        <div class="form-group">
                            <label for="patronymic">Отчество</label>
                            <input v-model="selectedStudent.patronymic" :readonly="!isEditing" type="text" class="form-control" id="patronymic">
                        </div>
                        <div class="form-group">
                            <label for="birth_date">Дата рождения</label>
                            <input v-model="selectedStudent.birth_date" :readonly="!isEditing" type="date" class="form-control" id="birth_date" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Телефон</label>
                            <input v-model="selectedStudent.phone" :readonly="!isEditing" type="text" class="form-control" id="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input v-model="selectedStudent.email" :readonly="!isEditing" type="email" class="form-control" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <div class="input-group">
                                <input v-model="selectedStudent.password" :readonly="!isEditing" :type="showPassword ? 'text' : 'password'" class="form-control" :id="'password-' + selectedStudent.id">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" @click="togglePasswordVisibility" :disabled="!isEditing">
                                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" v-if="isEditing">{{ isCreating ? 'Создать' : 'Сохранить' }}</button>
                            <button type="button" class="btn btn-secondary" @click="cancelEdit">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-overlay {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    animation: fadeIn 0.3s ease-in-out;
}

.modal-dialog {
    width: 100%;
    max-width: 500px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideIn 0.3s ease-in-out;
    overflow: hidden;
}

.modal-content {
    padding: 20px;
    background: linear-gradient(135deg, #f0f8ff, #e6f2ff);
    border-radius: 12px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 10px;
    margin-bottom: 20px;
    color: #007bff;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    transition: color 0.3s ease;
    color: #007bff;
}

.close:hover {
    color: #dc3545;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #007bff;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-check {
    display: flex;
    align-items: center;
}

.form-check-input {
    margin-right: 10px;
    border: 1px solid #007bff;
    transition: background-color 0.3s ease;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
    margin-left: 10px;
}

.btn-secondary:hover {
    background-color: #5a6268;
    transform: scale(1.05);
}

/* Анимации */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>