<?php
/**
 * Deutsch (de) — Watchtower module strings.
 * Missing keys fall back to lang/en/watchtower.php per-key.
 */
return [
    'title' => 'Watchtower',

    'nav' => [
        'dashboard' => 'Dashboard',
        'help'      => 'Hilfe',
    ],

    'dashboard' => [
        'heading'      => 'Aufmerksamkeitsübersicht',
        'refresh'      => 'Aktualisieren',
        'updated'      => 'Aktualisiert {time}',
    ],

    // Per-module card names shown in the card header (links to each module).
    'cards' => [
        'morning_checks' => 'Morgenchecks',
        'tickets'        => 'Tickets',
        'changes'        => 'Änderungen',
        'calendar'       => 'Kalender',
        'service_status' => 'Servicestatus',
        'contracts'      => 'Verträge',
        'knowledge'      => 'Wissen',
        'assets'         => 'Assets',
        'tasks'          => 'Aufgaben',
    ],

    // Morning Checks card.
    'mc' => [
        'metric_done' => 'Erledigt',
        'metric_ok'   => 'OK',
        'metric_warn' => 'Warnung',
        'metric_fail' => 'Fehler',
        'not_started'      => 'Checks heute noch nicht begonnen',
        'pending'          => '{count} Checks noch ausstehend',
        'failed'           => '{count} Check(s) fehlgeschlagen',
        'warnings'         => '{count} Check(s) mit Warnungen',
        'all_passing'      => 'Alle Checks abgeschlossen und bestanden',
    ],

    // Tickets card.
    'tickets' => [
        'metric_open'   => 'Offen',
        'metric_new'    => 'Neu',
        'metric_active' => 'Aktiv',
        'metric_hold'   => 'Wartend',
        'urgent_high'   => '<span class="wt-attention-bold">{count}</span> Tickets mit dringender/hoher Priorität',
        'unassigned'    => '<span class="wt-attention-bold">{count}</span> nicht zugewiesene Tickets',
        'paused_one'    => '<span class="wt-attention-bold">{count}</span> Ticket länger als {hours} Std. pausiert (SLA-Uhr angehalten)',
        'paused_many'   => '<span class="wt-attention-bold">{count}</span> Tickets länger als {hours} Std. pausiert (SLA-Uhr angehalten)',
        'all_clear'     => 'Keine dringenden Einträge',
    ],

    // Changes card.
    'changes' => [
        'metric_next_7d' => 'Nächste 7 T.',
        'metric_active'  => 'Aktiv',
        'metric_pending' => 'Ausstehend',
        'awaiting'       => '<span class="wt-attention-bold">{count}</span> Änderung(en) warten auf Genehmigung',
        'in_progress'    => '{count} Änderung(en) derzeit in Bearbeitung',
        'scheduled'      => '{count} Änderung(en) für diese Woche geplant',
        'all_clear'      => 'Keine anstehenden Änderungen',
    ],

    // Calendar card.
    'calendar' => [
        'metric_today' => 'Heute',
        'metric_week'  => 'Diese Woche',
        'all_day'      => 'Ganztägig',
        'no_events'    => 'Keine Termine heute',
    ],

    // Service Status card.
    'service' => [
        'all_operational' => 'Alle Systeme betriebsbereit',
        'active_incidents' => '<span class="wt-attention-bold">{count}</span> aktive(r) Vorfall/Vorfälle',
    ],

    // Contracts card.
    'contracts' => [
        'metric_30d'     => '30 Tage',
        'metric_90d'     => '90 Tage',
        'metric_notices' => 'Fristen',
        'expiring'       => '<span class="wt-attention-bold">{count}</span> Vertrag/Verträge laufen innerhalb von 30 Tagen aus',
        'notices'        => '<span class="wt-attention-bold">{count}</span> Kündigungsfrist(en) stehen bevor',
        'all_clear'      => 'Keine Verträge erfordern Aufmerksamkeit',
    ],

    // Knowledge card.
    'knowledge' => [
        'overdue'         => '<span class="wt-attention-bold">{count}</span> Artikel zur Überprüfung überfällig',
        'published_week'  => 'Diese Woche veröffentlicht',
        'up_to_date'      => 'Wissensdatenbank auf dem neuesten Stand',
    ],

    // Assets card.
    'assets' => [
        'metric_total'    => 'Gesamt',
        'metric_offline'  => 'Offline',
        'metric_warranty' => 'Garantie',
        'warranty'        => '<span class="wt-attention-bold">{count}</span> Asset(s) mit abgelaufener oder innerhalb von {days} Tagen ablaufender Garantie',
        'offline'         => '<span class="wt-attention-bold">{count}</span> Asset(s) seit 7+ Tagen nicht gesehen',
        'all_active'      => 'Alle Assets kürzlich aktiv',
    ],

    // Tasks card.
    'tasks' => [
        'metric_todo'   => 'Zu erledigen',
        'metric_active' => 'Aktiv',
        'overdue'       => '<span class="wt-attention-bold">{count}</span> überfällige Aufgabe(n)',
        'due_today'     => '<span class="wt-attention-bold">{count}</span> heute fällig',
        'all_clear'     => 'Keine überfälligen Aufgaben',
    ],

    // Help guide.
    'help' => [
        'page_title'   => 'Watchtower-Leitfaden',
        'sidebar_label' => 'Leitfaden',
        'hero_title'   => 'Watchtower-Leitfaden',
        'hero_subtitle' => 'Ein einheitliches Aufmerksamkeits-Dashboard, das handlungsrelevante Einträge aus jedem Modul auf einen Blick anzeigt.',

        'nav_overview'  => 'Überblick',
        'nav_layout'    => 'Das Dashboard-Layout',
        'nav_dots'      => 'Statuspunkte verstehen',
        'nav_cards'     => 'Modulkarten erklärt',
        'nav_refresh'   => 'Automatische Aktualisierung',
        'nav_tips'      => 'Schnelltipps',

        // Section 1 — Overview
        's1_title' => 'Überblick',
        's1_intro' => 'Watchtower ist Ihr zentrales Übersichtsfenster für den IT-Betrieb. Anstatt jedes Modul einzeln zu öffnen, um nach dringenden Einträgen zu suchen, holt Watchtower die wichtigsten Informationen aus jedem Modul in ein einziges Dashboard. Auf einen Blick sehen Sie, was Aufmerksamkeit erfordert, was reibungslos läuft und worauf Sie Ihre Zeit konzentrieren sollten.',
        's1_feat1_title' => 'Aufmerksamkeitstafel',
        's1_feat1_desc'  => 'Sehen Sie an einem Ort, was über alle Module hinweg Ihre Aufmerksamkeit erfordert. Morgenchecks, Tickets, Änderungen, Kalendertermine, Servicestatus, Verträge, Wissensartikel und Assets werden alle auf einem einzigen Bildschirm zusammengefasst.',
        's1_feat2_title' => 'Farbcodierter Status',
        's1_feat2_desc'  => 'Jede Modulkarte zeigt einen grünen, gelben oder roten Statuspunkt für eine sofortige Einordnung. Sie erkennen auf einen Blick, welche Bereiche gesund sind, welche Aufmerksamkeit erfordern und welche sofortiges Handeln verlangen.',
        's1_feat3_title' => 'Automatische Aktualisierung',
        's1_feat3_desc'  => 'Das Dashboard aktualisiert sich automatisch alle 5 Minuten, sodass die Informationen ohne manuelles Zutun aktuell bleiben. Lassen Sie Watchtower geöffnet, und es hält sich im Hintergrund selbst auf dem neuesten Stand.',
        's1_feat4_title' => 'Direkter Zugriff',
        's1_feat4_desc'  => 'Springen Sie direkt von der Karte in jedes Modul. Jeder Modulname ist ein anklickbarer Link, der Sie direkt in den relevanten Bereich führt, sodass Sie Probleme angehen können, ohne die richtige Seite suchen zu müssen.',

        // Section 2 — Dashboard layout
        's2_title' => 'Das Dashboard-Layout',
        's2_p1' => 'Das Watchtower-Dashboard verwendet ein responsives 3-Spalten-Raster aus Modulkarten. Auf kleineren Bildschirmen passt sich das Raster auf 2 Spalten oder eine einzelne Spalte an, sodass es auf jedem Gerät funktioniert. Über dem Raster befindet sich die Titelleiste mit einer Schaltfläche zum Aktualisieren und einem Zeitstempel „Aktualisiert“, der anzeigt, wann die Daten zuletzt abgerufen wurden.',
        's2_p2' => 'Jede Karte im Raster folgt einer einheitlichen Struktur, damit Sie sie schnell überfliegen können:',
        's2_diagram_name'   => 'Modulname',
        's2_diagram_open'   => 'OFFEN',
        's2_diagram_active' => 'AKTIV',
        's2_diagram_hold'   => 'WARTEND',
        's2_diagram_clear'  => 'Alles in Ordnung — keine dringenden Einträge',
        's2_field_icon'    => '<strong>Farbiges Symbol</strong> &mdash; ein kleines quadratisches Symbol in der Themenfarbe des Moduls (Türkis für Morgenchecks, Blau für Tickets usw.), damit Sie jede Karte sofort erkennen.',
        's2_field_name'    => '<strong>Modulname</strong> &mdash; ein anklickbarer Link, der direkt zu diesem Modul navigiert. Klicken Sie, um direkt einzusteigen und tätig zu werden.',
        's2_field_dot'     => '<strong>Statuspunkt</strong> &mdash; ein grüner, gelber oder roter Punkt in der oberen rechten Ecke, der die Gesamtdringlichkeit für dieses Modul anzeigt.',
        's2_field_metrics' => '<strong>Kennzahlen</strong> &mdash; große Zahlen, die die wichtigsten Werte zusammenfassen (z. B. offene Tickets, abgeschlossene Checks, ablaufende Verträge).',
        's2_field_attention' => '<strong>Aufmerksamkeitseinträge</strong> &mdash; farbcodierte Meldungszeilen, die hervorheben, was innerhalb dieses Moduls konkret Ihre Aufmerksamkeit erfordert.',
        's2_tip' => 'Das Kartenlayout ist auf das Überfliegen ausgelegt, nicht auf eine tiefgehende Analyse. Verwenden Sie Watchtower, um zu erkennen, welche Module Ihre Aufmerksamkeit benötigen, und klicken Sie dann zum Modul selbst durch, um alle Details zu sehen.',

        // Section 3 — Status dots
        's3_title' => 'Statuspunkte verstehen',
        's3_intro' => 'Jede Modulkarte zeigt in ihrem Kopfbereich einen Statuspunkt an. Dieser Punkt bietet einen sofortigen visuellen Hinweis darauf, ob dieser Bereich Ihres IT-Betriebs Aufmerksamkeit erfordert. Die Farbe wird automatisch anhand der von jedem Modul zurückgegebenen Daten bestimmt.',
        's3_green_label' => 'Grün',
        's3_green_desc'  => 'Alles in Ordnung. Keine Maßnahme erforderlich. Das Modul befindet sich in einem gesunden Zustand ohne offene Probleme oder Einträge, die Aufmerksamkeit erfordern.',
        's3_green_examples' => '<strong>Beispiele:</strong> Alle Morgenchecks bestanden, keine dringenden Tickets, alle Systeme betriebsbereit, keine bald ablaufenden Verträge.',
        's3_amber_label' => 'Gelb',
        's3_amber_desc'  => 'Etwas erfordert Aufmerksamkeit, ist aber nicht kritisch. Es gibt Einträge, die Sie bei Gelegenheit überprüfen sollten, aber nichts Brennendes.',
        's3_amber_examples' => '<strong>Beispiele:</strong> Checks mit Warnungen, nicht zugewiesene Tickets, Änderungen, die auf Genehmigung warten, Verträge, die innerhalb von 90 Tagen auslaufen.',
        's3_red_label' => 'Rot',
        's3_red_desc'  => 'Dringende Einträge erfordern sofortiges Handeln. Etwas ist fehlgeschlagen, überfällig oder kritisch beeinträchtigt und muss umgehend behoben werden.',
        's3_red_examples' => '<strong>Beispiele:</strong> Morgenchecks nicht begonnen oder fehlgeschlagen, Tickets mit dringender/hoher Priorität, größere Serviceausfälle, Verträge, die innerhalb von 30 Tagen auslaufen.',
        's3_tip' => 'Stellen Sie sich die Punkte wie eine Ampel vor. Grün bedeutet, gehen Sie Ihrem Tag nach, Gelb bedeutet, überprüfen Sie es bei Gelegenheit, und Rot bedeutet, halten Sie inne und untersuchen Sie es. Das Ziel ist, alle Punkte grün zu halten.',

        // Section 4 — Module cards explained
        's4_title' => 'Modulkarten erklärt',
        's4_intro' => 'Watchtower überwacht acht Module. Jede Karte ist darauf zugeschnitten, die relevantesten Informationen für diesen Bereich anzuzeigen. Hier sehen Sie, was jede Karte anzeigt und was die Farbe ihres Statuspunkts auslöst.',
        's4_mc_title'    => 'Morgenchecks',
        's4_mc_desc'     => 'Zeigt den Abschlussfortschritt (z. B. 8/10 erledigt) sowie die Anzahl der Ergebnisse OK, Warnung und Fehler. Aufmerksamkeitseinträge weisen darauf hin, wenn Checks nicht begonnen wurden oder welche fehlgeschlagen sind.',
        's4_mc_triggers' => '<strong>Rot:</strong> Checks heute nicht begonnen oder Checks fehlgeschlagen. <strong>Gelb:</strong> Checks unvollständig oder Warnungen vorhanden. <strong>Grün:</strong> Alle Checks abgeschlossen und bestanden.',
        's4_tk_title'    => 'Tickets',
        's4_tk_desc'     => 'Zeigt die Gesamtzahl der offenen Tickets, aufgeschlüsselt nach Neu, Aktiv und Wartend. Aufmerksamkeitseinträge heben Tickets mit dringender/hoher Priorität und nicht zugewiesene Tickets hervor.',
        's4_tk_triggers' => '<strong>Rot:</strong> Tickets mit dringender oder hoher Priorität vorhanden. <strong>Gelb:</strong> Nicht zugewiesene Tickets vorhanden. <strong>Grün:</strong> Keine dringenden oder nicht zugewiesenen Tickets.',
        's4_ch_title'    => 'Änderungen',
        's4_ch_desc'     => 'Zeigt die Anzahl der in den nächsten 7 Tagen geplanten Änderungen, wie viele derzeit in Bearbeitung sind und wie viele auf Genehmigung warten. Aufmerksamkeitseinträge weisen auf nicht genehmigte und aktive Änderungen hin.',
        's4_ch_triggers' => '<strong>Gelb:</strong> Änderungen warten auf Genehmigung. <strong>Grün:</strong> Keine nicht genehmigten Änderungen.',
        's4_cal_title'    => 'Kalender',
        's4_cal_desc'     => 'Zeigt die Anzahl der Termine heute und in dieser Woche an. Wenn es heute Termine gibt, werden sie mit ihren Uhrzeiten aufgelistet (oder „Ganztägig“ bei ganztägigen Terminen).',
        's4_cal_triggers' => '<strong>Gelb:</strong> Für heute geplante Termine. <strong>Grün:</strong> Keine Termine heute.',
        's4_ss_title'    => 'Servicestatus',
        's4_ss_desc'     => 'Zeigt die Anzahl der aktiven Vorfälle an und listet betroffene Dienste mit ihren Auswirkungs-Badges auf (Größerer Ausfall, Teilausfall, Beeinträchtigt, Wartung). Wenn alles in Ordnung ist, erscheint ein grünes Banner „Alle Systeme betriebsbereit“.',
        's4_ss_triggers' => '<strong>Rot:</strong> Größerer oder Teilausfall bei einem Dienst. <strong>Gelb:</strong> Status beeinträchtigt oder Wartung. <strong>Grün:</strong> Alle Systeme betriebsbereit.',
        's4_ct_title'    => 'Verträge',
        's4_ct_desc'     => 'Zeigt Verträge an, die innerhalb von 30 Tagen, innerhalb von 90 Tagen auslaufen, sowie bevorstehende Kündigungsfristen. Aufmerksamkeitseinträge warnen vor unmittelbar bevorstehenden Abläufen und anstehenden Kündigungsfristen.',
        's4_ct_triggers' => '<strong>Rot:</strong> Verträge laufen innerhalb von 30 Tagen aus. <strong>Gelb:</strong> Verträge laufen innerhalb von 90 Tagen aus oder Kündigungsfristen stehen bevor. <strong>Grün:</strong> Keine Verträge erfordern Aufmerksamkeit.',
        's4_kb_title'    => 'Wissen',
        's4_kb_desc'     => 'Zeigt die Anzahl der zur Überprüfung überfälligen Artikel an und listet kürzlich veröffentlichte Artikel aus dieser Woche auf. Wenn keine Überprüfungen überfällig sind und die Wissensdatenbank aktuell ist, zeigt die Karte eine Entwarnungsmeldung an.',
        's4_kb_triggers' => '<strong>Gelb:</strong> Artikel zur Überprüfung überfällig. <strong>Grün:</strong> Wissensdatenbank auf dem neuesten Stand.',
        's4_as_title'    => 'Assets',
        's4_as_desc'     => 'Zeigt die Gesamtzahl der erfassten Assets an und wie viele seit 7 oder mehr Tagen nicht mehr gesehen wurden. Dies hilft, Geräte zu identifizieren, die offline, außer Betrieb genommen oder verloren sein könnten.',
        's4_as_triggers' => '<strong>Gelb:</strong> Assets seit 7+ Tagen nicht gesehen. <strong>Grün:</strong> Alle Assets kürzlich aktiv.',

        // Section 5 — Auto-refresh
        's5_title' => 'Automatische und manuelle Aktualisierung',
        's5_intro' => 'Watchtower ist als passives Überwachungswerkzeug konzipiert, das Sie den ganzen Tag über in einem Browser-Tab geöffnet lassen können. Das Dashboard hält sich durch automatische Aktualisierungszyklen selbst auf dem neuesten Stand.',
        's5_step1' => '<strong>Automatische Aktualisierung</strong> &mdash; das Dashboard ruft alle 5 Minuten frische Daten aus allen Modulen ab. Sie müssen die Seite nicht neu laden oder etwas anklicken; die Karten und Statuspunkte werden im Hintergrund stillschweigend aktualisiert.',
        's5_step2' => '<strong>Manuelle Aktualisierung</strong> &mdash; klicken Sie auf die Schaltfläche <strong>Aktualisieren</strong> in der oberen rechten Ecke, um die neuesten Daten sofort abzurufen. Das Schaltflächensymbol dreht sich, während die Anfrage läuft, und bestätigt so, dass neue Daten geladen werden.',
        's5_step3' => '<strong>Zeitstempel „Aktualisiert“</strong> &mdash; neben der Schaltfläche zum Aktualisieren zeigt ein Zeitstempel an, wann die Daten zuletzt abgerufen wurden (z. B. „Aktualisiert 09:15“). So wissen Sie genau, wie aktuell die angezeigten Informationen sind.',
        's5_tip' => 'Lassen Sie Watchtower für die passive Überwachung in einem eigenen Browser-Tab geöffnet. Der 5-minütige Aktualisierungszyklus bedeutet, dass Sie stets eine nahezu Echtzeit-Ansicht Ihres IT-Betriebs haben, ohne jedes Modul manuell prüfen zu müssen.',

        // Section 6 — Quick tips
        's6_title' => 'Schnelltipps',
        's6_tip1_title' => 'Beginnen Sie Ihren Tag hier',
        's6_tip1_desc'  => 'Öffnen Sie Watchtower jeden Morgen als Erstes für einen schnellen Betriebsüberblick. In Sekunden sehen Sie, ob die Morgenchecks erledigt sind, ob Tickets dringend sind und ob alle Dienste gesund sind.',
        's6_tip2_title' => 'Rote Punkte zuerst',
        's6_tip2_desc'  => 'Kümmern Sie sich vor allem anderen um rote Statuspunkte. Diese weisen auf dringende Einträge hin, die sofortige Aufmerksamkeit erfordern &mdash; fehlgeschlagene Checks, Tickets mit hoher Priorität oder Serviceausfälle, die Benutzer aktiv beeinträchtigen.',
        's6_tip3_title' => 'Klicken Sie zum Einsteigen',
        's6_tip3_desc'  => 'Klicken Sie auf einer Karte auf einen beliebigen Modulnamen, um direkt zu diesem Modul zu navigieren. Sie müssen weder das Hauptmenü noch die Waffle-Navigation verwenden &mdash; Watchtower dient als direkte Verknüpfung dorthin, wo Aufmerksamkeit erforderlich ist.',
        's6_tip4_title' => 'Aktualisieren für das Neueste',
        's6_tip4_desc'  => 'Obwohl sich das Dashboard alle 5 Minuten automatisch aktualisiert, können Sie jederzeit auf die Schaltfläche „Aktualisieren“ klicken, wenn Sie die allerneuesten Daten möchten. Nützlich nach dem Beheben eines Problems, um zu bestätigen, dass sich der Statuspunkt geändert hat.',
        's6_tip5_title' => 'In Teambesprechungen nutzen',
        's6_tip5_desc'  => 'Projizieren Sie Watchtower während Stand-ups oder betrieblichen Review-Besprechungen auf einen Bildschirm. Die farbcodierten Punkte erleichtern es, zu besprechen, welche Bereiche Aufmerksamkeit erfordern, und die Verantwortung für gelbe oder rote Einträge zuzuweisen.',
        's6_tip6_title' => 'Grün bedeutet alles in Ordnung',
        's6_tip6_desc'  => 'Wenn jeder Punkt auf dem Dashboard grün ist, ist Ihr IT-Betrieb in gutem Zustand. Keine dringenden Tickets, keine fehlgeschlagenen Checks, keine ablaufenden Verträge und alle Dienste betriebsbereit. Das ist das Ziel.',
    ],
];
