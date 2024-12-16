FROM php:7.4.9-fpm

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

# 最新のChromeDriverをインストール
RUN LATEST_CHROMEDRIVER=$(curl -sSL https://chromedriver.storage.googleapis.com/LATEST_RELEASE) && \
  wget https://chromedriver.storage.googleapis.com/$LATEST_CHROMEDRIVER/chromedriver_linux64.zip && \
  unzip chromedriver_linux64.zip -d /usr/local/bin/ && \
  chmod +x /usr/local/bin/chromedriver

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