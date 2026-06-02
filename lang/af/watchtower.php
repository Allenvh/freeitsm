<?php
/**
 * Afrikaans (af) — Watchtower module strings.
 * Missing keys fall back to lang/en/watchtower.php per-key.
 */
return [
    'title' => 'Watchtower',

    'nav' => [
        'dashboard' => 'Paneelbord',
        'help'      => 'Hulp',
    ],

    'dashboard' => [
        'heading'      => 'Aandagoorsig',
        'refresh'      => 'Verfris',
        'updated'      => 'Bygewerk {time}',
    ],

    // Per-module card names shown in the card header (links to each module).
    'cards' => [
        'morning_checks' => 'Oggendtoetse',
        'tickets'        => 'Kaartjies',
        'changes'        => 'Veranderinge',
        'calendar'       => 'Kalender',
        'service_status' => 'Diensstatus',
        'contracts'      => 'Kontrakte',
        'knowledge'      => 'Kennis',
        'assets'         => 'Bates',
        'tasks'          => 'Take',
    ],

    // Morning Checks card.
    'mc' => [
        'metric_done' => 'Klaar',
        'metric_ok'   => 'Reg',
        'metric_warn' => 'Waarsku',
        'metric_fail' => 'Misluk',
        'not_started'      => 'Toetse vandag nog nie begin nie',
        'pending'          => '{count} toetse steeds hangende',
        'failed'           => '{count} toets(e) het misluk',
        'warnings'         => '{count} toets(e) met waarskuwings',
        'all_passing'      => 'Alle toetse voltooi en geslaag',
    ],

    // Tickets card.
    'tickets' => [
        'metric_open'   => 'Oop',
        'metric_new'    => 'Nuut',
        'metric_active' => 'Aktief',
        'metric_hold'   => 'Wag',
        'urgent_high'   => '<span class="wt-attention-bold">{count}</span> dringende/hoë prioriteit kaartjies',
        'unassigned'    => '<span class="wt-attention-bold">{count}</span> ongetoewysde kaartjies',
        'paused_one'    => '<span class="wt-attention-bold">{count}</span> kaartjie meer as {hours}u onderbreek (SLA-horlosie gestop)',
        'paused_many'   => '<span class="wt-attention-bold">{count}</span> kaartjies meer as {hours}u onderbreek (SLA-horlosie gestop)',
        'all_clear'     => 'Geen dringende items nie',
    ],

    // Changes card.
    'changes' => [
        'metric_next_7d' => 'Volgende 7d',
        'metric_active'  => 'Aktief',
        'metric_pending' => 'Hangende',
        'awaiting'       => '<span class="wt-attention-bold">{count}</span> verandering(e) wag op goedkeuring',
        'in_progress'    => '{count} verandering(e) tans aan die gang',
        'scheduled'      => '{count} verandering(e) hierdie week geskeduleer',
        'all_clear'      => 'Geen komende veranderinge nie',
    ],

    // Calendar card.
    'calendar' => [
        'metric_today' => 'Vandag',
        'metric_week'  => 'Hierdie week',
        'all_day'      => 'Heeldag',
        'no_events'    => 'Geen gebeure vandag nie',
    ],

    // Service Status card.
    'service' => [
        'all_operational' => 'Alle stelsels operasioneel',
        'active_incidents' => '<span class="wt-attention-bold">{count}</span> aktiewe voorval(le)',
    ],

    // Contracts card.
    'contracts' => [
        'metric_30d'     => '30 dae',
        'metric_90d'     => '90 dae',
        'metric_notices' => 'Kennisgewings',
        'expiring'       => '<span class="wt-attention-bold">{count}</span> kontrak(te) verstryk binne 30 dae',
        'notices'        => '<span class="wt-attention-bold">{count}</span> kennisgewingstydperk(e) nader',
        'all_clear'      => 'Geen kontrakte wat aandag verg nie',
    ],

    // Knowledge card.
    'knowledge' => [
        'overdue'         => '<span class="wt-attention-bold">{count}</span> artikel(s) agterstallig vir hersiening',
        'published_week'  => 'Hierdie week gepubliseer',
        'up_to_date'      => 'Kennisbasis op datum',
    ],

    // Assets card.
    'assets' => [
        'metric_total'    => 'Totaal',
        'metric_offline'  => 'Vanlyn',
        'metric_warranty' => 'Waarborg',
        'warranty'        => '<span class="wt-attention-bold">{count}</span> bate(s) met waarborg wat verstryk het of binne {days} dae verstryk',
        'offline'         => '<span class="wt-attention-bold">{count}</span> bate(s) wat in 7+ dae nie gesien is nie',
        'all_active'      => 'Alle bates onlangs aktief',
    ],

    // Tasks card.
    'tasks' => [
        'metric_todo'   => 'Om te doen',
        'metric_active' => 'Aktief',
        'overdue'       => '<span class="wt-attention-bold">{count}</span> agterstallige taak/take',
        'due_today'     => '<span class="wt-attention-bold">{count}</span> vandag verskuldig',
        'all_clear'     => 'Geen agterstallige take nie',
    ],

    // Help guide.
    'help' => [
        'page_title'   => 'Watchtower-gids',
        'sidebar_label' => 'Gids',
        'hero_title'   => 'Watchtower-gids',
        'hero_subtitle' => 'n Verenigde aandagpaneelbord wat uitvoerbare items van elke module met een oogopslag wys.',

        'nav_overview'  => 'Oorsig',
        'nav_layout'    => 'Die paneelbord-uitleg',
        'nav_dots'      => 'Verstaan statuskolletjies',
        'nav_cards'     => 'Modulekaarte verduidelik',
        'nav_refresh'   => 'Outo-verfris',
        'nav_tips'      => 'Vinnige wenke',

        // Section 1 — Overview
        's1_title' => 'Oorsig',
        's1_intro' => 'Watchtower is jou enkele venster op IT-bedrywighede. In plaas daarvan om elke module afsonderlik oop te maak om vir dringende items te kyk, trek Watchtower die belangrikste inligting uit elke module in een paneelbord. Met een oogopslag kan jy sien wat aandag verg, wat glad verloop, en waar om jou tyd te fokus.',
        's1_feat1_title' => 'Aandagbord',
        's1_feat1_desc'  => 'Sien wat jou fokus verg oor alle modules heen op een plek. Oggendtoetse, kaartjies, veranderinge, kalendergebeure, diensstatus, kontrakte, kennisartikels en bates word almal op een skerm opgesom.',
        's1_feat2_title' => 'Kleurgekodeerde status',
        's1_feat2_desc'  => 'Elke modulekaart vertoon n groen, amber of rooi statuskolletjie vir onmiddellike triage. Jy kan met een oogopslag sien watter areas gesond is, watter aandag verg, en watter onmiddellike optrede vereis.',
        's1_feat3_title' => 'Outo-verfris',
        's1_feat3_desc'  => 'Die paneelbord verfris outomaties elke 5 minute, sodat die inligting aktueel bly sonder enige handmatige optrede. Los Watchtower oop en dit hou homself op datum in die agtergrond.',
        's1_feat4_title' => 'Deurklik',
        's1_feat4_desc'  => 'Spring direk vanaf sy kaart in enige module in. Elke modulenaam is n klikbare skakel wat jou reguit na die betrokke area neem, sodat jy op probleme kan reageer sonder om vir die regte bladsy te soek.',

        // Section 2 — Dashboard layout
        's2_title' => 'Die paneelbord-uitleg',
        's2_p1' => 'Die Watchtower-paneelbord gebruik n responsiewe 3-kolom rooster van modulekaarte. Op kleiner skerms pas die rooster aan na 2 kolomme of n enkele kolom, sodat dit op enige toestel werk. Bo die rooster is die titelbalk met n verfris-knoppie en n "Bygewerk"-tydstempel wat wys wanneer data laas gehaal is.',
        's2_p2' => 'Elke kaart in die rooster volg n konsekwente struktuur sodat jy hulle vinnig kan deurkyk:',
        's2_diagram_name'   => 'Modulenaam',
        's2_diagram_open'   => 'OOP',
        's2_diagram_active' => 'AKTIEF',
        's2_diagram_hold'   => 'WAG',
        's2_diagram_clear'  => 'Alles reg — geen dringende items nie',
        's2_field_icon'    => '<strong>Gekleurde ikoon</strong> &mdash; n klein vierkantige ikoon in die module se temakleur (groenblou vir Oggendtoetse, blou vir Kaartjies, ens.) sodat jy elke kaart onmiddellik kan herken.',
        's2_field_name'    => '<strong>Modulenaam</strong> &mdash; n klikbare skakel wat direk na daardie module navigeer. Klik om reguit in te spring en op te tree.',
        's2_field_dot'     => '<strong>Statuskolletjie</strong> &mdash; n groen, amber of rooi kolletjie in die regterbohoek wat die algehele dringendheidsvlak vir daardie module wys.',
        's2_field_metrics' => '<strong>Sleutelmaatstawwe</strong> &mdash; groot getalle wat die belangrikste tellings opsom (bv. oop kaartjies, voltooide toetse, kontrakte wat verstryk).',
        's2_field_attention' => '<strong>Aandagitems</strong> &mdash; kleurgekodeerde boodskaprye wat uitlig wat spesifiek jou aandag verg binne daardie module.',
        's2_tip' => 'Die kaartuitleg is ontwerp vir deurkyk, nie diep ontleding nie. Gebruik Watchtower om te identifiseer watter modules jou aandag verg, klik dan deur na die module self vir volle besonderhede.',

        // Section 3 — Status dots
        's3_title' => 'Verstaan statuskolletjies',
        's3_intro' => 'Elke modulekaart vertoon n statuskolletjie in sy kopstuk. Hierdie kolletjie verskaf n onmiddellike visuele aanduiding van of daardie area van jou IT-bedrywighede aandag verg. Die kleur word outomaties bepaal op grond van die data wat van elke module terugkom.',
        's3_green_label' => 'Groen',
        's3_green_desc'  => 'Alles is reg. Geen optrede nodig nie. Die module is in n gesonde toestand sonder uitstaande probleme of items wat aandag verg.',
        's3_green_examples' => '<strong>Voorbeelde:</strong> Alle oggendtoetse geslaag, geen dringende kaartjies, alle stelsels operasioneel, geen kontrakte wat binnekort verstryk nie.',
        's3_amber_label' => 'Amber',
        's3_amber_desc'  => 'Iets verg aandag maar is nie krities nie. Daar is items wat jy moet nagaan wanneer jy kans kry, maar niks is dringend nie.',
        's3_amber_examples' => '<strong>Voorbeelde:</strong> Toetse met waarskuwings, ongetoewysde kaartjies, veranderinge wat op goedkeuring wag, kontrakte wat binne 90 dae verstryk.',
        's3_red_label' => 'Rooi',
        's3_red_desc'  => 'Dringende items vereis onmiddellike optrede. Iets het misluk, is agterstallig, of is krities geraak en moet dadelik aangespreek word.',
        's3_red_examples' => '<strong>Voorbeelde:</strong> Oggendtoetse nie begin of misluk, dringende/hoë prioriteit kaartjies, groot diensonderbrekings, kontrakte wat binne 30 dae verstryk.',
        's3_tip' => 'Dink aan die kolletjies soos n verkeerslig. Groen beteken gaan voort met jou dag, amber beteken kyk na wanneer moontlik, en rooi beteken stop wat jy doen en ondersoek. Die doel is om alle kolletjies groen te hou.',

        // Section 4 — Module cards explained
        's4_title' => 'Modulekaarte verduidelik',
        's4_intro' => 'Watchtower monitor agt modules. Elke kaart is aangepas om die mees relevante inligting vir daardie area te wys. Hier is wat elke kaart vertoon en wat sy statuskolletjie se kleur sneller.',
        's4_mc_title'    => 'Oggendtoetse',
        's4_mc_desc'     => 'Wys voltooiingsvordering (bv. 8/10 klaar) plus tellings van Reg-, Waarskuwing- en Misluk-resultate. Aandagitems vlag wanneer toetse nie begin is nie of wanneer enige misluk het.',
        's4_mc_triggers' => '<strong>Rooi:</strong> Toetse vandag nie begin nie, of enige toetse het misluk. <strong>Amber:</strong> Toetse onvolledig of waarskuwings teenwoordig. <strong>Groen:</strong> Alle toetse voltooi en geslaag.',
        's4_tk_title'    => 'Kaartjies',
        's4_tk_desc'     => 'Vertoon die totale oop telling opgedeel in Nuut, Aktief en Wag. Aandagitems lig dringende/hoë prioriteit kaartjies uit en enige wat ongetoewys is.',
        's4_tk_triggers' => '<strong>Rooi:</strong> Dringende of hoë prioriteit kaartjies bestaan. <strong>Amber:</strong> Ongetoewysde kaartjies teenwoordig. <strong>Groen:</strong> Geen dringende items of ongetoewysde kaartjies nie.',
        's4_ch_title'    => 'Veranderinge',
        's4_ch_desc'     => 'Wys die aantal veranderinge wat in die volgende 7 dae geskeduleer is, hoeveel tans aan die gang is, en hoeveel op goedkeuring wag. Aandagitems lig ongekeurde en aktiewe veranderinge uit.',
        's4_ch_triggers' => '<strong>Amber:</strong> Veranderinge wat op goedkeuring wag. <strong>Groen:</strong> Geen ongekeurde veranderinge nie.',
        's4_cal_title'    => 'Kalender',
        's4_cal_desc'     => 'Vertoon die aantal gebeure vandag en hierdie week. As daar gebeure vandag is, word hulle met hul tye gelys (of "Heeldag" vir heeldag-gebeure).',
        's4_cal_triggers' => '<strong>Amber:</strong> Gebeure vir vandag geskeduleer. <strong>Groen:</strong> Geen gebeure vandag nie.',
        's4_ss_title'    => 'Diensstatus',
        's4_ss_desc'     => 'Wys die telling van aktiewe voorvalle en lys geaffekteerde dienste met hul impakvlak-kentekens (Groot Onderbreking, Gedeeltelike Onderbreking, Verswak, Onderhoud). Wanneer alles gesond is, verskyn n groen "Alle stelsels operasioneel"-baniere.',
        's4_ss_triggers' => '<strong>Rooi:</strong> Groot of gedeeltelike onderbreking op enige diens. <strong>Amber:</strong> Verswak- of onderhoudstatus. <strong>Groen:</strong> Alle stelsels operasioneel.',
        's4_ct_title'    => 'Kontrakte',
        's4_ct_desc'     => 'Vertoon kontrakte wat binne 30 dae verstryk, binne 90 dae, en kennisgewingstydperke wat nader. Aandagitems waarsku oor naderende verstrykings en komende kennisgewingsperke.',
        's4_ct_triggers' => '<strong>Rooi:</strong> Kontrakte wat binne 30 dae verstryk. <strong>Amber:</strong> Kontrakte wat binne 90 dae verstryk of kennisgewingstydperke wat nader. <strong>Groen:</strong> Geen kontrakte wat aandag verg nie.',
        's4_kb_title'    => 'Kennis',
        's4_kb_desc'     => 'Wys die aantal artikels wat agterstallig is vir hersiening en lys onlangs gepubliseerde artikels van hierdie week. Wanneer geen hersienings agterstallig is nie en die kennisbasis aktueel is, wys die kaart n alles-reg-boodskap.',
        's4_kb_triggers' => '<strong>Amber:</strong> Artikels agterstallig vir hersiening. <strong>Groen:</strong> Kennisbasis op datum.',
        's4_as_title'    => 'Bates',
        's4_as_desc'     => 'Vertoon die totale aantal nagespoorde bates en hoeveel in 7 of meer dae nie gesien is nie. Dit help om toestelle te identifiseer wat dalk vanlyn, ontbind of verlore is.',
        's4_as_triggers' => '<strong>Amber:</strong> Bates wat in 7+ dae nie gesien is nie. <strong>Groen:</strong> Alle bates onlangs aktief.',

        // Section 5 — Auto-refresh
        's5_title' => 'Outo-verfris en handmatige verfris',
        's5_intro' => 'Watchtower is ontwerp om n passiewe moniteringshulpmiddel te wees wat jy in n blaaieroortjie oop kan los regdeur die dag. Die paneelbord hou homself aktueel deur outomatiese verfris-siklusse.',
        's5_step1' => '<strong>Outomatiese verfris</strong> &mdash; die paneelbord haal vars data van alle modules elke 5 minute. Jy hoef nie die bladsy te herlaai of enigiets te klik nie; die kaarte en statuskolletjies werk stil in die agtergrond by.',
        's5_step2' => '<strong>Handmatige verfris</strong> &mdash; klik die <strong>Verfris</strong>-knoppie in die regterbohoek om die jongste data onmiddellik te haal. Die knoppie-ikoon draai terwyl die versoek aan die gang is, wat bevestig dat nuwe data gelaai word.',
        's5_step3' => '<strong>Bygewerk-tydstempel</strong> &mdash; langs die verfris-knoppie wys n tydstempel die laaste keer wat data gehaal is (bv. "Bygewerk 09:15"). Dit vertel jou presies hoe aktueel die vertoonde inligting is.',
        's5_tip' => 'Hou Watchtower oop in n toegewyde blaaieroortjie vir passiewe monitering. Die 5-minute verfris-siklus beteken jy het altyd n byna-intydse uitsig op jou IT-bedrywighede sonder om elke module handmatig na te gaan.',

        // Section 6 — Quick tips
        's6_title' => 'Vinnige wenke',
        's6_tip1_title' => 'Begin jou dag hier',
        's6_tip1_desc'  => 'Maak Watchtower elke oggend eerste oop vir n vinnige operasionele oorsig. In sekondes kan jy sien of oggendtoetse klaar is, of enige kaartjies dringend is, en of alle dienste gesond is.',
        's6_tip2_title' => 'Rooi kolletjies eerste',
        's6_tip2_desc'  => 'Spreek rooi statuskolletjies aan voor enigiets anders. Hierdie dui dringende items aan wat onmiddellike aandag verg &mdash; mislukte toetse, hoë-prioriteit kaartjies, of diensonderbrekings wat gebruikers aktief raak.',
        's6_tip3_title' => 'Klik om in te spring',
        's6_tip3_desc'  => 'Klik enige modulenaam op n kaart om reguit na daardie module te navigeer. Geen behoefte om die hoofkieslys of waffelnavigasie te gebruik nie &mdash; Watchtower tree op as n direkte kortpad na waar aandag nodig is.',
        's6_tip4_title' => 'Druk Verfris vir die jongste',
        's6_tip4_desc'  => 'Terwyl die paneelbord elke 5 minute outo-verfris, kan jy die Verfris-knoppie enige tyd klik wanneer jy die allernuutste data wil hê. Nuttig nadat n probleem opgelos is om te bevestig dat die statuskolletjie verander het.',
        's6_tip5_title' => 'Gebruik dit in spanvergaderings',
        's6_tip5_desc'  => 'Projekteer Watchtower op n skerm tydens staanvergaderings of operasionele hersieningsvergaderings. Die kleurgekodeerde kolletjies maak dit maklik om te bespreek watter areas aandag verg en eienaarskap van amber of rooi items toe te wys.',
        's6_tip6_title' => 'Groen beteken alles reg',
        's6_tip6_desc'  => 'Wanneer elke kolletjie op die paneelbord groen is, is jou IT-bedrywighede in goeie toestand. Geen dringende kaartjies, geen mislukte toetse, geen kontrakte wat verstryk nie, en alle dienste operasioneel. Dit is die doel.',
    ],
];
