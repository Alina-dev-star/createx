<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12 text-center">
        <h2 class="course-header">КУРСЫ</h2>
        <div class="row">
          <div v-for="course in courses" :key="course.course.id" class="col-md-4 mb-4">
            <div class="course-card" @click="selectCourse(course.course.id)">
              <div class="course-status" :class="getStatusClass(course.status)">
                {{ getStatusText(course.status) }}
              </div>
              <h3 class="course-title">{{ course.course.title }}</h3>
              <p class="course-description">{{ course.course.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="selectedCourse" class="row mt-5">
      <div class="col-md-12">
        <h3 class="module-title">{{ getSelectedCourseTitle() }}</h3>
        <div v-if="getSelectedCourseModules().length > 0" class="course-modules">
          <div v-for="module in getSelectedCourseModules()" :key="module.id" class="module-card" @click="selectModule(module.id)">
            <h4 class="module-title">{{ module.title }}</h4>
            <p class="module-description">{{ module.description }}</p>
          </div>
        </div>
        <div v-else>
          <p>Модули недоступны, так как ваша заявка на курс не одобрена.</p>
        </div>
      </div>
    </div>

    <div v-if="selectedModule" class="row mt-5">
      <div class="col-md-12">
        <h3 class="module-title">{{ getSelectedModuleTitle() }}</h3>
        <div v-if="getSelectedModuleLessons().length > 0" class="lesson-list">
          <div v-for="lesson in getSelectedModuleLessons()" :key="lesson.id" class="lesson-card">
            <p class="lesson-date"><strong>{{ formatDate(lesson.start_time) }}</strong></p>
            <p class="lesson-title"><strong>{{ lesson.title }}</strong></p>
            <p class="lesson-description">{{ lesson.description }}</p>
            <button v-if="lesson.status === 'pending'" @click="openRescheduleForm(lesson)">Перенести урок</button>
          </div>
        </div>
        <div v-else>
          <p>Уроки недоступны, так как ваша заявка на курс не одобрена.</p>
        </div>
      </div>
    </div>

    <!-- Форма для переноса урока -->
    <div v-if="rescheduleFormVisible" class="reschedule-form">
      <h4>Перенести урок</h4>
      <form @submit.prevent="submitRescheduleForm">
        <div class="form-group">
          <label for="reschedule_date">Дата:</label>
          <input type="date" id="reschedule_date" v-model="rescheduleForm.date" required />
        </div>
        <div class="form-group">
          <label for="reschedule_time">Время:</label>
          <input type="time" id="reschedule_time" v-model="rescheduleForm.time" required />
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <button @click="closeRescheduleForm" class="btn btn-secondary">Отмена</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  modules: Array,
  courses: {
    type: Array,
    required: true,
  },
});

const modules = ref(props.modules);
const courses = ref(props.courses);
const selectedCourse = ref(null);
const selectedModule = ref(null);
const rescheduleFormVisible = ref(false);
const rescheduleForm = ref({
  date: '',
  time: '',
  lesson_id: null,
});

const selectCourse = (courseId) => {
  console.log('Выбранный ID курса:', courseId);
  selectedCourse.value = courseId;
  selectedModule.value = null;
};

const selectModule = (moduleId) => {
  selectedModule.value = moduleId;
};

const getSelectedCourseTitle = () => {
  const course = courses.value.find(course => course.course.id === selectedCourse.value);
  return course ? course.course.title : '';
};

const getSelectedCourseModules = () => {
  const course = courses.value.find(course => course.course.id === selectedCourse.value);
  console.log('Выбранный курс:', course);
  console.log('Модули курса:', course ? course.course.modules : 'Модули не найдены');
  return course && course.status === 'approved' ? course.course.modules : [];
};

const getSelectedModuleTitle = () => {
  const module = getSelectedCourseModules().find(module => module.id === selectedModule.value);
  return module ? module.title : '';
};

