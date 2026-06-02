<?php
/**
 * Français (fr) — Watchtower module strings.
 * Missing keys fall back to lang/en/watchtower.php per-key.
 */
return [
    'title' => 'Watchtower',

    'nav' => [
        'dashboard' => 'Tableau de bord',
        'help'      => 'Aide',
    ],

    'dashboard' => [
        'heading'      => 'Aperçu des points d\'attention',
        'refresh'      => 'Actualiser',
        'updated'      => 'Mis à jour {time}',
    ],

    // Per-module card names shown in the card header (links to each module).
    'cards' => [
        'morning_checks' => 'Vérifications du matin',
        'tickets'        => 'Tickets',
        'changes'        => 'Changements',
        'calendar'       => 'Calendrier',
        'service_status' => 'État des services',
        'contracts'      => 'Contrats',
        'knowledge'      => 'Connaissances',
        'assets'         => 'Actifs',
        'tasks'          => 'Tâches',
    ],

    // Morning Checks card.
    'mc' => [
        'metric_done' => 'Faites',
        'metric_ok'   => 'OK',
        'metric_warn' => 'Alerte',
        'metric_fail' => 'Échec',
        'not_started'      => 'Vérifications non commencées aujourd\'hui',
        'pending'          => '{count} vérifications encore en attente',
        'failed'           => '{count} vérification(s) en échec',
        'warnings'         => '{count} vérification(s) avec avertissements',
        'all_passing'      => 'Toutes les vérifications terminées et réussies',
    ],

    // Tickets card.
    'tickets' => [
        'metric_open'   => 'Ouverts',
        'metric_new'    => 'Nouveaux',
        'metric_active' => 'Actifs',
        'metric_hold'   => 'En attente',
        'urgent_high'   => '<span class="wt-attention-bold">{count}</span> tickets urgents/haute priorité',
        'unassigned'    => '<span class="wt-attention-bold">{count}</span> tickets non assignés',
        'paused_one'    => '<span class="wt-attention-bold">{count}</span> ticket en pause depuis plus de {hours}h (chrono SLA arrêté)',
        'paused_many'   => '<span class="wt-attention-bold">{count}</span> tickets en pause depuis plus de {hours}h (chrono SLA arrêté)',
        'all_clear'     => 'Aucun élément urgent',
    ],

    // Changes card.
    'changes' => [
        'metric_next_7d' => '7 prochains j',
        'metric_active'  => 'Actifs',
        'metric_pending' => 'En attente',
        'awaiting'       => '<span class="wt-attention-bold">{count}</span> changement(s) en attente d\'approbation',
        'in_progress'    => '{count} changement(s) en cours actuellement',
        'scheduled'      => '{count} changement(s) planifié(s) cette semaine',
        'all_clear'      => 'Aucun changement à venir',
    ],

    // Calendar card.
    'calendar' => [
        'metric_today' => 'Aujourd\'hui',
        'metric_week'  => 'Cette semaine',
        'all_day'      => 'Toute la journée',
        'no_events'    => 'Aucun événement aujourd\'hui',
    ],

    // Service Status card.
    'service' => [
        'all_operational' => 'Tous les systèmes opérationnels',
        'active_incidents' => '<span class="wt-attention-bold">{count}</span> incident(s) actif(s)',
    ],

    // Contracts card.
    'contracts' => [
        'metric_30d'     => '30 jours',
        'metric_90d'     => '90 jours',
        'metric_notices' => 'Préavis',
        'expiring'       => '<span class="wt-attention-bold">{count}</span> contrat(s) expirant sous 30 jours',
        'notices'        => '<span class="wt-attention-bold">{count}</span> période(s) de préavis approchant',
        'all_clear'      => 'Aucun contrat ne nécessite d\'attention',
    ],

    // Knowledge card.
    'knowledge' => [
        'overdue'         => '<span class="wt-attention-bold">{count}</span> article(s) en retard de révision',
        'published_week'  => 'Publiés cette semaine',
        'up_to_date'      => 'Base de connaissances à jour',
    ],

    // Assets card.
    'assets' => [
        'metric_total'    => 'Total',
        'metric_offline'  => 'Hors ligne',
        'metric_warranty' => 'Garantie',
        'warranty'        => '<span class="wt-attention-bold">{count}</span> actif(s) avec garantie expirée ou expirant sous {days} jours',
        'offline'         => '<span class="wt-attention-bold">{count}</span> actif(s) non vu(s) depuis 7 jours ou plus',
        'all_active'      => 'Tous les actifs récemment actifs',
    ],

    // Tasks card.
    'tasks' => [
        'metric_todo'   => 'À faire',
        'metric_active' => 'Actives',
        'overdue'       => '<span class="wt-attention-bold">{count}</span> tâche(s) en retard',
        'due_today'     => '<span class="wt-attention-bold">{count}</span> à échéance aujourd\'hui',
        'all_clear'     => 'Aucune tâche en retard',
    ],

    // Help guide.
    'help' => [
        'page_title'   => 'Guide Watchtower',
        'sidebar_label' => 'Guide',
        'hero_title'   => 'Guide Watchtower',
        'hero_subtitle' => 'Un tableau de bord d\'attention unifié présentant les éléments à traiter de chaque module en un seul coup d\'œil.',

        'nav_overview'  => 'Vue d\'ensemble',
        'nav_layout'    => 'La disposition du tableau de bord',
        'nav_dots'      => 'Comprendre les pastilles d\'état',
        'nav_cards'     => 'Les cartes des modules expliquées',
        'nav_refresh'   => 'Actualisation automatique',
        'nav_tips'      => 'Astuces rapides',

        // Section 1 — Overview
        's1_title' => 'Vue d\'ensemble',
        's1_intro' => 'Watchtower est votre point de vue unique sur les opérations informatiques. Au lieu d\'ouvrir chaque module individuellement pour vérifier les éléments urgents, Watchtower regroupe les informations les plus importantes de chaque module dans un seul tableau de bord. D\'un coup d\'œil, vous voyez ce qui nécessite de l\'attention, ce qui fonctionne sans accroc et où concentrer votre temps.',
        's1_feat1_title' => 'Tableau d\'attention',
        's1_feat1_desc'  => 'Voyez ce qui requiert votre attention dans tous les modules au même endroit. Vérifications du matin, tickets, changements, événements du calendrier, état des services, contrats, articles de connaissances et actifs sont tous résumés sur un seul écran.',
        's1_feat2_title' => 'État codé par couleur',
        's1_feat2_desc'  => 'Chaque carte de module affiche une pastille d\'état verte, orange ou rouge pour un triage instantané. Vous savez d\'un coup d\'œil quels domaines sont sains, lesquels nécessitent de l\'attention et lesquels exigent une action immédiate.',
        's1_feat3_title' => 'Actualisation automatique',
        's1_feat3_desc'  => 'Le tableau de bord s\'actualise automatiquement toutes les 5 minutes, de sorte que les informations restent à jour sans aucune action manuelle. Laissez Watchtower ouvert et il se tient à jour en arrière-plan.',
        's1_feat4_title' => 'Accès direct',
        's1_feat4_desc'  => 'Accédez directement à n\'importe quel module depuis sa carte. Chaque nom de module est un lien cliquable qui vous emmène directement vers la zone concernée, afin que vous puissiez agir sur les problèmes sans chercher la bonne page.',

        // Section 2 — Dashboard layout
        's2_title' => 'La disposition du tableau de bord',
        's2_p1' => 'Le tableau de bord Watchtower utilise une grille réactive de cartes de modules sur 3 colonnes. Sur les écrans plus petits, la grille s\'adapte à 2 colonnes ou à une seule colonne, afin de fonctionner sur tout appareil. Au-dessus de la grille se trouve la barre de titre avec un bouton d\'actualisation et un horodatage « Mis à jour » indiquant la dernière récupération des données.',
        's2_p2' => 'Chaque carte de la grille suit une structure cohérente pour que vous puissiez les parcourir rapidement :',
        's2_diagram_name'   => 'Nom du module',
        's2_diagram_open'   => 'OUVERTS',
        's2_diagram_active' => 'ACTIFS',
        's2_diagram_hold'   => 'EN ATTENTE',
        's2_diagram_clear'  => 'Tout est en ordre — aucun élément urgent',
        's2_field_icon'    => '<strong>Icône colorée</strong> &mdash; une petite icône carrée dans la couleur thématique du module (turquoise pour les Vérifications du matin, bleu pour les Tickets, etc.) afin d\'identifier chaque carte instantanément.',
        's2_field_name'    => '<strong>Nom du module</strong> &mdash; un lien cliquable qui mène directement à ce module. Cliquez pour y accéder directement et agir.',
        's2_field_dot'     => '<strong>Pastille d\'état</strong> &mdash; une pastille verte, orange ou rouge dans le coin supérieur droit indiquant le niveau d\'urgence global de ce module.',
        's2_field_metrics' => '<strong>Indicateurs clés</strong> &mdash; de grands nombres résumant les décomptes les plus importants (p. ex. tickets ouverts, vérifications terminées, contrats expirant).',
        's2_field_attention' => '<strong>Éléments d\'attention</strong> &mdash; des lignes de message codées par couleur mettant en évidence ce qui nécessite précisément votre attention au sein de ce module.',
        's2_tip' => 'La disposition des cartes est conçue pour le balayage rapide, pas pour l\'analyse approfondie. Utilisez Watchtower pour repérer quels modules requièrent votre attention, puis cliquez pour accéder au module lui-même afin d\'en obtenir tous les détails.',

        // Section 3 — Status dots
        's3_title' => 'Comprendre les pastilles d\'état',
        's3_intro' => 'Chaque carte de module affiche une pastille d\'état dans son en-tête. Cette pastille fournit un indicateur visuel instantané indiquant si ce domaine de vos opérations informatiques nécessite de l\'attention. La couleur est déterminée automatiquement à partir des données renvoyées par chaque module.',
        's3_green_label' => 'Vert',
        's3_green_desc'  => 'Tout va bien. Aucune action nécessaire. Le module est dans un état sain, sans problème en suspens ni élément nécessitant de l\'attention.',
        's3_green_examples' => '<strong>Exemples :</strong> Toutes les vérifications du matin réussies, aucun ticket urgent, tous les systèmes opérationnels, aucun contrat expirant prochainement.',
        's3_amber_label' => 'Orange',
        's3_amber_desc'  => 'Quelque chose nécessite de l\'attention mais sans être critique. Il y a des éléments à examiner dès que possible, mais rien d\'urgent.',
        's3_amber_examples' => '<strong>Exemples :</strong> Vérifications avec avertissements, tickets non assignés, changements en attente d\'approbation, contrats expirant sous 90 jours.',
        's3_red_label' => 'Rouge',
        's3_red_desc'  => 'Des éléments urgents requièrent une action immédiate. Quelque chose a échoué, est en retard ou est gravement impacté et doit être traité sur-le-champ.',
        's3_red_examples' => '<strong>Exemples :</strong> Vérifications du matin non commencées ou en échec, tickets urgents/haute priorité, pannes de service majeures, contrats expirant sous 30 jours.',
        's3_tip' => 'Considérez les pastilles comme un feu de circulation. Le vert signifie continuez votre journée, l\'orange signifie examinez dès que possible, et le rouge signifie arrêtez ce que vous faites et investiguez. L\'objectif est de garder toutes les pastilles vertes.',

        // Section 4 — Module cards explained
        's4_title' => 'Les cartes des modules expliquées',
        's4_intro' => 'Watchtower surveille huit modules. Chaque carte est adaptée pour afficher les informations les plus pertinentes pour ce domaine. Voici ce que chaque carte affiche et ce qui déclenche la couleur de sa pastille d\'état.',
        's4_mc_title'    => 'Vérifications du matin',
        's4_mc_desc'     => 'Affiche la progression de l\'achèvement (p. ex. 8/10 faites) ainsi que les décomptes des résultats OK, Avertissement et Échec. Les éléments d\'attention signalent lorsque les vérifications n\'ont pas été commencées ou lorsqu\'une a échoué.',
        's4_mc_triggers' => '<strong>Rouge :</strong> Vérifications non commencées aujourd\'hui, ou des vérifications en échec. <strong>Orange :</strong> Vérifications incomplètes ou avertissements présents. <strong>Vert :</strong> Toutes les vérifications terminées et réussies.',
        's4_tk_title'    => 'Tickets',
        's4_tk_desc'     => 'Affiche le nombre total ouvert ventilé en Nouveaux, Actifs et En attente. Les éléments d\'attention mettent en évidence les tickets urgents/haute priorité et ceux qui sont non assignés.',
        's4_tk_triggers' => '<strong>Rouge :</strong> Des tickets urgents ou haute priorité existent. <strong>Orange :</strong> Tickets non assignés présents. <strong>Vert :</strong> Aucun élément urgent ni ticket non assigné.',
        's4_ch_title'    => 'Changements',
        's4_ch_desc'     => 'Affiche le nombre de changements planifiés au cours des 7 prochains jours, combien sont actuellement en cours et combien sont en attente d\'approbation. Les éléments d\'attention signalent les changements non approuvés et actifs.',
        's4_ch_triggers' => '<strong>Orange :</strong> Changements en attente d\'approbation. <strong>Vert :</strong> Aucun changement non approuvé.',
        's4_cal_title'    => 'Calendrier',
        's4_cal_desc'     => 'Affiche le nombre d\'événements aujourd\'hui et cette semaine. S\'il y a des événements aujourd\'hui, ils sont listés avec leurs horaires (ou « Toute la journée » pour les événements sur la journée entière).',
        's4_cal_triggers' => '<strong>Orange :</strong> Événements planifiés pour aujourd\'hui. <strong>Vert :</strong> Aucun événement aujourd\'hui.',
        's4_ss_title'    => 'État des services',
        's4_ss_desc'     => 'Affiche le nombre d\'incidents actifs et liste les services affectés avec leurs badges de niveau d\'impact (Panne majeure, Panne partielle, Dégradé, Maintenance). Lorsque tout est sain, une bannière verte « Tous les systèmes opérationnels » apparaît.',
        's4_ss_triggers' => '<strong>Rouge :</strong> Panne majeure ou partielle sur un service. <strong>Orange :</strong> État dégradé ou en maintenance. <strong>Vert :</strong> Tous les systèmes opérationnels.',
        's4_ct_title'    => 'Contrats',
        's4_ct_desc'     => 'Affiche les contrats expirant sous 30 jours, sous 90 jours, et les périodes de préavis approchant. Les éléments d\'attention avertissent des expirations imminentes et des échéances de préavis à venir.',
        's4_ct_triggers' => '<strong>Rouge :</strong> Contrats expirant sous 30 jours. <strong>Orange :</strong> Contrats expirant sous 90 jours ou périodes de préavis approchant. <strong>Vert :</strong> Aucun contrat ne nécessite d\'attention.',
        's4_kb_title'    => 'Connaissances',
        's4_kb_desc'     => 'Affiche le nombre d\'articles en retard de révision et liste les articles récemment publiés cette semaine. Lorsqu\'aucune révision n\'est en retard et que la base de connaissances est à jour, la carte affiche un message « Tout est en ordre ».',
        's4_kb_triggers' => '<strong>Orange :</strong> Articles en retard de révision. <strong>Vert :</strong> Base de connaissances à jour.',
        's4_as_title'    => 'Actifs',
        's4_as_desc'     => 'Affiche le nombre total d\'actifs suivis et combien n\'ont pas été vus depuis 7 jours ou plus. Cela aide à repérer les appareils potentiellement hors ligne, désaffectés ou perdus.',
        's4_as_triggers' => '<strong>Orange :</strong> Actifs non vus depuis 7 jours ou plus. <strong>Vert :</strong> Tous les actifs récemment actifs.',

        // Section 5 — Auto-refresh
        's5_title' => 'Actualisation automatique et manuelle',
        's5_intro' => 'Watchtower est conçu pour être un outil de surveillance passif que vous pouvez laisser ouvert dans un onglet de navigateur tout au long de la journée. Le tableau de bord se tient à jour grâce à des cycles d\'actualisation automatiques.',
        's5_step1' => '<strong>Actualisation automatique</strong> &mdash; le tableau de bord récupère des données fraîches de tous les modules toutes les 5 minutes. Vous n\'avez pas besoin de recharger la page ni de cliquer sur quoi que ce soit ; les cartes et les pastilles d\'état se mettent à jour silencieusement en arrière-plan.',
        's5_step2' => '<strong>Actualisation manuelle</strong> &mdash; cliquez sur le bouton <strong>Actualiser</strong> dans le coin supérieur droit pour récupérer immédiatement les dernières données. L\'icône du bouton tourne pendant que la requête est en cours, confirmant que de nouvelles données sont en cours de chargement.',
        's5_step3' => '<strong>Horodatage de mise à jour</strong> &mdash; à côté du bouton d\'actualisation, un horodatage indique la dernière récupération des données (p. ex. « Mis à jour 09:15 »). Cela vous dit exactement à quel point les informations affichées sont récentes.',
        's5_tip' => 'Gardez Watchtower ouvert dans un onglet de navigateur dédié pour une surveillance passive. Le cycle d\'actualisation de 5 minutes signifie que vous disposez toujours d\'une vue quasi en temps réel de vos opérations informatiques sans avoir à vérifier manuellement chaque module.',

        // Section 6 — Quick tips
        's6_title' => 'Astuces rapides',
        's6_tip1_title' => 'Commencez votre journée ici',
        's6_tip1_desc'  => 'Ouvrez Watchtower en premier chaque matin pour un aperçu opérationnel rapide. En quelques secondes, vous voyez si les vérifications du matin sont faites, si des tickets sont urgents et si tous les services sont sains.',
        's6_tip2_title' => 'Les pastilles rouges d\'abord',
        's6_tip2_desc'  => 'Traitez les pastilles d\'état rouges avant toute autre chose. Elles indiquent des éléments urgents nécessitant une attention immédiate &mdash; vérifications en échec, tickets haute priorité ou pannes de service impactant activement les utilisateurs.',
        's6_tip3_title' => 'Cliquez pour y accéder',
        's6_tip3_desc'  => 'Cliquez sur n\'importe quel nom de module sur une carte pour naviguer directement vers ce module. Pas besoin d\'utiliser le menu principal ni la navigation en gaufre &mdash; Watchtower agit comme un raccourci direct vers l\'endroit où l\'attention est requise.',
        's6_tip4_title' => 'Actualisez pour les dernières données',
        's6_tip4_desc'  => 'Bien que le tableau de bord s\'actualise automatiquement toutes les 5 minutes, vous pouvez cliquer sur le bouton Actualiser à tout moment pour obtenir les toutes dernières données. Utile après avoir résolu un problème pour confirmer que la pastille d\'état a changé.',
        's6_tip5_title' => 'Utilisez-le en réunion d\'équipe',
        's6_tip5_desc'  => 'Projetez Watchtower sur un écran pendant les réunions debout ou les revues opérationnelles. Les pastilles codées par couleur facilitent la discussion sur les domaines nécessitant de l\'attention et l\'attribution de la responsabilité des éléments orange ou rouges.',
        's6_tip6_title' => 'Le vert signifie tout est en ordre',
        's6_tip6_desc'  => 'Lorsque chaque pastille du tableau de bord est verte, vos opérations informatiques sont en bonne forme. Aucun ticket urgent, aucune vérification en échec, aucun contrat expirant, et tous les services opérationnels. C\'est l\'objectif.',
    ],
];
