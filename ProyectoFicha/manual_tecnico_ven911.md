# MANUAL TÉCNICO

# Sistema Web de Gestión de Emergencias — VEN 9-1-1

---

## 1. PORTADA

| Campo | Detalle |
|---|---|
| **Nombre del sistema** | Sistema Web de Gestión de Emergencias VEN 9-1-1 |
| **Versión** | 1.0 |
| **Fecha** | Junio 2026 |
| **Autor** | Juan Ignacio Soto Mejías |
| **Institución** | Centro de Comando, Control y Telecomunicaciones VEN 9-1-1, sede Carabobo |
| **Universidad** | Universidad Nacional Experimental Politécnica de la Fuerza Armada Nacional Bolivariana (UNEFA) |

---

## 2. INTRODUCCIÓN

### 2.1 Propósito del Documento

El presente manual técnico documenta de forma exhaustiva la arquitectura, estructura, configuración, módulos funcionales y procedimientos de mantenimiento del Sistema Web de Gestión de Emergencias desarrollado para el Centro de Comando, Control y Telecomunicaciones VEN 9-1-1, sede Carabobo. Su finalidad es servir como referencia técnica para desarrolladores encargados de la evolución del sistema y administradores responsables de su operación y mantenimiento.

### 2.2 Alcance del Manual

Este documento cubre la totalidad de los componentes del sistema: desde la infraestructura de servidores y base de datos hasta la lógica de negocio implementada en cada módulo, pasando por los mecanismos de seguridad, el sistema de notificaciones en tiempo real y los procedimientos de despliegue tanto en entorno local (XAMPP) como en contenedores Docker.

### 2.3 Audiencia Objetivo

- **Desarrolladores / Programadores:** Personal técnico encargado de mantener, depurar y extender el código fuente del sistema.
- **Administradores de sistema:** Personal responsable de la configuración del servidor, la base de datos, los servicios auxiliares (WebSocket, RabbitMQ) y los respaldos.

### 2.4 Convenciones y Terminología

| Convención | Significado |
|---|---|
| `código monoespaciado` | Comando de consola, nombre de archivo, variable o fragmento de código |
| **Negrita** | Término técnico o concepto importante |
| RBAC | Control de Acceso Basado en Roles (*Role-Based Access Control*) |
| SSP | Procesamiento del Lado del Servidor (*Server-Side Processing*) |
| WS | WebSocket |
| MQ | Cola de mensajería (RabbitMQ) |

---

## 3. DESCRIPCIÓN GENERAL DEL SISTEMA

### 3.1 Propósito y Funcionalidad Principal

El sistema gestiona el ciclo de vida completo de los eventos de emergencia reportados por la ciudadanía al número 9-1-1 en la sede Carabobo. Sus funciones principales incluyen:

- Registro de fichas de emergencia por parte de los operadores.
- Visualización en tiempo real de fichas activas por el departamento de despacho.
- Asignación y seguimiento de organismos de respuesta (policía, bomberos, ambulancias).
- Generación de reportes estadísticos y exportación a PDF/XLSX.
- Gestión de usuarios, roles y permisos diferenciados.
- Sistema de notificaciones en tiempo real mediante WebSockets.
- Auditoría completa de todas las operaciones del sistema.

### 3.2 Arquitectura General

El sistema opera bajo una **arquitectura cliente-servidor de tres capas**, complementada con servicios auxiliares de mensajería y notificaciones en tiempo real:

```
┌──────────────────────────────────────────────────────────────────┐
│                       CAPA DE PRESENTACIÓN                       │
│  Navegador Web (HTML + CSS + JavaScript + AJAX + WebSocket)      │
│  Librerías: AdminLTE 4, Bootstrap 5, DataTables, ApexCharts,    │
│             Select2, SweetAlert2                                 │
└──────────────────────┬───────────────────────────────────────────┘
                       │ HTTP / WebSocket (ws://)
┌──────────────────────┴───────────────────────────────────────────┐
│                       CAPA DE LÓGICA DE NEGOCIO                  │
│  Front Controller (index.php) → Controladores → Servicios        │
│  Helpers: Validador, Notificador, Cache                          │
│  Servidor: Apache (mod_rewrite) + PHP 8.2                        │
├──────────────────────────────────────────────────────────────────┤
│  SERVICIOS AUXILIARES                                            │
│  ┌─────────────────┐  ┌──────────────┐  ┌────────────────────┐  │
│  │ Servidor WS     │  │ RabbitMQ     │  │ Worker Consumer    │  │
│  │ (Ratchet/React) │←─│ (Broker MQ)  │←─│ (consumidor_notif) │  │
│  │ Puerto 8080/8081│  │ Puerto 5672  │  │ Demonio PHP        │  │
│  └─────────────────┘  └──────────────┘  └────────────────────┘  │
└──────────────────────┬───────────────────────────────────────────┘
                       │ PDO (mysql:charset=utf8mb4)
┌──────────────────────┴───────────────────────────────────────────┐
│                       CAPA DE DATOS                              │
│  MySQL / MariaDB 10.11                                           │
│  Base de datos: ficha_ven_911 (22 tablas)                        │
│  Integridad referencial + Charset UTF-8 MB4                      │
└──────────────────────────────────────────────────────────────────┘
```

### 3.3 Módulos y Componentes Principales

| Módulo | Controlador | Descripción |
|---|---|---|
| Autenticación | `AuthControlador` | Login, logout, recuperación de contraseña por preguntas de seguridad |
| Dashboard | `HomeControlador` | Panel de inicio con estadísticas segmentadas por rol |
| Fichas de Emergencia | `FichaControlador` | CRUD de fichas, gestión de estados, administración de catálogos |
| Centro de Despacho | `DespachoControlador` | Tomar fichas, asignar organismos, seguimiento de unidades |
| Usuarios | `UsuarioControlador` | CRUD de usuarios, cambio de contraseñas, preguntas de seguridad |
| Notificaciones | `NotificacionControlador` | Buzón de alertas, emisión en tiempo real vía WebSocket |
| Auditoría | `EventoControlador` | Historial de acciones del sistema y de fichas de emergencia |
| Reportes | `ReporteControlador` | Búsqueda filtrada, exportación PDF/XLSX, acumulado mensual |
| Registro Inicial | `RegistroControlador` | Setup del primer usuario SuperAdministrador |
| Ayuda | `AyudaControlador` | Renderizado de la vista de ayuda contextual |

---

## 4. REQUISITOS DEL SISTEMA

### 4.1 Requisitos de Hardware

#### Servidor

| Especificación | Mínimo | Recomendado |
|---|---|---|
| Procesador | 2 núcleos, 2.0 GHz | 4 núcleos, 2.5 GHz |
| Memoria RAM | 2 GB | 4 GB |
| Almacenamiento | 10 GB HDD | 20 GB SSD |
| Red | Ethernet 100 Mbps (LAN) | Ethernet 1 Gbps (LAN) |

