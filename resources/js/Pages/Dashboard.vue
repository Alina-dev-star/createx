<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import AdminDashboard from './Admin/AdminDashboard.vue';
import TeacherDashboard from './Teacher/TeacherDashboard.vue';
import StudentDashboard from './Student/StudentDashboard.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    userRole: String,
    modules: Array,
    applications: Array,
    students: Array,
    teachers: Array,
    courses: Array,
    userCourses: Array,
    lessons: Array,
});

const activeTab = ref('applications');

const setActiveTab = (tab) => {
    activeTab.value = tab;
};

const userRole = ref(props.userRole);
const modules = ref(props.modules);
const userCourses = ref(props.userCourses);
const lessons = ref(props.lessons || []);

onMounted(() => {
    console.log(userRole.value);
    console.log(modules.value);
    console.log(userCourses.value);
    console.log(lessons.value);
});
</script>

<template>
    <Head title="Панель управления" />

    <AuthenticatedLayout>
        <div class="p-6 text-gray-900">
            <div v-if="userRole === 'admin'">
                <AdminDashboard 
                    :applications="applications" 
                    :students="students" 
                    :teachers="teachers"
                    :courses="courses" 
                    :modules="modules" 
                    :lessons="lessons" 
                />
            </div>
            <div v-else-if="userRole === 'teacher'">
                <TeacherDashboard />
            </div>
            <div v-else-if="userRole === 'student'">
                <StudentDashboard 
                    :modules="modules" 
                    :courses="userCourses" 
                />
            </div>
            <div v-else>
                <p>У вас нет доступа к этой странице.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>