<div align="center">

# Operation Log

<p>
    <a href="https://github.com/dcat-x/operation-log/actions"><img src="https://github.com/dcat-x/operation-log/actions/workflows/tests.yml/badge.svg" alt="Tests"></a>
    <a href="https://packagist.org/packages/dcat-x/operation-log"><img src="https://poser.pugx.org/dcat-x/operation-log/v/stable" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/dcat-x/operation-log"><img src="https://img.shields.io/packagist/dt/dcat-x/operation-log.svg" alt="Total Downloads"></a>
    <a href="https://www.php.net/"><img src="https://img.shields.io/badge/php-8.2+-59a9f8.svg" alt="PHP Version"></a>
    <a href="https://laravel.com/"><img src="https://img.shields.io/badge/laravel-12+-59a9f8.svg" alt="Laravel Version"></a>
    <a href="https://github.com/dcat-x/dcat-admin"><img src="https://img.shields.io/badge/dcat--admin-1.0+-59a9f8.svg" alt="Dcat Admin Version"></a>
    <a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
</p>

**Dcat Admin 操作日志扩展，自动记录管理后台的所有操作**

</div>

## 功能特性

- 自动记录所有后台操作请求
- 支持多种 HTTP 方法过滤
- 敏感字段自动脱敏处理
- 灵活的路由排除规则
- 多语言支持（中文/英文/繁体中文）
- 可视化日志管理界面

## 安装

```bash
composer require dcat-x/operation-log
```

## 配置

### 扩展设置

在 Dcat Admin 扩展管理页面启用后，可通过设置页面配置：

| 配置项 | 说明 |
|--------|------|
| `except` | 排除的路由，不记录这些路径的操作 |
| `allowed_methods` | 允许记录的 HTTP 方法 |
| `secret_fields` | 敏感字段，这些字段的值会被脱敏处理 |

### 默认配置

- **敏感字段**: `password`, `password_confirmation`
- **排除路由**: `dcat-admin.operation-log.*`
- **允许方法**: `GET`, `HEAD`, `POST`, `PUT`, `DELETE`, `CONNECT`, `OPTIONS`, `TRACE`, `PATCH`

## 使用

安装并启用扩展后，系统会自动记录所有管理后台操作。

### 访问日志

通过菜单或直接访问：

```
/admin/auth/operation-logs
```

### 日志内容

每条操作日志包含：

- 操作用户
- 请求方法 (GET/POST/PUT/DELETE 等)
- 请求路径
- 客户端 IP
- 请求参数 (敏感字段已脱敏)
- 操作时间

### 日志管理

- 支持按用户、方法、路径、IP、时间范围过滤
- 支持删除单条或批量删除日志

## 数据库

扩展会创建 `admin_operation_log` 表：

| 字段 | 类型 | 说明 |
|------|------|------|
| id | bigint | 主键 |
| user_id | bigint | 用户 ID |
| path | string | 请求路径 |
| method | string(10) | HTTP 方法 |
| ip | string(45) | 客户端 IP |
| input | text | 请求参数 (JSON) |
| created_at | timestamp | 创建时间 |
| updated_at | timestamp | 更新时间 |

## 开发

```bash
# 安装依赖
composer install

# 代码格式化
composer lint

# 运行测试
composer test
```

## 更新日志

详见 [CHANGELOG](CHANGELOG.md)

## 许可证

[MIT](LICENSE)
