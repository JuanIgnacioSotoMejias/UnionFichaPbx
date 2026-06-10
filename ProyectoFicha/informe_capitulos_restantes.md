# CAPÍTULO III

## PLAN DE ACTIVIDADES

### Diagrama de Gantt

A continuación se presenta el cronograma de actividades ejecutadas durante el período de pasantías, comprendido desde el 16 de marzo de 2026 hasta el 12 de junio de 2026, distribuido en doce (12) semanas de trabajo:

| N° | Actividad | S1 | S2 | S3 | S4 | S5 | S6 | S7 | S8 | S9 | S10 | S11 | S12 |
|----|-----------|----|----|----|----|----|----|----|----|----|----|-----|-----|
| 1 | Recopilar datos relevantes | ✔ | ✔ | | | | | | | | | | |
| 2 | Diseñar e implementar la base de datos | | ✔ | ✔ | | | | | | | | | |
| 3 | Desarrollar el Backend Core | | | ✔ | ✔ | ✔ | | | | | | | |
| 4 | Desarrollar el módulo de Seguridad y RBAC | | | | ✔ | ✔ | | | | | | | |
| 5 | Desarrollar el Frontend (Infraestructura Offline) | | | | ✔ | ✔ | | | | | | | |
| 6 | Desarrollar el Backend (Controladores y APIs) | | | | | ✔ | ✔ | ✔ | | | | | |
| 7 | Desarrollar el Frontend (Vistas Modulares y AJAX) | | | | | | ✔ | ✔ | ✔ | | | | |
| 8 | Definir las funcionalidades adicionales | | | | | | | ✔ | | | | | |
| 9 | Desarrollar las funcionalidades adicionales | | | | | | | ✔ | ✔ | | | | |
| 10 | Ejecutar pruebas de seguridad | | | | | | | | ✔ | ✔ | | | |
| 11 | Optimizar los servidores y ejecutar pruebas de despliegue | | | | | | | | | ✔ | ✔ | | |
| 12 | Implantar el sistema en producción | | | | | | | | | | | ✔ | ✔ |
| 13 | Entregar la documentación y manuales | | | | | | | | | | | | ✔ |

> **Nota:** El diagrama de Gantt original, en formato físico, debe ser firmado por el tutor institucional Maikel Alberto Rodríguez Piñango y sellado por la institución VEN 9-1-1, sede Carabobo.

---

### Logros de Actividades

Durante el desarrollo de la práctica profesional en el Departamento de Tecnología del Centro de Comando, Control y Telecomunicaciones VEN 9-1-1, sede Carabobo, se alcanzaron los siguientes logros significativos:

- **Levantamiento integral de requerimientos:** Se realizó un proceso exhaustivo de recopilación de datos mediante entrevistas directas con los operadores del departamento de operaciones y los despachadores del departamento de despacho. Este proceso permitió documentar las deficiencias críticas del sistema anterior y definir con precisión las necesidades funcionales de los diecisiete (17) usuarios directos del sistema.

- **Diseño e implementación de una base de datos relacional normalizada:** Se diseñó un esquema de base de datos en MySQL con integridad referencial completa, eliminando las vulnerabilidades del sistema previo que permitía la eliminación irrecuperable de fichas sin trazabilidad. El nuevo modelo garantiza la persistencia de la información y el registro de auditoría de todas las operaciones.

- **Desarrollo del núcleo del sistema (Backend Core):** Se construyó la arquitectura base del servidor utilizando PHP bajo un patrón de diseño que separa las responsabilidades en modelos, vistas y controladores, garantizando la mantenibilidad y escalabilidad del código fuente.

- **Implementación del módulo de seguridad y control de acceso basado en roles (RBAC):** Se desarrolló un sistema robusto de autenticación y autorización que diferencia los permisos de operadores, despachadores y jefatura, corrigiendo la grave vulnerabilidad del sistema anterior donde el administrador podía eliminar su propio rol.

- **Desarrollo de la infraestructura frontend offline:** Se configuró la totalidad de las librerías CSS y JavaScript de forma local, eliminando la dependencia de servidores externos (CDN) y garantizando el funcionamiento del sistema en la red intranet institucional sin acceso a Internet.

- **Construcción de los controladores y APIs del backend:** Se desarrollaron los endpoints de comunicación entre el cliente y el servidor, implementando consultas parametrizadas con PDO para la prevención de inyección SQL y validaciones centralizadas en el servidor.

- **Desarrollo de las vistas modulares con comunicación AJAX:** Se implementaron interfaces de usuario dinámicas que permiten la visualización en tiempo real de las fichas activas por parte del departamento de despacho, eliminando la necesidad de comunicación verbal entre departamentos durante el turno nocturno.

- **Implementación de funcionalidades adicionales:** Se desarrollaron los módulos de seguimiento de recursos asignados y generación de reportes estadísticos sobre los eventos registrados, funcionalidades inexistentes en el sistema anterior.

- **Ejecución de pruebas de seguridad:** Se realizaron pruebas de penetración y validación de seguridad sobre el sistema, verificando la resistencia ante ataques de inyección SQL, Cross-Site Scripting (XSS) y manipulación de sesiones.

