<template>
    <div class="students">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-1"></i> Список студентов</h5>
                <button class="btn btn-outline-primary btn-sm" @click="createNewStudent">
                    <i class="fas fa-plus me-1"></i> Добавить нового студента
                </button>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div class="input-group search-group">
                        <input v-model="searchQuery" type="text" class="form-control search-input"
                            placeholder="Поиск по имени, фамилии, отчеству, телефону или email">
                        <div class="input-group-append">
                            <span class="input-group-text search-icon">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Отчество</th>
                            <th>Дата рождения</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Пароль</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="student in filteredStudents" :key="student.id">
                            <td>{{ student.id }}</td>
                            <td>{{ student.name }}</td>
                            <td>{{ student.surname }}</td>
                            <td>{{ student.patronymic }}</td>
                            <td>{{ student.birth_date }}</td>
                            <td>{{ student.phone }}</td>
                            <td>{{ student.email }}</td>
                            <td>
                                <div v-if="isEditing && selectedStudent && selectedStudent.id === student.id" class="input-group">
                                    <input v-model="selectedStudent.password" :readonly="!isEditing"
                                        :type="student.showPassword ? 'text' : 'password'" class="form-control" :id="'password-' + student.id">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary"
                                            :disabled="!isEditing" @click="togglePasswordVisibility(student)">
                                            <i :class="student.showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                        </button>
                                    </div>
                                </div>
                                <span v-else>********</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" @click="showStudent(student.id)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" @click="editStudent(student.id)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" @click="deleteStudent(student.id)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Модальное окно для просмотра и редактирования -->
        <ModalStudents v-if="selectedStudent" :student="selectedStudent" :isEditing="isEditing" :isCreating="isCreating"
            @update="updateStudent" @create="createStudent" @cancel="cancelEdit" />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import ModalStudents from './Modal/ModalStudents.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    students: {
        type: Array,
        required: true,
    },
});

const students = ref(props.students ? props.students.map(student => ({ ...student, showPassword: false })) : []);
const selectedStudent = ref(null);
const isEditing = ref(false);
const isCreating = ref(false);
const searchQuery = ref('');

const filteredStudents = computed(() => {
    return students.value.filter(student => {
        const query = searchQuery.value.toLowerCase();

        return (
            student.name.toLowerCase().includes(query) ||
            student.surname.toLowerCase().includes(query) ||
            student.patronymic.toLowerCase().includes(query) ||
            student.phone.toLowerCase().includes(query) ||
            student.email.toLowerCase().includes(query)
        );
    });
});

const showStudent = async (studentId) => {
    try {
        const response = await axios.get(route('admin.students.show', studentId));
        selectedStudent.value = response.data;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные студента успешно загружены!',
        });
    } catch (error) {
        console.error('Ошибка при получении данных студента:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при загрузке данных студента.',
        });
    }
};

const editStudent = async (studentId) => {
    try {
        const response = await axios.get(route('admin.students.edit', studentId));
        selectedStudent.value = response.data;
        isEditing.value = true;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные студента успешно загружены для редактирования!',
        });
    } catch (error) {
        console.error('Ошибка при получении данных студента для редактирования:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при загрузке данных студента для редактирования.',
        });
    }
};

const createNewStudent = () => {
    selectedStudent.value = { name: '', surname: '', patronymic: '', birth_date: '', phone: '', email: '', password: '', agree: false, showPassword: false };
    isEditing.value = true;
    isCreating.value = true;
};

const updateStudent = async (student) => {
    try {
        const response = await axios.put(route('admin.students.update', student.id), student);
        const index = students.value.findIndex(s => s.id === response.data.id);
        students.value[index] = { ...response.data, showPassword: false };
        selectedStudent.value = null;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные студента успешно обновлены!',
        });
    } catch (error) {
        console.error('Ошибка при обновлении данных студента:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при обновлении данных студента.',
        });
    }
};

const createStudent = async (student) => {
    try {
        const response = await axios.post(route('admin.students.store'), student);
        students.value.push({ ...response.data, showPassword: false });
        selectedStudent.value = null;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Студент успешно создан!',
        });
    } catch (error) {
        if (error.response && error.response.status === 422) {
            console.error('Ошибки валидации:', error.response.data.errors);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: 'Произошла ошибка при создании студента.',
            });
        } else {
            console.error('Ошибка при создании нового студента:', error);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: 'Произошла ошибка при создании нового студента.',
            });
        }
    }
};

const deleteStudent = async (studentId) => {
    const result = await Swal.fire({
        title: 'Вы уверены?',
        text: "Вы не сможете восстановить этого студента!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отмена',
    });

    if (result.isConfirmed) {
        try {
            await axios.delete(route('admin.students.destroy', studentId));
            students.value = students.value.filter(student => student.id !== studentId);
            Swal.fire({
                icon: 'success',
                title: 'Успешно',
                text: 'Студент успешно удален!',
            });
        } catch (error) {
            console.error('Ошибка при удалении студента:', error);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: 'Произошла ошибка при удалении студента.',
            });
        }
    }
};

const cancelEdit = () => {
    selectedStudent.value = null;
    isEditing.value = false;
    isCreating.value = false;
};

const togglePasswordVisibility = (student) => {
    student.showPassword = !student.showPassword;
};
</script>

<style scoped>
.students {
    font-family: 'Arial', sans-serif;
}

.card {
    border: none;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-header {
    border-bottom: 1px solid #dee2e6;
}

.table {
    font-size: 0.9rem;
}

.btn-group {
    display: flex;
    gap: 0.5rem;
}

.btn-outline-primary,
.btn-outline-warning,
.btn-outline-danger {
    padding: 0.25rem 0.5rem;
}

.search-group {
    position: relative;
}

.search-input {
    border-radius: 20px;
    padding: 0.5rem 1rem;
    transition: box-shadow 0.3s ease;
    border: 1px solid #ced4da;
}

.search-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
    border-color: #80bdff;
}

.search-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background-color: transparent;
    border: none;
    color: #6c757d;
    transition: color 0.3s ease;
}

.search-input:focus+.search-icon {
    color: #007bff;
}
</style>