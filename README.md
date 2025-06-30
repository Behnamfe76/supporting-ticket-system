# Supporting Ticket System

A modern, full-featured Laravel-based support ticket system, containerized with Docker for easy local development.  
This project leverages Laravel Octane (with Swoole), Inertia.js, Vue 3, MySQL, Redis, Mailhog, RedisInsight, phpMyAdmin, and more.

---

## Features

- **Modern Laravel 12** with Octane (Swoole) for high performance
- **Inertia.js + Vue 3** SPA frontend
- **MySQL** for persistent storage
- **Redis** for cache, queue, and session
- **Mailhog** for local email testing
- **phpMyAdmin** for DB management
- **RedisInsight** for Redis monitoring
- **Supervisor** for process management (Octane, Horizon, Reverb)
- **Full Docker Compose** setup for all services

---

## Architecture Overview

- **Nginx** serves as a reverse proxy to the Laravel Octane server.
- **Supervisor** manages Octane, Horizon (queues), and Reverb (WebSockets).
- **MySQL** is the main database.
- **Redis** is used for cache, session, and queues.
- **Mailhog** captures outgoing emails for development.
- **phpMyAdmin** and **RedisInsight** provide GUIs for DB and Redis.

For a detailed architecture diagram and flow, see [`docs/architecture.html`](docs/architecture.html).

---

## Quick Start (with Docker)

### 1. Clone the Repository

```bash
git clone https://github.com/Behnamfe76/supporting-ticket-system.git
cd supporting-ticket-system
```

### 2. Build and Start the Containers

```bash
docker-compose up --build -d
```

- The first build may take several minutes as it installs PHP, Composer, Node, and builds assets.

### 3. Run Database Migrations and Seeders

After the containers are up, you need to run the database migrations and seeders. First, open a shell inside the app container:

```bash
docker exec -it app bash
```

Then, inside the container, run:

```bash
php artisan migrate --seed
```

### 4. Access the Application

- **App:** [http://supporting-ticket-system.test](http://supporting-ticket-system.test) (or [http://localhost](http://localhost) if not using custom DNS)
- **phpMyAdmin:** [http://localhost:8080](http://localhost:8080)
- **Mailhog:** [http://localhost:8025](http://localhost:8025)
- **RedisInsight:** [http://localhost:5540](http://localhost:5540)

### 5. Monitoring & Debugging Panels

- **Horizon (Queue Monitoring):**  
  [http://supporting-ticket-system.test/horizon](http://supporting-ticket-system.test/horizon) or [http://localhost/horizon](http://localhost/horizon)  
  Use Horizon to monitor and manage your Laravel queues in real time.

- **Telescope (Debugging & Insights):**  
  [http://supporting-ticket-system.test/telescope](http://supporting-ticket-system.test/telescope) or [http://localhost/telescope](http://localhost/telescope)  
  Use Telescope to inspect requests, jobs, logs, queries, and more for in-depth debugging and application insights.

### 4. (Optional) Add Local DNS

For a nicer local domain, add this to your `/etc/hosts`:

```
127.0.0.1 supporting-ticket-system.test
```

---

## Common Docker Commands

- **Start containers:** `docker-compose up -d`
- **Stop containers:** `docker-compose down`
- **Rebuild after code changes:** `docker-compose up --build`
- **View logs:** `docker-compose logs -f`

---

## Database Access

- **MySQL Host:** `db`
- **Username/Password:** Set in `.env` (`DB_USERNAME`, `DB_PASSWORD`)
- **phpMyAdmin:** [http://localhost:8080](http://localhost:8080)

---

## Email Testing

- All outgoing emails are caught by **Mailhog** ([http://localhost:8025](http://localhost:8025)).

---

## Redis & Queues

- **Redis** is used for cache, session, and queues.
- **RedisInsight** ([http://localhost:5540](http://localhost:5540)) provides a GUI for monitoring.

---

## Customization

- **PHP config:** See `docker/php/php.ini`
- **Nginx config:** See `docker/nginx/nginx.conf`
- **Supervisor config:** See `docker/supervisord.conf`
- **Laravel initialization:** See `docker/init-laravel.sh`

---

## Troubleshooting

- If you change dependencies or environment variables, rebuild the containers:  
  `docker-compose up --build`
- If you encounter permission issues, ensure your `storage` and `bootstrap/cache` directories are writable.

---

## License

MIT

---

## Credits

- [Laravel](https://laravel.com/)
- [Inertia.js](https://inertiajs.com/)
- [Vue.js](https://vuejs.org/)
- [Docker](https://www.docker.com/)

---

For more details, see the [architecture documentation](docs/architecture.html). 