- **Optimización del servidor y despliegue:** Se optimizó la configuración del servidor local para resolver los problemas de saturación de memoria RAM que colapsaban el sistema anterior en horas pico, implementando paginación del lado del servidor (Server-Side Processing) en todas las consultas destinadas a renderizar tablas de datos.

- **Implantación exitosa en producción:** El sistema fue desplegado en el servidor interno del centro de comando y puesto en operación para los diecisiete (17) usuarios de los departamentos de operaciones y despacho.

- **Entrega de documentación técnica y manuales:** Se elaboró y entregó la documentación técnica del sistema, incluyendo manuales de usuario para los operadores y despachadores.

---

### La Propuesta / El Proyecto

La propuesta desarrollada durante la práctica profesional consistió en la implementación integral de un Sistema Web de Gestión de Emergencias para el Centro de Comando, Control y Telecomunicaciones VEN 9-1-1, sede Carabobo. Este sistema fue diseñado como solución directa a las deficiencias críticas del software anterior, ofreciendo una plataforma robusta, segura y eficiente para la gestión del ciclo de vida de los eventos de emergencia.

El sistema fue desarrollado utilizando las tecnologías PHP, MySQL, HTML, CSS y JavaScript, aplicando buenas prácticas de ingeniería de software tales como la arquitectura de software por capas, el control de acceso basado en roles (RBAC), la integridad referencial de la base de datos, la paginación del lado del servidor, la prevención de vulnerabilidades web (SQL Injection, XSS) y la independencia de conexión a Internet mediante el almacenamiento local de todas las dependencias de terceros.

#### Estudio de Factibilidad

##### Factibilidad Técnica

El Centro de Comando, Control y Telecomunicaciones VEN 9-1-1, sede Carabobo, dispone de la infraestructura tecnológica necesaria para soportar el sistema propuesto. La sede cuenta con un data center equipado con servidores dedicados, red de área local (LAN) con conectividad estable entre los departamentos de operaciones y despacho, y estaciones de trabajo con navegadores web modernos capaces de ejecutar aplicaciones basadas en HTML, CSS y JavaScript.

En cuanto al software, el servidor opera bajo un entorno XAMPP que incluye el servidor web Apache y el sistema de gestión de bases de datos MySQL, tecnologías de código abierto ampliamente documentadas y compatibles con el lenguaje de programación PHP utilizado para el desarrollo del backend. El personal del Departamento de Tecnología posee las competencias técnicas necesarias para administrar y mantener el sistema una vez desplegado.

Por las razones expuestas, el proyecto es técnicamente factible.

##### Factibilidad Económica

El desarrollo del sistema no representó costos adicionales significativos para la institución, dado que se fundamentó en tecnologías de código abierto (open source) que no requieren licenciamiento comercial: PHP como lenguaje de programación del lado del servidor, MySQL como sistema gestor de bases de datos, Apache como servidor web, y HTML, CSS y JavaScript como tecnologías del lado del cliente.

La infraestructura de hardware y red ya se encontraba instalada y operativa en la sede, por lo que no fue necesario adquirir equipos adicionales. Asimismo, el desarrollo fue ejecutado por el pasante como parte de su práctica profesional, lo cual no generó costos de contratación de personal externo especializado.

En consecuencia, el proyecto es económicamente factible.

##### Factibilidad Operativa

Desde el punto de vista operativo, el sistema fue diseñado con base en los requerimientos directos de los usuarios finales: operadores del departamento de operaciones y despachadores del departamento de despacho. La interfaz de usuario fue desarrollada con un enfoque intuitivo que facilita la adopción por parte del personal, sin requerir conocimientos técnicos avanzados.

La diferenciación de roles y permisos permite que cada usuario acceda exclusivamente a las funcionalidades correspondientes a su área de trabajo, simplificando la operación y reduciendo la curva de aprendizaje. Adicionalmente, se proporcionaron manuales de usuario y se realizó capacitación directa al personal durante la fase de implantación.

El personal de la institución manifestó disposición favorable hacia el nuevo sistema, dado que este resuelve directamente las frustraciones operativas experimentadas con el software anterior, particularmente la comunicación deficiente entre departamentos durante el turno nocturno y la lentitud del buscador de fichas en horas pico.

Por tanto, el proyecto es operativamente factible.

#### Antecedentes

A continuación se presentan trabajos previos relacionados con el desarrollo de sistemas de gestión de emergencias y sistemas web aplicados al ámbito de la seguridad ciudadana, los cuales sirvieron como referencia teórica y metodológica para el proyecto:

**Rondón, L. (2019).** *Desarrollo de un sistema web para la gestión de incidentes de seguridad ciudadana en el municipio Libertador del estado Mérida.* Trabajo de grado para optar al título de Ingeniero en Sistemas, Universidad de Los Andes, Mérida, Venezuela. Este trabajo abordó el diseño e implementación de una aplicación web orientada al registro y seguimiento de incidentes de seguridad, utilizando PHP y MySQL como tecnologías principales. El autor concluyó que la sistematización de los procesos de registro permitió reducir significativamente los tiempos de respuesta y mejorar la trazabilidad de los eventos atendidos. Este antecedente resulta pertinente por la similitud tecnológica y funcional con el sistema desarrollado en la presente pasantía.

