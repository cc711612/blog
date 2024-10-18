{{--定義server的名稱 使用者@deploy-unit的內部IP--}}
@servers(['main' => 'gitlab-runner@172.31.42.248'])

{{--自動化腳本執行內容--}}
@task('deploy', ['on' => 'main'])
cd /var/www/blog
sudo git checkout .
sudo git checkout master
sudo git pull origin master
composer install
php artisan cache:clear
php artisan optimize
php artisan view:cache
@endtask
