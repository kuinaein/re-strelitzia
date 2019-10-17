# Re:すとれりちあ

Re:「すとれりちあ」はできたてほやほやの家計簿ソフトです。まだ、記帳と最終残高の表示くらいしかできません。。

## 動作環境

（カッコ内は開発に使用しているバージョン）

- PHP 7 (7.3.x)
- PostgreSQL (11.x)
- Composer (1.9.x)
- Node.js (10.x)
- NPM (6.x)
- Yarn (1.x)

開発に使用している OS は Windows 10 (1903)、ブラウザは Google Chrome です。

## インストール

### システム側のセットアップ

1. `.env.example` を `.env` にコピーする。
1. PostgreSQL でユーザー `re_trelitzia`、データベース `re_strelitzia` を作成する。（DB 名等は `.env` で変更可能）<br/>
   `CREATE USER re_strelitzia WITH ENCRYPTED PASSWORD 're_strelitzia';`<br/>
   `CREATE DATABASE re_strelitzia WITH OWNER re_strelitzia ENCODING 'UTF8' LC_COLLATE 'C' LC_CTYPE 'C' TEMPLATE template0;`
1. `composer install --no-dev` を実行する。
1. `php artisan stre:install` を実行する。

## 起動方法

1. `php artisan serve` を実行する。
1. ブラウザで http://localhost:8000/ を開く。 (ポート番号は `--port` オプションで変更可能)
1. 終了時はコンソールウィンドウをそのまま閉じれば OK。

## アップデート

1. データベースのバックアップを取る。<br/>
   `pg_dump -U re_strelitzia -b -Fc > storage/app/pg_dump`
1. `composer install --no-dev` を再度実行する。
1. `php artisan stre:update` を実行する。

## アンインストール

データベースとディレクトリを削除すれば OK です。
レジストリなどは利用していません。

## ライセンス

[MIT](https://github.com/kuinaein/re-strelitzia/blob/release/LICENSE-ja.txt)