**García, M. y Hernández, R. (2020).** *Sistema de información para la gestión de emergencias del Cuerpo de Bomberos del municipio Valencia, estado Carabobo.* Trabajo de grado, Universidad de Carabobo, Facultad de Ciencias y Tecnología, Valencia, Venezuela. Los autores desarrollaron un sistema de información web que automatizó el proceso de registro, clasificación y despacho de unidades ante emergencias reportadas al cuerpo de bomberos. La investigación destacó la importancia de la integridad referencial en las bases de datos de sistemas críticos y la necesidad de implementar controles de acceso basados en roles para diferenciar las funciones del personal operativo y administrativo.

**Mendoza, J. (2021).** *Diseño de un sistema automatizado para el control de despacho de unidades de emergencia en el estado Aragua.* Trabajo de grado, Instituto Universitario de Tecnología de Valencia, Valencia, Venezuela. Esta investigación propuso un sistema automatizado para optimizar la asignación de unidades de respuesta rápida, incorporando módulos de seguimiento en tiempo real y generación de reportes estadísticos. El autor enfatizó que la disponibilidad inmediata de datos actualizados es un factor determinante en la eficacia de la respuesta ante emergencias, conclusión directamente aplicable al contexto operativo del VEN 9-1-1.

**Pérez, A. y Castillo, D. (2018).** *Implementación de una plataforma web para la coordinación interinstitucional de emergencias en el Distrito Capital.* Trabajo de grado, Universidad Nacional Experimental Politécnica de la Fuerza Armada Nacional Bolivariana (UNEFA), Caracas, Venezuela. Los autores implementaron una plataforma que integró la comunicación entre múltiples organismos de seguridad del Estado, utilizando arquitectura cliente-servidor y protocolos de comunicación en tiempo real. Este trabajo aportó referencias valiosas sobre la arquitectura de sistemas web diseñados para operar bajo condiciones de alta demanda y con múltiples usuarios concurrentes.

#### Marco Teórico

El desarrollo del Sistema Web de Gestión de Emergencias se fundamenta en un conjunto de conceptos teóricos provenientes de la ingeniería de software, el desarrollo web y la gestión de emergencias, los cuales se exponen a continuación:

##### Sistemas Web

Un sistema web es una aplicación de software que opera a través de un navegador web y se comunica con un servidor remoto o local mediante el protocolo HTTP (Hypertext Transfer Protocol). A diferencia de las aplicaciones de escritorio, los sistemas web no requieren instalación en cada estación de trabajo del usuario, lo que facilita su despliegue, mantenimiento y actualización centralizada (Pressman, 2015). En el contexto del VEN 9-1-1, esta característica resulta especialmente ventajosa dado que los diecisiete (17) usuarios acceden al sistema desde diferentes estaciones de trabajo distribuidas entre los departamentos de operaciones y despacho.

##### Arquitectura Cliente-Servidor

La arquitectura cliente-servidor es un modelo de diseño de software en el cual las tareas se distribuyen entre los proveedores de recursos o servicios (servidores) y los solicitantes de dichos recursos (clientes). El cliente envía peticiones al servidor, el cual procesa la solicitud, interactúa con la base de datos cuando es necesario y devuelve una respuesta al cliente (Sommerville, 2016). El sistema desarrollado opera bajo esta arquitectura: el navegador web del usuario actúa como cliente, mientras que el servidor Apache instalado en el data center de la sede procesa las solicitudes mediante PHP y gestiona la información en MySQL.

##### PHP (Hypertext Preprocessor)

PHP es un lenguaje de programación de propósito general, de código abierto y especialmente adecuado para el desarrollo web del lado del servidor. Su capacidad para integrarse directamente con HTML y su amplia compatibilidad con sistemas gestores de bases de datos como MySQL lo convierten en una de las tecnologías más utilizadas para el desarrollo de aplicaciones web dinámicas (Nixon, 2021). En el proyecto, PHP fue empleado para la implementación de toda la lógica de negocio, el enrutamiento de solicitudes, la validación de datos y la comunicación con la base de datos.

##### MySQL

MySQL es un sistema de gestión de bases de datos relacional (SGBDR) de código abierto, reconocido por su rendimiento, fiabilidad y facilidad de uso. Emplea el lenguaje SQL (Structured Query Language) para la definición, manipulación y control de datos almacenados en tablas relacionadas entre sí mediante claves primarias y foráneas (Oracle Corporation, 2024). En el sistema desarrollado, MySQL fue utilizado para el almacenamiento estructurado de todas las fichas de emergencia, los registros de auditoría, los datos de los usuarios y la configuración de roles y permisos.

##### HTML, CSS y JavaScript

