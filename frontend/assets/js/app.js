const slides = [...document.querySelectorAll('.slide')];
        const dots = [...document.querySelectorAll('.dot')];
        let currentSlide = 0;
        let sliderTimer;
        if (slides.length > 0 && dots.length > 0) {

        function showSlide(index) {
            currentSlide = (index + slides.length) % slides.length;
            slides.forEach((slide, i) => slide.classList.toggle('active', i === currentSlide));
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
                if (i === currentSlide) dot.setAttribute('aria-current', 'true');
                else dot.removeAttribute('aria-current');
            });
        }

        function restartSlider() {
            clearInterval(sliderTimer);
            sliderTimer = setInterval(() => showSlide(currentSlide + 1), 3000);
        }

        document.querySelector('.previous').addEventListener('click', () => {
            showSlide(currentSlide - 1);
            restartSlider();
        });
        document.querySelector('.next').addEventListener('click', () => {
            showSlide(currentSlide + 1);
            restartSlider();
        });
        dots.forEach((dot, index) => dot.addEventListener('click', () => {
            showSlide(index);
            restartSlider();
        }));
        restartSlider();
        }
        
const registrationForm = document.querySelector('.register-form');

if (registrationForm) {

    registrationForm.noValidate = true;

    const rules = {
        login: (value) =>
            /^[A-Za-z0-9]{6,}$/.test(value)
                ? ''
                : 'Логин: минимум 6 символов, только латиница и цифры',

        password: (value) =>
            [...value].length >= 8 && /[0-9]/.test(value)
                ? ''
                : 'Пароль: минимум 8 символов и одна цифра',

        full_name: (value) =>
            /^[А-Яа-яЁё]+(?:\s+[А-Яа-яЁё]+){2}$/.test(value.trim())
                ? ''
                : 'Введите фамилию, имя и отчество (3 слова, кириллица)',

        phone: (value) =>
            /^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/.test(value)
                ? ''
                : 'Формат телефона: 8(999)123-45-67',

        email: (value) =>
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
                ? ''
                : 'Некорректный адрес электронной почты'
    };

    function checkField(field) {
        const message = rules[field.name](field.value);

        let error = registrationForm.querySelector(
            `[data-error-for="${field.name}"]`
        );

        if (!error) {
            error = document.createElement('p');
            error.className = 'client-error';
            error.dataset.errorFor = field.name;

            field.insertAdjacentElement('afterend', error);
        }

        error.textContent = message;
        error.hidden = message === '';

        field.classList.toggle('input-invalid', Boolean(message));
        field.setAttribute('aria-invalid', message ? 'true' : 'false');
        field.dataset.checked = 'true';

        return message === '';
    }

    Object.keys(rules).forEach((name) => {
        const field = registrationForm.elements.namedItem(name);

        field.addEventListener('blur', () => {
            checkField(field);
        });

        field.addEventListener('input', () => {
            if (field.dataset.checked === 'true') {
                checkField(field);
            }
        });
    });

    registrationForm.addEventListener('submit', (event) => {
        let firstInvalidField = null;

        Object.keys(rules).forEach((name) => {
            const field = registrationForm.elements.namedItem(name);

            if (!checkField(field) && !firstInvalidField) {
                firstInvalidField = field;
            }
        });

        if (firstInvalidField) {
            event.preventDefault();
            firstInvalidField.focus();
        }
    });
}

const loginForm = document.querySelector('.login-form');

if (loginForm) {
    loginForm.noValidate = true;

    const loginFields = ['login', 'password'];

    function checkLoginField(field) {
        const isEmpty = field.value.trim() === '';

        let error = loginForm.querySelector(
            `[data-login-error="${field.name}"]`
        );

        if (!error) {
            error = document.createElement('p');
            error.className = 'client-error';
            error.dataset.loginError = field.name;
            field.insertAdjacentElement('afterend', error);
        }

        error.textContent = isEmpty
            ? (field.name === 'login'
                ? 'Введите логин'
                : 'Введите пароль')
            : '';

        error.hidden = !isEmpty;

        field.classList.toggle('input-invalid', isEmpty);
        field.setAttribute('aria-invalid', String(isEmpty));

        return !isEmpty;
    }

    loginFields.forEach((name) => {
        const field = loginForm.elements.namedItem(name);

        field.addEventListener('blur', () => {
            checkLoginField(field);
        });

        field.addEventListener('input', () => {
            checkLoginField(field);
        });
    });

    loginForm.addEventListener('submit', (event) => {
        let firstInvalidField = null;

        loginFields.forEach((name) => {
            const field = loginForm.elements.namedItem(name);

            if (!checkLoginField(field) && !firstInvalidField) {
                firstInvalidField = field;
            }
        });

        if (firstInvalidField) {
            event.preventDefault();
            firstInvalidField.focus();
        }
    });
}

const serviceSelect = document.querySelector('#service_name');
const otherServiceField = document.querySelector('#other-service-field');
const otherServiceInput = document.querySelector('#other_service_description');

if (serviceSelect && otherServiceField && otherServiceInput) {

    function toggleOtherService() {
        const isOther = serviceSelect.value === 'Иная услуга';

        otherServiceField.hidden = !isOther;
        otherServiceInput.required = isOther;

        if (!isOther) {
            otherServiceInput.value = '';
        }
    }

    serviceSelect.addEventListener('change', toggleOtherService);

    toggleOtherService();
}
