<?php
/**
 * Español (es) — Watchtower module strings.
 * Missing keys fall back to lang/en/watchtower.php per-key.
 */
return [
    'title' => 'Watchtower',

    'nav' => [
        'dashboard' => 'Panel',
        'help'      => 'Ayuda',
    ],

    'dashboard' => [
        'heading'      => 'Resumen de atención',
        'refresh'      => 'Actualizar',
        'updated'      => 'Actualizado {time}',
    ],

    // Per-module card names shown in the card header (links to each module).
    'cards' => [
        'morning_checks' => 'Comprobaciones matutinas',
        'tickets'        => 'Tickets',
        'changes'        => 'Cambios',
        'calendar'       => 'Calendario',
        'service_status' => 'Estado del servicio',
        'contracts'      => 'Contratos',
        'knowledge'      => 'Conocimiento',
        'assets'         => 'Activos',
        'tasks'          => 'Tareas',
    ],

    // Morning Checks card.
    'mc' => [
        'metric_done' => 'Hechas',
        'metric_ok'   => 'OK',
        'metric_warn' => 'Aviso',
        'metric_fail' => 'Fallo',
        'not_started'      => 'Comprobaciones no iniciadas hoy',
        'pending'          => '{count} comprobaciones aún pendientes',
        'failed'           => '{count} comprobación(es) fallida(s)',
        'warnings'         => '{count} comprobación(es) con avisos',
        'all_passing'      => 'Todas las comprobaciones completadas y superadas',
    ],

    // Tickets card.
    'tickets' => [
        'metric_open'   => 'Abiertos',
        'metric_new'    => 'Nuevos',
        'metric_active' => 'Activos',
        'metric_hold'   => 'En espera',
        'urgent_high'   => '<span class="wt-attention-bold">{count}</span> tickets de prioridad urgente/alta',
        'unassigned'    => '<span class="wt-attention-bold">{count}</span> tickets sin asignar',
        'paused_one'    => '<span class="wt-attention-bold">{count}</span> ticket pausado más de {hours}h (reloj de SLA detenido)',
        'paused_many'   => '<span class="wt-attention-bold">{count}</span> tickets pausados más de {hours}h (reloj de SLA detenido)',
        'all_clear'     => 'No hay elementos urgentes',
    ],

    // Changes card.
    'changes' => [
        'metric_next_7d' => 'Próximos 7d',
        'metric_active'  => 'Activos',
        'metric_pending' => 'Pendientes',
        'awaiting'       => '<span class="wt-attention-bold">{count}</span> cambio(s) a la espera de aprobación',
        'in_progress'    => '{count} cambio(s) en curso ahora',
        'scheduled'      => '{count} cambio(s) programado(s) esta semana',
        'all_clear'      => 'No hay cambios próximos',
    ],

    // Calendar card.
    'calendar' => [
        'metric_today' => 'Hoy',
        'metric_week'  => 'Esta semana',
        'all_day'      => 'Todo el día',
        'no_events'    => 'No hay eventos hoy',
    ],

    // Service Status card.
    'service' => [
        'all_operational' => 'Todos los sistemas operativos',
        'active_incidents' => '<span class="wt-attention-bold">{count}</span> incidente(s) activo(s)',
    ],

    // Contracts card.
    'contracts' => [
        'metric_30d'     => '30 días',
        'metric_90d'     => '90 días',
        'metric_notices' => 'Avisos',
        'expiring'       => '<span class="wt-attention-bold">{count}</span> contrato(s) que vence(n) en 30 días',
        'notices'        => '<span class="wt-attention-bold">{count}</span> período(s) de preaviso próximo(s)',
        'all_clear'      => 'No hay contratos que requieran atención',
    ],

    // Knowledge card.
    'knowledge' => [
        'overdue'         => '<span class="wt-attention-bold">{count}</span> artículo(s) con revisión vencida',
        'published_week'  => 'Publicados esta semana',
        'up_to_date'      => 'Base de conocimiento al día',
    ],

    // Assets card.
    'assets' => [
        'metric_total'    => 'Total',
        'metric_offline'  => 'Sin conexión',
        'metric_warranty' => 'Garantía',
        'warranty'        => '<span class="wt-attention-bold">{count}</span> activo(s) con garantía vencida o que vence en {days} días',
        'offline'         => '<span class="wt-attention-bold">{count}</span> activo(s) no vistos en 7+ días',
        'all_active'      => 'Todos los activos activos recientemente',
    ],

    // Tasks card.
    'tasks' => [
        'metric_todo'   => 'Por hacer',
        'metric_active' => 'Activas',
        'overdue'       => '<span class="wt-attention-bold">{count}</span> tarea(s) vencida(s)',
        'due_today'     => '<span class="wt-attention-bold">{count}</span> vence(n) hoy',
        'all_clear'     => 'No hay tareas vencidas',
    ],

    // Help guide.
    'help' => [
        'page_title'   => 'Guía de Watchtower',
        'sidebar_label' => 'Guía',
        'hero_title'   => 'Guía de Watchtower',
        'hero_subtitle' => 'Un panel de atención unificado que muestra los elementos accionables de cada módulo de un solo vistazo.',

        'nav_overview'  => 'Visión general',
        'nav_layout'    => 'La disposición del panel',
        'nav_dots'      => 'Comprender los puntos de estado',
        'nav_cards'     => 'Tarjetas de módulo explicadas',
        'nav_refresh'   => 'Actualización automática',
        'nav_tips'      => 'Consejos rápidos',

        // Section 1 — Overview
        's1_title' => 'Visión general',
        's1_intro' => 'Watchtower es tu único panel de control para las operaciones de TI. En lugar de abrir cada módulo individualmente para comprobar los elementos urgentes, Watchtower reúne la información más importante de cada módulo en un solo panel. De un vistazo puedes ver qué necesita atención, qué funciona sin problemas y dónde centrar tu tiempo.',
        's1_feat1_title' => 'Tablón de atención',
        's1_feat1_desc'  => 'Ve qué necesita tu atención en todos los módulos en un solo lugar. Las comprobaciones matutinas, los tickets, los cambios, los eventos del calendario, el estado del servicio, los contratos, los artículos de conocimiento y los activos se resumen en una sola pantalla.',
        's1_feat2_title' => 'Estado con código de colores',
        's1_feat2_desc'  => 'Cada tarjeta de módulo muestra un punto de estado verde, ámbar o rojo para una clasificación instantánea. Puedes saber de un vistazo qué áreas están en buen estado, cuáles necesitan atención y cuáles requieren acción inmediata.',
        's1_feat3_title' => 'Actualización automática',
        's1_feat3_desc'  => 'El panel se actualiza automáticamente cada 5 minutos, por lo que la información se mantiene al día sin ninguna acción manual. Deja Watchtower abierto y se mantendrá actualizado en segundo plano.',
        's1_feat4_title' => 'Acceso directo',
        's1_feat4_desc'  => 'Salta directamente a cualquier módulo desde su tarjeta. El nombre de cada módulo es un enlace en el que se puede hacer clic que te lleva directamente al área relevante, para que puedas actuar sobre los problemas sin buscar la página correcta.',

        // Section 2 — Dashboard layout
        's2_title' => 'La disposición del panel',
        's2_p1' => 'El panel de Watchtower utiliza una cuadrícula adaptable de 3 columnas de tarjetas de módulo. En pantallas más pequeñas, la cuadrícula se adapta a 2 columnas o a una sola columna, por lo que funciona en cualquier dispositivo. Encima de la cuadrícula está la barra de título con un botón de actualización y una marca de tiempo «Actualizado» que muestra cuándo se obtuvieron los datos por última vez.',
        's2_p2' => 'Cada tarjeta de la cuadrícula sigue una estructura coherente para que puedas examinarlas rápidamente:',
        's2_diagram_name'   => 'Nombre del módulo',
        's2_diagram_open'   => 'ABIERTOS',
        's2_diagram_active' => 'ACTIVOS',
        's2_diagram_hold'   => 'EN ESPERA',
        's2_diagram_clear'  => 'Todo en orden — no hay elementos urgentes',
        's2_field_icon'    => '<strong>Icono de color</strong> &mdash; un pequeño icono cuadrado en el color temático del módulo (verde azulado para Comprobaciones matutinas, azul para Tickets, etc.) para que puedas identificar cada tarjeta al instante.',
        's2_field_name'    => '<strong>Nombre del módulo</strong> &mdash; un enlace en el que se puede hacer clic que navega directamente a ese módulo. Haz clic para entrar directamente y actuar.',
        's2_field_dot'     => '<strong>Punto de estado</strong> &mdash; un punto verde, ámbar o rojo en la esquina superior derecha que muestra el nivel general de urgencia de ese módulo.',
        's2_field_metrics' => '<strong>Métricas clave</strong> &mdash; números grandes que resumen los recuentos más importantes (p. ej. tickets abiertos, comprobaciones completadas, contratos que vencen).',
        's2_field_attention' => '<strong>Elementos de atención</strong> &mdash; filas de mensajes con código de colores que destacan qué necesita específicamente tu atención dentro de ese módulo.',
        's2_tip' => 'La disposición de las tarjetas está diseñada para el examen rápido, no para el análisis profundo. Utiliza Watchtower para identificar qué módulos necesitan tu atención y, a continuación, haz clic para ir al módulo en sí y ver todos los detalles.',

        // Section 3 — Status dots
        's3_title' => 'Comprender los puntos de estado',
        's3_intro' => 'Cada tarjeta de módulo muestra un punto de estado en su encabezado. Este punto proporciona un indicador visual instantáneo de si esa área de tus operaciones de TI necesita atención. El color se determina automáticamente en función de los datos devueltos por cada módulo.',
        's3_green_label' => 'Verde',
        's3_green_desc'  => 'Todo está bien. No se necesita ninguna acción. El módulo está en buen estado, sin problemas pendientes ni elementos que requieran atención.',
        's3_green_examples' => '<strong>Ejemplos:</strong> Todas las comprobaciones matutinas superadas, sin tickets urgentes, todos los sistemas operativos, sin contratos que venzan próximamente.',
        's3_amber_label' => 'Ámbar',
        's3_amber_desc'  => 'Algo necesita atención pero no es crítico. Hay elementos que deberías revisar cuando tengas ocasión, pero nada es urgente.',
        's3_amber_examples' => '<strong>Ejemplos:</strong> Comprobaciones con avisos, tickets sin asignar, cambios a la espera de aprobación, contratos que vencen en 90 días.',
        's3_red_label' => 'Rojo',
        's3_red_desc'  => 'Hay elementos urgentes que requieren acción inmediata. Algo ha fallado, está vencido o se ve afectado de forma crítica y debe abordarse de inmediato.',
        's3_red_examples' => '<strong>Ejemplos:</strong> Comprobaciones matutinas no iniciadas o fallidas, tickets de prioridad urgente/alta, interrupciones graves del servicio, contratos que vencen en 30 días.',
        's3_tip' => 'Piensa en los puntos como un semáforo. El verde significa que puedes seguir con tu día, el ámbar significa revisar cuando sea posible y el rojo significa detener lo que estás haciendo e investigar. El objetivo es mantener todos los puntos en verde.',

        // Section 4 — Module cards explained
        's4_title' => 'Tarjetas de módulo explicadas',
        's4_intro' => 'Watchtower supervisa ocho módulos. Cada tarjeta está adaptada para mostrar la información más relevante de esa área. Esto es lo que muestra cada tarjeta y qué determina el color de su punto de estado.',
        's4_mc_title'    => 'Comprobaciones matutinas',
        's4_mc_desc'     => 'Muestra el progreso de finalización (p. ej. 8/10 hechas) más los recuentos de resultados OK, Aviso y Fallo. Los elementos de atención señalan cuándo no se han iniciado las comprobaciones o cuándo alguna ha fallado.',
        's4_mc_triggers' => '<strong>Rojo:</strong> Comprobaciones no iniciadas hoy o alguna comprobación fallida. <strong>Ámbar:</strong> Comprobaciones incompletas o avisos presentes. <strong>Verde:</strong> Todas las comprobaciones completadas y superadas.',
        's4_tk_title'    => 'Tickets',
        's4_tk_desc'     => 'Muestra el recuento total de abiertos desglosado en Nuevos, Activos y En espera. Los elementos de atención destacan los tickets de prioridad urgente/alta y los que están sin asignar.',
        's4_tk_triggers' => '<strong>Rojo:</strong> Existen tickets de prioridad urgente o alta. <strong>Ámbar:</strong> Hay tickets sin asignar. <strong>Verde:</strong> No hay elementos urgentes ni tickets sin asignar.',
        's4_ch_title'    => 'Cambios',
        's4_ch_desc'     => 'Muestra el número de cambios programados en los próximos 7 días, cuántos están actualmente en curso y cuántos están pendientes de aprobación. Los elementos de atención señalan los cambios no aprobados y activos.',
        's4_ch_triggers' => '<strong>Ámbar:</strong> Cambios a la espera de aprobación. <strong>Verde:</strong> No hay cambios sin aprobar.',
        's4_cal_title'    => 'Calendario',
        's4_cal_desc'     => 'Muestra el número de eventos de hoy y de esta semana. Si hay eventos hoy, se enumeran con sus horas (o «Todo el día» para los eventos de día completo).',
        's4_cal_triggers' => '<strong>Ámbar:</strong> Eventos programados para hoy. <strong>Verde:</strong> No hay eventos hoy.',
        's4_ss_title'    => 'Estado del servicio',
        's4_ss_desc'     => 'Muestra el recuento de incidentes activos y enumera los servicios afectados con sus distintivos de nivel de impacto (Interrupción grave, Interrupción parcial, Degradado, Mantenimiento). Cuando todo está en buen estado, aparece un banner verde «Todos los sistemas operativos».',
        's4_ss_triggers' => '<strong>Rojo:</strong> Interrupción grave o parcial en cualquier servicio. <strong>Ámbar:</strong> Estado degradado o de mantenimiento. <strong>Verde:</strong> Todos los sistemas operativos.',
        's4_ct_title'    => 'Contratos',
        's4_ct_desc'     => 'Muestra los contratos que vencen en 30 días, en 90 días y los períodos de preaviso próximos. Los elementos de atención advierten sobre vencimientos inminentes y plazos de preaviso próximos.',
        's4_ct_triggers' => '<strong>Rojo:</strong> Contratos que vencen en 30 días. <strong>Ámbar:</strong> Contratos que vencen en 90 días o períodos de preaviso próximos. <strong>Verde:</strong> No hay contratos que requieran atención.',
        's4_kb_title'    => 'Conocimiento',
        's4_kb_desc'     => 'Muestra el número de artículos con revisión vencida y enumera los artículos publicados recientemente esta semana. Cuando no hay revisiones vencidas y la base de conocimiento está al día, la tarjeta muestra un mensaje de todo en orden.',
        's4_kb_triggers' => '<strong>Ámbar:</strong> Artículos con revisión vencida. <strong>Verde:</strong> Base de conocimiento al día.',
        's4_as_title'    => 'Activos',
        's4_as_desc'     => 'Muestra el número total de activos rastreados y cuántos no se han visto en 7 o más días. Esto ayuda a identificar dispositivos que pueden estar sin conexión, dados de baja o perdidos.',
        's4_as_triggers' => '<strong>Ámbar:</strong> Activos no vistos en 7+ días. <strong>Verde:</strong> Todos los activos activos recientemente.',

        // Section 5 — Auto-refresh
        's5_title' => 'Actualización automática y manual',
        's5_intro' => 'Watchtower está diseñado para ser una herramienta de supervisión pasiva que puedes dejar abierta en una pestaña del navegador durante todo el día. El panel se mantiene al día mediante ciclos de actualización automática.',
        's5_step1' => '<strong>Actualización automática</strong> &mdash; el panel obtiene datos nuevos de todos los módulos cada 5 minutos. No necesitas recargar la página ni hacer clic en nada; las tarjetas y los puntos de estado se actualizan en silencio en segundo plano.',
        's5_step2' => '<strong>Actualización manual</strong> &mdash; haz clic en el botón <strong>Actualizar</strong> de la esquina superior derecha para obtener los datos más recientes de inmediato. El icono del botón gira mientras la solicitud está en curso, confirmando que se están cargando nuevos datos.',
        's5_step3' => '<strong>Marca de tiempo de actualización</strong> &mdash; junto al botón de actualización, una marca de tiempo muestra la última vez que se obtuvieron los datos (p. ej. «Actualizado 09:15»). Esto te indica exactamente cuán actual es la información mostrada.',
        's5_tip' => 'Mantén Watchtower abierto en una pestaña del navegador dedicada para la supervisión pasiva. El ciclo de actualización de 5 minutos significa que siempre tienes una vista casi en tiempo real de tus operaciones de TI sin necesidad de comprobar manualmente cada módulo.',

        // Section 6 — Quick tips
        's6_title' => 'Consejos rápidos',
        's6_tip1_title' => 'Empieza tu día aquí',
        's6_tip1_desc'  => 'Abre Watchtower a primera hora cada mañana para obtener una visión operativa rápida. En segundos puedes ver si las comprobaciones matutinas están hechas, si hay tickets urgentes y si todos los servicios están en buen estado.',
        's6_tip2_title' => 'Los puntos rojos primero',
        's6_tip2_desc'  => 'Atiende los puntos de estado rojos antes que nada. Estos indican elementos urgentes que necesitan atención inmediata &mdash; comprobaciones fallidas, tickets de alta prioridad o interrupciones del servicio que afectan activamente a los usuarios.',
        's6_tip3_title' => 'Haz clic para entrar',
        's6_tip3_desc'  => 'Haz clic en el nombre de cualquier módulo de una tarjeta para navegar directamente a ese módulo. No es necesario usar el menú principal ni la navegación de cuadrícula &mdash; Watchtower actúa como un acceso directo a donde se necesita atención.',
        's6_tip4_title' => 'Pulsa Actualizar para lo más reciente',
        's6_tip4_desc'  => 'Aunque el panel se actualiza automáticamente cada 5 minutos, puedes hacer clic en el botón Actualizar siempre que quieras los datos más recientes. Es útil tras resolver un problema para confirmar que el punto de estado ha cambiado.',
        's6_tip5_title' => 'Úsalo en las reuniones de equipo',
        's6_tip5_desc'  => 'Proyecta Watchtower en una pantalla durante las reuniones de seguimiento o de revisión operativa. Los puntos con código de colores facilitan debatir qué áreas necesitan atención y asignar la responsabilidad de los elementos ámbar o rojos.',
        's6_tip6_title' => 'El verde significa todo en orden',
        's6_tip6_desc'  => 'Cuando todos los puntos del panel están en verde, tus operaciones de TI están en buena forma. Sin tickets urgentes, sin comprobaciones fallidas, sin contratos que venzan y todos los servicios operativos. Ese es el objetivo.',
    ],
];