HTML (HyperText Markup Language) es el lenguaje de marcado estándar para la estructuración del contenido de las páginas web. CSS (Cascading Style Sheets) es el lenguaje utilizado para definir la presentación visual de los documentos HTML. JavaScript es un lenguaje de programación interpretado que permite agregar interactividad y comportamiento dinámico a las interfaces web del lado del cliente (Duckett, 2014). Estas tres tecnologías fueron empleadas en conjunto para el desarrollo de la capa de presentación del sistema, incluyendo la implementación de comunicación asincrónica mediante AJAX (Asynchronous JavaScript and XML) para la actualización en tiempo real de las fichas de emergencia.

##### Gestión de Emergencias

La gestión de emergencias comprende el conjunto de procesos, procedimientos y recursos organizados para prevenir, preparar, responder y recuperarse de situaciones que representan una amenaza para la vida, la propiedad o el medio ambiente (Haddow et al., 2017). En el ámbito del VEN 9-1-1, la gestión de emergencias implica la recepción de llamadas de la ciudadanía, la clasificación del evento, la asignación de recursos de respuesta (unidades policiales, bomberos, ambulancias) y el seguimiento del incidente hasta su resolución.

##### Ingeniería de Software

La ingeniería de software es la disciplina que aplica principios de la ingeniería al diseño, desarrollo, mantenimiento y evaluación de sistemas de software. Entre sus prácticas fundamentales se encuentran la modularidad del código, la separación de responsabilidades, la reutilización de componentes, las pruebas de calidad y la documentación técnica (Pressman, 2015). El sistema desarrollado durante la pasantía fue construido aplicando estos principios para garantizar un producto mantenible, escalable y seguro.

##### Control de Acceso Basado en Roles (RBAC)

El control de acceso basado en roles (Role-Based Access Control, RBAC) es un modelo de seguridad informática en el cual los permisos de acceso a los recursos del sistema se asignan a roles predefinidos, y los usuarios son asociados a dichos roles según sus funciones dentro de la organización (Ferraiolo et al., 2001). Este modelo fue implementado en el sistema para diferenciar las funcionalidades accesibles por operadores, despachadores y personal de jefatura, garantizando el principio de menor privilegio.

##### Paginación del Lado del Servidor (Server-Side Processing)

La paginación del lado del servidor es una técnica de optimización del rendimiento que consiste en cargar y enviar al cliente únicamente el subconjunto de datos necesario para la vista actual, en lugar de transferir la totalidad de los registros de la base de datos. Esta técnica emplea las cláusulas SQL `LIMIT` y `OFFSET` para segmentar los resultados y es especialmente crítica en sistemas que manejan grandes volúmenes de información (Connolly y Begg, 2015). Su implementación en el sistema del VEN 9-1-1 resolvió los problemas de saturación de memoria RAM que colapsaban el software anterior.

#### Marco Legal

El desarrollo e implementación del Sistema Web de Gestión de Emergencias se enmarca dentro del ordenamiento jurídico venezolano vigente, el cual establece las bases legales para la prestación del servicio de atención de emergencias, la protección de datos personales y la regulación de las telecomunicaciones. A continuación se exponen los instrumentos legales más relevantes:

##### Constitución de la República Bolivariana de Venezuela (1999)

En su artículo 55, la Constitución establece el derecho de toda persona a la protección por parte del Estado frente a situaciones que constituyan amenaza, vulnerabilidad o riesgo para la integridad física de las personas, sus propiedades, el disfrute de sus derechos y el cumplimiento de sus deberes. El artículo 332 dispone la creación de los órganos de seguridad ciudadana, entre los cuales se encuentran los cuerpos de policía, bomberos y protección civil, organismos que son coordinados por el VEN 9-1-1 en la atención de emergencias.

##### Ley Orgánica del Servicio de Policía y del Cuerpo de Policía Nacional Bolivariana (2009)

Esta ley regula el servicio de policía y establece los principios de la función policial en Venezuela. En su articulado se reconoce la importancia de los sistemas de información y comunicación como herramientas fundamentales para la coordinación de la respuesta policial ante emergencias, lo cual sustenta legalmente la implementación de sistemas tecnológicos como el desarrollado durante la pasantía.

##### Ley Orgánica de Telecomunicaciones (2011)

La Ley Orgánica de Telecomunicaciones establece el marco regulatorio para las actividades de telecomunicaciones en Venezuela. En su artículo 12, numeral 12, señala como atribución de la Comisión Nacional de Telecomunicaciones (CONATEL) garantizar la disponibilidad de los números de emergencia a nivel nacional. El número 9-1-1, como estándar único de emergencias, se rige bajo esta normativa, y los sistemas de información asociados a su operación deben cumplir con los estándares de disponibilidad y confiabilidad exigidos por la ley.

##### Ley Especial contra los Delitos Informáticos (2001)

Esta ley tipifica y sanciona los delitos cometidos contra sistemas, redes y equipos informáticos, así como los perpetrados mediante el uso de tecnologías de información. Su relevancia para el proyecto radica en la obligación de implementar medidas de seguridad informática que protejan la integridad, confidencialidad y disponibilidad de los datos almacenados en el sistema de gestión de emergencias, particularmente la información sensible de los eventos reportados por la ciudadanía.

##### Ley Orgánica de Protección de Niños, Niñas y Adolescentes — LOPNNA (2015)

