<template>
    <div class="modal-overlay" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ isCreating ? 'Создать курс' : (isEditing ? 'Редактировать курс' : 'Просмотр курса') }}</h5>
                    <button type="button" class="close" @click="cancelEdit">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitForm">
                        <div class="form-group">
                            <label for="title">Название</label>
                            <input type="text" class="form-control" id="title" v-model="form.title" :disabled="!isEditing && !isCreating">
                            <div v-if="errors.title" class="text-danger">{{ errors.title[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <textarea class="form-control" id="description" v-model="form.description" :disabled="!isEditing && !isCreating"></textarea>
                            <div v-if="errors.description" class="text-danger">{{ errors.description[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="what_they_will_learn">Что они узнают</label>
                            <textarea class="form-control" id="what_they_will_learn" v-model="form.what_they_will_learn" :disabled="!isEditing && !isCreating"></textarea>
                            <div v-if="errors.what_they_will_learn" class="text-danger">{{ errors.what_they_will_learn[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="price">Цена</label>
                            <input type="text" class="form-control" id="price" v-model="form.price" :disabled="!isEditing && !isCreating">
                            <div v-if="errors.price" class="text-danger">{{ errors.price[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="category">Категория</label>
                            <input type="text" class="form-control" id="category" v-model="form.category" :disabled="!isEditing && !isCreating">
                            <div v-if="errors.category" class="text-danger">{{ errors.category[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="duration">Продолжительность</label>
                            <input type="text" class="form-control" id="duration" v-model="form.duration" :disabled="!isEditing && !isCreating">
                            <div v-if="errors.duration" class="text-danger">{{ errors.duration[0] }}</div>
                        </div>
                        <div class="form-group">
                            <label for="image">Картинка</label>
                            <input type="file" class="form-control" id="image" @change="e => form.image = e.target.files[0]" :disabled="!isEditing && !isCreating">
                            <div v-if="errors.image" class="text-danger">{{ errors.image[0] }}</div>
                        </div>
                        <div class="form-group" v-if="form.image || form.imageUrl">
                            <img :src="getImageUrl(form.image || form.imageUrl)" alt="Course Image" class="img-thumbnail" style="max-width: 100px;">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" v-if="isEditing || isCreating">{{ isCreating ? 'Создать' : 'Сохранить' }}</button>
                            <button type="button" class="btn btn-secondary" @click="cancelEdit" v-if="isEditing || isCreating">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
    isEditing: {
        type: Boolean,
        required: true,
    },
    isCreating: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update', 'cancel', 'create', 'error']);

const form = ref({
    title: props.course.title,
    description: props.course.description,
    what_they_will_learn: props.course.what_they_will_learn, // Добавляем новое поле
    price: props.course.price,
    category: props.course.category,
    duration: props.course.duration,
    image: props.course.image,
    imageUrl: props.course.image ? `/storage/${props.course.image}` : null,
});

const errors = ref({});

watch(() => props.course, (newCourse) => {
    form.value = {
        title: newCourse.title,
        description: newCourse.description,
        what_they_will_learn: newCourse.what_they_will_learn, // Добавляем новое поле
        price: newCourse.price,
        category: newCourse.category,
        duration: newCourse.duration,
        image: newCourse.image,
        imageUrl: newCourse.image ? `/storage/${newCourse.image}` : null,
    };
});

const submitForm = () => {
    if (!form.value.title || !form.value.description || !form.value.what_they_will_learn || !form.value.price || !form.value.category || !form.value.duration) {
        alert('Пожалуйста, заполните все поля');
        return;
    }

    errors.value = {};
    if (props.isCreating) {
        emit('create', form.value);
    } else {
        emit('update', { ...form.value, id: props.course.id });
    }
};

const cancelEdit = () => {
    errors.value = {};
    emit('cancel');
};

const handleError = (error) => {
    if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors;
    } else {
        errors.value = { general: ['Произошла ошибка. Пожалуйста, попробуйте позже.'] };
    }
};

const getImageUrl = (imagePath) => {
    if (typeof imagePath === 'string') {
        if (imagePath.startsWith('http')) {
            return imagePath;
        }
        return `/storage/${imagePath}`;
    }
    return ''; // Возвращаем пустую строку или URL-заглушку, если изображение еще не загружено
};

emit('error', handleError);
</script>

<style scoped>
.modal-overlay {
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-dialog {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 400px;
    max-width: 90%;
}

.modal-content {
    padding: 20px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
}

.close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
    margin-left: 10px;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.modal {
    display: block;
    background: rgba(0, 0, 0, 0.5);
}
</style>