<template>
  <div class="week-schedule-table">
    <table class="schedule-table">
      <thead>
        <tr>
          <th>Дата</th>
          <th v-for="interval in timeIntervals" :key="interval">{{ interval }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(daySchedule, date) in schedules" :key="date">
          <td>{{ date }}</td>
          <td v-for="interval in timeIntervals" :key="interval">
            {{ daySchedule[interval] ? 'Запланировано' : '-' }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    schedules: Object, // Изменили на Object
    timeIntervals: Array,
  },
  mounted() {
    console.log('Received schedules:', this.schedules); // Добавьте это для отладки
  },
};
</script>

<style scoped>
.week-schedule-table {
  margin-top: 20px;
  overflow-x: auto;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.schedule-table {
  width: 100%;
  border-collapse: collapse;
  font-family: 'Arial', sans-serif;
  background-color: #ffffff;
}

.schedule-table th,
.schedule-table td {
  padding: 15px 20px;
  border: 1px solid #e0e0e0;
  text-align: center;
}

.schedule-table th {
  background-color: #007bff;
  color: #ffffff;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.schedule-table tbody tr:nth-child(even) {
  background-color: #f8f9fa;
}

.schedule-table tbody tr:hover {
  background-color: #e9ecef;
}

.schedule-table td {
  font-size: 14px;
  color: #333333;
}

.schedule-table td:first-child {
  font-weight: bold;
  background-color: #f2f2f2;
}
</style>