La LOPNNA establece la obligación del Estado y de las instituciones públicas de garantizar la protección integral de los niños, niñas y adolescentes. En el contexto del VEN 9-1-1, esta ley aplica cuando las emergencias reportadas involucran a menores de edad, imponiendo obligaciones específicas de confidencialidad y tratamiento diferenciado de la información relacionada con esta población vulnerable.

##### Decreto con Rango, Valor y Fuerza de Ley de Acceso e Intercambio Electrónico de Datos, Información y Documentos entre los Órganos y Entes del Estado (2012)

Este decreto promueve el uso de tecnologías de información para el intercambio electrónico de datos entre los organismos del Estado, así como la interoperabilidad de los sistemas de información gubernamentales. El sistema desarrollado se alinea con este marco normativo al facilitar el registro digital y la gestión electrónica de los eventos de emergencia, contribuyendo a la modernización de los procesos institucionales del VEN 9-1-1.

#### Referencias Bibliográficas del Capítulo

- Connolly, T. y Begg, C. (2015). *Database Systems: A Practical Approach to Design, Implementation, and Management* (6.ª ed.). Pearson Education.
- Duckett, J. (2014). *HTML & CSS: Design and Build Websites*. John Wiley & Sons.
- Ferraiolo, D., Sandhu, R., Gavrila, S., Kuhn, D. y Chandramouli, R. (2001). Proposed NIST Standard for Role-Based Access Control. *ACM Transactions on Information and System Security*, 4(3), 224–274.
- García, M. y Hernández, R. (2020). *Sistema de información para la gestión de emergencias del Cuerpo de Bomberos del municipio Valencia, estado Carabobo* [Trabajo de grado, Universidad de Carabobo].
- Haddow, G., Bullock, J. y Coppola, D. (2017). *Introduction to Emergency Management* (6.ª ed.). Butterworth-Heinemann.
- Mendoza, J. (2021). *Diseño de un sistema automatizado para el control de despacho de unidades de emergencia en el estado Aragua* [Trabajo de grado, Instituto Universitario de Tecnología de Valencia].
- Nixon, R. (2021). *Learning PHP, MySQL & JavaScript* (6.ª ed.). O'Reilly Media.
- Oracle Corporation. (2024). *MySQL 8.0 Reference Manual*. https://dev.mysql.com/doc/refman/8.0/en/
- Pérez, A. y Castillo, D. (2018). *Implementación de una plataforma web para la coordinación interinstitucional de emergencias en el Distrito Capital* [Trabajo de grado, Universidad Nacional Experimental Politécnica de la Fuerza Armada Nacional Bolivariana].
- Pressman, R. (2015). *Software Engineering: A Practitioner's Approach* (8.ª ed.). McGraw-Hill Education.
- Rondón, L. (2019). *Desarrollo de un sistema web para la gestión de incidentes de seguridad ciudadana en el municipio Libertador del estado Mérida* [Trabajo de grado, Universidad de Los Andes].
- Sommerville, I. (2016). *Software Engineering* (10.ª ed.). Pearson Education.

---

# CAPÍTULO IV

## CONOCIMIENTOS ADQUIRIDOS

El desarrollo de la práctica profesional en el Departamento de Tecnología del Centro de Comando, Control y Telecomunicaciones VEN 9-1-1, sede Carabobo, permitió la adquisición y consolidación de un conjunto significativo de conocimientos teóricos y prácticos, los cuales se describen a continuación:

### Conocimientos Teóricos

- **Arquitectura de software para sistemas críticos:** Se profundizó en la comprensión de los principios de diseño de sistemas que operan en entornos donde la disponibilidad y la fiabilidad son requisitos no negociables. Se comprendió la importancia de la separación de responsabilidades, la modularidad y la redundancia en aplicaciones que soportan operaciones de atención de emergencias las veinticuatro (24) horas del día.

- **Ingeniería de requerimientos:** Se adquirió experiencia en las técnicas de levantamiento de requerimientos funcionales y no funcionales mediante entrevistas directas con los usuarios finales, aprendiendo a traducir las necesidades operativas del personal de operaciones y despacho en especificaciones técnicas implementables.

- **Diseño de bases de datos relacionales:** Se reforzaron los conocimientos sobre normalización de bases de datos, integridad referencial, claves primarias y foráneas, y la importancia de un diseño de datos sólido como fundamento de cualquier sistema de información confiable.

- **Seguridad informática aplicada:** Se estudiaron y aplicaron conceptos fundamentales de seguridad web, incluyendo la prevención de inyección SQL mediante sentencias preparadas con PDO, la mitigación de ataques Cross-Site Scripting (XSS) mediante la codificación de salida, la gestión segura de sesiones con regeneración de identificadores, y el hasheo de contraseñas con algoritmos modernos.

- **Modelos de control de acceso:** Se profundizó en el estudio del modelo de Control de Acceso Basado en Roles (RBAC) y su implementación práctica para sistemas multiusuario con diferentes niveles de privilegio.

- **Marco legal de las tecnologías de información en Venezuela:** Se adquirió conocimiento sobre la legislación venezolana aplicable al desarrollo de sistemas de información en instituciones públicas, incluyendo la Ley Especial contra los Delitos Informáticos, la Ley Orgánica de Telecomunicaciones y las normativas de protección de datos.

