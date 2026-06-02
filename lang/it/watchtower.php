<?php
/**
 * Italiano (it) — Watchtower module strings.
 * Missing keys fall back to lang/en/watchtower.php per-key.
 */
return [
    'title' => 'Watchtower',

    'nav' => [
        'dashboard' => 'Dashboard',
        'help'      => 'Aiuto',
    ],

    'dashboard' => [
        'heading'      => 'Panoramica attenzione',
        'refresh'      => 'Aggiorna',
        'updated'      => 'Aggiornato {time}',
    ],

    // Per-module card names shown in the card header (links to each module).
    'cards' => [
        'morning_checks' => 'Controlli mattutini',
        'tickets'        => 'Ticket',
        'changes'        => 'Modifiche',
        'calendar'       => 'Calendario',
        'service_status' => 'Stato dei servizi',
        'contracts'      => 'Contratti',
        'knowledge'      => 'Knowledge base',
        'assets'         => 'Asset',
        'tasks'          => 'Attività',
    ],

    // Morning Checks card.
    'mc' => [
        'metric_done' => 'Completati',
        'metric_ok'   => 'OK',
        'metric_warn' => 'Avvisi',
        'metric_fail' => 'Falliti',
        'not_started'      => 'Controlli non avviati oggi',
        'pending'          => '{count} controlli ancora in sospeso',
        'failed'           => '{count} controllo/i fallito/i',
        'warnings'         => '{count} controllo/i con avvisi',
        'all_passing'      => 'Tutti i controlli completati e superati',
    ],

    // Tickets card.
    'tickets' => [
        'metric_open'   => 'Aperti',
        'metric_new'    => 'Nuovi',
        'metric_active' => 'Attivi',
        'metric_hold'   => 'In attesa',
        'urgent_high'   => '<span class="wt-attention-bold">{count}</span> ticket con priorità urgente/alta',
        'unassigned'    => '<span class="wt-attention-bold">{count}</span> ticket non assegnati',
        'paused_one'    => '<span class="wt-attention-bold">{count}</span> ticket in pausa da oltre {hours}h (orologio SLA fermo)',
        'paused_many'   => '<span class="wt-attention-bold">{count}</span> ticket in pausa da oltre {hours}h (orologio SLA fermo)',
        'all_clear'     => 'Nessun elemento urgente',
    ],

    // Changes card.
    'changes' => [
        'metric_next_7d' => 'Prossimi 7g',
        'metric_active'  => 'Attive',
        'metric_pending' => 'In sospeso',
        'awaiting'       => '<span class="wt-attention-bold">{count}</span> modifica/e in attesa di approvazione',
        'in_progress'    => '{count} modifica/e attualmente in corso',
        'scheduled'      => '{count} modifica/e pianificata/e questa settimana',
        'all_clear'      => 'Nessuna modifica imminente',
    ],

    // Calendar card.
    'calendar' => [
        'metric_today' => 'Oggi',
        'metric_week'  => 'Questa settimana',
        'all_day'      => 'Tutto il giorno',
        'no_events'    => 'Nessun evento oggi',
    ],

    // Service Status card.
    'service' => [
        'all_operational' => 'Tutti i sistemi operativi',
        'active_incidents' => '<span class="wt-attention-bold">{count}</span> incidente/i attivo/i',
    ],

    // Contracts card.
    'contracts' => [
        'metric_30d'     => '30 giorni',
        'metric_90d'     => '90 giorni',
        'metric_notices' => 'Preavvisi',
        'expiring'       => '<span class="wt-attention-bold">{count}</span> contratto/i in scadenza entro 30 giorni',
        'notices'        => '<span class="wt-attention-bold">{count}</span> periodo/i di preavviso in avvicinamento',
        'all_clear'      => 'Nessun contratto richiede attenzione',
    ],

    // Knowledge card.
    'knowledge' => [
        'overdue'         => '<span class="wt-attention-bold">{count}</span> articolo/i da rivedere in ritardo',
        'published_week'  => 'Pubblicati questa settimana',
        'up_to_date'      => 'Knowledge base aggiornata',
    ],

    // Assets card.
    'assets' => [
        'metric_total'    => 'Totale',
        'metric_offline'  => 'Offline',
        'metric_warranty' => 'Garanzia',
        'warranty'        => '<span class="wt-attention-bold">{count}</span> asset con garanzia scaduta o in scadenza entro {days} giorni',
        'offline'         => '<span class="wt-attention-bold">{count}</span> asset non rilevati da oltre 7 giorni',
        'all_active'      => 'Tutti gli asset attivi di recente',
    ],

    // Tasks card.
    'tasks' => [
        'metric_todo'   => 'Da fare',
        'metric_active' => 'Attive',
        'overdue'       => '<span class="wt-attention-bold">{count}</span> attività in ritardo',
        'due_today'     => '<span class="wt-attention-bold">{count}</span> in scadenza oggi',
        'all_clear'     => 'Nessuna attività in ritardo',
    ],

    // Help guide.
    'help' => [
        'page_title'   => 'Guida di Watchtower',
        'sidebar_label' => 'Guida',
        'hero_title'   => 'Guida di Watchtower',
        'hero_subtitle' => 'Una dashboard unificata di attenzione che mostra gli elementi su cui agire da ogni modulo in un\'unica occhiata.',

        'nav_overview'  => 'Panoramica',
        'nav_layout'    => 'Il layout della dashboard',
        'nav_dots'      => 'Comprendere i pallini di stato',
        'nav_cards'     => 'Le schede dei moduli spiegate',
        'nav_refresh'   => 'Aggiornamento automatico',
        'nav_tips'      => 'Consigli rapidi',

        // Section 1 — Overview
        's1_title' => 'Panoramica',
        's1_intro' => 'Watchtower è il tuo pannello unico per le operazioni IT. Invece di aprire ogni modulo singolarmente per verificare gli elementi urgenti, Watchtower raccoglie le informazioni più importanti da ogni modulo in un\'unica dashboard. A colpo d\'occhio puoi vedere cosa richiede attenzione, cosa procede senza problemi e dove concentrare il tuo tempo.',
        's1_feat1_title' => 'Bacheca dell\'attenzione',
        's1_feat1_desc'  => 'Visualizza in un unico punto ciò che richiede la tua attenzione in tutti i moduli. Controlli mattutini, ticket, modifiche, eventi del calendario, stato dei servizi, contratti, articoli della knowledge base e asset sono tutti riepilogati in un\'unica schermata.',
        's1_feat2_title' => 'Stato con codice colore',
        's1_feat2_desc'  => 'Ogni scheda del modulo mostra un pallino di stato verde, ambra o rosso per una valutazione immediata. Puoi capire a colpo d\'occhio quali aree sono in salute, quali richiedono attenzione e quali necessitano di un\'azione immediata.',
        's1_feat3_title' => 'Aggiornamento automatico',
        's1_feat3_desc'  => 'La dashboard si aggiorna automaticamente ogni 5 minuti, quindi le informazioni restano sempre attuali senza alcuna azione manuale. Lascia Watchtower aperto e si manterrà aggiornato in background.',
        's1_feat4_title' => 'Accesso diretto',
        's1_feat4_desc'  => 'Passa direttamente a qualsiasi modulo dalla sua scheda. Il nome di ogni modulo è un collegamento cliccabile che ti porta subito all\'area pertinente, così puoi agire sui problemi senza cercare la pagina giusta.',

        // Section 2 — Dashboard layout
        's2_title' => 'Il layout della dashboard',
        's2_p1' => 'La dashboard di Watchtower utilizza una griglia reattiva a 3 colonne di schede dei moduli. Su schermi più piccoli la griglia si adatta a 2 colonne o a una singola colonna, così funziona su qualsiasi dispositivo. Sopra la griglia c\'è la barra del titolo con un pulsante di aggiornamento e una marca temporale "Aggiornato" che indica quando i dati sono stati recuperati l\'ultima volta.',
        's2_p2' => 'Ogni scheda nella griglia segue una struttura coerente così da poterle scorrere rapidamente:',
        's2_diagram_name'   => 'Nome del modulo',
        's2_diagram_open'   => 'APERTI',
        's2_diagram_active' => 'ATTIVI',
        's2_diagram_hold'   => 'IN ATTESA',
        's2_diagram_clear'  => 'Tutto a posto — nessun elemento urgente',
        's2_field_icon'    => '<strong>Icona colorata</strong> &mdash; una piccola icona quadrata nel colore tematico del modulo (verde acqua per i Controlli mattutini, blu per i Ticket, ecc.) così da identificare istantaneamente ogni scheda.',
        's2_field_name'    => '<strong>Nome del modulo</strong> &mdash; un collegamento cliccabile che ti porta direttamente a quel modulo. Clicca per accedere subito e agire.',
        's2_field_dot'     => '<strong>Pallino di stato</strong> &mdash; un pallino verde, ambra o rosso nell\'angolo in alto a destra che indica il livello complessivo di urgenza per quel modulo.',
        's2_field_metrics' => '<strong>Metriche chiave</strong> &mdash; numeri grandi che riepilogano i conteggi più importanti (ad es. ticket aperti, controlli completati, contratti in scadenza).',
        's2_field_attention' => '<strong>Elementi da seguire</strong> &mdash; righe di messaggio con codice colore che evidenziano cosa richiede specificamente la tua attenzione all\'interno di quel modulo.',
        's2_tip' => 'Il layout delle schede è pensato per la scansione rapida, non per l\'analisi approfondita. Usa Watchtower per individuare quali moduli richiedono la tua attenzione, poi clicca per accedere al modulo stesso e vedere tutti i dettagli.',

        // Section 3 — Status dots
        's3_title' => 'Comprendere i pallini di stato',
        's3_intro' => 'Ogni scheda del modulo mostra un pallino di stato nella sua intestazione. Questo pallino fornisce un indicatore visivo immediato del fatto che quell\'area delle tue operazioni IT richieda o meno attenzione. Il colore viene determinato automaticamente in base ai dati restituiti da ogni modulo.',
        's3_green_label' => 'Verde',
        's3_green_desc'  => 'Tutto a posto. Nessuna azione necessaria. Il modulo è in uno stato di salute, senza problemi in sospeso o elementi che richiedono attenzione.',
        's3_green_examples' => '<strong>Esempi:</strong> Tutti i controlli mattutini superati, nessun ticket urgente, tutti i sistemi operativi, nessun contratto in scadenza a breve.',
        's3_amber_label' => 'Ambra',
        's3_amber_desc'  => 'Qualcosa richiede attenzione ma non è critico. Ci sono elementi da rivedere quando ne hai l\'occasione, ma niente di urgente.',
        's3_amber_examples' => '<strong>Esempi:</strong> Controlli con avvisi, ticket non assegnati, modifiche in attesa di approvazione, contratti in scadenza entro 90 giorni.',
        's3_red_label' => 'Rosso',
        's3_red_desc'  => 'Gli elementi urgenti richiedono un\'azione immediata. Qualcosa è fallito, è in ritardo o è impattato in modo critico e deve essere affrontato subito.',
        's3_red_examples' => '<strong>Esempi:</strong> Controlli mattutini non avviati o falliti, ticket con priorità urgente/alta, gravi interruzioni dei servizi, contratti in scadenza entro 30 giorni.',
        's3_tip' => 'Pensa ai pallini come a un semaforo. Verde significa procedere con la giornata, ambra significa rivedere quando possibile e rosso significa fermarsi e indagare. L\'obiettivo è mantenere tutti i pallini verdi.',

        // Section 4 — Module cards explained
        's4_title' => 'Le schede dei moduli spiegate',
        's4_intro' => 'Watchtower monitora otto moduli. Ogni scheda è studiata per mostrare le informazioni più rilevanti per quell\'area. Ecco cosa mostra ciascuna scheda e cosa determina il colore del suo pallino di stato.',
        's4_mc_title'    => 'Controlli mattutini',
        's4_mc_desc'     => 'Mostra l\'avanzamento del completamento (ad es. 8/10 completati) più i conteggi dei risultati OK, Avviso e Fallito. Gli elementi da seguire segnalano quando i controlli non sono stati avviati o quando qualcuno è fallito.',
        's4_mc_triggers' => '<strong>Rosso:</strong> Controlli non avviati oggi, o qualche controllo fallito. <strong>Ambra:</strong> Controlli incompleti o avvisi presenti. <strong>Verde:</strong> Tutti i controlli completati e superati.',
        's4_tk_title'    => 'Ticket',
        's4_tk_desc'     => 'Mostra il numero totale di aperti suddiviso in Nuovi, Attivi e In attesa. Gli elementi da seguire evidenziano i ticket con priorità urgente/alta e quelli non assegnati.',
        's4_tk_triggers' => '<strong>Rosso:</strong> Esistono ticket con priorità urgente o alta. <strong>Ambra:</strong> Ticket non assegnati presenti. <strong>Verde:</strong> Nessun elemento urgente o ticket non assegnato.',
        's4_ch_title'    => 'Modifiche',
        's4_ch_desc'     => 'Mostra il numero di modifiche pianificate nei prossimi 7 giorni, quante sono attualmente in corso e quante sono in attesa di approvazione. Gli elementi da seguire segnalano le modifiche non approvate e attive.',
        's4_ch_triggers' => '<strong>Ambra:</strong> Modifiche in attesa di approvazione. <strong>Verde:</strong> Nessuna modifica non approvata.',
        's4_cal_title'    => 'Calendario',
        's4_cal_desc'     => 'Mostra il numero di eventi di oggi e di questa settimana. Se ci sono eventi oggi, vengono elencati con i loro orari (o "Tutto il giorno" per gli eventi che durano l\'intera giornata).',
        's4_cal_triggers' => '<strong>Ambra:</strong> Eventi pianificati per oggi. <strong>Verde:</strong> Nessun evento oggi.',
        's4_ss_title'    => 'Stato dei servizi',
        's4_ss_desc'     => 'Mostra il numero di incidenti attivi ed elenca i servizi interessati con le etichette del livello di impatto (Interruzione grave, Interruzione parziale, Degradato, Manutenzione). Quando tutto è in salute, compare un banner verde "Tutti i sistemi operativi".',
        's4_ss_triggers' => '<strong>Rosso:</strong> Interruzione grave o parziale su un servizio. <strong>Ambra:</strong> Stato degradato o di manutenzione. <strong>Verde:</strong> Tutti i sistemi operativi.',
        's4_ct_title'    => 'Contratti',
        's4_ct_desc'     => 'Mostra i contratti in scadenza entro 30 giorni, entro 90 giorni e i periodi di preavviso in avvicinamento. Gli elementi da seguire avvisano sulle scadenze imminenti e sui prossimi termini di preavviso.',
        's4_ct_triggers' => '<strong>Rosso:</strong> Contratti in scadenza entro 30 giorni. <strong>Ambra:</strong> Contratti in scadenza entro 90 giorni o periodi di preavviso in avvicinamento. <strong>Verde:</strong> Nessun contratto richiede attenzione.',
        's4_kb_title'    => 'Knowledge base',
        's4_kb_desc'     => 'Mostra il numero di articoli da rivedere in ritardo ed elenca gli articoli pubblicati di recente in questa settimana. Quando non ci sono revisioni in ritardo e la knowledge base è aggiornata, la scheda mostra un messaggio di tutto a posto.',
        's4_kb_triggers' => '<strong>Ambra:</strong> Articoli da rivedere in ritardo. <strong>Verde:</strong> Knowledge base aggiornata.',
        's4_as_title'    => 'Asset',
        's4_as_desc'     => 'Mostra il numero totale di asset tracciati e quanti non sono stati rilevati da 7 o più giorni. Questo aiuta a individuare i dispositivi che potrebbero essere offline, dismessi o persi.',
        's4_as_triggers' => '<strong>Ambra:</strong> Asset non rilevati da oltre 7 giorni. <strong>Verde:</strong> Tutti gli asset attivi di recente.',

        // Section 5 — Auto-refresh
        's5_title' => 'Aggiornamento automatico e manuale',
        's5_intro' => 'Watchtower è progettato per essere uno strumento di monitoraggio passivo che puoi lasciare aperto in una scheda del browser per tutta la giornata. La dashboard si mantiene aggiornata tramite cicli di aggiornamento automatici.',
        's5_step1' => '<strong>Aggiornamento automatico</strong> &mdash; la dashboard recupera dati aggiornati da tutti i moduli ogni 5 minuti. Non è necessario ricaricare la pagina o cliccare nulla; le schede e i pallini di stato si aggiornano silenziosamente in background.',
        's5_step2' => '<strong>Aggiornamento manuale</strong> &mdash; clicca il pulsante <strong>Aggiorna</strong> nell\'angolo in alto a destra per recuperare immediatamente i dati più recenti. L\'icona del pulsante ruota mentre la richiesta è in corso, confermando che i nuovi dati vengono caricati.',
        's5_step3' => '<strong>Marca temporale di aggiornamento</strong> &mdash; accanto al pulsante di aggiornamento, una marca temporale mostra l\'ultima volta che i dati sono stati recuperati (ad es. "Aggiornato 09:15"). Questo ti dice esattamente quanto sono attuali le informazioni visualizzate.',
        's5_tip' => 'Tieni Watchtower aperto in una scheda dedicata del browser per il monitoraggio passivo. Il ciclo di aggiornamento di 5 minuti significa che hai sempre una visione quasi in tempo reale delle tue operazioni IT senza dover controllare manualmente ogni modulo.',

        // Section 6 — Quick tips
        's6_title' => 'Consigli rapidi',
        's6_tip1_title' => 'Inizia qui la tua giornata',
        's6_tip1_desc'  => 'Apri Watchtower come prima cosa ogni mattina per una rapida panoramica operativa. In pochi secondi puoi vedere se i controlli mattutini sono stati completati, se qualche ticket è urgente e se tutti i servizi sono in salute.',
        's6_tip2_title' => 'Prima i pallini rossi',
        's6_tip2_desc'  => 'Affronta i pallini di stato rossi prima di ogni altra cosa. Questi indicano elementi urgenti che richiedono attenzione immediata &mdash; controlli falliti, ticket ad alta priorità o interruzioni dei servizi che stanno impattando attivamente gli utenti.',
        's6_tip3_title' => 'Clicca per accedere',
        's6_tip3_desc'  => 'Clicca il nome di qualsiasi modulo su una scheda per passare direttamente a quel modulo. Non serve usare il menu principale o la navigazione a griglia &mdash; Watchtower funge da scorciatoia diretta verso il punto in cui serve attenzione.',
        's6_tip4_title' => 'Premi Aggiorna per i dati più recenti',
        's6_tip4_desc'  => 'Anche se la dashboard si aggiorna automaticamente ogni 5 minuti, puoi cliccare il pulsante Aggiorna ogni volta che vuoi i dati più recenti in assoluto. Utile dopo aver risolto un problema per confermare che il pallino di stato sia cambiato.',
        's6_tip5_title' => 'Usalo nelle riunioni di team',
        's6_tip5_desc'  => 'Proietta Watchtower su uno schermo durante gli stand-up o le riunioni di revisione operativa. I pallini con codice colore rendono facile discutere quali aree richiedono attenzione e assegnare la responsabilità degli elementi ambra o rossi.',
        's6_tip6_title' => 'Verde significa tutto a posto',
        's6_tip6_desc'  => 'Quando ogni pallino sulla dashboard è verde, le tue operazioni IT sono in buono stato. Nessun ticket urgente, nessun controllo fallito, nessun contratto in scadenza e tutti i servizi operativi. Questo è l\'obiettivo.',
    ],
];
