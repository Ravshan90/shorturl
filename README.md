Инструкция:
1. Создать БД "shorturldb"
2. Добавить следующее в конфигурационный файл apache:
	# Set document root to be "basic/web"
	DocumentRoot "path/to/shorturl/web"

	<Directory "path/to/shorturl/web">
		# use mod_rewrite for pretty URL support
		RewriteEngine on
		# If a directory or a file exists, use the request directly
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		# Otherwise forward the request to index.php
		RewriteRule . index.php

		
	</Directory>
3. Запустить миграцию