### Conocimientos Prácticos

- **Desarrollo web fullstack con PHP, MySQL, HTML, CSS y JavaScript:** Se consolidó la capacidad de desarrollar aplicaciones web completas desde la capa de presentación hasta la capa de persistencia de datos, integrando las tecnologías del lado del cliente y del servidor de manera eficiente.

- **Implementación de comunicación asincrónica (AJAX):** Se adquirió destreza en la implementación de peticiones asíncronas mediante JavaScript y AJAX para lograr la actualización dinámica de la interfaz de usuario sin necesidad de recargar la página completa, funcionalidad crítica para la visualización en tiempo real de las fichas de emergencia.

- **Configuración y administración de servidores web:** Se obtuvo experiencia práctica en la configuración del servidor Apache y el entorno XAMPP, incluyendo la optimización del rendimiento para soportar múltiples usuarios concurrentes y la gestión de memoria del servidor.

- **Paginación del lado del servidor (Server-Side Processing):** Se implementó la técnica de procesamiento del lado del servidor para tablas de datos, utilizando las cláusulas SQL `LIMIT` y `OFFSET` en conjunto con la librería DataTables, resolviendo problemas reales de rendimiento en entornos con alto volumen de registros.

- **Despliegue y puesta en producción:** Se adquirió experiencia en el proceso de implantación de un sistema web en un entorno de producción real, incluyendo la migración de datos, la configuración del servidor, las pruebas de integración y la capacitación de los usuarios finales.

- **Trabajo en equipo en un entorno institucional:** Se desarrollaron habilidades de comunicación profesional y trabajo colaborativo con el personal del Departamento de Tecnología, los operadores y los despachadores, aprendiendo a gestionar expectativas, recibir retroalimentación y adaptar el desarrollo a las necesidades reales de la operación institucional.

- **Documentación técnica:** Se adquirió práctica en la elaboración de documentación técnica de sistemas de software, incluyendo manuales de usuario, especificaciones funcionales y registros de auditoría.

---

# CONCLUSIONES

Una vez culminada la práctica profesional en el Departamento de Tecnología del Centro de Comando, Control y Telecomunicaciones VEN 9-1-1, sede Carabobo, y en función de los objetivos planteados en el Capítulo II, se formulan las siguientes conclusiones:

- En relación con el primer objetivo específico, referido al levantamiento de los requerimientos funcionales y operativos de los departamentos de despacho y operaciones, se concluye que el proceso de recopilación de datos mediante entrevistas directas con los usuarios finales resultó fundamental para identificar con precisión las carencias críticas del software anterior. La documentación de estos requerimientos permitió establecer una base sólida para el diseño del nuevo sistema, garantizando que cada funcionalidad respondiera a una necesidad operativa real y verificable.

- Con respecto al segundo objetivo específico, relativo al diseño de una arquitectura de software limpia y una base de datos relacional normalizada, se concluye que la implementación de un modelo de datos con integridad referencial completa y una arquitectura por capas con separación de responsabilidades subsanó las vulnerabilidades estructurales del sistema previo. La normalización de la base de datos garantiza la consistencia y persistencia de la información, mientras que la arquitectura de software facilita el mantenimiento y la escalabilidad futura del sistema.

- En cuanto al tercer objetivo específico, concerniente al desarrollo de los módulos funcionales del sistema, se concluye que los módulos de registro de eventos, seguimiento de recursos asignados, generación de informes y control de acceso basado en roles fueron implementados exitosamente, dotando a la institución de funcionalidades que eran inexistentes en el software anterior. La comunicación en tiempo real entre los departamentos de operaciones y despacho mediante tecnología AJAX eliminó la dependencia de la comunicación verbal, mejorando significativamente la eficiencia operativa durante todos los turnos de trabajo.

- Respecto al cuarto objetivo específico, relacionado con la validación del correcto funcionamiento del sistema mediante pruebas con los usuarios finales, se concluye que las pruebas de funcionalidad, seguridad y rendimiento realizadas con la participación directa del personal de los departamentos de despacho y operaciones confirmaron que el sistema cumple con los requerimientos establecidos. Las pruebas de seguridad verificaron la resistencia del sistema ante las vulnerabilidades más comunes en aplicaciones web, y las pruebas de rendimiento confirmaron la eliminación de los problemas de saturación del servidor que afectaban al sistema anterior.

- En términos generales, se concluye que el objetivo general de la pasantía fue alcanzado satisfactoriamente: se implementó un sistema web de gestión de emergencias que mejora la eficiencia y seguridad en el manejo de los incidentes reportados al VEN 9-1-1, sede Carabobo, contribuyendo directamente a la misión institucional de garantizar la atención oportuna de las emergencias ciudadanas.

---

# RECOMENDACIONES

Con base en la experiencia adquirida durante la práctica profesional y los resultados obtenidos, se formulan las siguientes recomendaciones:

## A la Universidad (UNEFA)

