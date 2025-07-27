# Bocek1.cz - WordPress Development Environment

A complete Docker-based development environment for the bocek1.cz WordPress website featuring custom theme development, automatic plugin management, and asset compilation.

## 🚀 Quick Start

### Prerequisites
- Docker and Docker Compose installed
- Your database dump file (see [Database Setup](#database-setup))

### 1. Clone and Setup
```bash
git clone <repository-url>
cd bocek1.cz
```

### 2. Database Setup
Your database dump has been moved to the correct location:
- `database/bocek_database.sql` - Ready for automatic import

### 3. Start Development Environment
```bash
# Start all services
docker-compose up --build

# Or run in background
docker-compose up -d --build
```

### 4. Access Your Site
- **WordPress Site**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **Admin Login**: admin / admin (default)

## 🏗️ Architecture

### Services
- **WordPress**: PHP 8.2.1 + Apache with WP-CLI
- **Database**: MariaDB 10.11
- **Node.js**: Asset compilation and watching
- **phpMyAdmin**: Database management interface

### Directory Structure
```
bocek1.cz/
├── bocek/                          # Custom WordPress theme
│   ├── assets/                     # Source assets (SCSS, JS, images)
│   ├── build/                      # Compiled assets (generated)
│   ├── includes/                   # PHP functionality modules
│   ├── parts/                      # Template parts and blocks
│   └── package.json               # Node.js dependencies and scripts
├── plugins/                        # Custom plugins directory
│   ├── advanced-custom-fields-pro/    # ACF Pro plugin (custom)
│   ├── digitalmediate-wp-toolkit/     # Custom toolkit plugin
│   └── digitalmediate-zalohovani/     # Custom backup plugin
├── database/                      # Database import directory (place bocek_database.sql here)
├── docker/                        # Docker configuration files
├── uploads/                       # WordPress media uploads (volume)
└── docker-compose.yml            # Docker services configuration
```

## 🔧 Development Workflow

### Theme Development
The theme assets are automatically compiled and watched:

```bash
# Manual compilation (inside bocek/ directory)
npm run build              # Development build
npm run build:production   # Production build
npm run watch             # Watch for changes
npm run build:svg_sprites  # Generate SVG sprites
```

### Asset Pipeline
- **SCSS**: Compiled to CSS with autoprefixing
- **JavaScript**: Bundled with Webpack
- **Images**: Optimized and organized
- **Fonts**: Processed and organized
- **SVG Sprites**: Automated generation

### Plugin Management
Plugins are automatically installed via WP-CLI on container startup:

**Managed via WP-CLI** (automatically installed):
- Advanced Custom Fields (free)
- Polylang
- Loco Translate
- Duplicate Page
- Simple History
- Automatic Translations for Polylang

**Custom Plugins** (mounted individually from /plugins directory):
- ACF Pro
- Digital Mediate WP Toolkit
- Digital Mediate Zalohovani

### Database Management
- Access phpMyAdmin at http://localhost:8081
- Database is automatically imported from `database/` directory
- Volume persists data between container restarts

## 🛠️ Available Commands

### Docker Commands
```bash
# Start development environment
docker-compose up --build

# Stop all services
docker-compose down

# View logs
docker-compose logs -f wordpress
docker-compose logs -f node

# Rebuild containers
docker-compose build --no-cache

# Access WordPress container shell
docker-compose exec wordpress bash

# Access database
docker-compose exec db mysql -u wordpress -p wordpress
```

### WordPress CLI Commands
```bash
# Execute WP-CLI commands
docker-compose exec wordpress wp --help

# Examples:
docker-compose exec wordpress wp user list --allow-root
docker-compose exec wordpress wp plugin list --allow-root
docker-compose exec wordpress wp theme list --allow-root
```

### Theme Asset Commands
```bash
# Inside bocek/ directory or via Docker
docker-compose exec node npm run build
docker-compose exec node npm run watch
```

## 📁 Key Files

### Configuration Files
- `docker-compose.yml` - Docker services configuration
- `Dockerfile` - WordPress container setup
- `wp-config-docker.php` - WordPress configuration for Docker
- `docker/plugins.json` - Plugin installation configuration

### Theme Files
- `bocek/functions.php` - Theme functionality loader
- `bocek/webpack.config.js` - Asset compilation configuration
- `bocek/assets/css/main.scss` - Main stylesheet
- `bocek/assets/js/main.js` - Main JavaScript file

## 🔒 Security Notes

- Default admin credentials: admin/admin (change in production)
- WordPress debug mode is enabled for development
- Database credentials are in docker-compose.yml (environment-specific)

## 🐛 Troubleshooting

### Common Issues

**Assets not compiling:**
```bash
# Restart node service
docker-compose restart node

# Check node logs
docker-compose logs node
```

**Database connection issues:**
```bash
# Check database status
docker-compose logs db

# Restart database
docker-compose restart db
```

**WordPress not accessible:**
```bash
# Check WordPress logs
docker-compose logs wordpress

# Verify container is running
docker-compose ps
```

### Reset Environment
```bash
# Complete reset (removes all data)
docker-compose down -v
docker-compose up --build

# Reset only containers (keeps database)
docker-compose down
docker-compose up --build
```

## 📝 Development Notes

### Theme Development
- Theme uses modular PHP structure in `includes/` directory
- Assets are compiled with Webpack and PostCSS
- SCSS follows BEM methodology with component-based organization
- JavaScript uses jQuery (available globally)

### Customization
- Modify `docker/plugins.json` to change plugin configuration
- Update `wp-config-docker.php` for WordPress settings
- Adjust `docker-compose.yml` for service configuration

### Production Deployment
1. Build production assets: `npm run build:production`
2. Export database with proper domain URLs
3. Update wp-config.php for production settings
4. Deploy files and database to production server

---

**Support**: For issues related to the development environment, check Docker logs and ensure all prerequisites are installed.