<template>
  <div class="container">
    <!-- Форма для добавления занятий -->
    <form @submit.prevent="submitSchedule" class="schedule-form">
      <div class="form-group">
        <label for="date">Дата:</label>
        <input type="date" id="date" v-model="form.date" required />
      </div>
      <div class="form-group">
        <label for="time_interval">Время:</label>
        <select id="time_interval" v-model="form.time_interval" required>
          <option v-for="interval in timeIntervals" :key="interval" :value="interval">
            {{ interval }}
          </option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>

    <!-- Сообщение об успешном добавлении -->
    <div v-if="success" class="success-message">
      {{ success }}
    </div>

    <!-- Таблица расписания на неделю -->
    <WeekScheduleTable :schedules="schedules" :timeIntervals="timeIntervals" />

    <!-- Список уроков, которые нужно перенести -->
    <div v-if="pendingLessons && pendingLessons.length > 0" class="pending-lessons">
      <h3>Уроки, которые нужно перенести:</h3>
      <ul>
        <li v-for="lesson in pendingLessons" :key="lesson.id">
          {{ lesson.title }}
          <button @click="updateSchedule(lesson)">Перенести</button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import WeekScheduleTable from '@/Components/MyComponents/WeekScheduleTable.vue';
import axios from 'axios';

export default {
  components: {
    WeekScheduleTable,
  },
  props: {
    success: String,
    timeIntervals: Array,
    schedules: Object, // Изменили на Object
  },
  setup(props) {
    const form = useForm({
      date: '',
      time_interval: '',
    });

    const schedules = ref(props.schedules || {}); // Изменили на Object
    const pendingLessons = ref([]);

    const submitSchedule = () => {
      console.log('Submitting schedule:', form.data()); // Добавьте это для отладки
      form.post(route('schedule.store'), {
        onSuccess: () => {
          form.reset();
          fetchSchedules();
        },
      });
    };

    const fetchSchedules = async () => {
      try {
        const response = await axios.get('/schedule');
        schedules.value = response.data.schedules;
        pendingLessons.value = response.data.pendingLessons || []; // Убедитесь, что pendingLessons инициализирован как массив
        console.log('Fetched schedules:', schedules.value); // Добавьте это для отладки
      } catch (error) {
        console.error('Error fetching schedules:', error);
      }
    };

    const updateSchedule = (lesson) => {
      form.date = '';
      form.time_interval = '';
      form.lesson_id = lesson.id;
    };

    onMounted(() => {
      fetchSchedules();
    });

    return { form, submitSchedule, timeIntervals: props.timeIntervals, schedules, pendingLessons, updateSchedule };
  },
};
</script>

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
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  margin-bottom: 5px;
  font-weight: bold;
  color: #333333;
}

.form-group input,
.form-group select {
  padding: 10px;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  font-size: 14px;
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
  font-weight: bold;
}
</style>