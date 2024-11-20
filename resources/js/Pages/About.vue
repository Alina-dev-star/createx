<script setup>
import HeaderApp from '@/Components/Header/HeaderApp.vue';
import FooterApp from '@/Components/Footer/FooterApp.vue';

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
    <header>
        <div class="container py-5">
            <HeaderApp :canLogin="canLogin" :canRegister="canRegister" />
        </div>
    </header>

    <div class="container mt-5 py-10">
        <h1 class="text-center mb-4">About Us</h1>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
                <p>
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
        </div>
    </div>

    <FooterApp />
</template>

<style>
.platform-text_3 {
    margin-top: 80px;
    font-weight: bold;
    text-transform: uppercase;
}

.platform-text_2 {
    padding-top: 10px;
    font-size: 64px;
    font-weight: bold;
}
</style>