# フリマアプリ

## 環境構築
### Dockerビルド
1. ```git clone git@github.com:mikanchaaaan/coachtech-fleamarket.git```
2. ```docker-compose up -d --build```
※ MySQLは、OSによって起動しない場合があるためそれぞれのPCに合わせてdocker-compose.ymlファイルを編集してください。

### Laravel環境構築
1. ```docker-compose exec php bash```
2. ```composer install```
3. ``` cp -p .env.example .env```
4. envの環境変数を変更（下記参照）
5. ```php artisan key:generate```
6. ```php artisan migrate```
7. ```php artisan db:seed```

### 環境変数（.envに追加する）
| 変数名              | 値                                         | 備考                                    |
| ------------------- | ------------------------------------------ | --------------------------------------- |
| DB_HOST             | mysql                                      | 接続するデータベース                    |
| DB_DATABASE         | docker-compose.ymlの「MYSQL_DATABASE」参照 | 接続するデータベース名                  |
| DB_USERNAME         | docker-compose.ymlの「MYSQL_USER」参照     | データベースに接続時のユーザー名        |
| DB_PASSWORD         | docker-compose.ymlの「MYSQL_PASSWORD」参照 | データベースに接続時のパスワード        |
| MAIL_FROM_ADDRESS   | 任意のメールアドレス（入力必須）           | メール認証時の送信元メールアドレス      |
| STRIPE_PUBLIC_KEY   | StripeのAPIキー（Public）                  | Stripe接続用のAPIキー（Public）         |
| STRIPE_SECRET_KEY   | StripeのAPIキー（Secret）                  | Stripe接続用のAPIキー（Secret）         |

### 使用技術
* PHP 7.4.9
* Laravel Framework 8.83.29
* nginx 1.21.1
* MySQL 10.3.39-MariaDB
* Stripe 9.9.0
* mailhog latest

### URL
* 開発環境：```http://localhost/```
* phpMyAdmin：```http://localhost:8080/```
* Mailhog：```http://localhost:8025/```

## テスト
### テスト環境構築
1. 
2. ``` cp -p .env.example .env```
3. .env.exampleの環境変数を変更
4. ```php artisan key:generate --env=testing```
5. ```php artisan migrate --env=testing```


6. ```php artisan migrate --env=dusk.testing```
7. ```nohup php artisan serve --env=dusk.testing&```

### テスト実行
※ 各テスト内容は案件シートの[テストケース一覧]シート参照。
#### PHP Unitテスト
1. ```vendor/bin/phpunit tests/Feature/RegisterUser.php```
2. ```vendor/bin/phpunit tests/Feature/LoginTest.php```
3. ```vendor/bin/phpunit tests/Feature/LogoutTest.php```
4. ```vendor/bin/phpunit tests/Feature/ExhibitionListView.php```
5. ```vendor/bin/phpunit tests/Feature/MyListView.php```
6. ```vendor/bin/phpunit tests/Feature/ExhibitionSearch.php```
7. ```vendor/bin/phpunit tests/Feature/ExhibitionDetail.php```
8. ```vendor/bin/phpunit tests/Feature/ExhibitionLike.php```
9. ```vendor/bin/phpunit tests/Feature/ExhibitionComment.php```
10. ```vendor/bin/phpunit tests/Feature/ExhibitionPurchase.php```
11. ```vendor/bin/phpunit tests/Feature/ChangeAddress.php```
12. ```vendor/bin/phpunit tests/Feature/UserProfileView.php```
13. ```vendor/bin/phpunit tests/Feature/ChangeUserProfile.php```
14. ```vendor/bin/phpunit tests/Feature/ExhibitionSale.php```

#### Laravel Duskテスト
1. ```php artisan dusk --filter ExhibitionLikeColorTest --env=dusk.testing```
2. ```php artisan dusk --filter PaymentMethodTest --env=dusk.testing```

※　テスト実行時に「This version of ChromeDriver only supports Chrome version 114
Current browser version is 131.0.6778.139 with binary path /usr/bin/google-chrome」というメッセージが表示された場合は、Googleブラウザのバージョン114をインストールして再実行してください。
1. Chromeブラウザのバージョン114をダウンロード
2. ```dpkg -i chrome_114_amd64.deb```

