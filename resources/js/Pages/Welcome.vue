<script setup>
import { Head } from '@inertiajs/vue3';
import HeaderApp from '@/Components/Header/HeaderApp.vue';
import FooterApp from '@/Components/Footer/FooterApp.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    courses: {
        type: Array,
        required: true,
    },
    teachers: {
        type: Array,
        required: true,
    },
});

const getImageUrl = (imagePath) => {
    if (imagePath.startsWith('http')) {
        return imagePath;
    }
    return `/storage/${imagePath}`;
};
</script>

<template>

    <Head title="Createx" />


    <header>
        <div class="container py-5">
            <HeaderApp :canLogin="canLogin" :canRegister="canRegister" />

            <div class="d-flex justify-content-between text-black mt-3">
                <div>
                    <p class="platform-text_1"
                        style=" padding-top: 155px; font-weight: bold; text-transform: uppercase;">Платформа курса</p>
                    <p class="platform-text_2">Наслаждайтесь<br>учебой<br>с курсами Createx</p>
                    <div class="d-flex mt-custom">
                        <button type="button" class="btn btn-outline-danger me-3 btn-custom">О нас</button>
                       <button type="button" class="btn btn-outline-danger btn-custom">Изучите курсы</button>
                    </div>
                </div>
                <div class="illustration">
                    <img src="/background/illustration_1.png" alt="illustration">
                </div>
            </div>

            <div class="d-flex justify-content-around align-items-center text-black mt-custom">
                <span class="number-46">1200 </span>
                <p>Студенты закончили</p>
                <img src="/background/ellipse.png" alt="ellipse" class="img-fixed-size">
                <span class="number-46">84 </span>
                <p>Партнера школы</p>
                <img src="/background/ellipse.png" alt="ellipse" class="img-fixed-size">
                <span class="number-46">5 </span>
                <p>Квалифицированные репетиторы</p>
                <img src="/background/ellipse.png" alt="ellipse" class="img-fixed-size">
                <span class="number-46">5 </span>
                <p>Многолетний опыт</p>
            </div>
        </div>
    </header>



    <main class="mt-6">
        <div class="img_background mt-custom_2">
            <div class="container">
                <div class="d-flex justify-content-around text-black mt-3">
                    <div style="margin-top: 40px;">
                        <img src="/background/illustration_2.png" alt="illustration">
                    </div>
                    <div>
                        <p class="platform-text_3">Кто мы</p>
                        <p class="platform-text_2">Почему Createx?</p>
                        <div class="mt-custom">
                            <div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Творчество: Подчеркивает творческий<br>потенциал и возможности для студентов.</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Эксклюзивность: Символизирует уникальность<br>и исключительность программ.</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Превосходство: Отражает стремление к<br>высокому уровню качества образования.</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Инновации: Подчеркивает инновационный<br>подход к обучению искусству.</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="/background/check.png" alt="check" class="img-check">
                                    <p>Создание: Фокусируется на процессе создания<br>искусства.</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-custom mt-custom">Подробнее о
                                нас</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-custom_2">
            <div class="container">
                <p class="platform-text_3">Готовы учиться?</p>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="platform-text_2">Рекомендуемые курсы</p>
                    <button type="button" class="btn btn-outline-danger btn-custom">Посмотреть все
                        курсы</button>
                </div>

                <div class="row justify-content-around">
                    <div v-for="course in courses" :key="course.id" class="card mb-3"
                        style="width: 600px; margin-top: 60px;">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-4">
                                <img :src="getImageUrl(course.image)" class="img-fluid rounded-start" :alt="course.title">
                            </div>
                            <div class="col-md-6">
                                <div class="card-body">
                                    <span class="card-title badge text-bg-success">{{ course.category }}</span>
                                    <p class="card-text mt-2"
                                        style="font-size: 20px; font-weight: bold; margin-top: 20px;">
                                        {{ course.title }}
                                    </p>
                                    <p class="card-text"><small class="text-body-secondary"><span
                                                style="font-size: 18px; color: #FF4242; font-weight: bold;">₽{{
                                                    course.price }}</span>
                                            <span style="font-size: 18px; color: #787A80; font-weight: bold;">
                                                | Максим Козлов</span></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="mt-custom_2 img_background_2">
            <div class="container" v-for="teacher in teachers" :key="teacher.id">
                <p class="platform-text_3">Лучшие репетиторы здесь</p>
                <p class="platform-text_2">Познакомьтесь с нашей командой</p>
                <div class="row justify-content-around">

                    <div class="col-md-3 mb-4" style="width: 300px; margin-top: 60px;">
                        <div class="card mb-3" style="background-color: #C1908B;">
                            <img src="/teacher/teacher_1.png" class="card-img" alt="teacher">
                        </div>
                        <div class="text-center">
                            <h5 class="card-title" style="font-size: 20px; font-weight: bold; color: #424551;">{{
                                teacher.name }} {{ teacher.surname }}</h5>
                            <p class="card-text" style="font-weight: normal; color: #787A80; margin-top: 10px;">
                                Генеральный директор,<br>Преподаватель Истории Искусства</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <FooterApp />

</template>


<style>
.platform-text_2 {
    padding-top: 25px;
    font-size: 64px;
    font-weight: bold;
}

.mt-custom {
    margin-top: 50px;
}

.illustration {
    margin-top: 100px;
}

.number-46 {
    font-size: 46px;
    font-weight: bold;
}

.img-fixed-size {
    width: 8px;
    height: 8px;
    object-fit: cover;
}

.btn-custom {
    padding-left: 40px;
    padding-right: 40px;
    padding-top: 20px;
    padding-bottom: 20px;
}

.img_background {
    background: url('/background/background_2.png') no-repeat center center;
    background-size: cover;
}

.img_background_2 {
    background: url('/background/background_3.png') no-repeat center center;
    background-size: cover;
    padding: 100px 0;
}

.platform-text_3 {
    margin-top: 20px;
    font-weight: bold;
    text-transform: uppercase;
}

.mt-custom_2 {
    margin-top: 150px;
}

.img-check {
    width: 16px;
    height: 16px;
    object-fit: cover;
    margin-right: 18px;
}
</style>