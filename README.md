# sharenote

## 使用

- 克隆代码

 
```
git@github.com:omwteam/sharenote.git
```

 
- 安装依赖


```
composer install
```


- 配置env

```
cp .env.example .env
```

修改数据库用户名和密码

- 生成key

```
php artisan key:generate
```

- 数据迁移

```
php artisan migrate
```
- 生成数据

```
php artisan db:seed
```

- 启动服务


```
php artisan serve
```