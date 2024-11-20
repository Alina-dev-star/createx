<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <nav>
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="d-flex align-items-center">
                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800 me-3" />

                <Link :href="route('about')" class="nav-link me-3">
                О нас
                </Link>
                <Link :href="route('coursess')" class="nav-link me-3">
                Курсы
                </Link>
                <Link :href="route('dashboard')" class="nav-link me-3">
                Личный кабинет
                </Link>
                <Link :href="route('contact')" class="nav-link me-3">
                Контакты
                </Link>
            </div>

            <div class="d-flex align-items-center">
                <Link :href="route('login')" class="text-black me-2">
                Войти
                </Link>
                <p class="text-black me-2">/</p>
                <Link :href="route('register')" class="text-black">
                Зарегистрироваться
                </Link>
            </div>
        </div>
    </nav>
</template>

<style>
header {
    background: url('/background/background_1.png') no-repeat center center;
    background-size: cover;
}
</style>