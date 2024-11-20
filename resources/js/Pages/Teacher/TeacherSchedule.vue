<template>
    <div class="container">
      <h1>Расписание для преподавателя</h1>
      <table>
        <thead>
          <tr>
            <th>Студент</th>
            <th>Урок</th>
            <th>Дата</th>
            <th>Время</th>
            <th>Статус</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="schedule in schedules" :key="schedule.id">
            <td>{{ schedule.user.name }}</td>
            <td>{{ schedule.lesson.title }}</td>
            <td>{{ schedule.date }}</td>
            <td>{{ schedule.time }}</td>
            <td>{{ schedule.completed ? 'Пройден' : 'Не пройден' }}</td>
            <td>
              <button @click="markCompleted(schedule)">Отметить как пройденный</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  
  export default {
    setup() {
      const schedules = ref([]);
  
      const fetchSchedules = async () => {
        try {
          const response = await axios.get('/schedule');
          schedules.value = response.data.schedules;
        } catch (error) {
          console.error('Error fetching schedules:', error);
        }
      };
  
      const markCompleted = async (schedule) => {
        try {
          await axios.put(`/schedule/${schedule.id}/mark-completed`);
          fetchSchedules();
        } catch (error) {
          console.error('Error marking schedule as completed:', error);
        }
      };
  
      onMounted(() => {
        fetchSchedules();
      });
  
      return { schedules, markCompleted };
    },
  };
  </script>