#### Cliente

| Especificación | Requisito |
|---|---|
| Navegador | Google Chrome 90+, Mozilla Firefox 88+, Microsoft Edge 90+ |
| Resolución mínima | 1366 × 768 px |
| JavaScript | Habilitado (obligatorio) |
| WebSocket | Soporte nativo del navegador (todos los navegadores modernos) |

### 4.2 Requisitos de Software

#### Stack Tecnológico del Servidor

| Componente | Versión | Notas |
|---|---|---|
| Sistema Operativo | Windows 10/11 o Linux (Ubuntu/Debian) | XAMPP en Windows, Docker en Linux |
| PHP | 8.2+ | Extensiones: `pdo_mysql`, `gd`, `zip`, `sockets`, `mbstring` |
| MySQL / MariaDB | 10.11+ | Charset `utf8mb4`, motor InnoDB |
| Apache | 2.4+ | `mod_rewrite` habilitado |
| RabbitMQ | 3.13+ | Requerido para notificaciones en tiempo real |
| Docker (opcional) | 24.0+ | Para despliegue en contenedores |
| Docker Compose | 2.20+ | Orquestación de servicios |

#### Dependencias PHP (Composer)

| Paquete | Versión | Propósito |
|---|---|---|
| `cboden/ratchet` | ^0.4.4 | Servidor WebSocket (Ratchet + ReactPHP) |
| `react/http` | ^1.11 | Servidor HTTP interno para pálpitos del WebSocket |
| `php-amqplib/php-amqplib` | ^3.7 | Cliente AMQP para RabbitMQ |
| `phpoffice/phpspreadsheet` | ^5.7 | Generación de archivos Excel XLSX |

#### Librerías Frontend (Almacenadas Localmente en `public/libs/`)

| Librería | Propósito |
|---|---|
| AdminLTE 4 | Template CSS/JS del panel de administración |
| Bootstrap 5 | Framework CSS responsive |
| Bootstrap Icons | Iconografía vectorial |
| DataTables | Tablas interactivas con SSP |
| ApexCharts | Gráficos estadísticos del dashboard |
| Select2 | Selectores avanzados con búsqueda |
| SweetAlert2 | Modales y alertas interactivas |
| FPDF | Generación de documentos PDF |
| Popper.js | Posicionamiento de tooltips y popovers |
| OverlayScrollbars | Barras de scroll personalizadas |
| Inter / Source Sans 3 | Tipografías locales |

> [!IMPORTANT]
> Todas las librerías están almacenadas localmente en `public/libs/`. Queda prohibido el uso de CDNs externos, garantizando la operación del sistema en redes intranet sin acceso a Internet.

---

## 5. INSTALACIÓN Y CONFIGURACIÓN

### 5.1 Opción A — Instalación con XAMPP (Entorno Local / Windows)

#### Paso 1: Instalar XAMPP

Descargar e instalar XAMPP con PHP 8.2+ desde https://www.apachefriends.org. Asegurar que los módulos **Apache** y **MySQL** estén activos.

#### Paso 2: Clonar el proyecto

```bash
cd C:\xampp\htdocs
git clone <URL_DEL_REPOSITORIO> ProyectoFicha
```

#### Paso 3: Instalar dependencias PHP

```bash
cd C:\xampp\htdocs\ProyectoFicha
php composer.phar install
```

#### Paso 4: Crear la base de datos

1. Acceder a phpMyAdmin (`http://localhost/phpmyadmin`).
2. Crear una nueva base de datos con nombre `ficha_ven_911` y cotejamiento `utf8mb4_general_ci`.
3. Importar el archivo `ficha_ven_911.sql` ubicado en la raíz del proyecto.

#### Paso 5: Configurar la conexión

El archivo de configuración se encuentra en `app/Config/Database.php`. Las credenciales se obtienen de variables de entorno con fallback a valores por defecto:

```php
$this->servidor   = getenv('DB_HOST') ?: "localhost";
$this->nombre_bd  = getenv('DB_NAME') ?: "ficha_ven_911";
$this->usuario    = getenv('DB_USER') ?: "root";
$this->contrasena = getenv('DB_PASS') ?: "";
```

Para entorno XAMPP local, los valores por defecto (`root` sin contraseña) son funcionales sin configuración adicional.

#### Paso 6: Verificar mod_rewrite

Asegurar que el módulo `mod_rewrite` de Apache esté habilitado en `C:\xampp\apache\conf\httpd.conf`:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

#### Paso 7: Acceder al sistema

Abrir el navegador y navegar a:

```
http://localhost/ProyectoFicha/
```

El sistema detectará que no existen usuarios y redirigirá al formulario de setup inicial (`index.php?url=setup`).

#### Paso 8: Servicios de notificaciones (opcional)

Para habilitar notificaciones en tiempo real, abrir dos terminales de PowerShell:

```powershell
# Terminal 1: Servidor WebSocket
C:\xampp\php\php.exe C:\xampp\htdocs\ProyectoFicha\app\bin\servidor_ws.php

# Terminal 2: Worker consumidor de RabbitMQ
C:\xampp\php\php.exe C:\xampp\htdocs\ProyectoFicha\app\bin\consumidor_notif.php
```

> [!NOTE]
> RabbitMQ debe estar instalado y corriendo en el puerto 5672. Si no está disponible, el sistema funciona correctamente sin tiempo real; las notificaciones se persisten en BD y se visualizan al recargar la página.

### 5.2 Opción B — Despliegue con Docker

#### Paso 1: Construir e iniciar los contenedores

```bash
cd /ruta/al/ProyectoFicha
docker compose up -d --build
```

Esto levanta automáticamente 5 servicios:

| Servicio | Contenedor | Puerto |
|---|---|---|
| Aplicación web (Apache + PHP) | `ven911_web` | 80 |
| Base de datos (MariaDB) | `ven911_mysql` | — (interno) |
| Servidor WebSocket (Ratchet) | `ven911_sockets` | 8080 |
| Broker de mensajería (RabbitMQ) | `ven911_rabbit` | 15672 (admin) |
| Worker consumidor | `ven911_consumer` | — (interno) |
| phpMyAdmin | `ven911_phpmyadmin` | 8081 |

#### Paso 2: Importar la base de datos

```bash
docker exec -i ven911_mysql mysql -u ven911_user -pven911_secure_pass ficha_ven_911 < ficha_ven_911.sql
```

#### Paso 3: Acceder al sistema

```
http://localhost/
```

### 5.3 Estructura de Carpetas del Proyecto

