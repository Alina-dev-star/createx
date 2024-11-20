<script setup>
</script>

<template>
    <footer>
        <div style="background-color: #1E212C;">
            <div class="container">
                <div class="row text-white py-5">
                    <div class="col-md-3">
                        <img src="/logo/logo_2.png" alt="logo" class="mb-3" style="width: 130px; height: 30px;">
                        <p style="margin-top: 25px; color: #777777;">Сайта Художественной Школы "Createx"<br>предоставляет
                            пользователям важную информацию
                            о<br>контактах, политике конфиденциальности, условиях<br>использования.</p>
                            <ul class="list-unstyled d-flex" style="margin-top: 55px;">
                            <li><img src="/communication_logo/facebook.png" alt="facebook" class="me-3 social-icon"></li>
                            <li><img src="/communication_logo/twitter.png" alt="twitter" class="me-3 social-icon"></li>
                            <li><img src="/communication_logo/YouTube.png" alt="YouTube" class="me-3 social-icon"></li>
                            <li><img src="/communication_logo/telegram.png" alt="telegram" class="me-3 social-icon"></li>
                            <li><img src="/communication_logo/instagram.png" alt="instagram" class="me-3 social-icon"></li>
                            <li><img src="/communication_logo/linked-in.png" alt="linked-in" class="me-3 social-icon"></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>КАРТА САЙТА</h5>
                        <ul class="list-unstyled" style="color: #777777;">
                            <li style="margin-top: 12px;"><a href="#">О нас</a></li>
                            <li style="margin-top: 12px;"><a href="#">Курсы</a></li>
                            <li style="margin-top: 12px;"><a href="#">Контакты</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>КУРСЫ</h5>
                        <ul class="list-unstyled" style="color: #777777;">
                            <li style="margin-top: 12px;"><a href="#">ИЗО</a></li>
                            <li style="margin-top: 12px;"><a href="#">Лепка</a></li>
                            <li style="margin-top: 12px;"><a href="#">История</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>СВЯЗАТЬСЯ С НАМИ</h5>
                        <ul class="list-unstyled" style="color: #777777;">
                            <li style="margin-top: 12px;"><a href="tel:(405) 555-0128">(405) 555-0128</a></li>
                            <li style="margin-top: 12px;"><a href="mailto:hello@createx.com">hello@createx.com</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div style="background-color: #292C37;">
            <div class="container">
                <div class="row text-white py-3">
                    <div class="d-flex justify-content-between">
                        <p>&copy; {{ new Date().getFullYear() }} Сайта Художественной Школы "Createx". Все права
                            защищены.
                        </p>
                        <a href="#">ВВЕРХ</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
a {
    text-decoration: none;
    color: #777777;
}

a:hover {
    color: #ffffff;
}

.social-icon {
    transition: filter 0.3s ease;
}

.social-icon:hover {
    filter: brightness(0) invert(1);
}
</style>