<?php
/**
 * Nederlands (nl) — Reporting module strings.
 * Missing keys fall back to lang/en/reporting.php per-key.
 */
return [
    'title' => 'Rapportage',

    'nav' => [
        'logs'    => 'Logboeken',
        'tickets' => 'Tickets',
        'intune'  => 'Intune',
        'help'    => 'Help',

        'logs_title'    => 'Systeemlogboeken',
        'tickets_title' => 'Ticketdashboards',
        'intune_title'  => 'Intune-dashboard',
        'help_title'    => 'Help',
    ],

    'landing' => [
        'heading'  => 'Rapportage',
        'subtitle' => 'Kies een rapportagegebied om te beginnen',

        'logs_title'    => 'Systeemlogboeken',
        'logs_desc'     => 'Bekijk aanmeldpogingen, e-mailimports en andere logboeken met systeemactiviteit.',
        'tickets_title' => 'Ticketdashboards',
        'tickets_desc'  => 'KPI-dashboards voor ticketprestaties, oplostijden en teambelasting.',
        'intune_title'  => 'Intune-dashboard',
        'intune_desc'   => 'Naleving, versleuteling, OS-verdeling, aanmeldtrend en laatste-synchronisatiestatus voor elk beheerd apparaat.',
    ],

    'logs' => [
        'heading'  => 'Systeemlogboeken',
        'refresh'  => 'Vernieuwen',
        'tab_login'        => 'Gebruikersaanmeldingen',
        'tab_email_import' => 'E-mailimports',

        'loading'        => 'Logboeken laden...',
        'no_logs'        => 'Geen logboeken gevonden',
        'load_error'     => 'Fout bij het laden van logboeken: {error}',

        'col_datetime'    => 'Datum/tijd',
        'col_username'    => 'Gebruikersnaam',
        'col_status'      => 'Status',
        'col_ip'          => 'IP-adres',
        'col_user_agent'  => 'User-agent',
        'col_from'        => 'Van',
        'col_subject'     => 'Onderwerp',
        'col_type'        => 'Type',
        'col_attachments' => 'Bijlagen',

        'status_success' => 'Geslaagd',
        'status_failed'  => 'Mislukt',
        'unknown'        => 'Onbekend',
        'no_subject'     => '(Geen onderwerp)',
        'new_ticket'     => 'Nieuw ticket',
        'reply'          => 'Antwoord',
        'none'           => 'Geen',

        'row_title'  => 'Klik om JSON-details te bekijken',

        'pagination' => 'Pagina {current} van {total} ({count} totaal)',
        'prev'       => 'Vorige',
        'next'       => 'Volgende',

        'modal_title' => 'Logdetails (JSON)',
        'close'       => 'Sluiten',
    ],

    'tickets' => [
        'heading' => 'Ticketdashboards',
        'coming_soon' => 'KPI-dashboards en rapportage voor ticketprestaties, oplostijden en teambelasting komen hier binnenkort beschikbaar.',
    ],

    'intune' => [
        'heading'      => 'Intune-dashboard',
        'loading_meta' => 'Laden…',
        'refresh'      => 'Vernieuwen',
        'refresh_title'=> 'Gegevens vernieuwen',
        'loading_data' => 'Intune-gegevens laden…',

        'last_sync'    => 'Laatste synchronisatie: {when}',
        'error'        => 'Fout: {error}',
        'load_failed'  => 'Dashboard laden mislukt: {error}',
        'no_devices_title' => 'Geen Intune-apparaten gevonden.',
        'no_devices_body'  => 'Voer een Intune-synchronisatie uit vanuit de Assets-module om apparaten te importeren en kom dan hier terug.',
        'no_data'      => 'Geen gegevens',
        'unknown'      => 'Onbekend',

        // KPI strip
        'kpi_total'            => 'Totaal apparaten',
        'kpi_total_sub'        => 'Alle beheerde apparaten',
        'kpi_compliant'        => 'Conform',
        'kpi_compliant_sub'    => '{count} van {total}',
        'kpi_encrypted'        => 'Versleuteld',
        'kpi_encrypted_sub'    => '{count} van {total}',
        'kpi_stale'            => 'Verouderd (30+ dagen)',
        'kpi_stale_sub'        => 'Geen synchronisatie in de laatste 30 dagen',
        'kpi_enrolled'         => 'Aangemeld (30 dagen)',
        'kpi_enrolled_sub'     => 'Nieuw in de laatste 30 dagen',

        'kpi_compliant_drill'  => 'Conforme apparaten',
        'kpi_encrypted_drill'  => 'Versleutelde apparaten',
        'kpi_stale_drill'      => 'Verouderd (30+ dagen)',
        'kpi_enrolled_drill'   => 'Aangemeld in de laatste 30 dagen',

        // Widgets
        'w_compliance_title'   => 'Nalevingsoverzicht',
        'w_compliance_desc'    => 'Apparaten per nalevingsstatus',
        'w_os_title'           => 'Besturingssysteem',
        'w_os_desc'            => 'Apparaten gegroepeerd per OS',
        'w_owner_title'        => 'Eigenaarstype',
        'w_owner_desc'         => 'Zakelijke versus persoonlijke apparaten',
        'w_manufacturers_title'=> 'Belangrijkste fabrikanten',
        'w_manufacturers_desc' => 'Apparaten per fabrikant (top 10)',
        'w_os_versions_title'  => 'Belangrijkste OS-versies',
        'w_os_versions_desc'   => 'Meest voorkomende OS- en versiecombinaties',
        'w_last_sync_title'    => 'Laatste synchronisatievenster',
        'w_last_sync_desc'     => 'Hoe recent apparaten zich hebben aangemeld',
        'w_enrolment_title'    => 'Aanmeldingen (laatste 90 dagen)',
        'w_enrolment_desc'     => 'Nieuwe apparaten aangemeld per dag',
        'w_encryption_title'   => 'Versleuteling per OS',
        'w_encryption_desc'    => 'Versleuteld versus onversleuteld, per OS',

        // Chart tooltips / labels
        'tooltip_enrolled'     => '{count} aangemeld (klik om in te zoomen)',
        'drill_enrolled_on'    => 'Aangemeld op {date}',

        // Drill-down modal
        'drill_devices'        => 'Apparaten',
        'drill_loading'        => 'Laden…',
        'drill_count'          => '{count} apparaat',
        'drill_count_plural'   => '{count} apparaten',
        'drill_no_match'       => 'Geen apparaten komen overeen met dit filter.',
        'drill_error'          => 'Fout: {error}',
        'drill_load_failed'    => 'Laden mislukt: {error}',
        'drill_page_info'      => 'Pagina {current} van {total}',
        'drill_prev'           => '‹ Vorige',
        'drill_next'           => 'Volgende ›',
        'drill_export'         => 'CSV exporteren',
        'drill_close'          => 'Sluiten',

        'drill_col_device'     => 'Apparaat',
        'drill_col_user'       => 'Gebruiker',
        'drill_col_os'         => 'OS',
        'drill_col_compliance' => 'Naleving',
        'drill_col_encrypted'  => 'Versleuteld',
        'drill_col_last_sync'  => 'Laatste synchronisatie',

        'never'                => 'Nooit',
        'yes'                  => 'Ja',
        'no'                   => 'Nee',
    ],

    'help' => [
        'page_title' => 'Rapportagehandleiding',
        'guide'      => 'Handleiding',

        'hero_heading' => 'Rapportagehandleiding',
        'hero_sub'     => 'Zet de gegevens van uw servicedesk om in bruikbare inzichten met logboeken, analyses en dashboards.',

        'nav_overview'           => 'Overzicht',
        'nav_ticket_reports'     => 'Ticketrapporten',
        'nav_system_logs'        => 'Systeemlogboeken',
        'nav_understanding_data' => 'De gegevens begrijpen',
        'nav_settings_filters'   => 'Instellingen en filters',
        'nav_tips'               => 'Snelle tips',

        // Section 1: Overview
        's1_heading' => 'Overzicht',
        's1_intro'   => 'De Rapportagemodule brengt alles wat er op uw servicedesk gebeurt op één plek samen. Volg ticketprestaties, bewaak systeemactiviteit, beoordeel aanmeldpogingen en controleer e-mailimports — allemaal vanuit één module die u helpt trends te herkennen en datagestuurde beslissingen te nemen.',
        's1_card1_title' => 'Ticketanalyses',
        's1_card1_body'  => 'Visualiseer ticketvolume, oplostijden, SLA-naleving en teambelasting via interactieve dashboards die in realtime worden bijgewerkt.',
        's1_card2_title' => 'Systeemlogboeken',
        's1_card2_body'  => 'Beoordeel elke aanmeldpoging, e-mailimport en systeemgebeurtenis in een doorzoekbare, filterbare tabel met tijdstempels en statusindicatoren.',
        's1_card3_title' => 'Activiteitsregistratie',
        's1_card3_body'  => 'Bewaak de activiteit van analisten op het platform — wie er aanmeldt, aan welke tickets wordt gewerkt en waar tijd aan wordt besteed.',
        's1_card4_title' => 'Audittrail',
        's1_card4_body'  => 'Elke actie wordt vastgelegd met wie het deed, wanneer en wat er is gewijzigd. Essentieel voor naleving, beveiligingscontroles en probleemoplossing.',

        // Section 2: Ticket reports
        's2_heading' => 'Ticketrapporten',
        's2_intro'   => 'Het Tickets-gebied van de rapportage biedt KPI-dashboards die u een duidelijk beeld geven van hoe uw servicedesk presteert. Deze dashboards halen gegevens rechtstreeks uit uw ticketrecords en presenteren deze via grafieken en samenvattingskaarten.',
        's2_card1_title' => 'Ticketvolume',
        's2_card1_body'  => 'Zie hoeveel tickets er worden aangemaakt, opgelost en nog openstaan over een willekeurige periode. Identificeer drukke dagen en seizoenspatronen.',
        's2_card2_title' => 'SLA-naleving',
        's2_card2_body'  => 'Volg welk percentage tickets de reactie- en oplosdoelen haalt. Zoom in op prioriteit of categorie om probleemgebieden te vinden.',
        's2_card3_title' => 'Oplostijden',
        's2_card3_body'  => 'Meet de gemiddelde en mediane tijd om tickets op te lossen. Vergelijk per team, categorie of prioriteitsniveau om knelpunten te herkennen.',
        's2_card4_title' => 'Teambelasting',
        's2_card4_body'  => 'Zie hoe tickets over analisten zijn verdeeld. Identificeer wie overbelast is en wie ruimte heeft om meer werk op te pakken.',
        's2_card5_title' => 'Categorie-uitsplitsing',
        's2_card5_body'  => 'Begrijp welke soorten problemen de meeste tickets genereren. Gebruik dit om training, documentatie of zelfbedieningsverbeteringen te richten.',
        's2_card6_title' => 'Trendanalyse',
        's2_card6_body'  => 'Bekijk ticketgegevens over weken, maanden of kwartalen om langetermijntrends te herkennen en de impact van procesverbeteringen te meten.',
        's2_tip'         => 'Ticketdashboards zijn toegankelijk via het tabblad Tickets in de kopnavigatie. Gebruik datumbereikfilters om verschillende periodes naast elkaar te vergelijken.',

        // Section 3: System logs
        's3_heading' => 'Systeemlogboeken',
        's3_intro'   => 'Het Logboeken-gebied legt alles vast wat er achter de schermen in uw FreeITSM-instantie gebeurt. Elke aanmeldpoging, e-mailimport en systeemgebeurtenis wordt vastgelegd met een tijdstempel en status, zodat u altijd een volledig beeld hebt van de platformactiviteit.',
        's3_badge_login'  => 'AANMELDING',
        's3_badge_email'  => 'E-MAIL',
        's3_badge_system' => 'SYSTEEM',
        's3_badge_audit'  => 'AUDIT',
        's3_login_title'  => 'Aanmeldpogingen',
        's3_login_body'   => 'Elke geslaagde en mislukte aanmelding wordt vastgelegd met de naam van de analist, het IP-adres en het tijdstempel. Mislukte pogingen worden rood gemarkeerd, zodat u snel ongeoorloofde toegangspogingen of buitengesloten gebruikers kunt herkennen.',
        's3_email_title'  => 'E-mailimports',
        's3_email_body'   => 'Wanneer het systeem inkomende e-mails verwerkt tot tickets, wordt elke import vastgelegd met het afzenderadres, de onderwerpregel en of deze succesvol is omgezet. Mislukte imports tonen de reden, zodat u teruggestuurde of misvormde berichten kunt onderzoeken.',
        's3_system_title' => 'Systeemgebeurtenissen',
        's3_system_body'  => 'Achtergrondprocessen, geplande taken, configuratiewijzigingen en API-activiteit worden hier allemaal vastgelegd. Gebruik deze logboeken om te controleren of geautomatiseerde taken correct draaien en om problemen te diagnosticeren.',
        's3_audit_title'  => 'Auditvermeldingen',
        's3_audit_body'   => 'Wijzigingen op veldniveau worden over het hele platform bijgehouden. Zie precies wie wat heeft gewijzigd, wanneer en wat de vorige waarde was. Van onschatbare waarde voor nalevingseisen en het oplossen van geschillen.',
        's3_step1_title' => 'Open het tabblad Logboeken',
        's3_step1_body'  => 'klik op Logboeken in de kopnavigatie om de systeemlogviewer te openen.',
        's3_step2_title' => 'Schakel tussen logtypen',
        's3_step2_body'  => 'gebruik de tabbalk bovenaan om te filteren op aanmeldpogingen, e-mailimports of systeemgebeurtenissen.',
        's3_step3_title' => 'Beoordeel de details',
        's3_step3_body'  => 'elke rij toont een tijdstempel, statusbadge (geslaagd of mislukt) en contextuele details zoals IP-adressen, e-mailonderwerpen of gebeurtenisbeschrijvingen.',
        's3_tip'         => 'Controleer aanmeldlogboeken regelmatig op herhaalde mislukte pogingen vanaf onbekende IP-adressen. Dit kan duiden op brute-force-aanvallen of gecompromitteerde inloggegevens die onmiddellijke aandacht vereisen.',

        // Section 4: Understanding the data
        's4_heading' => 'De gegevens begrijpen',
        's4_intro'   => 'Ruwe gegevens worden pas nuttig wanneer u weet waarnaar u moet kijken. Hier volgen de belangrijkste statistieken om in de gaten te houden en hoe u ze interpreteert om echte verbeteringen in uw servicedeskwerking te realiseren.',
        's4_metric1_title' => 'Eerste reactietijd',
        's4_metric1_body'  => 'Hoe lang gebruikers wachten voordat een analist hun ticket bevestigt. Een stijgende trend hier betekent dat uw team mogelijk onderbezet is of dat tickets niet effectief worden doorgestuurd. Doel: onder uw SLA-drempel.',
        's4_metric2_title' => 'Oplospercentage',
        's4_metric2_body'  => 'Het percentage tickets dat binnen een bepaalde periode is opgelost versus het aantal aangemaakte tickets. Als er meer tickets binnenkomen dan eruit gaan, groeit uw achterstand en moet u de oorzaak onderzoeken.',
        's4_metric3_title' => 'Herhaalcontacten',
        's4_metric3_body'  => 'Tickets die opnieuw worden geopend of gebruikers die hetzelfde probleem meerdere keren melden. Hoge herhaalcontactpercentages suggereren dat de grondoorzaak niet wordt aangepakt of dat oplossingen niet duidelijk worden gecommuniceerd.',
        's4_metric4_title' => 'Categorie-knelpunten',
        's4_metric4_body'  => 'Welke categorieën in de loop van de tijd de meeste tickets genereren. Een piek in een bepaalde categorie kan wijzen op een falend systeem, een slechte software-update of een leemte in de gebruikerstraining die moet worden aangepakt.',
        's4_combine'     => 'Gebruik deze statistieken samen in plaats van afzonderlijk. Een hoog oplospercentage in combinatie met een hoog herhaalcontactpercentage kan er bijvoorbeeld op wijzen dat tickets te snel worden gesloten zonder het onderliggende probleem op te lossen.',
        's4_tip'         => 'Plan een wekelijkse beoordeling van uw belangrijkste statistieken met het team. Patronen die van dag tot dag onzichtbaar zijn, worden vaak duidelijk wanneer ze op wekelijkse of maandelijkse basis worden bekeken.',

        // Section 5: Settings & filters
        's5_heading' => 'Instellingen en filters',
        's5_intro'   => 'Zowel de logviewer als de ticketdashboards ondersteunen een reeks filters om u te helpen precies de gegevens te vinden die u nodig hebt. Effectief gebruik van filters verandert een muur van gegevens in gerichte, bruikbare informatie.',
        's5_step1_title' => 'Datumbereiken',
        's5_step1_body'  => 'filter logboeken en rapporten op een specifiek tijdvenster. Gebruik vooraf ingestelde bereiken (vandaag, deze week, deze maand) of stel aangepaste begin- en einddatums in voor nauwkeurige controle.',
        's5_step2_title' => 'Statusfilters',
        's5_step2_body'  => 'filter in de logviewer op geslaagd- of mislukt-status om problemen snel te isoleren. Filter in ticketrapporten op open, opgelost of gesloten status.',
        's5_step3_title' => 'Zoeken',
        's5_step3_body'  => 'gebruik het zoekvak om specifieke vermeldingen op trefwoord te vinden. In logboeken doorzoekt dit namen van analisten, IP-adressen, e-mailonderwerpen en gebeurtenisbeschrijvingen.',
        's5_step4_title' => 'Tijdgroepering',
        's5_step4_body'  => 'groepeer in ticketdashboards gegevens per dag, week of maand om de granulariteit van uw grafieken te wijzigen. Dagweergaven tonen kortetermijnpieken; maandweergaven onthullen langetermijntrends.',
        's5_step5_title' => 'Afdelingsfilters',
        's5_step5_body'  => 'beperk dashboardresultaten tot een specifieke afdeling om prestaties tussen verschillende delen van de organisatie te vergelijken.',
        's5_tip'         => 'Combineer meerdere filters voor gerichte analyse. Filter bijvoorbeeld op een specifieke afdeling en een datumbereik om te zien hoe een recente proceswijziging het ticketvolume van dat team heeft beïnvloed.',

        // Section 6: Quick tips
        's6_heading' => 'Snelle tips',
        's6_tip1_title' => 'Beoordeel regelmatig',
        's6_tip1_body'  => 'Rapporten zijn het meest waardevol wanneer ze consequent worden beoordeeld. Stel een ritme in — wekelijks voor operationele statistieken, maandelijks voor trendanalyse — en houd u eraan.',
        's6_tip2_title' => 'Onderzoek afwijkingen',
        's6_tip2_body'  => 'Een plotselinge piek of daling in een statistiek is een signaal dat onderzoek waard is. Bekijk de logboeken voor context — was er een systeemstoring, een software-uitrol of een personeelswijziging?',
        's6_tip3_title' => 'Vergelijk periodes',
        's6_tip3_body'  => 'Gebruik datumfilters om deze week met vorige week te vergelijken, of deze maand met dezelfde maand vorig jaar. Relatieve vergelijkingen onthullen verbetering of achteruitgang duidelijker dan ruwe cijfers.',
        's6_tip4_title' => 'Bewaak de beveiliging',
        's6_tip4_body'  => 'Houd mislukte aanmeldpogingen in de systeemlogboeken in de gaten. Herhaalde mislukkingen vanaf hetzelfde IP-adres of tegen hetzelfde account kunnen wijzen op een beveiligingsprobleem dat escalatie vereist.',
    ],
];
