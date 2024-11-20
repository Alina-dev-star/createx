<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import ModalApplication from './Modal/ModalApplication.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    applications: {
        type: Array,
        required: true,
    },
    courses: {
        type: Array,
        required: true,
    },
});

const applications = ref(props.applications);
const courses = ref(props.courses);
const selectedApplication = ref(null);
const isEditing = ref(false);
const isCreating = ref(false);
const sortByStatus = ref('all');
const searchQuery = ref('');

const getCourseTitle = (courseId) => {
    const course = courses.value.find(course => course.id === courseId);
    return course ? course.title : 'Не указан';
};

const getStatusText = (status) => {
    switch (status) {
        case 'pending':
            return 'В ожидании';
        case 'approved':
            return 'Одобрено';
        case 'rejected':
            return 'Отклонено';
        case 'completed':
            return 'Завершено';
        default:
            return 'Неизвестно';
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'pending':
            return 'text-warning';
        case 'approved':
            return 'text-success';
        case 'rejected':
            return 'text-danger';
        case 'completed':
            return 'text-info';
        default:
            return 'text-secondary';
    }
};

const filteredApplications = computed(() => {
    return applications.value.filter(application => {
        const query = searchQuery.value.toLowerCase();
        return (
            application.name.toLowerCase().includes(query) ||
            application.surname.toLowerCase().includes(query) ||
            application.email.toLowerCase().includes(query) ||
            application.phone.toLowerCase().includes(query)
        );
    });
});

const showApplication = async (applicationId) => {
    try {
        const response = await axios.get(route('admin.applications.show', applicationId));
        selectedApplication.value = response.data;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные заявки успешно загружены!',
        });
    } catch (error) {
        console.error('Error fetching application:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при загрузке данных заявки.',
        });
    }
};

const editApplication = async (applicationId) => {
    try {
        const response = await axios.get(route('admin.applications.edit', { id: applicationId }));
        selectedApplication.value = response.data;
        isEditing.value = true;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Данные заявки успешно загружены для редактирования!',
        });
    } catch (error) {
        console.error('Error fetching application for edit:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при загрузке данных заявки для редактирования.',
        });
    }
};

const createNewApplication = () => {
    selectedApplication.value = { name: '', surname: '', phone: '', email: '', agree: false, status: 'pending', course_id: null };
    isEditing.value = true;
    isCreating.value = true;
};

const updateApplication = async (application) => {
    try {
        const response = await axios.put(route('admin.applications.update', application.id), application);
        const index = applications.value.findIndex(app => app.id === response.data.id);
        applications.value[index] = response.data;
        selectedApplication.value = null;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Заявка успешно обновлена!',
        });
    } catch (error) {
        console.error('Error updating application:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Произошла ошибка при обновлении заявки.',
        });
    }
};

const createApplication = async (application) => {
    try {
        const response = await axios.post(route('applications.store'), application);
        applications.value.push(response.data);
        selectedApplication.value = null;
        isEditing.value = false;
        isCreating.value = false;
        Swal.fire({
            icon: 'success',
            title: 'Успешно',
            text: 'Заявка успешно создана!',
        });
    } catch (error) {
        if (error.response && error.response.status === 422) {
            console.error('Validation errors:', error.response.data.errors);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: 'Произошла ошибка при создании заявки.',
            });
        } else {
            console.error('Error creating application:', error);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: 'Произошла ошибка при создании заявки.',
            });
        }
    }
};

const deleteApplication = async (applicationId) => {
    const result = await Swal.fire({
        title: 'Вы уверены?',
        text: "Вы не сможете восстановить эту заявку!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отмена',
    });

    if (result.isConfirmed) {
        try {
            await axios.delete(route('admin.applications.destroy', applicationId));
            applications.value = applications.value.filter(application => application.id !== applicationId);
            Swal.fire({
                icon: 'success',
                title: 'Успешно',
                text: 'Заявка успешно удалена!',
            });
        } catch (error) {
            console.error('Error deleting application:', error);
            Swal.fire({
                icon: 'error',
                title: 'Ошибка',
                text: 'Произошла ошибка при удалении заявки.',
            });
        }
    }
};

const cancelEdit = () => {
    selectedApplication.value = null;
    isEditing.value = false;
    isCreating.value = false;
};

const sortApplications = (status) => {
    sortByStatus.value = status;
    if (status === 'all') {
        applications.value = props.applications;
    } else {
        applications.value = props.applications.filter(app => app.status === status);
    }
};
</script>

<template>
    <div class="applications">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-1"></i> Последние записи</h5>
                <div class="btn-group">
                    <button class="btn btn-outline-primary btn-sm" @click="createNewApplication">
                        <i class="fas fa-plus"></i> Добавить новую
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-sort"></i> Сортировать по статусу
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sortDropdown">
                            <a class="dropdown-item" href="#" @click.prevent="sortApplications('all')">Все</a>
                            <a class="dropdown-item" href="#" @click.prevent="sortApplications('pending')">В ожидании</a>
                            <a class="dropdown-item" href="#" @click.prevent="sortApplications('approved')">Одобрено</a>
                            <a class="dropdown-item" href="#" @click.prevent="sortApplications('rejected')">Отклонено</a>
                            <a class="dropdown-item" href="#" @click.prevent="sortApplications('completed')">Завершено</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom">
                    <div class="input-group search-group">
                        <input v-model="searchQuery" type="text" class="form-control search-input" placeholder="Поиск по имени, фамилии, email или телефону">
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
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Курс</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="application in filteredApplications" :key="application.id">
                            <td>{{ application.name }}</td>
                            <td>{{ application.surname }}</td>
                            <td>{{ application.phone }}</td>
                            <td>{{ application.email }}</td>
                            <td>{{ getCourseTitle(application.course_id) }}</td>
                            <td>
                                <span :class="getStatusClass(application.status)">
                                    {{ getStatusText(application.status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" @click="showApplication(application.id)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" @click="editApplication(application.id)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" @click="deleteApplication(application.id)">
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
        <ModalApplication
            v-if="selectedApplication"
            :application="selectedApplication"
            :isEditing="isEditing"
            :isCreating="isCreating"
            :courses="courses"
            @update="updateApplication"
            @create="createApplication"
            @cancel="cancelEdit"
        />
    </div>
</template>

<style scoped>
.applications {
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

.text-warning {
    color: #ffc107;
}

.text-success {
    color: #28a745;
}

.text-danger {
    color: #dc3545;
}

.text-info {
    color: #17a2b8;
}

.text-secondary {
    color: #6c757d;
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