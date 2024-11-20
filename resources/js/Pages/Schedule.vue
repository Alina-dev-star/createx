<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import StudentSchedule from './Student/StudentSchedule.vue';
import TeacherSchedule from './Teacher/TeacherSchedule.vue';
import AdminSchedule from './Admin/AdminSchedule.vue';

const props = defineProps({
    userRole: String,
    timeIntervals: Array,
    schedules: Object, // Изменили на Object
});

const userRole = ref(props.userRole);
const timeIntervals = ref(props.timeIntervals);
const schedules = ref(props.schedules || {}); // Изменили на Object

onMounted(() => {
    console.log('User role:', userRole.value); // Проверьте, что роль передается корректно
    console.log('Time intervals:', timeIntervals.value); // Проверьте, что интервалы времени передаются корректно
    console.log('Schedules:', schedules.value); // Проверьте, что расписание на неделю передается корректно
});
</script>

<template>
    <Head title="Расписание" />

    <AuthenticatedLayout :userRole="userRole">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Расписание
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="userRole === 'student'">
                            <StudentSchedule :timeIntervals="timeIntervals" :schedules="schedules" /> <!-- Передайте расписание на неделю -->
                        </div>
                        <div v-else-if="userRole === 'teacher'">
                            <TeacherSchedule />
                        </div>
                        <div v-else-if="userRole === 'admin'">
                            <AdminSchedule />
                        </div>
                        <div v-else>
                            <p>You do not have access to this page.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  text-align: center;
  margin-bottom: 20px;
}

.schedule-form {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  margin-bottom: 5px;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
}

.btn-primary {
  background-color: #007bff;
  color: #fff;
}

.success-message {
  color: green;
  margin-top: 10px;
  text-align: center;
}
</style>