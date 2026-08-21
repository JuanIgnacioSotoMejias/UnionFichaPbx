# Registro Histórico de Errores y Soluciones

Este documento sirve como bitácora para registrar todos los problemas encontrados, diagnosticados y solucionados dentro de los sistemas `ProyectoFicha`, `pbx-receptor`, infraestructura FreePBX y Firewall USG6630.

> **Regla de uso:** Antes de iniciar el diagnóstico de un nuevo problema, revisar este historial para comprobar si un síntoma similar ya fue resuelto en el pasado.

---

## Plantilla Base (Copiar para cada nuevo error)

* **Fecha:** DD/MM/AAAA
* **Sistema afectado:** (Ej. ProyectoFicha, pbx-receptor, FreePBX, Red/Firewall)
* **Archivo o módulo afectado:** (Ej. Controlador X, Script Y, Interfaz Z)
* **Descripción del problema:** 
* **Síntoma observado:** 
* **Causa raíz:** 
* **Diagnóstico realizado:** 
* **Solución aplicada:** 
* **Archivos modificados:** 
* **Configuración modificada:** 
* **Pruebas realizadas:** 
* **Resultado:** 
* **Posibles efectos secundarios:** 
* **Recomendaciones para evitar que vuelva a ocurrir:** 

---

## Historial de Errores

### 1. (Ejemplo del Documento Firewall) Error de Registro SIP por DNS Timeout
* **Fecha:** 14/08/2026
* **Sistema afectado:** Red/Firewall / Telefonía IP
* **Archivo o módulo afectado:** Configuración de Red eSpace 7950
* **Descripción del problema:** Los teléfonos IP mostraban "Reintentando..." y no lograban registrarse en el FreePBX.
* **Síntoma observado:** Falla de registro en los teléfonos.
* **Causa raíz:** El teléfono eSpace 7950 hacía peticiones DNS al PBX (`172.16.80.250`), el cual no actuaba como servidor DNS.
* **Diagnóstico realizado:** Verificación de logs y captura de tráfico mostrando timeouts en peticiones DNS (Puerto 53 UDP) originadas por el teléfono.
* **Solución aplicada:** Configurar el gateway (`172.16.80.1`) o un DNS público (`8.8.8.8`) en la configuración de los teléfonos.
* **Archivos/Configuración modificada:** DHCP del Firewall o Configuración manual en teléfonos.
* **Resultado:** Teléfonos se registran exitosamente tras resolver DNS.
* **Recomendaciones:** Asegurarse de que el servidor DHCP entregue un DNS válido y accesible (no la IP del FreePBX) a los dispositivos finales.

### 2. (Ejemplo del Documento Firewall) Bloqueo de Tráfico L2 (Zero-Trust)
* **Fecha:** 14/08/2026
* **Sistema afectado:** Firewall USG6630
* **Archivo o módulo afectado:** Zonas de Seguridad (`trust`)
* **Descripción del problema:** Los dispositivos dentro de la misma VLAN 80 no podían comunicarse entre sí.
* **Síntoma observado:** Paquetes perdidos en la comunicación intra-VLAN (SIP/RTP fallando internamente).
* **Causa raíz:** El Firewall Huawei bloquea por defecto incluso el tráfico dentro de la misma zona (`trust`). Además, las interfaces físicas no estaban agregadas a la zona `trust`.
* **Solución aplicada:** Agregar interfaces físicas (`GE1/0/1` - `GE1/0/7`) a la zona `trust` explícitamente y mantener regla de permitir tráfico interno (`SEC_VEN911_INTERNAL_ALL`).
* **Resultado:** Comunicación L2/L3 permitida dentro del segmento de voz.

### 3. (Ejemplo del Documento Firewall) Intercepción SIP ALG Corrupta
* **Fecha:** 14/08/2026
* **Sistema afectado:** Firewall USG6630 / FreePBX
* **Archivo o módulo afectado:** Motor ALG (Application Layer Gateway)
* **Descripción del problema:** Registro y señalización SIP corrupta (Errores 401, 403, Timeouts).
* **Causa raíz:** SIP ALG en Huawei alteraba las cabeceras SDP de los paquetes SIP innecesariamente, dado que no se estaba utilizando NAT (redes planas).
* **Solución aplicada:** Ejecución del comando global `undo firewall detect sip`.
* **Resultado:** Señalización SIP estable y transparente.
* **Recomendaciones:** Jamás reactivar SIP ALG en entornos enrutados sin NAT.

### 4. Pérdida de Conectividad SSH y Servicios Web tras Reconexión Física
* **Fecha:** 21/08/2026
* **Sistema afectado:** Servidor Dell PowerEdge R630 / Infraestructura de Desarrollo
* **Archivo o módulo afectado:** Tarjeta de Red `eno4`, Demonio `sshd`
* **Descripción del problema:** No se podía acceder por SSH ni visualizar las interfaces web de `ProyectoFicha` ni `pbx-receptor` desde la computadora de desarrollo.
* **Síntoma observado:** Ping sin respuesta y conexión rechazada (`Connection refused` / `TcpTestSucceeded: False`) al puerto 22.
* **Causa raíz:** La interfaz de red `eno4` no tenía fijada la IP estática `172.16.80.239` y el demonio `openssh-server` no estaba instalado/habilitado en el sistema operativo Linux.
* **Diagnóstico realizado:** Prueba de socket TCP desde PowerShell hacia `172.16.80.239:22` mostrando `banner exchange: Connection refused`. Tabla ARP confirmando resolución L2 a MAC `14:18:77:67:87:c5`.
* **Solución aplicada:** Configuración de IP estática `172.16.80.239/24` en `eno4`, conexión a Internet por `eno1`, e instalación/habilitación de `openssh-server` mediante `systemctl enable --now ssh`.
* **Resultado:** Conectividad SSH operativa al 100% (`TcpTestSucceeded: True`).
* **Recomendaciones:** Asegurar que `sshd` permanezca habilitado en el arranque (`systemctl enable ssh`) y que la interfaz `eno4` conserve su perfil manual estático.

### 5. Falla de Resolución de Nombre de Base de Datos (`getaddrinfo for pbx_db failed`)
* **Fecha:** 21/08/2026
* **Sistema afectado:** pbx-receptor (Docker / MariaDB)
* **Archivo o módulo afectado:** Contenedor `pbx_db_container`, Red `pbx-receptor_pbx_network`
* **Descripción del problema:** Al acceder a `http://172.16.80.240`, Laravel arrojaba un error 500 `QueryException: getaddrinfo for pbx_db failed: Temporary failure in name resolution`.
* **Síntoma observado:** Falla al consultar la tabla `sessions` en MariaDB desde `pbx_app_container`.
* **Causa raíz:** El contenedor de base de datos `pbx_db_container` no estaba enlazado a la red interna `pbx-receptor_pbx_network` tras haber sido iniciado de forma independiente.
* **Diagnóstico realizado:** `docker inspect` mostró que `pbx_db_container` tenía `"Networks": {}`.
* **Solución aplicada:** Conexión del contenedor a la red mediante `docker network connect --alias pbx_db pbx-receptor_pbx_network pbx_db_container` y reinicio de `pbx_app_container`.
* **Resultado:** `http://172.16.80.240/login` y `/api/ping` respondiendo exitosamente con código `HTTP 200 OK`.
* **Recomendaciones:** Siempre iniciar el stack completo mediante `docker compose up -d` en el directorio de `pbx-receptor` para preservar las redes compartidas.

