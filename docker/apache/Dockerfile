# Базовий образ PHP 8.1 з Apache
# Документація: https://hub.docker.com/_/php
FROM php:8.1-apache

# Встановлення залежностей та розширень PHP
#RUN apt-get update && apt-get install -y \
#    libfreetype6-dev \         # Бібліотека для роботи зі шрифтами (gd) \
#    libpng-dev \               # Бібліотека для роботи з PNG (gd) \
#    libjpeg-dev \              # Бібліотека для роботи з JPEG (gd) \
#    zip \                      # Утиліта для роботи з zip-архівами \
#    unzip \                    # Утиліта для розпаковки zip-архівів \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg \  # Налаштування розширення gd з підтримкою freetype та jpeg \
#    && docker-php-ext-install gd pdo pdo_mysql mysqli \           # Встановлення розширень gd, pdo, pdo_mysql, mysqli \
#    && a2enmod rewrite \       # Увімкнення модуля Apache mod_rewrite (для ЧПУ) \
#    && apt-get clean \         # Очищення кешу apt \
#    && rm -rf /var/lib/apt/lists/*  # Видалення тимчасових файлів apt

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Встановлення часової зони
# Документація: https://docs.docker.com/reference/dockerfile/#env
ENV TZ=Europe/Kyiv

# Налаштування системної часової зони
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Команда запуску Apache у контейнері
# Документація: https://hub.docker.com/_/php#apache
CMD ["apache2-foreground"]