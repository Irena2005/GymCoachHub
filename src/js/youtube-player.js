// Додаємо обробник події, коли весь DOM завантажено
document.addEventListener('DOMContentLoaded', function() {
    // Знаходимо всі посилання з класом .pilates-link
 const links = document.querySelectorAll('.pilates-link, .hard-exercise-link, .fitness-link');
    // Знаходимо елемент модального вікна
    const modal = document.querySelector('#modal-movie');
    // Знаходимо контейнер для контенту модального вікна
    const modalContent = document.querySelector('#modal-movie .modal-content');


    // Для кожного посилання додаємо обробник кліку
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Відміняємо стандартну поведінку посилання
            // Отримуємо посилання на відео з data-movie
            const movieUrl = this.getAttribute('data-movie');

            if (movieUrl) {
                // Витягуємо id відео з посилання (підтримка youtu.be та youtube.com)
                const videoId = movieUrl.includes('youtu.be/')
                    ? movieUrl.split('youtu.be/')[1].split('?')[0]
                    : new URL(movieUrl).searchParams.get('v');

                // Додаємо iframe з відео у модальне вікно
                modalContent.innerHTML = `<iframe width="100%" height="450" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`;
                // Показуємо модальне вікно
                modal.style.display = 'flex';
            }
        });
    });

    // Обробник кліку поза модальним вікном для його закриття
    window.addEventListener('click', e => {
        if (e.target === modal) {
            modalContent.innerHTML = ''; // Очищаємо контент модального вікна
            modal.style.display = 'none'; // Ховаємо модальне вікно
        }
        console.log(e.target);
    });
});