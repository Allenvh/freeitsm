<?php
/**
 * Français (fr) — Reporting module strings.
 * Missing keys fall back to lang/en/reporting.php per-key.
 */
return [
    'title' => 'Rapports',

    'nav' => [
        'logs'    => 'Journaux',
        'tickets' => 'Tickets',
        'intune'  => 'Intune',
        'help'    => 'Aide',

        'logs_title'    => 'Journaux système',
        'tickets_title' => 'Tableaux de bord des tickets',
        'intune_title'  => 'Tableau de bord Intune',
        'help_title'    => 'Aide',
    ],

    'landing' => [
        'heading'  => 'Rapports',
        'subtitle' => 'Choisissez un domaine de reporting pour commencer',

        'logs_title'    => 'Journaux système',
        'logs_desc'     => 'Consultez les tentatives de connexion, les imports d\'e-mails et autres journaux d\'activité système.',
        'tickets_title' => 'Tableaux de bord des tickets',
        'tickets_desc'  => 'Tableaux de bord KPI sur les performances des tickets, les délais de résolution et la charge de travail des équipes.',
        'intune_title'  => 'Tableau de bord Intune',
        'intune_desc'   => 'Conformité, chiffrement, répartition des OS, tendance des inscriptions et état de la dernière synchronisation sur chaque appareil géré.',
    ],

    'logs' => [
        'heading'  => 'Journaux système',
        'refresh'  => 'Actualiser',
        'tab_login'        => 'Connexions utilisateurs',
        'tab_email_import' => 'Imports d\'e-mails',

        'loading'        => 'Chargement des journaux...',
        'no_logs'        => 'Aucun journal trouvé',
        'load_error'     => 'Erreur lors du chargement des journaux : {error}',

        'col_datetime'    => 'Date/heure',
        'col_username'    => 'Nom d\'utilisateur',
        'col_status'      => 'Statut',
        'col_ip'          => 'Adresse IP',
        'col_user_agent'  => 'Agent utilisateur',
        'col_from'        => 'De',
        'col_subject'     => 'Objet',
        'col_type'        => 'Type',
        'col_attachments' => 'Pièces jointes',

        'status_success' => 'Réussite',
        'status_failed'  => 'Échec',
        'unknown'        => 'Inconnu',
        'no_subject'     => '(Sans objet)',
        'new_ticket'     => 'Nouveau ticket',
        'reply'          => 'Réponse',
        'none'           => 'Aucune',

        'row_title'  => 'Cliquez pour afficher les détails JSON',

        'pagination' => 'Page {current} sur {total} ({count} au total)',
        'prev'       => 'Précédent',
        'next'       => 'Suivant',

        'modal_title' => 'Détails du journal (JSON)',
        'close'       => 'Fermer',
    ],

    'tickets' => [
        'heading' => 'Tableaux de bord des tickets',
        'coming_soon' => 'Les tableaux de bord KPI et les rapports sur les performances des tickets, les délais de résolution et la charge de travail des équipes seront bientôt disponibles ici.',
    ],

    'intune' => [
        'heading'      => 'Tableau de bord Intune',
        'loading_meta' => 'Chargement…',
        'refresh'      => 'Actualiser',
        'refresh_title'=> 'Actualiser les données',
        'loading_data' => 'Chargement des données Intune…',

        'last_sync'    => 'Dernière synchronisation : {when}',
        'error'        => 'Erreur : {error}',
        'load_failed'  => 'Échec du chargement du tableau de bord : {error}',
        'no_devices_title' => 'Aucun appareil Intune trouvé.',
        'no_devices_body'  => 'Lancez une synchronisation Intune depuis le module Actifs pour importer des appareils, puis revenez ici.',
        'no_data'      => 'Aucune donnée',
        'unknown'      => 'Inconnu',

        // KPI strip
        'kpi_total'            => 'Total des appareils',
        'kpi_total_sub'        => 'Tous les appareils gérés',
        'kpi_compliant'        => 'Conformes',
        'kpi_compliant_sub'    => '{count} sur {total}',
        'kpi_encrypted'        => 'Chiffrés',
        'kpi_encrypted_sub'    => '{count} sur {total}',
        'kpi_stale'            => 'Obsolètes (30+ jours)',
        'kpi_stale_sub'        => 'Aucune synchronisation depuis 30 jours',
        'kpi_enrolled'         => 'Inscrits (30 jours)',
        'kpi_enrolled_sub'     => 'Nouveaux ces 30 derniers jours',

        'kpi_compliant_drill'  => 'Appareils conformes',
        'kpi_encrypted_drill'  => 'Appareils chiffrés',
        'kpi_stale_drill'      => 'Obsolètes (30+ jours)',
        'kpi_enrolled_drill'   => 'Inscrits ces 30 derniers jours',

        // Widgets
        'w_compliance_title'   => 'Répartition de la conformité',
        'w_compliance_desc'    => 'Appareils par état de conformité',
        'w_os_title'           => 'Système d\'exploitation',
        'w_os_desc'            => 'Appareils regroupés par OS',
        'w_owner_title'        => 'Type de propriétaire',
        'w_owner_desc'         => 'Appareils professionnels ou personnels',
        'w_manufacturers_title'=> 'Principaux fabricants',
        'w_manufacturers_desc' => 'Appareils par fabricant (top 10)',
        'w_os_versions_title'  => 'Principales versions d\'OS',
        'w_os_versions_desc'   => 'Combinaisons OS + version les plus courantes',
        'w_last_sync_title'    => 'Fenêtre de dernière synchronisation',
        'w_last_sync_desc'     => 'À quelle date les appareils se sont connectés',
        'w_enrolment_title'    => 'Inscriptions (90 derniers jours)',
        'w_enrolment_desc'     => 'Nouveaux appareils inscrits par jour',
        'w_encryption_title'   => 'Chiffrement par OS',
        'w_encryption_desc'    => 'Chiffrés ou non chiffrés, par OS',

        // Chart tooltips / labels
        'tooltip_enrolled'     => '{count} inscrit(s) (cliquez pour explorer)',
        'drill_enrolled_on'    => 'Inscrit le {date}',

        // Drill-down modal
        'drill_devices'        => 'Appareils',
        'drill_loading'        => 'Chargement…',
        'drill_count'          => '{count} appareil',
        'drill_count_plural'   => '{count} appareils',
        'drill_no_match'       => 'Aucun appareil ne correspond à ce filtre.',
        'drill_error'          => 'Erreur : {error}',
        'drill_load_failed'    => 'Échec du chargement : {error}',
        'drill_page_info'      => 'Page {current} sur {total}',
        'drill_prev'           => '‹ Préc.',
        'drill_next'           => 'Suiv. ›',
        'drill_export'         => 'Exporter en CSV',
        'drill_close'          => 'Fermer',

        'drill_col_device'     => 'Appareil',
        'drill_col_user'       => 'Utilisateur',
        'drill_col_os'         => 'OS',
        'drill_col_compliance' => 'Conformité',
        'drill_col_encrypted'  => 'Chiffré',
        'drill_col_last_sync'  => 'Dernière synchronisation',

        'never'                => 'Jamais',
        'yes'                  => 'Oui',
        'no'                   => 'Non',
    ],

    'help' => [
        'page_title' => 'Guide des rapports',
        'guide'      => 'Guide',

        'hero_heading' => 'Guide des rapports',
        'hero_sub'     => 'Transformez les données de votre centre de services en informations exploitables grâce aux journaux, aux analyses et aux tableaux de bord.',

        'nav_overview'           => 'Vue d\'ensemble',
        'nav_ticket_reports'     => 'Rapports sur les tickets',
        'nav_system_logs'        => 'Journaux système',
        'nav_understanding_data' => 'Comprendre les données',
        'nav_settings_filters'   => 'Paramètres et filtres',
        'nav_tips'               => 'Astuces rapides',

        // Section 1: Overview
        's1_heading' => 'Vue d\'ensemble',
        's1_intro'   => 'Le module Rapports rassemble en un seul endroit tout ce qui se passe dans votre centre de services. Suivez les performances des tickets, surveillez l\'activité système, examinez les tentatives de connexion et auditez les imports d\'e-mails — le tout depuis un module unique conçu pour vous aider à repérer les tendances et à prendre des décisions fondées sur les données.',
        's1_card1_title' => 'Analyse des tickets',
        's1_card1_body'  => 'Visualisez le volume de tickets, les délais de résolution, le respect des SLA et la charge de travail des équipes grâce à des tableaux de bord interactifs mis à jour en temps réel.',
        's1_card2_title' => 'Journaux système',
        's1_card2_body'  => 'Examinez chaque tentative de connexion, import d\'e-mail et événement système dans un tableau filtrable et consultable, avec horodatages et indicateurs de statut.',
        's1_card3_title' => 'Suivi de l\'activité',
        's1_card3_body'  => 'Surveillez l\'activité des analystes sur la plateforme — qui se connecte, quels tickets sont traités et où le temps est consacré.',
        's1_card4_title' => 'Piste d\'audit',
        's1_card4_body'  => 'Chaque action est enregistrée avec son auteur, sa date et ce qui a changé. Essentiel pour la conformité, les revues de sécurité et le dépannage.',

        // Section 2: Ticket reports
        's2_heading' => 'Rapports sur les tickets',
        's2_intro'   => 'La zone Tickets des rapports fournit des tableaux de bord KPI qui vous donnent une vision claire des performances de votre centre de services. Ces tableaux de bord puisent directement dans vos enregistrements de tickets et les présentent sous forme de graphiques et de cartes récapitulatives.',
        's2_card1_title' => 'Volume de tickets',
        's2_card1_body'  => 'Voyez combien de tickets sont créés, résolus et encore ouverts sur n\'importe quelle période. Identifiez les jours chargés et les tendances saisonnières.',
        's2_card2_title' => 'Respect des SLA',
        's2_card2_body'  => 'Suivez le pourcentage de tickets qui atteignent leurs objectifs de réponse et de résolution. Explorez par priorité ou catégorie pour trouver les zones problématiques.',
        's2_card3_title' => 'Délais de résolution',
        's2_card3_body'  => 'Mesurez le temps moyen et médian de résolution des tickets. Comparez entre équipes, catégories ou niveaux de priorité pour repérer les goulets d\'étranglement.',
        's2_card4_title' => 'Charge de travail des équipes',
        's2_card4_body'  => 'Voyez comment les tickets sont répartis entre les analystes. Identifiez qui est surchargé et qui a la capacité d\'en prendre davantage.',
        's2_card5_title' => 'Répartition par catégorie',
        's2_card5_body'  => 'Comprenez quels types de problèmes génèrent le plus de tickets. Utilisez ces données pour cibler la formation, la documentation ou les améliorations en libre-service.',
        's2_card6_title' => 'Analyse des tendances',
        's2_card6_body'  => 'Consultez les données des tickets sur des semaines, des mois ou des trimestres pour repérer les tendances de fond et mesurer l\'impact des améliorations de processus.',
        's2_tip'         => 'Les tableaux de bord des tickets sont accessibles via l\'onglet Tickets de la barre de navigation. Utilisez les filtres de plage de dates pour comparer différentes périodes côte à côte.',

        // Section 3: System logs
        's3_heading' => 'Journaux système',
        's3_intro'   => 'La zone Journaux capture tout ce qui se passe en coulisses dans votre instance FreeITSM. Chaque tentative de connexion, import d\'e-mail et événement système est enregistré avec un horodatage et un statut, pour que vous ayez toujours une vue complète de l\'activité de la plateforme.',
        's3_badge_login'  => 'CONNEXION',
        's3_badge_email'  => 'E-MAIL',
        's3_badge_system' => 'SYSTÈME',
        's3_badge_audit'  => 'AUDIT',
        's3_login_title'  => 'Tentatives de connexion',
        's3_login_body'   => 'Chaque connexion réussie ou échouée est enregistrée avec le nom de l\'analyste, l\'adresse IP et l\'horodatage. Les tentatives échouées sont signalées en rouge afin que vous puissiez rapidement repérer les accès non autorisés ou les utilisateurs verrouillés.',
        's3_email_title'  => 'Imports d\'e-mails',
        's3_email_body'   => 'Lorsque le système transforme les e-mails entrants en tickets, chaque import est enregistré avec l\'adresse de l\'expéditeur, l\'objet et la réussite ou non de la conversion. Les imports échoués affichent la raison afin que vous puissiez examiner les messages rejetés ou mal formés.',
        's3_system_title' => 'Événements système',
        's3_system_body'  => 'Les processus en arrière-plan, les tâches planifiées, les changements de configuration et l\'activité de l\'API sont tous capturés ici. Utilisez ces journaux pour vérifier que les tâches automatisées s\'exécutent correctement et pour diagnostiquer les problèmes.',
        's3_audit_title'  => 'Entrées d\'audit',
        's3_audit_body'   => 'Suivi des modifications au niveau des champs sur l\'ensemble de la plateforme. Voyez exactement qui a changé quoi, quand, et quelle était la valeur précédente. Inestimable pour les exigences de conformité et la résolution des litiges.',
        's3_step1_title' => 'Ouvrez l\'onglet Journaux',
        's3_step1_body'  => 'cliquez sur Journaux dans la barre de navigation pour accéder à la visionneuse de journaux système.',
        's3_step2_title' => 'Basculez entre les types de journaux',
        's3_step2_body'  => 'utilisez la barre d\'onglets en haut pour filtrer par tentatives de connexion, imports d\'e-mails ou événements système.',
        's3_step3_title' => 'Examinez les détails',
        's3_step3_body'  => 'chaque ligne affiche un horodatage, un badge de statut (réussite ou échec) et des détails contextuels tels que les adresses IP, les objets d\'e-mails ou les descriptions d\'événements.',
        's3_tip'         => 'Vérifiez régulièrement les journaux de connexion à la recherche de tentatives échouées répétées provenant d\'adresses IP inconnues. Cela peut indiquer des attaques par force brute ou des identifiants compromis nécessitant une attention immédiate.',

        // Section 4: Understanding the data
        's4_heading' => 'Comprendre les données',
        's4_intro'   => 'Les données brutes ne deviennent utiles que lorsque vous savez quoi rechercher. Voici les indicateurs clés à surveiller et comment les interpréter pour générer de réelles améliorations dans le fonctionnement de votre centre de services.',
        's4_metric1_title' => 'Délai de première réponse',
        's4_metric1_body'  => 'Le temps que les utilisateurs attendent avant qu\'un analyste prenne en compte leur ticket. Une tendance à la hausse signifie que votre équipe est peut-être en sous-effectif ou que les tickets ne sont pas acheminés efficacement. Objectif : sous votre seuil de SLA.',
        's4_metric2_title' => 'Taux de résolution',
        's4_metric2_body'  => 'Le pourcentage de tickets résolus sur une période donnée par rapport à ceux créés. Si plus de tickets entrent qu\'il n\'en sort, votre arriéré augmente et vous devez en rechercher la cause.',
        's4_metric3_title' => 'Contacts répétés',
        's4_metric3_body'  => 'Tickets rouverts ou utilisateurs signalant plusieurs fois le même problème. Des taux de contacts répétés élevés suggèrent que la cause racine n\'est pas traitée, ou que les solutions ne sont pas clairement communiquées.',
        's4_metric4_title' => 'Points chauds par catégorie',
        's4_metric4_body'  => 'Quelles catégories génèrent le plus de tickets au fil du temps. Un pic dans une catégorie particulière peut signaler un système défaillant, une mauvaise mise à jour logicielle ou une lacune dans la formation des utilisateurs à combler.',
        's4_combine'     => 'Utilisez ces indicateurs ensemble plutôt qu\'isolément. Par exemple, un taux de résolution élevé combiné à un taux de contacts répétés élevé peut indiquer que les tickets sont clôturés trop rapidement sans résoudre le problème sous-jacent.',
        's4_tip'         => 'Planifiez une revue hebdomadaire de vos indicateurs clés avec l\'équipe. Les schémas invisibles au quotidien deviennent souvent évidents lorsqu\'ils sont observés sur une cadence hebdomadaire ou mensuelle.',

        // Section 5: Settings & filters
        's5_heading' => 'Paramètres et filtres',
        's5_intro'   => 'La visionneuse de journaux et les tableaux de bord des tickets prennent tous deux en charge une série de filtres pour vous aider à cibler exactement les données dont vous avez besoin. Une utilisation efficace des filtres transforme un mur de données en informations ciblées et exploitables.',
        's5_step1_title' => 'Plages de dates',
        's5_step1_body'  => 'filtrez les journaux et les rapports sur une période précise. Utilisez des plages prédéfinies (aujourd\'hui, cette semaine, ce mois) ou définissez des dates de début et de fin personnalisées pour un contrôle précis.',
        's5_step2_title' => 'Filtres de statut',
        's5_step2_body'  => 'dans la visionneuse de journaux, filtrez par statut de réussite ou d\'échec pour isoler rapidement les problèmes. Dans les rapports sur les tickets, filtrez par statut ouvert, résolu ou fermé.',
        's5_step3_title' => 'Recherche',
        's5_step3_body'  => 'utilisez la zone de recherche pour trouver des entrées précises par mot-clé. Dans les journaux, cela recherche parmi les noms d\'analystes, les adresses IP, les objets d\'e-mails et les descriptions d\'événements.',
        's5_step4_title' => 'Regroupement temporel',
        's5_step4_body'  => 'dans les tableaux de bord des tickets, regroupez les données par jour, semaine ou mois pour modifier la granularité de vos graphiques. Les vues quotidiennes montrent les pics à court terme ; les vues mensuelles révèlent les tendances de fond.',
        's5_step5_title' => 'Filtres par service',
        's5_step5_body'  => 'limitez les résultats du tableau de bord à un service précis pour comparer les performances entre différentes parties de l\'organisation.',
        's5_tip'         => 'Combinez plusieurs filtres pour une analyse ciblée. Par exemple, filtrez par un service précis et une plage de dates pour voir comment un changement de processus récent a affecté le volume de tickets de cette équipe.',

        // Section 6: Quick tips
        's6_heading' => 'Astuces rapides',
        's6_tip1_title' => 'Examinez régulièrement',
        's6_tip1_body'  => 'Les rapports sont les plus utiles lorsqu\'ils sont examinés de manière régulière. Fixez une cadence — hebdomadaire pour les indicateurs opérationnels, mensuelle pour l\'analyse des tendances — et tenez-vous-y.',
        's6_tip2_title' => 'Examinez les anomalies',
        's6_tip2_body'  => 'Un pic ou une chute soudaine d\'un indicateur est un signal qui mérite d\'être examiné. Vérifiez les journaux pour le contexte — y a-t-il eu une panne système, un déploiement logiciel ou un changement d\'effectif ?',
        's6_tip3_title' => 'Comparez les périodes',
        's6_tip3_body'  => 'Utilisez les filtres de dates pour comparer cette semaine à la semaine dernière, ou ce mois au même mois l\'an dernier. Les comparaisons relatives révèlent une amélioration ou une régression plus clairement que les chiffres bruts.',
        's6_tip4_title' => 'Surveillez la sécurité',
        's6_tip4_body'  => 'Gardez un œil sur les tentatives de connexion échouées dans les journaux système. Des échecs répétés depuis la même adresse IP ou contre le même compte peuvent indiquer un problème de sécurité nécessitant une escalade.',
    ],
];
