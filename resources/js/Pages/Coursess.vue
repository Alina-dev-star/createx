<script setup>
import { ref, computed, onMounted } from 'vue';
import HeaderApp from '@/Components/Header/HeaderApp.vue';
import FooterApp from '@/Components/Footer/FooterApp.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    courses: {
        type: Array,
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

const searchQuery = ref('');
const selectedCategory = ref('Все');

const filteredcourses = computed(() => {
    return props.courses.filter(course => {
        const matchesCategory = selectedCategory.value === 'Все' || course.category === selectedCategory.value;
        const matchesSearch = searchQuery.value ? course.title.toLowerCase().includes(searchQuery.value.toLowerCase()) : true;
        return matchesCategory && matchesSearch;
    });
});

const categories = ['Все', 'Изо', 'Лепка', 'История'];

const clearFilters = () => {
    selectedCategory.value = 'Все';
    searchQuery.value = '';
};

const getImageUrl = (imagePath) => {
    if (imagePath.startsWith('http')) {
        return imagePath;
    }
    return `/storage/${imagePath}`;
};

onMounted(() => {
    selectedCategory.value = 'Все';
});
</script>

<template>
    <header>
        <div class="container py-5">
            <HeaderApp :canLogin="canLogin" :canRegister="canRegister" />
        </div>
    </header>

    <div class="container mt-5">
        <div class="text-center">
            <p class="platform-text_3">Приятного обучения!</p>
            <p class="platform-text_2">Наши курсы</p>
        </div>

        <div class="row mt-4 mb-4">
            <div class="col-md-6">
                <div class="btn-group" role="group">
                    <button v-for="(category, index) in categories" :key="category"
                        :class="['btn', selectedCategory === category ? 'btn-selected' : 'btn-unselected', index > 0 ? 'ms-2' : '']"
                        @click="selectedCategory = category">
                        {{ category }}
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <input v-model="searchQuery" type="text" id="searchQuery" class="form-control rounded"
                    placeholder="Введите название курса">
            </div>
        </div>

        <div class="row">
            <div v-for="course in filteredcourses" :key="course.id" class="col-md-4 mb-4">
                <div class="card h-100">
                    <img :src="getImageUrl(course.image)" class="card-img-top" :alt="course.title">
                    <div class="card-body">
                        <h5 class="card-title">{{ course.title }}</h5>
                        <p class="card-text">{{ course.description }}</p>
                        <p class="card-text"><strong>Price:</strong> {{ course.price }}</p>
                        <p class="card-text"><strong>Duration:</strong> {{ course.duration }}</p>
                        <p class="card-text"><strong>Category:</strong> {{ course.category }}</p>
                        <p class="card-text"><strong>Start Date:</strong> {{ course.start_date }}</p>
                        <p class="card-text"><strong>End Date:</strong> {{ course.end_date }}</p>
                        <Link :href="route('coursess.show', { id: course.id })" class="btn btn-primary">Learn More </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 126px;">
        <FooterApp />
    </div>
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

.btn-selected {
    border: 2px solid #FF3F3A;
    color: #FF3F3A;
    background-color: transparent;
}

.btn-unselected {
    border: none;
    color: #9A9CA5;
    background-color: transparent;
}

.card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card-body {
    flex-grow: 1;
    overflow-y: auto;
}
</style>