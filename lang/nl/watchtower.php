<?php
/**
 * Nederlands (nl) — Watchtower module strings.
 * Missing keys fall back to lang/en/watchtower.php per-key.
 */
return [
    'title' => 'Watchtower',

    'nav' => [
        'dashboard' => 'Dashboard',
        'help'      => 'Help',
    ],

    'dashboard' => [
        'heading'      => 'Aandachtsoverzicht',
        'refresh'      => 'Vernieuwen',
        'updated'      => 'Bijgewerkt {time}',
    ],

    // Per-module card names shown in the card header (links to each module).
    'cards' => [
        'morning_checks' => 'Ochtendcontroles',
        'tickets'        => 'Tickets',
        'changes'        => 'Wijzigingen',
        'calendar'       => 'Agenda',
        'service_status' => 'Servicestatus',
        'contracts'      => 'Contracten',
        'knowledge'      => 'Kennis',
        'assets'         => 'Assets',
        'tasks'          => 'Taken',
    ],

    // Morning Checks card.
    'mc' => [
        'metric_done' => 'Klaar',
        'metric_ok'   => 'OK',
        'metric_warn' => 'Waarsch.',
        'metric_fail' => 'Mislukt',
        'not_started'      => 'Controles vandaag nog niet gestart',
        'pending'          => '{count} controles nog in behandeling',
        'failed'           => '{count} controle(s) mislukt',
        'warnings'         => '{count} controle(s) met waarschuwingen',
        'all_passing'      => 'Alle controles voltooid en geslaagd',
    ],

    // Tickets card.
    'tickets' => [
        'metric_open'   => 'Open',
        'metric_new'    => 'Nieuw',
        'metric_active' => 'Actief',
        'metric_hold'   => 'In wacht',
        'urgent_high'   => '<span class="wt-attention-bold">{count}</span> tickets met urgente/hoge prioriteit',
        'unassigned'    => '<span class="wt-attention-bold">{count}</span> niet-toegewezen tickets',
        'paused_one'    => '<span class="wt-attention-bold">{count}</span> ticket langer dan {hours} u gepauzeerd (SLA-klok gestopt)',
        'paused_many'   => '<span class="wt-attention-bold">{count}</span> tickets langer dan {hours} u gepauzeerd (SLA-klok gestopt)',
        'all_clear'     => 'Geen urgente items',
    ],

    // Changes card.
    'changes' => [
        'metric_next_7d' => 'Komende 7d',
        'metric_active'  => 'Actief',
        'metric_pending' => 'In behandeling',
        'awaiting'       => '<span class="wt-attention-bold">{count}</span> wijziging(en) wachten op goedkeuring',
        'in_progress'    => '{count} wijziging(en) nu in uitvoering',
        'scheduled'      => '{count} wijziging(en) deze week gepland',
        'all_clear'      => 'Geen aankomende wijzigingen',
    ],

    // Calendar card.
    'calendar' => [
        'metric_today' => 'Vandaag',
        'metric_week'  => 'Deze week',
        'all_day'      => 'Hele dag',
        'no_events'    => 'Geen afspraken vandaag',
    ],

    // Service Status card.
    'service' => [
        'all_operational' => 'Alle systemen operationeel',
        'active_incidents' => '<span class="wt-attention-bold">{count}</span> actief/actieve incident(en)',
    ],

    // Contracts card.
    'contracts' => [
        'metric_30d'     => '30 dagen',
        'metric_90d'     => '90 dagen',
        'metric_notices' => 'Opzegtermijnen',
        'expiring'       => '<span class="wt-attention-bold">{count}</span> contract(en) verlopen binnen 30 dagen',
        'notices'        => '<span class="wt-attention-bold">{count}</span> opzegtermijn(en) naderen',
        'all_clear'      => 'Geen contracten die aandacht vereisen',
    ],

    // Knowledge card.
    'knowledge' => [
        'overdue'         => '<span class="wt-attention-bold">{count}</span> artikel(en) over de revisietermijn heen',
        'published_week'  => 'Deze week gepubliceerd',
        'up_to_date'      => 'Kennisbank is up-to-date',
    ],

    // Assets card.
    'assets' => [
        'metric_total'    => 'Totaal',
        'metric_offline'  => 'Offline',
        'metric_warranty' => 'Garantie',
        'warranty'        => '<span class="wt-attention-bold">{count}</span> asset(s) met verlopen garantie of verloopt binnen {days} dagen',
        'offline'         => '<span class="wt-attention-bold">{count}</span> asset(s) al 7+ dagen niet gezien',
        'all_active'      => 'Alle assets recent actief',
    ],

    // Tasks card.
    'tasks' => [
        'metric_todo'   => 'Te doen',
        'metric_active' => 'Actief',
        'overdue'       => '<span class="wt-attention-bold">{count}</span> achterstallige ta(a)k(en)',
        'due_today'     => '<span class="wt-attention-bold">{count}</span> moet(en) vandaag af',
        'all_clear'     => 'Geen achterstallige taken',
    ],

    // Help guide.
    'help' => [
        'page_title'   => 'Watchtower-handleiding',
        'sidebar_label' => 'Handleiding',
        'hero_title'   => 'Watchtower-handleiding',
        'hero_subtitle' => 'Een gebundeld aandachtsdashboard dat in één oogopslag actiegerichte items uit elke module toont.',

        'nav_overview'  => 'Overzicht',
        'nav_layout'    => 'De dashboardindeling',
        'nav_dots'      => 'Statusbolletjes begrijpen',
        'nav_cards'     => 'Modulekaarten uitgelegd',
        'nav_refresh'   => 'Automatisch vernieuwen',
        'nav_tips'      => 'Snelle tips',

        // Section 1 — Overview
        's1_title' => 'Overzicht',
        's1_intro' => 'Watchtower is uw centrale venster op de IT-operaties. In plaats van elke module afzonderlijk te openen om op urgente items te controleren, haalt Watchtower de belangrijkste informatie uit elke module naar één dashboard. In één oogopslag ziet u wat aandacht nodig heeft, wat soepel verloopt en waar u uw tijd op moet richten.',
        's1_feat1_title' => 'Aandachtsbord',
        's1_feat1_desc'  => 'Zie op één plek wat over alle modules heen uw aandacht nodig heeft. Ochtendcontroles, tickets, wijzigingen, agenda-afspraken, servicestatus, contracten, kennisartikelen en assets worden allemaal op één scherm samengevat.',
        's1_feat2_title' => 'Kleurgecodeerde status',
        's1_feat2_desc'  => 'Elke modulekaart toont een groen, oranje of rood statusbolletje voor directe triage. U ziet in één oogopslag welke gebieden gezond zijn, welke aandacht nodig hebben en welke onmiddellijke actie vereisen.',
        's1_feat3_title' => 'Automatisch vernieuwen',
        's1_feat3_desc'  => 'Het dashboard vernieuwt automatisch elke 5 minuten, zodat de informatie actueel blijft zonder handmatige actie. Laat Watchtower openstaan en het houdt zichzelf op de achtergrond up-to-date.',
        's1_feat4_title' => 'Doorklikken',
        's1_feat4_desc'  => 'Spring rechtstreeks vanuit de kaart in elke module. Elke modulenaam is een aanklikbare link die u direct naar het relevante gebied brengt, zodat u op problemen kunt reageren zonder de juiste pagina te hoeven zoeken.',

        // Section 2 — Dashboard layout
        's2_title' => 'De dashboardindeling',
        's2_p1' => 'Het Watchtower-dashboard gebruikt een responsief raster van 3 kolommen met modulekaarten. Op kleinere schermen past het raster zich aan naar 2 kolommen of één kolom, zodat het op elk apparaat werkt. Boven het raster bevindt zich de titelbalk met een vernieuwknop en een tijdstempel "Bijgewerkt" die aangeeft wanneer de gegevens voor het laatst zijn opgehaald.',
        's2_p2' => 'Elke kaart in het raster volgt een consistente opbouw zodat u ze snel kunt scannen:',
        's2_diagram_name'   => 'Modulenaam',
        's2_diagram_open'   => 'OPEN',
        's2_diagram_active' => 'ACTIEF',
        's2_diagram_hold'   => 'IN WACHT',
        's2_diagram_clear'  => 'Alles in orde — geen urgente items',
        's2_field_icon'    => '<strong>Gekleurd pictogram</strong> &mdash; een klein vierkant pictogram in de themakleur van de module (turquoise voor Ochtendcontroles, blauw voor Tickets, enz.) zodat u elke kaart direct herkent.',
        's2_field_name'    => '<strong>Modulenaam</strong> &mdash; een aanklikbare link die rechtstreeks naar die module navigeert. Klik om er direct in te springen en actie te ondernemen.',
        's2_field_dot'     => '<strong>Statusbolletje</strong> &mdash; een groen, oranje of rood bolletje in de rechterbovenhoek dat het algehele urgentieniveau voor die module toont.',
        's2_field_metrics' => '<strong>Belangrijkste cijfers</strong> &mdash; grote getallen die de belangrijkste aantallen samenvatten (bijv. open tickets, voltooide controles, verlopende contracten).',
        's2_field_attention' => '<strong>Aandachtsitems</strong> &mdash; kleurgecodeerde berichtregels die benadrukken wat binnen die module specifiek uw aandacht nodig heeft.',
        's2_tip' => 'De kaartindeling is ontworpen om te scannen, niet voor diepgaande analyse. Gebruik Watchtower om te bepalen welke modules uw aandacht nodig hebben en klik vervolgens door naar de module zelf voor alle details.',

        // Section 3 — Status dots
        's3_title' => 'Statusbolletjes begrijpen',
        's3_intro' => 'Elke modulekaart toont een statusbolletje in de header. Dit bolletje geeft een directe visuele indicatie of dat gebied van uw IT-operaties aandacht nodig heeft. De kleur wordt automatisch bepaald op basis van de gegevens die elke module teruggeeft.',
        's3_green_label' => 'Groen',
        's3_green_desc'  => 'Alles is in orde. Geen actie nodig. De module is in een gezonde staat zonder openstaande problemen of items die aandacht vereisen.',
        's3_green_examples' => '<strong>Voorbeelden:</strong> Alle ochtendcontroles geslaagd, geen urgente tickets, alle systemen operationeel, geen contracten die binnenkort verlopen.',
        's3_amber_label' => 'Oranje',
        's3_amber_desc'  => 'Iets heeft aandacht nodig maar is niet kritiek. Er zijn items die u zou moeten bekijken wanneer u de kans hebt, maar er is geen brand.',
        's3_amber_examples' => '<strong>Voorbeelden:</strong> Controles met waarschuwingen, niet-toegewezen tickets, wijzigingen die op goedkeuring wachten, contracten die binnen 90 dagen verlopen.',
        's3_red_label' => 'Rood',
        's3_red_desc'  => 'Urgente items vereisen onmiddellijke actie. Iets is mislukt, achterstallig of kritiek getroffen en moet meteen worden aangepakt.',
        's3_red_examples' => '<strong>Voorbeelden:</strong> Ochtendcontroles niet gestart of mislukt, tickets met urgente/hoge prioriteit, grote serviceonderbrekingen, contracten die binnen 30 dagen verlopen.',
        's3_tip' => 'Zie de bolletjes als een verkeerslicht. Groen betekent ga rustig verder met uw dag, oranje betekent bekijk het zodra het kan, en rood betekent stop met wat u doet en ga onderzoeken. Het doel is om alle bolletjes groen te houden.',

        // Section 4 — Module cards explained
        's4_title' => 'Modulekaarten uitgelegd',
        's4_intro' => 'Watchtower bewaakt acht modules. Elke kaart is afgestemd om de meest relevante informatie voor dat gebied te tonen. Hier ziet u wat elke kaart toont en wat de kleur van het statusbolletje bepaalt.',
        's4_mc_title'    => 'Ochtendcontroles',
        's4_mc_desc'     => 'Toont de voortgang van de voltooiing (bijv. 8/10 klaar) plus aantallen van OK-, Waarschuwings- en Mislukt-resultaten. Aandachtsitems signaleren wanneer controles niet zijn gestart of wanneer er een zijn mislukt.',
        's4_mc_triggers' => '<strong>Rood:</strong> Controles vandaag niet gestart, of controles mislukt. <strong>Oranje:</strong> Controles onvolledig of waarschuwingen aanwezig. <strong>Groen:</strong> Alle controles voltooid en geslaagd.',
        's4_tk_title'    => 'Tickets',
        's4_tk_desc'     => 'Toont het totale aantal open tickets, uitgesplitst in Nieuw, Actief en In wacht. Aandachtsitems benadrukken tickets met urgente/hoge prioriteit en niet-toegewezen tickets.',
        's4_tk_triggers' => '<strong>Rood:</strong> Er zijn tickets met urgente of hoge prioriteit. <strong>Oranje:</strong> Niet-toegewezen tickets aanwezig. <strong>Groen:</strong> Geen urgente items of niet-toegewezen tickets.',
        's4_ch_title'    => 'Wijzigingen',
        's4_ch_desc'     => 'Toont het aantal wijzigingen dat in de komende 7 dagen is gepland, hoeveel er momenteel in uitvoering zijn en hoeveel er op goedkeuring wachten. Aandachtsitems wijzen op niet-goedgekeurde en actieve wijzigingen.',
        's4_ch_triggers' => '<strong>Oranje:</strong> Wijzigingen wachten op goedkeuring. <strong>Groen:</strong> Geen niet-goedgekeurde wijzigingen.',
        's4_cal_title'    => 'Agenda',
        's4_cal_desc'     => 'Toont het aantal afspraken vandaag en deze week. Als er vandaag afspraken zijn, worden ze met hun tijden vermeld (of "Hele dag" voor afspraken die de hele dag duren).',
        's4_cal_triggers' => '<strong>Oranje:</strong> Afspraken gepland voor vandaag. <strong>Groen:</strong> Geen afspraken vandaag.',
        's4_ss_title'    => 'Servicestatus',
        's4_ss_desc'     => 'Toont het aantal actieve incidenten en vermeldt getroffen services met hun impactniveau-badges (Grote storing, Gedeeltelijke storing, Verminderd, Onderhoud). Wanneer alles gezond is, verschijnt een groene banner "Alle systemen operationeel".',
        's4_ss_triggers' => '<strong>Rood:</strong> Grote of gedeeltelijke storing op een service. <strong>Oranje:</strong> Status verminderd of onderhoud. <strong>Groen:</strong> Alle systemen operationeel.',
        's4_ct_title'    => 'Contracten',
        's4_ct_desc'     => 'Toont contracten die binnen 30 dagen verlopen, binnen 90 dagen, en naderende opzegtermijnen. Aandachtsitems waarschuwen voor op handen zijnde verlopen en aankomende opzegdeadlines.',
        's4_ct_triggers' => '<strong>Rood:</strong> Contracten die binnen 30 dagen verlopen. <strong>Oranje:</strong> Contracten die binnen 90 dagen verlopen of naderende opzegtermijnen. <strong>Groen:</strong> Geen contracten die aandacht vereisen.',
        's4_kb_title'    => 'Kennis',
        's4_kb_desc'     => 'Toont het aantal artikelen dat over de revisietermijn heen is en vermeldt recent gepubliceerde artikelen van deze week. Wanneer er geen revisies achterstallig zijn en de kennisbank actueel is, toont de kaart een melding dat alles in orde is.',
        's4_kb_triggers' => '<strong>Oranje:</strong> Artikelen over de revisietermijn heen. <strong>Groen:</strong> Kennisbank is up-to-date.',
        's4_as_title'    => 'Assets',
        's4_as_desc'     => 'Toont het totale aantal gevolgde assets en hoeveel er al 7 of meer dagen niet zijn gezien. Dit helpt apparaten te identificeren die mogelijk offline, buiten gebruik gesteld of kwijt zijn.',
        's4_as_triggers' => '<strong>Oranje:</strong> Assets al 7+ dagen niet gezien. <strong>Groen:</strong> Alle assets recent actief.',

        // Section 5 — Auto-refresh
        's5_title' => 'Automatisch en handmatig vernieuwen',
        's5_intro' => 'Watchtower is ontworpen als een passief bewakingshulpmiddel dat u de hele dag in een browsertabblad kunt openhouden. Het dashboard houdt zichzelf actueel via automatische vernieuwingscycli.',
        's5_step1' => '<strong>Automatisch vernieuwen</strong> &mdash; het dashboard haalt elke 5 minuten verse gegevens op uit alle modules. U hoeft de pagina niet te herladen of ergens op te klikken; de kaarten en statusbolletjes werken op de achtergrond geruisloos bij.',
        's5_step2' => '<strong>Handmatig vernieuwen</strong> &mdash; klik op de knop <strong>Vernieuwen</strong> in de rechterbovenhoek om direct de nieuwste gegevens op te halen. Het pictogram van de knop draait terwijl het verzoek loopt, ter bevestiging dat er nieuwe gegevens worden geladen.',
        's5_step3' => '<strong>Tijdstempel Bijgewerkt</strong> &mdash; naast de vernieuwknop toont een tijdstempel wanneer de gegevens voor het laatst zijn opgehaald (bijv. "Bijgewerkt 09:15"). Dit vertelt u precies hoe actueel de getoonde informatie is.',
        's5_tip' => 'Houd Watchtower open in een speciaal browsertabblad voor passieve bewaking. Dankzij de vernieuwingscyclus van 5 minuten hebt u altijd een bijna realtime overzicht van uw IT-operaties zonder elke module handmatig te hoeven controleren.',

        // Section 6 — Quick tips
        's6_title' => 'Snelle tips',
        's6_tip1_title' => 'Begin hier uw dag',
        's6_tip1_desc'  => 'Open Watchtower als eerste elke ochtend voor een snel operationeel overzicht. Binnen enkele seconden ziet u of de ochtendcontroles klaar zijn, of er urgente tickets zijn en of alle services gezond zijn.',
        's6_tip2_title' => 'Rode bolletjes eerst',
        's6_tip2_desc'  => 'Pak rode statusbolletjes vóór al het andere aan. Deze duiden op urgente items die onmiddellijke aandacht nodig hebben &mdash; mislukte controles, tickets met hoge prioriteit of serviceonderbrekingen die gebruikers actief treffen.',
        's6_tip3_title' => 'Klik om in te springen',
        's6_tip3_desc'  => 'Klik op een willekeurige modulenaam op een kaart om rechtstreeks naar die module te navigeren. Geen hoofdmenu of wafelnavigatie nodig &mdash; Watchtower fungeert als directe snelkoppeling naar waar aandacht nodig is.',
        's6_tip4_title' => 'Klik op Vernieuwen voor het nieuwste',
        's6_tip4_desc'  => 'Hoewel het dashboard elke 5 minuten automatisch vernieuwt, kunt u op de knop Vernieuwen klikken wanneer u de allernieuwste gegevens wilt. Handig nadat u een probleem hebt opgelost om te bevestigen dat het statusbolletje is veranderd.',
        's6_tip5_title' => 'Gebruik het in teamvergaderingen',
        's6_tip5_desc'  => 'Projecteer Watchtower op een scherm tijdens stand-ups of operationele reviewvergaderingen. De kleurgecodeerde bolletjes maken het gemakkelijk te bespreken welke gebieden aandacht nodig hebben en het eigenaarschap van oranje of rode items toe te wijzen.',
        's6_tip6_title' => 'Groen betekent alles in orde',
        's6_tip6_desc'  => 'Wanneer elk bolletje op het dashboard groen is, zijn uw IT-operaties in goede vorm. Geen urgente tickets, geen mislukte controles, geen verlopende contracten en alle services operationeel. Dat is het doel.',
    ],
];