```
ProyectoFicha/
├── index.php                  # Front Controller centralizado (enrutamiento + RBAC)
├── composer.json              # Dependencias PHP
├── composer.phar              # Instalador de Composer
├── ficha_ven_911.sql          # Dump completo de la base de datos
├── Dockerfile                 # Imagen Docker (PHP 8.2 + Apache)
├── docker-compose.yml         # Orquestación de 5 servicios Docker
│
├── app/                       # Núcleo de la aplicación (MVC)
│   ├── Config/
│   │   └── Database.php       # Conexión PDO a MySQL con variables de entorno
│   ├── Helpers/
│   │   ├── Validador.php      # Validaciones centralizadas del servidor
│   │   ├── Notificador.php    # Emisión de notificaciones (BD + RabbitMQ)
│   │   └── Cache.php          # Caché adaptativa (Redis → Memcached → File)
│   ├── Servicios/
│   │   ├── FichaServicio.php      # Lógica de negocio de fichas
│   │   ├── DespachoServicio.php   # Lógica de negocio de despachos
│   │   ├── UsuarioServicio.php    # Lógica de negocio de usuarios
│   │   └── ReporteServicio.php    # Generación de PDF y Excel
│   ├── controladores/
│   │   ├── AuthControlador.php
│   │   ├── HomeControlador.php
│   │   ├── FichaControlador.php
│   │   ├── DespachoControlador.php
│   │   ├── UsuarioControlador.php
│   │   ├── NotificacionControlador.php
│   │   ├── EventoControlador.php
│   │   ├── ReporteControlador.php
│   │   ├── RegistroControlador.php
│   │   └── AyudaControlador.php
│   ├── modelos/
│   │   ├── FichaModelo.php
│   │   ├── DespachoModelo.php
│   │   ├── UsuarioModelo.php
│   │   ├── NotificacionModelo.php
│   │   ├── EventoModelo.php
│   │   ├── HomeModelo.php
│   │   ├── ReporteModelo.php
│   │   └── RegistroModelo.php
│   ├── vista/
│   │   ├── login.php              # Vista de autenticación
│   │   ├── setup.php              # Vista de configuración inicial
│   │   ├── partials/              # Componentes reutilizables (header, sidebar, footer)
│   │   ├── home/                  # Dashboard
│   │   ├── fichas/                # Módulo de fichas (index + componentes/)
│   │   ├── despachador/           # Centro de despacho
│   │   ├── usuarios/             # Gestión de usuarios
│   │   ├── notificaciones/       # Buzón de notificaciones
│   │   ├── eventos/              # Auditoría e historial
│   │   ├── reportes/             # Módulo de reportes
│   │   └── ayuda/                # Ayuda contextual
│   └── bin/
│       ├── servidor_ws.php        # Demonio WebSocket (puertos 8080 y 8081)
│       └── consumidor_notif.php   # Worker que consume cola RabbitMQ
│
├── public/                    # Recursos estáticos accesibles públicamente
│   ├── css/                   # Hojas de estilo (AdminLTE + módulos personalizados)
│   ├── js/                    # JavaScript modular por módulo
│   │   ├── adminlte.js
│   │   ├── comun/             # JS compartido (datatables_config, fichas_comun, notificaciones)
│   │   ├── auth/
│   │   ├── home/
│   │   ├── fichas/
│   │   ├── despacho/
│   │   ├── usuarios/
│   │   ├── notificaciones/
│   │   ├── eventos/
│   │   └── reportes/
│   ├── libs/                  # Librerías de terceros (offline)
│   │   ├── bootstrap/
│   │   ├── bootstrap-icons/
│   │   ├── datatables/
│   │   ├── apexcharts/
│   │   ├── select2/
│   │   ├── sweetalert2/
│   │   ├── fpdf/
│   │   ├── popperjs/
│   │   ├── overlayscrollbars/
│   │   ├── inter/
│   │   └── source-sans-3/
│   └── assets/                # Imágenes, logos e íconos
│
├── storage/                   # Almacenamiento de datos generados
│   └── cache/                 # Archivos de caché del sistema
│
└── vendor/                    # Dependencias de Composer (autoload)
```

---

## 6. ARQUITECTURA DE BASE DE DATOS

### 6.1 Descripción del Modelo Relacional

La base de datos `ficha_ven_911` consta de **22 tablas** organizadas en cuatro dominios funcionales:

1. **Gestión de Emergencias:** `fichas_emergencia`, `solicitantes`, `despachos_organismos`, `eventos_fichas`
2. **Catálogos Geográficos y Operativos:** `municipios`, `parroquias`, `comunas`, `sectores`, `cuadrantes_paz`, `organismos`, `tipos_emergencia`, `casos`, `motivos_cierre`
3. **Seguridad y Usuarios:** `usuarios`, `roles`, `modulos`, `permisos`, `rol_permiso`, `preguntas_seguridad`, `configuracion_sistema`
4. **Sistema:** `eventos_sistema`, `notificaciones`

### 6.2 Tablas Principales

#### fichas_emergencia

Tabla central del sistema. Almacena cada evento de emergencia reportado.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | INT UNSIGNED PK | Identificador único autoincremental |
| `parroquia_id` | INT UNSIGNED FK | Parroquia donde ocurre el evento |
| `comuna_id` | INT UNSIGNED FK NULL | Comuna (opcional) |
| `sector_id` | INT UNSIGNED FK NULL | Sector (opcional) |
| `direccion_exacta` | TEXT | Dirección detallada del incidente |
| `caso_id` | INT UNSIGNED FK | Caso específico de emergencia |
| `descripcion_caso` | TEXT | Descripción libre del evento |
| `solicitante_id` | INT UNSIGNED FK | Persona que reporta la emergencia |
| `id_user` | INT UNSIGNED FK NULL | Usuario que CREÓ la ficha (inmutable) |
| `id_owner` | INT UNSIGNED FK NULL | Último usuario que MODIFICÓ la ficha |
| `fecha_creacion` | TIMESTAMP | Fecha y hora de creación |
| `hora_cierre` | DATETIME NULL | Fecha y hora de cierre |
| `motivo_cierre` | VARCHAR(500) NULL | Descripción del motivo de cierre |
| `tipo_motivo_cierre` | VARCHAR(150) NULL | Tipo de motivo de cierre del catálogo |
| `estado_ficha` | ENUM | `Pendiente`, `En Proceso`, `Atendido`, `Cerrado`, `Finalizado` |
| `fecha_actualizacion` | TIMESTAMP | Última modificación (auto-actualizable) |

#### despachos_organismos

Registra cada organismo asignado a una ficha de emergencia.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | INT UNSIGNED PK | Identificador del despacho |
| `ficha_id` | INT UNSIGNED FK | Ficha de emergencia asociada |
| `organismo_id` | INT UNSIGNED FK | Organismo despachado |
| `cuadrante_id` | INT UNSIGNED FK NULL | Cuadrante de paz asignado |
| `unidad_designada` | VARCHAR(100) | Nombre/código de la unidad enviada |
| `mando_acargo` | VARCHAR(100) | Persona al mando de la unidad |
| `persona_atiende` | VARCHAR(100) NULL | Persona que atiende en sitio |
| `hora_despacho` | TIMESTAMP | Hora de asignación del despacho |
| `estatus_despacho` | ENUM | `Asignado`, `En Camino`, `En Sitio`, `Liberado`, `Cancelado` |
| `despachador_id` | INT UNSIGNED FK NULL | Despachador que realizó la asignación |
| `motivo_cancelacion` | VARCHAR(500) NULL | Motivo de cancelación del despacho |
| `tipo_motivo_cancelacion` | VARCHAR(100) NULL | Tipo de motivo del catálogo |

