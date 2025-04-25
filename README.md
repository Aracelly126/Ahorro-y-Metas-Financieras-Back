# 💰 Simulador de Ahorro y Metas Financieras

Aplicación web que permite a los usuarios planificar metas de ahorro, registrar sus aportes y visualizar su progreso financiero. Desarrollada para ayudar a organizar las finanzas personales mediante seguimiento de metas, cálculos aritméticos y recordatorios automatizados.

## 🎯 Objetivo General

Desarrollar una herramienta digital que permita:
- Crear y gestionar metas de ahorro.
- Registrar aportes manualmente.
- Visualizar el progreso de ahorro con gráficas dinámicas.
- Recibir sugerencias personalizadas de ahorro.
- Obtener reportes y recordatorios por correo.

## 🧱 Arquitectura del Proyecto

- **Frontend (Feature-based architecture):** React + Vite + TailwindCSS + Fetch + JWT.
- **Backend (MVC):** Laravel + MySQL + REST API + Mailtrap.


## 🛠️ Tecnologías Utilizadas

| Tecnología       | Versión |
|------------------|---------|
| Laravel          | 12.0    |
| PHP              | 8.2.16  |
| MySQL            | 8.0.42  |
| React            | 19.0.0  |
| Vite             | 4.1.4   |
| Tailwind CSS     | 4.1.4   |
| Fech             | 1.0.0   |
| JWT Auth         | -       |


## ⚙️ Instalación del Proyecto
### 📁 Clonar el repositorio

## Front-end
git clone https://github.com/Aracelly126/Ahorro-y-Metas-Financieras.git
## Back-end
git clone https://github.com/Aracelly126/Ahorro-y-Metas-Financieras-Back.git

## 🛠️ Backend (Laravel)
cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

php artisan serve

## 💻 Frontend (React + Vite)
cd frontend

npm install

npm run dev

## 🔐 Autenticación
Registro/Login mediante JWT.

Almacena token en localStorage.

Middleware de protección de rutas privadas.

## ✅ Funcionalidades Implementadas
- Registro e inicio de sesión.
- Gestión de perfil (foto, nombre, fecha, género).
- CRUD de metas de ahorro (con validaciones).
- Registro manual de aportes.
- Cálculo automático de progreso y sugerencias.
- Detección de inactividad del usuario.
- Envío automático de recordatorios y reportes PDF por correo.
- Reportes por categoría, estado, y exportación a PDF/Excel.
- Gráficas interactivas de ahorro planificado vs real.

## 📊 Indicadores de Progreso

- 🟢 **Verde:** Buen ritmo
- 🟡 **Amarillo:** Ligeramente retrasado
- 🔴 **Rojo:** Ritmo muy por debajo


## ⚠️ Meta en riesgo:

Si el usuario necesita ahorrar más del doble del promedio semanal esperado original, la meta se considera en riesgo.

## 💡Sugerencias dinámicas:

Cada vez que el usuario actualiza su meta o registro de aporte, se recalcula el monto sugerido por semana/mes.

## 📬 Recordatorios y Reportes
- Envío automático por correo si no hay aportes en 7 días.

- Reporte resumen (PDF) con metas activas, vencidas y cumplidas.

- Exportación a PDF o Excel desde la aplicación.

## 👥 Equipo de Desarrollo
Aracelly de los Ángeles Guangasi (Desarrollo de Backend)

José Elías Muyulema Pungaña (Desarrollo de Backend)

Neris David Giler Rizzo (Desarrollo de Frontend)

Jins Steeven Loor Sandoya (Desarrollo de Frontend)


