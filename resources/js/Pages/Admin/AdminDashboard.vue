<template>
    <div class="admin-dashboard">
        <!-- Main Content -->
        <div class="container-fluid mt-4">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-2">
                    <div class="sidebar">
                        <div class="sidebar-header">
                            <h3>Панель управления</h3>
                        </div>
                        <div class="sidebar-menu">
                            <a href="#" class="sidebar-menu-item" :class="{ active: activeTab === 'applications' }" @click.prevent="setActiveTab('applications')">
                                <i class="fas fa-clipboard-list me-2"></i> Записи
                            </a>
                            <a href="#" class="sidebar-menu-item" :class="{ active: activeTab === 'students' }" @click.prevent="setActiveTab('students')">
                                <i class="fas fa-user-graduate me-2"></i> Студенты
                            </a>
                            <a href="#" class="sidebar-menu-item" :class="{ active: activeTab === 'teachers' }" @click.prevent="setActiveTab('teachers')">
                                <i class="fas fa-chalkboard-teacher me-2"></i> Учителя
                            </a>
                            <a href="#" class="sidebar-menu-item" :class="{ active: activeTab === 'courses' }" @click.prevent="setActiveTab('courses')">
                                <i class="fas fa-book me-2"></i> Курсы
                            </a>
                            <a href="#" class="sidebar-menu-item" :class="{ active: activeTab === 'modules' }" @click.prevent="setActiveTab('modules')">
                                <i class="fas fa-cubes me-2"></i> Модули
                            </a>
                            <a href="#" class="sidebar-menu-item" :class="{ active: activeTab === 'lessons' }" @click.prevent="setActiveTab('lessons')">
                                <i class="fas fa-chalkboard me-2"></i> Уроки
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Content -->
                <div class="col-md-10">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <i class="fas fa-table me-1"></i>
                            {{ activeTab === 'applications' ? 'Записи' : activeTab === 'students' ? 'Студенты' : activeTab === 'teachers' ? 'Учителя' : activeTab === 'courses' ? 'Курсы' : activeTab === 'modules' ? 'Модули' : activeTab === 'lessons' ? 'Уроки' : 'Настройки' }}
                            <div v-if="activeTab === 'lessons'">
                                <select v-model="selectedModules" class="form-select" @change="updateSelectedModules">
                                    <option v-for="module in modules" :value="module.id">{{ module.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <Application 
                                    v-if="activeTab === 'applications'" 
                                    :applications="applications" 
                                    :courses="courses" 
                                />
                                <Students v-else-if="activeTab === 'students'" :students="students" />
                                <Teachers v-else-if="activeTab === 'teachers'" :teachers="teachers" :courses="courses" />
                                <Courses v-else-if="activeTab === 'courses'" :courses="courses" />
                                <Modules v-else-if="activeTab === 'modules'" :modules="modules" :courses="courses" />
                                <Lessons 
                                    v-else-if="activeTab === 'lessons'" 
                                    :lessons="lessons" 
                                    :modules="modules" 
                                    :courses="courses"
                                    :selectedModules="selectedModules"
                                />
                                <div v-else>
                                    <p>Содержимое для других вкладок</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Application from './Application.vue';
import Students from './Students.vue';
import Teachers from './Teachers.vue';
import Courses from './Courses.vue';
import Modules from './Modules.vue';
import Lessons from './Lessons.vue';

const props = defineProps({
    applications: {
        type: Array,
        required: true,
    },
    students: {
        type: Array,
        required: true,
    },
    teachers: {
        type: Array,
        required: true,
    },
    courses: {
        type: Array,
        required: true,
    },
    modules: {
        type: Array,
        required: true,
    },
    lessons: {
        type: Array,
        required: true,
    },
});

const activeTab = ref('applications');
const selectedModules = ref(null);

const setActiveTab = (tab) => {
    activeTab.value = tab;
    if (tab === 'lessons') {
        selectedModules.value = props.modules[0]?.id || null;
    }
};

const updateSelectedModules = () => {
    // Этот метод можно использовать для дополнительной логики, если необходимо
};
</script>

<style scoped>
.admin-dashboard {
    font-family: 'Arial', sans-serif;
}

.navbar {
    margin-bottom: 20px;
}

.card {
    border: none;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.sidebar {
    background: linear-gradient(135deg, #FF3F3A, #F75E05);
    color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.sidebar-header {
    text-align: center;
    margin-bottom: 20px;
}

.sidebar-menu-item {
    display: block;
    padding: 10px 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
    color: white;
    text-decoration: none;
}

.sidebar-menu-item:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.sidebar-menu-item.active {
    background-color: rgba(255, 255, 255, 0.3);
}
</style>