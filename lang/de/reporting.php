<?php
/**
 * Deutsch (de) — Reporting module strings.
 * Missing keys fall back to lang/en/reporting.php per-key.
 */
return [
    'title' => 'Reporting',

    'nav' => [
        'logs'    => 'Protokolle',
        'tickets' => 'Tickets',
        'intune'  => 'Intune',
        'help'    => 'Hilfe',

        'logs_title'    => 'Systemprotokolle',
        'tickets_title' => 'Ticket-Dashboards',
        'intune_title'  => 'Intune-Dashboard',
        'help_title'    => 'Hilfe',
    ],

    'landing' => [
        'heading'  => 'Reporting',
        'subtitle' => 'Wählen Sie einen Reporting-Bereich aus, um zu beginnen',

        'logs_title'    => 'Systemprotokolle',
        'logs_desc'     => 'Anmeldeversuche, E-Mail-Importe und andere Systemaktivitätsprotokolle anzeigen.',
        'tickets_title' => 'Ticket-Dashboards',
        'tickets_desc'  => 'KPI-Dashboards für Ticket-Performance, Lösungszeiten und Team-Auslastung.',
        'intune_title'  => 'Intune-Dashboard',
        'intune_desc'   => 'Compliance, Verschlüsselung, Betriebssystemverteilung, Enrollment-Trend und Zustand der letzten Synchronisierung über alle verwalteten Geräte hinweg.',
    ],

    'logs' => [
        'heading'  => 'Systemprotokolle',
        'refresh'  => 'Aktualisieren',
        'tab_login'        => 'Benutzeranmeldungen',
        'tab_email_import' => 'E-Mail-Importe',

        'loading'        => 'Protokolle werden geladen...',
        'no_logs'        => 'Keine Protokolle gefunden',
        'load_error'     => 'Fehler beim Laden der Protokolle: {error}',

        'col_datetime'    => 'Datum/Uhrzeit',
        'col_username'    => 'Benutzername',
        'col_status'      => 'Status',
        'col_ip'          => 'IP-Adresse',
        'col_user_agent'  => 'User-Agent',
        'col_from'        => 'Von',
        'col_subject'     => 'Betreff',
        'col_type'        => 'Typ',
        'col_attachments' => 'Anhänge',

        'status_success' => 'Erfolgreich',
        'status_failed'  => 'Fehlgeschlagen',
        'unknown'        => 'Unbekannt',
        'no_subject'     => '(Kein Betreff)',
        'new_ticket'     => 'Neues Ticket',
        'reply'          => 'Antwort',
        'none'           => 'Keine',

        'row_title'  => 'Klicken, um JSON-Details anzuzeigen',

        'pagination' => 'Seite {current} von {total} ({count} insgesamt)',
        'prev'       => 'Zurück',
        'next'       => 'Weiter',

        'modal_title' => 'Protokolldetails (JSON)',
        'close'       => 'Schließen',
    ],

    'tickets' => [
        'heading' => 'Ticket-Dashboards',
        'coming_soon' => 'KPI-Dashboards und Reporting für Ticket-Performance, Lösungszeiten und Team-Auslastung werden hier in Kürze verfügbar sein.',
    ],

    'intune' => [
        'heading'      => 'Intune-Dashboard',
        'loading_meta' => 'Wird geladen…',
        'refresh'      => 'Aktualisieren',
        'refresh_title'=> 'Daten aktualisieren',
        'loading_data' => 'Intune-Daten werden geladen…',

        'last_sync'    => 'Letzte Synchronisierung: {when}',
        'error'        => 'Fehler: {error}',
        'load_failed'  => 'Dashboard konnte nicht geladen werden: {error}',
        'no_devices_title' => 'Keine Intune-Geräte gefunden.',
        'no_devices_body'  => 'Führen Sie eine Intune-Synchronisierung im Assets-Modul aus, um Geräte zu importieren, und kehren Sie dann hierher zurück.',
        'no_data'      => 'Keine Daten',
        'unknown'      => 'Unbekannt',

        // KPI strip
        'kpi_total'            => 'Geräte insgesamt',
        'kpi_total_sub'        => 'Alle verwalteten Geräte',
        'kpi_compliant'        => 'Konform',
        'kpi_compliant_sub'    => '{count} von {total}',
        'kpi_encrypted'        => 'Verschlüsselt',
        'kpi_encrypted_sub'    => '{count} von {total}',
        'kpi_stale'            => 'Veraltet (30+ Tage)',
        'kpi_stale_sub'        => 'Keine Synchronisierung in den letzten 30 Tagen',
        'kpi_enrolled'         => 'Registriert (30 Tage)',
        'kpi_enrolled_sub'     => 'Neu in den letzten 30 Tagen',

        'kpi_compliant_drill'  => 'Konforme Geräte',
        'kpi_encrypted_drill'  => 'Verschlüsselte Geräte',
        'kpi_stale_drill'      => 'Veraltet (30+ Tage)',
        'kpi_enrolled_drill'   => 'In den letzten 30 Tagen registriert',

        // Widgets
        'w_compliance_title'   => 'Compliance-Aufschlüsselung',
        'w_compliance_desc'    => 'Geräte nach Compliance-Status',
        'w_os_title'           => 'Betriebssystem',
        'w_os_desc'            => 'Geräte gruppiert nach Betriebssystem',
        'w_owner_title'        => 'Besitzertyp',
        'w_owner_desc'         => 'Geschäftliche vs. private Geräte',
        'w_manufacturers_title'=> 'Top-Hersteller',
        'w_manufacturers_desc' => 'Geräte nach Hersteller (Top 10)',
        'w_os_versions_title'  => 'Top-Betriebssystemversionen',
        'w_os_versions_desc'   => 'Häufigste Kombinationen aus Betriebssystem + Version',
        'w_last_sync_title'    => 'Zeitfenster der letzten Synchronisierung',
        'w_last_sync_desc'     => 'Wie kürzlich sich Geräte gemeldet haben',
        'w_enrolment_title'    => 'Registrierungen (letzte 90 Tage)',
        'w_enrolment_desc'     => 'Neu registrierte Geräte pro Tag',
        'w_encryption_title'   => 'Verschlüsselung nach Betriebssystem',
        'w_encryption_desc'    => 'Verschlüsselt vs. unverschlüsselt, pro Betriebssystem',

        // Chart tooltips / labels
        'tooltip_enrolled'     => '{count} registriert (zum Aufschlüsseln klicken)',
        'drill_enrolled_on'    => 'Registriert am {date}',

        // Drill-down modal
        'drill_devices'        => 'Geräte',
        'drill_loading'        => 'Wird geladen…',
        'drill_count'          => '{count} Gerät',
        'drill_count_plural'   => '{count} Geräte',
        'drill_no_match'       => 'Keine Geräte entsprechen diesem Filter.',
        'drill_error'          => 'Fehler: {error}',
        'drill_load_failed'    => 'Laden fehlgeschlagen: {error}',
        'drill_page_info'      => 'Seite {current} von {total}',
        'drill_prev'           => '‹ Zurück',
        'drill_next'           => 'Weiter ›',
        'drill_export'         => 'CSV exportieren',
        'drill_close'          => 'Schließen',

        'drill_col_device'     => 'Gerät',
        'drill_col_user'       => 'Benutzer',
        'drill_col_os'         => 'Betriebssystem',
        'drill_col_compliance' => 'Compliance',
        'drill_col_encrypted'  => 'Verschlüsselt',
        'drill_col_last_sync'  => 'Letzte Synchronisierung',

        'never'                => 'Nie',
        'yes'                  => 'Ja',
        'no'                   => 'Nein',
    ],

    'help' => [
        'page_title' => 'Reporting-Leitfaden',
        'guide'      => 'Leitfaden',

        'hero_heading' => 'Reporting-Leitfaden',
        'hero_sub'     => 'Verwandeln Sie Ihre Service-Desk-Daten mit Protokollen, Analysen und Dashboards in umsetzbare Erkenntnisse.',

        'nav_overview'           => 'Überblick',
        'nav_ticket_reports'     => 'Ticket-Berichte',
        'nav_system_logs'        => 'Systemprotokolle',
        'nav_understanding_data' => 'Die Daten verstehen',
        'nav_settings_filters'   => 'Einstellungen & Filter',
        'nav_tips'               => 'Schnelle Tipps',

        // Section 1: Overview
        's1_heading' => 'Überblick',
        's1_intro'   => 'Das Reporting-Modul bringt alles, was in Ihrem Service Desk passiert, an einem Ort zusammen. Verfolgen Sie die Ticket-Performance, überwachen Sie Systemaktivitäten, prüfen Sie Anmeldeversuche und auditieren Sie E-Mail-Importe — alles aus einem einzigen Modul, das Ihnen hilft, Trends zu erkennen und datenbasierte Entscheidungen zu treffen.',
        's1_card1_title' => 'Ticket-Analyse',
        's1_card1_body'  => 'Visualisieren Sie Ticketvolumen, Lösungszeiten, SLA-Einhaltung und Team-Auslastung über interaktive Dashboards, die in Echtzeit aktualisiert werden.',
        's1_card2_title' => 'Systemprotokolle',
        's1_card2_body'  => 'Prüfen Sie jeden Anmeldeversuch, E-Mail-Import und jedes Systemereignis in einer durchsuchbaren, filterbaren Tabelle mit Zeitstempeln und Statusanzeigen.',
        's1_card3_title' => 'Aktivitätsverfolgung',
        's1_card3_body'  => 'Überwachen Sie die Analystenaktivität auf der gesamten Plattform — wer sich anmeldet, an welchen Tickets gearbeitet wird und wo Zeit aufgewendet wird.',
        's1_card4_title' => 'Audit-Trail',
        's1_card4_body'  => 'Jede Aktion wird mit Angabe, wer sie wann ausgeführt hat und was sich geändert hat, aufgezeichnet. Unverzichtbar für Compliance, Sicherheitsprüfungen und Fehlerbehebung.',

        // Section 2: Ticket reports
        's2_heading' => 'Ticket-Berichte',
        's2_intro'   => 'Der Tickets-Bereich des Reportings bietet KPI-Dashboards, die Ihnen ein klares Bild davon geben, wie Ihr Service Desk arbeitet. Diese Dashboards beziehen Daten direkt aus Ihren Ticketdatensätzen und stellen sie über Diagramme und Übersichtskarten dar.',
        's2_card1_title' => 'Ticketvolumen',
        's2_card1_body'  => 'Sehen Sie, wie viele Tickets über einen beliebigen Zeitraum erstellt, gelöst und noch offen sind. Erkennen Sie arbeitsreiche Tage und saisonale Muster.',
        's2_card2_title' => 'SLA-Einhaltung',
        's2_card2_body'  => 'Verfolgen Sie, welcher Prozentsatz der Tickets seine Reaktions- und Lösungsziele erreicht. Schlüsseln Sie nach Priorität oder Kategorie auf, um Problembereiche zu finden.',
        's2_card3_title' => 'Lösungszeiten',
        's2_card3_body'  => 'Messen Sie die durchschnittliche und mittlere Zeit zur Lösung von Tickets. Vergleichen Sie über Teams, Kategorien oder Prioritätsstufen hinweg, um Engpässe zu erkennen.',
        's2_card4_title' => 'Team-Auslastung',
        's2_card4_body'  => 'Sehen Sie, wie Tickets auf die Analysten verteilt sind. Erkennen Sie, wer überlastet ist und wer Kapazität hat, mehr Arbeit zu übernehmen.',
        's2_card5_title' => 'Kategorie-Aufschlüsselung',
        's2_card5_body'  => 'Verstehen Sie, welche Arten von Problemen die meisten Tickets erzeugen. Nutzen Sie dies, um Schulungen, Dokumentation oder Self-Service-Verbesserungen gezielt anzugehen.',
        's2_card6_title' => 'Trendanalyse',
        's2_card6_body'  => 'Betrachten Sie Ticketdaten über Wochen, Monate oder Quartale, um langfristige Trends zu erkennen und die Wirkung von Prozessverbesserungen zu messen.',
        's2_tip'         => 'Auf Ticket-Dashboards greifen Sie über den Tab Tickets in der Kopfnavigation zu. Verwenden Sie Datumsbereichsfilter, um verschiedene Zeiträume nebeneinander zu vergleichen.',

        // Section 3: System logs
        's3_heading' => 'Systemprotokolle',
        's3_intro'   => 'Der Protokollbereich erfasst alles, was im Hintergrund Ihrer FreeITSM-Instanz passiert. Jeder Anmeldeversuch, E-Mail-Import und jedes Systemereignis wird mit einem Zeitstempel und Status aufgezeichnet, sodass Sie stets ein vollständiges Bild der Plattformaktivität haben.',
        's3_badge_login'  => 'ANMELDUNG',
        's3_badge_email'  => 'E-MAIL',
        's3_badge_system' => 'SYSTEM',
        's3_badge_audit'  => 'AUDIT',
        's3_login_title'  => 'Anmeldeversuche',
        's3_login_body'   => 'Jede erfolgreiche und fehlgeschlagene Anmeldung wird mit Analystenname, IP-Adresse und Zeitstempel aufgezeichnet. Fehlgeschlagene Versuche werden rot markiert, sodass Sie unbefugte Zugriffsversuche oder gesperrte Benutzer schnell erkennen können.',
        's3_email_title'  => 'E-Mail-Importe',
        's3_email_body'   => 'Wenn das System eingehende E-Mails zu Tickets verarbeitet, wird jeder Import mit der Absenderadresse, der Betreffzeile und dem Hinweis protokolliert, ob er erfolgreich umgewandelt wurde. Fehlgeschlagene Importe zeigen den Grund an, sodass Sie zurückgewiesene oder fehlerhafte Nachrichten untersuchen können.',
        's3_system_title' => 'Systemereignisse',
        's3_system_body'  => 'Hintergrundprozesse, geplante Aufgaben, Konfigurationsänderungen und API-Aktivität werden hier alle erfasst. Verwenden Sie diese Protokolle, um zu überprüfen, ob automatisierte Aufgaben korrekt ausgeführt werden, und um Probleme zu diagnostizieren.',
        's3_audit_title'  => 'Audit-Einträge',
        's3_audit_body'   => 'Feldbezogene Änderungsverfolgung über die gesamte Plattform. Sehen Sie genau, wer was wann geändert hat und welchen Wert es zuvor hatte. Von unschätzbarem Wert für Compliance-Anforderungen und die Klärung von Streitfällen.',
        's3_step1_title' => 'Tab Protokolle öffnen',
        's3_step1_body'  => 'klicken Sie in der Kopfnavigation auf Protokolle, um den Systemprotokoll-Viewer aufzurufen.',
        's3_step2_title' => 'Zwischen Protokolltypen wechseln',
        's3_step2_body'  => 'verwenden Sie die Tableiste oben, um nach Anmeldeversuchen, E-Mail-Importen oder Systemereignissen zu filtern.',
        's3_step3_title' => 'Details prüfen',
        's3_step3_body'  => 'jede Zeile zeigt einen Zeitstempel, ein Status-Badge (erfolgreich oder fehlgeschlagen) und kontextbezogene Details wie IP-Adressen, E-Mail-Betreffe oder Ereignisbeschreibungen.',
        's3_tip'         => 'Prüfen Sie die Anmeldeprotokolle regelmäßig auf wiederholte fehlgeschlagene Versuche von unbekannten IP-Adressen. Dies kann auf Brute-Force-Angriffe oder kompromittierte Anmeldedaten hinweisen, die sofortige Aufmerksamkeit erfordern.',

        // Section 4: Understanding the data
        's4_heading' => 'Die Daten verstehen',
        's4_intro'   => 'Rohdaten werden erst dann nützlich, wenn Sie wissen, worauf Sie achten müssen. Hier sind die wichtigsten Kennzahlen, die Sie im Auge behalten sollten, und wie Sie sie interpretieren, um echte Verbesserungen in Ihrem Service-Desk-Betrieb zu erzielen.',
        's4_metric1_title' => 'Erste Reaktionszeit',
        's4_metric1_body'  => 'Wie lange Benutzer warten, bevor ein Analyst ihr Ticket bestätigt. Ein steigender Trend bedeutet hier, dass Ihr Team möglicherweise unterbesetzt ist oder Tickets nicht effektiv weitergeleitet werden. Ziel: unter Ihrem SLA-Schwellenwert.',
        's4_metric2_title' => 'Lösungsquote',
        's4_metric2_body'  => 'Der Prozentsatz der innerhalb eines bestimmten Zeitraums gelösten Tickets im Verhältnis zu den erstellten. Wenn mehr Tickets eingehen als gelöst werden, wächst Ihr Rückstand und Sie müssen die Ursache untersuchen.',
        's4_metric3_title' => 'Wiederholte Kontakte',
        's4_metric3_body'  => 'Tickets, die erneut geöffnet werden, oder Benutzer, die dasselbe Problem mehrfach melden. Hohe Wiederholungsraten deuten darauf hin, dass die Grundursache nicht behoben wird oder dass Lösungen nicht klar kommuniziert werden.',
        's4_metric4_title' => 'Kategorie-Brennpunkte',
        's4_metric4_body'  => 'Welche Kategorien im Laufe der Zeit die meisten Tickets erzeugen. Ein Anstieg in einer bestimmten Kategorie kann auf ein versagendes System, ein fehlerhaftes Software-Update oder eine Lücke in der Benutzerschulung hinweisen, die behoben werden muss.',
        's4_combine'     => 'Verwenden Sie diese Kennzahlen gemeinsam statt isoliert. Eine hohe Lösungsquote in Kombination mit einer hohen Wiederholungsrate kann beispielsweise darauf hindeuten, dass Tickets zu schnell geschlossen werden, ohne das zugrunde liegende Problem zu lösen.',
        's4_tip'         => 'Planen Sie eine wöchentliche Überprüfung Ihrer wichtigsten Kennzahlen mit dem Team. Muster, die im Tagesgeschäft unsichtbar sind, werden oft offensichtlich, wenn man sie in einem wöchentlichen oder monatlichen Rhythmus betrachtet.',

        // Section 5: Settings & filters
        's5_heading' => 'Einstellungen & Filter',
        's5_intro'   => 'Sowohl der Protokoll-Viewer als auch die Ticket-Dashboards unterstützen eine Reihe von Filtern, die Ihnen helfen, genau die Daten einzugrenzen, die Sie benötigen. Der effektive Einsatz von Filtern verwandelt eine Datenflut in gezielte, umsetzbare Informationen.',
        's5_step1_title' => 'Datumsbereiche',
        's5_step1_body'  => 'filtern Sie Protokolle und Berichte auf ein bestimmtes Zeitfenster. Verwenden Sie voreingestellte Bereiche (heute, diese Woche, dieser Monat) oder legen Sie benutzerdefinierte Start- und Enddaten für präzise Kontrolle fest.',
        's5_step2_title' => 'Statusfilter',
        's5_step2_body'  => 'filtern Sie im Protokoll-Viewer nach Erfolgs- oder Fehlerstatus, um Probleme schnell zu isolieren. In Ticket-Berichten filtern Sie nach dem Status offen, gelöst oder geschlossen.',
        's5_step3_title' => 'Suche',
        's5_step3_body'  => 'verwenden Sie das Suchfeld, um bestimmte Einträge per Stichwort zu finden. In Protokollen durchsucht dies Analystennamen, IP-Adressen, E-Mail-Betreffe und Ereignisbeschreibungen.',
        's5_step4_title' => 'Zeitliche Gruppierung',
        's5_step4_body'  => 'gruppieren Sie in Ticket-Dashboards Daten nach Tag, Woche oder Monat, um die Granularität Ihrer Diagramme zu ändern. Tagesansichten zeigen kurzfristige Spitzen; Monatsansichten zeigen langfristige Trends.',
        's5_step5_title' => 'Abteilungsfilter',
        's5_step5_body'  => 'grenzen Sie Dashboard-Ergebnisse auf eine bestimmte Abteilung ein, um die Performance zwischen verschiedenen Teilen der Organisation zu vergleichen.',
        's5_tip'         => 'Kombinieren Sie mehrere Filter für eine gezielte Analyse. Filtern Sie beispielsweise nach einer bestimmten Abteilung und einem Datumsbereich, um zu sehen, wie sich eine kürzliche Prozessänderung auf das Ticketvolumen dieses Teams ausgewirkt hat.',

        // Section 6: Quick tips
        's6_heading' => 'Schnelle Tipps',
        's6_tip1_title' => 'Regelmäßig prüfen',
        's6_tip1_body'  => 'Berichte sind am wertvollsten, wenn sie konsequent geprüft werden. Legen Sie einen Rhythmus fest — wöchentlich für operative Kennzahlen, monatlich für Trendanalysen — und halten Sie sich daran.',
        's6_tip2_title' => 'Anomalien untersuchen',
        's6_tip2_body'  => 'Ein plötzlicher Anstieg oder Rückgang einer Kennzahl ist ein Signal, das eine Untersuchung wert ist. Prüfen Sie die Protokolle auf Kontext — gab es einen Systemausfall, einen Software-Rollout oder eine Personaländerung?',
        's6_tip3_title' => 'Zeiträume vergleichen',
        's6_tip3_body'  => 'Verwenden Sie Datumsfilter, um diese Woche mit der letzten Woche oder diesen Monat mit demselben Monat im Vorjahr zu vergleichen. Relative Vergleiche zeigen Verbesserungen oder Verschlechterungen klarer als nackte Zahlen.',
        's6_tip4_title' => 'Sicherheit überwachen',
        's6_tip4_body'  => 'Behalten Sie fehlgeschlagene Anmeldeversuche in den Systemprotokollen im Auge. Wiederholte Fehlschläge von derselben IP-Adresse oder gegen dasselbe Konto können auf ein Sicherheitsproblem hinweisen, das eskaliert werden muss.',
    ],
];
