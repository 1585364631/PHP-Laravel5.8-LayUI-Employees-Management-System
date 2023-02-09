# PHP-Laravel5.8-LayUI-Employees-Management-System
后端基于Laravel5.8，前端基于LayUI框架开发的员工管理系统

# 网站演示
http://employees.000081.xyz/  
账号：admin  
密码：12345  

# 项目部署
## 1.解压项目部署到Laravel环境网站上  
## 2.将项目下.env.example改为.env文件  
## 3.mysql中创建新的数据库  
## 4.修改.env文件中的数据库配置信息  
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```
## 5.恢复伪静态文件  
### 5.1 nginx伪静态  
```conf
location / {
                # First attempt to serve request as file, then
                # as directory, then fall back to displaying a 404.
                # try_files $uri $uri/ =404;
                try_files $uri $uri/ /index.php?$query_string;
}
```
### 5.2 apache伪静态  
```conf
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

```

## 6.执行命令安装相关依赖
```bash
composer install
```

## 7.执行迁移文件命令
```bash
php artisan migrate
```

## 8.填充种子文件数据
```bash
php artisan db:seed --class=AllTableSeeder
```

## 进入网站测试
账号：admin  
密码：12345  
