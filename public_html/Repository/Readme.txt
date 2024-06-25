*USE modified.zip*

 - Install unzip if not available 
	sudo apt install unzip

1. cd /var/www/owncloud/apps-external
2. wget https://github.com/owncloud/theme-example/archive/master.zip
3. unzip master.zip
4. rm master.zip
5. mv theme-example-master mynewtheme
6. sed -i "s#<id>theme-example<#<id>mynewtheme<#" "mynewtheme/appinfo/info.xml"
7. sudo chown -R www-data: mynewtheme
8. occ app:enable mynewtheme
9. replace /var/www/owncloud/apps-external/mynewtheme/core/css/styles.css to styles2.css and change the name as styles.css
10. replace /var/www/owncloud/apps-external/mynewtheme/core/img/ to img/
11. replace /var/www/owncloud/apps-external/mynewtheme/default.php to default.php
12. replace /var/www/owncloud/core/css/styles.css to styles1.css and change the name as styles.css
13. systemctl restart apache2
14. hard reload browser (ctrl + shift + r)