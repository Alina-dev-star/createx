<template>
    <div class="teachers">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-1"></i> Список учителей</h5>
                <button class="btn btn-outline-primary btn-sm" @click="createNewTeacher">
                    <i class="fas fa-plus me-1"></i> Добавить нового учителя
                </button>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div class="input-group search-group">
                        <input v-model="searchQuery" type="text" class="form-control search-input" placeholder="Поиск по имени, фамилии, отчеству, телефону или email">
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
                            <th>Курс</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="teacher in filteredTeachers" :key="teacher.id">
                            <td>{{ teacher.id }}</td>
                            <td>{{ teacher.name || 'Нет данных' }}</td>
                            <td>{{ teacher.surname || 'Нет данных' }}</td>
                            <td>{{ teacher.patronymic || 'Нет данных' }}</td>
                            <td>{{ teacher.birth_date || 'Нет данных' }}</td>
                            <td>{{ teacher.phone || 'Нет данных' }}</td>
                            <td>{{ teacher.email || 'Нет данных' }}</td>
                            <td>
                                <div class="input-group">
                                    <input type="password" class="form-control" :value="'********'" disabled>
                                </div>
                            </td>
                            <td>{{ getCourseTitle(teacher.course_id, courses) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary"  @click="showTeacher(teacher.id)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning"  @click="editTeacher(teacher.id)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" @click="deleteTeacher(teacher.id)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <ModalTeachers
            v-if="selectedTeacher"
            :teacher="selectedTeacher"
            :isEditing="isEditing"
            :isCreating="isCreating"
            :courses="courses"
            @update="updateTeacher"
            @create="createTeacher"
            @cancel="cancelEdit"
        />
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import ModalTeachers from './Modal/ModalTeachers.vue';

const props = defineProps({
    teachers: {
        type: Array,
        required: true,
    },
    courses: {
        type: Array,
        required: true,
    },
});

const teachers = ref(props.teachers.map(teacher => ({ ...teacher, showPassword: false })));
const selectedTeacher = ref(null);
const isEditing = ref(false);
const isCreating = ref(false);
const searchQuery = ref('');

const filteredTeachers = computed(() => {
    return teachers.value.filter(teacher => {
        const query = searchQuery.value.toLowerCase();
        return (
            teacher.name?.toLowerCase().includes(query) ||
            teacher.surname?.toLowerCase().includes(query) ||
            teacher.patronymic?.toLowerCase().includes(query) ||
            teacher.phone?.toLowerCase().includes(query) ||
            teacher.email?.toLowerCase().includes(query)
        );
    });
});

onMounted(() => {
    console.log('Teachers data on mount:', teachers.value); // Добавьте эту строку для отладки
});

const showTeacher = async (teacherId) => {
    try {
        console.log('Fetching teacher with ID:', teacherId); // Отладочное сообщение
        const response = await axios.get(route('admin.teachers.show', teacherId));
        console.log('Response data:', response.data); // Отладочное сообщение
        selectedTeacher.value = response.data;
        isEditing.value = false;
        isCreating.value = false;
    } catch (error) {
        console.error('Error fetching teacher:', error);
        alert('Ошибка при загрузке данных преподавателя.');
    }
};

const editTeacher = async (teacherId) => {
    try {
        console.log('Fetching teacher for edit with ID:', teacherId); // Отладочное сообщение
        const response = await axios.get(route('admin.teachers.edit', teacherId));
        console.log('Response data:', response.data); // Отладочное сообщение
        selectedTeacher.value = response.data;
        isEditing.value = true;
        isCreating.value = false;
    } catch (error) {
        console.error('Error fetching teacher for edit:', error);
        alert('Ошибка при загрузке данных для редактирования.');
    }
};

const createNewTeacher = () => {
    selectedTeacher.value = { name: '', surname: '', patronymic: '', birth_date: '', phone: '', email: '', password: '', course_id: null };
    isEditing.value = true;
    isCreating.value = true;
};

const updateTeacher = async (teacher) => {
    try {
        console.log('Updating teacher with data:', teacher); // Отладочное сообщение
        const response = await axios.put(route('admin.teachers.update', teacher.id), teacher);
        console.log('Response data:', response.data); // Отладочное сообщение
        const index = teachers.value.findIndex(t => t.id === response.data.id);
        teachers.value[index] = response.data;
        selectedTeacher.value = null;
        isEditing.value = false;
        isCreating.value = false;
    } catch (error) {
        console.error('Error updating teacher:', error);
        alert('Ошибка при обновлении данных преподавателя.');
    }
};

const createTeacher = async (teacher) => {
    try {
        console.log('Creating teacher with data:', teacher); // Отладочное сообщение
        const response = await axios.post(route('admin.teachers.store'), teacher);
        console.log('Response data:', response.data); // Отладочное сообщение
        teachers.value.push(response.data);
        selectedTeacher.value = null;
        isEditing.value = false;
        isCreating.value = false;
    } catch (error) {
        console.error('Error creating teacher:', error);
        alert('Ошибка при создании нового преподавателя.');
    }
};

const deleteTeacher = async (teacherId) => {
    if (confirm('Вы уверены, что хотите удалить этого преподавателя?')) {
        try {
            console.log('Deleting teacher with ID:', teacherId); // Отладочное сообщение
            await axios.delete(route('admin.teachers.destroy', teacherId));
            teachers.value = teachers.value.filter(teacher => teacher.id !== teacherId);
        } catch (error) {
            console.error('Error deleting teacher:', error);
            alert('Ошибка при удалении преподавателя.');
        }
    }
};

const cancelEdit = () => {
    selectedTeacher.value = null;
    isEditing.value = false;
    isCreating.value = false;
};

const getCourseTitle = (courseId, courses) => {
    if (!courseId) return 'Не указан';
    const course = courses.find(course => course.id === courseId);
    return course ? course.title : 'Не указан';
};
</script>

<style scoped>
.teachers {
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

.search-input:focus + .search-icon {
    color: #007bff;
}
</style>