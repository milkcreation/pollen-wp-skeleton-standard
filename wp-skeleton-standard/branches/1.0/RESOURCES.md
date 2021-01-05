# Liste de ressources utiles

## .htaccess

- [RewriteBase dynamique](https://stackoverflow.com/questions/21062290/set-rewritebase-to-the-current-folder-path-dynamically)

```bash
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_URI}::$1 ^(.*?/)(.*)::\2$
RewriteRule ^(.*)$ - [E=BASE:%1]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ %{ENV:BASE}/index.php [L]
</IfModule>
````