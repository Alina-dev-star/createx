<template>
    <div class="courses">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-1"></i> Список курсов</h5>
                <div class="btn-group">
                    <button class="btn btn-outline-primary btn-sm" @click="createNewCourse">
                        <i class="fas fa-plus me-1"></i> Добавить новый курс
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                            id="priceFilterDropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fas fa-sort-amount-down me-1"></i> Сортировать по цене
                        </button>
                        <div class="dropdown-menu" aria-labelledby="priceFilterDropdown">
                            <a class="dropdown-item" href="#" @click.prevent="sortCoursesByPrice('asc')">От дешёвых к
                                дорогим</a>
                            <a class="dropdown-item" href="#" @click.prevent="sortCoursesByPrice('desc')">От дорогих к
                                дешёвым</a>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                            id="categoryFilterDropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fas fa-filter me-1"></i> Фильтр по категории
                        </button>
                        <div class="dropdown-menu" aria-labelledby="categoryFilterDropdown">
                            <a class="dropdown-item" href="#" @click.prevent="filterCoursesByCategory('all')">Все</a>
                            <a class="dropdown-item" href="#" v-for="category in categories" :key="category"
                                @click.prevent="filterCoursesByCategory(category)">{{ category }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div class="input-group search-group">
                        <input v-model="searchQuery" type="text" class="form-control search-input"
                            placeholder="Поиск по названию, описанию или категории">
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
                            <th>Картинка</th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Цена</th>
                            <th>Категория</th>
                            <th>Продолжительность</th>
                            <th>Начало курса</th>
                            <th>Конец курса</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="course in filteredCourses" :key="course.id">
                            <td>{{ course.id }}</td>
                            <td>
                                <img :src="getImageUrl(course.image)" alt="Course Image" class="img-thumbnail"
                                    style="max-width: 50px;">
                            </td>
                            <td>{{ course.title }}</td>
                            <td>{{ course.description }}</td>
                            <td>{{ course.price }}</td>
                            <td>{{ course.category }}</td>
                            <td>{{ course.duration }}</td>
                            <td>{{ course.start_date }}</td>
                            <td>{{ course.end_date }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" @click="showCourse(course.id)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" @click="editCourse(course.id)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" @click="deleteCourse(course.id)">
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
        <ModalCourses v-if="selectedCourse" :course="selectedCourse" :isEditing="isEditing" :isCreating="isCreating"
            @update="updateCourse" @create="createCourse" @cancel="cancelEdit" @error="handleError" />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import ModalCourses from './Modal/ModalCourses.vue';

const props = defineProps({
    courses: {
        type: Array,
        required: true,
    },
});

const courses = ref(props.courses);
const selectedCourse = ref(null);
const isEditing = ref(false);
const isCreating = ref(false);
const errorMessage = ref('');
const searchQuery = ref('');
const sortByPrice = ref(null);
const filterByCategory = ref('all');

const categories = computed(() => {
    const uniqueCategories = new Set(courses.value.map(course => course.category));
    return Array.from(uniqueCategories);
});

const filteredCourses = computed(() => {
    let filtered = courses.value.filter(course => {
        const query = searchQuery.value.toLowerCase();
        return (
            course.title.toLowerCase().includes(query) ||
            course.description.toLowerCase().includes(query) ||
            course.category.toLowerCase().includes(query)
        );
    });

    if (filterByCategory.value !== 'all') {
        filtered = filtered.filter(course => course.category === filterByCategory.value);
    }

    if (sortByPrice.value) {
        filtered.sort((a, b) => {
            if (sortByPrice.value === 'asc') {
                return a.price - b.price;
            } else {
                return b.price - a.price;
            }
        });
    }

    return filtered;
});

const showCourse = async (courseId) => {
    try {
        const response = await axios.get(route('admin.courses.show', courseId));
        selectedCourse.value = response.data;
        isEditing.value = false;
        isCreating.value = false;
    } catch (error) {
        handleError(error);
    }
};

const editCourse = async (courseId) => {
    try {
        const response = await axios.get(route('admin.courses.edit', courseId));
        selectedCourse.value = response.data;
        isEditing.value = true;
        isCreating.value = false;
    } catch (error) {
        handleError(error);
    }
};

const createNewCourse = () => {
    selectedCourse.value = { title: '', description: '', price: '', category: '', duration: '', start_date: '', end_date: '', image: '' };
    isEditing.value = true;
    isCreating.value = true;
};

const updateCourse = async (course) => {
    try {
        const data = {
            title: course.title,
            description: course.description,
            what_they_will_learn: course.what_they_will_learn,
            price: course.price,
            category: course.category,
            duration: course.duration,
        };

        let formData = new FormData();
        for (let key in data) {
            formData.append(key, data[key]);
        }
        if (course.image instanceof File) {
            formData.append('image', course.image);
        }

        const response = await axios.put(route('admin.courses.update', course.id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data', //Important for file uploads
            },
        });

        const index = courses.value.findIndex(c => c.id === response.data.id);
        courses.value[index] = response.data;
        selectedCourse.value = null;
        isEditing.value = false;
        isCreating.value = false;
    } catch (error) {
        handleError(error);
    }
};

const createCourse = async (course) => {
    try {
        const formData = new FormData();
        formData.append('title', course.title);
        formData.append('description', course.description);
        formData.append('what_they_will_learn', course.what_they_will_learn); // Добавляем новое поле
        formData.append('price', course.price);
        formData.append('category', course.category);
        formData.append('duration', course.duration);
        if (course.image instanceof File) {
            formData.append('image', course.image);
        }

        const response = await axios.post(route('admin.courses.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        courses.value.push(response.data);
        selectedCourse.value = null;
        isEditing.value = false;
        isCreating.value = false;
    } catch (error) {
        handleError(error);
    }
};

const deleteCourse = async (courseId) => {
    if (confirm('Вы уверены, что хотите удалить этот курс?')) {
        try {
            await axios.delete(route('admin.courses.destroy', courseId));
            courses.value = courses.value.filter(course => course.id !== courseId);
        } catch (error) {
            handleError(error);
        }
    }
};

const cancelEdit = () => {
    selectedCourse.value = null;
    isEditing.value = false;
    isCreating.value = false;
};

const handleError = (error) => {
    if (error.response && error.response.status === 422) {
        console.error('Validation errors:', error.response.data.errors);
        errorMessage.value = error.response.data.message;
    } else {
        errorMessage.value = 'Произошла ошибка. Пожалуйста, попробуйте позже.';
    }
};

const getImageUrl = (imagePath) => {
    if (typeof imagePath === 'string' && imagePath.startsWith('http')) {
        return imagePath;
    }
    return `/storage/${imagePath}`;
};

const sortCoursesByPrice = (order) => {
    sortByPrice.value = order;
};

const filterCoursesByCategory = (category) => {
    filterByCategory.value = category;
};
</script>

<style scoped>
.courses {
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