#### usuarios

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | INT UNSIGNED PK | Identificador del usuario |
| `usuario` | VARCHAR(30) | Nombre de usuario (alfanumérico, único) |
| `password` | VARCHAR(255) | Hash de contraseña (bcrypt con PASSWORD_DEFAULT) |
| `nombre_completo` | VARCHAR(150) | Nombre completo del usuario |
| `cedula` | VARCHAR(12) NULL | Cédula de identidad |
| `rol_id` | INT UNSIGNED FK | Rol asignado |
| `estado` | ENUM | `activo`, `inactivo` (soft delete) |
| `pregunta_1_id` | INT UNSIGNED FK NULL | Primera pregunta de seguridad |
| `pregunta_2_id` | INT UNSIGNED FK NULL | Segunda pregunta de seguridad |
| `respuesta_1` | VARCHAR(255) NULL | Hash de la respuesta 1 |
| `respuesta_2` | VARCHAR(255) NULL | Hash de la respuesta 2 |

#### eventos_sistema

Registro de auditoría de acciones administrativas.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT UNSIGNED PK | Identificador del evento |
| `usuario_id` | INT UNSIGNED FK NULL | Usuario que realizó la acción |
| `tipo_accion` | ENUM | `INSERT`, `UPDATE`, `DELETE`, `LOGIN`, `LOGOUT`, `CAMBIO_ESTADO` |
| `tabla_afectada` | VARCHAR(50) | Nombre de la tabla modificada |
| `registro_id` | INT UNSIGNED NULL | ID del registro afectado |
| `valor_anterior` | TEXT NULL | Estado previo en formato JSON |
| `valor_nuevo` | TEXT NULL | Estado nuevo en formato JSON |
| `descripcion` | TEXT NULL | Descripción legible del evento |
| `fecha` | TIMESTAMP | Fecha y hora del evento |

#### eventos_fichas

Registro de auditoría específico de las fichas de emergencia.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT UNSIGNED PK | Identificador del evento |
| `ficha_id` | INT UNSIGNED FK | Ficha que originó el evento |
| `usuario_id` | INT UNSIGNED FK NULL | Operador que realizó la acción |
| `tipo_evento` | ENUM | `CREACION`, `MODIFICACION`, `CAMBIO_ESTADO`, `PLAN_ACCION`, `DESPACHO`, `CIERRE` |
| `estado_anterior` | VARCHAR(50) NULL | Estado previo de la ficha |
| `estado_nuevo` | VARCHAR(50) NULL | Estado nuevo de la ficha |
| `valor_anterior` | TEXT NULL | Snapshot JSON del estado previo |
| `valor_nuevo` | TEXT NULL | Snapshot JSON del estado nuevo |
| `descripcion` | TEXT NULL | Nota legible del evento |
| `fecha` | TIMESTAMP | Fecha y hora del evento |

#### notificaciones

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT UNSIGNED PK | Identificador de la notificación |
| `usuario_recibe_id` | INT UNSIGNED FK | Destinatario |
| `ficha_id` | INT UNSIGNED FK NULL | Ficha que originó la notificación |
| `tipo` | VARCHAR(50) | `info`, `alerta`, `cambio_estado` |
| `titulo` | VARCHAR(150) | Título de la notificación |
| `mensaje` | VARCHAR(255) | Contenido del mensaje |
| `leido` | TINYINT(1) | 0 = no leído, 1 = leído |
| `fecha_creacion` | TIMESTAMP | Fecha de creación |

#### Tablas de Catálogos Geográficos

| Tabla | Campos Principales | Relación Jerárquica |
|---|---|---|
| `municipios` | `id`, `nombre_municipio`, `Descripcion`, `estado` | Nivel superior |
| `parroquias` | `id`, `municipio_id` (FK), `nombre_parroquia`, `estado` | → municipios |
| `comunas` | `id`, `parroquia_id` (FK), `nombre_comuna`, `estado` | → parroquias |
| `sectores` | `id`, `comuna_id` (FK), `nombre_sector`, `estado` | → comunas |
| `cuadrantes_paz` | `id`, `sector_id` (FK), `organismo_id` (FK), `nombre_cuadrante`, `estado` | → sectores, → organismos |

#### Tablas de Seguridad (RBAC)

| Tabla | Campos | Descripción |
|---|---|---|
| `roles` | `id`, `nombre` | Roles del sistema (Administrador, Operador, Despachador, Jefatura) |
| `modulos` | `id`, `clave`, `descripcion` | Módulos funcionales registrados |
| `permisos` | `id`, `modulo_id` (FK), `clave`, `descripcion` | Acciones por módulo (`ver`, `crear`, `editar`, `cambiar_estado`, `gestionar`) |
| `rol_permiso` | `rol_id` (FK), `permiso_id` (FK) | Tabla pivote: asocia permisos a roles |
| `preguntas_seguridad` | `id`, `pregunta` | Catálogo de preguntas para recuperación |
| `configuracion_sistema` | `id`, `llave_activacion` | Código de fábrica para operaciones críticas |

#### Otras Tablas

| Tabla | Descripción |
|---|---|
| `tipos_emergencia` | Categorías de emergencia (Seguridad, Médica, Incendio, etc.) |
| `casos` | Casos específicos por tipo de emergencia |
| `organismos` | Organismos de respuesta (CICPC, Bomberos, SAMU, etc.) |
| `solicitantes` | Personas que reportan emergencias |
| `motivos_cierre` | Catálogo de motivos de cierre (contexto: ficha u organismo) |

### 6.3 Relaciones Principales

```
usuarios ──(N:1)──→ roles
roles ──(N:M)──→ permisos ──(N:1)──→ modulos     (vía rol_permiso)
usuarios ──(1:1)──→ preguntas_seguridad            (pregunta_1, pregunta_2)

fichas_emergencia ──(N:1)──→ parroquias ──(N:1)──→ municipios
fichas_emergencia ──(N:1)──→ comunas ──(N:1)──→ parroquias
fichas_emergencia ──(N:1)──→ sectores ──(N:1)──→ comunas
fichas_emergencia ──(N:1)──→ casos ──(N:1)──→ tipos_emergencia
fichas_emergencia ──(N:1)──→ solicitantes
fichas_emergencia ──(N:1)──→ usuarios              (id_user, id_owner)

despachos_organismos ──(N:1)──→ fichas_emergencia
despachos_organismos ──(N:1)──→ organismos
despachos_organismos ──(N:1)──→ cuadrantes_paz
despachos_organismos ──(N:1)──→ usuarios             (despachador_id)

eventos_sistema ──(N:1)──→ usuarios
eventos_fichas ──(N:1)──→ fichas_emergencia
eventos_fichas ──(N:1)──→ usuarios
notificaciones ──(N:1)──→ usuarios
notificaciones ──(N:1)──→ fichas_emergencia
```

