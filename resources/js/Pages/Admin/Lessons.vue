<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import ModalLessons from './Modal/ModalLessons.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    lessons: {
        type: Array,
        required: true,
    },
    modules: {
        type: Array,
        required: true,
    },
    courses: {
        type: Array,
        required: true,
    },
});

const lessons = ref(props.lessons);
const selectedLesson = ref(null);
const selectedModule = ref(null);
const selectedCourse = ref(null);
const isEditing = ref(false);
const isCreating = ref(false);

// Вычисляемое свойство для получения уроков с модулями и курсами
const lessonsWithModulesAndCourses = computed(() => {
    return lessons.value.map(lesson => {
        const module = props.modules.find(module => module.id === lesson.module_id);
        const course = module ? props.courses.find(course => course.id === module.course_id) : null;
        return {
            ...lesson,
            module,
            course,
        };
    });
});

const showLesson = async (lessonId) => {
    try {
        const response = await axios.get(route('admin.lessons.show', { module: props.modules[0].id, lesson: lessonId }));
        selectedLesson.value = response.data.lesson;
        selectedModule.value = response.data.module;
        selectedCourse.value = response.data.course;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные урока успешно загружены!',
        });
    } catch (error) {
        console.error('Error fetching lesson:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при загрузке данных урока.',
        });
    }
};

const createNewLesson = () => {
    selectedLesson.value = { title: '', description: '', start_time: '', end_time: '', module_id: props.modules[0].id };
    selectedModule.value = null;
    selectedCourse.value = null;
    isEditing.value = true;
    isCreating.value = true;
};

const createLesson = async (lesson) => {
    try {
        const response = await axios.post(route('admin.lessons.store', { module: lesson.module_id }), lesson);
        lessons.value.push(response.data);
        selectedLesson.value = null;
        selectedModule.value = null;
        selectedCourse.value = null;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Урок успешно создан!',
        });
    } catch (error) {
        console.error('Error creating lesson:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при создании урока.',
        });
    }
};

const cancelEdit = () => {
    selectedLesson.value = null;
    selectedModule.value = null;
    selectedCourse.value = null;
    isEditing.value = false;
    isCreating.value = false;
};

const editLesson = async (lessonId) => {
    try {
        const response = await axios.get(route('admin.lessons.edit', { module: props.modules[0].id, lesson: lessonId }));
        selectedLesson.value = response.data.lesson;
        selectedModule.value = response.data.module;
        selectedCourse.value = response.data.course;
        isEditing.value = true;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные урока успешно загружены для редактирования!',
        });
    } catch (error) {
        console.error('Error fetching lesson for edit:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при загрузке данных урока для редактирования.',
        });
    }
};

const updateLesson = async (lesson) => {
    try {
        const response = await axios.put(route('admin.lessons.update', { module: lesson.module_id, lesson: lesson.id }), lesson);
        const index = lessons.value.findIndex(l => l.id === response.data.id);
        lessons.value[index] = response.data;
        selectedLesson.value = null;
        selectedModule.value = null;
        selectedCourse.value = null;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные урока успешно обновлены!',
        });
    } catch (error) {
        console.error('Error updating lesson:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при обновлении данных урока.',
        });
    }
};

const deleteLesson = async (lessonId) => {
    const result = await Swal.fire({
        title: 'Вы уверены?',
        text: "Вы не сможете восстановить этот урок!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отмена',
    });

    if (result.isConfirmed) {
        try {
            await axios.delete(route('admin.lessons.destroy', { module: props.modules[0].id, lesson: lessonId }));
            lessons.value = lessons.value.filter(lesson => lesson.id !== lessonId);
            Swal.fire({
                icon: 'success',
                title: 'Успешно',
                text: 'Урок успешно удален!',
            });
        } catch (error) {
            console.error('Error deleting lesson:', error);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: 'Произошла ошибка при удалении урока.',
            });
        }
    }
};
</script>

<template>
    <div class="lessons">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-1"></i> Список уроков</h5>
                <button class="btn btn-outline-primary btn-sm" @click="createNewLesson">Добавить новый урок</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Начало</th>
                            <th>Конец</th>
                            <th>Модуль</th>
                            <th>Курс</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="lesson in lessonsWithModulesAndCourses" :key="lesson.id">
                            <td>{{ lesson.id }}</td>
                            <td>{{ lesson.title }}</td>
                            <td>{{ lesson.description }}</td>
                            <td>{{ lesson.start_time }}</td>
                            <td>{{ lesson.end_time }}</td>
                            <td>{{ lesson.module ? lesson.module.title : 'Не указан' }}</td>
                            <td>{{ lesson.course ? lesson.course.title : 'Не указан' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" @click="showLesson(lesson.id)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" @click="editLesson(lesson.id)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" @click="deleteLesson(lesson.id)">
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
        <ModalLessons v-if="selectedLesson" :lesson="selectedLesson" :isEditing="isEditing" :isCreating="isCreating"
            :modules="modules" :course="selectedCourse" @update="updateLesson" @create="createLesson"
            @cancel="cancelEdit" />
    </div>
</template>

<style scoped>
.lessons {
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