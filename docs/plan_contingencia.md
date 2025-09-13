Plan de Contingencia
1. Introducción

El presente plan de contingencia tiene como finalidad definir las acciones que se deben realizar en caso de que ocurran problemas en el sistema del proyecto. Su objetivo principal es asegurar que, ante un incidente, el sistema pueda recuperarse rápidamente y siga funcionando de manera correcta.

2. Objetivos

Minimizar el tiempo de inactividad del sistema.

Evitar la pérdida de información importante.

Proteger los datos de los usuarios.

Definir un procedimiento claro para actuar frente a incidentes.

3. Riesgos principales

Durante el desarrollo y uso del sistema se identificaron los siguientes riesgos:

Fallo en la base de datos.

Caída del servidor o computadora que aloja el sistema.

Errores humanos (borrado de archivos, configuración incorrecta).

Problemas con servicios externos (ejemplo: Google Login o pasarela de pagos).

Pérdida de información por falta de copias de seguridad.

4. Medidas preventivas

Para reducir la probabilidad de que ocurran los problemas anteriores se plantean las siguientes medidas:

Realizar copias de seguridad (backups) de la base de datos todos los días.

Guardar los respaldos en diferentes lugares (PC, disco externo y nube).

Probar los cambios del sistema en un entorno de pruebas antes de pasarlos a producción.

Documentar los pasos importantes para que cualquier integrante del equipo pueda seguirlos.

Capacitar al equipo para evitar errores por desconocimiento.

5. Procedimiento de recuperación

En caso de que ocurra un incidente, se debe actuar de la siguiente manera:

Fallo en la base de datos: restaurar el backup más reciente y verificar que la información esté completa.

Caída del servidor: reiniciar el equipo; si no funciona, mover el sistema a un servidor alternativo.

Error humano: revisar los cambios realizados y restaurar archivos desde el repositorio Git o los respaldos.

Servicios externos caídos: esperar a que el proveedor los restablezca y, si es posible, usar métodos alternativos temporales.

Pérdida de datos: recuperar la información desde los respaldos.

6. Responsables

Administrador del sistema: encargado de los respaldos y restauración de datos.

Desarrolladores: encargados de corregir errores en el código.

Responsable legal o administrativo: encargado de avisar a los usuarios si ocurre un problema grave.

7. Conclusiones

El plan de contingencia es necesario para garantizar que el sistema pueda recuperarse de cualquier problema sin afectar gravemente a los usuarios ni perder información. Con las medidas preventivas y los procedimientos aquí definidos, se busca que el proyecto se mantenga confiable y seguro.
---

