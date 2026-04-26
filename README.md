<div align="center">

# WorldSkills Philippines 2026 — Web Technologies
### Western Mindanao (Regional 9) — Regional Champion

**Competitor: Christian Dhane Ramizo**

<br/>

![WorldSkills](https://img.shields.io/badge/WorldSkills%20Philipppines-2026-gold?style=for-the-badge&labelColor=1a1a2e)
![Region](https://img.shields.io/badge/Region-IX-blue?style=for-the-badge&labelColor=16213e)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-Structure-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-Styling-1572B6?style=for-the-badge&logo=css3&logoColor=white)

<br/>

**First Place Winner** at the **Philippines WorldSkills 2026 — Regional 9 (Western Mindanao)** under the skill category of **Web Technologies** representing excellence in full-stack web development, RESTful API design, and client-side application.

<br/>

</div>

---

## Table of Contents

- [Competition Overview](#-competition-overview)
- [Project Structure](#-project-structure)
- [Module B — Companies & Products Management System](#-module-b--companies--products-management-system)
  - [Features](#features-module-b)
  - [Tech Stack](#tech-stack-module-b)
  - [Database Schema](#database-schema)
  - [REST API Reference](#rest-api-reference)
  - [Setup & Installation](#setup--installation-module-b)
- [Module E — Photo Slideshow Application](-module-e--photo-slideshow-application)
  - [Features](#features-module-e)
  - [Tech Stack](#tech-stack-module-e)
  - [Setup & Usage](#setup--usage-module-e)
- [API Endpoints](#-api-endpoints)
- [Acknowledgements](#-acknowledgements)

---

## Competition Overview

| Field | Details |
|-------|---------|
| **Event** | WorldSkills Philippines 2026 — Regional 9 Competition |
| **Venue** | Zamboanga Peninsula Polytechnic State University (ZPPSU) |
| **Location** | Western Mindanao, Region IX, Philippines |
| **Skill Category** | Web Technologies |
| **Result** | **1st Place / Regional 9 Champion** |
| **Year** | 2026 |
| **Module E (Day 1)** | Photo Slideshow Application — 8 hours |
| **Module B (Day 2)** | Companies & Products Management System — 8 hours |

The competition challenged participants to design, develop, and deploy complete web solutions under a strict **8-hour time limit per module per day**, held at **ZPPSU** in Zamboanga City. Each day demanded rapid decision-making, real-time problem solving, and full-stack implementation skills — from backend database design to client-side JavaScript.

> **Note:** Some features in both modules were not fully completed due to the tight 8-hour time constraint per day. The code submitted represents the best possible implementation within the allotted time.

---

## Project Structure

```
Regional Web Technology WorldSkills 2026
├── stii_module_b/             # Module B — Companies & Products Management
│   ├── config.php             # Database connection & helper functions
│   ├── login.php              # Admin authentication
│   ├── logout.php             # Session termination
│   ├── companies.php          # List all active companies
│   ├── company_form.php       # Create/Edit company records
│   ├── company_view.php       # View company details
│   ├── deactivated_companies.php  # Manage deactivated companies
│   ├── products.php           # List all products
│   ├── product_form.php       # Create/Edit product records
│   ├── product_view.php       # View product details
│   ├── product_delete.php     # Delete product
│   ├── public_product.php     # Public-facing product page
│   ├── verify_gtin.php        # GTIN barcode verification
│   ├── api.php                # RESTful JSON API
│   ├── .htaccess              # Apache URL rewriting
│   └── uploads/               # Product image uploads
│
└── stii_module_e/             # Module E — Photo Slideshow Application
    ├── index.html             # Main application HTML
    ├── script.js              # Application logic (Vanilla JS)
    ├── style.css              # Theming & layout styles
    ├── setting-icon.png       # UI asset
    └── samplephotos/          # Built-in sample photo set
```

---

## Module B — Companies & Products Management System

A full-stack web application for managing companies and their associated products, featuring a secure admin panel, public product browsing, GTIN verification, and a RESTful JSON API.

### Features (Module B)

| Category | Capability |
|----------|-----------|
| **Authentication** | Secure admin login with PHP session management |
| **Company Management** | Create, read, update, deactivate companies |
| **Product Management** | Full CRUD for products with image upload support |
| **GTIN Verification** | Public-facing barcode/GTIN lookup page |
| **Public Pages** | Browse visible products without authentication |
| **REST API** | JSON API with pagination and keyword search |
| **Security** | PDO prepared statements · XSS protection · Session-based access control |

### Tech Stack (Module B)

```
Backend     → PHP 8.x (PDO + MySQL)
Database    → MySQL / MariaDB
Frontend    → HTML5 · CSS3 · Vanilla JS
Server      → Apache (XAMPP) with mod_rewrite
```

### Database Schema

The system uses a normalized relational schema with two primary tables:

```sql
-- Companies Table
CREATE TABLE companies (
    id                       INT AUTO_INCREMENT PRIMARY KEY,
    company_name             VARCHAR(255) NOT NULL,
    company_address          TEXT,
    company_telephone_number VARCHAR(50),
    company_email_address    VARCHAR(255),
    owner_name               VARCHAR(255),
    owner_mobile_number      VARCHAR(50),
    owner_email_address      VARCHAR(255),
    contact_name             VARCHAR(255),
    contact_mobile_number    VARCHAR(50),
    contact_email_address    VARCHAR(255),
    is_active                TINYINT(1) DEFAULT 1,
    created_at               TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE products (
    id                 INT AUTO_INCREMENT PRIMARY KEY,
    company_id         INT NOT NULL,
    name               VARCHAR(255) NOT NULL,
    name_fr            VARCHAR(255),
    description        TEXT,
    description_fr     TEXT,
    gtin               VARCHAR(14) UNIQUE NOT NULL,
    brand_name         VARCHAR(255),
    country_origin     VARCHAR(100),
    gross_weight       DECIMAL(10,2),
    net_content_weight DECIMAL(10,2),
    weight_unit        VARCHAR(20),
    image_path         VARCHAR(500),
    is_hidden          TINYINT(1) DEFAULT 0,
    created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
);
```

### REST API Reference

The API is accessible at `/stii_module_b/` and returns `application/json`.

#### `GET /products.json`
Returns a paginated list of all **visible** products.

**Query Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `page` | `int` | Page number (default: `1`) |
| `query` | `string` | Keyword search (name, description — EN & FR) |

**Example Response:**
```json
{
  "data": [
    {
      "name": { "en": "Apple Juice", "fr": "Jus de Pomme" },
      "description": { "en": "Fresh apple juice", "fr": "Jus de pomme frais" },
      "gtin": "1234567890123",
      "brand": "FreshBrand",
      "countryOfOrigin": "Philippines",
      "weight": { "gross": 1.5, "net": 1.2, "unit": "kg" },
      "company": {
        "companyName": "FreshCo Inc.",
        "companyAddress": "Zamboanga City, Philippines",
        "companyTelephone": "+63-912-345-6789",
        "companyEmail": "info@freshco.ph",
        "owner": { "name": "Juan Dela Cruz", "mobileNumber": "...", "email": "..." },
        "contact": { "name": "Maria Santos", "mobileNumber": "...", "email": "..." }
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 5,
    "per_page": 10,
    "next_page_url": "http://localhost/stii_module_b/products.json?page=2",
    "prev_page_url": null
  }
}
```

#### `GET /products/{gtin}.json`
Returns a **single product** by its GTIN barcode.

**Example:** `GET /products/1234567890123.json`

**404 Error Response:**
```json
{ "error": "Product not found" }
```

### Setup & Installation (Module B)

> **Prerequisites:** XAMPP (or any LAMP/WAMP stack) with PHP 8+ and MySQL.

```bash
1. Copy stii_module_b/ into your XAMPP htdocs folder
    C:/xampp/htdocs/stii_module_b/

2. Start Apache & MySQL from the XAMPP Control Panel

3. Create the database:
    - Go to http://localhost/phpmyadmin
    - Create a new database named: stii_module_b
    - Import stii_module_b.sql (see note below)

4. Open the application
    http://localhost/stii_module_b/login
```

> **Run XAMPP as Administrator** to ensure proper permissions for URL rewriting and file uploads.

> **Database Note:** The original database with all competition data was left on the competition PC at ZPPSU and was not exported. The included `stii_module_b.sql` file is a **reconstructed schema** based on the PHP source code, with sample data added so the application can run. The actual records entered during the competition are no longer available. You can use the sample data as a reference to understand the data structure.

---

## Module E — Photo Slideshow Application

A fully client-side photo slideshow application built with **Vanilla JavaScript**, featuring multiple display themes, playback modes, drag-and-drop photo reordering, and fullscreen support — with zero external dependencies.

### Features (Module E)

| Feature | Description |
|---------|-------------|
| **Browse Photos** | Load local images via file picker |
| **Drag & Drop** | Drop images directly onto the app |
| **Drag to Reorder** | Reorder photos in the thumbnail strip |
| **Sample Photos** | Preloaded Lyon, France photography set |
| **Playback Modes** | Manual · Auto Play (3s) · Random Play (3s) |
| **6 Themes** | Theme A–F with distinct visual styles |
| **Animated Captions** | Per-word fade-in stagger animation (Theme C) |
| **Keyboard Navigation** | Arrow keys for manual slide control |
| **Fullscreen Mode** | Native browser Fullscreen API |

### Tech Stack (Module E)

```
HTML5      → File API · Drag & Drop API · Fullscreen API
CSS3       → Theme system · Keyframe animations · Responsive layout
JavaScript → Vanilla ES6+ · Zero dependencies · Zero frameworks
```

### Setup & Usage (Module E)

```bash
No server required — runs entirely in the browser!

Open index.html in any modern browser:
open stii_module_e/index.html

Load photos via:
    - "Browse Photos"       → File picker dialog
    - "Load Sample Photos"  → Built-in Lyon, France demo set
    - Drag & Drop           → Drop images onto the drop zone

Operating Modes:
    Manual      → ← → Arrow keys to navigate slides
    Auto Play   → Advances automatically every 3 seconds
    Random Play → Randomly picks the next photo every 3 seconds

Themes A–F change the visual presentation style of each slide
"Full Screen" button triggers immersive browser fullscreen
```

---

## API Endpoints

| Method | Endpoint | Auth Required | Description |
|--------|----------|:---:|-------------|
| `GET` | `/stii_module_b/products.json` | ❌ | List products (paginated + search) |
| `GET` | `/stii_module_b/products/{gtin}.json` | ❌ | Get single product by GTIN |
| `GET` | `/stii_module_b/login` | ❌ | Admin login page |
| `POST` | `/stii_module_b/login` | ❌ | Authenticate admin |
| `GET` | `/stii_module_b/companies` | ✅ | List all companies |
| `GET/POST` | `/stii_module_b/companies/new` | ✅ | Create company |
| `GET/POST` | `/stii_module_b/companies/{id}/edit` | ✅ | Edit company |
| `GET` | `/stii_module_b/products` | ✅ | List all products (admin) |
| `GET/POST` | `/stii_module_b/products/new` | ✅ | Create product |
| `GET/POST` | `/stii_module_b/products/{gtin}/edit` | ✅ | Edit product |
| `POST` | `/stii_module_b/products/{gtin}/delete` | ✅ | Delete hidden product |
| `GET/POST` | `/stii_module_b/verify-gtin` | ❌ | Public bulk GTIN verification |
| `GET` | `/stii_module_b/01/{gtin}` | ❌ | Public product page (EN/FR) |

---

## Acknowledgements

- **WorldSkills & WorldSkills Philippines** — for the global standard that inspires excellence in vocational skills
- **Technical Education and Skills Development Authority (TESDA) Regional 9** — for organizing the regional competition
- All **mentors, coaches, and family** who made this achievement possible
