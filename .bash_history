#1559718200
less /usr/bin/../share/php/wp-cli/bin/wp
#1559718315
cp /usr/bin/../share/php/wp-cli/bin/wp ./wp-cli
#1559718318
chmod 755 wp-cli
#1559718321
vi wp-cli
#1559718364
./wp-cli plugin list
#1559718373
vi wp-cli
#1559718415
./wp-cli plugin list
#1559718417
vi wp-cli
#1559718427
./wp-cli plugin list
#1559718443
vi wp-cli
#1559718455
./wp-cli plugin list
#1559718466
ls /usr/bin/php/
#1559718507
cd /usr/bin
#1559718508
ls
#1559718512
find .|grep boot-fs.php
#1559718513
cd ..
#1559718514
find .|grep boot-fs.php
#1559718530
cd
#1559718537
vi wp-cli 
#1559718559
./wp-cli plugin list
#1559718579
vi wp-cli 
#1559718606
WP_CLI_PHP=php7.1 wp plugin list
#1559718620
WP_CLI_PHP=php7.1-cli  wp plugin list
#1559718630
rm wp-cli 
#1559719094
alias wp='WP_CLI_PHP=php7.1-cli  wp'
#1559719097
wp plugin list
#1559719212
php-cli
#1559719217
php7.1-cli
#1559719230
php7.1-cli -h
#1559719252
php7.1-cli -d error_reporting=0
#1559719327
alias wp='WP_CLI_PHP=php7.1-cli -d error_reporting=0  wp'
#1559719330
wp plugin list
#1559719343
alias wp='WP_CLI_PHP="php7.1-cli -d error_reporting=0"  wp'
#1559719345
wp plugin list