const getSelectedModuleLessons = () => {
  const module = getSelectedCourseModules().find(module => module.id === selectedModule.value);
  console.log('Уроки модуля:', module ? module.lessons : 'Уроки не найдены');
  return module ? module.lessons : [];
};

const formatDate = (date) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' };
  return new Date(date).toLocaleDateString('ru-RU', options);
};

const getStatusClass = (status) => {
  switch (status) {
    case 'approved':
      return 'status-approved';
    case 'pending':
      return 'status-pending';
    case 'rejected':
      return 'status-rejected';
    default:
      return 'status-default';
  }
};

const getStatusText = (status) => {
  switch (status) {
    case 'approved':
      return 'Одобрено';
    case 'pending':
      return 'В ожидании';
    case 'rejected':
      return 'Отклонено';
    default:
      return 'Неизвестно';
  }
};

const openRescheduleForm = (lesson) => {
  rescheduleForm.value.lesson_id = lesson.id;
  rescheduleFormVisible.value = true;
};

const closeRescheduleForm = () => {
  rescheduleFormVisible.value = false;
  rescheduleForm.value = {
    date: '',
    time: '',
    lesson_id: null,
  };
};

const submitRescheduleForm = () => {
  axios.put(`/schedule/${rescheduleForm.value.lesson_id}`, rescheduleForm.value)
    .then(response => {
      console.log('Урок успешно перенесен:', response.data);
      closeRescheduleForm();
      fetchLessons();
    })
    .catch(error => {
      console.error('Ошибка при переносе урока:', error);
    });
};

const fetchLessons = () => {
  axios.get('/lessons')
    .then(response => {
      const module = getSelectedCourseModules().find(module => module.id === selectedModule.value);
      if (module) {
        module.lessons = response.data.lessons;
      }
    })
    .catch(error => {
      console.error('Ошибка при загрузке уроков:', error);
    });
};

onMounted(() => {
  console.log('Курсы:', courses.value);
  console.log('Модули:', modules.value);
});
</script>

<style scoped>
.course-header {
  font-size: 36px;
  font-weight: bold;
  color: #4A90E2;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 30px;
  position: relative;
}

.course-header::after {
  content: '';
  display: block;
  width: 100px;
  height: 4px;
  background-color: #4A90E2;
  margin: 10px auto 0;
}

.course-card {
  background-color: #EAF2F8;
  padding: 20px;
  margin-bottom: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  cursor: pointer;
  position: relative;
}

.course-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.course-title {
  font-size: 28px;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.course-description {
  font-size: 18px;
  color: #666;
  line-height: 1.6;
}

.course-status {
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 14px;
  font-weight: bold;
  margin-bottom: 10px;
  text-align: center;
}

.status-approved {
  background-color: #28A745;
  color: white;
}

.status-pending {
  background-color: #FFA500;
  color: white;
}

.status-rejected {
  background-color: #DC3545;
  color: white;
}

.status-default {
  background-color: #6C757D;
  color: white;
}

.course-modules {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
  flex-wrap: wrap;
  justify-content: center;
}

.module-card {
  background-color: #EAF2F8;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  cursor: pointer;
  width: 300px;
}

.module-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.module-title {
  font-size: 24px;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.module-description {
  font-size: 16px;
  color: #666;
  line-height: 1.6;
}

.lesson-list {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: center;
}

.lesson-card {
  background-color: #EAF2F8;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  width: 300px;
}

.lesson-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.lesson-date {
  font-size: 18px;
  color: #4A90E2;
  margin-bottom: 10px;
}

.lesson-title {
  font-size: 20px;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.lesson-description {
  font-size: 16px;
  color: #666;
  margin-bottom: 10px;
}

.reschedule-form {
  margin-top: 20px;
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.reschedule-form h4 {
  margin-bottom: 20px;
}

.reschedule-form .form-group {
  margin-bottom: 15px;
}

.reschedule-form label {
  display: block;
  margin-bottom: 5px;
}

.reschedule-form input {
  width: 100%;
  padding: 10px;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
}

.reschedule-form .btn {
  margin-right: 10px;
}
</style>