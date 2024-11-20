<template>
    <header>
        <div class="container py-5">
            <HeaderApp :canLogin="canLogin" :canRegister="canRegister" />

            <p class="text-danger text-uppercase fw-bold text-center" style="margin-top: 120px;">Курсы</p>
            <p v-if="course" class="text-center fw-bold"
                style="font-size: 46px; margin-top: 42px; margin-bottom: 102px;">{{ course.title }}</p>
        </div>
    </header>

    <main class="container">
        <div v-if="course" class="mt-5">
            <div class="row d-flex justify-content-between">
                <div class="col-md-6 mb-5">
                    <div style="margin-top: 120px;">
                        <p class="fw-bold" style="font-size: 46px;">О курсе</p>
                        <p class="mt-5">{{ course.description }}</p>
                    </div>
                    <div>
                        <p class="fw-bold" style="margin-top: 48px; font-size: 28px;">Вы узнаете:</p>
                        <div class="mt-custom mt-4">
                            <div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Творчество: Подчеркивает творческий<br>потенциал и возможности для студентов.</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Эксклюзивность: Символизирует уникальность<br>и исключительность программ.</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Превосходство: Отражает стремление к<br>высокому уровню качества образования.</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Инновации: Подчеркивает инновационный<br>подход к обучению искусству.</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Создание: Фокусируется на процессе создания<br>искусства.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-5" style="margin-top: 120px; width: 500px;">
                    <div class="card shadow-lg p-4">
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label for="dates" class="form-label">Даты</label>
                                    <p class="" style="color: #FF3F3A; font-size: 24px;">{{
                                        formatDate(course.start_date) }} – {{ formatDate(course.end_date) }}</p>
                                    <small class="form-text text-muted">Metus turpis sit lorem lacus, in elit tellus
                                        lacus.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Продолжительность</label>
                                    <p class="" style="color: #FF3F3A; font-size: 24px;">{{ course.duration }}</p>
                                    <small class="form-text text-muted">Rhoncus pellentesque auctor auctor orci
                                        vulputate faucibus quis ut.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Цена</label>
                                    <p class="" style="color: #FF3F3A; font-size: 24px;">₽ {{ course.price }}</p>
                                    <small class="form-text text-muted">Nulla sem adipiscing adipiscing felis fringilla.
                                        Adipiscing mauris quam ac elit tristique dis.</small>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-custom-gradient" data-bs-toggle="modal"
                                        data-bs-target="#joinCourseModal" :data-course-id="course.id"
                                        :disabled="!isAuthenticated">
                                        Присоединиться к курсу
                                    </button>
                                    <p v-if="!isAuthenticated" class="text-danger mt-3">Чтобы присоединиться к курсу, пожалуйста, войдите или зарегистрируйтесь.</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно -->
        <JoinCourseModal :course-id="course.id" />

        <div class="mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="p-4">
                        <div>
                            <div class="row d-flex justify-content-between align-items-center">
                                <div class="col-md-4 col-sm-6 col-xs-12 text-center" style="background-color: #C1908B;">
                                    <img src="/teacher/teacher_3.png" alt="Максим Козлов" class="img-fluid">
                                </div>
                                <div class="col-md-7 col-sm-6 col-xs-12">
                                    <h2 class="mb-3 fw-bold">Куратор курса</h2>
                                    <h3 class="mb-4 fw-bold" style="font-size: 28px;">Максим Козлов</h3>
                                    <p class="mb-4" style="font-size: 18px;">Преподаватель Декоративного Искусства</p>
                                    <p class="mb-4">
                                        Mattis adipiscing aliquam eu proin metus a iaculis faucibus. Tempus curabitur
                                        venenatis, vulputate venenatis fermentum ante. Nisl, amet id semper semper quis
                                        commodo, consequat. Massa rhoncus sit morbi odio. Sit maecenas nibh consectetur
                                        vel diam. Sem vulputate molestie laoreet at massa sed pharetra. Ac commodo
                                        platea id habitasse proin. Nullam sit nec ipsum posuere non. Nam vel aliquam
                                        tristique sollicitudin interdum quam.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <div style="margin-top: 126px;">
        <FooterApp />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import HeaderApp from '@/Components/Header/HeaderApp.vue';
import FooterApp from '@/Components/Footer/FooterApp.vue';
import JoinCourseModal from '@/Components/MyComponents/JoinCourseModal.vue'; // Импортируем компонент модального окна

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
    canLogin: {
        type: Boolean,
        required: true,
    },
    canRegister: {
        type: Boolean,
        required: true,
    },
});

const course = ref(props.course);
const isAuthenticated = ref(!!usePage().props.user);
console.log('Is Authenticated:', isAuthenticated.value);
console.log('User:', usePage().props.user);

const formatDate = (dateString) => {
    const options = { month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('ru-RU', options);
};
</script>

<style>
.mb-50 {
    margin-bottom: 50px;
}

.btn-custom-gradient {
    background: linear-gradient(45deg, #FF3F3A, #F75E05);
    color: white;
    /* Цвет текста на кнопке */
    border: none;
    /* Убираем границу */
}

.btn-custom-gradient:hover {
    background: linear-gradient(45deg, #F75E05, #FF3F3A);
    /* Меняем градиент при наведении */
    color: white;
    /* Цвет текста при наведении */
}

.img-check {
    width: 16px;
    height: 16px;
    object-fit: cover;
    margin-right: 18px;
}

.custom-accordion-button {
    background-color: transparent;
    border: none;
    box-shadow: none;
    padding: 0;
    margin: 0;
}

.accordion-button:not(.collapsed) {
    color: inherit;
    background-color: transparent;
    box-shadow: none;
}

.accordion-button::after {
    display: none;
}

.accordion-item {
    border: none;
}

.accordion-body {
    padding: 0;
}

.accordion-collapse {
    transition: height 0.3s ease;
}
</style>