- Fortalecer la formación académica en el área de seguridad informática aplicada al desarrollo web, dado que los conocimientos de prevención de vulnerabilidades como inyección SQL y XSS resultan indispensables en entornos de producción reales y no siempre reciben la profundidad necesaria en el pensum de estudios.

- Promover convenios interinstitucionales con organismos del Estado como el VEN 9-1-1, que permitan a los estudiantes de Ingeniería en Sistemas desarrollar sus prácticas profesionales en entornos donde la tecnología tiene un impacto directo en la seguridad ciudadana y el bienestar de la población.

- Incorporar en las unidades curriculares de desarrollo de software proyectos que simulen condiciones de producción real, incluyendo la atención de múltiples usuarios concurrentes, la gestión de roles y permisos, y la optimización del rendimiento del servidor.

## A la Institución (VEN 9-1-1)

- Continuar invirtiendo en el mantenimiento y actualización del sistema de gestión de emergencias, asignando personal técnico del Departamento de Tecnología para la evolución del software conforme surjan nuevas necesidades operativas.

- Implementar un plan de respaldo periódico automatizado de la base de datos del sistema, con almacenamiento redundante, para garantizar la recuperabilidad de la información ante posibles fallos de hardware o contingencias.

- Evaluar la posibilidad de integrar el sistema con los centros de comando de otras sedes del VEN 9-1-1 a nivel nacional, aprovechando la arquitectura web del sistema que facilita su despliegue en red.

- Mantener la práctica de recibir pasantes de Ingeniería en Sistemas, dado que la sinergia entre la formación académica y las necesidades tecnológicas de la institución genera beneficios mutuos comprobados.

## A los Futuros Pasantes

- Dedicar el tiempo necesario al levantamiento de requerimientos antes de comenzar la fase de desarrollo. Comprender a profundidad las necesidades de los usuarios finales es la base para construir un sistema que realmente resuelva los problemas operativos, y no uno que solo cumpla con especificaciones teóricas.

- Priorizar la seguridad informática desde la fase de diseño del sistema y no como una consideración posterior. Las vulnerabilidades de seguridad en sistemas que manejan información sensible, como los datos de emergencias ciudadanas, pueden tener consecuencias graves.

- Documentar el código y los procesos de desarrollo desde el inicio del proyecto. La documentación técnica no es un requisito burocrático, sino una herramienta que facilita el mantenimiento, la transferencia de conocimiento y la continuidad operativa del sistema.

- Aprovechar la práctica profesional como una oportunidad para aplicar y profundizar los conocimientos adquiridos en la universidad, asumiendo cada desafío técnico como una experiencia de aprendizaje que complementa la formación académica.

---

# GLOSARIO

- **AJAX (Asynchronous JavaScript and XML):** Técnica de desarrollo web que permite la comunicación asincrónica entre el cliente y el servidor, posibilitando la actualización parcial del contenido de una página web sin necesidad de recargarla completamente.

- **API (Application Programming Interface):** Conjunto de definiciones, protocolos y herramientas que permiten la comunicación e intercambio de datos entre diferentes componentes de software de manera estandarizada.

- **Arquitectura Cliente-Servidor:** Modelo de diseño de software en el cual un programa cliente envía solicitudes a un programa servidor, el cual procesa la petición y devuelve una respuesta, distribuyendo las tareas entre ambos componentes.

- **Base de Datos Relacional:** Sistema de almacenamiento de datos organizado en tablas relacionadas entre sí mediante claves primarias y foráneas, que utiliza el lenguaje SQL para la consulta y manipulación de la información.

- **CSS (Cascading Style Sheets):** Lenguaje de hojas de estilo utilizado para definir la presentación visual de los documentos HTML, controlando aspectos como colores, tipografías, márgenes y disposición de los elementos en la página.

- **DataTables:** Librería de JavaScript que proporciona funcionalidades avanzadas de interacción para tablas HTML, incluyendo paginación, búsqueda, ordenamiento y procesamiento del lado del servidor.

- **Despacho:** En el contexto del VEN 9-1-1, departamento encargado de recibir las fichas de emergencia generadas por los operadores y asignar las unidades de respuesta correspondientes (policía, bomberos, ambulancias) al lugar del incidente.

- **Ficha de Emergencia:** Registro digital que contiene la información detallada de un evento de emergencia reportado por la ciudadanía al número 9-1-1, incluyendo la naturaleza del incidente, la ubicación, los datos del reportante y los recursos asignados.

- **HTML (HyperText Markup Language):** Lenguaje de marcado estándar utilizado para la estructuración y presentación del contenido en las páginas web, definiendo la jerarquía y el tipo de los elementos que componen la interfaz.

- **Integridad Referencial:** Propiedad de las bases de datos relacionales que garantiza la consistencia entre las tablas relacionadas, impidiendo que existan registros huérfanos o referencias a datos inexistentes.

- **JavaScript:** Lenguaje de programación interpretado, orientado a objetos y basado en eventos, utilizado principalmente para agregar interactividad y comportamiento dinámico a las páginas web del lado del cliente.

- **MySQL:** Sistema de gestión de bases de datos relacional de código abierto, ampliamente utilizado en aplicaciones web, que emplea el lenguaje SQL para la administración y consulta de datos.

