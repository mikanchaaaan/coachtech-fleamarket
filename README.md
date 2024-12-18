# フリマアプリ

## 環境構築
### Dockerビルド
1. ```git clone git@github.com:mikanchaaaan/coachtech-fleamarket.git```
2. ```docker-compose up -d --build```
※ MySQLは、OSによって起動しない場合があるためそれぞれのPCに合わせてdocker-compose.ymlファイルを編集してください。

### Laravel環境構築
1. ```docker-compose exec php bash```
2. ```composer install```
3. ```cp -p .env.example .env```
4. envの環境変数を変更（[環境変数](#環境変数env)参照）
5. ```php artisan key:generate```
6. ```php artisan migrate```
7. ```php artisan db:seed```
8. ```php artisan link:storage```

### 環境変数（.env）
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
* JavaScript

### URL
* 開発環境：```http://localhost/```
* phpMyAdmin：```http://localhost:8080/```
* Mailhog：```http://localhost:8025/```

## テスト
### PHP Unitテスト
#### 環境構築
1. ```docker-compose exec mysql bash```
2. ```mysql -u root -p```
3. ```CREATE DATABASE laravel_test;```
4. ```SHOW DATABASES;```
5. ```laravel_test```のデータベースが作成されていることを確認し、```exit```で抜ける。

6. ```docker-compose exec php bash```
7. ```cp -p .env .env.testing```
8. .env.exampleの環境変数を変更（[テスト用環境変数](#テスト用環境変数envtesting-および-envdusktesting)参照）
9. ```php artisan key:generate --env=testing```
10. ```php artisan migrate --env=testing```

#### テスト実行
※ 各テスト内容は案件シートの[テストケース一覧]シート参照。
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

### Laravel Duskテスト
#### 環境構築
1. ```docker-compose exec php bash```
2. ```cp -p .env .env.dusk.testing```
3. .env.dusk.exampleの環境変数を変更（[テスト用環境変数](#テスト用環境変数envtesting-および-envdusktesting)参照）
4. ```php artisan key:generate --env=dusk.testing```
5. ```php artisan migrate --env=dusk.testing```
6. Chromeブラウザのバージョン114をダウンロード
7. ```dpkg -i <ダウンロードしたChromeブラウザのパス>```
8. ```nohup php artisan serve --env=dusk.testing&```

#### テスト実行
※ 各テスト内容は案件シートの[テストケース一覧]シート参照。
1. ```php artisan dusk --filter ExhibitionLikeColorTest --env=dusk.testing```
2. ```php artisan dusk --filter PaymentMethodTest --env=dusk.testing```

#### トラブルシューティング
テスト実行時にエラーが出力された場合は、手動確認を実施するか、対応するChromeブラウザのバージョンをインストールして実行してください。

### テスト用環境変数（.env.testing および .env.dusk.testing）
| 変数名              | 値                                              | 備考                                                                |
| ------------------- | ----------------------------------------------- | ------------------------------------------------------------------- |
| APP_ENV             | testing(.env.testingの場合)                     | PHP Unitテスト時に接続する環境名                                    |
|                     | dusk.testing(.env.dusk.testingの場合)           | Laravel Duskテスト時に接続する環境名                                |
| APP_KEY             | 既存の値を削除する                              | Laravelアプリケーションの暗号化に使用されるキー。再作成するため削除 |
| DB_HOST             | mysql                                           | 接続するデータベース                                                |
| DB_DATABASE         | laravel_test                                    | 接続するデータベース名                                              |
| DB_USERNAME         | root                                            | データベースに接続時のユーザー名                                    |
| DB_PASSWORD         | docker-compose.ymlの「MYSQL_ROOT_PASSWORD」参照 | データベースに接続時のパスワード                                    |
| MAIL_FROM_ADDRESS   | 任意のメールアドレス（入力必須）                | メール認証時の送信元メールアドレス                                  |
| STRIPE_PUBLIC_KEY   | StripeのAPIキー（Public）                       | Stripe接続用のAPIキー（Public） ※.env.dusk.testingには不要         |
| STRIPE_SECRET_KEY   | StripeのAPIキー（Secret）                       | Stripe接続用のAPIキー（Secret） ※.env.dusk.testingには不要         |

