<template>
    <div>
        <h3 class="text-lg font-semibold mb-4">Расписание для администратора</h3>
        
        <!-- Выбор преподавателя -->
        <div class="mb-4">
            <label for="teacherSelect" class="block text-sm font-medium text-gray-700">Выберите преподавателя:</label>
            <select id="teacherSelect" v-model="selectedTeacher" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.name }}</option>
            </select>
        </div>

        <!-- Отображение расписания -->
        <div v-if="schedule && schedule.length > 0">
            <h4 class="text-md font-semibold mb-2">Расписание:</h4>
            <ul>
                <li v-for="entry in schedule" :key="entry.id" class="border-b py-2">
                    {{ entry.date }} {{ entry.time }} - {{ entry.student.name }}
                </li>
            </ul>
        </div>
        <div v-else>
            <p>Расписание отсутствует.</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        teachers: Array,
        schedule: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            selectedTeacher: null,
        };
    },
   
};
</script>