---

## 7. DESCRIPCIÓN DE MÓDULOS Y FUNCIONALIDADES

### 7.1 Módulo de Autenticación (`AuthControlador`)

**Propósito:** Gestionar el acceso al sistema, la validación de credenciales y la recuperación de contraseñas mediante preguntas de seguridad.

**Archivos involucrados:**

| Capa | Archivo |
|---|---|
| Controlador | `app/controladores/AuthControlador.php` |
| Modelo | `app/modelos/UsuarioModelo.php`, `app/modelos/RegistroModelo.php` |
| Vista | `app/vista/login.php` |
| JavaScript | `public/js/auth/` |
| CSS | `public/css/login.css` |

**Métodos principales:**

| Método | Descripción |
|---|---|
| `index()` | Renderiza la pantalla de login. Detecta si el sistema requiere setup inicial. |
| `authenticate()` | Valida credenciales vía POST, verifica hash con `password_verify()`, regenera ID de sesión, carga permisos RBAC y registra auditoría LOGIN. |
| `logout()` | Destruye la sesión de forma segura y registra auditoría LOGOUT. |
| `recuperarPaso1()` | Valida que el usuario exista y sea SuperAdministrador; retorna sus preguntas de seguridad. |
| `recuperarPaso2()` | Valida las respuestas de seguridad con rate limiting (máximo 3 intentos). |
| `recuperarPaso3()` | Cambia la contraseña tras validación previa en sesión; hashea con `PASSWORD_DEFAULT`. |
| `restablecerPreguntasConLlave()` | Valida el Código de Fábrica (llave de activación) y permite actualizar preguntas y respuestas secretas. |

**Flujo de autenticación:**

```
Cliente (POST) → authenticate()
  → Validador::validarUsuario()
  → Validador::validarContrasena()
  → UsuarioModelo::obtenerUsuarioPorNombre()
  → password_verify()
  → session_regenerate_id(true)
  → Carga $_SESSION[permisos] vía obtenerPermisosDeRol()
  → EventoModelo::registrarEvento(LOGIN)
  → JSON { success: true }
```

---

### 7.2 Módulo de Dashboard (`HomeControlador`)

**Propósito:** Consolidar estadísticas del sistema segmentadas por rol para el panel de inicio.

**Archivos involucrados:**

| Capa | Archivo |
|---|---|
| Controlador | `app/controladores/HomeControlador.php` |
| Modelo | `app/modelos/HomeModelo.php` |
| Vista | `app/vista/home/index.php` + componentes |
| JavaScript | `public/js/home/` |
| CSS | `public/css/home.css` |

**Métodos principales:**

| Método | Descripción |
|---|---|
| `index()` | Carga estadísticas por rol (Admin: resumen global, Operador: fichas propias, Despachador: fichas tomadas, Jefatura: métricas ejecutivas). |
| `obtenerStatsAjax()` | Endpoint AJAX para actualización de estadísticas sin recarga. |

---

### 7.3 Módulo de Fichas de Emergencia (`FichaControlador`)

**Propósito:** Gestionar el ciclo de vida completo de las fichas de emergencia y la administración de catálogos del sistema.

**Archivos involucrados:**

| Capa | Archivo |
|---|---|
| Controlador | `app/controladores/FichaControlador.php` |
| Servicio | `app/Servicios/FichaServicio.php` |
| Modelo | `app/modelos/FichaModelo.php` |
| Vista | `app/vista/fichas/index.php` + `componentes/` |
| JavaScript | `public/js/fichas/`, `public/js/comun/fichas_comun.js` |
| CSS | `public/css/fichas.css` |

**Métodos principales:**

| Método | Tipo | Descripción |
|---|---|---|
| `index()` | GET | Renderiza la interfaz principal con catálogos precargados. |
| `obtenerDatos()` | POST | DataTables SSP con filtrado por estado y restricción por rol. |
| `guardar()` | POST | Crea una nueva ficha delegando a `FichaServicio::crearFicha()`. |
| `actualizar()` | POST | Actualiza una ficha existente. Bloquea fichas en estado terminal. |
| `cambiarEstado()` | POST | Transiciona el estado: Pendiente → En Proceso → Atendido / Cancelada. |
| `detalle()` | GET | Retorna JSON completo de una ficha para visualización en modal. |
| `obtenerParroquiasPorMunicipio()` | GET | Cascada AJAX para selectores geográficos. |
| `obtenerCasosPorTipo()` | GET | Cascada AJAX para casos por tipo de emergencia. |
| `guardarCatalogo()` | POST | CRUD unificado para 9 catálogos (tipos, casos, municipios, parroquias, comunas, sectores, cuadrantes, organismos, motivos de cierre). |
| `obtenerCatalogo()` | GET | Retorna datos de cualquier catálogo para tablas administrativas. |

---

### 7.4 Módulo de Centro de Despacho (`DespachoControlador`)

**Propósito:** Gestionar la asignación de organismos de respuesta a las fichas de emergencia activas.

**Archivos involucrados:**

| Capa | Archivo |
|---|---|
| Controlador | `app/controladores/DespachoControlador.php` |
| Servicio | `app/Servicios/DespachoServicio.php` |
| Modelo | `app/modelos/DespachoModelo.php` |
| Vista | `app/vista/despachador/index.php` + `componentes/` |
| JavaScript | `public/js/despacho/` |
| CSS | `public/css/despacho.css` |

**Métodos principales:**

| Método | Descripción |
|---|---|
| `index()` | Vista principal con tabla de fichas activas (global para todos los despachadores). |
| `obtenerDatos()` | SSP de fichas Pendiente + En Proceso (vista global). |
| `obtenerDatosPropios()` | SSP filtrado por `id_owner = usuario actual` (fichas tomadas). |
| `tomarFicha()` | El despachador asume una ficha; actualiza `id_owner` y estado a "En Proceso". |
| `detalleFicha()` | JSON de ficha + despachos asignados para el modal de gestión. |
| `guardar()` | Asigna un nuevo organismo a una ficha en proceso. |
| `cambiarEstado()` | Avanza estatus del despacho: Asignado → En Camino → En Sitio → Liberado. |
| `cancelarDespacho()` | Cancela un organismo activo con motivo obligatorio del catálogo. |
| `editarFicha()` | Actualiza campos operacionales (descripción, dirección, teléfonos) desde despacho. |
| `cambiarEstadoFicha()` | Transición de estado de ficha desde despacho (con motivo obligatorio para cancelación). |

---

### 7.5 Módulo de Usuarios (`UsuarioControlador`)

**Propósito:** Gestionar el ciclo de vida de los usuarios, sus credenciales y preguntas de seguridad.

