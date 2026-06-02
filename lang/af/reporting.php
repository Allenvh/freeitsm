<?php
/**
 * Afrikaans (af) — Reporting module strings.
 * Missing keys fall back to lang/en/reporting.php per-key.
 */
return [
    'title' => 'Verslagdoening',

    'nav' => [
        'logs'    => 'Logboeke',
        'tickets' => 'Kaartjies',
        'intune'  => 'Intune',
        'help'    => 'Hulp',

        'logs_title'    => 'Stelsellogboeke',
        'tickets_title' => 'Kaartjie-paneelborde',
        'intune_title'  => 'Intune-paneelbord',
        'help_title'    => 'Hulp',
    ],

    'landing' => [
        'heading'  => 'Verslagdoening',
        'subtitle' => 'Kies \'n verslagdoeningsarea om te begin',

        'logs_title'    => 'Stelsellogboeke',
        'logs_desc'     => 'Bekyk aanmeldpogings, e-posinvoere en ander stelselaktiwiteitslogboeke.',
        'tickets_title' => 'Kaartjie-paneelborde',
        'tickets_desc'  => 'KPI-paneelborde vir kaartjieprestasie, oplostye en spanwerklading.',
        'intune_title'  => 'Intune-paneelbord',
        'intune_desc'   => 'Nakoming, enkripsie, BS-verspreiding, inskrywingsneiging en laaste-sinkronisering-gesondheid oor elke bestuurde toestel.',
    ],

    'logs' => [
        'heading'  => 'Stelsellogboeke',
        'refresh'  => 'Verfris',
        'tab_login'        => 'Gebruikeraanmeldings',
        'tab_email_import' => 'E-posinvoere',

        'loading'        => 'Laai logboeke...',
        'no_logs'        => 'Geen logboeke gevind nie',
        'load_error'     => 'Fout met laai van logboeke: {error}',

        'col_datetime'    => 'Datum/tyd',
        'col_username'    => 'Gebruikersnaam',
        'col_status'      => 'Status',
        'col_ip'          => 'IP-adres',
        'col_user_agent'  => 'Gebruikersagent',
        'col_from'        => 'Van',
        'col_subject'     => 'Onderwerp',
        'col_type'        => 'Tipe',
        'col_attachments' => 'Aanhangsels',

        'status_success' => 'Suksesvol',
        'status_failed'  => 'Misluk',
        'unknown'        => 'Onbekend',
        'no_subject'     => '(Geen onderwerp)',
        'new_ticket'     => 'Nuwe kaartjie',
        'reply'          => 'Antwoord',
        'none'           => 'Geen',

        'row_title'  => 'Klik om JSON-besonderhede te bekyk',

        'pagination' => 'Bladsy {current} van {total} ({count} totaal)',
        'prev'       => 'Vorige',
        'next'       => 'Volgende',

        'modal_title' => 'Logboekbesonderhede (JSON)',
        'close'       => 'Maak toe',
    ],

    'tickets' => [
        'heading' => 'Kaartjie-paneelborde',
        'coming_soon' => 'KPI-paneelborde en verslagdoening vir kaartjieprestasie, oplostye en spanwerklading sal binnekort hier beskikbaar wees.',
    ],

    'intune' => [
        'heading'      => 'Intune-paneelbord',
        'loading_meta' => 'Laai…',
        'refresh'      => 'Verfris',
        'refresh_title'=> 'Verfris data',
        'loading_data' => 'Laai Intune-data…',

        'last_sync'    => 'Laaste sinkronisering: {when}',
        'error'        => 'Fout: {error}',
        'load_failed'  => 'Kon nie paneelbord laai nie: {error}',
        'no_devices_title' => 'Geen Intune-toestelle gevind nie.',
        'no_devices_body'  => 'Voer \'n Intune-sinkronisering vanaf die Bates-module uit om toestelle in te voer, en kom dan hierheen terug.',
        'no_data'      => 'Geen data',
        'unknown'      => 'Onbekend',

        // KPI strip
        'kpi_total'            => 'Totale toestelle',
        'kpi_total_sub'        => 'Alle bestuurde toestelle',
        'kpi_compliant'        => 'Nakomend',
        'kpi_compliant_sub'    => '{count} van {total}',
        'kpi_encrypted'        => 'Geënkripteer',
        'kpi_encrypted_sub'    => '{count} van {total}',
        'kpi_stale'            => 'Verouderd (30+ dae)',
        'kpi_stale_sub'        => 'Geen sinkronisering in laaste 30 dae nie',
        'kpi_enrolled'         => 'Ingeskryf (30 dae)',
        'kpi_enrolled_sub'     => 'Nuut in laaste 30 dae',

        'kpi_compliant_drill'  => 'Nakomende toestelle',
        'kpi_encrypted_drill'  => 'Geënkripteerde toestelle',
        'kpi_stale_drill'      => 'Verouderd (30+ dae)',
        'kpi_enrolled_drill'   => 'Ingeskryf in laaste 30 dae',

        // Widgets
        'w_compliance_title'   => 'Nakomingsuiteensetting',
        'w_compliance_desc'    => 'Toestelle volgens nakomingstoestand',
        'w_os_title'           => 'Bedryfstelsel',
        'w_os_desc'            => 'Toestelle gegroepeer volgens BS',
        'w_owner_title'        => 'Eienaartipe',
        'w_owner_desc'         => 'Korporatiewe teenoor persoonlike toestelle',
        'w_manufacturers_title'=> 'Top-vervaardigers',
        'w_manufacturers_desc' => 'Toestelle volgens vervaardiger (top 10)',
        'w_os_versions_title'  => 'Top-BS-weergawes',
        'w_os_versions_desc'   => 'Mees algemene BS + weergawe-kombinasies',
        'w_last_sync_title'    => 'Laaste sinkroniseringsvenster',
        'w_last_sync_desc'     => 'Hoe onlangs toestelle ingemeld het',
        'w_enrolment_title'    => 'Inskrywings (laaste 90 dae)',
        'w_enrolment_desc'     => 'Nuwe toestelle ingeskryf per dag',
        'w_encryption_title'   => 'Enkripsie volgens BS',
        'w_encryption_desc'    => 'Geënkripteer teenoor ongeënkripteer, per BS',

        // Chart tooltips / labels
        'tooltip_enrolled'     => '{count} ingeskryf (klik om in te delf)',
        'drill_enrolled_on'    => 'Ingeskryf op {date}',

        // Drill-down modal
        'drill_devices'        => 'Toestelle',
        'drill_loading'        => 'Laai…',
        'drill_count'          => '{count} toestel',
        'drill_count_plural'   => '{count} toestelle',
        'drill_no_match'       => 'Geen toestelle pas by hierdie filter nie.',
        'drill_error'          => 'Fout: {error}',
        'drill_load_failed'    => 'Kon nie laai nie: {error}',
        'drill_page_info'      => 'Bladsy {current} van {total}',
        'drill_prev'           => '‹ Vorige',
        'drill_next'           => 'Volgende ›',
        'drill_export'         => 'Voer CSV uit',
        'drill_close'          => 'Maak toe',

        'drill_col_device'     => 'Toestel',
        'drill_col_user'       => 'Gebruiker',
        'drill_col_os'         => 'BS',
        'drill_col_compliance' => 'Nakoming',
        'drill_col_encrypted'  => 'Geënkripteer',
        'drill_col_last_sync'  => 'Laaste sinkronisering',

        'never'                => 'Nooit',
        'yes'                  => 'Ja',
        'no'                   => 'Nee',
    ],

    'help' => [
        'page_title' => 'Verslagdoeninggids',
        'guide'      => 'Gids',

        'hero_heading' => 'Verslagdoeninggids',
        'hero_sub'     => 'Verander jou dienstoonbankdata in bruikbare insigte met logboeke, analise en paneelborde.',

        'nav_overview'           => 'Oorsig',
        'nav_ticket_reports'     => 'Kaartjieverslae',
        'nav_system_logs'        => 'Stelsellogboeke',
        'nav_understanding_data' => 'Verstaan die data',
        'nav_settings_filters'   => 'Instellings en filters',
        'nav_tips'               => 'Vinnige wenke',

        // Section 1: Overview
        's1_heading' => 'Oorsig',
        's1_intro'   => 'Die Verslagdoening-module bring alles wat oor jou dienstoonbank gebeur in een plek bymekaar. Volg kaartjieprestasie, monitor stelselaktiwiteit, hersien aanmeldpogings en oudit e-posinvoere — alles vanuit \'n enkele module wat ontwerp is om jou te help om neigings raak te sien en datagedrewe besluite te neem.',
        's1_card1_title' => 'Kaartjie-analise',
        's1_card1_body'  => 'Visualiseer kaartjievolume, oplostye, SLA-nakoming en spanwerklading deur interaktiewe paneelborde wat intyds opdateer.',
        's1_card2_title' => 'Stelsellogboeke',
        's1_card2_body'  => 'Hersien elke aanmeldpoging, e-posinvoer en stelselgebeurtenis in \'n deursoekbare, filterbare tabel met tydstempels en statusaanwysers.',
        's1_card3_title' => 'Aktiwiteitsopsporing',
        's1_card3_body'  => 'Monitor analis-aktiwiteit oor die platform — wie meld aan, aan watter kaartjies word gewerk, en waar word tyd spandeer.',
        's1_card4_title' => 'Ouditspoor',
        's1_card4_body'  => 'Elke aksie word aangeteken met wie dit gedoen het, wanneer, en wat verander het. Noodsaaklik vir nakoming, sekuriteitshersienings en probleemoplossing.',

        // Section 2: Ticket reports
        's2_heading' => 'Kaartjieverslae',
        's2_intro'   => 'Die Kaartjies-area van verslagdoening verskaf KPI-paneelborde wat jou \'n duidelike beeld gee van hoe jou dienstoonbank presteer. Hierdie paneelborde trek data direk uit jou kaartjierekords en bied dit aan deur grafieke en opsommingskaarte.',
        's2_card1_title' => 'Kaartjievolume',
        's2_card1_body'  => 'Sien hoeveel kaartjies geskep, opgelos en steeds oop is oor enige tydperk. Identifiseer besige dae en seisoenale patrone.',
        's2_card2_title' => 'SLA-nakoming',
        's2_card2_body'  => 'Volg watter persentasie kaartjies hul reaksie- en oplostikkens haal. Delf in volgens prioriteit of kategorie om probleemareas te vind.',
        's2_card3_title' => 'Oplostye',
        's2_card3_body'  => 'Meet die gemiddelde en mediaantyd om kaartjies op te los. Vergelyk oor spanne, kategorieë of prioriteitsvlakke om knelpunte raak te sien.',
        's2_card4_title' => 'Spanwerklading',
        's2_card4_body'  => 'Sien hoe kaartjies oor analiste versprei is. Identifiseer wie oorlaai is en wie kapasiteit het om meer werk te onderneem.',
        's2_card5_title' => 'Kategorie-uiteensetting',
        's2_card5_body'  => 'Verstaan watter tipes kwessies die meeste kaartjies genereer. Gebruik dit om opleiding, dokumentasie of selfdiensverbeterings te teiken.',
        's2_card6_title' => 'Neigingsontleding',
        's2_card6_body'  => 'Bekyk kaartjiedata oor weke, maande of kwartale om langtermynneigings raak te sien en die impak van prosesverbeterings te meet.',
        's2_tip'         => 'Kaartjie-paneelborde word verkry via die Kaartjies-oortjie in die opskrifnavigasie. Gebruik datumreeksfilters om verskillende tydperke langs mekaar te vergelyk.',

        // Section 3: System logs
        's3_heading' => 'Stelsellogboeke',
        's3_intro'   => 'Die Logboeke-area vang alles vas wat agter die skerms in jou FreeITSM-instansie gebeur. Elke aanmeldpoging, e-posinvoer en stelselgebeurtenis word aangeteken met \'n tydstempel en status sodat jy altyd \'n volledige beeld van platformaktiwiteit het.',
        's3_badge_login'  => 'AANMELD',
        's3_badge_email'  => 'E-POS',
        's3_badge_system' => 'STELSEL',
        's3_badge_audit'  => 'OUDIT',
        's3_login_title'  => 'Aanmeldpogings',
        's3_login_body'   => 'Elke suksesvolle en mislukte aanmelding word aangeteken met die analisnaam, IP-adres en tydstempel. Mislukte pogings word in rooi gemerk sodat jy ongemagtigde toegangspogings of uitgesluite gebruikers vinnig kan raaksien.',
        's3_email_title'  => 'E-posinvoere',
        's3_email_body'   => 'Wanneer die stelsel inkomende e-posse in kaartjies verwerk, word elke invoer aangeteken met die senderadres, onderwerpreël en of dit suksesvol omgeskakel is. Mislukte invoere wys die rede sodat jy teruggekaatste of wanvormde boodskappe kan ondersoek.',
        's3_system_title' => 'Stelselgebeurtenisse',
        's3_system_body'  => 'Agtergrondprosesse, geskeduleerde take, konfigurasieveranderings en API-aktiwiteit word alles hier vasgevang. Gebruik hierdie logboeke om te bevestig dat outomatiese take korrek loop en om kwessies te diagnoseer.',
        's3_audit_title'  => 'Ouditinskrywings',
        's3_audit_body'   => 'Veldvlak-veranderingsopsporing oor die platform. Sien presies wie wat verander het, wanneer, en wat die vorige waarde was. Van onskatbare waarde vir nakomingsvereistes en die oplossing van geskille.',
        's3_step1_title' => 'Maak die Logboeke-oortjie oop',
        's3_step1_body'  => 'klik op Logboeke in die opskrifnavigasie om toegang tot die stelsellogboekbekyker te kry.',
        's3_step2_title' => 'Wissel tussen logboektipes',
        's3_step2_body'  => 'gebruik die oortjiebalk bo-aan om volgens aanmeldpogings, e-posinvoere of stelselgebeurtenisse te filter.',
        's3_step3_title' => 'Hersien die besonderhede',
        's3_step3_body'  => 'elke ry wys \'n tydstempel, statuskenteken (suksesvol of misluk), en kontekstuele besonderhede soos IP-adresse, e-pos-onderwerpe of gebeurtenisbeskrywings.',
        's3_tip'         => 'Kontroleer aanmeldlogboeke gereeld vir herhaalde mislukte pogings vanaf onbekende IP-adresse. Dit kan op brute-krag-aanvalle of gekompromitteerde geloofsbriewe dui wat onmiddellike aandag vereis.',

        // Section 4: Understanding the data
        's4_heading' => 'Verstaan die data',
        's4_intro'   => 'Rou data word eers nuttig wanneer jy weet waarvoor om te soek. Hier is die sleutelmaatstawwe om dop te hou en hoe om dit te interpreteer om werklike verbeterings in jou dienstoonbankbedrywighede te dryf.',
        's4_metric1_title' => 'Eerste reaksietyd',
        's4_metric1_body'  => 'Hoe lank gebruikers wag voordat \'n analis hul kaartjie erken. \'n Stygende neiging hier beteken jou span is dalk onderbeman of kaartjies word nie doeltreffend gerou nie. Doelwit: onder jou SLA-drempel.',
        's4_metric2_title' => 'Oplostempo',
        's4_metric2_body'  => 'Die persentasie kaartjies wat binne \'n gegewe tydperk opgelos word teenoor dié wat geskep word. As meer kaartjies inkom as wat uitgaan, groei jou agterstand en moet jy die oorsaak ondersoek.',
        's4_metric3_title' => 'Herhaalde kontakte',
        's4_metric3_body'  => 'Kaartjies wat heropen word of gebruikers wat dieselfde kwessie verskeie kere opper. Hoë herhaalde kontaktempo\'s dui daarop dat die grondoorsaak nie aangespreek word nie, of dat oplossings nie duidelik gekommunikeer word nie.',
        's4_metric4_title' => 'Kategorie-brandpunte',
        's4_metric4_body'  => 'Watter kategorieë genereer mettertyd die meeste kaartjies. \'n Skerp styging in \'n bepaalde kategorie kan op \'n falende stelsel, \'n slegte sagteware-opdatering of \'n gaping in gebruikersopleiding dui wat aangespreek moet word.',
        's4_combine'     => 'Gebruik hierdie maatstawwe saam eerder as in isolasie. Byvoorbeeld, \'n hoë oplostempo gekombineer met \'n hoë herhaalde kontaktempo kan aandui dat kaartjies te vinnig gesluit word sonder om die onderliggende probleem op te los.',
        's4_tip'         => 'Skeduleer \'n weeklikse hersiening van jou sleutelmaatstawwe met die span. Patrone wat van dag tot dag onsigbaar is, word dikwels duidelik wanneer dit op \'n weeklikse of maandelikse ritme bekyk word.',

        // Section 5: Settings & filters
        's5_heading' => 'Instellings en filters',
        's5_intro'   => 'Beide die logboekbekyker en kaartjie-paneelborde ondersteun \'n reeks filters om jou te help om presies die data wat jy nodig het, in te perk. Doeltreffende gebruik van filters verander \'n muur van data in geteikende, bruikbare inligting.',
        's5_step1_title' => 'Datumreekse',
        's5_step1_body'  => 'filter logboeke en verslae tot \'n spesifieke tydvenster. Gebruik vooraf ingestelde reekse (vandag, hierdie week, hierdie maand) of stel pasgemaakte begin- en einddatums vir presiese beheer.',
        's5_step2_title' => 'Statusfilters',
        's5_step2_body'  => 'in die logboekbekyker, filter volgens sukses- of mislukkingsstatus om probleme vinnig te isoleer. In kaartjieverslae, filter volgens oop, opgelos of geslote status.',
        's5_step3_title' => 'Soek',
        's5_step3_body'  => 'gebruik die soekboks om spesifieke inskrywings volgens sleutelwoord te vind. In logboeke deursoek dit analisname, IP-adresse, e-pos-onderwerpe en gebeurtenisbeskrywings.',
        's5_step4_title' => 'Tydgroepering',
        's5_step4_body'  => 'in kaartjie-paneelborde, groepeer data volgens dag, week of maand om die korreligheid van jou grafieke te verander. Daaglikse aansigte wys korttermyn-stygings; maandelikse aansigte onthul langtermynneigings.',
        's5_step5_title' => 'Afdelingsfilters',
        's5_step5_body'  => 'verskerp paneelborduitslae tot \'n spesifieke afdeling om prestasie oor verskillende dele van die organisasie te vergelyk.',
        's5_tip'         => 'Kombineer verskeie filters vir geteikende ontleding. Byvoorbeeld, filter volgens \'n spesifieke afdeling en \'n datumreeks om te sien hoe \'n onlangse prosesverandering daardie span se kaartjievolume beïnvloed het.',

        // Section 6: Quick tips
        's6_heading' => 'Vinnige wenke',
        's6_tip1_title' => 'Hersien gereeld',
        's6_tip1_body'  => 'Verslae is die waardevolste wanneer dit konsekwent hersien word. Stel \'n ritme — weekliks vir bedryfsmaatstawwe, maandeliks vir neigingsontleding — en hou daarby.',
        's6_tip2_title' => 'Ondersoek afwykings',
        's6_tip2_body'  => '\'n Skielike styging of daling in enige maatstaf is \'n sein wat die moeite werd is om te ondersoek. Kyk na die logboeke vir konteks — was daar \'n stelselonderbreking, \'n sagteware-uitrol of \'n personeelverandering?',
        's6_tip3_title' => 'Vergelyk tydperke',
        's6_tip3_body'  => 'Gebruik datumfilters om hierdie week met verlede week te vergelyk, of hierdie maand met dieselfde maand verlede jaar. Relatiewe vergelykings onthul verbetering of agteruitgang duideliker as rou getalle.',
        's6_tip4_title' => 'Monitor sekuriteit',
        's6_tip4_body'  => 'Hou \'n ogie op mislukte aanmeldpogings in die stelsellogboeke. Herhaalde mislukkings vanaf dieselfde IP-adres of teen dieselfde rekening kan op \'n sekuriteitsbekommernis dui wat eskalasie vereis.',
    ],
];
