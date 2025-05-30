//анімації до кнопки "Розпочати" на головній сторінці, затмняється при наведені курсора
window.addEventListener("DOMContentLoaded", function () {
    const start = document.querySelector('.start a');
    if (start) {
        start.classList.add('pulse');
        start.classList.add('glow');
    }
});

//обробка генерації та збереження вправ
document.addEventListener("DOMContentLoaded", function () {
    // Елементи DOM-всі наявні модулі для генерації
    const allExercises = Array.from(document.querySelectorAll(".hardexercise a, .pilates a, .fitness a"));
    const generateBtn = document.getElementById("generate");
    const modal = document.getElementById("modal");
    const closeModal = modal ? modal.querySelector(".close") : null;
    const exerciseList = document.getElementById("exercise-list");
    const saveBtn = document.getElementById("save-exercises");
    const messageDiv = document.getElementById("save-message");

    //перевіряєм наявність необхідних елементів
    if (!generateBtn || !modal || !closeModal || !exerciseList || !saveBtn || !messageDiv) {
        console.warn("Один або кілька елементів DOM не знайдено. Перевірте HTML-структуру.");
        return;
    }

    //функція для обробки відповідей сервера при помилках
    async function handleResponse(response) {
        if (!response) {
            throw new Error("Відповідь сервера не отримана (response is undefined)");
        }

        if (!response.ok) {
            const text = await response.text();
            console.error("Неваліний HTTP-статус:", response.status, "Текст відповіді:", text);
            throw new Error(`Сервер повернув помилку ${response.status}: ${text.substring(0, 100)}...`);
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error("Неваліний Content-Type:", contentType, "Текст відповіді:", text);
            throw new Error(`Сервер повернув не JSON (Content-Type: ${contentType}): ${text.substring(0, 100)}...`);
        }

        try {
            return  await response.json();
        } catch (err) {
            const text = await response.text();
            console.error("Помилка парсингу JSON. Текст відповіді:", text);
            throw new Error(`Помилка парсингу JSON: ${err.message}. Текст відповіді: ${text.substring(0, 100)}...`);
        }
    }

    //генерація випадкових вправ
    generateBtn.addEventListener("click", function (e) {
        e.preventDefault();//відміна стандартної діі форми-щоб сторінка не обновлялась а просто спрацювало
        if (allExercises.length === 0) {
            showMessage("❌ Немає доступних вправ для генерації", "red");
            return;
        }

        const selected = [...allExercises]//створюємо копію масива щоб не робити заново
            .sort(() => 0.5 - Math.random())//перемішуємо масив щоб витягнути з нього перші 5 вправ
            .slice(0, 5);

        exerciseList.innerHTML = "";//очищаємо попередні вправи
        selected.forEach(item => {//перебираємо вправи
            const name = item.getAttribute("data-id").replace(/_/g, " ");//заміна _ на пробіл
            const div = document.createElement("div"); //створили клас для представлення вправ
            div.className = "exercise-item";//
            div.setAttribute("data-id", item.getAttribute("data-id"));//зберігаємо назви по айді для виводу

            const text = document.createElement("span");
            text.textContent = name;

            const icon = document.createElement("span");
            icon.textContent = "✅";
            icon.className = "done-icon";
            icon.style.display = "none"; //приховуємо знак до кліку
            icon.style.marginLeft = "10px";

            div.appendChild(text); //вставляємо текст і іконку у дів
            div.appendChild(icon);
            exerciseList.appendChild(div);//передаємо цей дів у головний список для виводу вправ

            div.addEventListener("click", () => { //створили подію, коли користувач нажимає на іконку появляється галочка
                icon.style.display = icon.style.display === "none" ? "inline" : "none";
            });
        });

        saveBtn.style.display = "block";//відображення кнопки якщо вона була прихована
        modal.style.display = "block";//відображення модалки якщо воно було приховане
    });

    // Закриття модального вікна
    closeModal.addEventListener("click", () => modal.style.display = "none");
    window.addEventListener("click", (e) => e.target === modal && (modal.style.display = "none"));//При кліку поза модалкою вона закривається

    // Збереження вправ
    saveBtn.addEventListener("click", async function () {//створили подію для зберігання та зробили функ асинхронною-щоб можна було використати await
        try {
            const chosen = Array.from(document.querySelectorAll(".exercise-item"))// вибираємо всі елементи з вправами та перестворюємо на масив
                .filter(item => item.querySelector(".done-icon")?.style.display !== "none")// залишаємо в масиві тільки вправи з галочкою
                .map(item => item.getAttribute("data-id").replace(/_/g, " "));

            if (chosen.length === 0) {
                throw new Error("Виберіть хоча б одну вправу");
            }

            const trainingType = document.querySelector("#trainingType");// беремо значення вибраного типа завдань
            const dataBody = JSON.stringify({// сформувала джсон-рядок для відправки на сервер
                trainingType: trainingType.value,
                exercises: chosen,
            });
            // Збереження вправ
            console.log("Відправка запиту до save_exercise.php...");
            //await-для створення паузи поки фетч не закінчиться
            const saveResponse = await fetch("/src/pages/save-exercise.php", {//через фетч відправляємо запит на php-обробник з тіла body
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: dataBody
            });

            console.log("Відповідь отримана:", saveResponse);
            // Використовуємо message із серверного відповіді-якщо значення 200 значить true
            showMessage(
                saveResponse.status == 200 ? "✅ Вправи успішно збережено!" : "❌ Помилка збереження вправ"
            );

        } catch (err) {// при помилці якщо значення НЕ 200, показуємо помилку в консолі та користувачу
            console.error("Помилка:", err);
            showMessage(`❌ ${err.message}`, "red");
        }
    });

    //орема функція для показу повідомлень
    function showMessage(text, color) {
        messageDiv.textContent = text;
        messageDiv.style.color = color;
        setTimeout(() => messageDiv.textContent = "", 3000);
    }
});