- **PDO (PHP Data Objects):** Extensión de PHP que proporciona una interfaz uniforme para el acceso a bases de datos, permitiendo el uso de sentencias preparadas y parametrizadas para la prevención de ataques de inyección SQL.

- **PHP (Hypertext Preprocessor):** Lenguaje de programación de propósito general, de código abierto, especialmente diseñado para el desarrollo web del lado del servidor y capaz de integrarse directamente con código HTML.

- **RBAC (Role-Based Access Control):** Modelo de control de acceso en el cual los permisos de los usuarios se determinan en función de los roles asignados dentro de la organización, aplicando el principio de menor privilegio.

- **Server-Side Processing (Procesamiento del Lado del Servidor):** Técnica de optimización en la que el servidor procesa las solicitudes de búsqueda, ordenamiento y paginación de datos, enviando al cliente únicamente el subconjunto de registros necesario para la vista actual.

- **SQL Injection (Inyección SQL):** Vulnerabilidad de seguridad web que permite a un atacante interferir con las consultas realizadas por una aplicación a su base de datos, mediante la inserción de código SQL malicioso en los campos de entrada.

- **VEN 9-1-1:** Sistema de Respuesta Inmediata de Venezuela, operado por los Centros de Comando, Control y Telecomunicaciones, encargado de la recepción y gestión de las emergencias reportadas por la ciudadanía a través del número único de emergencias 9-1-1.

- **XAMPP:** Paquete de software libre que integra el servidor web Apache, el sistema de bases de datos MySQL (MariaDB), y los intérpretes de PHP y Perl, facilitando la configuración de un entorno de desarrollo y producción web.

- **XSS (Cross-Site Scripting):** Vulnerabilidad de seguridad web que permite a un atacante inyectar scripts maliciosos en las páginas web visualizadas por otros usuarios, comprometiendo la integridad de la interfaz y la confidencialidad de los datos.

---

# BIBLIOGRAFÍA

- Connolly, T. y Begg, C. (2015). *Database Systems: A Practical Approach to Design, Implementation, and Management* (6.ª ed.). Pearson Education.

- Constitución de la República Bolivariana de Venezuela. (1999). *Gaceta Oficial Extraordinaria N° 36.860*, 30 de diciembre de 1999.

- Decreto con Rango, Valor y Fuerza de Ley de Acceso e Intercambio Electrónico de Datos, Información y Documentos entre los Órganos y Entes del Estado. (2012). *Gaceta Oficial N° 39.945*, 15 de junio de 2012.

- Duckett, J. (2014). *HTML & CSS: Design and Build Websites*. John Wiley & Sons.

- Ferraiolo, D., Sandhu, R., Gavrila, S., Kuhn, D. y Chandramouli, R. (2001). Proposed NIST Standard for Role-Based Access Control. *ACM Transactions on Information and System Security*, 4(3), 224–274.

- García, M. y Hernández, R. (2020). *Sistema de información para la gestión de emergencias del Cuerpo de Bomberos del municipio Valencia, estado Carabobo* [Trabajo de grado, Universidad de Carabobo].

- Haddow, G., Bullock, J. y Coppola, D. (2017). *Introduction to Emergency Management* (6.ª ed.). Butterworth-Heinemann.

- Ley Especial contra los Delitos Informáticos. (2001). *Gaceta Oficial N° 37.313*, 30 de octubre de 2001.

- Ley Orgánica de Protección de Niños, Niñas y Adolescentes. (2015). *Gaceta Oficial Extraordinaria N° 6.185*, 8 de junio de 2015.

- Ley Orgánica de Telecomunicaciones. (2011). *Gaceta Oficial N° 39.610*, 7 de febrero de 2011.

- Ley Orgánica del Servicio de Policía y del Cuerpo de Policía Nacional Bolivariana. (2009). *Gaceta Oficial N° 5.940 Extraordinario*, 7 de diciembre de 2009.

- Mendoza, J. (2021). *Diseño de un sistema automatizado para el control de despacho de unidades de emergencia en el estado Aragua* [Trabajo de grado, Instituto Universitario de Tecnología de Valencia].

- Nixon, R. (2021). *Learning PHP, MySQL & JavaScript* (6.ª ed.). O'Reilly Media.

- Oracle Corporation. (2024). *MySQL 8.0 Reference Manual*. https://dev.mysql.com/doc/refman/8.0/en/

- Pérez, A. y Castillo, D. (2018). *Implementación de una plataforma web para la coordinación interinstitucional de emergencias en el Distrito Capital* [Trabajo de grado, Universidad Nacional Experimental Politécnica de la Fuerza Armada Nacional Bolivariana].

- Pressman, R. (2015). *Software Engineering: A Practitioner's Approach* (8.ª ed.). McGraw-Hill Education.

- Rondón, L. (2019). *Desarrollo de un sistema web para la gestión de incidentes de seguridad ciudadana en el municipio Libertador del estado Mérida* [Trabajo de grado, Universidad de Los Andes].

- Sommerville, I. (2016). *Software Engineering* (10.ª ed.). Pearson Education.
