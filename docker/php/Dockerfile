FROM php:8.1-fpm

# php.iniのコピー
COPY php.ini /usr/local/etc/php/

# 必要なパッケージをインストール
RUN apt update \
  && apt install -y \
  default-mysql-client \
  zlib1g-dev \
  libzip-dev \
  unzip \
  curl \
  wget \
  gnupg2 \
  ca-certificates \
  libx11-6 \
  libxcomposite1 \
  libxrandr2 \
  libgdk-pixbuf2.0-0 \
  libatk1.0-0 \
  libnss3 \
  libnspr4 \
  libappindicator3-1 \
  libxss1 \
  libasound2 \
  libdrm2 \
  libgbm1 \
  libvulkan1 \
  xdg-utils \
  fonts-liberation \
  libu2f-udev \
  lsb-release \
  && rm -rf /var/lib/apt/lists/*

# zip拡張をインストール
RUN docker-php-ext-install zip

# Chromeをインストール
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - && \
  echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google-chrome.list && \
  apt-get update && apt-get install -y google-chrome-stable

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  && docker-php-ext-install pdo_mysql zip

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

# 作業ディレクトリの設定
WORKDIR /var/www

# 画像アップロードテスト実行用の拡張機能のインストール
RUN apt-get update && apt-get install -y \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install gd