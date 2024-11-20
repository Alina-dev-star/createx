<script setup>
import { ref } from 'vue';
import axios from 'axios';
import ModalModules from './Modal/ModalModules.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    modules: {
        type: Array,
        required: true,
    },
    courses: {
        type: Array,
        required: true,
    },
});

const modules = ref(props.modules);
const courses = ref(props.courses);
const selectedModule = ref(null);
const isEditing = ref(false);
const isCreating = ref(false);
const errorMessage = ref('');

const showModule = async (moduleId) => {
    try {
        const response = await axios.get(route('admin.modules.show', moduleId));
        selectedModule.value = response.data;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные модуля успешно загружены!',
        });
    } catch (error) {
        handleError(error);
    }
};

const editModule = async (moduleId) => {
    try {
        const response = await axios.get(route('admin.modules.edit', moduleId));
        selectedModule.value = response.data;
        isEditing.value = true;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные модуля успешно загружены для редактирования!',
        });
    } catch (error) {
        handleError(error);
    }
};

const createNewModule = () => {
    selectedModule.value = { title: '', description: '', course_id: '' };
    isEditing.value = true;
    isCreating.value = true;
};

const updateModule = async (module) => {
    try {
        const response = await axios.put(route('admin.modules.update', module.id), module);
        const index = modules.value.findIndex(m => m.id === response.data.id);
        modules.value[index] = response.data;
        selectedModule.value = null;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные модуля успешно обновлены!',
        });
    } catch (error) {
        handleError(error);
    }
};

const createModule = async (module) => {
    try {
        const response = await axios.post(route('admin.modules.store'), module);
        modules.value.push(response.data);
        selectedModule.value = null;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Модуль успешно создан!',
        });
    } catch (error) {
        handleError(error);
    }
};

const deleteModule = async (moduleId) => {
    const result = await Swal.fire({
        title: 'Вы уверены?',
        text: "Вы не сможете восстановить этот модуль!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отмена',
    });

    if (result.isConfirmed) {
        try {
            await axios.delete(route('admin.modules.destroy', moduleId));
            modules.value = modules.value.filter(module => module.id !== moduleId);
            Swal.fire({
                icon: 'success',
                title: 'Успешно',
                text: 'Модуль успешно удален!',
            });
        } catch (error) {
            handleError(error);
        }
    }
};

const cancelEdit = () => {
    selectedModule.value = null;
    isEditing.value = false;
    isCreating.value = false;
};

const handleError = (error) => {
    if (error.response && error.response.status === 422) {
        errorMessage.value = error.response.data.message;
    } else {
        errorMessage.value = 'Произошла ошибка. Пожалуйста, попробуйте позже.';
    }
    Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: errorMessage.value,
    });
};

const getCourseTitle = (courseId) => {
    if (!courseId) return 'Не указан';
    const course = courses.value.find(course => course.id === courseId);
    return course ? course.title : 'Не указан';
};
</script>

<template>
    <div class="modules">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-1"></i> Список модулей</h5>
                <button class="btn btn-outline-primary btn-sm" @click="createNewModule">Добавить новый модуль</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Курс</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="module in modules" :key="module.id">
                            <td>{{ module.id }}</td>
                            <td>{{ module.title }}</td>
                            <td>{{ module.description }}</td>
                            <td>{{ getCourseTitle(module.course_id) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary"  @click="showModule(module.id)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning"  @click="editModule(module.id)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" @click="deleteModule(module.id)">
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
        <ModalModules
            v-if="selectedModule"
            :module="selectedModule"
            :isEditing="isEditing"
            :isCreating="isCreating"
            :courses="courses"
            @update="updateModule"
            @create="createModule"
            @cancel="cancelEdit"
            @error="handleError"
        />
    </div>
</template>

<style scoped>
.modules {
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
</style>