**Archivos involucrados:**

| Capa | Archivo |
|---|---|
| Controlador | `app/controladores/UsuarioControlador.php` |
| Servicio | `app/Servicios/UsuarioServicio.php` |
| Modelo | `app/modelos/UsuarioModelo.php`, `app/modelos/RegistroModelo.php` |
| Vista | `app/vista/usuarios/index.php` + `componentes/` |
| JavaScript | `public/js/usuarios/` |
| CSS | `public/css/usuarios.css` |

**Métodos principales:**

| Método | Descripción |
|---|---|
| `index()` | Vista con pestañas dinámicas por rol. |
| `obtenerDatos()` | SSP para listado general de usuarios. |
| `obtenerDatosPorRol()` | SSP filtrado por rol específico. |
| `guardar()` | Crea un nuevo usuario con validación de formato y unicidad. |
| `actualizar()` | Modifica datos de un usuario existente. |
| `actualizarContrasena()` | Cambia contraseña. Requiere respuestas de seguridad si el afectado es SuperAdmin. |
| `alternarEstado()` | Soft delete (activo/inactivo). Bloquea autodesactivación y protección del SuperAdmin. |
| `actualizarPreguntasSeguridad()` | Actualiza preguntas de seguridad previa validación del Código de Fábrica. |

---

### 7.6 Módulo de Notificaciones (`NotificacionControlador`)

**Propósito:** Gestionar el buzón de alertas del sistema y la emisión en tiempo real vía WebSocket.

**Archivos involucrados:**

| Capa | Archivo |
|---|---|
| Controlador | `app/controladores/NotificacionControlador.php` |
| Helper | `app/Helpers/Notificador.php` |
| Modelo | `app/modelos/NotificacionModelo.php` |
| Demonio WS | `app/bin/servidor_ws.php` |
| Worker | `app/bin/consumidor_notif.php` |
| Vista | `app/vista/notificaciones/index.php` + componentes |
| JavaScript | `public/js/comun/notificaciones.js`, `public/js/notificaciones/` |
| CSS | `public/css/notificaciones.css` |

**Arquitectura de notificaciones:**

```
Evento en el sistema (crear ficha, cambiar estado, etc.)
  ↓
Notificador::enviarPorRol() / enviarAUsuario()
  ├── 1. Persistencia síncrona en BD (tabla notificaciones)
  └── 2. Best-effort: encolar payload en RabbitMQ
                ↓
        Worker (consumidor_notif.php)
                ↓
        HTTP POST al puerto 8081 (Ratchet interno)
                ↓
        servidor_ws.php → enruta al WebSocket del usuario
                ↓
        Navegador del usuario recibe notificación en tiempo real
```

**Métodos principales del controlador:**

| Método | Descripción |
|---|---|
| `obtenerPaginado()` | DataTables SSP del buzón completo. |
| `obtenerPendientes()` | Retorna notificaciones no leídas (carga inicial). |
| `marcarLeida()` | Marca una notificación como leída. |
| `marcarTodas()` | Marca todas las notificaciones como leídas. |
| `estadoServidor()` | Diagnóstico del estado de WebSocket, RabbitMQ y Worker (solo Admin). |
| `iniciarServidor()` | Levanta los demonios WS y Worker desde el panel (solo Admin, solo XAMPP). |

---

### 7.7 Módulo de Auditoría (`EventoControlador`)

**Propósito:** Visualizar el historial de acciones administrativas y el historial de fichas de emergencia.

**Archivos involucrados:**

| Capa | Archivo |
|---|---|
| Controlador | `app/controladores/EventoControlador.php` |
| Modelo | `app/modelos/EventoModelo.php` |
| Vista | `app/vista/eventos/index.php` + componentes |
| JavaScript | `public/js/eventos/` |
| CSS | `public/css/eventos.css` |

**Métodos principales:**

| Método | Descripción |
|---|---|
| `index()` | Vista con pestañas: Logs del Sistema / Historial de Fichas. Jefatura solo ve fichas. |
| `obtenerDatos()` | SSP de auditoría administrativa (bloqueado para Jefatura). |
| `obtenerDatosFichas()` | SSP de auditoría de fichas de emergencia. |

---

### 7.8 Módulo de Reportes (`ReporteControlador`)

**Propósito:** Generar reportes estadísticos con filtros avanzados y exportación a PDF y Excel.

**Archivos involucrados:**

| Capa | Archivo |
|---|---|
| Controlador | `app/controladores/ReporteControlador.php` |
| Servicio | `app/Servicios/ReporteServicio.php` |
| Modelo | `app/modelos/ReporteModelo.php` |
| Vista | `app/vista/reportes/index.php` + componentes |
| JavaScript | `public/js/reportes/` |
| CSS | `public/css/reportes.css` |

**Métodos principales:**

| Método | Descripción |
|---|---|
| `index()` | Vista con filtros por fecha, municipio, tipo de emergencia, caso, operador y estado. |
| `buscar()` | Retorna fichas filtradas y resumen estadístico vía AJAX. |
| `exportarSincrono()` | Genera y descarga el reporte operativo en PDF (FPDF) o Excel (PhpSpreadsheet). |
| `exportarAcumuladoMensualExcel()` | Genera el acumulado mensual de incidencias con matriz de conteo día×caso. |

---

## 8. SEGURIDAD DEL SISTEMA

### 8.1 Gestión de Sesiones y Autenticación

- **Cookies seguras:** Configuradas con `httponly: true`, `samesite: Strict`, y `secure: true` en producción (HTTPS).
- **Regeneración de ID:** Se ejecuta `session_regenerate_id(true)` después de cada login exitoso para prevenir Session Fixation.
- **Cabeceras de hardening:** `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy: strict-origin-when-cross-origin`.
- **Auditoría de accesos:** Cada LOGIN y LOGOUT se registra en la tabla `eventos_sistema`.

### 8.2 Control de Acceso Basado en Roles (RBAC)

El sistema implementa RBAC en tres niveles:

1. **Middleware en `index.php`:** Verifica permisos antes de despachar la petición al controlador. Mapa de rutas protegidas:

```php
$reglasRutas = [
    'usuario'      => ['usuarios',  'ver'],
    'evento'       => ['historial', 'ver'],
    'ficha'        => ['fichas',    'ver'],
    'despacho'     => ['despachos', 'ver'],
    'reporte'      => ['reportes',  'ver'],
    'notificacion' => ['fichas',    'ver'],
];
```

2. **Nivel de controlador:** Cada método sensible verifica `tienePerm($modulo, $accion)` antes de ejecutar lógica.
3. **Nivel de vista:** Los componentes modulares se cargan condicionalmente con `tienePerm()` y los permisos se exportan a `window` para el control en JavaScript.

**Roles del sistema:**

