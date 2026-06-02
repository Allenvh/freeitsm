<?php
/**
 * Italiano (it) — Reporting module strings.
 * Missing keys fall back to lang/en/reporting.php per-key.
 */
return [
    'title' => 'Reportistica',

    'nav' => [
        'logs'    => 'Log',
        'tickets' => 'Ticket',
        'intune'  => 'Intune',
        'help'    => 'Aiuto',

        'logs_title'    => 'Log di sistema',
        'tickets_title' => 'Dashboard dei ticket',
        'intune_title'  => 'Dashboard Intune',
        'help_title'    => 'Aiuto',
    ],

    'landing' => [
        'heading'  => 'Reportistica',
        'subtitle' => 'Scegli un\'area di reportistica per iniziare',

        'logs_title'    => 'Log di sistema',
        'logs_desc'     => 'Visualizza i tentativi di accesso, le importazioni email e altri log di attività del sistema.',
        'tickets_title' => 'Dashboard dei ticket',
        'tickets_desc'  => 'Dashboard KPI per le prestazioni dei ticket, i tempi di risoluzione e il carico di lavoro del team.',
        'intune_title'  => 'Dashboard Intune',
        'intune_desc'   => 'Conformità, crittografia, distribuzione dei sistemi operativi, andamento delle registrazioni e stato dell\'ultima sincronizzazione per ogni dispositivo gestito.',
    ],

    'logs' => [
        'heading'  => 'Log di sistema',
        'refresh'  => 'Aggiorna',
        'tab_login'        => 'Accessi utente',
        'tab_email_import' => 'Importazioni email',

        'loading'        => 'Caricamento dei log...',
        'no_logs'        => 'Nessun log trovato',
        'load_error'     => 'Errore durante il caricamento dei log: {error}',

        'col_datetime'    => 'Data/ora',
        'col_username'    => 'Nome utente',
        'col_status'      => 'Stato',
        'col_ip'          => 'Indirizzo IP',
        'col_user_agent'  => 'User agent',
        'col_from'        => 'Da',
        'col_subject'     => 'Oggetto',
        'col_type'        => 'Tipo',
        'col_attachments' => 'Allegati',

        'status_success' => 'Riuscito',
        'status_failed'  => 'Non riuscito',
        'unknown'        => 'Sconosciuto',
        'no_subject'     => '(Nessun oggetto)',
        'new_ticket'     => 'Nuovo ticket',
        'reply'          => 'Risposta',
        'none'           => 'Nessuno',

        'row_title'  => 'Clicca per visualizzare i dettagli JSON',

        'pagination' => 'Pagina {current} di {total} ({count} totali)',
        'prev'       => 'Precedente',
        'next'       => 'Successivo',

        'modal_title' => 'Dettagli del log (JSON)',
        'close'       => 'Chiudi',
    ],

    'tickets' => [
        'heading' => 'Dashboard dei ticket',
        'coming_soon' => 'Le dashboard KPI e la reportistica per le prestazioni dei ticket, i tempi di risoluzione e il carico di lavoro del team saranno presto disponibili qui.',
    ],

    'intune' => [
        'heading'      => 'Dashboard Intune',
        'loading_meta' => 'Caricamento…',
        'refresh'      => 'Aggiorna',
        'refresh_title'=> 'Aggiorna i dati',
        'loading_data' => 'Caricamento dei dati Intune…',

        'last_sync'    => 'Ultima sincronizzazione: {when}',
        'error'        => 'Errore: {error}',
        'load_failed'  => 'Impossibile caricare la dashboard: {error}',
        'no_devices_title' => 'Nessun dispositivo Intune trovato.',
        'no_devices_body'  => 'Esegui una sincronizzazione Intune dal modulo Asset per importare i dispositivi, poi torna qui.',
        'no_data'      => 'Nessun dato',
        'unknown'      => 'Sconosciuto',

        // KPI strip
        'kpi_total'            => 'Dispositivi totali',
        'kpi_total_sub'        => 'Tutti i dispositivi gestiti',
        'kpi_compliant'        => 'Conformi',
        'kpi_compliant_sub'    => '{count} di {total}',
        'kpi_encrypted'        => 'Crittografati',
        'kpi_encrypted_sub'    => '{count} di {total}',
        'kpi_stale'            => 'Obsoleti (30+ giorni)',
        'kpi_stale_sub'        => 'Nessuna sincronizzazione negli ultimi 30 giorni',
        'kpi_enrolled'         => 'Registrati (30 giorni)',
        'kpi_enrolled_sub'     => 'Nuovi negli ultimi 30 giorni',

        'kpi_compliant_drill'  => 'Dispositivi conformi',
        'kpi_encrypted_drill'  => 'Dispositivi crittografati',
        'kpi_stale_drill'      => 'Obsoleti (30+ giorni)',
        'kpi_enrolled_drill'   => 'Registrati negli ultimi 30 giorni',

        // Widgets
        'w_compliance_title'   => 'Ripartizione della conformità',
        'w_compliance_desc'    => 'Dispositivi per stato di conformità',
        'w_os_title'           => 'Sistema operativo',
        'w_os_desc'            => 'Dispositivi raggruppati per sistema operativo',
        'w_owner_title'        => 'Tipo di proprietario',
        'w_owner_desc'         => 'Dispositivi aziendali e personali',
        'w_manufacturers_title'=> 'Produttori principali',
        'w_manufacturers_desc' => 'Dispositivi per produttore (primi 10)',
        'w_os_versions_title'  => 'Versioni OS principali',
        'w_os_versions_desc'   => 'Combinazioni OS + versione più comuni',
        'w_last_sync_title'    => 'Finestra dell\'ultima sincronizzazione',
        'w_last_sync_desc'     => 'Quanto di recente i dispositivi si sono sincronizzati',
        'w_enrolment_title'    => 'Registrazioni (ultimi 90 giorni)',
        'w_enrolment_desc'     => 'Nuovi dispositivi registrati al giorno',
        'w_encryption_title'   => 'Crittografia per sistema operativo',
        'w_encryption_desc'    => 'Crittografati e non crittografati, per sistema operativo',

        // Chart tooltips / labels
        'tooltip_enrolled'     => '{count} registrati (clicca per il dettaglio)',
        'drill_enrolled_on'    => 'Registrati il {date}',

        // Drill-down modal
        'drill_devices'        => 'Dispositivi',
        'drill_loading'        => 'Caricamento…',
        'drill_count'          => '{count} dispositivo',
        'drill_count_plural'   => '{count} dispositivi',
        'drill_no_match'       => 'Nessun dispositivo corrisponde a questo filtro.',
        'drill_error'          => 'Errore: {error}',
        'drill_load_failed'    => 'Impossibile caricare: {error}',
        'drill_page_info'      => 'Pagina {current} di {total}',
        'drill_prev'           => '‹ Prec',
        'drill_next'           => 'Succ ›',
        'drill_export'         => 'Esporta CSV',
        'drill_close'          => 'Chiudi',

        'drill_col_device'     => 'Dispositivo',
        'drill_col_user'       => 'Utente',
        'drill_col_os'         => 'Sistema operativo',
        'drill_col_compliance' => 'Conformità',
        'drill_col_encrypted'  => 'Crittografato',
        'drill_col_last_sync'  => 'Ultima sincronizzazione',

        'never'                => 'Mai',
        'yes'                  => 'Sì',
        'no'                   => 'No',
    ],

    'help' => [
        'page_title' => 'Guida alla reportistica',
        'guide'      => 'Guida',

        'hero_heading' => 'Guida alla reportistica',
        'hero_sub'     => 'Trasforma i dati del tuo service desk in informazioni utili con log, analisi e dashboard.',

        'nav_overview'           => 'Panoramica',
        'nav_ticket_reports'     => 'Report dei ticket',
        'nav_system_logs'        => 'Log di sistema',
        'nav_understanding_data' => 'Comprendere i dati',
        'nav_settings_filters'   => 'Impostazioni e filtri',
        'nav_tips'               => 'Consigli rapidi',

        // Section 1: Overview
        's1_heading' => 'Panoramica',
        's1_intro'   => 'Il modulo Reportistica riunisce in un unico luogo tutto ciò che accade nel tuo service desk. Monitora le prestazioni dei ticket, controlla l\'attività del sistema, esamina i tentativi di accesso e verifica le importazioni email — il tutto da un unico modulo progettato per aiutarti a individuare tendenze e prendere decisioni basate sui dati.',
        's1_card1_title' => 'Analisi dei ticket',
        's1_card1_body'  => 'Visualizza il volume dei ticket, i tempi di risoluzione, la conformità agli SLA e il carico di lavoro del team tramite dashboard interattive che si aggiornano in tempo reale.',
        's1_card2_title' => 'Log di sistema',
        's1_card2_body'  => 'Esamina ogni tentativo di accesso, importazione email ed evento di sistema in una tabella ricercabile e filtrabile con marche temporali e indicatori di stato.',
        's1_card3_title' => 'Monitoraggio dell\'attività',
        's1_card3_body'  => 'Tieni sotto controllo l\'attività degli analisti sulla piattaforma — chi accede, su quali ticket si lavora e dove viene speso il tempo.',
        's1_card4_title' => 'Traccia di controllo',
        's1_card4_body'  => 'Ogni azione viene registrata con chi l\'ha eseguita, quando e cosa è cambiato. Essenziale per la conformità, le revisioni di sicurezza e la risoluzione dei problemi.',

        // Section 2: Ticket reports
        's2_heading' => 'Report dei ticket',
        's2_intro'   => 'L\'area Ticket della reportistica fornisce dashboard KPI che ti offrono un quadro chiaro delle prestazioni del tuo service desk. Queste dashboard attingono i dati direttamente dai record dei ticket e li presentano tramite grafici e schede riepilogative.',
        's2_card1_title' => 'Volume dei ticket',
        's2_card1_body'  => 'Scopri quanti ticket vengono creati, risolti e ancora aperti in qualsiasi periodo di tempo. Individua i giorni di punta e gli andamenti stagionali.',
        's2_card2_title' => 'Conformità agli SLA',
        's2_card2_body'  => 'Monitora la percentuale di ticket che rispetta gli obiettivi di risposta e di risoluzione. Approfondisci per priorità o categoria per individuare le aree problematiche.',
        's2_card3_title' => 'Tempi di risoluzione',
        's2_card3_body'  => 'Misura il tempo medio e mediano di risoluzione dei ticket. Confronta tra team, categorie o livelli di priorità per individuare i colli di bottiglia.',
        's2_card4_title' => 'Carico di lavoro del team',
        's2_card4_body'  => 'Scopri come i ticket sono distribuiti tra gli analisti. Individua chi è sovraccarico e chi ha la capacità di occuparsi di altro lavoro.',
        's2_card5_title' => 'Ripartizione per categoria',
        's2_card5_body'  => 'Comprendi quali tipi di problemi generano il maggior numero di ticket. Usa questa informazione per mirare la formazione, la documentazione o i miglioramenti al self-service.',
        's2_card6_title' => 'Analisi delle tendenze',
        's2_card6_body'  => 'Visualizza i dati dei ticket su settimane, mesi o trimestri per individuare tendenze a lungo termine e misurare l\'impatto dei miglioramenti dei processi.',
        's2_tip'         => 'Le dashboard dei ticket sono accessibili tramite la scheda Ticket nella navigazione dell\'intestazione. Usa i filtri per intervallo di date per confrontare periodi diversi affiancati.',

        // Section 3: System logs
        's3_heading' => 'Log di sistema',
        's3_intro'   => 'L\'area Log cattura tutto ciò che accade dietro le quinte nella tua istanza di FreeITSM. Ogni tentativo di accesso, importazione email ed evento di sistema viene registrato con una marca temporale e uno stato, così da avere sempre un quadro completo dell\'attività della piattaforma.',
        's3_badge_login'  => 'ACCESSO',
        's3_badge_email'  => 'EMAIL',
        's3_badge_system' => 'SISTEMA',
        's3_badge_audit'  => 'AUDIT',
        's3_login_title'  => 'Tentativi di accesso',
        's3_login_body'   => 'Ogni accesso riuscito e non riuscito viene registrato con il nome dell\'analista, l\'indirizzo IP e la marca temporale. I tentativi non riusciti sono evidenziati in rosso così da individuare rapidamente i tentativi di accesso non autorizzati o gli utenti bloccati.',
        's3_email_title'  => 'Importazioni email',
        's3_email_body'   => 'Quando il sistema elabora le email in arrivo trasformandole in ticket, ogni importazione viene registrata con l\'indirizzo del mittente, la riga dell\'oggetto e se è stata convertita con successo. Le importazioni non riuscite mostrano il motivo, così da poter indagare sui messaggi respinti o malformati.',
        's3_system_title' => 'Eventi di sistema',
        's3_system_body'  => 'I processi in background, le attività pianificate, le modifiche alla configurazione e l\'attività delle API vengono tutti catturati qui. Usa questi log per verificare che i processi automatizzati funzionino correttamente e per diagnosticare i problemi.',
        's3_audit_title'  => 'Voci di audit',
        's3_audit_body'   => 'Tracciamento delle modifiche a livello di campo sulla piattaforma. Scopri esattamente chi ha cambiato cosa, quando e quale fosse il valore precedente. Preziosissimo per i requisiti di conformità e la risoluzione delle controversie.',
        's3_step1_title' => 'Apri la scheda Log',
        's3_step1_body'  => 'clicca su Log nella navigazione dell\'intestazione per accedere al visualizzatore dei log di sistema.',
        's3_step2_title' => 'Passa da un tipo di log all\'altro',
        's3_step2_body'  => 'usa la barra delle schede in alto per filtrare per tentativi di accesso, importazioni email o eventi di sistema.',
        's3_step3_title' => 'Esamina i dettagli',
        's3_step3_body'  => 'ogni riga mostra una marca temporale, un badge di stato (riuscito o non riuscito) e dettagli contestuali come indirizzi IP, oggetti delle email o descrizioni degli eventi.',
        's3_tip'         => 'Controlla regolarmente i log di accesso per individuare ripetuti tentativi non riusciti da indirizzi IP sconosciuti. Questo può indicare attacchi a forza bruta o credenziali compromesse che richiedono attenzione immediata.',

        // Section 4: Understanding the data
        's4_heading' => 'Comprendere i dati',
        's4_intro'   => 'I dati grezzi diventano utili solo quando sai cosa cercare. Ecco le metriche chiave da tenere d\'occhio e come interpretarle per ottenere miglioramenti concreti nelle operazioni del tuo service desk.',
        's4_metric1_title' => 'Tempo di prima risposta',
        's4_metric1_body'  => 'Quanto tempo gli utenti attendono prima che un analista prenda in carico il loro ticket. Un andamento in crescita indica che il tuo team potrebbe essere sotto organico o che i ticket non vengono instradati in modo efficace. Obiettivo: al di sotto della soglia del tuo SLA.',
        's4_metric2_title' => 'Tasso di risoluzione',
        's4_metric2_body'  => 'La percentuale di ticket risolti in un dato periodo rispetto a quelli creati. Se arrivano più ticket di quanti ne vengano chiusi, il tuo arretrato sta crescendo e devi indagarne la causa.',
        's4_metric3_title' => 'Contatti ripetuti',
        's4_metric3_body'  => 'Ticket riaperti o utenti che segnalano lo stesso problema più volte. Tassi elevati di contatti ripetuti suggeriscono che la causa principale non viene affrontata o che le soluzioni non vengono comunicate chiaramente.',
        's4_metric4_title' => 'Categorie critiche',
        's4_metric4_body'  => 'Quali categorie generano il maggior numero di ticket nel tempo. Un picco in una particolare categoria può segnalare un sistema in avaria, un aggiornamento software difettoso o una lacuna nella formazione degli utenti da colmare.',
        's4_combine'     => 'Usa queste metriche insieme anziché isolatamente. Per esempio, un alto tasso di risoluzione combinato con un alto tasso di contatti ripetuti può indicare che i ticket vengono chiusi troppo rapidamente senza risolvere il problema di fondo.',
        's4_tip'         => 'Pianifica una revisione settimanale delle tue metriche chiave con il team. Gli andamenti invisibili giorno per giorno spesso diventano evidenti se osservati con cadenza settimanale o mensile.',

        // Section 5: Settings & filters
        's5_heading' => 'Impostazioni e filtri',
        's5_intro'   => 'Sia il visualizzatore dei log sia le dashboard dei ticket supportano una serie di filtri per aiutarti a restringere esattamente i dati di cui hai bisogno. Un uso efficace dei filtri trasforma un muro di dati in informazioni mirate e utili.',
        's5_step1_title' => 'Intervalli di date',
        's5_step1_body'  => 'filtra log e report su una finestra temporale specifica. Usa intervalli preimpostati (oggi, questa settimana, questo mese) o imposta date di inizio e fine personalizzate per un controllo preciso.',
        's5_step2_title' => 'Filtri di stato',
        's5_step2_body'  => 'nel visualizzatore dei log, filtra per stato riuscito o non riuscito per isolare rapidamente i problemi. Nei report dei ticket, filtra per stato aperto, risolto o chiuso.',
        's5_step3_title' => 'Ricerca',
        's5_step3_body'  => 'usa la casella di ricerca per trovare voci specifiche tramite parola chiave. Nei log, la ricerca avviene tra nomi degli analisti, indirizzi IP, oggetti delle email e descrizioni degli eventi.',
        's5_step4_title' => 'Raggruppamento temporale',
        's5_step4_body'  => 'nelle dashboard dei ticket, raggruppa i dati per giorno, settimana o mese per cambiare la granularità dei tuoi grafici. Le viste giornaliere mostrano i picchi a breve termine; le viste mensili rivelano le tendenze a lungo termine.',
        's5_step5_title' => 'Filtri per reparto',
        's5_step5_body'  => 'restringi i risultati della dashboard a un reparto specifico per confrontare le prestazioni tra le diverse parti dell\'organizzazione.',
        's5_tip'         => 'Combina più filtri per un\'analisi mirata. Per esempio, filtra per un reparto specifico e un intervallo di date per vedere come una recente modifica di processo ha influito sul volume di ticket di quel team.',

        // Section 6: Quick tips
        's6_heading' => 'Consigli rapidi',
        's6_tip1_title' => 'Esamina regolarmente',
        's6_tip1_body'  => 'I report sono più preziosi quando vengono esaminati con costanza. Stabilisci una cadenza — settimanale per le metriche operative, mensile per l\'analisi delle tendenze — e mantienila.',
        's6_tip2_title' => 'Indaga le anomalie',
        's6_tip2_body'  => 'Un improvviso picco o calo in qualsiasi metrica è un segnale che vale la pena indagare. Controlla i log per il contesto — c\'è stata un\'interruzione del sistema, un rilascio software o un cambio di personale?',
        's6_tip3_title' => 'Confronta i periodi',
        's6_tip3_body'  => 'Usa i filtri per data per confrontare questa settimana con la precedente, o questo mese con lo stesso mese dell\'anno scorso. I confronti relativi rivelano miglioramenti o peggioramenti più chiaramente dei numeri grezzi.',
        's6_tip4_title' => 'Monitora la sicurezza',
        's6_tip4_body'  => 'Tieni d\'occhio i tentativi di accesso non riusciti nei log di sistema. Ripetuti fallimenti dallo stesso indirizzo IP o sullo stesso account possono indicare un problema di sicurezza che richiede un\'escalation.',
    ],
];
