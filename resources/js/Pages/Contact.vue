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

    <div class="container mt-5 py-5">
        <div class="row">
            <div class="col-md-6">
                <div class="contact-info">
                    <h2>Контактная информация</h2>
                    <p><strong>Свяжитесь с нами</strong></p>
                    <p><strong>Поговорите с нами:</strong> hello@createx.com</p>
                    <p><strong>Позвоните нам:</strong> (405) 555-0128</p>
                    <p><strong>Адрес:</strong> 2464 Royal Ln. Mesa, New Jersey 45463, USA</p>
                    <p><strong>Подписывайтесь на нас:</strong></p>
                    <div class="social-icons">
                        <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1422937950147!2d-73.98785308459313!3d40.75797897932681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1628722956715!5m2!1sen!2sus"
                        width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6">
                <img src="background/background_4.png" alt="Contact Us Image" class="img-fluid">
            </div>
            <div class="col-md-6">
                <form>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="firstName" class="form-label">First Name*</label>
                            <input type="text" class="form-control" id="firstName" placeholder="Ваше имя">
                        </div>
                        <div class="col">
                            <label for="lastName" class="form-label">Last Name*</label>
                            <input type="text" class="form-control" id="lastName" placeholder="Ваша фамилия">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="email" class="form-label">Email*</label>
                            <input type="email" class="form-control" id="email"
                                placeholder="Ваш рабочий адрес электронной почты">
                        </div>
                        <div class="col">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" placeholder="Ваш номер телефона">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message*</label>
                        <textarea class="form-control" id="message" rows="3" placeholder="Ваше сообщение"></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="agree">
                        <label class="form-check-label" for="agree">Я согласен получать сообщения от школы
                            Createx</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить сообщение</button>
                </form>
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

.contact-info {
    padding: 20px;
    border-radius: 10px;
}

.social-icons a {
    font-size: 24px;
    color: #007bff;
}

.map-container {
    border-radius: 10px;
    overflow: hidden;
}
</style>