| ID | Rol | Privilegio |
|---|---|---|
| 1 | Administrador | Bypass total. Acceso a todos los módulos y acciones. |
| 2 | Operador | Fichas: crear, editar, ver. Sin acceso a despacho ni usuarios. |
| 3 | Despachador | Fichas: ver, cambiar estado. Despachos: CRUD completo. |
| 4 | Jefatura | Reportes: ver. Fichas/despachos: solo lectura. Sin auditoría de sistema. |

### 8.3 Protección contra SQL Injection

Todas las consultas a la base de datos utilizan **sentencias preparadas con PDO** (`$stmt->prepare()` con `bindParam()` o `execute([...])`). PDO está configurado con `ATTR_EMULATE_PREPARES = false` para garantizar la preparación real en el motor MySQL.

### 8.4 Protección contra XSS

- **Servidor:** Los datos se almacenan sin codificar (texto plano) para preservar la integridad.
- **Cliente:** Todo dato renderizado en tablas DataTables y vistas dinámicas se pasa por la función global `window.escapeHTML()` definida en `public/js/comun/datatables_config.js`, que neutraliza las etiquetas HTML inyectadas.

### 8.5 Manejo de Contraseñas

- **Hasheo:** Todas las contraseñas se almacenan con `password_hash($password, PASSWORD_DEFAULT)` (bcrypt adaptativo).
- **Verificación:** Se utiliza `password_verify()` para la comparación segura contra el hash almacenado.
- **Preguntas de seguridad:** Las respuestas se normalizan a minúsculas y se hashean con `password_hash()` antes de ser almacenadas. Nunca se guardan en texto plano.
- **Política de fortaleza:** Mínimo 8 caracteres, al menos una mayúscula y un número (validado por `Validador::validarContrasena()`).

### 8.6 Validación Centralizada en el Servidor

Todas las validaciones de integridad se delegan al helper estático `App\Helpers\Validador`. Los formularios HTML no incluyen atributos restrictivos (`minlength`, `maxlength`, `required`); el control recae absolutamente en el backend.

**Métodos disponibles en Validador:**

| Método | Validación |
|---|---|
| `validarUsuario()` | 7-32 caracteres alfanuméricos |
| `validarContrasena()` | 8-128 caracteres, al menos 1 mayúscula y 1 número |
| `validarNombreCompleto()` | No vacío, máximo 128 caracteres |
| `validarCedula()` | 6-8 dígitos numéricos |
| `validarRespuestaSeguridad()` | Letras, números, espacios; máximo 128 caracteres |
| `validarTelefono()` | Formato venezolano (04XX-XXXXXXX) |
| `validarTextoLibre()` | Longitud configurable por campo |
| `validarId()` | Entero positivo mayor a cero |
| `validarNombreCatalogo()` | 1-100 caracteres, letras, números, espacios y signos básicos |
| `validarNombreAlfabetico()` | Solo letras y espacios |

### 8.7 Prevención de Doble Envío

Todo formulario AJAX inhabilita el botón de acción (`disabled = true`) y muestra un spinner inmediatamente después del primer clic. El botón se restaura en el bloque `complete`/`always` de la petición.

### 8.8 Rate Limiting

El sistema implementa rate limiting basado en sesión para operaciones críticas:
- **Preguntas de seguridad:** Máximo 3 intentos fallidos; después el proceso se bloquea.
- **Código de Fábrica:** Máximo 3 intentos fallidos para la llave de activación.

### 8.9 Protección del SuperAdministrador

- El rol SuperAdministrador (ID 1) no puede ser desactivado ni modificado por ningún usuario, incluyéndose a sí mismo.
- Las operaciones sobre sus credenciales requieren la validación del Código de Fábrica almacenado en la tabla `configuracion_sistema`.

---

## 9. MANTENIMIENTO Y ADMINISTRACIÓN

### 9.1 Respaldo de la Base de Datos

#### Respaldo manual (línea de comandos)

```bash
# XAMPP / Windows
C:\xampp\mysql\bin\mysqldump -u root ficha_ven_911 > backup_ven911_%date:~-4,4%%date:~-7,2%%date:~-10,2%.sql

# Docker
docker exec ven911_mysql mysqldump -u ven911_user -pven911_secure_pass ficha_ven_911 > backup_ven911_$(date +%Y%m%d).sql
```

#### Restauración

```bash
# XAMPP / Windows
C:\xampp\mysql\bin\mysql -u root ficha_ven_911 < backup_ven911_20260605.sql

# Docker
docker exec -i ven911_mysql mysql -u ven911_user -pven911_secure_pass ficha_ven_911 < backup_ven911_20260605.sql
```

### 9.2 Gestión de Logs

| Log | Ubicación | Descripción |
|---|---|---|
| Errores PHP | `C:\xampp\php\logs\php_error_log` (XAMPP) o `docker logs ven911_web` | Errores de la aplicación PHP |
| Apache | `C:\xampp\apache\logs\error.log` | Errores del servidor web |
| WebSocket | `app/bin/servidor_ws.log` | Actividad del demonio WebSocket |
| Worker | `app/bin/worker.log` | Actividad del consumidor RabbitMQ |
| Auditoría del sistema | Tabla `eventos_sistema` | Acciones administrativas |
| Auditoría de fichas | Tabla `eventos_fichas` | Operaciones sobre fichas |

### 9.3 Procedimientos de Actualización

1. Realizar un respaldo completo de la base de datos.
2. Extraer el código fuente actualizado en el directorio del proyecto.
3. Ejecutar `php composer.phar install` si se actualizaron dependencias.
4. Importar las migraciones SQL adicionales (si las hay).
5. Limpiar la caché del sistema: eliminar archivos en `storage/cache/`.
6. Verificar el funcionamiento de los módulos principales.

### 9.4 Tareas de Mantenimiento Preventivo

| Tarea | Frecuencia | Detalle |
|---|---|---|
| Respaldo de BD | Diaria | Exportar dump SQL y almacenar en ubicación redundante |
| Limpieza de caché | Semanal | Eliminar archivos expirados en `storage/cache/` |
| Revisión de logs | Semanal | Verificar errores recurrentes en logs de PHP y Apache |
| Verificación de servicios WS | Diaria | Confirmar estado de WebSocket, RabbitMQ y Worker desde el panel de Administrador |
| Rotación de logs | Mensual | Archivar y rotar los archivos de log del servidor |
| Actualización de dependencias | Trimestral | Ejecutar `composer update` y verificar compatibilidad |

---

## 10. MANEJO DE ERRORES Y SOLUCIÓN DE PROBLEMAS

