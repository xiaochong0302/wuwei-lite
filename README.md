# WuWei Lite

WuWei Lite is the **community edition of WuWei LMS** — a streamlined learning management system designed for online education.

---

## 📦 Installation Guide

### ✅ Requirements
- **Server Type:** VPS or Dedicated Server (❌ Shared hosting not supported)
- **Operating System:** Debian, Ubuntu, or RHEL (**Debian recommended**)
- **Memory (RAM):** 2 GB or more

> ⚠️ **Important:** Use a **clean system**. If your server already has web services like **Nginx** or **Apache** running (especially on ports `80` or `443`), they may conflict with this installation.

### 🚀 Installation Steps

#### 1. Log in as Root
Make sure you have root privileges:
```bash
sudo -i
```

#### 2. Download the Installation Script

```bash
cd ~ && curl -O https://download.koogua.com/wuwei-lite/install.sh
```

#### 3. Run a Clean Installation

```
bash install.sh --domain {your_domain}
```

Replace `{your_domain}` with your actual domain name or server ip address.

**Admin Login (default)**

- **Email:** `10000@163.com`
- **Password:** `123456`

#### 4. Install with Demo Data (optional)

```
bash install.sh --domain {your_domain} --demo on
```

Replace `{your_domain}` with your actual domain name or server ip address.

**Admin Login (demo)**

- **Email:** `100015@163.com`
- **Password:** `123456`

#### 5. Access Your Website

- **Home Page:** http://{your_domain}
- **Admin Panel:** http://{your_domain}/admin

---

## 🐳 WuWei Lite Docker

Pre-configured Docker environment:

👉 [WuWei Lite Docker](https://github.com/xiaochong0302/wuwei-lite-docker)

---

## 📖 Documentation

For full usage guides, configuration, and advanced setup:

👉 [WuWei Documentation](https://www.koogua.net/wuwei/docs)

---

## 📜 License

- WuWei Lite is released under the [GPL-3.0 license](https://opensource.org/licenses/GPL-3.0)
- For commercial use and advanced features, see [WuWei Pro](https://www.koogua.net/wuwei/features)
