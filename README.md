# 🎨 JV Graphics Design Services - Web Ordering System

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Active Development](https://img.shields.io/badge/Status-Active-brightgreen.svg)]()
[![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Vue 3](https://img.shields.io/badge/Vue-3-4FC08D?logo=vue.js&logoColor=white)](https://vuejs.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.1-06B6D4?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![MySQL 8](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com)
[![Docker](https://img.shields.io/badge/Docker-✓-2496ED?logo=docker&logoColor=white)](https://www.docker.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)](https://www.php.net)

> Professional web-based ordering system that streamlines graphics design service requests and order management.

### ✨ Key Features

### 👤 Client Features

- 🔐 Secure user authentication (registration/login)
- 🖼️ Intuitive order submission with file uploads
- 📊 Real-time order status tracking
- 💬 Integrated messaging system

### 👨‍💼 Admin Features

- 🎛️ Comprehensive dashboard with analytics
- 📝 Order management tools
- 👥 Client management portal

## 💻 Live Demo

👉 [Visit the live project here https://jvgdss.webs.vc](https://jvgdss.webs.vc)

## 🛠 Tech Stack

### Backend

| Technology | Version | Purpose                     |
| ---------- | ------- | --------------------------- |
| Laravel    | 12      | MVC Framework               |
| PHP        | 8.2+    | Server-side logic           |
| MySQL      | 8.0     | Primary database            |
| Sqlite     | latest  | Database during development |

### Frontend

| Technology  | Version | Purpose             |
| ----------- | ------- | ------------------- |
| Vue.js      | 3       | Reactive UI         |
| TailwindCSS | 4.1     | Utility-first CSS   |
| Inertia.js  | latest  | SPA-like experience |
| Vite        | latest  | Build tool          |

### 🖼️ Icons & Fonts

- [Bootstrap Icons](https://icons.getbootstrap.com/) – Free, high-quality SVG icons from Bootstrap.
- [Font Awesome](https://fontawesome.com/) – Popular icon library (free + paid tiers).
- [Google Fonts](https://fonts.google.com/) – Free, open-source fonts for web projects.

### 📦 Tools & Utilities

- [Mailtrap](https://mailtrap.io/) — Email testing tool.
- [NPM](https://www.npmjs.com/) — JavaScript package manager.
- [GitHub Copilot](https://github.com/features/copilot) — AI-powered coding assistant.
- [ChatGPT](https://chatgpt.com/) — AI assistant for code review, brainstorming, and writing help.
- [Figma](https://www.figma.com/) — Collaborative interface design and prototyping tool.
- [Docker](https://www.docker.com) — A platform for containerizing applications (note: the description seems incorrect; it’s not for design but for DevOps).

## 🚀 Quick Start, Installation guide

### Clone the Repository

```bash
git clone https://github.com/Wowmeww/JV_Graphics_Design_Services_Web-based_Ordering_System.git
cd JV_Graphics_Design_Services_Web-based_Ordering_System
```

### Traditional Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
npm run build
composer run dev #this should run (npm run dev, php artisan serve, php artisan queue:listen, php artisan reverb:start)
```

### With Docker (Recommended)

```bash
# Build and launch containers
docker compose up -d --build
# docker compose up -d    # if built
# docker exec -it <container_name> bash
# Replace <container_name> with your actual container name
docker exec -it capstone-app bash
npm run build
```

### Database preparation

##### Configure your database in .env file:

```bash
# traditional
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jv_graphics_design_services
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# docker
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=jv_graphics_design_services
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# for sqlite
DB_CONNECTION=sqlite
```

##### Create database structure

```bash
php artisan migrate
```

##### Create structure with sample data

```bash
php artisan migrate --seed
```

##### Reset entire database (DANGER: destroys all data)

```bash
php artisan migrate:fresh
```

##### Reset and repopulate with sample data

```bash
php artisan migrate:fresh --seed
```

### 📂 Project Structure

```bash
├── app/               # Application core
├── assets/            # Uploaded resource by developer
├── bootstrap/         # Framework initialization
├── config/            # Configuration files
├── database/          # Migrations & seeders
├── public/            # Entry point
├── resources/         # Views & assets
├── routes/            # Application routes
├── storage/           # Uploads & logs
└── tests/             # Test cases
```

## 📬 Contact

Developed by [@Wowmeww](https://github.com/Wowmeww) 🚀

Support: [nicobernardfirmanes@gmail.com ](https://github.com/Wowmeww)

## 🙏 Acknowledgments

Thanks to [ChatGPT](https://chatgpt.com/), [GitHub Copilot](https://github.com/features/copilot) and [DeepSeek](https://chat.deepseek.com) for assisting in code generation and project planning.

## 📄 License

This project is licensed under the [MIT License](LICENSE).

## 📸 Screenshots

|                      Home Page                       |                        Login Page                        |
| :--------------------------------------------------: | :------------------------------------------------------: |
| ![Home Screenshot](./assets/screenshots/landing.png) | ![Login Page Screenshot](./assets/screenshots/login.png) |

|                          Customer Dashboard                          |                          Admin Dashboard                          |
| :------------------------------------------------------------------: | :---------------------------------------------------------------: |
| ![Dashboard Screenshot](./assets/screenshots/customer_dashboard.png) | ![Dashboard Screenshot](./assets/screenshots/admin_dashboard.png) |

---

-->
