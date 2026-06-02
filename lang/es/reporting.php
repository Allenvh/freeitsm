<?php
/**
 * Español (es) — Reporting module strings.
 * Missing keys fall back to lang/en/reporting.php per-key.
 */
return [
    'title' => 'Informes',

    'nav' => [
        'logs'    => 'Registros',
        'tickets' => 'Tickets',
        'intune'  => 'Intune',
        'help'    => 'Ayuda',

        'logs_title'    => 'Registros del sistema',
        'tickets_title' => 'Paneles de tickets',
        'intune_title'  => 'Panel de Intune',
        'help_title'    => 'Ayuda',
    ],

    'landing' => [
        'heading'  => 'Informes',
        'subtitle' => 'Elige un área de informes para comenzar',

        'logs_title'    => 'Registros del sistema',
        'logs_desc'     => 'Consulta los intentos de inicio de sesión, las importaciones de correo y otros registros de actividad del sistema.',
        'tickets_title' => 'Paneles de tickets',
        'tickets_desc'  => 'Paneles de KPI para el rendimiento de los tickets, los tiempos de resolución y la carga de trabajo del equipo.',
        'intune_title'  => 'Panel de Intune',
        'intune_desc'   => 'Cumplimiento, cifrado, distribución del sistema operativo, tendencia de inscripción y estado de la última sincronización en todos los dispositivos gestionados.',
    ],

    'logs' => [
        'heading'  => 'Registros del sistema',
        'refresh'  => 'Actualizar',
        'tab_login'        => 'Inicios de sesión de usuarios',
        'tab_email_import' => 'Importaciones de correo',

        'loading'        => 'Cargando registros...',
        'no_logs'        => 'No se han encontrado registros',
        'load_error'     => 'Error al cargar los registros: {error}',

        'col_datetime'    => 'Fecha/hora',
        'col_username'    => 'Nombre de usuario',
        'col_status'      => 'Estado',
        'col_ip'          => 'Dirección IP',
        'col_user_agent'  => 'Agente de usuario',
        'col_from'        => 'De',
        'col_subject'     => 'Asunto',
        'col_type'        => 'Tipo',
        'col_attachments' => 'Adjuntos',

        'status_success' => 'Correcto',
        'status_failed'  => 'Fallido',
        'unknown'        => 'Desconocido',
        'no_subject'     => '(Sin asunto)',
        'new_ticket'     => 'Nuevo ticket',
        'reply'          => 'Respuesta',
        'none'           => 'Ninguno',

        'row_title'  => 'Haz clic para ver los detalles en JSON',

        'pagination' => 'Página {current} de {total} ({count} en total)',
        'prev'       => 'Anterior',
        'next'       => 'Siguiente',

        'modal_title' => 'Detalles del registro (JSON)',
        'close'       => 'Cerrar',
    ],

    'tickets' => [
        'heading' => 'Paneles de tickets',
        'coming_soon' => 'Los paneles de KPI y los informes sobre el rendimiento de los tickets, los tiempos de resolución y la carga de trabajo del equipo estarán disponibles aquí muy pronto.',
    ],

    'intune' => [
        'heading'      => 'Panel de Intune',
        'loading_meta' => 'Cargando…',
        'refresh'      => 'Actualizar',
        'refresh_title'=> 'Actualizar datos',
        'loading_data' => 'Cargando datos de Intune…',

        'last_sync'    => 'Última sincronización: {when}',
        'error'        => 'Error: {error}',
        'load_failed'  => 'No se pudo cargar el panel: {error}',
        'no_devices_title' => 'No se han encontrado dispositivos de Intune.',
        'no_devices_body'  => 'Ejecuta una sincronización de Intune desde el módulo de Activos para importar dispositivos y luego vuelve aquí.',
        'no_data'      => 'Sin datos',
        'unknown'      => 'Desconocido',

        // KPI strip
        'kpi_total'            => 'Total de dispositivos',
        'kpi_total_sub'        => 'Todos los dispositivos gestionados',
        'kpi_compliant'        => 'Conformes',
        'kpi_compliant_sub'    => '{count} de {total}',
        'kpi_encrypted'        => 'Cifrados',
        'kpi_encrypted_sub'    => '{count} de {total}',
        'kpi_stale'            => 'Obsoletos (más de 30 días)',
        'kpi_stale_sub'        => 'Sin sincronizar en los últimos 30 días',
        'kpi_enrolled'         => 'Inscritos (30 días)',
        'kpi_enrolled_sub'     => 'Nuevos en los últimos 30 días',

        'kpi_compliant_drill'  => 'Dispositivos conformes',
        'kpi_encrypted_drill'  => 'Dispositivos cifrados',
        'kpi_stale_drill'      => 'Obsoletos (más de 30 días)',
        'kpi_enrolled_drill'   => 'Inscritos en los últimos 30 días',

        // Widgets
        'w_compliance_title'   => 'Desglose de cumplimiento',
        'w_compliance_desc'    => 'Dispositivos por estado de cumplimiento',
        'w_os_title'           => 'Sistema operativo',
        'w_os_desc'            => 'Dispositivos agrupados por sistema operativo',
        'w_owner_title'        => 'Tipo de propietario',
        'w_owner_desc'         => 'Dispositivos corporativos frente a personales',
        'w_manufacturers_title'=> 'Principales fabricantes',
        'w_manufacturers_desc' => 'Dispositivos por fabricante (10 principales)',
        'w_os_versions_title'  => 'Principales versiones del sistema operativo',
        'w_os_versions_desc'   => 'Combinaciones de sistema operativo y versión más comunes',
        'w_last_sync_title'    => 'Intervalo de la última sincronización',
        'w_last_sync_desc'     => 'Hace cuánto los dispositivos se han registrado',
        'w_enrolment_title'    => 'Inscripciones (últimos 90 días)',
        'w_enrolment_desc'     => 'Nuevos dispositivos inscritos por día',
        'w_encryption_title'   => 'Cifrado por sistema operativo',
        'w_encryption_desc'    => 'Cifrados frente a sin cifrar, por sistema operativo',

        // Chart tooltips / labels
        'tooltip_enrolled'     => '{count} inscritos (haz clic para profundizar)',
        'drill_enrolled_on'    => 'Inscrito el {date}',

        // Drill-down modal
        'drill_devices'        => 'Dispositivos',
        'drill_loading'        => 'Cargando…',
        'drill_count'          => '{count} dispositivo',
        'drill_count_plural'   => '{count} dispositivos',
        'drill_no_match'       => 'Ningún dispositivo coincide con este filtro.',
        'drill_error'          => 'Error: {error}',
        'drill_load_failed'    => 'No se pudo cargar: {error}',
        'drill_page_info'      => 'Página {current} de {total}',
        'drill_prev'           => '‹ Anterior',
        'drill_next'           => 'Siguiente ›',
        'drill_export'         => 'Exportar CSV',
        'drill_close'          => 'Cerrar',

        'drill_col_device'     => 'Dispositivo',
        'drill_col_user'       => 'Usuario',
        'drill_col_os'         => 'Sistema operativo',
        'drill_col_compliance' => 'Cumplimiento',
        'drill_col_encrypted'  => 'Cifrado',
        'drill_col_last_sync'  => 'Última sincronización',

        'never'                => 'Nunca',
        'yes'                  => 'Sí',
        'no'                   => 'No',
    ],

    'help' => [
        'page_title' => 'Guía de informes',
        'guide'      => 'Guía',

        'hero_heading' => 'Guía de informes',
        'hero_sub'     => 'Convierte los datos de tu mesa de servicio en información práctica con registros, análisis y paneles.',

        'nav_overview'           => 'Visión general',
        'nav_ticket_reports'     => 'Informes de tickets',
        'nav_system_logs'        => 'Registros del sistema',
        'nav_understanding_data' => 'Comprender los datos',
        'nav_settings_filters'   => 'Ajustes y filtros',
        'nav_tips'               => 'Consejos rápidos',

        // Section 1: Overview
        's1_heading' => 'Visión general',
        's1_intro'   => 'El módulo de Informes reúne en un solo lugar todo lo que ocurre en tu mesa de servicio. Haz un seguimiento del rendimiento de los tickets, supervisa la actividad del sistema, revisa los intentos de inicio de sesión y audita las importaciones de correo, todo desde un único módulo diseñado para ayudarte a detectar tendencias y tomar decisiones basadas en datos.',
        's1_card1_title' => 'Análisis de tickets',
        's1_card1_body'  => 'Visualiza el volumen de tickets, los tiempos de resolución, el cumplimiento de los SLA y la carga de trabajo del equipo mediante paneles interactivos que se actualizan en tiempo real.',
        's1_card2_title' => 'Registros del sistema',
        's1_card2_body'  => 'Revisa cada intento de inicio de sesión, importación de correo y evento del sistema en una tabla que se puede buscar y filtrar, con marcas de tiempo e indicadores de estado.',
        's1_card3_title' => 'Seguimiento de la actividad',
        's1_card3_body'  => 'Supervisa la actividad de los analistas en toda la plataforma: quién inicia sesión, en qué tickets se trabaja y en qué se invierte el tiempo.',
        's1_card4_title' => 'Pista de auditoría',
        's1_card4_body'  => 'Cada acción se registra con quién la realizó, cuándo y qué cambió. Esencial para el cumplimiento, las revisiones de seguridad y la resolución de problemas.',

        // Section 2: Ticket reports
        's2_heading' => 'Informes de tickets',
        's2_intro'   => 'El área de Tickets de los informes ofrece paneles de KPI que te dan una imagen clara del rendimiento de tu mesa de servicio. Estos paneles extraen los datos directamente de los registros de tus tickets y los presentan mediante gráficos y tarjetas de resumen.',
        's2_card1_title' => 'Volumen de tickets',
        's2_card1_body'  => 'Comprueba cuántos tickets se crean, se resuelven y siguen abiertos en cualquier periodo de tiempo. Identifica los días de mayor actividad y los patrones estacionales.',
        's2_card2_title' => 'Cumplimiento de los SLA',
        's2_card2_body'  => 'Haz un seguimiento del porcentaje de tickets que cumplen sus objetivos de respuesta y resolución. Profundiza por prioridad o categoría para encontrar las áreas problemáticas.',
        's2_card3_title' => 'Tiempos de resolución',
        's2_card3_body'  => 'Mide el tiempo medio y mediano para resolver los tickets. Compara entre equipos, categorías o niveles de prioridad para detectar cuellos de botella.',
        's2_card4_title' => 'Carga de trabajo del equipo',
        's2_card4_body'  => 'Comprueba cómo se distribuyen los tickets entre los analistas. Identifica quién está sobrecargado y quién tiene capacidad para asumir más trabajo.',
        's2_card5_title' => 'Desglose por categoría',
        's2_card5_body'  => 'Comprende qué tipos de incidencias generan más tickets. Úsalo para orientar la formación, la documentación o las mejoras de autoservicio.',
        's2_card6_title' => 'Análisis de tendencias',
        's2_card6_body'  => 'Consulta los datos de los tickets a lo largo de semanas, meses o trimestres para detectar tendencias a largo plazo y medir el impacto de las mejoras de los procesos.',
        's2_tip'         => 'Se accede a los paneles de tickets a través de la pestaña Tickets en la navegación del encabezado. Usa los filtros de intervalo de fechas para comparar distintos periodos en paralelo.',

        // Section 3: System logs
        's3_heading' => 'Registros del sistema',
        's3_intro'   => 'El área de Registros captura todo lo que ocurre entre bastidores en tu instancia de FreeITSM. Cada intento de inicio de sesión, importación de correo y evento del sistema se registra con una marca de tiempo y un estado, de modo que siempre tengas una imagen completa de la actividad de la plataforma.',
        's3_badge_login'  => 'INICIO DE SESIÓN',
        's3_badge_email'  => 'CORREO',
        's3_badge_system' => 'SISTEMA',
        's3_badge_audit'  => 'AUDITORÍA',
        's3_login_title'  => 'Intentos de inicio de sesión',
        's3_login_body'   => 'Cada inicio de sesión correcto y fallido se registra con el nombre del analista, la dirección IP y la marca de tiempo. Los intentos fallidos se marcan en rojo para que puedas detectar rápidamente intentos de acceso no autorizados o usuarios bloqueados.',
        's3_email_title'  => 'Importaciones de correo',
        's3_email_body'   => 'Cuando el sistema procesa los correos entrantes para convertirlos en tickets, cada importación se registra con la dirección del remitente, la línea de asunto y si se convirtió correctamente. Las importaciones fallidas muestran el motivo para que puedas investigar los mensajes rechazados o mal formados.',
        's3_system_title' => 'Eventos del sistema',
        's3_system_body'  => 'Aquí se capturan los procesos en segundo plano, las tareas programadas, los cambios de configuración y la actividad de la API. Usa estos registros para verificar que los trabajos automatizados se ejecutan correctamente y para diagnosticar problemas.',
        's3_audit_title'  => 'Entradas de auditoría',
        's3_audit_body'   => 'Seguimiento de cambios a nivel de campo en toda la plataforma. Comprueba exactamente quién cambió qué, cuándo y cuál era el valor anterior. Inestimable para los requisitos de cumplimiento y la resolución de disputas.',
        's3_step1_title' => 'Abre la pestaña Registros',
        's3_step1_body'  => 'haz clic en Registros en la navegación del encabezado para acceder al visor de registros del sistema.',
        's3_step2_title' => 'Cambia entre tipos de registro',
        's3_step2_body'  => 'usa la barra de pestañas de la parte superior para filtrar por intentos de inicio de sesión, importaciones de correo o eventos del sistema.',
        's3_step3_title' => 'Revisa los detalles',
        's3_step3_body'  => 'cada fila muestra una marca de tiempo, una insignia de estado (correcto o fallido) y detalles contextuales como direcciones IP, asuntos de correo o descripciones de eventos.',
        's3_tip'         => 'Revisa con regularidad los registros de inicio de sesión en busca de intentos fallidos repetidos desde direcciones IP desconocidas. Esto puede indicar ataques de fuerza bruta o credenciales comprometidas que requieren atención inmediata.',

        // Section 4: Understanding the data
        's4_heading' => 'Comprender los datos',
        's4_intro'   => 'Los datos en bruto solo resultan útiles cuando sabes qué buscar. Estas son las métricas clave que debes vigilar y cómo interpretarlas para impulsar mejoras reales en las operaciones de tu mesa de servicio.',
        's4_metric1_title' => 'Tiempo de primera respuesta',
        's4_metric1_body'  => 'Cuánto esperan los usuarios antes de que un analista atienda su ticket. Una tendencia al alza aquí significa que tu equipo puede estar falto de personal o que los tickets no se enrutan de forma eficaz. Objetivo: por debajo de tu umbral de SLA.',
        's4_metric2_title' => 'Tasa de resolución',
        's4_metric2_body'  => 'El porcentaje de tickets resueltos en un periodo determinado frente a los creados. Si entran más tickets de los que salen, tu acumulación está creciendo y debes investigar la causa.',
        's4_metric3_title' => 'Contactos repetidos',
        's4_metric3_body'  => 'Tickets reabiertos o usuarios que plantean la misma incidencia varias veces. Las tasas altas de contactos repetidos sugieren que no se aborda la causa raíz o que las soluciones no se comunican con claridad.',
        's4_metric4_title' => 'Focos por categoría',
        's4_metric4_body'  => 'Qué categorías generan más tickets a lo largo del tiempo. Un pico en una categoría concreta puede indicar un sistema que falla, una mala actualización de software o una carencia en la formación de los usuarios que debe abordarse.',
        's4_combine'     => 'Usa estas métricas en conjunto en lugar de por separado. Por ejemplo, una tasa de resolución alta combinada con una tasa alta de contactos repetidos puede indicar que los tickets se cierran demasiado rápido sin resolver el problema subyacente.',
        's4_tip'         => 'Programa una revisión semanal de tus métricas clave con el equipo. Los patrones invisibles en el día a día a menudo se vuelven evidentes cuando se observan con una frecuencia semanal o mensual.',

        // Section 5: Settings & filters
        's5_heading' => 'Ajustes y filtros',
        's5_intro'   => 'Tanto el visor de registros como los paneles de tickets admiten una serie de filtros para ayudarte a acotar exactamente los datos que necesitas. Un uso eficaz de los filtros convierte un muro de datos en información dirigida y práctica.',
        's5_step1_title' => 'Intervalos de fechas',
        's5_step1_body'  => 'filtra los registros y los informes a una ventana de tiempo concreta. Usa intervalos predefinidos (hoy, esta semana, este mes) o establece fechas de inicio y fin personalizadas para un control preciso.',
        's5_step2_title' => 'Filtros de estado',
        's5_step2_body'  => 'en el visor de registros, filtra por estado correcto o fallido para aislar problemas rápidamente. En los informes de tickets, filtra por estado abierto, resuelto o cerrado.',
        's5_step3_title' => 'Búsqueda',
        's5_step3_body'  => 'usa el cuadro de búsqueda para encontrar entradas concretas por palabra clave. En los registros, esto busca en los nombres de los analistas, las direcciones IP, los asuntos de correo y las descripciones de eventos.',
        's5_step4_title' => 'Agrupación temporal',
        's5_step4_body'  => 'en los paneles de tickets, agrupa los datos por día, semana o mes para cambiar la granularidad de tus gráficos. Las vistas diarias muestran los picos a corto plazo; las vistas mensuales revelan las tendencias a largo plazo.',
        's5_step5_title' => 'Filtros por departamento',
        's5_step5_body'  => 'acota los resultados del panel a un departamento concreto para comparar el rendimiento entre distintas partes de la organización.',
        's5_tip'         => 'Combina varios filtros para un análisis dirigido. Por ejemplo, filtra por un departamento concreto y un intervalo de fechas para ver cómo un cambio de proceso reciente afectó al volumen de tickets de ese equipo.',

        // Section 6: Quick tips
        's6_heading' => 'Consejos rápidos',
        's6_tip1_title' => 'Revisa con regularidad',
        's6_tip1_body'  => 'Los informes son más valiosos cuando se revisan de forma constante. Establece una frecuencia (semanal para las métricas operativas, mensual para el análisis de tendencias) y cúmplela.',
        's6_tip2_title' => 'Investiga las anomalías',
        's6_tip2_body'  => 'Un pico o una caída repentina en cualquier métrica es una señal que merece investigarse. Consulta los registros para obtener contexto: ¿hubo una interrupción del sistema, un despliegue de software o un cambio de personal?',
        's6_tip3_title' => 'Compara periodos',
        's6_tip3_body'  => 'Usa los filtros de fechas para comparar esta semana con la anterior, o este mes con el mismo mes del año pasado. Las comparaciones relativas revelan mejoras o retrocesos con más claridad que las cifras en bruto.',
        's6_tip4_title' => 'Supervisa la seguridad',
        's6_tip4_body'  => 'Vigila los intentos fallidos de inicio de sesión en los registros del sistema. Los fallos repetidos desde la misma dirección IP o contra la misma cuenta pueden indicar un problema de seguridad que requiere escalado.',
    ],
];
