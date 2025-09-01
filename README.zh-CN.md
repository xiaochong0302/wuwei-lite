简体中文 | [English](./README.md)

# WuWei Lite

WuWei Lite 是 **WuWei LMS 的社区版**，一款专为在线教育设计的轻量级学习管理系统。

## 📦 安装指南

### ✅ 环境要求

- **服务器类型:** VPS 或独立服务器（❌ 不支持虚拟主机）
- **操作系统:** Debian、Ubuntu 或 RHEL（ **推荐使用 Debian** ）
- **内存 (RAM):** 2 GB 或以上

> ⚠️ **重要提示:** 请使用一个 **干净的系统** 。如果您的服务器上已经运行了 **Nginx** 或 **Apache** 等 Web 服务（特别是占用了 `80` 或 `443` 端口），可能会与此安装发生冲突。

### 🚀 安装步骤

#### 1. 以 Root 用户登录

确保您拥有 root 权限：

```
sudo -i
```

#### 2. 下载安装脚本

```
cd ~ && curl -O https://download.koogua.com/wuwei-lite/install.sh
```

#### 3. 执行全新安装

```
bash install.sh --domain {你的域名}
```

将 `{你的域名}` 替换为您实际的域名或服务器 IP 地址。

**管理员登录信息**

- **邮箱:** `10000@163.com`
- **密码:** `123456`

#### 4. 安装演示数据（可选）

```
bash install.sh --domain {你的域名} --demo on
```

将 `{你的域名}` 替换为您实际的域名或服务器 IP 地址。

**管理员登录信息**

- **邮箱:** `100015@163.com`
- **密码:** `123456`

#### 5. 访问您的网站

- **首页:** http://{你的域名}
- **管理后台:** http://{你的域名}/admin

## 🐳 WuWei Lite Docker

预配置的 Docker 环境：

👉 [WuWei Lite Docker](https://github.com/xiaochong0302/wuwei-lite-docker)

## 📖 文档

完整的用法指南、配置和高级设置：

👉 [WuWei 文档](https://www.koogua.net/wuwei/docs)

## 📜 许可证

- WuWei Lite 基于 [GPL-3.0 许可证](https://opensource.org/licenses/GPL-3.0) 发布
- 商业用途和高级功能，请参见 [WuWei Pro](https://www.koogua.net/wuwei/features)