| Problema | Causa Probable | Solución |
|---|---|---|
| Página en blanco al acceder al sistema | `mod_rewrite` deshabilitado en Apache | Habilitar `LoadModule rewrite_module` en `httpd.conf` y reiniciar Apache |
| Error "Conexión a la base de datos" | Credenciales incorrectas o MySQL detenido | Verificar variables de entorno `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` en `Database.php`. Confirmar que MySQL esté activo |
| 404 al acceder a rutas del sistema | `.htaccess` no se procesa | Verificar `AllowOverride All` en la configuración de Apache para el directorio del proyecto |
| Notificaciones no llegan en tiempo real | WebSocket o RabbitMQ detenidos | Desde el panel de Administrador, verificar estado y reiniciar servicios. En XAMPP, ejecutar manualmente los scripts del directorio `app/bin/` |
| DataTables carga vacía | Error en la consulta SQL del modelo | Revisar `php_error_log` para errores de PDO. Verificar que la tabla consultada exista y tenga datos |
| Error "Call to undefined function" | Extensión PHP faltante | Verificar que las extensiones `pdo_mysql`, `gd`, `zip`, `sockets`, `mbstring` estén habilitadas en `php.ini` |
| Lentitud en tablas con muchos registros | SSP no configurado correctamente | Verificar que la tabla DataTables use `serverSide: true` y que el modelo emplee `LIMIT`/`OFFSET` |
| Login funciona pero redirige al login | Problema con sesiones | Verificar que `session.save_path` sea escribible. En Docker, verificar permisos del directorio temporal |
| PDF/Excel no se descarga | `ob_start()` interfiere con headers | El sistema ejecuta `ob_end_clean()` antes de enviar headers binarios. Si persiste, verificar que no haya output previo |
| Error 500 al crear ficha | Integridad referencial violada | Verificar que los IDs de catálogos (parroquia, caso, tipo) existan y estén activos en la BD |
| Worker se detiene inesperadamente | RabbitMQ se reinició | El worker debe reiniciarse manualmente (XAMPP) o automáticamente (Docker con `restart: always`) |
| Caché devuelve datos obsoletos | TTL no expirado | Ejecutar `Cache::limpiarTodo()` o eliminar archivos en `storage/cache/` |

---

## 11. GLOSARIO DE TÉRMINOS TÉCNICOS

- **AdminLTE:** Template de código abierto para paneles de administración web basado en Bootstrap.
- **AJAX:** Técnica de comunicación asincrónica entre cliente y servidor que permite actualizar partes de la página sin recarga completa.
- **Apache:** Servidor web HTTP de código abierto utilizado para servir la aplicación PHP.
- **bcrypt:** Algoritmo de hasheo de contraseñas adaptativo, utilizado por `password_hash()` con `PASSWORD_DEFAULT`.
- **Broker de mensajería:** Software intermediario (RabbitMQ) que gestiona colas de mensajes entre productores y consumidores.
- **DataTables:** Librería JavaScript que proporciona paginación, búsqueda y ordenamiento avanzado para tablas HTML.
- **Docker:** Plataforma de contenedorización que empaqueta la aplicación y sus dependencias en entornos aislados y reproducibles.
- **Front Controller:** Patrón de diseño donde un único punto de entrada (`index.php`) procesa todas las peticiones del sistema.
- **Integridad referencial:** Restricción de la base de datos que garantiza que las claves foráneas referencien registros existentes.
- **MariaDB:** Sistema de gestión de bases de datos relacional, fork de MySQL, utilizado en el entorno Docker.
- **Middleware:** Capa de software intermedio que intercepta las peticiones antes de que lleguen al controlador (autenticación, RBAC).
- **MVC:** Patrón Modelo-Vista-Controlador que separa la lógica de negocio, la presentación y el control de flujo.
- **PDO:** PHP Data Objects, extensión que proporciona una interfaz uniforme para acceso a bases de datos con soporte para sentencias preparadas.
- **RabbitMQ:** Broker de mensajería de código abierto que implementa el protocolo AMQP para comunicación asincrónica.
- **Ratchet:** Librería PHP para la creación de servidores WebSocket sobre ReactPHP.
- **RBAC:** Role-Based Access Control, modelo de seguridad donde los permisos se asignan a roles y los usuarios se vinculan a roles.
- **ReactPHP:** Framework de programación asincrónica y orientada a eventos para PHP.
- **Server-Side Processing (SSP):** Técnica donde el servidor procesa la paginación, búsqueda y ordenamiento, enviando solo el subconjunto de datos necesario.
- **Session Fixation:** Ataque que fuerza al usuario a usar un ID de sesión conocido por el atacante; se previene con `session_regenerate_id()`.
- **Soft Delete:** Técnica de eliminación lógica donde el registro no se borra físicamente sino que se marca como inactivo.
- **SQL Injection:** Vulnerabilidad que permite ejecutar código SQL malicioso a través de campos de entrada; se previene con sentencias preparadas PDO.
- **WebSocket:** Protocolo de comunicación bidireccional sobre TCP que permite al servidor enviar datos al cliente sin que este los solicite.
- **Worker:** Proceso en segundo plano (demonio) que consume mensajes de una cola y los procesa de forma asincrónica.
- **XSS:** Cross-Site Scripting, vulnerabilidad que permite inyectar scripts maliciosos en páginas web; se previene con la codificación de salida.
- **XAMPP:** Paquete de software libre que integra Apache, MySQL/MariaDB, PHP y Perl para entornos de desarrollo web.

---

## 12. REFERENCIAS BIBLIOGRÁFICAS

- Apache Software Foundation. (2024). *Apache HTTP Server Documentation*. https://httpd.apache.org/docs/2.4/

- Docker Inc. (2024). *Docker Documentation*. https://docs.docker.com/

- MariaDB Foundation. (2024). *MariaDB Server Documentation*. https://mariadb.com/kb/en/documentation/

- Mozilla Developer Network. (2024). *MDN Web Docs — HTML, CSS, JavaScript*. https://developer.mozilla.org/

- Oracle Corporation. (2024). *MySQL 8.0 Reference Manual*. https://dev.mysql.com/doc/refman/8.0/en/

- PHP Group. (2024). *PHP Manual*. https://www.php.net/manual/es/

- PHP Group. (2024). *PDO — PHP Data Objects*. https://www.php.net/manual/es/book.pdo.php

- PHP Group. (2024). *password_hash — Manual PHP*. https://www.php.net/manual/es/function.password-hash.php

- Pivotal Software. (2024). *RabbitMQ Documentation*. https://www.rabbitmq.com/documentation.html

- Ratchet. (2024). *Ratchet — PHP WebSockets*. http://socketo.me/docs/

- The jQuery Foundation. (2024). *jQuery API Documentation*. https://api.jquery.com/

- SpryMedia Ltd. (2024). *DataTables — Table plug-in for jQuery*. https://datatables.net/manual/

- PhpOffice. (2024). *PhpSpreadsheet Documentation*. https://phpspreadsheet.readthedocs.io/

- OWASP Foundation. (2024). *OWASP Top Ten — Web Application Security Risks*. https://owasp.org/www-project-top-ten/

- Ferraiolo, D., Sandhu, R., Gavrila, S., Kuhn, D. y Chandramouli, R. (2001). Proposed NIST Standard for Role-Based Access Control. *ACM Transactions on Information and System Security*, 4(3), 224–274.
