<?php
            $result = $result ?? null; // Якщо $result не задано, встановити як null (mysqli_result або null)
            $class = $class ?? 'training-item'; // Якщо $class не задано, встановити клас за замовчуванням

            if ($result->num_rows > 0) { // Якщо є результати у запиті
                while ($row = $result->fetch_assoc()) { // Перебір кожного рядка з результату
                    $dataMovie = $row['movie'] ? 'data-movie="' . $row['movie'] . '"' : ''; // Якщо є посилання на відео, додати атрибут data-movie
                    echo <<<HTML
                    <a href="#" data-id="{$row['data_id']}" $dataMovie class="$class"> <!-- Посилання на вправу з атрибутами -->
                        <img src="{$row['img']}" alt="{$row['title']}"> <!-- Зображення вправи -->
                        <p>{$row['title']}</p> <!-- Назва вправи -->
                    </a>
                    HTML;
                }
            } else { // Якщо немає результатів
                echo '<p>Немає доступних вправ</p>'; // Вивести повідомлення про відсутність вправ
            }