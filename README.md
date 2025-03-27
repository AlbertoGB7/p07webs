# 🚀 Pràctica 7 Laravel: Aplicació d'Articles amb Integracions de Clash of Clans

![Laravel Logo](https://laravel.com/img/logomark.min.svg)

## 📝 Descripció

Aquest projecte és una aplicació web desenvolupada amb **Laravel** que permet als usuaris gestionar articles, compartir-los mitjançant codis QR i integrar dades de l'API oficial de **Clash of Clans**. L'aplicació ofereix múltiples mètodes d'autenticació i una experiència d'usuari completa.

---

## 🎮 Temàtica

La integració amb l'API de **Clash of Clans** permet als usuaris visualitzar informació actualitzada sobre **clans** i **jugadors** directament des de l'aplicació.

---

## 👤 Usuaris de Prova

### Administrador
- **Usuari:** `Xavi`
- **Correu:** `xmartin@sapalomera.cat`
- **Contrasenya:** `P@ssw0rd`

### Usuari Normal
- **Usuari:** `Alberto`
- **Correu:** `alberto.gb222@gmail.com`
- **Contrasenya:** `P@ssw0rd`

---

## ✨ Funcionalitats Principals

### 🔐 Sistema d'Autenticació Avançat

- Login tradicional amb usuari/contrasenya
- Registre de nous usuaris
- 🔐 Integració amb **Google OAuth**
- 🖥️ Integració amb **GitHub OAuth**
- 📧 Recuperació de contrasenya via email
- 🍪 Recordar sessió mitjançant cookies

---

### 📚 Gestió d'Articles

- CRUD complet (Crear, Llegir, Actualitzar, Eliminar)
- 🔍 Cercador i filtrat d'articles
- 📅 Ordenació per data o títol
- 🔢 Selecció d'articles per pàgina

---

### 📱 Articles Compartits i QR

- Vista **AJAX** per carregar articles compartits
- Generació de **codis QR**
- Lectura de codis QR per importar articles
- Còpia directa d'articles compartits al perfil

---

### ⚔️ Integració amb l'API de Clash of Clans

#### Informació del Clan:
- Escut
- Nom
- Nivell
- Membres

#### Perfil de Jugador:
- Lliga
- Nom
- Nivell
- Copes

---

### 👨‍💼 Gestió d'Usuaris (Només Admin)

- Llistat d'usuaris
- Eliminació d'usuaris i els seus articles

---

### 👤 Gestió de Perfil

- Modificació del nom d'usuari
- Canvi d'imatge de perfil
- Canvi de contrasenya

---

## 🛠️ Tecnologies Utilitzades

- **Laravel 12** - Framework PHP
- **MySQL** - Base de dades
- **Bootstrap 5** - Framework CSS
- **JavaScript** - Interactivitat
- **AJAX / Fetch** - Càrregues dinàmiques
- **OAuth 2.0** - Autenticació social
- **API REST** - Integració externa

---

## 🧩 Arquitectura MVC

El projecte segueix el patró **Model-Vista-Controlador** de Laravel:

- **Models:** `Article`, `ArticleCompartit`, `Usuari`
- **Vistes:** Plantilles Blade
- **Controladors:** Tota la lògica de negoci

---

## 🔄 API RESTful

### Rutes disponibles:

GET /api/articles GET /api/articles/{userId} POST /api/articles PUT /api/articles DELETE /api/articles/{id}/{userId}


---

## 📋 Requisits

- PHP 8.1 o superior
- Composer
- MySQL
- Node.js i NPM (per als assets)
- Claus d'API de **Google** i **GitHub**
- Clau de l'API de **Clash of Clans**

---

## ⚙️ Instal·lació

```bash
# 1. Clona el repositori
git clone https://github.com/usuari/laravel-practica7.git

# 2. Entra al projecte
cd laravel-practica7

# 3. Instal·la les dependències de PHP
composer install

# 4. Instal·la les dependències de Node
npm install && npm run build

# 5. Copia el fitxer d'entorn
cp .env.example .env

# 6. Configura la base de dades i les claus al .env

# 7. Genera la clau de Laravel
php artisan key:generate

# 8. Executa les migracions
php artisan migrate

# 9. Inicia el servidor
php artisan serve
