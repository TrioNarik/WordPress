ShortCode
[contact_form email="ddd@gg.pl" success_page='']

/wp-content/languages/themes/
---------
https://localise.biz/free/poeditor 
===============> generowanie *.mo na podstawie *.po (btr-pen-pl_PL.po => btr-pen-pl_PL.mo)


SECURITY:
https://www.web2generators.com/apache-tools/htpasswd-generator
File:
.htpasswd => folder .secure
.htaccess => 
<Files wp-login.php>
    AuthType Basic
    AuthName "Restricted Area"
    AuthUserFile C:\_Webserwer\GrupaFaro\WordPress_6\.secure\.htpasswd
    Require valid-user
</Files>

ps. echo __DIR__; w info.php
